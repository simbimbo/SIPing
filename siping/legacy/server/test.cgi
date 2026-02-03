#!/usr/bin/perl

use warnings;
use strict;
use CGI::Pretty qw( :all );

my $cgi=new CGI;

print $cgi->header;
print $cgi->start_html(-title=>'TEST',-head=>meta({-http_equiv=>'refresh',-content =>'60'}),-style=>{-src=>'/css/test.css',-type =>'text/css'});
print qq(<div id="container2">);
 print qq(<div id="container1">);
  print qq(<div id="col1">);
   print $cgi->h1('COL1');
  print qq(</div>);
  print qq(<div id="col2">);
   print $cgi->h2('COL2');  
  print qq(</div>);
 print qq(<div id="footer">TBC Corp<div>);
 print qq(</div>);
print qq(</div>);
print $cgi->end_html;



