#!/usr/bin/perl

use warnings;
use strict;
use lib "/usr/local/rrdtool/lib/perl";
use RRDs;
use Net::SMTP;
use POSIX qw(strftime :sys_wait_h);
use Time::ParseDate;
use IO::Socket;
use DBI;
use MIME::Lite;
use CGI::Pretty qw( :all );

##########CONFIGURATION SECTION##########

#data directory
my $datadir = "/var/rbping-server/data";

#tmp directory
my $tmpdir = "/var/rbping-server/tmp";

#smtp servers
my $smtp_server_r = ["10.8.69.42"];

#smtp from address
my $from_addr = "rbping-server\@tbccorp.com";

#smtp to address
my @to_addr = ("rblock\@tbccorp.com");
#my @to_addr = ("wanadmin\@citco.com");

my $graph_height = 100;
my $graph_width = 700;

my $url = "http://rbping-server.network.tbccorp.com/grapher.cgi?agent=";

##########END OF CONFIGURATION SECTION##########

my $cgi=new CGI;
#my $time_format = "%D %T %Z";
my $time_format = "%Y-%m-%d %H:%M:%S";
my $time_format2 = "%b %d,%Y %T %Z";
my $dbh = DBI->connect("DBI:mysql:database=rbping;host=localhost",
                       "rbping_master", $ENV{RBPING_DB_PASS} || 'CHANGEME',
                       {'RaiseError' => 1})
                       or die "Couldn't connect to database: " . DBI->errstr;

##Get previous down hosts count
my $sth = $dbh->prepare("SELECT agent_id, host FROM rbping_outages WHERE up_date_time IS NULL");
my $previous_down_hosts_count = $sth->execute();
$sth->finish();

##Mark down alert sent for down hosts
down_alert_sent();

##Check to see if down hosts are up
check_down_hosts();

##Find new down hosts
find_down_hosts();

#send_outage_alert();

$dbh->disconnect();

#############END OF MAIN###############

sub check_down_hosts { 
 my $sth = $dbh->prepare("SELECT agent_id, host FROM rbping_outages WHERE up_date_time IS NULL");
 $sth->execute();
 while ( my @row = $sth->fetchrow_array ) {
  my $agent_id = $row[0];
  my $host = $row[1];
  my $hostfile = get_hostfile($agent_id,$host);
  my $x = 1;
  my $up_polling_cycle_r = $dbh->selectcol_arrayref("SELECT up_polling_cycle FROM rbping_server WHERE agent_id = '$agent_id' 
   AND host = '$host'");
  my $polling_time = ($$up_polling_cycle_r[0] * 60) + 60;
  my $time = RRDs::last "$datadir/$agent_id/$hostfile.rrd";
  my ($start,$step,$names,$data) = RRDs::fetch
   ("$datadir/$agent_id/$hostfile.rrd","LAST","-s","$time - $polling_time","-e","$time - 60");
  foreach my $line (@$data) {
   if ($$line[0] >= 80) {
    $x = 1;
    last;  
   } else {
    $x = 0;
   }
  }
  if ($x == 0) {
   #print "$host is up\n";
   db_update($agent_id,$host,$$up_polling_cycle_r[0]);
   #individual_up_alert_message_body($agent_id,$host);
  }
 }
}

sub db_insert {
 my $agent_id = shift;
 my $host = shift;
 my $polling_cycles = shift;
 my $down_time = time - ($polling_cycles * 60);
 my $display_down_time = strftime($time_format, localtime($down_time));
 $dbh->do("INSERT INTO rbping_outages(agent_id,host,down_date_time,down_date_time_unixtime) VALUES ('$agent_id','$host','$display_down_time','$down_time')");
}

sub db_update {
 my $agent_id = shift;
 my $host = shift;
 my $up_polling_cycle = shift;
 my $down_date_time_ref = $dbh->selectcol_arrayref("SELECT down_date_time FROM rbping_outages WHERE agent_id = '$agent_id'
  AND host = '$host' AND up_date_time IS NULL");
 my $down_time = parsedate($$down_date_time_ref[0]);
 my $up_time = time - ($up_polling_cycle * 60);
 my $display_up_time = strftime($time_format, localtime($up_time));
 my $total_sec = $up_time - $down_time;
 my $total_down_time = total_outage($total_sec);
 $dbh->do("UPDATE rbping_outages SET up_date_time = '$display_up_time', up_date_time_unixtime = '$up_time', total_downtime = '$total_down_time', up_alert_sent = '1' 
  WHERE agent_id = '$agent_id' AND host = '$host' AND up_date_time IS NULL");
 individual_up_alert_message_body($agent_id,$host,$total_down_time);
}

sub down_alert_sent {
 $dbh->do("UPDATE rbping_outages SET down_alert_sent = '1' WHERE down_alert_sent = '0'");
}

sub down_message_body {
 my @down_hosts = @_;
 my $down_hosts = '';
 my $time = strftime($time_format, localtime());
 foreach (@down_hosts) {
  my $host = $_;
  my $down_date_time_ref = $dbh->selectcol_arrayref("SELECT down_date_time FROM rbping_outages WHERE host = '$host' AND
   up_date_time IS NULL");
  my $down_time = parsedate($$down_date_time_ref[0]);
  my $current_time = time;
  my $total_sec = $current_time - $down_time;
  my $total_down_time = total_outage($total_sec);
  $down_hosts = $down_hosts . $host . " \($total_down_time\)" . $cgi->br();
 } 
 my $mime_subject = "Down Hosts";
 my $mime_data = $cgi->start_html() . 
  $cgi->p('The following hosts are down',br,br,
   $down_hosts,br,
   'at ',$time,br,
   '*****') . $cgi->end_html(); 

 mime_mailer($mime_subject,$mime_data);
}


sub find_down_hosts {
 my $sth = $dbh->prepare("SELECT agent_id, host, polling_cycles FROM rbping_server WHERE enable = '1' AND down_enable = '1'");
 $sth->execute();
 while ( my @row = $sth->fetchrow_array ) {
  my $agent_id = $row[0];
  my $host = $row[1];
  my $hostfile = get_hostfile($agent_id,$host);
  my $host_down = 0;
  my $polling_cycles = $row[2];
  my $polling_time = ($polling_cycles * 60) + 60;
  my $time = RRDs::last "$datadir/$agent_id/$hostfile.rrd";
  my ($start,$step,$names,$data) = RRDs::fetch
   ("$datadir/$agent_id/$hostfile.rrd","LAST","-s","$time - $polling_time","-e","$time - 60");
  foreach my $line (@$data) {
   if ($$line[0] <= 80) {
    $host_down = 0; 
    last;
   } else {
    $host_down = 1;
   }
  }
  if ($host_down == 1) {
  # print "$host is down\n";
   my $host_down_already_exists = $dbh->do("SELECT host FROM rbping_outages WHERE agent_id = '$agent_id' and 
    host = '$host' and up_date_time IS NULL");
   if ($host_down_already_exists == 0) {
    db_insert($agent_id,$host,$polling_cycles);
    individual_down_alert_message_body($agent_id,$host);
   } else {
    #print "$host already exists in outages db\n";
   } 
  }
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

sub individual_down_alert_message_body {
 my $to_addr;
 my $agent_id = shift;
 my $host = shift;
 my $time = strftime($time_format, localtime());
 my $timezone = strftime("%Z", localtime());
 my $mime_subject = "$host Down Alert";
 my $grapher_url = $url . $agent_id . 
  '&host=' . $host;
 my $notes_ref = $dbh->selectcol_arrayref("SELECT notes FROM rbping_server WHERE agent_id = '$agent_id' and
    host = '$host'");
 my $mime_data = $cgi->start_html() . 
  $cgi->p(b('Host: '),$host,br,
  b('Alert: '),$host,' is down',br,
  b('Time: '),$time . ' ' . $timezone,br,
  b('Notes: '),$$notes_ref[0],br,br,
  b('URL: '),a({-href=>$grapher_url},$grapher_url),br,br,
  img({-src=>'cid:packetloss_graph'})) . 
  $cgi->end_html(); 

 my $sth = $dbh->prepare("SELECT email1,email2,email3,email4 FROM rbping_server WHERE agent_id = '$agent_id' AND
  host = '$host' AND (email1 IS NOT NULL OR email2 IS NOT NULL OR email3 IS NOT NULL OR email4 IS NOT NULL)");
 my $size = $sth->execute();
 if ($size > 0) {
  foreach ( my @row = $sth->fetchrow_array ) {
   if (defined $_) { $to_addr = $_ . "," . $to_addr; }
  }
  chop($to_addr);
 lossgraph($agent_id,$host);
 mime_mailer_individual_alert($mime_subject,$mime_data,$host,$to_addr);
 }
}

sub individual_up_alert_message_body {
 my $to_addr;
 my $agent_id = shift; 
 my $host = shift;
 my $total_down_time = shift;
 my $time = strftime($time_format, localtime());
 my $timezone = strftime("%Z", localtime());
 my $mime_subject = "$host Up Alert";
 my $grapher_url = $url . $agent_id .
  '&host=' . $host;
 my $notes_ref = $dbh->selectcol_arrayref("SELECT notes FROM rbping_server WHERE agent_id = '$agent_id' and
  host = '$host'");             
 my $mime_data = $cgi->start_html() .
  $cgi->p(b('Host: '),$host,br,
  b('Alert: '),$host,' is back up',br,
  b('Time: '),$time . ' ' . $timezone,br,
  b('Total Down Time: '),$total_down_time,br,
  b('Notes: '),$$notes_ref[0],br,br,
  b('URL: '),a({-href=>$grapher_url},$grapher_url),br,br,
  img({-src=>'cid:packetloss_graph'})) .
  $cgi->end_html();
             
 my $sth = $dbh->prepare("SELECT email1,email2,email3,email4 FROM rbping_server WHERE agent_id = '$agent_id' AND
  host = '$host' AND (email1 IS NOT NULL OR email2 IS NOT NULL OR email3 IS NOT NULL OR email4 IS NOT NULL)");
 my $size = $sth->execute();
 if ($size > 0) {  
  foreach ( my @row = $sth->fetchrow_array ) {
   if (defined $_) { $to_addr = $_ . "," . $to_addr; }
  }
  chop($to_addr);
 lossgraph($agent_id,$host);
 mime_mailer_individual_alert($mime_subject,$mime_data,$host,$to_addr);
 }
}

sub lossgraph {
 my $agent_id = shift;
 my $host = shift;
 my $color = "#FF0000";
 my $hostfile = get_hostfile($agent_id,$host);
 my $end_time = RRDs::last "$datadir/$agent_id/$hostfile.rrd";
 my $start_time = $end_time - 10800;
 my $stime  = strftime($time_format2, localtime($start_time));
 my $etime = strftime($time_format2, localtime($end_time));
 my $title = "$host Loss Graph from $stime to $etime"; 
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
 @args = (@args,
          "DEF:loss$x=$datadir/$agent_id/$hostfile.rrd:loss:LAST",
          "LINE2:loss$x$color:$host",
          "GPRINT:loss$x:LAST:latest Loss\\: %.0lf%%\\n");

 RRDs::graph("$tmpdir/packetlossgraph-$host-alert.png", @args);
}

sub mime_mailer {
 my $mime_subject = shift;
 my $mime_data = shift;
 my $mime_message = MIME::Lite->new(   
  From     =>$from_addr,
  To       =>@to_addr,
  Subject  =>$mime_subject,
  Type     =>'text/html',
  Data => $mime_data,
  Encoding =>'base64'
 );

 MIME::Lite->send('smtp', $smtp_server_r, Timeout=>30);
 $mime_message->send;
}

sub mime_mailer_individual_alert {
 my $mime_subject = shift;
 my $mime_data = shift;
 my $host = shift;
 my $to_addr = shift;
 my $mime_message = MIME::Lite->new(   
  From     =>$from_addr,
  To       =>$to_addr,
  Subject  =>$mime_subject,
  Type     =>'multipart/related'
 );

 $mime_message->attach(
  Type =>'text/html',
  Data => $mime_data,
 );

 $mime_message->attach(
  Type =>'image/png',
  Id   => 'packetloss_graph',
  Path => "$tmpdir/packetlossgraph-$host-alert.png"
 );

 MIME::Lite->send('smtp', $smtp_server_r, Timeout=>30);
 $mime_message->send;
 unlink("$tmpdir/packetlossgraph-$host-alert.png");
}

sub send_outage_alert {
 my $sth = $dbh->prepare("SELECT down_alert_sent FROM rbping_outages WHERE down_alert_sent = '0'");
 my $new_down_hosts_count = $sth->execute();
 $sth = $dbh->prepare("SELECT host FROM rbping_outages WHERE up_date_time IS NULL");
 my $any_down_hosts_count = $sth->execute();
 #print "$down_hosts_count\n";
 #exit;
 if ($new_down_hosts_count > 0) {
  my $down_hosts_r = $dbh->selectcol_arrayref("SELECT host FROM rbping_outages WHERE up_date_time IS NULL ORDER BY id ASC");
  down_message_body(@$down_hosts_r);
 }   
 if ($previous_down_hosts_count > 0 && $any_down_hosts_count == 0) {
  up_message_body();
 }
}

sub total_outage {
 my $total_sec = shift;
 my $hrs = sprintf ("%02d", ($total_sec  / (60*60) ) );
 my $min = sprintf ("%02d", ( ($total_sec - $hrs*60*60) / (60) ) );
 my $sec = sprintf ("%02d", ($total_sec - ($hrs*60*60) - ($min*60) ) );
 my $total_down_time = $hrs . ":" . $min . ":" . $sec; 
 return($total_down_time);
}

sub up_message_body {
 my $time = strftime($time_format, localtime());
 my $mime_subject = "All Hosts are Up";
 my $mime_data = $cgi->start_html() .
  $cgi->p('All Hosts are Up',br,br,
   'at ',$time,br,
   '*****') . $cgi->end_html();
   
 mime_mailer($mime_subject,$mime_data);
}
