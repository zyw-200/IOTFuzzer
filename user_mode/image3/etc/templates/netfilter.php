#!/bin/sh
<? /* vi: set sw=4 ts=4: */

$netfilter = "/var/run/__netfilter.sh";
$trafficmgr_enable = query("/trafficctrl/trafficmgr/enable");
$arpspoof_enable = query("/arpspoofing/enable");
$qos_enable = query("/trafficctrl/qos/enable");
$tcmonitor_enable = query("/tc_monitor/state");

fwrite($netfilter, "echo netfilter.sh...\n");
if ($trafficmgr_enable== 1||$arpspoof_enable == 1||$qos_enable == 1 || $tcmonitor_enable ==1)
{
    fwrite2($netfilter, "brctl netfilter br0 1\n");
}else{
    fwrite2($netfilter, "brctl netfilter br0 0\n");
}
?>
