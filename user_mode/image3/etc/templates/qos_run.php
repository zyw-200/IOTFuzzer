#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

/* tc */
$TC="tc";
$K=kbit;

$UPLINK_INF="veth0";
$DOWNLINK_INF="veth1";

$qos_run = query("/runtime/stats/trafficctrl/qos_run");
$trafficmgr_run = query("/runtime/stats/trafficctrl/trafficmgr_run");
$trafficmgr_enable = query("/trafficctrl/trafficmgr/enable");
$veth_exist = query("/runtime/stats/trafficctrl/veth_exist");

/* main */
if ($generate_start==1)
{
	echo "echo Start QOS system ... \n";

	$wlan_enable_g = query("/wlan/inf:1/enable");
	$wlan_enable_a = query("/wlan/inf:2/enable");
	if($wlan_enable_g != 1 && $wlan_enable_a != 1){
		echo "echo QOS is disabled, because WLAN setting. \n";
		exit;
	}

	$QOS_ENABLE  = query("/trafficctrl/qos/enable");
	if($QOS_ENABLE != 1){
		echo "echo QOS is disabled. \n";
		exit;
	}	

	if($qos_run == 1){
		echo "echo QOS is running. \n";
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

	echo "echo QOS=".$QOS_ENABLE." up_link=".$up_link."k down_link=".$down_link."k \n";

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
		if($wlanapmode_g == 0 || $wlanapmode_g == 1 || $wlanapmode_g == 3){ 
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
		if($wlanapmode_a == 0 || $wlanapmode_a == 1 || $wlanapmode_a == 3){ 
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

	echo "rgdb -i -s /runtime/stats/trafficctrl/qos_run 1 \n";

	/*-- advanced qos --*/

	anchor("/trafficctrl/qos/protocol");

	$ack_max	= query("aui/limit");
	if($ack_max == "" || $ack_max > 100){ $ack_max = 100; }
	$ack_prio	= query("aui/priority");
	if($ack_prio == "" ){ $ack_prio = 3; }

	$web_max	= query("web/limit");
	if($web_max == "" || $web_max > 100){ $web_max = 100; }
	$web_prio	= query("web/priority");
	if($web_prio == "" ){ $web_prio = 3; }

	$mail_max	= query("mail/limit");
	if($mail_max == "" || $mail_max > 100){ $mail_max = 100; }
	$mail_prio	= query("mail/priority");
	if($mail_prio == "" ){ $mail_prio = 3; }

	$ftp_max	= query("ftp/limit");
	if($ftp_max == "" || $ftp_max > 100){ $ftp_max = 100; }
	$ftp_prio	= query("ftp/priority");
	if($ftp_prio == "" ){ $ftp_prio = 3; }

	$other_max	= query("other/limit");
	if($other_max == "" || $other_max > 100){ $other_max = 100; }
	$other_prio	= query("other/priority");
	if($other_prio == "" ){ $other_prio = 3; }

	/* user defined .*/
	$user1_max	= query("user1/limit");
	if($user1_max == "" || $user1_max > 100){ $user1_max = 100; }
	$user1_prio	= query("user1/priority");
	if($user1_prio == "" ){ $user1_prio = 3; }
	$user1_startport = query("user1/startport");
	$user1_endport = query("user1/endport");
	if($user1_startport == ""){$user1_startport = 0; }
	if($user1_endport == ""){$user1_endport = 0; }
	/* user define 1 priority configuration */
	$user1_configed = 0;
	$user1_range = 0;
	if($user1_startport <= $user1_endport && $user1_startport != 0 && $user1_endport != 0){
		$user1_configed = 1;
		$user1_range = $user1_endport - $user1_startport + 1;
	}
	
	$user2_max	= query("user2/limit");
	if($user2_max == "" || $user2_max > 100){ $user2_max = 100; }
	$user2_prio	= query("user2/priority");
	if($user2_prio == "" ){ $user2_prio = 3; }
	$user2_startport = query("user2/startport");
	$user2_endport = query("user2/endport");
	if($user2_startport == ""){$user2_startport = 0; }
	if($user2_endport == ""){$user2_endport = 0; }
	$user2_configed = 0;
	$user2_range = 0;
	if($user2_startport <= $user2_endport && $user2_startport != 0 && $user2_endport != 0){
		$user2_configed = 1;
		$user2_range = $user2_endport - $user2_startport + 1;
	}
	
	$user3_max	= query("user3/limit");
	if($user3_max == "" || $user3_max > 100){ $user3_max = 100; }
	$user3_prio	= query("user3/priority");
	if($user3_prio == "" ){ $user3_prio = 3; }
	$user3_startport = query("user3/startport");
	$user3_endport = query("user3/endport");
	if($user3_startport == ""){$user3_startport = 0; }
	if($user3_endport == ""){$user3_endport = 0; }
	$user3_configed = 0;
	$user3_range = 0;
	if($user3_startport <= $user3_endport && $user3_startport != 0 && $user3_endport != 0){
		$user3_configed = 1;
		$user3_range = $user3_endport - $user3_startport + 1;
	}
	
	$user4_max	= query("user4/limit");
	if($user4_max == "" || $user4_max > 100){ $user4_max = 100; }
	$user4_prio	= query("user4/priority");
	if($user4_prio == "" ){ $user4_prio = 3; }
	$user4_startport = query("user4/startport");
	$user4_endport = query("user4/endport");
	if($user4_startport == ""){$user4_startport = 0; }
	if($user4_endport == ""){$user4_endport = 0; }
	$user4_configed = 0;
	$user4_range = 0;
	if($user4_startport <= $user4_endport && $user4_startport != 0 && $user4_endport != 0){
		$user4_configed = 1;
		$user4_range = $user4_endport - $user4_startport + 1;
	}
	
	/* min bandwidth alloc for each queue
	 * highest/second/third/lowest = 4/3/2/1
	 */
	$whole_prio = 4*9 - $ack_prio - $web_prio - $mail_prio - $ftp_prio - $other_prio - $user1_prio - $user2_prio - $user3_prio - $user4_prio;

	$PRIO_ALL	= $up_link;

	$percent	= 4 - $ack_prio;
	$ACK_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$ACK_MAX	= $PRIO_ALL * $ack_max / 100 ;
	if($ACK_MIN > $ACK_MAX){ $ACK_MIN = $ACK_MAX; }
	if($ACK_MIN == 0){ $ACK_MIN = 1; }
	if($ACK_MAX == 0){ $ACK_MAX = 1; }

	$percent	= 4 - $web_prio;
	$WEB_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$WEB_MAX	= $PRIO_ALL * $web_max / 100 ;
	if($WEB_MIN > $WEB_MAX){ $WEB_MIN = $WEB_MAX; }
	if($WEB_MIN == 0){ $WEB_MIN = 1; }
	if($WEB_MAX == 0){ $WEB_MAX = 1; }

	$percent	= 4 - $mail_prio;
	$MAIL_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$MAIL_MAX	= $PRIO_ALL * $mail_max / 100 ;
	if($MAIL_MIN > $MAIL_MAX){ $MAIL_MIN = $MAIL_MAX; }
	if($MAIL_MIN == 0){ $MAIL_MIN = 1; }
	if($MAIL_MAX == 0){ $MAIL_MAX = 1; }

	$percent	= 4 - $ftp_prio;
	$FTP_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$FTP_MAX	= $PRIO_ALL * $ftp_max / 100 ;
	if($FTP_MIN > $FTP_MAX){ $FTP_MIN = $FTP_MAX; }
	if($FTP_MIN == 0){ $FTP_MIN = 1; }
	if($FTP_MAX == 0){ $FTP_MAX = 1; }

	$percent	= 4 - $other_prio;
	$OTHER_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$OTHER_MAX	= $PRIO_ALL * $other_max / 100 ;
	if($OTHER_MIN > $OTHER_MAX){ $OTHER_MIN = $OTHER_MAX; }
	if($OTHER_MIN == 0){ $OTHER_MIN = 1; }
	if($OTHER_MAX == 0){ $OTHER_MAX = 1; }

	$percent	= 4 - $user1_prio;
	$USER1_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER1_MAX	= $PRIO_ALL * $user1_max / 100 ;
	if($USER1_MIN > $USER1_MAX){ $USER1_MIN = $USER1_MAX; }
	if($USER1_MIN == 0){ $USER1_MIN = 1; }
	if($USER1_MAX == 0){ $USER1_MAX = 1; }

	$percent	= 4 - $user2_prio;
	$USER2_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER2_MAX	= $PRIO_ALL * $user2_max / 100 ;
	if($USER2_MIN > $USER2_MAX){ $USER2_MIN = $USER2_MAX; }
	if($USER2_MIN == 0){ $USER2_MIN = 1; }
	if($USER2_MAX == 0){ $USER2_MAX = 1; }

	$percent	= 4 - $user3_prio;
	$USER3_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER3_MAX	= $PRIO_ALL * $user3_max / 100 ;
	if($USER3_MIN > $USER3_MAX){ $USER3_MIN = $USER3_MAX; }
	if($USER3_MIN == 0){ $USER3_MIN = 1; }
	if($USER3_MAX == 0){ $USER3_MAX = 1; }

	$percent	= 4 - $user4_prio;
	$USER4_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER4_MAX	= $PRIO_ALL * $user4_max / 100 ;
	if($USER4_MIN > $USER4_MAX){ $USER4_MIN = $USER4_MAX; }
	if($USER4_MIN == 0){ $USER4_MIN = 1; }
	if($USER4_MAX == 0){ $USER4_MAX = 1; }

	/* create uplink queue */
	/* dispatch packet to 'other' queue default */
	echo $TC." qdisc add dev ".$UPLINK_INF." handle 1: root htb default 15 \n";

	echo $TC." class add dev ".$UPLINK_INF." parent 1:0 classid 1:1 htb rate ".$PRIO_ALL.$K." ceil ".$PRIO_ALL.$K." \n";

	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:11 htb prio ".$ack_prio."  rate ".$ACK_MIN.$K." ceil ".$ACK_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:12 htb prio ".$web_prio."  rate ".$WEB_MIN.$K." ceil ".$WEB_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:13 htb prio ".$mail_prio."  rate ".$MAIL_MIN.$K." ceil ".$MAIL_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:14 htb prio ".$ftp_prio."  rate ".$FTP_MIN.$K." ceil ".$FTP_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:15 htb prio ".$other_prio."  rate ".$OTHER_MIN.$K." ceil ".$OTHER_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:16 htb prio ".$user1_prio."  rate ".$USER1_MIN.$K." ceil ".$USER1_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:17 htb prio ".$user2_prio."  rate ".$USER2_MIN.$K." ceil ".$USER2_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:18 htb prio ".$user3_prio."  rate ".$USER3_MIN.$K." ceil ".$USER3_MAX.$K." \n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:19 htb prio ".$user4_prio."  rate ".$USER4_MIN.$K." ceil ".$USER4_MAX.$K." \n";

	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:11 handle 110: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:12 handle 120: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:13 handle 130: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:14 handle 140: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:15 handle 150: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:16 handle 160: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:17 handle 170: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:18 handle 180: pfifo \n";
	echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:19 handle 190: pfifo \n";


	$PRIO_ALL	= $down_link;

	$percent	= 4 - $ack_prio;
	$ACK_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$ACK_MAX	= $PRIO_ALL * $ack_max / 100 ;
	if($ACK_MIN > $ACK_MAX){ $ACK_MIN = $ACK_MAX; }
	if($ACK_MIN == 0){ $ACK_MIN = 1; }
	if($ACK_MAX == 0){ $ACK_MAX = 1; }

	$percent	= 4 - $web_prio;
	$WEB_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$WEB_MAX	= $PRIO_ALL * $web_max / 100 ;
	if($WEB_MIN > $WEB_MAX){ $WEB_MIN = $WEB_MAX; }
	if($WEB_MIN == 0){ $WEB_MIN = 1; }
	if($WEB_MAX == 0){ $WEB_MAX = 1; }

	$percent	= 4 - $mail_prio;
	$MAIL_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$MAIL_MAX	= $PRIO_ALL * $mail_max / 100 ;
	if($MAIL_MIN > $MAIL_MAX){ $MAIL_MIN = $MAIL_MAX; }
	if($MAIL_MIN == 0){ $MAIL_MIN = 1; }
	if($MAIL_MAX == 0){ $MAIL_MAX = 1; }

	$percent	= 4 - $ftp_prio;
	$FTP_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$FTP_MAX	= $PRIO_ALL * $ftp_max / 100 ;
	if($FTP_MIN > $FTP_MAX){ $FTP_MIN = $FTP_MAX; }
	if($FTP_MIN == 0){ $FTP_MIN = 1; }
	if($FTP_MAX == 0){ $FTP_MAX = 1; }

	$percent	= 4 - $other_prio;
	$OTHER_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$OTHER_MAX	= $PRIO_ALL * $other_max / 100 ;
	if($OTHER_MIN > $OTHER_MAX){ $OTHER_MIN = $OTHER_MAX; }
	if($OTHER_MIN == 0){ $OTHER_MIN = 1; }
	if($OTHER_MAX == 0){ $OTHER_MAX = 1; }

	$percent	= 4 - $user1_prio;
	$USER1_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER1_MAX	= $PRIO_ALL * $user1_max / 100 ;
	if($USER1_MIN > $USER1_MAX){ $USER1_MIN = $USER1_MAX; }
	if($USER1_MIN == 0){ $USER1_MIN = 1; }
	if($USER1_MAX == 0){ $USER1_MAX = 1; }

	$percent	= 4 - $user2_prio;
	$USER2_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER2_MAX	= $PRIO_ALL * $user2_max / 100 ;
	if($USER2_MIN > $USER2_MAX){ $USER2_MIN = $USER2_MAX; }
	if($USER2_MIN == 0){ $USER2_MIN = 1; }
	if($USER2_MAX == 0){ $USER2_MAX = 1; }

	$percent	= 4 - $user3_prio;
	$USER3_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER3_MAX	= $PRIO_ALL * $user3_max / 100 ;
	if($USER3_MIN > $USER3_MAX){ $USER3_MIN = $USER3_MAX; }
	if($USER3_MIN == 0){ $USER3_MIN = 1; }
	if($USER3_MAX == 0){ $USER3_MAX = 1; }

	$percent	= 4 - $user4_prio;
	$USER4_MIN	= $PRIO_ALL * $percent / $whole_prio;
	$USER4_MAX	= $PRIO_ALL * $user4_max / 100 ;
	if($USER4_MIN > $USER4_MAX){ $USER4_MIN = $USER4_MAX; }
	if($USER4_MIN == 0){ $USER4_MIN = 1; }
	if($USER4_MAX == 0){ $USER4_MAX = 1; }

	/* create downlink queue */
	/* dispatch packet to 'other' queue default */
	echo $TC." qdisc add dev ".$DOWNLINK_INF." handle 2: root htb default 15 \n";

	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:0 classid 2:1 htb rate ".$PRIO_ALL.$K." ceil ".$PRIO_ALL.$K." \n";

	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:11 htb prio ".$ack_prio."  rate ".$ACK_MIN.$K." ceil ".$ACK_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:12 htb prio ".$web_prio."  rate ".$WEB_MIN.$K." ceil ".$WEB_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:13 htb prio ".$mail_prio."  rate ".$MAIL_MIN.$K." ceil ".$MAIL_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:14 htb prio ".$ftp_prio."  rate ".$FTP_MIN.$K." ceil ".$FTP_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:15 htb prio ".$other_prio."  rate ".$OTHER_MIN.$K." ceil ".$OTHER_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:16 htb prio ".$user1_prio."  rate ".$USER1_MIN.$K." ceil ".$USER1_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:17 htb prio ".$user2_prio."  rate ".$USER2_MIN.$K." ceil ".$USER2_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:18 htb prio ".$user3_prio."  rate ".$USER3_MIN.$K." ceil ".$USER3_MAX.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:19 htb prio ".$user4_prio."  rate ".$USER4_MIN.$K." ceil ".$USER4_MAX.$K." \n";

	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:11 handle 110: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:12 handle 120: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:13 handle 130: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:14 handle 140: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:15 handle 150: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:16 handle 160: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:17 handle 170: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:18 handle 180: pfifo \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." parent 2:19 handle 190: pfifo \n";

    /*Classifiers, adv qos rule */

	/* ack/dhcp/dns/icmp */
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 6 0xff match u8 0x05 0x0f at 0 match u16 0x0000 0xffc0 at 2 match u8 0x10 0xff at 33 flowid 1:11\n";
	
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match u8 0x01 0xff at 9 flowid 1:11\n";
	
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 67 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 67 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 546 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 546 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 68 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 68 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 547 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 547 1 flowid 1:11\n";

	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip sport 53 1 flowid 1:11\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 300 u32 match ip dport 53 1 flowid 1:11\n";

	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 6 0xff match u8 0x05 0x0f at 0 match u16 0x0000 0xffc0 at 2 match u8 0x10 0xff at 33 flowid 2:11\n";
	
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match u8 0x01 0xff at 9 flowid 2:11\n";
	
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 67 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 67 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 546 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 546 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 68 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 68 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip sport 547 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip protocol 17 0xff match ip dport 547 1 flowid 2:11\n";
	
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip sport 53 1 flowid 2:11\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 300 u32 match ip dport 53 1 flowid 2:11\n";
	
	/* web traffic (port 80 443 3128 8080) */
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 80 1 flowid 1:12\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 80 1 flowid 1:12\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 8080 1 flowid 1:12\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 8080 1 flowid 1:12\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 443 1 flowid 1:12\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 443 1 flowid 1:12\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 3128 1 flowid 1:12\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 3128 1 flowid 1:12\n";
	
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 80 1 flowid 2:12\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 80 1 flowid 2:12\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 8080 1 flowid 2:12\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 8080 1 flowid 2:12\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 443 1 flowid 2:12\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 443 1 flowid 2:12\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip sport 3128 1 flowid 2:12\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 210 u32 match ip protocol 6 0xff match ip dport 3128 1 flowid 2:12\n";
	
	/* mail traffic (port 25 110 465 995) */
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 25 1 flowid 1:13\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 25 1 flowid 1:13\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 110 1 flowid 1:13\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 110 1 flowid 1:13\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 465 1 flowid 1:13\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 465 1 flowid 1:13\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 995 1 flowid 1:13\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 995 1 flowid 1:13\n";
	
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 25 1 flowid 2:13\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 25 1 flowid 2:13\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 110 1 flowid 2:13\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 110 1 flowid 2:13\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 465 1 flowid 2:13\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 465 1 flowid 2:13\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip sport 995 1 flowid 2:13\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 220 u32 match ip protocol 6 0xff match ip dport 995 1 flowid 2:13\n";

	/* ftp traffic (port 20 21) not dynamic port! */
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 230 u32 match ip protocol 6 0xff match ip sport 20 1 flowid 1:14\n";
	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 230 u32 match ip protocol 6 0xff match ip dport 20 1 flowid 1:14\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 230 u32 match ip protocol 6 0xff match ip sport 21 1 flowid 1:14\n";
   	echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 230 u32 match ip protocol 6 0xff match ip dport 21 1 flowid 1:14\n";
	
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 230 u32 match ip protocol 6 0xff match ip sport 20 1 flowid 2:14\n";
	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 230 u32 match ip protocol 6 0xff match ip dport 20 1 flowid 2:14\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 230 u32 match ip protocol 6 0xff match ip sport 21 1 flowid 2:14\n";
   	echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 230 u32 match ip protocol 6 0xff match ip dport 21 1 flowid 2:14\n";
	
	/* default dispatch to other traffic */

	/* user define port filter */
	if($user1_configed == 1){
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user1_startport." ".$user1_range." flowid 1:16\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user1_startport." ".$user1_range." flowid 1:16\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user1_startport." ".$user1_range." flowid 1:16\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user1_startport." ".$user1_range." flowid 1:16\n";

		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user1_startport." ".$user1_range." flowid 2:16\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user1_startport." ".$user1_range." flowid 2:16\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user1_startport." ".$user1_range." flowid 2:16\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user1_startport." ".$user1_range." flowid 2:16\n";
	}

	if($user2_configed == 1){
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user2_startport." ".$user2_range." flowid 1:17\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user2_startport." ".$user2_range." flowid 1:17\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user2_startport." ".$user2_range." flowid 1:17\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user2_startport." ".$user2_range." flowid 1:17\n";

		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user2_startport." ".$user2_range." flowid 2:17\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user2_startport." ".$user2_range." flowid 2:17\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user2_startport." ".$user2_range." flowid 2:17\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user2_startport." ".$user2_range." flowid 2:17\n";
	}

	if($user3_configed == 1){
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user3_startport." ".$user3_range." flowid 1:18\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user3_startport." ".$user3_range." flowid 1:18\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user3_startport." ".$user3_range." flowid 1:18\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user3_startport." ".$user3_range." flowid 1:18\n";

		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user3_startport." ".$user3_range." flowid 2:18\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user3_startport." ".$user3_range." flowid 2:18\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user3_startport." ".$user3_range." flowid 2:18\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user3_startport." ".$user3_range." flowid 2:18\n";
	}

	if($user4_configed == 1){
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user4_startport." ".$user4_range." flowid 1:19\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user4_startport." ".$user4_range." flowid 1:19\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user4_startport." ".$user4_range." flowid 1:19\n";
		echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user4_startport." ".$user4_range." flowid 1:19\n";

		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip sport ".$user4_startport." ".$user4_range." flowid 2:19\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 6 0xff match ip dport ".$user4_startport." ".$user4_range." flowid 2:19\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip sport ".$user4_startport." ".$user4_range." flowid 2:19\n";
		echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol ip prio 200 u32 match ip protocol 17 0xff match ip dport ".$user4_startport." ".$user4_range." flowid 2:19\n";
	}

}
else
{
	if($qos_run != 1){
		exit;
	}

	echo "echo Stop QOS system ... \n";

	if($veth_exist == 1){
		echo $TC." qdisc del dev ".$UPLINK_INF." root \n";
		echo $TC." qdisc del dev ".$DOWNLINK_INF." root \n";
		if($trafficmgr_run != 1){
			echo "rmmod vethdev \n";
			echo "rgdb -i -s /runtime/stats/trafficctrl/veth_exist 0 \n";
		}
	}else{
		//echo "echo virtual module is not exist. \n";
	}

	echo "rgdb -i -s /runtime/stats/trafficctrl/qos_run 0 \n";

	exit;
}

?>
