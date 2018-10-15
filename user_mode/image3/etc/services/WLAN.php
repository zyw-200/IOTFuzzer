#!/bin/sh
<? /* vi: set sw=4 ts=4: */
if ($SERVDSTART=="1")
{
	echo "/etc/templates/wlan_run.sh start";
}
else
{
echo "/etc/templates/wlan_run.sh stop";
}
?>
