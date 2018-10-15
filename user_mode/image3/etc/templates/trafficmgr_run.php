#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$TC="tc";
$K=kbit;

$UPLINK_INF = "veth2";
$DOWNLINK_INF = "veth3";

$qos_run = query("/runtime/stats/trafficctrl/qos_run");
$trafficmgr_run = query("/runtime/stats/trafficctrl/trafficmgr_run");
$trafficmgr_enable = query("/trafficctrl/trafficmgr/enable");
$veth_exist = query("/runtime/stats/trafficctrl/veth_exist");

$create_downlink = "/var/run/create_downlink";
$create_uplink = "/var/run/create_uplink";
$tm_downlink = "/var/run/tm_downlink";
$tm_uplink = "/var/run/tm_uplink";

/* main */
if ($generate_start==1)
{
	echo "echo Start traffic manager system ... \n";

	$wlan_enable_g = query("/wlan/inf:1/enable");
	$wlan_enable_a = query("/wlan/inf:2/enable");
	if($wlan_enable_g != 1 && $wlan_enable_a != 1){
		echo "echo traffic manager is disabled, because WLAN setting. \n";
		exit;
	}
	
	if($trafficmgr_enable != 1){ 
		echo "echo traffic manager is disabled. \n";
		exit;
	}

	if($trafficmgr_run == 1){
		echo "echo traffic manager is running. \n";
		exit;
	}

	$up_link = query("/trafficctrl/updownlinkset/bandwidth/uplink");
	$down_link = query("/trafficctrl/updownlinkset/bandwidth/downlink");
	if($up_link == "" || $up_link == 0 || $down_link == "" || $down_link == 0){
		echo "echo uplink ,downlink bandwidth set error. \n");
		exit;
	}

	/* Mbps -> kbps */
	$up_link = $up_link * 1024;
	$down_link = $down_link * 1024;

	echo "echo traffic manager=".$trafficmgr_enable." up_link=".$up_link." down_link=".$down_link." \n";

	$wlanapmode_g = query("/wlan/inf:1/ap_mode");
	$wlanapmode_a = query("/wlan/inf:2/ap_mode");

	/* create virtual interface for QOS and traffic manager */
	if($veth_exist != 1){
		echo "insmod /lib/modules/vethdev.ko devnum=4 \n";
		echo "rgdb -i -s /runtime/stats/trafficctrl/veth_exist 1 \n";

		/* bind uplink/downlink port base on ap_mode 
	 	* 0 - ap  1 - apclient 3 - WDS+AP  4 WDS 
	 	*/
		$eth0bind = query("/lan/ethernet/updownlink");
		if($eth0bind == 1){
			echo "vethctl -a veth1 eth0 \n";
		}else if($eth0bind == 2){
			echo "vethctl -a veth0 eth0 \n";
		}
		$eth1bind = query("/lan/ethernet:2/updownlink");
		if($eth1bind == 1){
			echo "vethctl -a veth1 eth1 \n";
		}else if($eth1bind == 2){
			echo "vethctl -a veth0 eth1 \n";
		}

		/* G band */
		/* not wds without ap ,query primary ssid */
		if($wlanapmode_g == 0 || $wlanapmode_g == 3){ 
			$primarybind_g = query("/wlan/inf:1/updownlink");
			if($primarybind_g == 1){
				echo "vethctl -a veth1 ath0 \n";
			}else if($primarybind_g == 2){
				echo "vethctl -a veth0 ath0 \n";
			}
		}
		$multi_state_g = query("/wlan/inf:1/multi/state");
		if($wlanapmode_g == 0 || $wlanapmode_g == 3){
			if($multi_state_g == 1){
				$index = 0;
				for("/wlan/inf:1/multi/index"){
					$index++;
					$multienable_g = query("/wlan/inf:1/multi/index:".$index."/state");
					$multibind_g = query("/wlan/inf:1/multi/index:".$index."/updownlink");
					if($multienable_g == 1 && $multibind_g == 1){
						echo "vethctl -a veth1 ath".$index." \n";
					}else if($multienable_g == 1 && $multibind_g == 2){
						echo "vethctl -a veth0 ath".$index." \n";
					}
				}
			}
		}
		if($wlanapmode_g == 3 || $wlanapmode_g == 4){
			$index1 = 7;
			$index2 = 0;
			for("/wlan/inf:1/wds/list/index"){
				$index1++;
				$index2++;
				$wdsmac_g = query("/wlan/inf:1/wds/list/index:".$index2."/mac");
				$wdsbind_g = query("/wlan/inf:1/wds/list/index:".$index2."/updownlink");
				if($wdsmac_g != "" && $wdsbind_g == 1){
					echo "vethctl -a veth1 ath".$index1." \n";
				}else if($wdsmac_g != "" && $wdsbind_g == 2){
					echo "vethctl -a veth0 ath".$index1." \n";
				}
			}
		}

		/* A band */
		/* not wds without ap ,query primary ssid */
		if($wlanapmode_a == 0 || $wlanapmode_a == 3){ 
			$primarybind_a = query("/wlan/inf:2/updownlink");
			if($primarybind_a == 1){
				echo "vethctl -a veth1 ath16 \n";
			}else if($primarybind_a == 2){
				echo "vethctl -a veth0 ath16 \n";
			}
		}
		$multi_state_a = query("/wlan/inf:2/multi/state");
		if($wlanapmode_a == 0 || $wlanapmode_a == 3){
			if($multi_state_a == 1){
				$index = 0;
				$index2 = 16;
				for("/wlan/inf:2/multi/index"){
					$index++;
					$index2++;
					$multienable_a = query("/wlan/inf:2/multi/index:".$index."/state");
					$multibind_a = query("/wlan/inf:2/multi/index:".$index."/updownlink");
					if($multienable_a == 1 && $multibind_a == 1){
						echo "vethctl -a veth1 ath".$index2." \n";
					}else if($multienable_a == 1 && $multibind_a == 2){
						echo "vethctl -a veth0 ath".$index2." \n";
					}
				}
			}
		}
		if($wlanapmode_a == 3 || $wlanapmode_a == 4){
			$index1 = 23;
			$index2 = 0;
			for("/wlan/inf:2/wds/list/index"){
				$index1++;
				$index2++;
				$wdsmac_a = query("/wlan/inf:2/wds/list/index:".$index2."/mac");
				$wdsbind_a = query("/wlan/inf:2/wds/list/index:".$index2."/updownlink");
				if($wdsmac_a != "" && $wdsbind_a == 1){
					echo "vethctl -a veth1 ath".$index1." \n";
				}else if($wdsmac_a != "" && $wdsbind_a == 2){
					echo "vethctl -a veth0 ath".$index1." \n";
				}
			}
		}

		echo "vethctl -a veth2 veth0 \n";
		echo "vethctl -a veth3 veth1 \n";

		echo "ifconfig veth3 up \n";
		echo "ifconfig veth2 up \n";
		echo "ifconfig veth1 up \n";
		echo "ifconfig veth0 up \n";
	}

	echo "rgdb -i -s /runtime/stats/trafficctrl/trafficmgr_run 1 \n";

	$UPLINK  = $up_link;
	$DOWNLINK  = $down_link;

	/*---------------Create queues------------------------------------------------------*/
	echo $TC." qdisc add dev ".$UPLINK_INF." root handle 1: htb default 165 ;\n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1: classid 1:1 htb rate ".$UPLINK.$K." ceil ".$UPLINK.$K." burst 250k cburst 250k ;\n";

	echo $TC." qdisc add dev ".$DOWNLINK_INF." root handle 2: htb default 165 ;\n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2: classid 2:1 htb rate ".$DOWNLINK.$K." ceil ".$DOWNLINK.$K." burst 250k cburst 250k ;\n";
	
	$index = 0;
	$all_uplink = 0;
	$all_downlink = 0;

	echo "rm -f ".$create_downlink." ".$create_uplink." ".$tm_downlink." ".$tm_uplink." \n ";
	echo "touch ".$tm_downlink." ".$tm_uplink." \n";

	$rule_path = "/trafficctrl/trafficmgr";

	/*---- query rules into 2 temp file /var/run/tm_downlink tm_uplink ----*/
	for($rule_path."/rule/index")
	{
		$index++;
		if($index <= 64)
		{

		$uplink=query($rule_path."/rule/index:".$index."/uplink");
		$downlink=query($rule_path."/rule/index:".$index."/downlink");
		$ip=query($rule_path."/rule/index:".$index."/clientip");
		$mac=query($rule_path."/rule/index:".$index."/clientmac");
		
		if($ip == "" && $mac == ""){exit;} /* error */
		if($uplink == "" && $downlink == ""){exit;} /* error */

		$uplink = $uplink * 1024;
		$downlink = $downlink * 1024;

		/* client's limit can't larger than whole limit */
		if($uplink >= $UPLINK || $uplink == "" || $uplink == 0) { $uplink = $UPLINK; }
		if($downlink >= $DOWNLINK || $downlink == "" || $downlink == 0) { $downlink = $DOWNLINK; }
		
		if($ip == 0 || $ip == "")
		{
			$ip = "0.0.0.0";
		}
		if($mac == 0 || $mac == "")
		{
			$mac = "00:00:00:00:00:00";
		}

		if($uplink != 0 && $uplink != "")
		{
			echo "echo ".$uplink."	".$ip."	".$mac."	 >> ".$tm_uplink." \n";
			$all_uplink += $uplink;
		}
		
		if($downlink != 0 && $downlink != "")
		{
			echo "echo ".$downlink."	".$ip."	".$mac." >> ".$tm_downlink." \n";
			$all_downlink += $downlink;
		}

		}
		
	}
	
	/* sort and add line(prio) num */
	echo "sort ".$tm_uplink." > /var/run/tm_tmp\n";
	echo "mv /var/run/tm_tmp ".$tm_uplink." \n";
	echo "sort ".$tm_downlink." > /var/run/tm_tmp\n";
	echo "mv /var/run/tm_tmp ".$tm_downlink." \n";
	echo "grep -n . ".$tm_uplink." | sed 's/:/	/' > /var/run/tm_tmp \n";
	echo "mv /var/run/tm_tmp ".$tm_uplink." \n";
	echo "grep -n . ".$tm_downlink." | sed 's/:/	/' > /var/run/tm_tmp \n";
	echo "mv /var/run/tm_tmp ".$tm_downlink." \n";
	
	/* create DOWNLINK queues and filters, line num is the filter priority */
	echo "echo while read prio limit ip mac >> ".$create_downlink." \n";
	echo "echo do >> ".$create_downlink." \n";
	echo "echo { >> ".$create_downlink." \n";
	//echo "echo	".$TC." class add dev ".$UPLINK_INF " parent 1:1 classid 1:1$prio htb prio 8 rate ".$uplink.$K." ceil ".$uplink.$K. \n" >> /var/run/tm_createqueue";
	echo "echo \"	\"".$TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:1'\"$prio\"' htb prio 8 rate '\"$limit\"'".$K." ceil '\"$limit\"'".$K." burst 200k cburst 200k >> ".$create_downlink." \n";
	echo "echo \"	\"opt_src_ip='\"\"' >> ".$create_downlink." \n";
	echo "echo \"	\"opt_dst_ip='\"\"' >> ".$create_downlink." \n";
	echo "echo \"	\"opt_src_mac='\"\"' >> ".$create_downlink." \n";
	echo "echo \"	\"opt_dst_mac='\"\"' >> ".$create_downlink." \n";
	/* ip filter option */
	echo "echo \"	\"if [ '\"$ip\"' != \\\"0.0.0.0\\\" ] >> ".$create_downlink." \n";
	echo "echo \"	\"then >> ".$create_downlink." \n";
	echo "echo \"		\"opt_src_ip='\"match ip src $ip/1\"' >> ".$create_downlink." \n";
	echo "echo \"		\"opt_dst_ip='\"match ip dst $ip/1\"' >> ".$create_downlink." \n";
	echo "echo \"	\"fi >> ".$create_downlink." \n";
	/* mac filter option */
	echo "echo \"	\"if [ '\"$mac\"' != \\\"00:00:00:00:00:00\\\" ] >> ".$create_downlink." \n";
	echo "echo \"	\"then >> ".$create_downlink." \n";
	echo "echo \"		\"tfmgr_mac='\"$mac\"' >> ".$create_downlink." \n";
	echo "echo \"		\"tfmgr_mac1='`echo $tfmgr_mac | cut -c1-2`'  >> ".$create_downlink." \n";
	echo "echo \"		\"tfmgr_mac2='`echo $tfmgr_mac | cut -c4-5`'  >> ".$create_downlink." \n";
	echo "echo \"		\"tfmgr_mac3='`echo $tfmgr_mac | cut -c7-8`'  >> ".$create_downlink." \n";
	echo "echo \"		\"tfmgr_mac4='`echo $tfmgr_mac | cut -c10-11`'  >> ".$create_downlink." \n";
	echo "echo \"		\"tfmgr_mac5='`echo $tfmgr_mac | cut -c13-14`'  >> ".$create_downlink." \n";
	echo "echo \"		\"tfmgr_mac6='`echo $tfmgr_mac | cut -c16-17`'  >> ".$create_downlink." \n";	
	echo "echo \"		\"opt_dst_mac='`echo match u8 0x$tfmgr_mac1 0xFF at -14 match u8 0x$tfmgr_mac2 0xFF at -13 match u8 0x$tfmgr_mac3 0xFF at -12 match u8 0x$tfmgr_mac4 0xFF at -11 match u8 0x$tfmgr_mac5 0xFF at -10 match u8 0x$tfmgr_mac6 0xFF at -9`' >> ".$create_downlink." \n";
	echo "echo \"		\"opt_src_mac='`echo match u8 0x$tfmgr_mac1 0xFF at -8 match u8 0x$tfmgr_mac2 0xFF at -7 match u8 0x$tfmgr_mac3 0xFF at -6 match u8 0x$tfmgr_mac4 0xFF at -5 match u8 0x$tfmgr_mac5 0xFF at -4 match u8 0x$tfmgr_mac6 0xFF at -3`' >> ".$create_downlink." \n";
	echo "echo \"	\"fi >> ".$create_downlink." \n";
	/* add filter */
	echo "echo \"	\"".$TC." filter add dev ".$DOWNLINK_INF." protocol ip parent 2: prio '$prio' u32 '$opt_src_ip' '$opt_src_mac' flowid 2:1'$prio' >> ".$create_downlink." \n";
	echo "echo \"	\"".$TC." filter add dev ".$DOWNLINK_INF." protocol ip parent 2: prio '$prio' u32 '$opt_dst_ip' '$opt_dst_mac' flowid 2:1'$prio' >> ".$create_downlink." \n";
	echo "echo } >> ".$create_downlink." \n";
	echo "echo done >> ".$create_downlink." \n";
	
	echo "sh ".$create_downlink." < ".$tm_downlink." \n";
	
	
	/* create UPLINK queues and filters, line num is the filter priority */
	echo "echo while read prio limit ip mac >> ".$create_uplink." \n";
	echo "echo do >> ".$create_uplink." \n";
	echo "echo { >> ".$create_uplink." \n";
	echo "echo \"	\"".$TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:1'\"$prio\"' htb prio 8 rate '\"$limit\"'".$K." ceil '\"$limit\"'".$K." burst 200k cburst 200k >> ".$create_uplink." \n";
	echo "echo \"	\"opt_src_ip='\"\"' >> ".$create_uplink." \n";
	echo "echo \"	\"opt_dst_ip='\"\"' >> ".$create_uplink." \n";
	echo "echo \"	\"opt_src_mac='\"\"' >> ".$create_uplink." \n";
	echo "echo \"	\"opt_dst_mac='\"\"' >> ".$create_uplink." \n";
	/* ip filter option */
	echo "echo \"	\"if [ '\"$ip\"' != \\\"0.0.0.0\\\" ] >> ".$create_uplink." \n";
	echo "echo \"	\"then >> ".$create_uplink." \n";
	echo "echo \"		\"opt_src_ip='\"match ip src $ip/1\"' >> ".$create_uplink." \n";
	echo "echo \"		\"opt_dst_ip='\"match ip dst $ip/1\"' >> ".$create_uplink." \n";
	echo "echo \"	\"fi >> ".$create_uplink." \n";
	/* mac filter option */
	echo "echo \"	\"if [ '\"$mac\"' != \\\"00:00:00:00:00:00\\\" ] >> ".$create_uplink." \n";
	echo "echo \"	\"then >> ".$create_uplink." \n";
	echo "echo \"		\"tfmgr_mac='\"$mac\"' >> ".$create_uplink." \n";
	echo "echo \"		\"tfmgr_mac1='`echo $tfmgr_mac | cut -c1-2`'  >> ".$create_uplink." \n";
	echo "echo \"		\"tfmgr_mac2='`echo $tfmgr_mac | cut -c4-5`'  >> ".$create_uplink." \n";
	echo "echo \"		\"tfmgr_mac3='`echo $tfmgr_mac | cut -c7-8`'  >> ".$create_uplink." \n";
	echo "echo \"		\"tfmgr_mac4='`echo $tfmgr_mac | cut -c10-11`'  >> ".$create_uplink." \n";
	echo "echo \"		\"tfmgr_mac5='`echo $tfmgr_mac | cut -c13-14`'  >> ".$create_uplink." \n";
	echo "echo \"		\"tfmgr_mac6='`echo $tfmgr_mac | cut -c16-17`'  >> ".$create_uplink." \n";	
	echo "echo \"		\"opt_dst_mac='`echo match u8 0x$tfmgr_mac1 0xFF at -14 match u8 0x$tfmgr_mac2 0xFF at -13 match u8 0x$tfmgr_mac3 0xFF at -12 match u8 0x$tfmgr_mac4 0xFF at -11 match u8 0x$tfmgr_mac5 0xFF at -10 match u8 0x$tfmgr_mac6 0xFF at -9`' >> ".$create_uplink." \n";
	echo "echo \"		\"opt_src_mac='`echo match u8 0x$tfmgr_mac1 0xFF at -8 match u8 0x$tfmgr_mac2 0xFF at -7 match u8 0x$tfmgr_mac3 0xFF at -6 match u8 0x$tfmgr_mac4 0xFF at -5 match u8 0x$tfmgr_mac5 0xFF at -4 match u8 0x$tfmgr_mac6 0xFF at -3`' >> ".$create_uplink." \n";
	echo "echo \"	\"fi >> ".$create_uplink." \n";
	/* add filter */
	echo "echo \"	\"".$TC." filter add dev ".$UPLINK_INF." protocol ip parent 1: prio '$prio' u32 '$opt_src_ip' '$opt_src_mac' flowid 1:1'$prio' >> ".$create_uplink." \n";
	echo "echo \"	\"".$TC." filter add dev ".$UPLINK_INF." protocol ip parent 1: prio '$prio' u32 '$opt_dst_ip' '$opt_dst_mac' flowid 1:1'$prio' >> ".$create_uplink." \n";
	echo "echo } >> ".$create_uplink." \n";
	echo "echo done >> ".$create_uplink." \n";
	
	echo "sh ".$create_uplink." < ".$tm_uplink." \n";
	
	/*--- the default queue ---*/
	$default_index = 65;
	if($all_uplink >= $UPLINK){
		$default_uplink = 1;
	}
	else{
		$default_uplink = $UPLINK - $all_uplink;
	}
	if($all_downlink >= $DOWNLINK){
		$default_downlink = 1;
	}
	else{
		$default_downlink = $DOWNLINK - $all_downlink;
	}

	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:1".$default_index." htb prio 8 rate ".$default_uplink.$K." ceil ".$UPLINK.$K." burst 200k cburst 200k  \n";

	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:1".$default_index." htb prio 8 rate ".$default_downlink.$K." ceil ".$DOWNLINK.$K." burst 200k cburst 200k  \n";

	/*--- forword unlisted client? ----*/
	$forward_unlist = query($rule_path."/unlistclientstraffic"); /* 0 - deny , 1 - forward */
	if($forward_unlist == 0)
	{
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio ".$default_index." u32 match u8 00 00 flowid 1:1".$default_index." police rate 1bps burst 1 drop \n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio ".$default_index." u32 match u8 00 00 flowid 2:1".$default_index." police rate 1bps burst 1 drop \n";
	}

}
else
{
	echo "rm -f ".$create_downlink." ".$create_uplink." ".$tm_downlink." ".$tm_uplink." \n ";
	if($trafficmgr_run != 1){
		exit;
	}

	echo "echo Stop traffic manager system ... \n";

	if($veth_exist == 1){
		echo $TC." qdisc del dev ".$UPLINK_INF." root \n";
		echo $TC." qdisc del dev ".$DOWNLINK_INF." root \n";
		if($qos_run != 1){
			echo "rmmod vethdev \n";
			echo "rgdb -i -s /runtime/stats/trafficctrl/veth_exist 0 \n";
		}
	}else{
		//echo "echo virtual module is not exist. \n";
	}

	echo "rgdb -i -s /runtime/stats/trafficctrl/trafficmgr_run 0 \n";
}

?>
