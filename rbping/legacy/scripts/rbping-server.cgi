#!/usr/bin/perl

use warnings;
use strict;
use lib "/usr/local/rrdtool/lib/perl";
use RRDs;
use POSIX qw(strftime);
use Time::ParseDate;
use CGI::Pretty qw( :all );
use DBI;

my $dbh = DBI->connect("DBI:mysql:database=rbping;host=localhost",
                         "rbping_master", $ENV{RBPING_DB_PASS} || 'CHANGEME',
                         {'RaiseError' => 1})
                         or die "Couldn't connect to database: " . DBI->errstr;

my $datadir = "/var/rbping-server/data";
my $time_format = "%T %Z %D";
my $time_format2 = "%b %d,%Y %T %Z";
my $cgi=new CGI;
my $url = $cgi->url(-base => 1);
my $relative_url = $cgi->url(-relative=>1);
my ($sth,$port,$latency,$packet_loss,$style_latency,$style_pl,$style_update,$style_host,$style_ip,$agent_id,$search,$full_search);

$| = 1;

print $cgi->header;
print $cgi->start_html(-title=>'TBC Corp RBping Server',-head=>meta({-http_equiv=>'refresh',-content =>'60'}),-style=>{-src=>"$url/css/rbping-server.css",-type =>'text/css'});
print $cgi->start_form(-name=>'RBping Server',-method=>'GET');
print qq(<div id="container2">);
print qq(<div id="container1">);

print qq(<div id="col1">);
display_navigation();
print qq(</div>);

print qq(<div id="col2">);
if (defined($cgi->param('search'))) {
 $search = $cgi->param('text_search');
 display_table_search();
}
if (defined($cgi->param('agent'))) {
 $agent_id = $cgi->param('agent');
 display_table_agent();
}
if (defined($cgi->param('reports'))) {
 report_down_hosts();
}

print qq(</div>);

print qq(</div>);
print qq(</div>);
print $cgi->end_form;
print $cgi->end_html;


sub display_navigation {
 my @agents = @{$dbh->selectcol_arrayref("SELECT DISTINCT agent_id FROM rbping_server ORDER BY agent_id")};
 print $cgi->img({src=>"$url/icons/tbclogo.gif",alt=>'TBC Corp'}),br,br;
 print $cgi->textfield(-name=>'text_search',-size=>'20'),br;
 print $cgi->scrolling_list(-style=>'margin-top: 5px',-name=>'search_type',-values=>['contains','begins with','ends with'],-size=>'1'),br;
 print $cgi->submit(-style=>'margin-top: 5px',-name=>'search',-value=>'SUBMIT'),br;
 print $cgi->h2('AGENTS');
 foreach (@agents) {
  print $cgi->a({-href=>"$relative_url?agent=$_"},$_),br;
 }
 print $cgi->h2('REPORTS');
 print $cgi->a({-href=>"$relative_url?reports=down_hosts"},'DOWN HOSTS'),br;
}

sub display_table_agent {
 print $cgi->start_table({-class=>'grid',-border=>'1'});

 $sth = $dbh->prepare("SELECT host,ip_addr,protocol,port FROM rbping_server WHERE agent_id='$agent_id' AND enable=1 ORDER BY host");
 my @rows = int($sth->execute());

 print $cgi->Tr([
  $cgi->th({-colspan=>'7',-class=>'heading-large'},"TBC Corp - Agent $agent_id(@rows hosts)"),
  $cgi->th({-class=>'pad'},['HOST','IP Address','PROTOCOL','PORT','LATENCY(ms)','PACKET LOSS(%)','LAST UPDATE'])
 ]);

 while (my @hosts = $sth->fetchrow_array) {
  my $host = $hosts[0];
  my $ip_addr = $hosts[1];
  my $protocol = $hosts[2];
  $latency = "-";
  $packet_loss = "-";

  if ($protocol eq 'icmp') {
   $port = "-";
  } else {
   $port = $hosts[3];
  }
  my $hostfile = get_hostfile($agent_id, $host);
  my $last_update_time = (RRDs::last "$datadir/$agent_id/$hostfile.rrd");
  my $last_update_time_formated = strftime($time_format2, localtime(RRDs::last "$datadir/$agent_id/$hostfile.rrd"));
  
  if (time - $last_update_time >= 300) {
   $style_update = "high-alert";
  } else {
   $style_update = "all-clear";
  } 

  my ($start,$step,$names,$data) = RRDs::fetch
   ("$datadir/$agent_id/$hostfile.rrd","LAST","-s","$last_update_time - 60","-e","$last_update_time - 60");
  foreach my $line (@$data) {
   $packet_loss = sprintf('%0d',$$line[0]);
   $latency = sprintf('%0d',$$line[1]);
  } 

  if ($packet_loss < 20) {
   $style_pl = "all-clear";
  } elsif ( $packet_loss >= 20 && $packet_loss < 80) {
   $style_pl = "low-alert";
  } elsif ( $packet_loss >= 80) {
   $style_pl = "high-alert";
  } else {
   $style_pl = "inherit";
  }
 
  if ($latency < 500) {
   $style_latency = "all-clear";
  } elsif ($latency >=500 && $latency < 1000) {
   $style_latency = "low-alert";
  } elsif ( $latency >=1000 ) {
   $style_latency = "high-alert";
  } else {
   $style_latency = "inherit";
  }

  if ($packet_loss == 100) {
   $latency = "-";
   $style_latency = "high-alert";
  }

  print $cgi->Tr({-align=>'center'},
   $cgi->td([$cgi->a({-href=>"javascript:",
    -onClick=>"window.open('$url/grapher.cgi?agent=$agent_id&host=$host',
    '_blank','width=850,height=550,resizable=no,scrollbars=no')",-class=>'pad'},$host),
    $ip_addr,$protocol,$port]),
   $cgi->td({-class=>$style_latency},$latency),
   $cgi->td({-class=>$style_pl},$packet_loss),
   $cgi->td({-class=>$style_update},$last_update_time_formated)
  );
 }
} 

sub display_table_search {
 print $cgi->start_table({-class=>'grid',-border=>'1'});

 if ($cgi->param('search_type') eq 'contains') {
  $full_search = "%" . $search . "%";
 }
 if ($cgi->param('search_type') eq 'begins with') {
  $full_search = $search . "%";
 }
 if ($cgi->param('search_type') eq 'ends with') {
  $full_search = "%" . $search;      
 }

 $sth = $dbh->prepare("SELECT agent_id,host,ip_addr,protocol,port FROM rbping_server WHERE (host LIKE '$full_search' OR ip_addr LIKE '$full_search') AND enable=1 ORDER BY host");
 my @rows = int($sth->execute());

 print $cgi->Tr([
  $cgi->th({-colspan=>'8',-class=>'heading-large'},"TBC Corp - Search Results for $search(@rows hosts)"),
  $cgi->th({-class=>'pad'},['AGENT','HOST','IP Address','PROTOCOL','PORT','LATENCY(ms)','PACKET LOSS(%)','LAST UPDATE'])
 ]);

 while (my @hosts = $sth->fetchrow_array) {
  my $agent_id = $hosts[0]; 
  my $host = $hosts[1];
  my $ip_addr = $hosts[2];
  my $protocol = $hosts[3];
  if ($protocol eq 'icmp') {
   $port = "-";
  } else {
   $port = $hosts[4];
  }
  my $hostfile = get_hostfile($agent_id,$host);
  my $last_update_time = (RRDs::last "$datadir/$agent_id/$hostfile.rrd");
  my $last_update_time_formated = strftime($time_format2, localtime(RRDs::last "$datadir/$agent_id/$hostfile.rrd"));
  
  if (time - $last_update_time >= 300) {
   $style_update = "high-alert";
  } else {
   $style_update = "all-clear";
  } 

  my ($start,$step,$names,$data) = RRDs::fetch
   ("$datadir/$agent_id/$hostfile.rrd","LAST","-s","$last_update_time - 60","-e","$last_update_time - 60");
  foreach my $line (@$data) {
   $packet_loss = sprintf('%0d',$$line[0]);
   $latency = sprintf('%0d',$$line[1]);
  } 

  if ($packet_loss < 20) {
   $style_pl = "all-clear";
  } elsif ( $packet_loss >= 20 && $packet_loss < 80) {
   $style_pl = "low-alert";
  } elsif ( $packet_loss >= 80) {
   $style_pl = "high-alert";
  } else {
   $style_pl = "inherit";
  }
 
  if ($latency < 500) {
   $style_latency = "all-clear";
  } elsif ($latency >=500 && $latency < 1000) {
   $style_latency = "low-alert";
  } elsif ( $latency >=1000 ) {
   $style_latency = "high-alert";
  } else {
   $style_latency = "inherit";
  }

  if ($packet_loss == 100) {
   $latency = "-";
   $style_latency = "high-alert";
  }

 # if (

  print $cgi->Tr({-align=>'center'},
   $cgi->td([$agent_id,$cgi->a({-href=>"javascript:",
    -onClick=>"window.open('$url/grapher.cgi?agent=$agent_id&host=$host',
    '_blank','width=850,height=550,resizable=no,scrollbars=no')",-class=>'pad'},$host),
    $ip_addr,$protocol,$port]),
   $cgi->td({-class=>$style_latency},$latency),
   $cgi->td({-class=>$style_pl},$packet_loss),
   $cgi->td({-class=>$style_update},$last_update_time_formated)
  );
 }
} 

sub get_hostfile {
 my $hostfile;
 my $agent_id = shift;
 my $host = shift;
 my $sth = $dbh->prepare("SELECT protocol, port, tos FROM rbping_server WHERE agent_id = '$agent_id' AND 
  host = '$host'");
 $sth->execute();
 while ( my @row = $sth->fetchrow_array ) { 
  if ($row[0] eq "icmp") {
   $hostfile = $host . "-" . $row[0] . "-" . $row[2];
  } else {
   $hostfile = $host . "-" . $row[0] . "-" . $row[1] . "-" . $row[2];
  }
 }
 return($hostfile);
} 

sub report_down_hosts {
 my $style = "high-alert";
 print $cgi->start_table({-class=>'grid',-border=>'1'});

 $sth = $dbh->prepare("SELECT agent_id,host,ip_addr,protocol,port FROM rbping_server WHERE enable=1 ORDER BY host");
 my @rows = int($sth->execute());

 print $cgi->Tr([
  $cgi->th({-colspan=>'8',-class=>'heading-large'},"TBC Corp - DOWN HOSTS"),
  $cgi->th({-class=>'pad'},['AGENT','HOST','IP Address','PROTOCOL','PORT','LATENCY(ms)','PACKET LOSS(%)','LAST UPDATE'])
 ]);

 while (my @hosts = $sth->fetchrow_array) {
  my $agent_id = $hosts[0];
  my $host = $hosts[1];
  my $ip_addr = $hosts[2];
  my $protocol = $hosts[3];
  if ($protocol eq 'icmp') {
   $port = "-";
  } else {
   $port = $hosts[4];
  }
  my $hostfile = get_hostfile($agent_id,$host);
  my $last_update_time = (RRDs::last "$datadir/$agent_id/$hostfile.rrd");
  my $last_update_time_formated = strftime($time_format2, localtime(RRDs::last "$datadir/$agent_id/$hostfile.rrd"));

  my $host_down = 0;  
  my ($start,$step,$names,$data) = RRDs::fetch
   ("$datadir/$agent_id/$hostfile.rrd","LAST","-s","$last_update_time - 3600","-e","$last_update_time - 60");
  foreach my $line (@$data) {
   if ($$line[0] <= 80) {
    $host_down = 0; 
    last;
   } else {
    $host_down = 1;
   }
  }

  if ($host_down == 1) {
   print $cgi->Tr({-align=>'center'},
    $cgi->td([$agent_id,$cgi->a({-href=>"javascript:",
     -onClick=>"window.open('$url/grapher.cgi?agent=$agent_id&host=$host',
     '_blank','width=850,height=550,resizable=no,scrollbars=no')",-class=>'pad'},$host),
     $ip_addr,$protocol,$port]),
    $cgi->td('-'),
    $cgi->td({-class=>$style},'100'),
    $cgi->td($last_update_time_formated)
   );
  } 
 }
}
