<?
$wlanif_a = query("/runtime/layout/wlanif_a");//ath16
$IWPRIV="iwpriv ".$wlanif_a;
$WLAN = "/wlan/inf:2/";
$countrycode= query("/runtime/nvram/countrycode");
if ($generate_start==1)
{
	if ($wlan_ap_operate_mode_a!=0)  /*close aparray if APC&WDS&WDS+AP mode*/
	{
		echo "echo not pure AP mode or WDS/AP, so disable AP ARRAY ... > /dev/console\n";
	  	echo "rgdb -s /wlan/inf:1/aparray_enable 0  \n";	  	 
		echo "killall neapc> /dev/console\n";  		
		echo "rgdb -i -d /runtime/wlan/inf:1/ap_array_members/list \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/slaver_record \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/arrayslaver/list \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/arraymaster/list \n";
		echo "rgdb -i -d /runtime/wlan/inf:1/scan_table \n";
	}  
	/*for JP //OUTDOOR_APPS_JP +++ */
	$is_outdoor = query("/wlan/application");
	if ($wlan_ap_operate_mode_a==3 || $wlan_ap_operate_mode_a==4) // WDS or WDS+AP mode
	{
		if ($countrycode==392 && $is_outdoor==1 )
		{
			set($WLAN."/ap_mode", 0);
			echo "echo Because japan outdoor has no WDS DFS channel, so WDS change to Pure AP mode! > /dev/console\n";
			$wlan_ap_operate_mode_a = query($WLAN."/ap_mode");
		}
	}		
		//echo "echo wlan_ap_operate_mode is ".$wlan_ap_operate_mode." ... > /dev/console\n";
	/*for JP //OUTDOOR_APPS_JP --- */
	if ($wlan_ap_operate_mode_a==1)
	{
                $clonetype = query("/wlan/inf:1/macclone/type");
                if ($clonetype==1){/*auto*/
                        echo "brctl setcloneaddr br0 00:00:00:00:00:00\n";
                        echo "brctl clonetype br0 1\n";
                        echo "brctl apc_a br0 1"."\n";
                        echo "cloned\n";
                }else{/*Disable and Manual*/
		require($template_root."/__wlan_apcmode_a.php");
		//	exit;
		}
	}
	else if ($wlan_ap_operate_mode_a==3 || $wlan_ap_operate_mode_a==4)
	{
		require($template_root."/__wlan_wdsmode_a.php");
	//	exit;
	}
	else{
	/* common cmd */
//	$IWPRIV="iwpriv ".$wlanif_a;
	$EmRadiusState  =   query("/wlan/inf:2/wpa/embradius/state");
	$MultiEmRadiusState_index1  =   query("/wlan/inf:2/multi/index:1/embradius_state");
	$MultiEmRadiusState_index2  =   query("/wlan/inf:2/multi/index:2/embradius_state");
	$MultiEmRadiusState_index3  =   query("/wlan/inf:2/multi/index:3/embradius_state");
	$MultiEmRadiusState_index4  =   query("/wlan/inf:2/multi/index:4/embradius_state");
	$MultiEmRadiusState_index5  =   query("/wlan/inf:2/multi/index:5/embradius_state");
	$MultiEmRadiusState_index6  =   query("/wlan/inf:2/multi/index:6/embradius_state");
	$MultiEmRadiusState_index7  =   query("/wlan/inf:2/multi/index:7/embradius_state");

	$EmRadiusCertState  =   query("/wlan/inf:2/wpa/embradius/certstate");
	$EmRadiusEAPUser_conf   =   "/var/hostapd_a.eap_user";
	$EmRadiusDefaultCA  = "/etc/templates/certs/cacert.pem";
	$EmRadiusDefaultCAKey   =   "/etc/templates/certs/cakey.key";
	$EmRadiusDefaultCAPass  =   "DEFAULT";
	//Internal Radius EAP user, the same for 2.4G and 5G
	$EmRadiusEapUser    =   "/wlan/inf:1/wpa/embradius/eap_user/index";
	$EmRadiusE_SrvCert  =   "/var/etc/certs/hostapd/eca_srvcert.pem";
	$EmRadiusE_SrvKey   =   "/var/etc/certs/hostapd/eca_srvkey.key";
	$EmRadiusE_Srv_KeyPass  =   query("/wlan/inf:2/wpa/embradius/eca_keypasswd");
	// Disable IPv6 on wireless interface
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/wifi1/disable_ipv6;\n";

	anchor("/wlan/inf:2");
	$channel = query("channel");         if (query("autochannel")==1) { $channel=0; }
	$bintval = query("beaconinterval");
	$cwmmode = query("cwmmode");
	$shortgi = query("shortgi");
	$a_mode = query("wlmode");
	$ssidhidden = query("ssidhidden");
	$dtim       = query("dtim");
	$aniena		= query("aniena");
	$wmmenable  = query("wmm/enable");
	$ampdu = query("ampdu");
	$ampdulimit = query("ampdulimit");
	$ampduframes = query("ampduframes");
	$autochannel = query("autochannel");
	$epartition = query("e_partition");
	$assoclimitenable   = query("assoc_limit/enable");
	$assoclimitnumber   = query("assoc_limit/number");
	$wpartition = query("w_partition");
	$acktimeout = query("acktimeout_a");
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
	$INACT 	= query("inact");
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
	echo "ifconfig wifi1 hw ether ".$wlanmac_a."\n";//joe
	echo "wlanconfig ".$wlanif_a." create wlandev wifi1 wlanmode ap\n";//joe
	echo "echo Start WLAN interface ".$wlanif_a." ... > /dev/console\n";

	// Disable IPv6 on wireless interface
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$wlanif_a."/disable_ipv6;\n";
	
	echo $IWPRIV." dbgLVL 0x100\n";
	echo "iwpriv wifi1 enable_ol_stats 1\n";
	
	// common RF settings start
	echo "ifconfig ".$wlanif_a." txqueuelen 1000\n";
	echo "ifconfig wifi1 txqueuelen 1000\n";
	
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
	//if($rxmit_first != "")	{ echo "iwpriv wifi1 rxmit_first ".$rxmit_first."\n"; }
	//if($rxmit_last != "")	{ echo "iwpriv wifi1 rxmit_last ".$rxmit_last."\n"; }
	//if($txmit_first != "")	{ echo "iwpriv wifi1 txmit_first ".$txmit_first."\n"; }
	//if($txmit_last != "")	{ echo "iwpriv wifi1 txmit_last ".$txmit_last."\n"; }
	
	if($autochannel==0){
		if($channel<36){
			$channel=36;
		}
	}


	// 1:a/n, 2:a, 3:n 4.a/n/ac
	if ($cwmmode == 0){
		if($a_mode == 4) {echo $IWPRIV." mode 11ACVHT20\n";}
		else if($a_mode == 2) {echo $IWPRIV." mode 11A\n";}
		else {echo $IWPRIV." mode 11NAHT20\n";} // 11n mode
	}
	//1-->NA mix 3--> only n	
	if ($cwmmode == 1){//cwmmode=1 HT20/40 mode
		if($autochannel==1){//autochannel enable
			if($a_mode == 4) {echo $IWPRIV." mode 11ACVHT40\n";}
			else {echo $IWPRIV." mode 11NAHT40\n";}
		}
		else{ //autochannel disable
			if($channel==36||$channel==44||$channel==52||$channel==60||$channel==100||$channel==108||$channel==116||$channel==124||$channel==132||$channel==149||$channel==157){
				if($a_mode == 4) {
					if($countrycode==36/*AU*/&&$channel==116) {echo $IWPRIV." mode 11ACVHT20\n";}
					else if($countrycode==410/*KR*/&&$channel==124) {echo $IWPRIV." mode 11ACVHT20\n";}
					else {echo $IWPRIV." mode 11ACVHT40PLUS\n";}
				}
				else {
					if($countrycode==36/*AU*/&&$channel==116) {echo $IWPRIV." mode 11NAHT20\n";}
					else if($countrycode==410/*KR*/&&$channel==124) {echo $IWPRIV." mode 11NAHT20\n";}
					else {echo $IWPRIV." mode 11NAHT40PLUS\n";}
				}
			}
			else if($channel==40||$channel==48||$channel==56||$channel==64||$channel==104||$channel==112||$channel==120||$channel==128||$channel==136||$channel==153||$channel==161){
				if($a_mode == 4) {
					if($countrycode==158/*TW*/&&$channel==56) {echo $IWPRIV." mode 11ACVHT20\n";}
					else {echo $IWPRIV." mode 11ACVHT40MINUS\n";}
				}
				else {
					if($countrycode==158/*TW*/&&$channel==56) {echo $IWPRIV." mode 11NAHT20\n";}
					else {echo $IWPRIV." mode 11NAHT40MINUS\n";}
				}
			}
			else{
				if($a_mode == 4) {echo $IWPRIV." mode 11ACVHT20\n";}
				else {echo $IWPRIV." mode 11NAHT20\n";} // channel 140, 165
			}
		}
	}
	if ($cwmmode == 2){ // 20/40/80MHz auto
		if($autochannel==1){//autochannel enable
			echo $IWPRIV." mode 11ACVHT80\n";
		}
		else {
			if($countrycode==410/*KR*/) {
				if($channel==124) {echo $IWPRIV." mode 11ACVHT20\n";}
				else if($channel==120||$channel==116) {echo $IWPRIV." mode 11ACVHT40\n";}
				else {echo $IWPRIV." mode 11ACVHT80\n";}
			}
			else if($countrycode==36/*AU*/) {
				if($channel==116||$channel==140||$channel==165) {echo $IWPRIV." mode 11ACVHT20\n";}
				else if($channel==132||$channel==136) {echo $IWPRIV." mode 11ACVHT40\n";}
				else {echo $IWPRIV." mode 11ACVHT80\n";}
			}
			else if($countrycode==158/*TW*/) {
				if($channel==56||$channel==165) {echo $IWPRIV." mode 11ACVHT20\n";}
				else if($channel==60||$channel==64) {echo $IWPRIV." mode 11ACVHT40\n";}
				else {echo $IWPRIV." mode 11ACVHT80\n";}
			}
			else {
				if($channel==140 || $channel==165)	{echo $IWPRIV." mode 11ACVHT20\n";}
				else if($channel==132 || $channel==136)	{echo $IWPRIV." mode 11ACVHT40\n";}
				else {echo $IWPRIV." mode 11ACVHT80\n";}
			}
		}
	}

	//echo "iwpriv wifi1 burst 1\n"; // for intel6300 downlink/bidi throughput issue
	//1--->NA mix 3--> only n
	if($a_mode == 3){
		echo $IWPRIV." puren 1\n";
	}		
	else{
		echo $IWPRIV." puren 0\n";
	}

	echo "iwconfig ".$wlanif_a." essid \"".get("s","/wlan/inf:2/ssid")."\" freq ".$channel."\n";
	echo "iwconfig ".$wlanif_a." mode master\n";

	

	echo "iwpriv wifi1 txchainmask 3\n";
	echo "iwpriv wifi1 rxchainmask 3\n";
	
//	echo "iwpriv wifi1 noisespuropt 1\n";

	// common RF settings end
	
	if ($ssidhidden!="")    { echo $IWPRIV." hide_ssid ".$ssidhidden."\n"; }
	if ($uapsd!="")	{ echo $IWPRIV." uapsd ".$uapsd."\n"; }	

	if ($dtim!="")          { echo $IWPRIV." dtim_period ".$dtim."\n"; }
	
	/* e_partition 2008-02-1 start */
	if ($epartition!="")  { echo "brctl e_partition_a br0 ".$epartition."\n";}
	else            { echo "brctl e_partition_a br0 0\n"; }
	/* e_partition 2008-02-1 end */
	/* w_partition 2008-03-22 start */
	if ($wpartition==2)  /* guest mode*/
        {
            echo $IWPRIV." ap_bridge 0 \n";
        echo "brctl w_partition br0 ".$wlanif_a." 1 \n";
        }
        else if ($wpartition==1)
        {
            echo $IWPRIV." ap_bridge 0 \n";
            echo "brctl w_partition br0 ".$wlanif_a." 0\n";
        }
        else
        {
            echo $IWPRIV." ap_bridge 1\n";
            echo "brctl w_partition br0 ".$wlanif_a." 0\n";
        }
	/* w_partition 2008-03-22 end */
	
	if ($wmmenable>0)       { echo $IWPRIV." wmm 1\n"; }
	else                    { echo $IWPRIV." wmm 0\n"; }

	if($fixedrate==7)	{echo "iwconfig ".$wlanif_a." rate 54M\n";}
	else if($fixedrate==6)	{echo "iwconfig ".$wlanif_a." rate 48M\n";}
	else if($fixedrate==5)	{echo "iwconfig ".$wlanif_a." rate 36M\n";}
	else if($fixedrate==4)	{echo "iwconfig ".$wlanif_a." rate 24M\n";}
	else if($fixedrate==3)	{echo "iwconfig ".$wlanif_a." rate 18M\n";}
	else if($fixedrate==2)	{echo "iwconfig ".$wlanif_a." rate 12M\n";}
	else if($fixedrate==1)	{echo "iwconfig ".$wlanif_a." rate 9M\n";}
	else if($fixedrate==0)	{echo "iwconfig ".$wlanif_a." rate 6M\n";}
	else if($ratectl>1)
	{
			// if rate control is not auto, set the manual settings
		echo $IWPRIV." set11NRates ".$manrate."\n";// seems default 0x8c8c8c8c will cause can not ping through, joe
		echo $IWPRIV." set11NRetries ".$manretries."\n";
	}
	else {echo "iwconfig ".$wlanif_a." rate auto\n";}
	
	if($acktimeout != "")	{ echo $IWPRIV." acktimeout ".$acktimeout."\n"; }//default to 25
	else { echo $IWPRIV." acktimeout 25\n"; }

	require($template_root."/__wlan_acl_a.php");

	if($multi_pri_state==1)	
	{ 
		echo  $IWPRIV." pristate 1\n";
		echo  $IWPRIV." pribit ".$pri_bit."\n";
	}
	else	{echo  $IWPRIV." pristate 0\n";}


        /*add for mcast rate by yuda start */
        if($mcastrate!=0){
    	if($mcastrate==1){
    		echo $IWPRIV." mcast_rate 6000\n";
    	}
    	else if($mcastrate==2){
    		echo $IWPRIV." mcast_rate 9000\n";
    	}
    	else if($mcastrate==3){
    		echo $IWPRIV." mcast_rate 12000\n";
    	}
    	else if($mcastrate==4){
    		echo $IWPRIV." mcast_rate 18000\n";
    	}
    	else if($mcastrate==5){
    		echo $IWPRIV." mcast_rate 24000\n";
    	}
    	else if($mcastrate==6){
    		echo $IWPRIV." mcast_rate 36000\n";
    	}
    	else if($mcastrate==7){
    		echo $IWPRIV." mcast_rate 48000\n";
    	}
    	else if($mcastrate==8){
    		echo $IWPRIV." mcast_rate 54000\n";
    	}
    	else if($mcastrate==9){
    		echo $IWPRIV." mcast_rate 6500\n";
    	}
    	else if($mcastrate==10){
    		echo $IWPRIV." mcast_rate 13000\n";
    	}
    	else if($mcastrate==11){
    		echo $IWPRIV." mcast_rate 19500\n";
    	}
    	else if($mcastrate==12){
    		echo $IWPRIV." mcast_rate 26000\n";
    	}
    	else if($mcastrate==13){
    		echo $IWPRIV." mcast_rate 39000\n";
    	}
    	else if($mcastrate==14){
    		echo $IWPRIV." mcast_rate 52000\n";
    	}
    	else if($mcastrate==15){
    		echo $IWPRIV." mcast_rate 58500\n";
    	}
    	else if($mcastrate==16){
    		echo $IWPRIV." mcast_rate 65000\n";
    	}
    	else if($mcastrate==17){
    		echo $IWPRIV." mcast_rate 78000\n";
    	}
    	else if($mcastrate==18){
    		echo $IWPRIV." mcast_rate 104000\n";
    	}
    	else if($mcastrate==19){
    		echo $IWPRIV." mcast_rate 117000\n";
    	}
    	else if($mcastrate==20){
    		echo $IWPRIV." mcast_rate 130000\n";
    	}
    	else {
    		echo $IWPRIV." mcast_rate 6000\n";
    	}        
    	}
    	/*add for mcast rate by yuda end */

	/*Please put the settings which will affect all VAPs here, and no need to do it in multi-ssid, vice versa*/
	/*Common setting for all VAPs of ATH DEV start*/
	/* erial move to __wlan_device_a_up.php, for dual band ap scan.
	echo $IWPRIV." apband 1\n"; //show ap band in wireless driver*/
	if($shortgi != "")	{ echo $IWPRIV." shortgi ".$shortgi."\n"; }

	if($assoclimitenable == 1){
	if($a_mode == 1 || $a_mode == 3 || $a_mode == 4){
        	if($cwmmode==0){//default 60M*60s
         		if($wlan_utilization==1)    {echo "iwpriv ".$wlanif_a." wlan_utiliz 0\n";}
			else if ($wlan_utilization==2) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 377487360\n";}
			else if ($wlan_utilization==3) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 283115520\n";}
			else if ($wlan_utilization==4) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 188743680\n";}
			else if ($wlan_utilization==5) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 94371840\n";}
         		else if ($wlan_utilization==6) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 0\n";}
        		else  {echo "iwpriv ".$wlanif_a." wlan_utiliz 0\n";}
        	}
		else{//cwmmode=1 //default 90M*60s
        		if($wlan_utilization==1)    {echo "iwpriv ".$wlanif_a." wlan_utiliz 0\n";}
			else if ($wlan_utilization==2) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 566231040\n";}
			else if ($wlan_utilization==3) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 424673280\n";}
			else if ($wlan_utilization==4) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 283115520\n";}
			else if ($wlan_utilization==5) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 141557760\n";}
        		else if ($wlan_utilization==6) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 0\n";}
         		else  {echo "iwpriv ".$wlanif_a." wlan_utiliz 0\n";}
        	}
        }
        else if($a_mode == 2){   // a mode only in HT20 mode default 20M*60s
		if($wlan_utilization==1)    {echo "iwpriv ".$wlanif_a." wlan_utiliz 0\n";}
		else if ($wlan_utilization==2) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 125829120\n";}
		else if ($wlan_utilization==3) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 94371840\n";}
		else if ($wlan_utilization==4) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 62914560\n";}
		else if ($wlan_utilization==5) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 31457280\n";}
		else if ($wlan_utilization==6) {echo "iwpriv ".$wlanif_a." wlan_utiliz 1; iwpriv ".$wlanif_a." bytes_time 60; iwpriv ".$wlanif_a." bytes_limit 0\n";}
		else  {echo "iwpriv ".$wlanif_a." wlan_utiliz 0\n";}
	}
	else {echo "iwpriv ".$wlanif_a." wlan_utiliz 0\n";}
	}
	echo $IWPRIV." doth 1\n";// dfs for 5G
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
                require($template_root."/zonedefence_a.php");
  }
  else
  {
                echo $IWPRIV." zonedefence 0\n";
  }
	echo $IWPRIV." inact_auth ".$INACT."\n";
	/*Common setting for all VAPs of ATH DEV end*/

	$auth_mode = query("/wlan/inf:2/authentication");
	if ($auth_mode>1){  
	}
	else { 
		require($template_root."/__auth_openshared_a.php");
	}

	require($template_root."/multi_ssid_run_a.php");

	}
}//if end
else
{
	$wlanif_a = query("/runtime/layout/wlanif_a");
	$igmpsnoop = query("/wlan/inf:2/igmpsnoop");
	$multi_total_state = query($WLAN_a."/multi/state");
	if ($multi_total_state == 1)
	{
		if ($wlan_ap_operate_mode_a==1 || $wlan_ap_operate_mode_a==2 || $wlan_ap_operate_mode_a==4) /*    WDS_OVER_MULTI_SSID_080422 */
		{   // 1:apc, 2:apr, 4:WDS without AP 
			echo "echo multi-ssid must be normal AP mode! > /dev/console\n";
			set($WLAN_a."/ap_mode", 0);
		}
	}
	if ($wlan_ap_operate_mode_a==3 || $wlan_ap_operate_mode_a==4)
	{
		require($template_root."/__wlan_wdsmode_a.php");
	//	exit;
	}
	else{
	if (query("/wlan/inf:2/enable")!=1)
	{
		echo "echo WLAN is disabled ! > /dev/console\n";
		exit;
	}

	echo "brctl e_partition_a br0 0\n";

	$wlxmlpatch_a_pid = "/var/run/wlxmlpatch_a.pid";
	echo "if [ -f ".$wlxmlpatch_a_pid." ]; then\n";
	echo "kill \`cat ".$wlxmlpatch_a_pid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$wlxmlpatch_a_pid."\n";
	echo "fi\n\n";
	 	
	/* IGMP Snooping dennis 2008-01-29 start */
	if ($igmpsnoop == 1){
		$ap_igmp_pid = "/var/run/ap_igmp_a.pid";
		echo "echo disable > /proc/net/br_igmp_ap_br0\n";
		echo "echo unsetwl ".$wlanif_a." > /proc/net/br_igmp_ap_br0\n";//joe
		echo "brctl igmp_snooping br0 0\n";
		echo "if [ -f ".$ap_igmp_pid." ]; then\n";
		echo "kill \`cat ".$ap_igmp_pid."\` > /dev/null 2>&1\n";
		echo "rm -f ".$ap_igmp_pid."\n";
		echo "fi\n\n";
	}
	/* IGMP Snooping dennis 2008-01-29 end */

	echo "ifconfig ".$wlanif_a." down"."\n";//joe
	echo "sleep 2\n";
	echo "brctl delif br0 ".$wlanif_a."\n";//joe
 
	echo "sleep 1\n";
	echo "brctl apc_a br0 0"."\n";
	echo "iwconfig ".$wlanif_a." key off\n";
	}
}
?>
