#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$wr_pid = "/var/run/webredirect.pid";

$generate_start=0;

//echo "echo Web Redirect is stop ! > /dev/console\n";
//echo "if [ -f ".$wr_pid." ]; then\n";
//echo "kill \`cat ".$wr_pid."\` > /dev/null 2>&1\n";
//echo "rm -f ".$wr_pid."\n";
//echo "fi\n\n";	
echo "brctl webredirect br0 0 \n";

?>
