#!/bin/sh
echo [$0] $1 ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
?>
rm -f /var/etc/ppp/resolv.conf.$1 > /dev/console
<?
echo "xmldbc -x /runtime/stats/wan/inf:1/rx/bytes \"get:\"\n";
echo "xmldbc -x /runtime/stats/wan/inf:1/rx/packets \"get:\"\n";
echo "xmldbc -x /runtime/stats/wan/inf:1/tx/bytes \"get:\"\n";
echo "xmldbc -x /runtime/stats/wan/inf:1/tx/packets \"get:\"\n";

echo $template_root."/dnrd.sh restart > /dev/console\n";

$generate_start=0;
require($template_root."/misc/ntp_run.php");
require($template_root."/misc/dyndns_run.php");

/* kick upnpd to send notify */
echo "echo Kicking UPNPD ... > /dev/console\n";
echo $template_root."/upnpd/NOTIFY.WANIPConnection.1.sh\n";
echo "killall -SIGUSR1 upnpd\n";	/* This is for the old IGD daemon. */
?>
