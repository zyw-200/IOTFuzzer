<?
$AUTH_GROUP=fread("/var/proc/web/session:".$sid."/user/group");
if ($AUTH_GROUP!="0") {require("/www/permission_deny.php");exit;}
$switch = query("/runtime/web/display/switchable");
if($ACTION_POST == "st_logs")
{
//	if(valid_ip($log_ip) != 0){exit;}
	if(valid_string($smtp_ip) != 0){exit;}
	if(valid_num($log_schedule) != 0){exit;}
	if(valid_string($smtp_name) != 0){exit;}
	if(valid_email($smtp_from_email) != 0){exit;}
	if(valid_email($smtp_to_email) != 0){exit;}
}
else if($ACTION_POST == "tool_admin")
{
	if(valid_ip($ip_from) != 0){exit;}
	if(valid_ip($ip_to) != 0){exit;}
}

if($ACTION_POST == "__sample")
{
	echo "<!--\n";
	echo "sample = ". $sample ."\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;

	if(query("/sys/devicename") != $sample) {$dirty++; set("/sys/devicename", $sample);}

	if($dirty > 0)
	{
		$SUBMIT_STR = "submit ";
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$ACTION_POST);
	if($SUBMIT_STR != "") {require($G_SAVING_URL);}
	else                  {require($G_NO_CHANGED_URL);}
}

else if($ACTION_POST == "tool_admin")
{
	echo "<!--\n";
	echo "user_name	= ". $user_name  ."\n";
	echo "new_password	= ". $new_password  ."\n";
	echo "f_console_pro	= ". $f_console_pro  ."\n";
	echo "timeout	= ". $timeout  ."\n";
	echo "ping_status	= ". $ping_status  ."\n";
	echo "f_add_value	= ". $f_add_value  ."\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";		
	$dirty_login = 0;
	$dirty_console = 0;
	$dirty_ping = 0;
	$dirty_snmp = 0;
	$dirty_limit = 0;
	$dirty_sysname = 0;
	$dirty_wlan = 0;
	$dirty_swctrl = 0;
	$dirty_led = 0;

	if($f_list_del!="")
	{
		$dirty_limit++;
//		del("/sys/adminlimit/ipentry/index:".$f_list_del);
		set("/sys/adminlimit/ipentry/index:".$f_list_del."/ippoolstart", "");
		set("/sys/adminlimit/ipentry/index:".$f_list_del."/ippoolend", "");
		if($f_disable_ip == 1)
		{
			if(query("/sys/adminlimit/status")==3){set("/sys/adminlimit/status",1);}
			if(query("/sys/adminlimit/status")==2){set("/sys/adminlimit/status",0);}
		}
	}		
	else if($f_add_value == "1")
	{
		$dirty_limit++;
		if(query("/sys/adminlimit/status") != $f_limit_status) {$dirty_limit++; set("/sys/adminlimit/status", $f_limit_status);}
		if($f_limit_status == 1 || $f_limit_status == 3)
		{
			if(query("/sys/vlan_mode")!=0){set("/sys/vlan_mode", 0);$dirty_wlan++;}/*nap*/
			if(query("/sys/vlan_state")!=0){set("/sys/vlan_state",0);$dirty_wlan++;}/*vlan state*/ 
			if(query("/wlan/inf:1/webredirect/enable")!=0){set("/wlan/inf:1/webredirect/enable", 0);$dirty_url++;}/*url_state*/
			if(query("/wlan/inf:1/webredirect/auth/enable")!=0){set("/wlan/inf:1/webredirect/auth/enable", 0);$dirty_url++;}
			if(query("/wlan/inf:1/webredirect/url/enable")!=0){set("/wlan/inf:1/webredirect/url/enable", 0);$dirty_url++;}
		}
		$i = 1;
		while($i < 5)
		{
			if(query("/sys/adminlimit/ipentry/index:".$i."/ippoolstart") =="")
			{
				set("/sys/adminlimit/ipentry/index:".$i."/ippoolstart", $ip_from);
				set("/sys/adminlimit/ipentry/index:".$i."/ippoolend", $ip_to);
				$i=4;
			}
			$i++;	
		}
	}
	else
	{
		if(query("/sys/wtp/enable") !=1)	
		{	
			if(query("/sys/adminlimit/status") != $f_limit_status) {$dirty_limit++; set("/sys/adminlimit/status", $f_limit_status);}
	
			if($f_limit_status == 1 || $f_limit_status == 3)
			{
				if(query("/sys/adminlimit/vlanid") != $admin_vid) {$dirty_limit++; set("/sys/adminlimit/vlanid", $admin_vid);}
				if(query("/sys/vlan_mode")!=0){set("/sys/vlan_mode", 0);$dirty_wlan++;}/*nap*/
				if(query("/sys/vlan_state")!=0){set("/sys/vlan_state",0);$dirty_wlan++;}/*vlan state*/ 
				if(query("/wlan/inf:1/webredirect/enable")!=0){set("/wlan/inf:1/webredirect/enable", 0);$dirty_url++;}/*url_state*/
				if(query("/wlan/inf:1/webredirect/auth/enable")!=0){set("/wlan/inf:1/webredirect/auth/enable", 0);$dirty_url++;}
				if(query("/wlan/inf:1/webredirect/url/enable")!=0){set("/wlan/inf:1/webredirect/url/enable", 0);$dirty_url++;}
			}
		}
		
		if(query("/sys/systemName")!= $sysname){set("/sys/systemName", $sysname);$dirty_sysname++;}
		if(query("/sys/systemLocation")!= $location){ set("/sys/systemLocation", $location);$dirty_sysname++;}
		
		if(query("/sys/wtp/enable") !=1)	
		{	
			if(query("/sys/user:1/name") != $user_name) {$dirty_login++; set("/sys/user:1/name", $user_name);}
			//add by yuejun for apply new password
			if($con_change == 1)
			{
				if(queryEnc("/sys/user:1/password") != $new_password) {$dirty_login++; setEnc("/sys/user:1/password", $new_password);}
			}
			if(query("/sys/consoleprotocol/protocol") != $f_console_pro) {$dirty_console++; set("/sys/consoleprotocol/protocol", $f_console_pro);}
			if(query("/sys/consoleprotocol/timeout") != $timeout) {$dirty_console++; set("/sys/consoleprotocol/timeout", $timeout);}

			if(query("/sys/snmpd/status")	!=$f_snmp_status)	{$dirty_snmp++; 	set("/sys/snmpd/status", $f_snmp_status);}

			if($snmp_status == "1")
			{
				if(query("/sys/snmpd/rocomm")	!=$snmp_public)	{$dirty_snmp++; set("/sys/snmpd/rocomm", $snmp_public);}
				if(query("/sys/snmpd/rwcomm")	!=$snmp_private)	{$dirty_snmp++; set("/sys/snmpd/rwcomm", $snmp_private);}	

				if(query("/runtime/web/display/snmp/trap") != "0")
				{
					if(query("/sys/snmptrap/status")	!=$f_trap_status)	{$dirty_snmp++; set("/sys/snmptrap/status", $f_trap_status);}
					if($trap_status == "1")
					{
						if(query("/sys/snmpd/hostip:1")	!=$trap_server_ip)	{$dirty_snmp++; set("/sys/snmpd/hostip:1", $trap_server_ip);}
						set("/sys/snmpd/secumodel:1", 1);
						set("/sys/snmpd/commorun:1", $snmp_public);
					}
				}
//				if(query("/sys/snmpd/v3status")   !=$snmp_v3status)   {$dirty_snmp++;     set("/sys/snmpd/v3status", $snmp_v3status);}
				if($snmp_v3status == 1)
				{
					if(query("/sys/snmpd/viewname:1") != $view_name)
					{
						set("/sys/snmpd/viewname:1", $view_name);
						set("/sys/snmpd/readview:1", $view_name);
						set("/sys/snmpd/writeview:1", $view_name);
						set("/sys/snmpd/notifyview:1", $view_name);
						$dirty_snmp++;
					}
					if(query("/sys/snmpd/oid:1") != $view_oid){set("/sys/snmpd/oid:1", $view_oid);$dirty_snmp++;}
					if(query("/sys/snmpd/viewtype:1") != $view_type){set("/sys/snmpd/viewtype:1", $view_type);$dirty_snmp++;}
					if(query("/sys/snmpd/groupname:1") != $group_name){set("/sys/snmpd/groupname:1", $group_name);set("/sys/snmpd/mapgroup:1", $group_name);$dirty_snmp++;}
					if(query("/sys/snmpd/seculevel:1") != $security_level){set("/sys/snmpd/seculevel:1", $security_level);$dirty_snmp++;}
//					if(query("/sys/snmpd/readview:1") != $read_view){set("/sys/snmpd/readview:1", $read_view);$dirty_snmp++;}
//					if(query("/sys/snmpd/writeview:1") != $write_view){set("/sys/snmpd/writeview:1", $write_view);$dirty_snmp++;}
//					if(query("/sys/snmpd/notifyview:1") != $notify_view){set("/sys/snmpd/notifyview:1", $notify_view);$dirty_snmp++;}
					if(query("/sys/snmpd/secuname:1") != $sec_username){set("/sys/snmpd/secuname:1", $sec_username);$dirty_snmp++;}
//					if(query("/sys/snmpd/mapgroup:1") != $map_group){set("/sys/snmpd/mapgroup:1", $map_group);$dirty_snmp++;}
					if(query("/sys/snmpd/authprotocol:1") != $f_auth_pro){set("/sys/snmpd/authprotocol:1", $f_auth_pro);$dirty_snmp++;}
					if($f_auth_pro == ""){set("/sys/snmpd/authprotocol:1", "none");}
					if(query("/sys/snmpd/authkey:1") != $auth_key){set("/sys/snmpd/authkey:1", $auth_key);$dirty_snmp++;}
					if($auth_key == ""){set("/sys/snmpd/authkey:1", "none");}
					if(query("/sys/snmpd/privprotocol:1") != $f_priv_pro){set("/sys/snmpd/privprotocol:1", $f_priv_pro);$dirty_snmp++;}
					if($f_priv_pro == ""){set("/sys/snmpd/privprotocol:1", "none");}
					if(query("/sys/snmpd/privkey:1") != $priv_key){set("/sys/snmpd/privkey:1", $priv_key);$dirty_snmp++;}
					if($priv_key == ""){set("/sys/snmpd/privkey:1", "none");}
				}
			}
			if(query("/runtime/web/display/ping_control") != "0")
			{
				if(query("/sys/pingctl") != $f_ping_status) {$dirty_ping++; set("/sys/pingctl", $f_ping_status);}
			}
		}
		
		if(query("/sys/swcontroller/enable") != $sw_enable){set("/sys/swcontroller/enable",$sw_enable);$dirty_swctrl++;}
		if($sw_enable == 1)
		{
			set("/wlan/inf:1/aparray_enable", 0);
			set("/sys/snmpd/status", 1);
			$dirty_swctrl++;
		}
		if(query("/sys/led/power") != $f_led_st){set("/sys/led/power", $f_led_st); $dirty_led++;}
	}
		
	$dirty_str = 0;
	$dirty_str = $dirty_limit + $dirty_console + $dirty_login + $dirty_snmp + $dirty_ping;
	
	if($dirty_sysname > 0 && $dirty_str == 0)
	{
		//$SUBMIT_STR="COMMIT";
		set("/runtime/web/submit/commit",1);
	}
	
	$dirty_str = $dirty_str - $dirty_limit;
	
	if($dirty_limit > 0 && $dirty_str == 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit Limit";
		set("/runtime/web/submit/limit", 1);
	}
	else if($dirty_limit > 0 && $dirty_str != 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit Limit;";
		set("/runtime/web/submit/limit", 1);
	}
	if($dirty_wlan > 0)
	{
		set("/runtime/web/submit/wlan", 1);
	}
	$dirty_str = $dirty_str - $dirty_login;

	if($dirty_login > 0 && $dirty_str == 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit HTTPD_PASSWD;submit CONSOLE";
		set("/runtime/web/submit/httpdpasswd", 1);
		set("/runtime/web/submit/neaps", 1);
		set("/runtime/web/submit/console", 1);
	}
	else if($dirty_login > 0 && $dirty_str != 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit HTTPD_PASSWD;submit CONSOLE;";
		set("/runtime/web/submit/httpdpasswd", 1);
		set("/runtime/web/submit/neaps", 1);
		set("/runtime/web/submit/console", 1);
	}
	
	$dirty_str = $dirty_str - $dirty_console;
	
	if($dirty_console > 0 && $dirty_str == 0 && $dirty_login == 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit CONSOLE";
		set("/runtime/web/submit/console", 1);
	}
	else if($dirty_console > 0 && $dirty_str != 0 && $dirty_login ==0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit CONSOLE;";
		set("/runtime/web/submit/console", 1);
	}	

	$dirty_str = $dirty_str - $dirty_snmp;
	
	if($dirty_snmp > 0 && $dirty_str == 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit SNMP";
		set("/runtime/web/submit/snmp", 1);
	}
	else if($dirty_snmp > 0 && $dirty_str != 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit SNMP;";
		set("/runtime/web/submit/snmp", 1);
	}	

	if($dirty_ping > 0)
	{
		//$SUBMIT_STR=$SUBMIT_STR."submit PINGCTL";
		set("/runtime/web/submit/pingctl", 1);
	}
	
	if($dirty_swctrl > 0)
	{
		set("/runtime/web/submit/snmp", 1);
		set("/runtime/web/submit/ap_array",1);
		set("/runtime/web/submit/captival",1);
		set("/runtime/web/submit/autorf",1);
		set("/runtime/web/submit/loadbalance",1);
		set("/runtime/web/submit/apneaps_v2",1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	if($dirty_led > 0)
	{
		set("/runtime/web/submit/led",1);
	}
		
	set("/runtime/web/sub_str",$SUBMIT_STR);
	set("/runtime/web/next_page",$ACTION_POST);
	/*if($dirty > 0) {*/require($G_SAVING_URL);
	/*}
	else                  {require($G_NO_CHANGED_URL);}	*/
}
else if($ACTION_POST == "tool_sntp")
{
	echo "<!--\n";

	echo "-->\n";	
	
	$SUBMIT_STR = "";		
	$dirty = 0;	
	$sync_dirty = 0;
	
	if (query("/time/syncwith")!=$sync)             {$dirty++; $sync_dirty++; set("/time/syncwith", $sync); }
    if ($sync == "2")
	{
		if (query("/time/timezone")!=$tzone)			{$sync_dirty++; $dirty++; set("/time/timezone", $tzone); }
		if (query("/time/daylightSaving")!=$daylight)	{$sync_dirty++; $dirty++; set("/time/daylightSaving", $daylight); }
		if (query("/time/ntpserver/ip")!=$ntp)				{$sync_dirty++; $dirty++; set("/time/ntpserver/ip", $ntp); }
		//if (query("/time/ntpserver/interval")!=$interval)	{$dirty++; set("/time/ntpserver/interval", $interval); }
	}
	else
	{
		$XGISET_STR="setPath=/runtime/time/";
		$XGISET_STR=$XGISET_STR."&date=".$mon."/".$f_days."/".$year;
		$XGISET_STR=$XGISET_STR."&time=".$hour.":".$min.":".$sec;
		$XGISET_STR=$XGISET_STR."&endSetPath=1";
		$dirty++;
		set("/runtime/time/sntp", 1);
	}		
	
	if($dirty > 0)
	{	
		//$SUBMIT_STR="submit TIME";
		if(query("/schedule/enable")==1)
		{
			set("/runtime/web/submit/wlan", 1);
		}	
		if($sync_dirty > 0)
		{
		set("/runtime/web/submit/time", 1);
		}
		set("/runtime/web/submit/commit", 1);
	}		
	set("/runtime/web/sub_xgi_str",$XGISET_STR);
	set("/runtime/web/sub_str",$SUBMIT_STR);
	set("/runtime/web/next_page",$ACTION_POST);
	/*if($dirty > 0) {*/require($G_SAVING_URL);
	/*}
	else                  {require($G_NO_CHANGED_URL);}		*/
}
else if($ACTION_POST == "st_ap")
{
	echo "<!--\n";
	echo "-->\n";
	$SUBMIT_STR = "";
	set("/wlan/ch_mode",$band);	
	$cfg_ap_band = $band;	
	if($f_detect == "1")
	{
		if($switch == 1)
		{
			$SUBMIT_STR="submit AP_SITESURVEY_G;submit COMP_G";
			set("/runtime/wlan/inf:1/st_ap",1);
		}
		else
		{
			if($cfg_ap_band == 0)
			{
				$SUBMIT_STR="submit AP_SITESURVEY_G;submit COMP_G";
				set("/runtime/wlan/inf:1/st_ap",1);
			}
			else if($cfg_ap_band == 1)
			{
				$SUBMIT_STR="submit AP_SITESURVEY_A;submit COMP_A";
				set("/runtime/wlan/inf:2/st_ap",1);
			}
		}
	}
	set("/runtime/web/sub_xgi_str",$XGISET_STR);
	set("/runtime/web/sub_str",$SUBMIT_STR);
	set("/runtime/web/next_page",$ACTION_POST);
	if($SUBMIT_STR!="" && $f_detect=="1")   {require($G_SCAN_URL);}
	else /*if($SUBMIT_STR!="" && $f_detect!="1")    */{require($G_SAVING_URL);}
}
else if($ACTION_POST == "st_ap_test")
{
	echo "<!--\n";
	echo "-->\n";
	$SUBMIT_STR = "";
	set("/wlan/ch_mode",$band);	
	$cfg_ap_band = $band;	
	if($f_detect == "1")
        {
                //set("/wlan/inf:1/instruction",1);//add for dennis to difference at apc scan or instruction scan
                if($cfg_ap_band == 0)
                {
                        set("/wlan/inf:1/instruction",1);//add for dennis to difference at apc scan or instruction scan
                        $SUBMIT_STR="submit AP_SITESURVEY_G;submit COMP_G";
                	echo "<!-- g -->\n";
		}
                else if($cfg_ap_band == 1)
                {
                        set("/wlan/inf:2/instruction",1);//add for dennis to difference at apc scan or instruction scan
                        $SUBMIT_STR="submit AP_SITESURVEY_A;submit COMP_A";
                	  echo "<!-- a -->\n";
		}
		  echo "<!-- 0 -->\n";
        }
	 set("/runtime/web/sub_xgi_str",$XGISET_STR);
        set("/runtime/web/sub_str",$SUBMIT_STR);
        set("/runtime/web/next_page",$ACTION_POST);
	 if($SUBMIT_STR!="" && $f_detect=="1")   {require($G_SCAN_URL);}
        else /*if($SUBMIT_STR!="" && $f_detect!="1")    */{require($G_SAVING_URL);}
}
else if($ACTION_POST == "st_logs")
{
        echo "<!--\n";

        echo "-->\n";

        $SUBMIT_STR = "";
        $dirty = 0;
        $flag  = 0;/*for yuda*/
        if(query("/sys/log/logserver")  !=$log_ip)      {set("/sys/log/logserver", $log_ip);    $dirty++;}
		if(query("/sys/log/eulogserver")  !=$eu_ip)      {set("/sys/log/eulogserver", $eu_ip);    $dirty++;}

        if($log_ip != "" || $eu_ip != "")
        {
                if(query("/security/log/remoteinfo") != 1 )             {set("/security/log/remoteinfo", 1);            $dirty++;}

        }
        else
        {
                if(query("/security/log/remoteinfo") != 0 )             {set("/security/log/remoteinfo", 0);            $dirty++;}
        }

        anchor("/security/log");

        if(query("apsystemInfo")        !=$system_activity)             {set("apsystemInfo", $system_activity);         $dirty++;}
        if(query("WirelessInfo")        !=$wireless_activity)   {set("WirelessInfo", $wireless_activity);       $dirty++;}
        if(query("apnoticeInfo")        !=$notice)      {set("apnoticeInfo", $notice);          $dirty++;}

        if(query("/runtime/web/display/log/email") != "0")
        {
                if(query("smtpInfo")                    !=$smtp_enable)         {set("smtpInfo", $smtp_enable); $dirty++;}
                if($smtp_enable=="1")
                {
                if(query("/runtime/web/display/autorekey")==1)
            {
                        if(query("/sys/log/smtpindex")  !=$email_type)  {set("/sys/log/smtpindex", $email_type);        $dirty++;$flag++;}
                        if($email_type==1)
                        {
                                anchor("/sys/log/smtp/index:1");
                        }
                        else if($email_type==2)
                        {
                                anchor("/sys/log/smtp/index:2");
                        }
                        else if($email_type==3)
                        {
                                anchor("/sys/log/smtp/index:3");
                        }
                        if(query("authtype")    !=$auth_enable) {set("authtype", $auth_enable); $dirty++;$flag++;}
                        if(query("ssl") !=$ssl_enable)  {set("ssl", $ssl_enable);       $dirty++;$flag++;}
            }
            else
                {
                               anchor("/sys/log");
                        }
                        if(query("mailserver")  !=$smtp_ip)     {set("mailserver", $smtp_ip);   $dirty++;$flag++;}
                        if(query("fromemail")   !=$smtp_from_email)     {set("fromemail", $smtp_from_email);    $dirty++;$flag++;}
                        if(query("toemail")     !=$smtp_to_email)       {set("toemail", $smtp_to_email);        $dirty++;$flag++;}
                        if(query("mailport")    !=$smtp_port)   {set("mailport", $smtp_port);   $dirty++;$flag++;}
                        if(query("username")    !=$smtp_name)   {set("username", $smtp_name);   $dirty++;$flag++;}
                        if(queryEnc("pass1")       !=$smtp_password)       {setEnc("pass1", $smtp_password);  $dirty++;$flag++;}
                }
                if(query("/sys/log/timemail")   !=$log_schedule)        {set("/sys/log/timemail", $log_schedule);               $dirty++;}
        }
        if($dirty > 0)
        {
        if(query("/runtime/web/display/autorekey")==1)
        {
                if($flag > 0)
                {
                        set("/rumtime/wlan/inf:1/autorekey/first",0);
                        set("/runtime/web/submit/wlan", 1);
                }
        }
                //$SUBMIT_STR= "COMMIT";
                //$SUBMIT_STR="submit SYSLOG";
                set("/runtime/web/submit/syslog", 1);
        }
        set("/runtime/web/sub_str",$SUBMIT_STR);
        set("/runtime/web/next_page",$ACTION_POST);
        /*if($dirty > 0) {*/require($G_SAVING_URL);/*}
        else                  {require($G_NO_CHANGED_URL);}             */
}	
else if($ACTION_POST == "sys_setting")
{
	$SUBMIT_STR = "";

	//echo "ACTION_POST	= ". $ACTION_POST  ."\n";
	$system=query("/runtime/system/setting/status");
	$wlan=query("/runtime/web/submit/wlan");
	$rgblocking=query("/runtime/web/submit/rgblocking");
	$rgvsvr=query("/runtime/web/submit/rgvsvr");
	$rgmacfilter=query("/runtime/web/submit/rgmacfilter");
	$rgdmz=query("/runtime/web/submit/rgdmz");
	$rgfirewall=query("/runtime/web/submit/rgfirewall");
	$rgmisc=query("/runtime/web/submit/rgmisc");
	$qos_tm=query("/runtime/web/submit/qos_tm");
	$qos=query("/runtime/web/submit/qos");
	$trafficmgr=query("/runtime/web/submit/trafficmgr");	
	$trafficmgrdy=query("/runtime/web/submit/trafficmgrdy");
	$trafficmgrconflict=query("/runtime/web/display/trafficdy");
	$wtp=query("/runtime/web/submit/wtp");
	$stunnel=query("/runtime/web/submit/stunnel");
	$lan=query("/runtime/web/submit/lan");
	$wan=query("/runtime/web/submit/wan");
	$console=query("/runtime/web/submit/console");
	$wanapc=query("/runtime/web/submit/wanapc");
	$limit=query("/runtime/web/submit/limit");
	$httpdpasswd=query("/runtime/web/submit/httpdpasswd");
	$snmp=query("/runtime/web/submit/snmp");
	$pingctl=query("/runtime/web/submit/pingctl");
	$time=query("/runtime/web/submit/time");
	$syslog=query("/runtime/web/submit/syslog");
	$dhcpd=query("/runtime/web/submit/dhcpd");
	$delaylan=query("/runtime/web/submit/delaylan");
	$lanchange=query("/runtime/web/submit/lanchange");
	$vlanportlist=query("/runtime/web/submit/vlanportlist");
	$vlan=query("/runtime/web/submit/vlan");
	$dhcpdrestart=query("/runtime/web/submit/dhcpd_restart");
	$dhcpcompare=query("/runtime/web/submit/dhcp_compare");
	$commit=query("/runtime/web/submit/commit");
	$aclupload=query("/runtime/web/acl/upload_flag");
	$ap_array=query("/runtime/web/submit/ap_array");
	$webredirect=query("/runtime/web/submit/webredirect");	
	$arp_spoofing=query("/runtime/web/submit/arpspoofing");
	$ipv6=query("/runtime/web/submit/ipv6");
	$dns=query("/runtime/web/submit/dns");	
	$captival=query("/runtime/web/submit/captival");
	$captival_tar=query("/runtime/web/submit/captival_tar");
	$autorf=query("/runtime/web/submit/autorf");
	$apneaps_v2=query("/runtime/web/submit/apneaps_v2");
	$neaps = query("/runtime/web/submit/neaps");
	$wlan_acl=query("/runtime/web/submit/wlan_acl");
	$loadbalance=query("/runtime/web/submit/loadbalance");
	$led=query("/runtime/web/submit/led");
	$m2u=query("/runtime/web/submit/m2u");
	if($system==1)
	{
		$SUBMIT_STR="submit SYSTEM";
		set("/runtime/web/count",180);
	}
	else
	{
		if($wlan==1)
		{
			$SUBMIT_STR="submit WLAN";
			if(query("/runtime/web/count")!= 180)
			{
				set("/runtime/web/count",60);
			}
		}
		if($rgblocking==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit RG_BLOCKING";
			}
			else
			{
				$SUBMIT_STR="submit RG_BLOCKING";
			}
		}
		if($rgvsvr==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit RG_VSVR";
			}
			else
			{
				$SUBMIT_STR="submit RG_VSVR";
			}
		}
		if($rgmacfilter==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit RG_MAC_FILTER";
			}
			else
			{
				$SUBMIT_STR="submit RG_MAC_FILTER";
			}
		}
		if($rgdmz==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit RG_DMZ";
			}
			else
			{
				$SUBMIT_STR="submit RG_DMZ";
			}
		}
		if($rgfirewall==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit RG_FIREWALL";
			}
			else
			{
				$SUBMIT_STR="submit RG_FIREWALL";
			}
		}
		if($rgmisc==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit RG_MISC";
			}
			else
			{
				$SUBMIT_STR="submit RG_MISC";
			}
		}
		if($trafficmgr==1||$qos==1||$qos_tm==1||$arp_spoofing==1 || $trafficmgr == 1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit NETFILTER";
			}
			else
			{
				$SUBMIT_STR="submit NETFILTER";
			}
				
		}
/*		if($trafficmgrconflict == 1)
		{
				if($trafficmgrdy==1||$qos==1||$qos_tm==1)
				{
						if($SUBMIT_STR !="")
						{
							$SUBMIT_STR=$SUBMIT_STR.";submit QOS_TC_TM";
						}
						else
						{
							$SUBMIT_STR="submit QOS_TC_TM";
						}
				}
		}
		else
		{
				if($qos_tm==1)
				{
					if($SUBMIT_STR !="")
					{
						$SUBMIT_STR=$SUBMIT_STR.";submit QOS_TM";
					}
					else
					{
						$SUBMIT_STR="submit QOS_TM";
					}
				}
				if($qos==1)
				{
					if($SUBMIT_STR !="")
					{
						$SUBMIT_STR=$SUBMIT_STR.";submit QOS";
					}
					else
					{
						$SUBMIT_STR="submit QOS";
					}
				}
				if($trafficmgrdy==1)
				{
					if($SUBMIT_STR !="")
					{
						$SUBMIT_STR=$SUBMIT_STR.";submit TRAFFICMGR";
					}
					else
					{
						$SUBMIT_STR="submit TRAFFICMGR";
					}
				}
		}*/
		if($trafficmgrdy==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit QOS_TC_TM";
			}
			else
			{
				$SUBMIT_STR="submit QOS_TC_TM";
			}
			$SUBMIT_STR=$SUBMIT_STR.";submit NETFILTER";
		}
		if($trafficmgr==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit TRAFFICMGR";
			}
			else
			{
				$SUBMIT_STR="submit TRAFFICMGR";
			}
			$SUBMIT_STR=$SUBMIT_STR.";submit NETFILTER";
		}
		if($wtp==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit WTP";
			}
			else
			{
				$SUBMIT_STR="submit WTP";
			}
		}
		if($stunnel==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit STUNNEL";
			}
			else
			{
				$SUBMIT_STR="submit STUNNEL";
			}
		}
		if($lan==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit LAN";
			}
			else
			{
				$SUBMIT_STR="submit LAN";
			}
		}
		if($ipv6==1)
                {
                        if($SUBMIT_STR !="")
                        {
                                $SUBMIT_STR=$SUBMIT_STR.";submit IPV6";
                        }
                        else
                        {
                                $SUBMIT_STR="submit IPV6";
                        }
                }
		if($wan==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit WAN";
			}
			else
			{
				$SUBMIT_STR="submit WAN";
			}
		}
		if($console==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit CONSOLE";
			}
			else
			{
				$SUBMIT_STR="submit CONSOLE";
			}
		}
		if($wanapc==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit WAN_APC";
			}
			else
			{
				$SUBMIT_STR="submit WAN_APC";
			}
		}
		if($limit==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit Limit";
			}
			else
			{
				$SUBMIT_STR="submit Limit";
			}
		}
		if($httpdpasswd==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit HTTPD_PASSWD";
			}
			else
			{
				$SUBMIT_STR="submit HTTPD_PASSWD";
			}
		}
		if($snmp==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit SNMP";
			}
			else
			{
				$SUBMIT_STR="submit SNMP";
			}
		}
		if($pingctl==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit PINGCTL";
			}
			else
			{
				$SUBMIT_STR="submit PINGCTL";
			}
		}
		if($time==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit TIME";
			}
			else
			{
				$SUBMIT_STR="submit TIME";
			}
		}
		if($syslog==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit SYSLOG";
			}
			else
			{
		$SUBMIT_STR="submit SYSLOG";
	}		
		}
		if($dhcpd==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit DHCPD";
			}
			else
			{
				$SUBMIT_STR="submit DHCPD";
			}
		}
		if($delaylan==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit DELAY_LAN";
			}
			else
			{
				$SUBMIT_STR="submit DELAY_LAN";
			}
		}
		if($lanchange==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit LAN_CHANGE";
			}
			else
			{
				$SUBMIT_STR="submit LAN_CHANGE";
			}
		}
		if($vlanportlist==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit VLAN_PORT_LIST";
			}
			else
			{
				$SUBMIT_STR="submit VLAN_PORT_LIST";
			}
		}
		if($vlan==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit VLAN";
			}
			else
			{
				$SUBMIT_STR="submit VLAN";
			}
		}
		if($ap_array==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit AP_ARRAY";
			}
			else
			{
				$SUBMIT_STR="submit AP_ARRAY";
			}
		}
/*victor modify for dennis 2009-2-20 start*/		
		if($dhcpdrestart==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit DHCPD_RESTART";
			}
			else
			{
				$SUBMIT_STR="submit DHCPD_RESTART";
			}
		}
		if($dhcpcompare==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit DHCP_COMPARE";
			}
			else
			{
				$SUBMIT_STR="submit DHCP_COMPARE";
			}
		}
/*victor modify for dennis 2009-2-20 end*/			
		if($webredirect==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit WEBREDIRECT";
			}
			else
			{
				$SUBMIT_STR="submit WEBREDIRECT";
			}
		}		
		if($commit==1 || $aclupload==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit COMMIT";
			}
			else
			{
				$SUBMIT_STR="submit COMMIT";
			}
		}			
		if($arp_spoofing==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit ARP_SPOOFING";
			}
			else
			{
				$SUBMIT_STR="submit ARP_SPOOFING";
			}
		}	
		if($captival==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit CAPTIVAL_PORTAL";
			}
			else
			{
				$SUBMIT_STR="submit CAPTIVAL_PORTAL";
			}
		}
		if($captival_tar==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit CAPTIVAL_PORTAL_TAR save";
			}
			else
			{
				$SUBMIT_STR="submit CAPTIVAL_PORTAL_TAR save";
			}
		}
		if($autorf==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit AUTORF";
			}
			else
			{
				$SUBMIT_STR="submit AUTORF";
			}
		}
		if($apneaps_v2==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit APNEAPS_V2";
			}
			else
			{
				$SUBMIT_STR="submit APNEAPS_V2";
			}
		}
		if($led==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit POWERLED";
			}
			else
			{
				$SUBMIT_STR="submit POWERLED";
			}
		}
		if($m2u==1)
		{
			if($SUBMIT_STR !="")
			{
				$SUBMIT_STR=$SUBMIT_STR.";submit MULTI2UNI";
			}
			else
			{
				$SUBMIT_STR="submit MULTI2UNI";
			}
		}
		if ($neaps == 1)
		{
		if($SUBMIT_STR !="")
                        {
                                $SUBMIT_STR=$SUBMIT_STR.";submit NEAPS";
                        }
                        else
                        {
                                $SUBMIT_STR="submit NEAPS";
                        }	
		}
		if($wlan_acl == 1)
		{
			if($SUBMIT_STR !="")
            {
                $SUBMIT_STR=$SUBMIT_STR.";submit WLAN_ACL";
            }
            else
            {
                $SUBMIT_STR="submit WLAN_ACL";
            }

		}
		 if($loadbalance==1)
                {
                        if($SUBMIT_STR !="")
                        {
                                $SUBMIT_STR=$SUBMIT_STR.";submit LOADBALANCE";
                        }
                        else
                        {
                                $SUBMIT_STR="submit LOADBALANCE";
                        }
                }
			
	}
	if(query("/runtime/web/count")==0 || query("/runtime/web/count")=="")
	{
			set("/runtime/web/count",20);
	}
	//set("/runtime/system/setting/status",0);
	set("/runtime/web/submit/wlan",0);
	set("/runtime/web/submit/rgblocking",0);
	set("/runtime/web/submit/rgvsvr",0);
	set("/runtime/web/submit/rgmacfilter",0);
	set("/runtime/web/submit/rgdmz",0);
	set("/runtime/web/submit/rgfirewall",0);
	set("/runtime/web/submit/rgmisc",0);
	set("/runtime/web/submit/qos_tm",0);
	set("/runtime/web/submit/qos",0);
	set("/runtime/web/submit/trafficmgr",0);
	set("/runtime/web/submit/wtp",0);
	set("/runtime/web/submit/stunnel",0);
	set("/runtime/web/submit/lan",0);
	set("/runtime/web/submit/wan",0);
	set("/runtime/web/submit/console",0);
	set("/runtime/web/submit/wanapc",0);
	set("/runtime/web/submit/limit",0);
	set("/runtime/web/submit/httpdpasswd",0);
	set("/runtime/web/submit/snmp",0);
	set("/runtime/web/submit/pingctl",0);
	set("/runtime/web/submit/time",0);
	set("/runtime/web/submit/syslog",0);
	set("/runtime/web/submit/dhcpd",0);
	set("/runtime/web/submit/delaylan",0);
	set("/runtime/web/submit/lanchange",0);
	set("/runtime/web/submit/vlanportlist",0);
	set("/runtime/web/submit/vlan",0);
	set("/runtime/web/submit/ap_array",0);
	set("/runtime/web/submit/commit",0);
	set("/runtime/web/submit/dhcpd_restart", 0);
	set("/runtime/web/submit/dhcp_compare", 0);
	set("/runtime/web/submit/webredirect", 0);
	set("/runtime/web/submit/arpspoofing",0);
	set("/runtime/web/submit/captival",0);
	set("/runtime/web/submit/captival_tar",0);
	set("/runtime/web/submit/autorf",0);
	set("/runtime/web/submit/loadbalance",0);
	set("/runtime/web/submit/apneaps_v2",0);
	set("/runtime/web/submit/neaps",0);
	set("/runtime/web/submit/led",0);
	set("/runtime/web/submit/m2u",0);
	set("/runtime/web/submit/wlan_acl",0);
	set("/runtime/web/acl/upload_flag",0);
	set("/runtime/ui/apmode_status", "unchanged");
	set("/runtime/ui/apmode_status_a", "unchanged");
	set("/runtime/web/sub_str",$SUBMIT_STR);
	
/*start 2009_4_2 sandy for ap_array,if config version flag=1 , config version add 1 */
        $aparray_version_flag=query("/runtime/aparray/version_flag");
        if($aparray_version_flag==1)
        {
/*Modify add by daniel*-----------------------------------------------------*/
                $aparray_cfg_version=query("/wlan/inf:1/aparray_cfg_version");
                $aparray_cfg_version++;
                if(query("/wlan/inf:1/aparray_enable") == "0")
                {
                      // $aparray_cfg_version=1;
                }
                else
                {
                        if(query("/wlan/inf:1/arrayrole_original") == "3")
                        {
                                $aparray_cfg_version=0;
                        }
                        if(query("/wlan/inf:1/arrayrole_original") == "2")
                        {
                                if(query("/wlan/inf:1/arrayrole") != "1")
                                {
                                        $aparray_cfg_version=0;
                                }
                        }
                        if(query("/wlan/inf:1/arrayrole_original") == "1")
                        {
                                if(query("/wlan/inf:1/arrayrole") != "1")
                                {
                                        $aparray_cfg_version=0;
                                }
                        }
                }
/*Modify end------------------------------------------------------------------*/
                set("/wlan/inf:1/aparray_cfg_version",$aparray_cfg_version);
                set("/runtime/aparray/version_flag",0);
        }
/*end 2009_4_2 sandy---------------------------------------------------------------*/
        //set("/runtime/web/next_page",$ACTION_POST);
		if(query("/sys/swcontroller/enable") == 0 && query("/runtime/sys/swcontroller/active") == 1)
		{
			set("/runtime/sys/swcontroller/active", 0);
		}
		if(query("/sys/b_swcontroller/enable") == 0 && query("/runtime/sys/swcontroller/active") == 2)
		{
			set("/runtime/sys/swcontroller/active", 0);
		}
        if($SUBMIT_STR != "") {require("/www/cfg_valid.php");set("/sys/configversion"," ");}//tell CWM that user has changed something by web
        else                  {require("/www/__no_changed.php");}
}	
?>
