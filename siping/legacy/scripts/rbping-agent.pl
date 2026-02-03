#!/usr/bin/perl

use strict;
use warnings;
use Net::Ping;
use Time::HiRes qw ( sleep );
use IO::Socket;
use Proc::Queue size => 30;

##########CONFIGURATION SECTION##########

#number of pings
my $num_ping = 10;

#agent name
my $agent_id = "TBC-PGA";

#agent version
my $agent_version = "1.0";

#source address
my $source_address = $ENV{RBPING_SOURCE_ADDRESS} || "0.0.0.0";

#server
my $server = $ENV{RBPING_SERVER} || "127.0.0.1";

#server port
my $server_port = 9274;

#temp file
my $tmp_file = "/usr/local/rbping-agent/tmp.txt";

##########END OF CONFIGURATION SECTION##########


my $sock = new IO::Socket::INET(
                   PeerAddr => $server,
                   PeerPort => $server_port,
                   Proto    => 'tcp',
                   Timeout  => '5');  
 $sock or die "no socket :$!";

print $sock "agent_id = $agent_id\n";
my $host = <$sock>;
chop($host);
my @hosts = split (/,/, $host);

open (TMP_FILE, "> $tmp_file") || die ("cannot open file $tmp_file: $!\n");
close TMP_FILE;

foreach (@hosts) {
 $SIG{CHLD} = \&REAPER;
 my @hostline = split (/[\t ]+/, $_);
 my $child = fork();
  if ($child == 0) { #Child
   my ($host, $loss, $avg_rtt) = ping(@hostline);
   open (TMP_FILE, ">> $tmp_file") || die ("cannot open file $tmp_file\n");
   print TMP_FILE "$host $loss $avg_rtt\n";
   exit 0;
  }
}

1 while wait() > 0;

close TMP_FILE;
open (TMP_FILE, "$tmp_file") || die ("cannot open file $tmp_file: $!\n");
while (<TMP_FILE>) {
 print $sock "$agent_id $_";
}
close TMP_FILE;
unlink $tmp_file;
close $sock;
exit 0;

sub ping {
 my @hostline = @_;
 my ($loss, $rtt, $x);
 my $host = $hostline[0];
 my $ip = $hostline[1];
 my $protocol = $hostline[2];
 my $port = $hostline[3];
 my $tos = $hostline[4];
 my $n = 0;
 my $y = 0;
 my $total_rtt = 0;
 my $avg_rtt = "U";
 my $p = Net::Ping->new($protocol, 2, 56, 0, $tos);
 $p->bind($source_address) if defined $source_address;
 if ($protocol eq "tcp" || "udp") {
  $p->{port_num} = $port;
 }
 $p->hires(1);
 $p->service_check(1);
 for ( $x=1; $x <= $num_ping && $y != 5; $x++ ) {
  my @result = $p->ping($ip);
  if ($result[0] == 1) {
   $rtt = sprintf("%.3f", $result[1]) * 1000;
   $total_rtt = $total_rtt + $rtt;
   $n = $n + 1;
   $y = 0;
  } else {
   $y = $y +1;
  }
  sleep (.25);
 }
 $p->close();
 $loss = ($num_ping - $n) / $num_ping * 100;
 if ($loss != 100) {
 $avg_rtt = sprintf("%.0f", ($total_rtt / $n));
 #print "$hostline[0] avg latency is ",$avg_rtt,"ms\n";
 }
 #print $sock "$hostline[0] packet loss is ",$loss,"%\n"; 
 return ($host, $loss, $avg_rtt);
}

sub REAPER {
 $SIG{CHLD} = \&REAPER;  # loathe sysV
 my $waitedpid = wait;
}
