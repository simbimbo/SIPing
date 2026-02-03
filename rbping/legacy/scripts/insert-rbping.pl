#!/usr/bin/perl

use strict;
use warnings;
use DBI;

my $swfile = "/home/rblock/scripts/switches.txt";
my $sth;

my $dbh = DBI->connect("DBI:mysql:database=rbping;host=10.5.56.16", "rbping_master", $ENV{RBPING_DB_PASS} || 'CHANGEME', {'RaiseError' => 1})
 or die "Couldn't connect to database: " . DBI->errstr;

(open(SWITCHES, "$swfile")) || die ("cannot open $swfile\n");

while (<SWITCHES>) {
 my @switch = split(/[ \t]+/, $_);
 my $hostname = $switch[0];
 my $ip_addr = $switch[1];
 chop($ip_addr);

 $dbh->do("INSERT INTO rbping_server(`agent_id`,`host`,`ip_addr`,`protocol`) VALUES('TBC-PGA-SW','$hostname','$ip_addr','icmp')");
# print "$hostname\n$ip_addr\n";
}
