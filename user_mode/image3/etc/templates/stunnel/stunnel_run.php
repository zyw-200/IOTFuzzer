#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$DAEMON="/usr/sbin/stunnel";
$PIDFILE="/var/run/stunnel.pid";
$CONFIG="/var/etc/stunnel.conf";
echo "DAEMON=".$DAEMON."\n";
echo "PIDFILE=".$PIDFILE."\n";
echo "CONFIG=".$CONFIG."\n";

if ($start==1)
{
	echo "echo -n \"Starting universal SSL tunnel: stunnel\"\n";
	echo "rgdb -A ".$template_root."/stunnel/stunnelconf.php > \$CONFIG\n";
	echo "sleep 1\n";
    echo "\$DAEMON \$CONFIG || echo -n \" failed\"\n";
    echo "echo \".\"\n";
}
else
{
	echo "echo -n \"Stopping universal SSL tunnel: stunnel\"\n";
    echo "if test -r \$PIDFILE; then\n";
    echo "	kill `cat \$PIDFILE` 2> /dev/null || echo -n \" failed\"\n";
    echo "else\n";
    echo "	echo -n \" no PID file\"\n";
    echo "fi\n";
    echo "echo \".\"\n";
}

?>
