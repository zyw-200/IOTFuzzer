#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
$tc_monitor_state = query("/tc_monitor/state");


if ($generate_start == 1)
{
    if($tc_monitor_state == 1)
    {
		echo "echo tc monitor is enable \n";
        echo "rgdb -is /runtime/tc_monitor_stop 0 \n";    
        echo "tc_monitor & \n";
    } 
	else
	{
		echo "echo tc monitor is disable \n";
	}
	
}
else
{
    $start = query("/runtime/tc_monitor_start");
    if($start == 1)
    {
		echo "echo stop tc monitor \n";
        echo "rgdb -is /runtime/tc_monitor_stop 1 \n";
        echo "tc qdisc del dev veth2 root \n";
        echo "tc qdisc del dev veth3 root \n";
        echo "tc qdisc del dev veth4 root \n";
        echo "tc qdisc del dev veth5 root \n";
        echo "ifconfig veth5 down \n";
        echo "ifconfig veth4 down \n";
        echo "ifconfig veth3 down \n";
        echo "ifconfig veth2 down \n";
        echo "ifconfig veth1 down \n";
        echo "ifconfig veth0 down \n";
        echo "rmmod vethdev \n");
        echo "ebtables -F \n");
        echo "rgdb -is /runtime/tc_monitor_start 0 \n";
        echo "rgdb -is /runtime/stats/trafficctrl/veth_exist 0 \n";
        echo "rgdb -is /runtime/stats/trafficctrl/trafficmgr_run 0 \n";
        //echo "sleep 2 \n";
    }
	else
	{
		echo "echo tc monitor already stop \n";
	}
}
?>
