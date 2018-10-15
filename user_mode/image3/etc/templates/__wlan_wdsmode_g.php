<? /* vi: set sw=4 ts=4: */
$WLAN="/wlan/inf:1";  // b, g, n band
$wlxmlpatch_primary_pid = "/var/run/wlxmlpatch_g.pid";
$ap_igmp_pid = "/var/run/ap_igmp_g.pid";
$g_mode = query($WLAN."/wlmode");    

if ($wlan_ap_operate_mode_g == 4)  // WDS without AP // WDSwithoutAP_bug_0321
{    $withoutap = 1; }
else  // ($WLAN == 3)  WDS with AP
{    $withoutap = 0; }

if ($generate_start==1)
{    
	anchor("/wlan/inf:1");
	$channel = query("channel");         if (query("autochannel")==1) { $channel=0; }
	$bintval = query("beaconinterval");
	$cwmmode = query("cwmmode");
	$shortgi = query("shortgi");
	$g_mode = query("wlmode");
	$ssidhidden = query("ssidhidden");
	$dtim       = query("dtim");
	$wmmenable  = query("wmm/enable");
	//$aniena	= query("aniena");
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
	$mcastrate_g  = query("mcastrate");/*add for mcast rate by yuda*/
	$wlan_utilization  = query("wlan_bytes_lim");
	$wlan_time = query("wlan_bytes_time");
	$ratectl = query("ratectl");
	$manrate = query("manrate");
	$manretries = query("manretries");
	$uapsd = query("uapsd");
	$zonedefence = query("zonedefence");
		$coexistence = query("coexistence/enable");
	$multi_pri_state = query("multi/pri_by_ssid");
	$pri_bit = query("pri_bit");
	//$wepmode = query("wpa/wepmode");
	$IWPRIV="iwpriv ".$wlanif_g;
	// Disable IPv6 on wireless interface
        echo "echo \"1\" > /proc/sys/net/ipv6/conf/wifi0/disable_ipv6;\n";

	echo "ifconfig wifi0 hw ether ".$wlanmac."\n";
	echo "wlanconfig ".$wlanif_g." create wlandev wifi0 wlanmode ap\n";

	echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$wlanif_g."/disable_ipv6;\n";

	echo "echo Start WLAN interface ".$wlanif_g." ... > /dev/console\n";
	
	echo "iwpriv wifi0 HALDbg 0x0\n";
	echo $IWPRIV." dbgLVL 0x100\n";
	echo "iwpriv wifi0 disablestats 0\n";

	// common RF setting start
	echo "ifconfig ".$wlanif_g." txqueuelen 1000\n";
	echo "ifconfig wifi0 txqueuelen 1000\n";

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
		//1--->11bgn  3--->only n	
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
				else{//channel 12,13 does not support 40MHz channel width
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

	// if rate control is not auto, set the manual settings
	if($ratectl > 1)
	{
		echo $IWPRIV." set11NRates ".$manrate."\n";// seems default 0x8c8c8c8c will cause can not ping through, joe
		echo $IWPRIV." set11NRetries ".$manretries."\n";
	}
	echo "iwpriv wifi0 txchainmask 3\n"; 
	echo "iwpriv wifi0 rxchainmask 3\n"; 
	echo "iwpriv wifi0 noisespuropt 1\n";
	// common RF setting end

	if ($ssidhidden!="")    { echo $IWPRIV." hide_ssid ".$ssidhidden."\n"; }

	echo "iwpriv wifi0 ANIEna 1 \n"; // default to enable for 2.4G
//	echo "iwpriv wifi0 burst 1\n"; // for intel6300 downlink/bidi throughput issue
	
	if ($uapsd!="")	{echo $IWPRIV." uapsd ".$uapsd."\n"; }
	if ($dtim!="")	{ echo $IWPRIV." dtim_period ".$dtim."\n"; }

	/*add for mcast rate by yuda start*/
    	if($mcastrate_g!=0){
    		if($mcastrate_g==1){
    			echo $IWPRIV." mcast_rate 1000\n";
    		}
    		else if($mcastrate_g==2){
    			echo $IWPRIV." mcast_rate 2000\n";
    		}
    		else if($mcastrate_g==3){
    			echo $IWPRIV." mcast_rate 5500\n";
    		}
    		else if($mcastrate_g==4){
    			echo $IWPRIV." mcast_rate 11000\n";
    		}
    		else if($mcastrate_g==5){
    			echo $IWPRIV." mcast_rate 6000\n";
    		}
    		else if($mcastrate_g==6){
    			echo $IWPRIV." mcast_rate 9000\n";
    		}
    		else if($mcastrate_g==7){
    			echo $IWPRIV." mcast_rate 12000\n";
    		}
    		else if($mcastrate_g==8){
    			echo $IWPRIV." mcast_rate 18000\n";
    		}
    		else if($mcastrate_g==9){
    			echo $IWPRIV." mcast_rate 24000\n";
    		}
    		else if($mcastrate_g==10){
    			echo $IWPRIV." mcast_rate 36000\n";
    		}
    		else if($mcastrate_g==11){
    			echo $IWPRIV." mcast_rate 48000\n";
    		}
    		else if($mcastrate_g==12){
    			echo $IWPRIV." mcast_rate 54000\n";
    		}
    		else if($mcastrate_g==13){
    			echo $IWPRIV." mcast_rate 6500\n";
    		}
    		else if($mcastrate_g==14){
	    		echo $IWPRIV." mcast_rate 13000\n";
	    	}
	    	else if($mcastrate_g==15){
	    		echo $IWPRIV." mcast_rate 19500\n";
    		}
    		else if($mcastrate_g==16){
    			echo $IWPRIV." mcast_rate 26000\n";
    		}
    		else if($mcastrate_g==17){
    			echo $IWPRIV." mcast_rate 39000\n";
    		}
    		else if($mcastrate_g==18){
    			echo $IWPRIV." mcast_rate 52000\n";
    		}
    		else if($mcastrate_g==19){
    			echo $IWPRIV." mcast_rate 58500\n";
    		}
    		else if($mcastrate_g==20){
    			echo $IWPRIV." mcast_rate 65000\n";
    		}
    		else if($mcastrate_g==21){
    			echo $IWPRIV." mcast_rate 78000\n";
    		}
    		else if($mcastrate_g==22){
    			echo $IWPRIV." mcast_rate 104000\n";
    		}
    		else if($mcastrate_g==23){
    			echo $IWPRIV." mcast_rate 117000\n";
    		}
    		else if($mcastrate_g==24){
    			echo $IWPRIV." mcast_rate 130000\n";
    		}
     		else {
    			echo $IWPRIV." mcast_rate 11000\n";
    		}   	   
    	}
	/*add for mcast rate by yuda end*/	

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
            echo $IWPRIV." ap_bridge 1 \n";
            echo "brctl w_partition br0 ".$wlanif_g." 0\n";
        }
	/* w_partition 2008-03-22 end */
	echo "brctl apc br0 0"."\n"; // trun AP client off
	
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
        else {echo "iwconfig ".$wlanif_g." rate auto\n";}
		
	if ($wmmenable>0)       { echo $IWPRIV." wmm 1\n"; }
	else                    { echo $IWPRIV." wmm 0\n"; }

	if($acktimeout != "")	{ echo "iwpriv wifi0 acktimeout ".$acktimeout."\n"; }//default to 48
	else	{ echo "iwpriv wifi0 acktimeout 48\n"; }

	if($multi_pri_state==1)	
	{ 
		echo  $IWPRIV." pristate 1\n";
		echo  $IWPRIV." pribit ".$pri_bit."\n";
	}
	else	{echo  $IWPRIV." pristate 0\n";}

	echo $IWPRIV." wds 1\n";
	echo $IWPRIV." wdsalpha 1\n";
	echo $IWPRIV." wdsprobereq 0\n";
	if($withoutap == 1 ) { echo $IWPRIV." wdswithoutap 1\n"; }
	else    { echo $IWPRIV." wdswithoutap 0\n"; }

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
		if($wlan_utilization==1)    {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
		else if ($wlan_utilization==2) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 125829120\n";}
		else if ($wlan_utilization==3) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 94371840\n";}
		else if ($wlan_utilization==4) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 62914560\n";}
		else if ($wlan_utilization==5) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 31457280\n";}
		else if ($wlan_utilization==6) {echo "iwpriv ".$wlanif_g." wlan_utiliz 1; iwpriv ".$wlanif_g." bytes_time 60; iwpriv ".$wlanif_g." bytes_limit 0\n";}
		else  {echo "iwpriv ".$wlanif_g." wlan_utiliz 0\n";}
}

	echo $IWPRIV." countryie 1\n"; // enable country IE in beacon and probe response
	echo $IWPRIV." doth 0\n";//no dfs for 2.4G
	echo $IWPRIV." extprotspac 0\n";

	if($bintval!="") {echo $IWPRIV." bintval ".$bintval."\n";}
	else {echo $IWPRIV." bintval 100\n"; }

	if($assoclimitenable == 1)	{echo $IWPRIV." assocenable 1\n";}
	else {echo $IWPRIV." assocenable 0\n";}
	if($assoclimitnumber != "") {echo $IWPRIV." assocnumber ".$assoclimitnumber."\n";}

	if ($zonedefence==1){
                echo $IWPRIV." zonedefence 1\n";
                require($template_root."/zonedefence_g.php");
        }
        else{
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
	/*Common setting for all VAPs of ATH DEV end*/

	require($template_root."/__wlan_acl_g.php");

	$auth_mode = query("/wlan/inf:1/authentication");
	if ($auth_mode>1){  
	}
	else { 
		require($template_root."/__auth_openshared_g.php");
	}

	require($template_root."/multi_ssid_run_g.php");

	anchor($WLAN);
	$auth_mode	= query("authentication");
	$defkey	= query("defkey");
	if($defkey==1){
	$keyformat  = query("wepkey:1/keyformat");
	}
	else if($defkey==2){
		$keyformat  = query("wepkey:2/keyformat");
	}
	else if($defkey==3){
		$keyformat  = query("wepkey:3/keyformat");
	}
	else if($defkey==4){
		$keyformat  = query("wepkey:4/keyformat");
	}
	else{
		$keyformat  = query("wepkey:1/keyformat");
	}
	$wepmode	= query("wpa/wepmode");
	$index=7;
	$index_mac=0;
	$up_ath_cnt=0;
	$W_PATH=$WLAN."/";
	$ssid = query($WLAN."/ssid");
	$wpapsk = query($WLAN."/wpa/wpapsk");
	for ($WLAN."/wds/list/index")
	{      
		$index++;     
		$index_mac++;   
		$path="/wirelss/";
		$wds_mac = query($WLAN."/wds/list/index:".$index_mac."/mac");  
		$IWPRIV = "iwpriv ath".$index;
		if ($wds_mac!="")  
		{                  	
			$up_ath_cnt++;
			echo "\necho Add WDS ath".$index."... > /dev/console\n";
			echo "wlanconfig ath".$index." create wlandev wifi0 wlanmode ap\n";
			/*iwconfig*/
			echo "ifconfig ath".$index." txqueuelen 1000\n";
			echo $IWPRIV." wds 1\n";
			echo $IWPRIV." wdsprobereq 1\n";
			echo $IWPRIV." wdsalpha 1\n";
			echo $IWPRIV." wdsaddmac ".$wds_mac."\n";
			if($withoutap == 1 ) { echo $IWPRIV." wdswithoutap 1\n";}
			else                 { echo $IWPRIV." wdswithoutap 0\n"; }	
			if($autochannel==0)
			{
				if($channel>14){
					$channel=6;
				}
			}
			/*iwpriv*/
				//(20) 1:b/g/n mix, 2.b/g mode  3:only n   
				if ($cwmmode == 0){
					if($g_mode == 1) {echo $IWPRIV." mode 11NGHT20\n";}
					else if($g_mode == 3) {echo $IWPRIV." mode 11NGHT20\n";}
					else if($g_mode == 2) {echo $IWPRIV." mode 11G\n";}
					else {echo $IWPRIV." mode 11NGHT20\n";}
				}
				//1--->11bgn  3--->only n	
				else{//cwmmode=1 HT20/40 mode
					if($autochannel==1){//autochannel enable
						echo $IWPRIV." mode 11NGHT40\n";
					}
					else{ //autochannel disable
						if($channel<5){//channel 1~4
							echo $IWPRIV." mode 11NGHT40PLUS\n";				
						}
						else{//channel 5~11
							echo $IWPRIV." mode 11NGHT40MINUS\n";
						}
					}
				}
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
			
			echo "iwconfig ath".$index." essid \"".get("s",$W_PATH."ssid")."\" freq ".$channel."\n";
			echo "iwconfig ath".$index." mode master\n";

			if ($dtim!="")          {echo $IWPRIV." dtim_period ".$dtim."\n"; }   // for each VAP. (WDS - ath0) jack.
			
			if ($wmmenable>0)       { echo $IWPRIV." wmm 1\n"; }
			else                    { echo $IWPRIV." wmm 0\n"; }
			
    			if($coexistence == 1)
			{
				echo $IWPRIV." disablecoext 0\n";
			}
			else
			{
				echo $IWPRIV." disablecoext 1\n";
			}

			if ($auth_mode==1 || $auth_mode==0 )  /*shared key or open*/  
			{                    
				echo $IWPRIV." authmode 1\n";
				if ($wepmode==1)
				{            
				/*
				*	For ASCII string:
				*		iwconfig ath0 key s:"ascii" [1]
				*	For Hex string:
				*		iwconfig ath0 key "1234567890" [1]
				*/
					echo $IWPRIV." ampdu 0\n";
					if ($keyformat==1)	{ $iw_keystring="s:\"".get("s",$W_PATH."wepkey:".$defkey)."\" [".$defkey."]";}
					else				{ $iw_keystring="\"".query($W_PATH."wepkey:".$defkey)."\" [".$defkey."]"; }
					echo "iwconfig ath".$index." key ".$iw_keystring."\n";
					if ($auth_mode==1)	{ echo $IWPRIV." authmode 2; "."iwconfig ath".$index." key ".$iw_keystring."\n"; }
				}
			}else if ($auth_mode==3 || /*wpa-psk*/ 
				$auth_mode==5 || /*wpa2-psk*/  
				$auth_mode==7 || /*wpa-auto-psk*/     
				$auth_mode==2 || /*wpa-eap*/ 
				$auth_mode==4 || /*wpa2-eap*/  
				$auth_mode==6) /*wpa-auto-eap*/           
			{            	
			$hostapd_conf	= "/var/run/hostapd".$index.".conf_wds";
//			$hostapd_pid	= "/var/run/hostapd".$index.".pid_wds";
			fwrite($hostapd_conf,
				"interface=ath".$index."\n".
				"bridge=br0\n".
				"logger_syslog=-1\n".
				"logger_syslog_level=2\n".
				"logger_stdout=-1\n".
				"logger_stdout_level=2\n".
				"ctrl_interface=/var/run/hostapd\n".
				"ctrl_interface_group=0\n".
				"ssid=".$ssid."\n".
				"dtim_period=2\n".
				"max_num_sta=255\n".
				"macaddr_acl=0\n".
				"auth_algs=1\n".
				"ignore_broadcast_ssid=0\n".
				"wme_enabled=0\n".
				"eapol_version=2\n".
				"eapol_key_index_workaround=0\n".
				"wpa=2\n".
					"wds_enable=1\n".
					"wds_mac=".$wds_mac."\n".
					"wpa_group_rekey=0\n".
					"wpa_key_mgmt=WPA-PSK\n".
					"wpa_pairwise=CCMP\n");

                                $wpapskformat   = query("/wlan/inf:1/wpa/passphraseformat");
                                if ($wpapskformat == 1) {fwrite2($hostapd_conf, "wpa_passphrase=".$wpapsk."\n");}
                                else                    {fwrite2($hostapd_conf, "wpa_psk=".$wpapsk."\n");}
	
			$wpa_supplicant_conf = "/var/run/wpa_supplicant".$index.".conf_wds";
//			$wpa_supplicant_pid = "/var/run/wpa_supplicant".$index.".pid_wds";
				fwrite($wpa_supplicant_conf,
					"ap_scan=2\n".
					"wds_enable=1\n".
					"wds_mac=".$wds_mac."\n".
					"network={\n".
					"ssid=\"".$ssid."\"\n".
					"scan_ssid=1\n".
					"key_mgmt=WPA-PSK\n".
					"proto=WPA2\n".
					"pairwise=CCMP\ngroup=CCMP\n");
                                
				if ($wpapskformat == 1) {fwrite2($wpa_supplicant_conf, "psk=\"".$wpapsk."\"\n"."}\n");}
                                else                    {fwrite2($wpa_supplicant_conf, "psk=".$wpapsk."\n"."}\n");}
	
			} // end of wpa-psk and eap           
			
		}     // end of if ($wds_mac!="") 
	}// end of for
	if($up_ath_cnt > 0)
	{
		echo "brctl stp br0 1 \n";    
	}
	
	//INCLUDE_WDSMODE_INFO_start
	if($index_mac !=0)
	{
		set("/runtime".$WLAN."/wds/devicenum", $index_mac);
		set("/runtime".$WLAN."/wds/wdsinfostatus", 1);  
	} 

} // end of ($generate_start==1)
else
{    
	$igmpsnoop = query("/wlan/inf:1/igmpsnoop");
	echo "echo Stop WLAN WDS interface ".$wlanif_g." ... > /dev/console\n";
	
	$index=7; 
	$index_mac=0;	
	echo "brctl stp br0 0 \n";

	for ($WLAN."/wds/list/index")
	{      
		$index++; 
		$index_mac++;
		$wds_mac = query($WLAN."/wds/list/index:".$index_mac."/mac");  
		if ($wds_mac!="")  
		{    
			echo "\necho kill WDS ath".$index."... > /dev/console\n"; 
			
			/* Stop wlxmlpatch_pid */	            
			/* Jack add 13/02/08 +++ wlxmlpatch_v2*/
			$index_tmp = $index-7;
			$wlxmlpatch_pid	= "/var/run/wlxmlpatch".$index.".pid";
			echo "if [ -f ".$wlxmlpatch_pid." ]; then\n";
			echo "kill \`cat ".$wlxmlpatch_pid."\` > /dev/null 2>&1\n";
			echo "rm -f ".$wlxmlpatch_pid."\n";
			echo "fi\n\n";
			/* Jack add 13/02/08 --- wlxmlpatch_v2*/
			/* IGMP Snooping dennis 2008-01-29 start */
			if($withoutap == 0 ){//wds with ap mode
				if($igmpsnoop == 1 ){
					echo "echo disable > /proc/net/br_igmp_ap_br0\n";
					echo "brctl igmp_snooping br0 0\n";
					echo "echo unsetwl ath".$index." > /proc/net/br_igmp_ap_br0\n";
				}
			}
			/* IGMP Snooping dennis 2008-01-29 end */
			echo "ifconfig ath".$index." down"."\n";  
			echo "sleep 2\n";  
			/* destroy and remove wireless */
			echo "brctl delif br0 ath".$index."\n";
			/*  echo "iwpriv ath".$index." wdsalpha 0"."\n";*/
			
			/* Stop hostapd */
	          	$hostapd_conf	= "/var/run/hostapd".$index.".conf_wds";
			echo "rm -f ".$hostapd_conf."\n";
			
			/* Stop wpa_supplicant */
	          	$wpa_supplicant_conf = "/var/run/wpa_supplicant".$index.".conf_wds";
			echo "rm -f ".$wpa_supplicant_conf."\n";
			
			/* IGMP Snooping dennis 2008-01-29 start */
			if($withoutap == 0 ){//wds with ap mode
				if($igmpsnoop == 1 ){
					echo "if [ -f /var/run/ap_igmp_".$index.".pid ]; then\n";
					echo "kill \`cat /var/run/ap_igmp_".$index.".pid\` > /dev/null 2>&1\n";
					echo "rm -f /var/run/ap_igmp_".$index.".pid\n";
					echo "fi\n\n";
				}
			}
			
			echo "iwconfig ath".$index." key off"."\n"; // must add key off
			echo "sleep 1\n";  
			//echo "wlanconfig ath".$index." destroy"."\n";  
		}  // end of ($wds_mac!="")
	}  // end of for

	            /* Stop wlxmlpatch_primary_pid */
/* Jack modify 13/02/08 +++ wlxmlpatch_v2*/
	echo "if [ -f ".$wlxmlpatch_primary_pid." ]; then\n";
	echo "kill \`cat ".$wlxmlpatch_primary_pid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$wlxmlpatch_primary_pid."\n";
	echo "fi\n\n";
/* Jack modify 13/02/08 --- wlxmlpatch_v2*/

	set("/runtime".$WLAN."/wds/wdsinfostatus", 0); //INCLUDE_WDSMODE_INFO
	

	if (query("/wlan/inf:1/enable")!=1)
	{
		echo "echo WLAN is disabled ! > /dev/console\n";
		exit;
	}
	
	// jack, \\1.54.45.230\project\andy\2590-4448\boards\wapnd02\apps\wireless\__wlan_wdsmode.php  
	// jack, modify sh, then load r004, and then r005 by WEB, than test , crash or not.......
	echo "brctl e_partition br0 0\n";
	/* IGMP Snooping dennis 2008-01-29 start */
	if($withoutap == 0 ){//wds with ap mode
		echo "echo disable > /proc/net/br_igmp_ap_br0\n";
		echo "echo unsetwl ath0 > /proc/net/br_igmp_ap_br0\n";
		echo "brctl igmp_snooping br0 0\n";
	}
	/* IGMP Snooping dennis 2008-01-29 end */

	echo "ifconfig ".$wlanif_g." down\n";
	echo "sleep 2\n";
	echo "brctl delif br0 ".$wlanif_g."\n";
	echo "sleep 1\n";
	/* Stop hostapd */
	$HAPD_conf	= "/var/run/hostapd.".$wlanif_g.".conf";
	echo "rm -f ".$HAPD_conf."\n";
	echo "kill -9 `ps | grep ".$HAPD_conf." | grep -v grep | cut -b 1-5`\n";
	/*$HAPD_pid  = "/var/run/hostapd.".$wlanif.".pid";
	echo "if [ -f ".$HAPD_pid." ]; then\n";
	echo "kill -9 \`cat ".$HAPD_pid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$HAPD_pid."\n";
	echo "rm -f ".$HAPD_conf."\n";
	echo "fi\n\n";*/
	/* IGMP Snooping dennis 2008-01-29 start */
	if($withoutap == 0 ){//wds with ap mode
		echo "if [ -f ".$ap_igmp_pid." ]; then\n";
		echo "kill \`cat ".$ap_igmp_pid."\` > /dev/null 2>&1\n";
		echo "rm -f ".$ap_igmp_pid."\n";
		echo "fi\n\n";
	}
	/* IGMP Snooping dennis 2008-01-29 end */
	echo "iwconfig ".$wlanif_g." key off\n";
	
}  // end of else ($generate_start!=1)

?>
