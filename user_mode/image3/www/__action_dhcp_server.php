<?
$AUTH_GROUP=fread("/var/proc/web/session:".$sid."/user/group");
if ($AUTH_GROUP!="0") {require("/www/permission_deny.php");exit;}
if($ACTION_POST == "adv_dhcpd")
{
	echo "<!--\n";
	echo "srv_enable	= ". $srv_enable  ."\n";
	echo "ipaddr		= ". $ipaddr  ."\n";
	echo "f_endip		= ". $f_endip  ."\n";
	echo "ipmask		= ". $ipmask  ."\n";
	echo "gateway		= ". $gateway  ."\n";
	echo "wins			= ". $wins  ."\n";
	echo "dns			= ". $dns  ."\n";
	echo "domain		= ". $domain  ."\n";
	echo "leasetime		= ". $leasetime  ."\n";
	echo "on_lan_change	= ". $on_lan_change  ."\n";
	echo "multi_srv_enable	= ". $multi_srv_enable  ."\n";
	echo "index	= ". $index  ."\n";
	echo "multi_enable	= ". $multi_enable  ."\n";
	echo "-->\n";	

	$SUBMIT_STR = "";		
	$dirty = 0;	
	$lan_dirty = 0;

	if(query("/lan/dhcp/server/enable")	!=$srv_enable)	{$dirty++; set("/lan/dhcp/server/enable", $srv_enable);}
	
	if($index == "")
	{
	  $index = 1;	
	}
	anchor("/lan/dhcp/server/pool:".$index);

		
	if($srv_enable == "1")
	{
		if($multi_dhcp_enable!="")
		{
			if(query("/lan/dhcp/server/multiinstances")	!=$multi_dhcp_enable)	{$dirty++; set("/lan/dhcp/server/multiinstances", $multi_dhcp_enable);}
		}
		if($multi_srv_enable!="")
		{
			if(query("enable")	!=$multi_srv_enable)	{$dirty++; set("enable", $multi_srv_enable);}
		}
		else
		{
			$multi_srv_enable="1";
			set("enable", $multi_srv_enable);
		}
		if(	$multi_srv_enable == "1")
		{
		if(query("startip")	!=$ipaddr)	{$lan_dirty++; $dirty++; set("startip", $ipaddr);}
		if(query("endip")	!=$f_endip)	{$dirty++; set("endip", $f_endip);}
		if(query("netmask")	!=$ipmask)	{$lan_dirty++; $dirty++; set("netmask", $ipmask);}
		if(query("gateway")	!=$gateway)	{$dirty++; set("gateway", $gateway);}
		if(query("primarywins")	!=$wins)	{$dirty++; set("primarywins", $wins);}
		if(query("dns1")	!=$dns)	{$dirty++; set("dns1", $dns);}
		if(query("whosetdomain")=="")
		{
			set("whosetdomain", "1");
			if(query("domain")	!=$domain)	{$dirty++; set("domain", $domain);}
		}
		else if (query("whosetdomain")=="1")
		{
			if(query("domain")	!=$domain)	{$dirty++; set("domain", $domain);}
		}
		if(query("leasetime")	!=$leasetime)	{$dirty++; set("leasetime", $leasetime);}	
	}
	}

	if($on_lan_change == "1")	{/*$SUBMIT_STR= "COMMIT";$SUBMIT_STR="submit COMMIT;submit LAN_CHANGE";*/ set("/runtime/web/submit/lanchange", 1);}
	if($lan_dirty > 0)	{/*$SUBMIT_STR= "COMMIT";$SUBMIT_STR=$SUBMIT_STR.";submit DELAY_LAN";*/set("/runtime/web/submit/delaylan", 1);}
	if($dirty > 0)	{/*$SUBMIT_STR= "COMMIT";$SUBMIT_STR=$SUBMIT_STR.";submit DHCPD";*/set("/runtime/web/submit/dhcpd", 1);}
		
	set("/runtime/web/sub_str",$SUBMIT_STR);
	set("/runtime/web/next_page",$ACTION_POST);
	/*if($dirty > 0 || $lan_dirty > 0 || $on_lan_change == "1") {*/require($G_SAVING_URL);//}
	//else                  {require($G_NO_CHANGED_URL);}	
}
else if($ACTION_POST == "adv_dhcps")
{
	$MAX_RULES=query("/lan/dhcp/server/pool:1/staticdhcp/max_client");
	if ($MAX_RULES==""){$MAX_RULES=25;}	
	
	$SUBMIT_STR = "";		
	$dirty = 0;		
	$entry = 1;
	
	for("/lan/dhcp/server/pool:1/staticdhcp/entry")
	{
		$entry ++;
	}
	
	if($f_entry_del!="")
	{
		$dirty++;
		del("/lan/dhcp/server/pool:1/staticdhcp/entry:".$f_entry_del);	
	}
	else
	{	
		if(query("/lan/dhcp/server/enable")	!=$srv_enable)	{$dirty++; set("/lan/dhcp/server/enable", $srv_enable);}
		if($srv_enable == "1")
		{	
			anchor("/lan/dhcp/server/pool:1");
			if(query("netmask")	!=$ipmask)	{$dirty++; set("netmask", $ipmask);}
			if(query("gateway")	!=$gateway)	{$dirty++; set("gateway", $gateway);}
			if(query("primarywins")	!=$wins)	{$dirty++; set("primarywins", $wins);}
			if(query("dns1")	!=$dns)	{$dirty++; set("dns1", $dns);}
			if(query("whosetdomain")=="")
			{
				set("whosetdomain", "2");
				if(query("domain")	!=$domain)	{$dirty++; set("domain", $domain);}
			}
			else if (query("whosetdomain")=="2")
			{
				if(query("domain")	!=$domain)	{$dirty++; set("domain", $domain);}
			}
			if($entry_edit != "")
			{
				anchor("/lan/dhcp/server/pool:1/staticdhcp/entry:".$entry_edit."/");
			}
			else
			{
				anchor("/lan/dhcp/server/pool:1/staticdhcp/entry:".$entry."/");
			}
			set("enable",1); $dirty++;
			if(query("hostname")	!=$host)		{$dirty++; set("hostname",$host);}
			if(query("ip")	!=$ipaddr)		{$dirty++; set("ip",$ipaddr);}
			if(query("mac")	!=$f_mac)		{$dirty++; set("mac",$f_mac);}
			if(query("netmask")	!=$ipmask)		{$dirty++; set("netmask",$ipmask);}
			if(query("gateway")	!=$gateway)		{$dirty++; set("gateway",$gateway);}
			if(query("primarywins")	!=$wins)		{$dirty++; set("primarywins",$wins);}
			if(query("dns1")	!=$dns)		{$dirty++; set("dns1",$dns);}
			if(query("whosetdomain")=="")
			{
				set("whosetdomain", "2");
				if(query("domain")	!=$domain)	{$dirty++; set("domain", $domain);}
			}
			else if (query("whosetdomain")=="2")
			{
				if(query("domain")	!=$domain)	{$dirty++; set("domain", $domain);}
			}
		}
	}
	if($dirty > 0)	{/*$SUBMIT_STR= "COMMIT";$SUBMIT_STR=$SUBMIT_STR.";submit DHCPD";*/set("/runtime/web/submit/dhcpd", 1);}

	set("/runtime/web/sub_str",$SUBMIT_STR);
	set("/runtime/web/next_page",$ACTION_POST);
	/*if($dirty > 0) {*/require($G_SAVING_URL);/*}
	else                  {require($G_NO_CHANGED_URL);}		*/
}	
?>
