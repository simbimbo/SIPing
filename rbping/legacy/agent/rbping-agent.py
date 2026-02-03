import os
import socket
import subprocess
import time

# Configuration section
num_ping = 10
agent_id = "TBC-PGA"
agent_version = "1.0"
source_address = os.getenv("RBPING_SOURCE_ADDRESS", "0.0.0.0")
server = os.getenv("RBPING_SERVER", "127.0.0.1")
server_port = 9274
tmp_file = "/usr/local/rbping-agent/tmp.txt"

# Establish a TCP connection
sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
sock.settimeout(5)
sock.connect((server, server_port))

sock.sendall(f"agent_id = {agent_id}\n".encode('utf-8'))
host = sock.recv(1024).decode('utf-8').strip()
hosts = host.split(',')

with open(tmp_file, 'w') as tmp_file_handle:
    pass

def ping(hostline):
    host = hostline[0]
    ip = hostline[1]
    protocol = hostline[2]
    port = int(hostline[3])
    tos = int(hostline[4])

    loss = 0
    total_rtt = 0
    avg_rtt = "U"
    n = 0
    y = 0

    for _ in range(num_ping):
        result = subprocess.run(["ping", "-c", "1", ip], stdout=subprocess.PIPE)
        if result.returncode == 0:
            rtt = float(result.stdout.decode('utf-8').split('time=')[1].split(' ms')[0])
            total_rtt += rtt
            n += 1
            y = 0
        else:
            y += 1
        
        if y == 5:
            break
        
        time.sleep(0.25)

    loss = (num_ping - n) / num_ping * 100
    if loss != 100:
        avg_rtt = round(total_rtt / n)

    return host, loss, avg_rtt

for host_entry in hosts:
    hostline = host_entry.split()
    pid = os.fork()
    if pid == 0:  # Child process
        host, loss, avg_rtt = ping(hostline)
        with open(tmp_file, 'a') as tmp_file_handle:
            tmp_file_handle.write(f"{host} {loss} {avg_rtt}\n")
        os._exit(0)

while True:
    try:
        os.wait()
    except ChildProcessError:
        break

with open(tmp_file, 'r') as tmp_file_handle:
    for line in tmp_file_handle:
        sock.sendall(f"{agent_id} {line}".encode('utf-8'))

os.remove(tmp_file)
sock.close()
