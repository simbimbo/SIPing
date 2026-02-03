import os
import socket
import sqlite3
import time
from datetime import datetime

# Configuration section
datadir = "/var/rbping-server/data"
local_port = 9274

# Daemonize the process (optional)
# import daemonize
# daemonize.daemonize('/var/run/rbping-server.pid', 'root', 'root')

def handle_connection(conn):
    with conn:
        localtime = time.localtime()
        print(f"Connection from: {conn.getpeername()} at {time.strftime('%Y-%m-%d %H:%M:%S', localtime)}")

        while True:
            data = conn.recv(1024)
            if not data:
                break

            buf = data.decode('utf-8').split()
            if buf[0] == "agent_id":
                agent_id = buf[2].strip()
                print(f"Agent {agent_id} is connected")

                conn_db = sqlite3.connect('/path/to/rbping.db')  # Update this path to the actual database path
                cursor = conn_db.cursor()

                cursor.execute("SELECT host, ip_addr, protocol, port, tos FROM rbping_server WHERE agent_id = ? AND enable = 1", (agent_id,))
                agent_hosts = ','.join([f"{row[0]} {row[1]} {row[2]} {row[3]} {row[4]}" for row in cursor.fetchall()])

                conn.sendall(f"{agent_hosts}\n".encode('utf-8'))
                conn_db.close()
            else:
                conn_db = sqlite3.connect('/path/to/rbping.db')  # Update this path to the actual database path
                cursor = conn_db.cursor()
                rrd_update(cursor, data.decode('utf-8').strip())
                conn_db.close()

def rrd_update(cursor, hostline):
    localtime = time.localtime()
    hostline = hostline.strip().split()
    agent_id = hostline[0]
    host = hostline[1]
    loss = hostline[2]
    rtt = hostline[3]

    cursor.execute("SELECT protocol, port, tos FROM rbping_server WHERE agent_id = ? AND host = ?", (agent_id, host))
    protocol, port, tos = cursor.fetchone()

    if protocol == "icmp":
        hostfile = f"{host}-{protocol}-{tos}"
    else:
        hostfile = f"{host}-{protocol}-{port}-{tos}"

    if not os.path.exists(f"{datadir}/{agent_id}"):
        os.makedirs(f"{datadir}/{agent_id}", 0o755)

    if not os.path.exists(f"{datadir}/{agent_id}/{hostfile}.rrd"):
        rrd_create(agent_id, hostfile)

    os.system(f"rrdtool update {datadir}/{agent_id}/{hostfile}.rrd N:{loss}:{rtt}")
    print(f"{time.strftime('%Y-%m-%d %H:%M:%S', localtime)} {agent_id}:{host} updated")

def rrd_create(agent_id, hostfile):
    os.system(f"rrdtool create {datadir}/{agent_id}/{hostfile}.rrd --step 60 DS:loss:GAUGE:180:U:U DS:rtt:GAUGE:180:U:U RRA:LAST:0.5:1:525600")
    print(f"RRD file created: {datadir}/{agent_id}/{hostfile}.rrd")

def main():
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as server_sock:
        server_sock.bind(('0.0.0.0', local_port))
        server_sock.listen()
        print(f"Server listening on port {local_port}")

        while True:
            conn, addr = server_sock.accept()
            pid = os.fork()
            if pid == 0:
                server_sock.close()
                handle_connection(conn)
                os._exit(0)
            else:
                conn.close()

if __name__ == "__main__":
    main()
