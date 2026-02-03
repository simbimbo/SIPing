#!/usr/bin/perl

use warnings;
use strict;
use lib "/usr/local/rrdtool/lib/perl";
use RRDs;
use POSIX qw(strftime);
use Time::ParseDate;
use CGI::Pretty qw( :all );
use DBI;

my $cgi=new CGI;
my $url = $cgi->url;
my $datadir = "/var/rbping-server/data";
my $time_format = "%T %Z %D";
my $time_format2 = "%b %d,%Y %T %Z";
my $graph_height = 100;
my $graph_width = 700;
my $range = 3;
my $format = "hours";
my @colors = ("#FF0000","#00FF00","#0000FF","#FF00FF","#8A2BE2","#FF9900","#006633","#993300","#00FFFF");
my $agent_id = $cgi->param('agent');
my @hosts = $cgi->param('host');
my $dbh = DBI->connect("DBI:mysql:database=rbping;host=localhost",
                         "rbping_master", $ENV{RBPING_DB_PASS} || 'CHANGEME',
                         {'RaiseError' => 1})
                         or die "Couldn't connect to database: " . DBI->errstr;

my $agents_ref = $dbh->selectcol_arrayref("SELECT DISTINCT agent_id FROM rbping_server ORDER BY agent_id");
my $jscript = display_javascript();
$| = 1;

if (defined($cgi->param('graph')) && $cgi->param('graph') eq 'rtt') {
 rttgraph();
 exit 0;
}

if (defined($cgi->param('graph')) && $cgi->param('graph') eq 'loss') {
 lossgraph();
 exit 0;
}

if (defined($cgi->param('submit'))) {
 display_graphs();
 exit 0;
}

display_graphs();
exit 0;

sub display_javascript {
 my $jscript = "var agents = new Object()\n";
 foreach (@$agents_ref) {
  my $agent_id = $_;
  my $hosts_ref = $dbh->selectcol_arrayref("SELECT host FROM rbping_server WHERE agent_id = '$agent_id' AND enable = '1'
  ORDER BY host");
  my $hosts = join ",", map qq{"$_"}, @$hosts_ref;
  $jscript = $jscript . "agents.$_ = new Array($hosts)\n";
 }  
 my $jscript2 = <<EOF;

 function assign_list (mySelect, agent) {
  mySelect.options.length = 0; // Clear the list
      for (var i = 0; i < agents[agent].length; i++) {
          mySelect.options[i] = new Option
               (agents[agent][i], agents[agent][i])
      }
 } 

EOF

 $jscript = $jscript . $jscript2;
 $dbh->disconnect();
 return ($jscript);
}

sub display_graphs {
 my $host = $hosts[0];
 my $hostfile = get_hostfile($host);
 my $time = RRDs::last "$datadir/$agent_id/$hostfile.rrd";
 my ($start_time,$end_time) = graph_times();
 my $hosts_ref = $dbh->selectcol_arrayref("SELECT host FROM rbping_server WHERE agent_id = '$agent_id' AND enable = '1'
  ORDER BY host");
 $start_time = strftime($time_format, localtime($start_time));
 $end_time = strftime($time_format, localtime($end_time));
 my $hosts = join "+", @hosts; 
 $| = 1;
 print $cgi->header;
 print $cgi->start_html(-title=>"@hosts Network Graphs",-script=>$jscript);
  print $cgi->start_form(-name=>'grapher',-method=>'GET');
  print $cgi->start_table({-border=>'0',-cellspacing=>'5'});
   print $cgi->Tr();
    print $cgi->th({-align=>'left'},'Use Date',$cgi->checkbox(-name=>'use_date',-checked=>'0',-value=>'on',-label=>''),'&nbsp',
     'START',$cgi->textfield(-name=>'start',-value=>"$start_time",-size=>'20',-maxlength=>'24'),'&nbsp',
     'END',$cgi->textfield(-name=>'end',-value=>"$end_time",-size=>'20',-maxlength=>'24'));
  print $cgi->end_table;
  print $cgi->start_table({-border=>'0',-cellspacing=>'5'}); 
    print $cgi->Tr();
     print $cgi->th({-valign=>'top',-align=>'left'},'Graph for last',
      $cgi->textfield(-name=>'start2',-value=>"$range",-size=>'3',-maxlength=>'3'),                     
      $cgi->scrolling_list(-name=>'time_format',
                           -values=>['minutes','hours','days','weeks','months','years'],
                           -default=>"$format",-size=>'1'));
     print $cgi->th({-valign=>'top',-align=>'left'}, 
      $cgi->scrolling_list(-name=>'agent',-values=>[@$agents_ref],-size=>'4',
       -onChange=>'assign_list(document.grapher.host,document.grapher.agent.value)'),'&nbsp',
      $cgi->scrolling_list(-name=>'host',-values=>[@$hosts_ref],-size=>'4',-multiple=>'1'),'&nbsp',);
#     $cgi->popup_menu(-name=>'host',-values=>[@$hosts_ref]),
      print $cgi->th({-valign=>'top',-align=>'left'},$cgi->submit(-name=>'submit',-value=>'GRAPH'));
  print $cgi->end_table;
  print $cgi->br;
  if ((defined ($cgi->param('use_date'))) && $cgi->param('use_date') eq 'on') {
   print $cgi->img({-src=>"$url?agent=$agent_id&graph=rtt&host=$hosts&start=$start_time&end=$end_time"});
   print $cgi->img({-src=>"$url?agent=$agent_id&graph=loss&host=$hosts&start=$start_time&end=$end_time"});
  } else {
   print $cgi->img({-src=>"$url?agent=$agent_id&graph=rtt&host=$hosts&start2=$range&time_format=$format"});
   print $cgi->img({-src=>"$url?agent=$agent_id&graph=loss&host=$hosts&start2=$range&time_format=$format"});
  }
 print $cgi->end_form;
 print $cgi->end_html;
 $dbh->disconnect();
}

sub get_hostfile {
 my $hostfile;
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

sub graph_times {
 my ($s,$start_time,$end_time);
 my $current_time = time;
 if (defined($cgi->param('start2'))) {
  $range = $cgi->param('start2');
  $format = $cgi->param('time_format');
 }
 $s = ($range *60) if $format eq "minutes";
 $s = ($range *60*60) if $format eq "hours"; 
 $s = ($range *60*60*24) if $format eq "days";
 $s = ($range *60*60*24*7) if $format eq "weeks";
 $s = ($range *60*60*24*30) if $format eq "months";
 $s = ($range *60*60*24*356) if $format eq "years";
 $start_time = $current_time - $s;
 $end_time = $current_time;
 if (defined($cgi->param('start')) && defined($cgi->param('end'))) {
  $start_time = parsedate($cgi->param('start'));
  $end_time  = parsedate($cgi->param('end'));
 }
 return($start_time,$end_time);
}   

sub lossgraph {
 my ($title,$hostfile);
 my $hosts = $cgi->param('host');
 my @hosts = split(/ /, $hosts);
 my $hosts_count = @hosts;
 my ($start_time,$end_time) = graph_times();
 $hostfile = get_hostfile($hosts[0]);
 my $update_time = strftime($time_format, localtime(RRDs::last "$datadir/$agent_id/$hostfile.rrd"));
 my $stime  = strftime($time_format2, localtime($start_time));
 my $etime = strftime($time_format2, localtime($end_time));
 if ($hosts_count > 1 ) {
  $title = "Loss Graph from $stime to $etime";
 } else {
  $title = "@hosts Loss Graph from $stime to $etime"; 
 }
 my @args = (
             "-v percentage",
             "-w $graph_width",
             "-h $graph_height",
             "-s $start_time",
             "-e $end_time",
             "-t $title",
             "-a", "PNG", 
             "-l 0",
             "-u 100",
             "-r");
 my $x = 0;
 foreach(@hosts) {
  $hostfile = get_hostfile($_);
  @args = (@args,
           "DEF:loss$x=$datadir/$agent_id/$hostfile.rrd:loss:LAST",
           "LINE2:loss$x$colors[$x]:$_",
           "GPRINT:loss$x:LAST:latest Loss\\: %.0lf%%\\n");
  $x++;
 }
 print $cgi->header('image/png');
 RRDs::graph("-", @args);
 my $ERR = RRDs::error;
 print STDERR "ERROR while creating @hosts: $ERR\n" if $ERR;
}

sub rttgraph {
 my ($title,$hostfile);
 my $hosts = $cgi->param('host');
 my @hosts = split(/ /, $hosts);
 my $hosts_count = @hosts;
 my ($start_time,$end_time) = graph_times();   
 $hostfile = get_hostfile($hosts[0]);
 my $update_time = strftime($time_format, localtime(RRDs::last "$datadir/$agent_id/$hostfile.rrd"));
 my $stime  = strftime($time_format2, localtime($start_time));
 my $etime = strftime($time_format2, localtime($end_time));
 if ($hosts_count > 1 ) {
  $title = "Latency Graph from $stime to $etime";
 } else {
  $title = "@hosts Latency Graph from $stime to $etime";
 }
 my @args = ( 
             "-v milliseconds",
             "-w $graph_width",
             "-h $graph_height",
             "-s $start_time",
             "-e $end_time",
             "-t $title",
             "-a", "PNG");
 my $x = 0;
 foreach(@hosts) {
  $hostfile = get_hostfile($_);
  @args = (@args, 
           "DEF:rtt$x=$datadir/$agent_id/$hostfile.rrd:rtt:LAST",             
           "LINE2:rtt$x$colors[$x]:$_",
           "GPRINT:rtt$x:LAST:latest RTT\\: %.0lf%sms\\n");
  $x++;
 } 
 print $cgi->header('image/png'); 
 RRDs::graph("-", @args);
 my $ERR = RRDs::error;
 print STDERR "ERROR while creating @hosts: $ERR\n" if $ERR;
}
