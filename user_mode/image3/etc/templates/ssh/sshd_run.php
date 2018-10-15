#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$DAEMON="/usr/sbin/sshd";
$PIDFILE="/var/run/sshd.pid";
$CONFIG="/var/etc/sshd_config";
echo "DAEMON=".$DAEMON."\n";
echo "PIDFILE=".$PIDFILE."\n";
echo "CONFIG=".$CONFIG."\n";

if ($start==1)
{
	echo "echo \"start sshd ...\";\n";
	echo "sshdstatus=`rgdb -g /sys/consoleprotocol/protocol`\n";
	echo "if [ \"$sshdstatus\" != \"2\" ]; then\n";
	echo "  echo \"Disable start-up daemon: sshd.\"\n";
	echo "  exit 0\n";
	echo "fi\n";
	echo "IPV6=`rgdb -g /inet/entry:1/ipv6/valid`\n";
	echo "if [ \"$IPV6\" = \"1\" ]; then\n";
	echo "rgdb -A ".$template_root."/ssh/sshdconf6.php > \$CONFIG\n";
	echo "else\n";
	echo "rgdb -A ".$template_root."/ssh/sshdconf.php > \$CONFIG\n";
	echo "fi\n";
	echo "sleep 1\n";
	echo "\$DAEMON\n";
	echo "if [ \"$?\" = \"0\" ]; then\n";
	echo "	echo \"sshd start ok \"\n";
	echo "else\n";
	echo "	echo \"\$0: Error starting \${DAEMON} failing ...\"\n";
	echo "fi\n";
	echo "echo done.";
}
else
{
	echo "echo -n \"Stopping secure shell daemin...\"\n";
	echo "killall sshd\n";
//	echo "kill `cat ".$PIDFILE."`\n";
	echo "if [ \"$?\" = \"0\" ]; then\n";
	echo "  echo \" Success.\"\n";
	echo "else\n";
	echo "  echo \" Failed.\"\n";
	echo "fi\n";
}

?>
