#!/usr/bin/perl

use warnings;
use strict;
use lib "/usr/local/rrdtool/lib/perl";
use RRDs;
use POSIX qw(strftime :sys_wait_h);
use IO::Socket;
use DBI;
use Net::Server::Daemonize qw(daemonize);

##########CONFIGURATION SECTION##########

#location of data(rrd files) directory
my $datadir = "/var/rbping-server/data";

#location of log file
#my $logfile = "/usr/local/rbping-server/log/rbping-server.log";

##########END OF CONFIGURATION SECTION##########

daemonize(
 'root',                 # User
 'root',                 # Group
 '/var/run/rbping-server.pid' # Path to PID file - optional
);

my $localtime;

$SIG{CHLD} = "IGNORE";

#open (LOGFILE, "> $logfile") || die "Can't open $logfile: $!\n";

my $sock = new IO::Socket::INET(
                   LocalHost => '0.0.0.0',
                   LocalPort => 9274,
                   Proto     => 'tcp',
                   Listen    => SOMAXCONN,
                   Reuse     => 1);
$sock or die "no socket :$!";
STDOUT->autoflush(1);
my($new_sock, $buf, $child, $agent_id, $row, $dbh, $sth);
my $agent_hosts = '';
while (1) {
 $new_sock = $sock->accept() or die $!;
 next if $child = fork;
 die "fork: $!" unless defined $child;
 # child now...
 # close the server - not needed
 close $sock;
 $localtime=localtime;
# print LOGFILE "$localtime Connection from: ",$new_sock->peerhost," on port ", $new_sock->peerport,"\n";
 while (defined($buf = <$new_sock>)) {
  my $dbh = DBI->connect("DBI:mysql:database=rbping;host=localhost",
                          "rbping_master", $ENV{RBPING_DB_PASS} || 'CHANGEME',
                          {'RaiseError' => 1})
                          or die "Couldn't connect to database: " . DBI->errstr;
  my @buf =  split (/ /, $buf);
  if ($buf[0] eq "agent_id") {
   $agent_id = $buf[2];
   chop($agent_id);
   $localtime=localtime;
#   print LOGFILE "$localtime Agent $agent_id is connected\n";
   $sth = $dbh->prepare("SELECT host, ip_addr, protocol, port, tos FROM rbping_server WHERE agent_id = '$agent_id' AND 
                        enable = '1'");
   $sth->execute();
   while ( my @row = $sth->fetchrow_array ) {
    $row = "$row[0] $row[1] $row[2] $row[3] $row[4]";
    $agent_hosts = $agent_hosts . $row . ",";
   }
   print $new_sock $agent_hosts,"\n";
  } else { 
   rrd_update($dbh,$buf);
  }
 $dbh->disconnect();
 }
 $new_sock->close();
 exit;
} continue {
 #parent closes the client since it is not needed
 close $new_sock;
}

#########END OF MAIN##########

sub rrd_update {
 my ($sth);
 my $hostfile = '';
 my $dbh = shift;
 my $hostline = shift;
 chomp($hostline);
 my @hostline = split (/ /, $hostline);
 my $agent_id = $hostline[0];
 my $host = $hostline[1];
 my $loss = $hostline[2];
 my $rtt = $hostline[3];
 $sth = $dbh->prepare("SELECT protocol, port, tos FROM rbping_server WHERE agent_id = '$agent_id' AND
                       host = '$host'");
 $sth->execute();
 while ( my @row = $sth->fetchrow_array ) {
  if ($row[0] eq "icmp") {
   $hostfile = $host . "-" . $row[0] . "-" . $row[2];
  } else {
   $hostfile = $host . "-" . $row[0] . "-" . $row[1] . "-" . $row[2];
  }
 }    
 $localtime=localtime;
# print LOGFILE "$localtime $agent_id $host loss:$loss rtt:$rtt\n";
 if (! -e "$datadir/$agent_id") {
  mkdir("$datadir/$agent_id", 0755);
 }
 if (! -e "$datadir/$agent_id/$hostfile.rrd") {
  rrd_create($agent_id,$hostfile);
 }
 RRDs::update ("$datadir/$agent_id/$hostfile.rrd", "-t", "loss:rtt","N:$loss:$rtt",);
 my $ERR = RRDs::error;
# print LOGFILE "ERROR while updating $hostfile.rrd: $ERR\n" if $ERR;
 $localtime=localtime;
# print LOGFILE "$localtime $agent_id:$host updated\n";
}

sub rrd_create {
 my $agent_id = shift;  
 my $hostfile = shift;
 RRDs::create ("$datadir/$agent_id/$hostfile.rrd", "-s 60",
  "DS:loss:GAUGE:180:U:U",
  "DS:rtt:GAUGE:180:U:U",
  "RRA:LAST:0.5:1:525600",);
 my $ERR = RRDs::error;
 $localtime=localtime;
# print LOGFILE "$localtime ERROR while creating $hostfile.rrd: $ERR\n" if $ERR;
}
