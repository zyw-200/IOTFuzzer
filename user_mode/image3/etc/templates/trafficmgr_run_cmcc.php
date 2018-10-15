#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$TC="tc";
$K=kbit;

$UPLINK_INF = "veth2";
$DOWNLINK_INF = "veth3";
$create_downlink = "/var/run/create_downlink";
$create_uplink = "/var/run/create_uplink";
$tm_downlink = "/var/run/tm_downlink";
$tm_uplink = "/var/run/tm_uplink";
$qos_run = query("/runtime/stats/trafficctrl/qos_run");
$trafficmgr_run = query("/runtime/stats/wtp/trafficctrl/trafficmgr_run");
$trafficmgr_enable = query("/trafficctrl/wtp/trafficmgr/enable");
$veth_exist = query("/runtime/stats/trafficctrl/veth_exist");

$client_index=0;
//clean runtime record
for("/runtime/stats/wtp/trafficctrl/trafficrule:1/client")
{
         $client_index = $@;

         echo "rgdb  -i -d /runtime/stats/wtp/trafficctrl/trafficrule:1/client:".$client_index."\n";

 }
for("/wlan/inf:1/multi/index")
{
         $mssid_index = $@;
         $Mssid=$mssid_index+1;
	  $client_index=0;
		//clean runtime record
		for("/runtime/stats/wtp/trafficctrl/trafficrule:".$Mssid."/client")
		{
		         $client_index = $@;
		         echo "rgdb  -i -d /runtime/stats/wtp/trafficctrl/trafficrule:".$Mssid."/client:".$client_index."\n";
		 }      
        
    }

 
/* main */
if ($generate_start==1)
{
	echo "echo Start traffic manager system ... \n";

	$wlan_enable = query("/wlan/inf:1/enable");
	if($wlan_enable != 1){
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

	/* Mbps -> kbps */
	/*
	$up_link = $up_link * 1024;
	$down_link = $down_link * 1024;
	*/	
	/*echo "echo traffic manager=".$trafficmgr_enable." up_link=".$up_link." down_link=".$down_link." \n";
	*/
	$wlanapmode = query("/wlan/inf:1/ap_mode");

	/* create virtual interface for QOS and traffic manager */
	if($veth_exist != 1){
		echo "insmod /lib/modules/vethdev.ko devnum=4 \n";
		echo "rgdb -i -s /runtime/stats/trafficctrl/veth_exist 1 \n";

		/* bind uplink/downlink port base on ap_mode 
	 	* 0 - ap  1 - apclient 3 - WDS+AP  4 WDS 
	 	*/
		
			echo "vethctl -a veth1 eth0 \n";
		
		/* not wds without ap ,query primary ssid */
		if($wlanapmode == 0 || $wlanapmode == 1 || $wlanapmode == 3){ 
			
				echo "vethctl -a veth0 ath0 \n";
				echo "vethctl -a veth0 ath16 \n";
			
		}
		$multi_state = query("/wlan/inf:1/multi/state");
		if($wlanapmode == 0 || $wlanapmode == 3){
			if($multi_state == 1){
				$index = 0;
				$mytrafficrule=1;
				for("/wlan/inf:1/multi/index"){
					$index++;
					$multienable = query("/wlan/inf:1/multi/index:".$index."/state");
					$mytrafficrule++;
					if($multienable == 1){
					   $myruleenable=query("/trafficctrl/trafficrule:".$mytrafficrule."/enable");
					   if($myruleenable == 1){
						echo "vethctl -a veth0 ath".$index." \n";
						}
					}
				}
			}
		}
		if($wlanapmode == 3 || $wlanapmode == 4){
			$index1 = 7;
			$index2 = 0;
			for("/wlan/inf:1/wds/list/index"){
				$index1++;
				$index2++;
				$wdsmac = query("/wlan/inf:1/wds/list/index:".$index2."/mac");
				
				if($wdsmac != ""){
					echo "vethctl -a veth0 ath".$index1." \n";
				}
			}
		}
    
   		$wlanapmode = query("/wlan/inf:2/ap_mode");
		if($wlanapmode == 0 || $wlanapmode == 3){
			if($multi_state == 1){
				$index = 0;
				$mytrafficrule=1;
				for("/wlan/inf:2/multi/index"){
					$index++;
					$multienable = query("/wlan/inf:2/multi/index:".$index."/state");
					$mytrafficrule++;
					if($multienable == 1){
					   $myruleenable=query("/trafficctrl/trafficrule:".$mytrafficrule."/enable");
					   if($myruleenable == 1){
					        $acindex=$index+16;
						echo "vethctl -a veth0 ath".$acindex." \n";
						}
					}
				}
			}
		}
		if($wlanapmode == 3 || $wlanapmode == 4){
			
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
/*-- advanced qos --*/

       /* ----------------harry add for new logic for 2590---------------------------------------------------------------------------------------------------------*/
       /*-------------------*/
       $ruleindex = 0;
        $default_index = 9;


  	echo $TC." qdisc add dev ".$UPLINK_INF." handle 1: root htb default ".$default_index." \n";
	echo $TC." qdisc add dev ".$DOWNLINK_INF." handle 2: root htb default  ".$default_index." \n";

      $default_rate =50000;
       $default_ceil_all =150000;
      $default_ceil =50000;
      	echo $TC." class add dev ".$UPLINK_INF." parent 1: classid 1:1 htb rate ".$default_ceil_all.$K." \n";
       echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:".$default_index." htb prio 8 rate ".$default_rate.$K." ceil ".$default_ceil.$K."  \n";
       	echo $TC." class add dev ".$DOWNLINK_INF." parent 2: classid 2:1 htb rate ".$default_ceil_all.$K." \n";
	echo $TC." class add dev ".$DOWNLINK_INF." parent 2:2 classid 2:".$default_index." htb prio 8 rate ".$default_rate.$K." ceil ".$default_ceil.$K."  \n";
       $forward_unlist = 1;
       
	if($forward_unlist == 0)
	{
		echo $TC." filter add dev ".$UPLINK_INF." parent ".$classid." protocol all prio ".$default_index." u32 match u8 00 00 flowid 1:".$markid."1".$default_index." police rate 1bps burst 1 drop \n";
	}
	
     /*shjtest mask  echo $TC." qdisc add dev ".$UPLINK_INF." parent 1:0 handle 1: pfifo\n";*/

       for("/trafficctrl/trafficrule")
	{
         $ruleindex++;       
         $markid=$ruleindex+10;   /*from 11 to 26*/
          $temmarkid=$markid+16;/*from 27 to 42*/
        if($ruleindex<=16)
        {
        

       $ruleenable=query("/trafficctrl/trafficrule:".$ruleindex."/enable");

       if($ruleenable ==1)
       {
       $UPLINK = query("/trafficctrl/trafficrule:".$ruleindex."/maxwidth");
       $DOWNLINK= query("/trafficctrl/trafficrule:".$ruleindex."/maxwidth");
        $FlowType = query("/trafficctrl/trafficrule:".$ruleindex."/flowcontroltype");
	/*$UPLINK=$UPLINK*1024;*/
	if($UPLINK == "" || $UPLINK == 0){
		echo "echo max bandwidth set error. \n");
		exit;
	}

	if($DOWNLINK == "" || $DOWNLINK == 0){
		echo "echo max bandwidth set error. \n");
		exit;
	}
        $rulename=query("/trafficctrl/trafficrule:".$ruleindex."/name");

        echo "echo add rule:".$rulename." to traffic management\n");
        $srcnodeindex=0;
        for("/trafficctrl/trafficrule:".$ruleindex."/srcnode/node")
	  {
             $srcnodeindex++;
             $dstnodeindex=0;
             $srcnodename=query("/trafficctrl/trafficrule:".$ruleindex."/srcnode/node:".$srcnodeindex."/name");
           
             for("/trafficctrl/trafficrule:".$ruleindex."/dstnode/node")
	      {
                     $dstnodeindex++;
                     $dstnodename=query("/trafficctrl/trafficrule:".$ruleindex."/dstnode/node:".$dstnodeindex."/name");
                     if($srcnodename!="" && $dstnodename!="")
                     {
                        /*echo "vethctl -n ".$srcnodename." ".$dstnodename." ".$ruleindex." \n");*/
                        
                        echo "ebtables -A FORWARD -i ".$srcnodename." -o ".$dstnodename." -j mark --set-mark ".$markid." \n");                       
                        echo "ebtables -A FORWARD -i ".$dstnodename." -o ".$srcnodename." -j mark --set-mark ".$temmarkid." \n");
                     }
	      }
         }


        /* Flow control type setting*/
        
        /* 0  fixed bandwidth */
        $burststr="";
        
        if($FlowType==2)
        {
          /* 1  max(dynamic) bandwidth */
  	 /* 1  minum bandwidth */
        $UPLINK_Rate =200;
        $UPLINK_CEIL =$UPLINK;

        $DOWNLINK_Rate =200;
        $DOWNLINK_CEIL =$DOWNLINK;
         }
        
        else if($FlowType==1)
        {
        /* 1  minum bandwidth */
        $UPLINK_Rate =$UPLINK;
        $UPLINK_CEIL =$default_ceil;

        $DOWNLINK_Rate =$DOWNLINK;
        $DOWNLINK_CEIL =$default_ceil;
         }
         else
        {
    
         $UPLINK_Rate =$UPLINK;
        $UPLINK_CEIL =$UPLINK;

        $DOWNLINK_Rate =$DOWNLINK;
        $DOWNLINK_CEIL =$DOWNLINK;
        $burststr=" burst 20k cburst 20k ";

        }
         
	/*---------------Create queues------------------------------------------------------*/
	/*
	echo $TC." qdisc add dev ".$UPLINK_INF." root handle 1: htb default 1500 ;\n";
	echo $TC." class add dev ".$UPLINK_INF." parent 1: classid 1:1 htb rate ".$UPLINK_Rate.$K." ceil ".$UPLINK_CEIL.$K.$burststr." ;\n";
	*/
       $smarkid=$markid+1;
       echo $TC." class add dev ".$UPLINK_INF." parent 1:1 classid 1:".$smarkid." htb rate ".$UPLINK_Rate.$K." ceil ".$UPLINK_CEIL.$K.$burststr." \n";
       echo $TC." filter add dev ".$UPLINK_INF." parent 1: protocol all prio 1 handle ".$markid." fw flowid 1:".$smarkid."\n";	

       
       echo $TC." class add dev ".$DOWNLINK_INF." parent 2:1 classid 2:".$smarkid." htb rate ".$DOWNLINK_Rate.$K." ceil ".$DOWNLINK_CEIL.$K.$burststr." \n";
       echo $TC." filter add dev ".$DOWNLINK_INF." parent 2: protocol all prio 1 handle ".$temmarkid." fw flowid 2:".$smarkid."\n";	

	
	
}
}
}

   

}
else
{
	$veth_exist = query("/runtime/stats/trafficctrl/veth_exist");
		
	echo "echo Stop traffic manager system ... \n";

	if($veth_exist == 1){
		echo $TC." qdisc del dev ".$UPLINK_INF." root \n";
		if($qos_run != 1){
			echo "rmmod vethdev \n";
			echo "rgdb -i -s /runtime/stats/trafficctrl/veth_exist 0 \n";
		}
	}else{
		//echo "echo virtual module is not exist. \n";
	}
       echo "ebtables -F\n";
	echo "rgdb -i -s /runtime/stats/wtp/trafficctrl/trafficmgr_run 0 \n";
}

?>
