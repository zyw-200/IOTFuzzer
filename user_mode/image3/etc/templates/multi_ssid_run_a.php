#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
$WLAN = "/wlan/inf:2"; 
$multi_ssid_path = $WLAN."/multi";
$igmpsnoop = query($WLAN."/igmpsnoop");
$multi_total_state = query($multi_ssid_path."/state");  
if ($generate_start==1)
{
	if ($multi_total_state == 1)
	{       
		$multicast_limit = query($WLAN."/multicast_bwctrl");/* dennis 2006-06-25 multicast bwctrl */
		$multi_pri_state = query($multi_ssid_path."/pri_by_ssid");  
		
		$multi_ssid_amount=0;    /* Jack add 17/04/07 MULTI_SSID_CLIENT_INFO */
		
		$index=16; 
		$index2=0;	
		for ($multi_ssid_path."/index")
		{      
			$index++;
			$index2++;
			$IWPRIV="iwpriv ath".$index;
			$multi_ind_state = query($multi_ssid_path."/index:".$index2."/state");  
	        	/*add schedule for multi-ssid by yuda start*/
	        	$schedule_enable=query("/schedule/enable");

//ELBOX_PROGS_PRIV_ACL_AGINGOUT_BY_RSSI_DATARATE
      			$agbyrssiordrstatus=query("agbyrssiordrstatus");  //0 dsiable, 1 rssi,2 datarate
		        $aclbyrssi=query("aclbyrssi");
		        $aclallbywlmode=query("aclallbywlmode");
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
	        	if ($schedule_enable==1)
	        	{
	        		if(query($multi_ssid_path."/index:".$index2."/schedule_rule_state")==1)
	        		{
	        			$multi_ind_schedule_state = query($multi_ssid_path."/index:".$index2."/schedule_state");
	        		}
	        		else
	        		{
	        			$multi_ind_schedule_state = 1;
	        		}     	
	        	}
	        	else
	        	{
	        		$multi_ind_schedule_state = 1;
	        	}
	        
	        	/*add schedule for multi-ssid by yuda end*/
			set("/runtime".$WLAN."/multi_ssid/index:".$index2."/state", 0);  /* Jack add 17/04/07 MULTI_SSID_CLIENT_INFO */
		 	if ($multi_ind_state==1)  //add $multi_ind_schedule_state for schedule for multi-ssid by yuda   
			{ 
				$multi_ssid = query($multi_ssid_path."/index:".$index2."/ssid");//query($multi_ssid_path."/index:".$index."/ssid");
				$multi_auth = query($multi_ssid_path."/index:".$index2."/auth");
				//$multi_cipher = query($multi_ssid_path."/index:".$index2."/cipher");
				$multi_ssid_hidden = query($multi_ssid_path."/index:".$index2."/ssid_hidden");
				$multi_wmm = query($multi_ssid_path."/index:".$index2."/wmm/enable");  
				$multi_pri_bit = query($multi_ssid_path."/index:".$index2."/pri_bit");  
				$multi_wpartition = query($multi_ssid_path."/index:".$index2."/w_partition");  
				$fixedrate  = query($WLAN."/fixedrate");
	                        $mcastrate_a  = query($WLAN."/mcastrate");/*add for mcast rate by yuda*/
				
				echo "\necho Add multi-ssid ath".$index."... > /dev/console\n";
				echo "wlanconfig ath".$index." create wlandev wifi1 wlanmode ap\n";
				// Disable IPv6 on wireless interface
                                echo "echo \"1\" > /proc/sys/net/ipv6/conf/ath".$index."/disable_ipv6;\n";
	
				echo "ifconfig ath".$index." txqueuelen 1000\n";
								
				if($autochannel==0){
					if($channel<36){
						$channel=36;
					}
				}
				

				// 1:na, 2:a, 3:n
				//(20) 8-->NA mix 9--> only n  7--> only a
				if ($cwmmode == 0){
					if($a_mode == 1) {echo $IWPRIV." mode 11NAHT20\n";}
					else if($a_mode == 3) {echo $IWPRIV." mode 11NAHT20\n";}
					else if($a_mode == 2) {echo $IWPRIV." mode 11A\n";}
					else {echo $IWPRIV." mode 11NAHT20\n";}
				}
				//1-->NA mix 3--> only n	
				else if ($cwmmode == 1){//cwmmode=1 HT20/40 mode
					if($autochannel==1){//autochannel enable
						echo $IWPRIV." mode 11NAHT40\n";
					}
					else{ //autochannel disable
						if($channel==36||$channel==44||$channel==52||$channel==60||$channel==100||$channel==108||$channel==116||$channel==124||$channel==132||$channel==149||$channel==157){
							echo $IWPRIV." mode 11NAHT40PLUS\n";
						}
						else if($channel==40||$channel==48||$channel==56||$channel==64||$channel==104||$channel==112||$channel==120||$channel==128||$channel==136||$channel==153||$channel==161){
							echo $IWPRIV." mode 11NAHT40MINUS\n";
						}
						else{//channel 140,165 does not support 40MHz channel width
							echo $IWPRIV." mode 11NAHT20\n";
						}
					}
				}
				else if ($cwmmode == 2){ // 20/40/80MHz auto
         			       if($channel==140 || $channel==165)      {echo $IWPRIV." mode 11ACVHT20\n";}
			                else if($channel==132 || $channel==136) {echo $IWPRIV." mode 11ACVHT40\n";}
			                else {echo $IWPRIV." mode 11ACVHT80\n";}
			        }
				
				if($fixedrate==7)       {echo "iwconfig ath".$index." rate 54M\n";}
			        else if($fixedrate==6)  {echo "iwconfig ath".$index." rate 48M\n";}
			        else if($fixedrate==5)  {echo "iwconfig ath".$index." rate 36M\n";}
			        else if($fixedrate==4)  {echo "iwconfig ath".$index." rate 24M\n";}
				else if($fixedrate==3)  {echo "iwconfig ath".$index." rate 18M\n";}
			        else if($fixedrate==2)  {echo "iwconfig ath".$index." rate 12M\n";}
			        else if($fixedrate==1)  {echo "iwconfig ath".$index." rate 9M\n";}
			        else if($fixedrate==0)  {echo "iwconfig ath".$index." rate 6M\n";}
			        else {echo "iwconfig ath".$index." rate auto\n";}	
				
				if($a_mode == 1){
					echo $IWPRIV." pureg 0\n";
					echo $IWPRIV." puren 0\n";
				}
				else if($a_mode == 3){
					echo $IWPRIV." puren 1\n";
				}		
				else{
					echo $IWPRIV." pureg 0\n";
					echo $IWPRIV." puren 0\n";
				}
			
                                echo "iwconfig ath".$index." essid \"".get("s",$multi_ssid_path."/index:".$index2."/ssid")."\" freq ".$channel."\n";
				echo "iwconfig ath".$index." mode master\n";

				// if rate control is not auto, set the manual settings
				if($ratectl > 1)
				{
					echo $IWPRIV." set11NRates ".$manrate."\n";// seems default 0x8c8c8c8c will cause can not ping through, joe
					echo $IWPRIV." set11NRetries ".$manretries."\n";
				}
				
				if ($multi_ssid_hidden!="")    {echo $IWPRIV." hide_ssid ".$multi_ssid_hidden."\n"; }   // Mssid1 try, jack.
				echo $IWPRIV." uapsd 1\n"; }
				if ($dtim!="")          {echo $IWPRIV." dtim_period ".$dtim."\n"; }   // for each VAP. (WDS - ath0) jack.
				if ($multi_wmm==1)                   { echo $IWPRIV." wmm 1\n";          }         
				else                                 { echo $IWPRIV." wmm 0\n";          }

			/*add for mcast rate by yuda start*/
			if($mcastrate_a!=0){
				if($mcastrate_a==1){
					echo $IWPRIV." mcast_rate 6000\n";
				}
    				else if($mcastrate_a==2){
    					echo $IWPRIV." mcast_rate 9000\n";
    				}
    				else if($mcastrate_a==3){
    					echo $IWPRIV." mcast_rate 12000\n";
    				}
    				else if($mcastrate_a==4){
    					echo $IWPRIV." mcast_rate 18000\n";
    				}
    				else if($mcastrate_a==5){
    					echo $IWPRIV." mcast_rate 24000\n";
    				}
    				else if($mcastrate_a==6){
    					echo $IWPRIV." mcast_rate 36000\n";
    				}
    				else if($mcastrate_a==7){
    					echo $IWPRIV." mcast_rate 48000\n";
    				}
    				else if($mcastrate_a==8){
    					echo $IWPRIV." mcast_rate 54000\n";
    				}
    				else if($mcastrate_a==9){
    					echo $IWPRIV." mcast_rate 6500\n";
    				}
    				else if($mcastrate_a==10){
    					echo $IWPRIV." mcast_rate 13000\n";
    				}
    				else if($mcastrate_a==11){
    					echo $IWPRIV." mcast_rate 19500\n";
    				}
    				else if($mcastrate_a==12){
    					echo $IWPRIV." mcast_rate 26000\n";
    				}
    				else if($mcastrate_a==13){
    					echo $IWPRIV." mcast_rate 39000\n";
    				}
    				else if($mcastrate_a==14){
    					echo $IWPRIV." mcast_rate 52000\n";
    				}
    				else if($mcastrate_a==15){
    					echo $IWPRIV." mcast_rate 58500\n";
    				}
    				else if($mcastrate_a==16){
    					echo $IWPRIV." mcast_rate 65000\n";
    				}
    				else if($mcastrate_a==17){
    					echo $IWPRIV." mcast_rate 78000\n";
    				}
    				else if($mcastrate_a==18){
    					echo $IWPRIV." mcast_rate 104000\n";
    				}
    				else if($mcastrate_a==19){
    					echo $IWPRIV." mcast_rate 117000\n";
    				}
    				else if($mcastrate_a==20){
    					echo $IWPRIV." mcast_rate 130000\n";
    				}
    				else {
    					echo $IWPRIV." mcast_rate 6000\n";
    				}
    			}
			/*add for mcast rate by yuda end*/
				
				/* w_partition 2008-03-22 start */
                                /* Jack add 11/11/08 +++ */
                                if ($multi_wpartition==2)   /* guest mode,  */
                                {
                                        echo $IWPRIV." ap_bridge 0 \n";
                                        echo "brctl w_partition br0 ath".$index."  1 \n";
                                }   // for Mssid1, WDS-ath0 jack.
                                else if ($multi_wpartition==1)
                                {
                                        echo $IWPRIV." ap_bridge 0 \n";
                                        echo "brctl w_partition br0 ath".$index." 0 \n";
                                }
                                else
                                {
                                        echo $IWPRIV." ap_bridge 1\n";
                                        echo "brctl w_partition br0 ath".$index." 0 \n";
                                }	

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
				
				
			
				/* releated to userlimit by ssid
			       if($aclbywlmode == 1)	{echo $IWPRIV." aclperwlmode 1\n";}
				else {echo $IWPRIV." aclperwlmode 0\n";}
			        */
//ELBOX_PROGS_PRIV_ACL_AGINGOUT_BY_RSSI_DATARATE end
				/* Jack add 20/04/07 MULTI_SSID_FILTER +++ */
				/* aclmode 0:disable, 1:allow all of the list, 2:deny all of the list */
				echo $IWPRIV." maccmd 3\n";   // flush the ACL database.
				
				$aclmode=query($WLAN."/multi/index:".$index2."/acl/mode");
				if ($aclmode=="")
				{
					$aclmode=query($WLAN."/acl/mode");
					//echo "echo aclmode:  ".$aclmode."  ... > /dev/console\n";
					if      ($aclmode == 1)     { echo $IWPRIV."  maccmd 1\n"; }
					else if ($aclmode == 2)     { echo $IWPRIV."  maccmd 2\n"; }
					else                        { echo $IWPRIV."  maccmd 0\n"; }
					if ($aclmode == 1 || $aclmode == 2)
					{
						for($WLAN."/acl/mac")
						{
							$mac=query($WLAN."/acl/mac:".$@);
							echo $IWPRIV."  addmac ".$mac."\n";//wtpmacenable 2007-09-13 dennis
						}
					}
				}
				else
				{
					//echo "echo aclmode:  ".$aclmode."  ... > /dev/console\n";
					if      ($aclmode == 1)     { echo $IWPRIV."  maccmd 1\n"; }
					else if ($aclmode == 2)     { echo $IWPRIV."  maccmd 2\n"; }
					else                        { echo $IWPRIV."  maccmd 0\n"; }
					if ($aclmode == 1 || $aclmode == 2)
					{
						for($WLAN."/multi/index:".$index2."/acl/mac")
						{
							$mac=query($WLAN."/multi/index:".$index2."/acl/mac:".$@);
							echo $IWPRIV."  addmac ".$mac."\n";//wtpmacenable 2007-09-13 dennis
						}
					}
				}
				/* Jack add 20/04/07 MULTI_SSID_FILTER --- */
				
				$multi_ssid_amount++;  /* Jack add 17/04/07 MULTI_SSID_CLIENT_INFO */
				set("/runtime".$WLAN."/multi_ssid/index:".$index2."/state", 1);  /* Jack add 17/04/07 MULTI_SSID_CLIENT_INFO */
			
				if($multi_pri_state==1)	
				{ 
					echo $IWPRIV." pristate 1\n";
					echo $IWPRIV." pribit ".$multi_pri_bit."\n";
				}
				else	{echo $IWPRIV." pristate 0\n";}
			}     // end of ($multi_ind_state==1) 
		}// end of for
		/* Jack add 17/04/07 MULTI_SSID_CLIENT_INFO +++ */
		if($multi_ssid_amount !=0)
		{
			set("/runtime".$WLAN."/multi_ssid/multi_ssid_infostatus", 1);  
			set("/runtime".$WLAN."/multi_ssid/devicenum", $multi_ssid_amount);  
		} 
	} // end of ($multi_total_state == 1)
} // end of ($generate_start==1)
else
{
	if (query($WLAN."/enable")!=1)
	{
		echo "echo WLAN is disabled ! > /dev/console\n";
		exit;
	}
	if ($multi_total_state == 1)
	{       
		$index=16;
		$index2=0;
		for ($WLAN."/multi/index")
		{      
			$index++;
			$index2++;
			$multi_ind_state = query($multi_ssid_path."/index:".$index2."/state");  
	        /*add schedule for multi-ssid by yuda start*/
	        $schedule_enable=query("/schedule/enable");
	        if ($schedule_enable==1)
	        {
	        	if(query($multi_ssid_path."/index:".$index2."/schedule_rule_state")==1)
	        	{
	        		$multi_ind_schedule_state = query($multi_ssid_path."/index:".$index2."/schedule_state");
	        	}
	        	else
	        	{
	        		$multi_ind_schedule_state = 1;
	        	}     	
	        }
	        else
	        {
	        	$multi_ind_schedule_state = 1;
	        }
	        /*add schedule for multi-ssid by yuda end*/

	        if ($multi_ind_state==1)  //add $multi_ind_schedule_state for schedule for multi-ssid by yuda 
			{          
				echo "\necho kill multi-ssid ath".$index."... > /dev/console\n"; 
				$hostapd_pid = "/var/run/hostapd0".$index.".pid";
				$hostapd_conf = "/var/run/hostapd0".$index.".conf";
				echo "rm -f ".$hostapd_conf."\n";		        
				echo "if [ -f ".$hostapd_pid." ]; then\n";
				echo "kill -9 \`cat ".$hostapd_pid."\` > /dev/null 2>&1\n";
				echo "rm -f ".$hostapd_pid."\n";
				echo "fi\n\n";
				/* Jack add 13/02/08 +++ wlxmlpatch_v2*/
				$wlxmlpatch_pid	= "/var/run/wlxmlpatch".$index.".pid";
				echo "if [ -f ".$wlxmlpatch_pid." ]; then\n";
				echo "kill -9 \`cat ".$wlxmlpatch_pid."\` > /dev/null 2>&1\n";
				echo "rm -f ".$wlxmlpatch_pid."\n";
				echo "fi\n\n";
				/* Jack add 13/02/08 --- wlxmlpatch_v2*/	            
				
				if ($igmpsnoop == 1){      
					echo "echo disable > /proc/net/br_igmp_ap_br0\n";
					echo "brctl igmp_snooping br0 0\n";
					echo "echo unsetwl ath".$index." > /proc/net/br_igmp_ap_br0\n";
					echo "if [ -f /var/run/ap_igmp_".$index.".pid ]; then\n";
					echo "kill \`cat /var/run/ap_igmp_".$index.".pid\` > /dev/null 2>&1\n";
					echo "rm -f /var/run/ap_igmp_".$index.".pid\n";
					echo "fi\n\n";
				}
				
				echo "ifconfig ath".$index." down"."\n";  
				echo "sleep 2\n";  
				echo "brctl delif br0 ath".$index."\n";				
				echo "iwconfig ath".$index." key off"."\n"; 
			}  // end of ($multi_ind_state==1)
		}  // end of for
	} // end of ($multi_total_state == 1)
}  // end of else ($generate_start!=1)
?>
