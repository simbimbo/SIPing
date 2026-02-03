#!/usr/bin/perl

use warnings;
use strict;
use lib "/usr/local/rrdtool/lib/perl";
use RRDs;

my $latency;

my $hostfile = "hosts2";
(open(HOSTFILE, "$hostfile")) || die ("cannot open $hostfile\n");

while (<HOSTFILE>) {
 my $host = $_;
 chomp ($host);
 my $file = `ls -1 /var/rbping-server/data/*/*$host*`;
 chomp($file);
 my $last_update_time = (RRDs::last $file);

 my $n = 0;
 my $total_latency = 0;
 
 my ($start,$step,$names,$data) = RRDs::fetch
  ($file,"LAST","-s","$last_update_time - 30d","-e","$last_update_time - 60");
 foreach my $line (@$data) { 
  $latency = sprintf('%0d',$$line[1]);   
  if (defined $latency) {
   $n = $n + 1;
   $total_latency = $total_latency + $latency;   
  }
 } 

 print "$host - ";
 print sprintf('%0d',$total_latency/$n),"\n";
}

close(HOSTFILE);
