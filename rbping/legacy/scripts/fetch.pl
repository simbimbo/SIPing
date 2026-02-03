#!/usr/bin/perl

use warnings;
use strict;
use lib "/usr/local/rrdtool/lib/perl";
use RRDs;

my ($sth,$port,$latency,$packet_loss);
my $file = "/var/rbping-server/data/TBC-PGA-Retail-1/TBC-001-2911R-01-wan-icmp-0.rrd";


my $last_update_time = (RRDs::last $file);
 print "$last_update_time\n";
 
 my ($start,$step,$names,$data) = RRDs::fetch
  ($file,"LAST","-s","$last_update_time - 60","-e","$last_update_time - 60");
 foreach my $line (@$data) { 
  $packet_loss = sprintf('%0d',$$line[0]);    
  $latency = sprintf('%0d',$$line[1]);   
 } 
print "$packet_loss\n$latency\n";
