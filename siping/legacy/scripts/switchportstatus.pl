#!/usr/bin/perl

use strict;
use warnings;

my $swfile = "/home/rblock/scripts/switches.txt";


(open(SWITCHES, "$swfile")) || die ("cannot open $swfile\n");

while (<SWITCHES>) {
 my $switch = $_;
 chop($switch);
 my $swstatusfile = "/home/network/info/switch/$switch/intstatus.txt";
# (open(STATUS, "$swstatusfile")) || die ("cannot open $swstatusfile\n");
 (open(STATUS, "$swstatusfile"));
 while (<STATUS>) {
  my @port = split(/[ \t]+/, $_);
  my $port_number = $port[0];
  if ($port_number =~ /Fa0\/39|Fa0\/4[0-8]/) {
   if ($_ =~/connected/) {
    print "$switch port $port_number is connected\n";
   }
  }
 }
 close(STATUS);
}
close(SWITCHES);
