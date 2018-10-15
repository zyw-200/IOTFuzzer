#!/bin/sh
<? /* vi: set sw=4 ts=4: */

$dhcp_mc2uc_ctl = "/var/run/__dhcp_mc2uc.sh";
$dhcp_mc2uc_status = query("/sys/dhcp_mc2uc");

fwrite($dhcp_mc2uc_ctl, "echo dhcp_mc2uc.sh...\n");
if ($dhcp_mc2uc_status==1)
{

    fwrite2($dhcp_mc2uc_ctl, "brctl dhcp_bc2uc_enable br0 1\n");
}else{
    fwrite2($dhcp_mc2uc_ctl, "brctl dhcp_bc2uc_enable br0 0\n");
}
?>
