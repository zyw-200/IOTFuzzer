#!/bin/sh
echo [$0] ... > /dev/console
# neapc_run.php >>>
<?
/* vi: set sw=4 ts=4: */
$timeout="3";  /* seconds*/
$portnumber="55000";  /*sandy 2009_10_28*/
$neapcpid = "/var/run/neapc.pid";
$aparraymsgpid = "/var/run/aparraymsg.pid";
if ($generate_start==1 )
{
	$role   =  query("/wlan/inf:1/arrayrole_original");
	$state   = query("/wlan/inf:1/aparray_enable");
	$ap_mode   = query("/wlan/inf:1/ap_mode");
	$ap_mode_a   = query("/wlan/inf:2/ap_mode");
	if ($role =="")
	{
		echo "rgdb -s /wlan/inf:1/arrayrole_original 3\n";
		echo "rgdb -s /wlan/inf:1/arrayrole 3\n";
	}
	if ($state =="")
	{
		echo "rgdb -s /wlan/inf:1/aparray_enable 0\n";
	}
	if ($ap_mode != 0 && $ap_mode != 3)  // if ap mode is not pure AP mode, disable AP ARRAY
	{
		echo "echo not pure AP mode, so disable AP ARRAY ... > /dev/console\n";
		echo "rgdb -s /wlan/inf:1/aparray_enable 0\n";
	}
	if($ap_mode_a != "")
	{ 
		if($ap_mode_a != 0)
		{
			echo "echo not pure AP mode, so disable AP ARRAY ... > /dev/console\n";
			echo "rgdb -s /wlan/inf:1/aparray_enable 0\n";
		}
	}
	/* check rgdb node NULL or not, if NULL, assign default value 1.  +++ */	
	$sync_ssid   =  query("/aparray/sync/ssid");
	$sync_ssidhidden   =  query("/aparray/sync/ssidhidden");
	$sync_autochannel   =  query("/aparray/sync/autochannel");
	$sync_channelwidth   =  query("/aparray/sync/channelwidth");
	$sync_security   =  query("/aparray/sync/security");
	$sync_fixedrate   =  query("/aparray/sync/fixedrate");
	$sync_beaconinterval   =  query("/aparray/sync/beaconinterval");
	$sync_dtim   =  query("/aparray/sync/dtim");
	$sync_txpower   =  query("/aparray/sync/txpower");
	$sync_wmm   =  query("/aparray/sync/wmm");
	$sync_acktimeout   =  query("/aparray/sync/acktimeout");
	$sync_shortgi   =  query("/aparray/sync/shortgi");
	$sync_igmpsnoop   =  query("/aparray/sync/igmpsnoop");
	$sync_connectionlimit   =  query("/aparray/sync/connectionlimit");
	$sync_linkintegrity   =  query("/aparray/sync/linkintegrity");
	$sync_multi_ssid   =  query("/aparray/sync/multi/ssid");
	$sync_multi_ssid_hidden   =  query("/aparray/sync/multi/ssid_hidden");
	$sync_multi_security   =  query("/aparray/sync/multi/security");
	$sync_multi_wmm   =  query("/aparray/sync/multi/wmm");
	$sync_qos   =  query("/aparray/sync/qos");
	$sync_vlan   =  query("/aparray/sync/vlan");
	$sync_schedule   =  query("/aparray/sync/schedule");
	$sync_time   =  query("/aparray/sync/time");
	$sync_log   =  query("/aparray/sync/log");
	$sync_adminlimit   =  query("/aparray/sync/adminlimit");
	$sync_system   =  query("/aparray/sync/system");
	$sync_consoleprotocol   =  query("/aparray/sync/consoleprotocol");
	$sync_snmp   =  query("/aparray/sync/snmp");
	$sync_pingctl   =  query("/aparray/sync/pingctl");
	$sync_dhcp   =  query("/aparray/sync/dhcp");
	$sync_login   =  query("/aparray/sync/login");
	$sync_acl   =  query("/aparray/sync/acl");	
        $sync_band   =  query("/aparray/sync/band");
	//add new node
	$sync_wlan_status=query("/aparray/sync/wlanstate");//wireless status on/off
	$sync_wlmode_status=query("/aparray/sync/wlmode");//wlan mode b/g/n/ac
	$sync_captival_profile_status=query("/aparray/sync/captivalstate");//captivalstate
	$sync_captival_multi_profile_status=query("/aparray/sync/multi/captival");//multi captival
	$sync_captival_portal=query("/aparray/sync/captivalportal");//captival portal
	$sync_arrayauth=query("/aparray/sync/arrayauth");
	$sync_autorf=query("/aparray/sync/autorf");
	$sync_loadbalance=query("/aparray/sync/loadbalance");
	$sync_arp_spoofing=query("/aparray/sync/arpspoofing");//arp spoofing
	$sync_fair_air_time=query("/aparray/sync/fairairtime");//fair air time
	//end	
	
	
	if ($sync_ssid   =="")            {	echo "rgdb -s /aparray/sync/ssid 1\n";	}                   
	if ($sync_ssidhidden   =="")      { echo "rgdb -s /aparray/sync/ssidhidden 1\n";	}         
	if ($sync_autochannel  =="")      { echo "rgdb -s /aparray/sync/autochannel 1\n";	}          
	if ($sync_channelwidth  =="")     { echo "rgdb -s /aparray/sync/channelwidth  1\n";	}         
	if ($sync_security   =="")        { echo "rgdb -s /aparray/sync/security 1\n";	}                  
	if ($sync_fixedrate   =="")       { echo "rgdb -s /aparray/sync/fixedrate 1\n";	}               
	if ($sync_beaconinterval   =="")  { echo "rgdb -s /aparray/sync/beaconinterval 1\n";	}    
	if ($sync_dtim   =="")            { echo "rgdb -s /aparray/sync/dtim 1\n";	}                         
	if ($sync_txpower   =="")         { echo "rgdb -s /aparray/sync/txpower 1\n";	}                    
	if ($sync_wmm   =="")             { echo "rgdb -s /aparray/sync/wmm 1\n";	}                           
	if ($sync_acktimeout   =="")      { echo "rgdb -s /aparray/sync/acktimeout 1\n";	}             
	if ($sync_shortgi   =="")         { echo "rgdb -s /aparray/sync/shortgi 1\n";	}                   
	if ($sync_igmpsnoop  =="")        { echo "rgdb -s /aparray/sync/igmpsnoop 1\n";	}                
	if ($sync_connectionlimit  =="")  { echo "rgdb -s /aparray/sync/connectionlimit 1\n";	}  
	if ($sync_linkintegrity   =="")   { echo "rgdb -s /aparray/sync/linkintegrity 1\n";	}      
	if ($sync_multi_ssid   =="")      { 
		//echo "echo sync_multi_ssid is ".$sync_multi_ssid.".  \n";
		echo "rgdb -s /aparray/sync/multi/ssid 1\n";	
		//echo "echo sync_multi_ssid   ==NULL ... > /dev/console\n";
		}              
	if ($sync_multi_ssid_hidden  ==""){ echo "rgdb -s /aparray/sync/multi/ssid_hidden 1\n";	}
	if ($sync_multi_security  =="")   { echo "rgdb -s /aparray/sync/multi/security 1\n";	}    
	if ($sync_multi_wmm  =="")        { echo "rgdb -s /aparray/sync/multi/wmm 1\n";	}               
	if ($sync_qos   =="")             { echo "rgdb -s /aparray/sync/qos 1\n";	}                           
	if ($sync_vlan   =="")            { echo "rgdb -s /aparray/sync/vlan 1\n";	}                       
	if ($sync_schedule   =="")        { echo "rgdb -s /aparray/sync/schedule 1\n";	}                
	if ($sync_time  =="")             { echo "rgdb -s /aparray/sync/time 1\n";	}     
	//add new node                   
	if ($sync_captival_portal  =="")             { echo "rgdb -s /aparray/sync/captivalportal 1\n";	}  
	if ($sync_arrayauth == "")			{echo "rgdb -s /aparray/sync/arrayauth 1\n";}
	if ($sync_autorf == "")				{echo "rgdb -s /aparray/sync/autorf 1\n";}
	if ($sync_loadbalance == "")		{echo "rgdb -s /aparray/sync/loadbalance 1\n";}
	if ($sync_arp_spoofing  =="")             { echo "rgdb -s /aparray/sync/arpspoofing 1\n";	}  
	if ($sync_fair_air_time  =="")             { echo "rgdb -s /aparray/sync/fairairtime 1\n";	}  
	//end                      
	if ($sync_log  =="")              { echo "rgdb -s /aparray/sync/log 1\n";	}                            
	if ($sync_adminlimit  =="")       { echo "rgdb -s /aparray/sync/adminlimit 1\n";	}              
	if ($sync_system   =="")          { echo "rgdb -s /aparray/sync/system 1\n";	}                      
	if ($sync_consoleprotocol   =="") { echo "rgdb -s /aparray/sync/consoleprotocol 1\n";	} 
	if ($sync_snmp   =="")            { echo "rgdb -s /aparray/sync/snmp 1\n";	}                          
	if ($sync_pingctl   =="")         { echo "rgdb -s /aparray/sync/pingctl 1\n";	}                   
	if ($sync_dhcp   =="")            { echo "rgdb -s /aparray/sync/dhcp 1\n";	}                          
	if ($sync_login   =="")           { echo "rgdb -s /aparray/sync/login 1\n";	}                        
	if ($sync_acl  =="")              { echo "rgdb -s /aparray/sync/acl 1\n";	}  
	if ($sync_band  =="")             { echo "rgdb -s /aparray/sync/band 1\n";	}  
	//add new node
	if ($sync_wlan_status  =="")       { echo "rgdb -s /aparray/sync/wlanstate 1\n";      }
	if ($sync_wlmode_status  =="")     { echo "rgdb -s /aparray/sync/wlmode 1\n";      }
	if ($sync_captival_profile_status  =="")             { echo "rgdb -s /aparray/sync/captivalstate 1\n";}
	if ($sync_captival_multi_profile_status  =="")             { echo "rgdb -s /aparray/sync/multi/captival 1\n";}
	//end
	/* check rgdb node NULL or not? if NULL, assign default value 1.  111 */	                          
	
	echo "echo Start NeapC Client ... > /dev/console\n";
	echo "aparraymsg &\n";   // Must always turn on, to get message from NeapS
	echo "echo $! > ".$aparraymsgpid."\n";
        //echo "echo ori_role is ".$role.", state is ".$state.". over \n";
	if($role != "")
	{
		echo "rgdb -s /wlan/inf:1/arrayrole ".$role."\n";
	}
	
	echo "rgdb -i -d /runtime/wlan/inf:1/ap_array_members/list \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/slaver_record \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/arrayslaver/list \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/arraymaster/list \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/scan_table \n";
	
	if (query("/wlan/inf:1/aparray_enable")==1)
	{
	   echo "rgdb -i -s /runtime/aparray_scan_status 1\n"; /*sandy 2009_8_25*/	
		$LANIF=query("/runtime/layout/lanif");
		echo "neapc -i ".$LANIF." -t ".$timeout. " -p ".$portnumber."  &> /dev/console\n";/*sandy 2009_10_28*/
	        echo "echo $! > ".$neapcpid."\n";
        }
}
else
{
	echo "echo Stop NeapC Client ... > /dev/console\n";
	echo "if [ -f ".$neapcpid." ]; then\n";
	echo "kill -9 \`cat ".$neapcpid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$neapcpid."\n";
	echo "fi\n\n";
        echo "if [ -f ".$aparraymsgpid." ]; then\n";
	echo "kill -9 \`cat ".$aparraymsgpid."\` > /dev/null 2>&1\n";
	echo "rm -f ".$aparraymsgpid."\n";
	echo "fi\n\n";
  echo "rgdb -i -s /runtime/aparray_scan_status 0\n"; /*sandy 2009_8_25*/  

	echo "rgdb -i -d /runtime/wlan/inf:1/ap_array_members/list \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/slaver_record \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/aparray/assoc \n";	
	echo "rgdb -i -d /runtime/wlan/inf:1/arrayslaver/list \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/arraymaster/list \n";
	echo "rgdb -i -d /runtime/wlan/inf:1/scan_table \n";
	
}
?>
# neaps_run.php <<<
