#!/bin/sh
<? /* vi: set sw=4 ts=4: */
if ($SERVDSTART=="1")
{
	echo "ifconfig ath5 up\n";

}
else
{
	echo "ifconfig ath5 down\n";
}
?>
