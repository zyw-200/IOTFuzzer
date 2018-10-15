<?
$wlanif_g = query("/runtime/layout/wlanif_g");//ath0
$IWPRIV="iwpriv ".$wlanif_g;
$IWCONF="iwconfig ".$wlanif_g;
if ($generate_start==1)
{
	if ($wlan_ap_operate_mode_g!=0) /*close aparray if APC&WDS&WDS+AP mode*/
	{
		echo "echo not pure AP mode, so disable AP ARRAY ... > /dev/console\n";
		echo "rgdb -s /wlan/inf:1/aparray_enable 0  \n";	 
		echo "killall neapc> /dev/console\n";  		
		echo "rgdb -i -d /runtime/wlan/inf:1/ap_array_members/list \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/slaver_record \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/arrayslaver/list \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/arraymaster/list \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/scan_table \n";
	}   
	if ($wlan_ap_operate_mode_g==1) // APC mode
	{
		$clonetype = query("/wlan/inf:1/macclone/type");
		if ($clonetype==1){/*auto*/
			echo "brctl setcloneaddr br0 00:00:00:00:00:00\n";
                        echo "brctl clonetype br0 1\n";
                        echo "brctl apc br0 1"."\n";
                        echo "cloned\n";
		}else{/*Disable and Manual*/
			require($template_root."/__wlan_apcmode_g.php");
			//	exit;
		}
	}else if ($wlan_ap_operate_mode_g==2)
	{
		require($template_root."/__wlan_aprmode.php");
		exit;
	}
	else if ($wlan_ap_operate_mode_g==3 || $wlan_ap_operate_mode_g==4) // WDS or WDS+AP mode
	{
		require($template_root."/__wlan_wdsmode_g.php");
	//	exit;
	}
	else{ // AP mode

	$EmRadiusState  =   query("/wlan/inf:1/wpa/embradius/state");
	$MultiEmRadiusState_index1  =   query("/wlan/inf:1/multi/index:1/embradius_state");
	$MultiEmRadiusState_index2  =   query("/wlan/inf:1/multi/index:2/embradius_state");
	$MultiEmRadiusState_index3  =   query("/wlan/inf:1/multi/index:3/embradius_state");
	$MultiEmRadiusState_index4  =   query("/wlan/inf:1/multi/index:4/embradius_state");
	$MultiEmRadiusState_index5  =   query("/wlan/inf:1/multi/index:5/embradius_state");
	$MultiEmRadiusState_index6  =   query("/wlan/inf:1/multi/index:6/embradius_state");
	$MultiEmRadiusState_index7  =   query("/wlan/inf:1/multi/index:7/embradius_state");

	$EmRadiusCertState  =   query("/wlan/inf:1/wpa/embradius/certstate");
	$EmRadiusEAPUser_conf   =   "/var/hostapd_g.eap_user";
	$EmRadiusDefaultCA  = "/etc/templates/certs/cacert.pem";
	$EmRadiusDefaultCAKey   =   "/etc/templates/certs/cakey.key";
	$EmRadiusDefaultCAPass  =   "DEFAULT";
	$EmRadiusEapUser    =   "/wlan/inf:1/wpa/embradius/eap_user/index";
	$EmRadiusE_SrvCert  =   "/var/etc/certs/hostapd/eca_srvcert.pem";
	$EmRadiusE_SrvKey   =   "/var/etc/certs/hostapd/eca_srvkey.key";
	$EmRadiusE_Srv_KeyPass  =   query("/wlan/inf:1/wpa/embradius/eca_keypasswd");
	// Disable IPv6 on wireless interface
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/wifi0/disable_ipv6;\n";

	anchor("/wlan/inf:1");
	$channel = query("channel");         if (query("autochannel")==1) { $channel=0; }
	$bintval = query("beaconinterval");
	$cwmmode = query("cwmmode");
	$shortgi = query("shortgi");
	$g_mode = query("wlmode");
	$ssidhidden = query("ssidhidden");
	$dtim       = query("dtim");
	$wmmenable  = query("wmm/enable");
	$ampdu = query("ampdu");
	$ampdulimit = query("ampdulimit");
	$ampduframes = query("ampduframes");
	$autochannel = query("autochannel");
	$epartition = query("e_partition");
	$assoclimitenable   = query("assoc_limit/enable");
	$assoclimitnumber   = query("assoc_limit/number");
	$wpartition = query("w_partition");
	$acktimeout = query("acktimeout_g");
	$fixedrate  = query("fixedrate");
	$mcastrate  = query("mcastrate");/*add for mcast rate by yuda*/
	$wlan_utilization  = query("wlan_bytes_lim");
	$wlan_time = query("wlan_bytes_time");
	$ratectl = query("ratectl");
	$manrate = query("manrate");
	$manretries = query("manretries");
	$uapsd = query("uapsd");
	$multi_pri_state = query("multi/pri_by_ssid");
	$pri_bit = query("pri_bit");
	$zonedefence = query("zonedefence");	
	$coexistence = query("coexistence/enable");
	$INACT	=	query("inact");

//ELBOX_PROGS_PRIV_ACL_AGINGOUT_BY_RSSI_DATARATE
        $agbyrssiordrstatus=query("agbyrssiordrstatus");  //0 dsiable, 1 rssi,2 datarate
        $aclbyrssi=query("aclbyrssi");
        $aclallbywlmode=query("aclallbywlmode");
        $aclbywlmode=query("aclbywlmode");
        $agingbyrssithreshhold=query("agingbyrssithreshhold");
        $aclbyrssithreshhold=query("aclbyrssithreshhold");
        $agingbydrthreshhold=query("agingbydrthreshhold");
        
        $agingoutbyrssi= "0"; //query("agingbyrssi");
        $agingbydatarate="0"; //query("agingbydatarate");
        if($agbyrssiordrstatus==1)
        {
           $agingoutbyrssi=1;
        }

        if($agbyrssiordrstatus==2)
        {
           $agingbydatarate=1;
        }
// end of ELBOX_PROGS_PRIV_ACL_AGINGOUT_BY_RSSI_DATARATE        
	//$wepmode = query("wpa/wepmode");
	//$rxmit_first = query("rxmit_first");
	//$rxmit_last = query("rxmit_last");
	//$txmit_first = query("txmit_first");
	//$txmit_last = query("txmit_last");

	echo "ifconfig wifi0 hw ether ".$wlanmac."\n";
	echo "wlanconfig ".$wlanif_g." create wlandev wifi0 wlanmode ap\n";

	// Disable IPv6 on wireless interface
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$wlanif_g."/disable_ipv6;\n";	

	echo "echo Start WLAN interface ".$wlanif_g." ... > /dev/console\n";
	
	echo "iwpriv wifi0 HALDbg 0x0\n";
	echo $IWPRIV." dbgLVL 0x100\n";
	echo "iwpriv wifi0 ATHDebug 0x0\n";
	echo "iwpriv wifi0 disablestats 0\n";
	
	if($EmRadiusState == 1||$MultiEmRadiusState_index1==1||$MultiEmRadiusState_index2==1||
	$MultiEmRadiusState_index3==1||$MultiEmRadiusState_index4==1||$MultiEmRadiusState_index5==1||
	$MultiEmRadiusState_index6==1||$MultiEmRadiusState_index7==1)
	{
		fwrite($EmRadiusEAPUser_conf, "# Phase 1 users\n");
		fwrite2($EmRadiusEAPUser_conf, "*\tPEAP,TTLS\n");
		fwrite2($EmRadiusEAPUser_conf, "# Phase 2 (tunnelled within EAP-PEAP/EAP-TTLS) users\n");
		for($EmRadiusEapUser)
		{
			$EmRadiusEapUserName = query($EmRadiusEapUser.":".$@."/name");
			$EmRadiusEapUserEnable = query($EmRadiusEapUser.":".$@."/enable");
        		if($EmRadiusEapUserName != "" && $EmRadiusEapUserEnable == 1)
			{
				$EmRadiusEapUserPasswd = query($EmRadiusEapUser.":".$@."/passwd");
				fwrite2($EmRadiusEAPUser_conf, "\"".$EmRadiusEapUserName."\"\tMSCHAPV2\t\"".$EmRadiusEapUserPasswd."\"\t[2]\n");
			}
		}

	}

	// common RF setting start
	echo "ifconfig ".$wlanif_g." txqueuelen 1000\n";
	echo "ifconfig wifi0 txqueuelen 1000\n";

	//if($rxmit_first != "")	{ echo "iwpriv wifi0 rxmit_first ".$rxmit_first."\n"; }
	//if($rxmit_last != "")	{ echo "iwpriv wifi0 rxmit_last ".$rxmit_last."\n"; }
	//if($txmit_first != "")	{ echo "iwpriv wifi0 txmit_first ".$txmit_first."\n"; }
	//if($txmit_last != "")	{ echo "iwpriv wifi0 txmit_last ".$txmit_last."\n"; }

	if($autochannel==0){
		if($channel>14){
			$channel=6;
		}
	}

	//1:b/g/n mix, 2:b/g mode  3:only n 
		//(20) 1:b/g/n mix, 2.b/g mode  3:only n   
		if ($cwmmode == 0){
			if($g_mode == 1) {echo $IWPRIV." mode 11NGHT20\n";}
			else if($g_mode == 3) {echo $IWPRIV." mode 11NGHT20\n";}
			else if($g_mode == 2) {echo $IWPRIV." mode 11G\n";}
			else {echo $IWPRIV." mode 11NGHT20\n";}
		}
		//1--->11b/g/n, 3--->only n	
		else{//cwmmode=1 HT20/40 mode
			if($autochannel==1){//autochannel enable
				echo $IWPRIV." mode 11NGHT40\n";
			}
			else{ //autochannel disable
				if($channel<5){//channel 1~4
					echo $IWPRIV." mode 11NGHT40PLUS\n";
				}
				else if($channel<=11){//channel 5~11
					echo $IWPRIV." mode 11NGHT40MINUS\n";
				}
				else{//channel 12,13 for JP
					echo $IWPRIV." mode 11NGHT20\n";
				}
			}
		}
	if($g_mode==1 || $g_mode==3)	{echo "iwpriv wifi0 ForBiasAuto 1\n";}//if in bands of 11NG, require ANI processing
	//1:b/g/n mix, 2:b/g mode  3:only n 
	if($g_mode == 1 || $g_mode == 2){
		echo $IWPRIV." puren 0\n";
		echo $IWPRIV." pureg 0\n";
	}		
	else if($g_mode == 3){
		echo $IWPRIV." pureg 0\n";
		echo $IWPRIV." puren 1\n";
	}
	else{
		echo $IWPRIV." pureg 0\n";
		echo $IWPRIV." puren 0\n";
	}
	if($ampdu!="") {echo "iwpriv wifi0 AMPDU ".$ampdu."\n";}
	else {echo "iwpriv wifi0 AMPDU 1 \n";}	
	if($ampduframes!="") {echo "iwpriv wifi0 AMPDUFrames ".$ampduframes."\n";}
	else {echo "iwpriv wifi0 AMPDUFrames 32 \n";}
	if($ampdulimit!="")	{echo "iwpriv wifi0 AMPDULim ".$ampdulimit."\n";}
	else {echo "iwpriv wifi0 AMPDULim 50000 \n";}
	
	echo "iwconfig ".$wlanif_g." essid \"".get("s","/wlan/inf:1/ssid")."\" freq ".$channel."\n";
	echo "iwconfig ".$wlanif_g." mode master\n";

	

	echo "iwpriv wifi0 txchainmask 3\n";
	echo "iwpriv wifi0 rxchainmask 3\n"; 

	// common RF setting end

	if ($ssidhidden!="")    { echo $IWPRIV." hide_ssid ".$ssidhidden."\n"; }

	echo "iwpriv wifi0 ANIEna 1 \n";
	echo "iwpriv wifi0 noisespuropt 1\n";
	
//	echo "iwpriv wifi0 burst 1\n"; // for intel6300 downlink/bidi throughput issue

	if ($uapsd!="")	{echo $IWPRIV." uapsd ".$uapsd."\n"; }

	if ($dtim!="")	{ echo $IWPRIV." dtim_period ".$dtim."\n"; }
	/* e_partition 2008-02-1 start */
	if ($epartition!="")  { echo "brctl e_partition br0 ".$epartition."\n";}
	else	{ echo "brctl e_partition br0 0\n"; }
	/* e_partition 2008-02-1 end */
	/* w_partition 2008-03-22 start */
	if ($wpartition==2)  /* guest mode*/
        {
            echo $IWPRIV." ap_bridge 0 \n";
	    echo "brctl w_partition br0 ".$wlanif_g." 1 \n";
        }
        else if ($wpartition==1)
        {
            echo $IWPRIV." ap_bridge 0 \n";
            echo "brctl w_partition br0 ".$wlanif_g." 0\n";
        }
        else
        {
            echo $IWPRIV." ap_bridge 1\n";
            echo "brctl w_partition br0 ".$wlanif_g." 0\n";
        }
	/* w_partition 2008-03-22 end */
	
	if ($wmmenable>0)       { echo $IWPRIV." wmm 1\n"; }
	else                    { echo $IWPRIV." wmm 0\n"; }

	if($fixedrate==11)       {echo "iwconfig ".$wlanif_g." rate 54M\n";}
        else if($fixedrate==10)  {echo "iwconfig ".$wlanif_g." rate 48M\n";}
        else if($fixedrate==9)  {echo "iwconfig ".$wlanif_g." rate 36M\n";}
        else if($fixedrate==8)  {echo "iwconfig ".$wlanif_g." rate 24M\n";}
        else if($fixedrate==7)  {echo "iwconfig ".$wlanif_g." rate 18M\n";}
        else if($fixedrate==6)  {echo "iwconfig ".$wlanif_g." rate 12M\n";}
        else if($fixedrate==5)  {echo "iwconfig ".$wlanif_g." rate 9M\n";}
        else if($fixedrate==4)  {echo "iwconfig ".$wlanif_g." rate 6M\n";}
        else if($fixedrate==3)  {echo "iwconfig ".$wlanif_g." rate 11M\n";}
        else if($fixedrate==2)  {echo "iwconfig ".$wlanif_g." rate 5.5M\n";}
        else if($fixedrate==1)  {echo "iwconfig ".$wlanif_g." rate 2M\n";}
        else if($fixedrate==0)  {echo "iwconfig ".$wlanif_g." rate 1M\n";}
        else if($ratectl>1)
				{
						// if rate control is not auto, set the manual settings
					echo $IWPRIV." set11NRates ".$manrate."\n";// seems default 0x8c8c8c8c will cause can not ping through, joe
					echo $IWPRIV." set11NRetries ".$manretries."\n";
				}
        else {echo "iwconfig ".$wlanif_g." rate auto\n";}
	
	if($acktimeout != "")	{ echo "iwpriv wifi0 acktimeout ".$acktimeout."\n"; }//default to 48
	else	{ echo "iwpriv wifi0 acktimeout 48\n"; }

	require($template_root."/__wlan_acl_g.php");

	if($multi_pri_state==1)	
	{ 
		echo  $IWPRIV." pristate 1\n";
		echo  $IWPRIV." pribit ".$pri_bit."\n";
	}
	else	{echo  $IWPRIV." pristate 0\n";}

/*add for mcast rate by yuda start*/
	if($mcastrate!=0){
    	if($mcastrate==1){
    		echo $IWPRIV." mcast_rate 1000\n";
    	}
    	else if($mcastrate==2){
    		echo $IWPRIV." mcast_rate 2000\n";
    	}
    	else if($mcastrate==3){
    		echo $IWPRIV." mcast_rate 5500\n";
    	}
    	else if($mcastrate==4){
    		echo $IWPRIV." mcast_rate 11000\n";
    	}
    	else if($mcastrate==5){
    		echo $IWPRIV." mcast_rate 6000\n";
    	}
    	else if($mcastrate==6){
    		echo $IWPRIV." mcast_rate 9000\n";
    	}
    	else if($mcastrate==7){
    		echo $IWPRIV." mcast_rate 12000\n";
    	}
    	else if($mcastrate==8){
    		echo $IWPRIV." mcast_rate 18000\n";
    	}
    	else if($mcastrate==9){
    		echo $IWPRIV." mcast_rate 24000\n";
    	}
    	else if($mcastrate==10){
    		echo $IWPRIV." mcast_rate 36000\n";
    	}
    	else if($mcastrate==11){
    		echo $IWPRIV." mcast_rate 48000\n";
    	}
    	else if($mcastrate==12){
    		echo $IWPRIV." mcast_rate 54000\n";
    	}
    	else if($mcastrate==13){
    		echo $IWPRIV." mcast_rate 6500\n";
    	}
    	else if($mcastrate==14){
    		echo $IWPRIV." mcast_rate 13000\n";
    	}
    	else if($mcastrate==15){
    		echo $IWPRIV." mcast_rate 19500\n";
    	}
    	else if($mcastrate==16){
    		echo $IWPRIV." mcast_rate 26000\n";
    	}
    	else if($mcastrate==17){
    		echo $IWPRIV." mcast_rate 39000\n";
    	}
    	else if($mcastrate==18){
    		echo $IWPRIV." mcast_rate 52000\n";
    	}
    	else if($mcastrate==19){
    		echo $IWPRIV." mcast_rate 58500\n";
    	}
    	else if($mcastrate==20){
    		echo $IWPRIV." mcast_rate 65000\n";
    	}
    	else if($mcastrate==21){
    		echo $IWPRIV." mcast_rate 78000\n";
    	}
    	else if($mcastrate==22){
    		echo $IWPRIV." mcast_rate 104000\n";
    	}
    	else if($mcastrate==23){
    		echo $IWPRIV." mcast_rate 117000\n";
    	}
    	else if($mcastrate==24){
    		echo $IWPRIV." mcast_rate 130000\n";
    	}
     	else {
    		echo $IWPRIV." mcast_rate 11000\n";
    	}   	
    	}        
    	/*add for mcast rate by yuda end*/

	/*Please put the settings which will affect all VAPs here, and no need to do it in multi-ssid, vice versa*/
	/*Common setting for all VAPs of ATH DEV start*/
	/* erial move to __wlan_device_g_up.php, for dual band ap scan.
	echo $IWPRIV." apband 0\n"; //show ap band in wireless driver*/
	if($shortgi != "")	{ echo $IWPRIV." shortgi ".$shortgi."\n"; }

	if ($cwmmode == 1){
		if($autochannel==0){//if autochannel is true, let driver to do this setting
			if($channel<5){
			}
			else if($channel<=11){
			}
			else{//channel 12,13 does not support 40MHz channel width
				$cwmmode = 0;
				set("/wlan/inf:1/cwmmode",$cwmmode);
			}
		}
	}
	if($assoclimitenable == 1){
	if($g_mode == 1 || $g_mode == 3){
		if($cwmmode==0){//default 60M*60s
			if($wlan_utilization==1)	{echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
			else if ($wlan_utilization==2) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 377487360\n";}
			else if ($wlan_utilization==3) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 283115520\n";}
			else if ($wlan_utilization==4) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 188743680\n";}
			else if ($wlan_utilization==5) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 94371840\n";}
			else if ($wlan_utilization==6) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 0\n";}
			else  {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
		}
		else{//cwmmode=1 //default 90M*60s
			if($wlan_utilization==1)    {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
			else if ($wlan_utilization==2) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 566231040\n";}
			else if ($wlan_utilization==3) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 424673280\n";}
			else if ($wlan_utilization==4) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 283115520\n";}
			else if ($wlan_utilization==5) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 141557760\n";}
			else if ($wlan_utilization==6) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 0\n";}
			else  {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
		}
	}
	else{ // b/g mode only in HT20 mode default 20M*60s
		echo "iwpriv ".$wlanif_g." bytes_time 60\n";
		if($wlan_utilization==1)    {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
		else if ($wlan_utilization==2) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 125829120\n";}
		else if ($wlan_utilization==3) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 94371840\n";}
		else if ($wlan_utilization==4) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 62914560\n";}
		else if ($wlan_utilization==5) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 31457280\n";}
		else if ($wlan_utilization==6) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 0\n";}
		else  {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
	}
	}
	else  {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
	echo $IWPRIV." doth 0\n";//no dfs for 2.4G
	echo $IWPRIV." extprotspac 0\n";

	$preferred5g = query("/sys/wlan/preferred5g");	
        if($preferred5g == 1)
        {
                $pre5g_rssi = query("/sys/wlan/pre5g_rssi");
                $pre5g_refuse = query("/sys/wlan/pre5g_refuse");
                $pre5g_diff = query("/sys/wlan/pre5g_diff");
                $pre5g_age = query("/sys/wlan/pre5g_age");

                echo $IWPRIV." preferred5g 1\n";
                if($pre5g_rssi != ""){ echo $IWPRIV." pre5g_rssi ".$pre5g_rssi."\n"; }
                if($pre5g_refuse != ""){ echo $IWPRIV." pre5g_refuse ".$pre5g_refuse."\n"; }
                if($pre5g_diff != ""){ echo $IWPRIV." pre5g_diff ".$pre5g_diff."\n"; } 
                if($pre5g_age != ""){ echo $IWPRIV." pre5g_age ".$pre5g_age."\n"; }
        }
	else { echo $IWPRIV." preferred5g 0\n"; }

	if($bintval!="") {echo $IWPRIV." bintval ".$bintval."\n";}
	else {echo $IWPRIV." bintval 100\n"; }

	if($assoclimitenable == 1)	{echo $IWPRIV." assocenable 1\n";}
	else {echo $IWPRIV." assocenable 0\n";}
	if($assoclimitnumber != "") {echo $IWPRIV." assocnumber ".$assoclimitnumber."\n";}
//ELBOX_PROGS_PRIV_ACL_AGINGOUT_BY_RSSI_DATARATE
       if($agingoutbyrssi == 1)	{

       echo $IWPRIV." agrssi 1\n";

	if($agingbyrssithreshhold != "") {echo $IWPRIV." agrssiset `sh /etc/templates/rssiconvert.sh ".$agingbyrssithreshhold."`\n";}

       }
	else {echo $IWPRIV." agrssi 0\n";}

 	if($agingbydatarate == 1)	{
 	echo $IWPRIV." agdatarate 1\n";
	
	if($agingbydrthreshhold != "") {echo $IWPRIV." agdatartset ".$agingbydrthreshhold."\n";}

 	}
	else {echo $IWPRIV." agdatarate 0\n";}

       if($aclbyrssi == 1)	{
       echo $IWPRIV." aclrssi 1\n";
	if($aclbyrssithreshhold != "") {echo $IWPRIV." aclrssiset `sh /etc/templates/rssiconvert.sh ".$aclbyrssithreshhold."`\n";}

       }
	else {echo $IWPRIV." aclrssi 0\n";}
	
       if($aclallbywlmode == 1)	{echo $IWPRIV." aclwlmode 1\n";}
	else {echo $IWPRIV." aclwlmode 0\n";}

	/* releated to userlimit by ssid
       if($aclbywlmode == 1)	{echo $IWPRIV." aclperwlmode 1\n";}
	else {echo $IWPRIV." aclperwlmode 0\n";}
        */
//ELBOX_PROGS_PRIV_ACL_AGINGOUT_BY_RSSI_DATARATE end
	if ($zonedefence==1)
	{
                echo $IWPRIV." zonedefence 1\n";
                require($template_root."/zonedefence_g.php");
  }
  else
  {
                echo $IWPRIV." zonedefence 0\n";
  }
  if($coexistence == 1)
	{
			echo $IWPRIV." disablecoext 0\n";
	}
	else
	{
			echo $IWPRIV." disablecoext 1\n";
	}
	echo $IWPRIV." inact_auth ".$INACT."\n";
	/*Common setting for all VAPs of ATH DEV end*/

	$auth_mode = query("/wlan/inf:1/authentication");
	if ($auth_mode>1){  
	}
	else { 
		require($template_root."/__auth_openshared_g.php");
	}

	require($template_root."/multi_ssid_run_g.php");
	}
}//if end
else
{
	$igmpsnoop = query("/wlan/inf:1/igmpsnoop");
	// multi-ssid must be normal AP mode. /* Jack add multi_ssid 31/03/07 */
	$multi_total_state = query($WLAN_g."/multi/state");
	if ($multi_total_state == 1)
	{
		if ($wlan_ap_operate_mode_g==1 || $wlan_ap_operate_mode_g==2 || $wlan_ap_operate_mode_g==4) /*    WDS_OVER_MULTI_SSID_080422 */
		{   // 1:apc, 2:apr, 4:WDS without AP 
			echo "echo multi-ssid must be normal AP mode or WDS mode! > /dev/console\n";
			set($WLAN_g."/ap_mode", 0);
		}
	}
	if ($wlan_ap_operate_mode_g==3 || $wlan_ap_operate_mode_g==4)
	{
		require($template_root."/__wlan_wdsmode_g.php");
	//	exit;
	}
	else{
	if (query("/wlan/inf:1/enable")!=1)
	{
		echo "echo WLAN is disabled ! > /dev/console\n";
		exit;
	}
	
	echo "brctl e_partition br0 0\n";
	
	$wlxmlpatch_g_pid = "/var/run/wlxmlpatch_g.pid";
	echo "if [ -f ".$wlxmlpatch_g_pid." ]; then\n";
	echo "kill \`cat ".$wlxmlpatch_g_pid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$wlxmlpatch_g_pid."\n";
	echo "fi\n\n";		
    $wlxmlpatch_pid = "/var/run/wlxmlpatch.pid";
    echo "if [ -f ".$wlxmlpatch_pid." ]; then\n";
    echo "kill \`cat ".$wlxmlpatch_pid."\` > /dev/null 2>&1\n";
    echo "rm -f ".$wlxmlpatch_pid."\n";
    echo "fi\n\n";
    if ($wlan_ap_operate_mode==2){      echo "killall wlxmlpatch\n";    }
	/* IGMP Snooping dennis 2008-01-29 start */
	if ($igmpsnoop == 1){
	$ap_igmp_pid = "/var/run/ap_igmp_g.pid";
	echo "echo disable > /proc/net/br_igmp_ap_br0\n";
	echo "echo unsetwl ".$wlanif_g." > /proc/net/br_igmp_ap_br0\n";
	echo "brctl igmp_snooping br0 0\n";
	echo "if [ -f ".$ap_igmp_pid." ]; then\n";
	echo "kill \`cat ".$ap_igmp_pid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$ap_igmp_pid."\n";
	echo "fi\n\n";
	}
	/* IGMP Snooping dennis 2008-01-29 end */
	
	echo "ifconfig ".$wlanif_g." down"."\n";
	echo "sleep 2\n";
	echo "brctl delif br0 ".$wlanif_g."\n";

	echo "sleep 1\n";
	echo "brctl apc br0 0"."\n";
	echo "iwconfig ".$wlanif_g." key off\n";
	}
}
?>
