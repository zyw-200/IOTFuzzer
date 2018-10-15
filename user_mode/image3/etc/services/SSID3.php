#!/bin/sh
<? /* vi: set sw=4 ts=4: */
if ($SERVDSTART=="1")
{
	echo "ifconfig ath3 up\n";
}
else
{
	echo "ifconfig ath3 down\n";
}
?>
