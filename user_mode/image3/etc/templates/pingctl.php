#!/bin/sh
<? /* vi: set sw=4 ts=4: */

$pingctl = "/var/run/__pingctl.sh";
$pingctlstatus = query("/sys/pingctl");

fwrite($pingctl, "echo pingctl.sh...\n");
//phelpsll:don't use "brctl pingctl br0 enable" to disable ping,use proc instead.2009-8-12
if ($pingctlstatus==1)
{

    fwrite2($pingctl, "echo 1 > /proc/sys/net/ipv4/icmp_echo_ignore_all\n");
}else{
    fwrite2($pingctl, "echo 0 > /proc/sys/net/ipv4/icmp_echo_ignore_all\n");
}
?>
