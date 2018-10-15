<script type="text/javascript">
var S2I = function(str) { var num = parseInt(str, 10); return isNaN(num)?0:num;}
var EventName=null;
function SendEvent(str)
{
	var ajaxObj = GetAjaxObj(str);
	if (EventName != null) return;

	EventName = str;
	ajaxObj.createRequest();
	ajaxObj.onCallback = function (xml)
	{
		ajaxObj.release();
		//setTimeout("OnLoadBody()", 3*1000);
		EventName = null;
	}
	ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxObj.sendRequest("service.cgi", "EVENT="+EventName);
}
function WAN1DHCPRENEW()	{ SendEvent("WAN-1.DHCP.RENEW"); }
function WAN1DHCPRELEASE()	{ SendEvent("WAN-1.DHCP.RELEASE"); }
/*PPPoE or 3G*/
function WAN1PPPDIALUP()	{ SendEvent("WAN-1.PPP.DIALUP"); }
function WAN1PPPHANGUP()	{ SendEvent("WAN-1.PPP.HANGUP"); }
/*PPTP/L2TP*/
function WAN1COMBODIALUP()	{ SendEvent("WAN-1.COMBO.DIALUP"); }
function WAN1COMBOHANGUP()	{ SendEvent("WAN-1.COMBO.HANGUP"); }

function Page() {}
Page.prototype =
{
	services: "<?
		$layout = query("/runtime/device/layout");
		//echo "RUNTIME.TIME,RUNTIME.DEVICE,RUNTIME.PHYINF,WIFI.WLAN-1,";
		echo "RUNTIME.TIME,RUNTIME.DEVICE,RUNTIME.PHYINF,WIFI.PHYINF,";
		if ($layout=="router")
			echo "INET.WAN-1,INET.LAN-1,RUNTIME.INF.WAN-1,INET.WAN-2,RUNTIME.INF.WAN-2";
		else
			echo "RUNTIME.INF.BRIDGE-1";
		?>",
	OnLoad: function(){},
	OnUnload: function() {},
	OnSubmitCallback: function ()
	{
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		//PXML.doc.dbgdump();
		if (!this.InitGeneral()) return false;
		//if (!this.InitWLAN()) return false;
		if (!this.InitWLAN("WLAN-1","WIFI.PHYINF")) return false;
		if (!this.InitWLAN("WLAN-2","WIFI.PHYINF")) return false;
<?
		if ($layout=="router")
		{
			echo "\t\tif (!this.InitLAN()) return false;\n";
			echo "\t\tif (!this.InitWAN()) return false;\n";
		}
		else
		{
			echo "\t\tif (!this.InitBridge()) return false;\n";
		}
?>			
<?
	    if (isfile("/htdocs/webinc/js/st_device_3G.php")==1)
	    {
	        echo "if (!WAN3G.InitWan3g()) return false;\n";
        }
?>		
		return true;
	},
	PreSubmit: function()
	{
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	lanip: null,
	inetp: null,
	dhcps4: null,
	leasep: null,
	mask: null,
	ipdirty: false,
	InitGeneral: function ()
	{
        this.timep = PXML.FindModule("RUNTIME.TIME");
		this.uptime = XG  (this.timep+"/runtime/device/uptime");
		if (!this.uptime)
		{
			BODY.ShowAlert("InitGeneral() ERROR!!!");
			return false;
		}
		OBJ("st_time").innerHTML  = this.uptime;
		return true;
	},
	InitWLAN: function ( wlan_phyinf, wifi_phyinf )
	{
		var str_Aband;
		
		var wifi_phyinf_path = PXML.FindModule(wifi_phyinf);
		var phy_wlan_path = GPBT(wifi_phyinf_path, "phyinf", "uid", wlan_phyinf, false);
		var wifi_profile_name = XG(phy_wlan_path+"/wifi");
		var wifi_path = GPBT(wifi_phyinf_path+"/wifi", "entry", "uid", wifi_profile_name, false);
		
		var rphy = PXML.FindModule("RUNTIME.PHYINF");
		var rwlan1p = 	GPBT(rphy+"/runtime", "phyinf", "uid", wlan_phyinf, false);
		var freq = XG(phy_wlan_path+"/media/freq");
		
		if ((!wifi_path)||(!phy_wlan_path))
		{
			BODY.ShowAlert("InitWLAN() ERROR!!!");
			return false;
		}
		
		if(freq=="5")
			str_Aband = "_Aband";
		else 
			str_Aband = "";
			
		OBJ("st_wireless_radio"+str_Aband).innerHTML  = XG(phy_wlan_path+"/active")== "1" ? "<?echo i18n("Enabled");?>":"<?echo i18n("Disabled");?>";
		var IEEE80211mode =  XG(phy_wlan_path+"/media/wlmode");
		var string_bandwidth = "20MHZ";
		var check_bandwidth = 0;
        switch (IEEE80211mode)
		{
		   case "bgn":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("Mixed 802.11n, 802.11g and 802.11b");?>";
                check_bandwidth = 1;
				break;
		   case "bg":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("Mixed 802.11g and 802.11b");?>";
				break;
		   case "gn":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("Mixed 802.11n and 802.11g");?>";
                check_bandwidth = 1;
				break;
		   case "an":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("Mixed 802.11n and 802.11a");?>";
                check_bandwidth = 1;
				break;
		   case "n":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("802.11n only");?>";
                check_bandwidth = 1;
				break;
		   case "b":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("802.11b only");?>";
				break;
		   case "g":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("802.11g only");?>";
				break;
		   case "a":
		   		OBJ("st_80211mode"+str_Aband).innerHTML  = "<?echo i18n("802.11a only");?>";
				break;
		}
		if (check_bandwidth==1)
		{
			string_bandwidth = XG  (phy_wlan_path+"/media/dot11n/bandwidth")== "20+40" ? "20/40MHz":"20MHz";
		}
		OBJ("st_Channel_Width"+str_Aband).innerHTML  = string_bandwidth;
		OBJ("st_Channel"+str_Aband).innerHTML  = XG  (rwlan1p+"/media/channel");
		
		var channel = XG  (rwlan1p+"/media/channel");
		OBJ("st_Channel"+str_Aband).innerHTML  = channel ? channel : "N/A";
		
		OBJ("st_SSID"+str_Aband).innerHTML  = XG  (wifi_path+"/ssid");
        var string_WPS =  "<?echo i18n("Disabled");?>";
		if ( XG  (wifi_path+"/wps/enable") == "1")
		{
        	string_WPS =  "<?echo i18n("Enabled");?>"+"/"+ (XG  (wifi_path+"/wps/configured")== "1" ? "<?echo i18n("Configured");?>":"<?echo i18n("Unconfigured");?>");
		}
		OBJ("st_WPS_status"+str_Aband).innerHTML  = string_WPS;
        var string_security = "<?echo i18n("Disabled");?>"; 
        if (XG  (wifi_path+"/encrtype") != "NONE")
		{
		    switch(XG  (wifi_path+"/authtype"))
			{
				case "OPEN":
				case "SHARED":
			    	string_security = "<?echo i18n("WEP");?>";
					break;
				case "WPA":
			    	string_security = "<?echo i18n("WPA-EAP");?>";
					break;
				case "WPA2":
			    	string_security = "<?echo i18n("WPA2-EAP");?>";
					break;
				case "WPA+2":
			    	string_security = "<?echo i18n("WPA/WPA2-EAP");?>";
					break;
				case "WPAPSK":
			    	string_security = "<?echo i18n("WPA-PSK");?>";
					break;
				case "WPA2PSK":
			    	string_security = "<?echo i18n("WPA2-PSK");?>";
					break;
				case "WPA+2PSK":
			    	string_security = "<?echo i18n("WPA/WPA2-PSK");?>";
					break;
			}
		}
		OBJ("st_security"+str_Aband).innerHTML  = string_security;
	    return true;
	},
	DHCP_Renew: function()
	{
	 	OBJ("st_wan_dhcp_renew").disabled	= true;	//Joseph Chao
	    WAN1DHCPRENEW();
	    setTimeout("BODY.OnLoad()", 5*1000);
	},
	DHCP_Release: function()
	{
		OBJ("st_wan_dhcp_release").disabled	= true; //Joseph Chao
	    WAN1DHCPRELEASE();
	    setTimeout("BODY.OnLoad()", 5*1000);
	},
	PPP_Connect: function()
	{
	    var wan	= PXML.FindModule("INET.WAN-1");
	    var combo = XG  (wan+"/inf/lowerlayer");
	    if (combo !="")
	        WAN1COMBODIALUP();
	    else
	     WAN1PPPDIALUP();
	     setTimeout("BODY.OnLoad()", 5*1000);
	},
	PPP_Disconnect: function()
	{
	    var wan	= PXML.FindModule("INET.WAN-1");
	    var combo = XG  (wan+"/inf/lowerlayer");
	    if (combo !="")
	        WAN1COMBOHANGUP();
	    else
	     WAN1PPPHANGUP();
	     setTimeout("BODY.OnLoad()", 5*1000);
	},
	InitWAN: function ()
	{
		var wan	= PXML.FindModule("INET.WAN-1");
		var rwan = PXML.FindModule("RUNTIME.INF.WAN-1");
		var rphy = PXML.FindModule("RUNTIME.PHYINF");
		var waninetuid = XG  (wan+"/inf/inet");
		var wanphyuid = XG  (wan+"/inf/phyinf");
		this.waninetp = GPBT(wan+"/inet", "entry", "uid", waninetuid, false);
		this.rwaninetp = GPBT(rwan+"/runtime/inf", "inet", "uid", waninetuid, false);      
		this.rwanphyp = GPBT(rphy+"/runtime", "phyinf", "uid", wanphyuid, false);     
		var str_networkstatus = str_Disconnected = "<?echo i18n("Disconnected");?>";
		var str_Connected = "<?echo i18n("Connected");?>";
		var wan_uptime = S2I(XG  (this.rwaninetp+"/uptime"));
		var system_uptime = S2I(XG  (this.timep+"/runtime/device/uptimes"));
		var wan_delta_uptime = (system_uptime-wan_uptime);
		var wan_uptime_sec = 0;
		var wan_uptime_min = 0;
		var wan_uptime_hour = 0;
		var wan_uptime_day = 0;
		var str_wanipaddr = str_wangateway = str_wanDNSserver = str_wanDNSserver2 = str_wannetmask ="0.0.0.0";
		var str_name_wanipaddr = "<?echo i18n("IP Address");?>";
		var str_name_wangateway = "<?echo i18n("Default Gateway");?>";

        var wancable_status=0;
		var wan_network_status=0;
		if ((!this.waninetp))
		{
			BODY.ShowAlert("InitWAN() ERROR!!!");
			return false;
		}

        if((XG  (this.rwanphyp+"/linkstatus")!="0")&&(XG  (this.rwanphyp+"/linkstatus")!=""))
        {
		   wancable_status=1;
		}
		OBJ("st_wancable").innerHTML  = wancable_status==1 ? str_Connected:str_Disconnected;

		if (XG  (this.waninetp+"/addrtype") == "ipv4")
		{
			if(XG(this.waninetp+"/ipv4/ipv4in6/mode")!="")
			{
				OBJ("st_dslite_wantype").innerHTML  = "DS-Lite";
				str_dslite_networkstatus  = str_Disconnected;
				//if ((XG  (this.rwaninetp+"/ipv4/valid")== "1")&& (wancable_status==1))
				if (wancable_status==1)
				{
					wan_network_status=1;
					str_dslite_networkstatus = str_Connected;
				}

				OBJ("st_aftrserver").innerHTML = XG(rwan+"/runtime/inf/inet/ipv4/ipv4in6/remote");	
				OBJ("st_dslite_wancable").innerHTML  = wancable_status==1 ? str_Connected:str_Disconnected;
				var dslite_remote = XG(this.waninetp+"/ipv4/ipv4in6/remote");
				OBJ("st_dslite_dhcp6opt").innerHTML = dslite_remote==""? "Enabled":"Disabled";
			}
			else
			{
            if(XG  ( this.waninetp+"/ipv4/static")== "1")
			{
			    OBJ("st_wantype").innerHTML  = "Static IP";
			    str_networkstatus  = wancable_status== 1 ? str_Connected:str_Disconnected;
			    wan_network_status=wancable_status;
			}
			else
			{
			    OBJ("st_wantype").innerHTML  = "DHCP Client";
				if ((XG  (this.rwaninetp+"/ipv4/valid")== "1")&& (wancable_status==1))
				{
					wan_network_status=1;
					str_networkstatus = str_Connected;
				}
				OBJ("st_wan_dhcp_action").style.display = "block";
            
				}
			}
		}
		else if (XG  (this.waninetp+"/addrtype") == "ppp4" || XG(this.waninetp+"/addrtype") == "ppp10")
		{
		    
            if(XG  ( this.waninetp+"/ppp4/over")== "eth")
			{
				OBJ("st_wantype").innerHTML  = "PPPoE";
			}
			else if(XG  ( this.waninetp+"/ppp4/over")== "pptp")
			    {
			        OBJ("st_wantype").innerHTML  = "PPTP";
			    }
			else if(XG  ( this.waninetp+"/ppp4/over")== "l2tp")
			    {
			        OBJ("st_wantype").innerHTML  = "L2TP";
			    }
			else
			    {OBJ("st_wantype").innerHTML  = "Unknow WAN type";}
			
			var connStat = XG(rwan+"/runtime/inf/pppd/status");    
			    
			if ((XG  (this.rwaninetp+"/ppp4/valid")== "1")&& (wancable_status==1))
				{
					wan_network_status=1;
				} 
		    switch (connStat)
	            {
	                case "connected":
            		if (wan_network_status == 1)
		            {
		                str_networkstatus=str_Connected;
                        OBJ("st_wan_ppp_connect").disabled = true;
                        OBJ("st_wan_ppp_disconnect").disabled = false;
		            }
		            else
		            {
		                str_networkstatus=str_Disconnected;
                        OBJ("st_wan_ppp_connect").disabled = false;
                        OBJ("st_wan_ppp_disconnect").disabled = true;
		            }
		            break;
		            case "":
	                case "disconnected":
                    {
		                str_networkstatus=str_Disconnected;
                        OBJ("st_wan_ppp_connect").disabled = false;
                        OBJ("st_wan_ppp_disconnect").disabled = true;
                        wan_network_status=0;
		            }
		            break;
	                case "on demand":
		                str_networkstatus="<?echo i18n("Idle");?>";
                        OBJ("st_wan_ppp_connect").disabled = false;
                        OBJ("st_wan_ppp_disconnect").disabled = true;
                        wan_network_status=0;
		            break;
	                default:
		                str_networkstatus = "<?echo i18n("Busy ...");?>";
                        OBJ("st_wan_ppp_connect").disabled = false;
                        OBJ("st_wan_ppp_disconnect").disabled = false;
		                setTimeout("BODY.OnLoad()", 6*1000);
		                break;
	                }	
    
			 str_name_wanipaddr = "<?echo i18n("Local address");?>";
		     str_name_wangateway = "<?echo i18n("Peer address");?>";
		     OBJ("st_wan_ppp_action").style.display = "block";
		     
		     if(XG(wan+"/inf/schedule")!="" && this.rwaninetp==null)
		     {
		     	OBJ("st_wan_ppp_connect").disabled = true;
                OBJ("st_wan_ppp_disconnect").disabled = true;
		     }
		}

		if ((XG  (this.rwaninetp+"/addrtype") == "ipv4")&& wan_network_status==1)
		{
		    str_wanipaddr = XG  (this.rwaninetp+"/ipv4/ipaddr");
		    str_wangateway =  XG  (this.rwaninetp+"/ipv4/gateway");
		    
		    str_wannetmask =  COMM_IPv4INT2MASK(XG  (this.rwaninetp+"/ipv4/mask"));
		    str_wanDNSserver = XG  (this.rwaninetp+"/ipv4/dns:1");
		    str_wanDNSserver2 = XG  (this.rwaninetp+"/ipv4/dns:2");
		}
		else if ((XG  (this.rwaninetp+"/addrtype") == "ppp4")&& wan_network_status==1)
		{
		    str_wanipaddr = XG  (this.rwaninetp+"/ppp4/local");
		    str_wangateway = XG  (this.rwaninetp+"/ppp4/peer");
		    str_wannetmask = "255.255.255.255";
		    str_wanDNSserver = XG  (this.rwaninetp+"/ppp4/dns:1");
		    str_wanDNSserver2 = XG  (this.rwaninetp+"/ppp4/dns:2");
		    if(str_wanDNSserver == "" && str_wanDNSserver2 == "")
		    {
		        var wan2 = PXML.FindModule("INET.WAN-2");
		        var rwan2 = PXML.FindModule("RUNTIME.INF.WAN-2");
		        var waninetuid2 = XG  (wan2+"/inf/inet");
		        var rwaninetp2 = GPBT(rwan2+"/runtime/inf", "inet", "uid", waninetuid2, false); 
		        str_wanDNSserver = XG  (rwaninetp2+"/ipv4/dns:1");
		        str_wanDNSserver2 = XG  (rwaninetp2+"/ipv4/dns:2");
		    }
		}
		else if ((XG  (this.rwaninetp+"/addrtype") == "ppp10")&& wan_network_status==1)
		{
		    str_wanipaddr = XG  (this.rwaninetp+"/ppp4/local");
		    str_wangateway = XG  (this.rwaninetp+"/ppp4/peer");
		    str_wannetmask = "255.255.255.255";
		    str_wanDNSserver = XG  (this.rwaninetp+"/ppp4/dns:1");
		    str_wanDNSserver2 = XG  (this.rwaninetp+"/ppp4/dns:2");
		    if(str_wanDNSserver == "" && str_wanDNSserver2 == "")
		    {
		        var wan2 = PXML.FindModule("INET.WAN-2");
		        var rwan2 = PXML.FindModule("RUNTIME.INF.WAN-2");
		        var waninetuid2 = XG  (wan2+"/inf/inet");
		        var rwaninetp2 = GPBT(rwan2+"/runtime/inf", "inet", "uid", waninetuid2, false); 
		        str_wanDNSserver = XG  (rwaninetp2+"/ipv4/dns:1");
		        str_wanDNSserver2 = XG  (rwaninetp2+"/ipv4/dns:2");
		    }
		}

        if ((wan_network_status==1)&& (wan_delta_uptime > 0)&& (wan_uptime > 0))
		{
			wan_uptime_sec = wan_delta_uptime%60;
			wan_uptime_min = Math.floor(wan_delta_uptime/60)%60;
		 	wan_uptime_hour = Math.floor(wan_delta_uptime/3600)%24;
		 	wan_uptime_day = Math.floor(wan_delta_uptime/86400);
		 	if (wan_uptime_sec < 0)
		 	{
		 	    wan_uptime_sec=0;
		 	    wan_uptime_min=0;
		 	    wan_uptime_hour=0;
		 	    wan_uptime_day=0;
		 	}
		 	
		 	
		}

        //Joseph Chao
		if (str_networkstatus == "Connected") 
		{
			OBJ("st_wan_dhcp_renew").disabled	= true;
        	OBJ("st_wan_dhcp_release").disabled	= false;
        }
        else if (str_networkstatus == "Disconnected") 
		{
			OBJ("st_wan_dhcp_renew").disabled	= false;
        	OBJ("st_wan_dhcp_release").disabled	= true;
        }
        //Joseph Chao

		if(XG(this.waninetp+"/ipv4/ipv4in6/mode")!="")
		{
			OBJ("st_dslite_networkstatus").innerHTML = str_dslite_networkstatus; 
			OBJ("st_dslite_wan_mac").innerHTML  =  XG  (this.rwanphyp+"/macaddr");

			OBJ("st_dslite_connection_uptime").innerHTML=  wan_uptime_day+" "+"<?echo i18n("Day");?>"+" "+wan_uptime_hour+" "+"<?echo i18n("Hour");?>"+" "+wan_uptime_min+" "+"<?echo i18n("Min");?>"+" "+wan_uptime_sec+" "+"<?echo i18n("Sec");?>";
			OBJ("wan_ethernet_dslite_block").style.display = "block";
		}
		else
		{
		OBJ("st_networkstatus").innerHTML = str_networkstatus; 
		OBJ("name_wanipaddr").innerHTML = str_name_wanipaddr;
		OBJ("name_wangateway").innerHTML = str_name_wangateway;
		OBJ("st_wanipaddr").innerHTML  = str_wanipaddr;
		OBJ("st_wangateway").innerHTML  =  str_wangateway;
		OBJ("st_wanDNSserver").innerHTML  = str_wanDNSserver!="" ? str_wanDNSserver:"0.0.0.0";
		OBJ("st_wanDNSserver2").innerHTML  = str_wanDNSserver2!="" ? str_wanDNSserver2:"0.0.0.0";
		OBJ("st_wannetmask").innerHTML  =  str_wannetmask;
		OBJ("st_wan_mac").innerHTML  =  XG  (this.rwanphyp+"/macaddr");
		
		OBJ("st_connection_uptime").innerHTML=  wan_uptime_day+" "+"<?echo i18n("Day");?>"+" "+wan_uptime_hour+" "+"<?echo i18n("Hour");?>"+" "+wan_uptime_min+" "+"<?echo i18n("Min");?>"+" "+wan_uptime_sec+" "+"<?echo i18n("Sec");?>";
		OBJ("wan_ethernet_block").style.display = "block";
		}
		return true;
	},
	InitLAN: function()
	{
		var lan	= PXML.FindModule("INET.LAN-1");
		var inetuid = XG  (lan+"/inf/inet");
		//var phyuid = XG  (lan+"/inf/phyinf");
		//var phy = PXML.FindModule("RUNTIME.PHYINF");
		this.inetp = GPBT(lan+"/inet", "entry", "uid", inetuid, false);
		//this.phyp = GPBT(phy+"/runtime", "phyinf", "uid", phyuid, false);
		if (!this.inetp)
		{
			BODY.ShowAlert("InitLAN() ERROR!!!");
			return false;
		}

        //this.macaddr=XG  (this.phyp+"/macaddr");
		//OBJ("st_macaddr").innerHTML  = this.macaddr;

		if (XG  (this.inetp+"/addrtype") == "ipv4")
		{
			var b = this.inetp+"/ipv4";
			this.lanip = XG  (b+"/ipaddr");
			this.mask = XG  (b+"/mask");
			this.dhcpsenable=XG  (lan+"/inf/dhcps4");
			OBJ("st_ip_address").innerHTML	= this.lanip;
			OBJ("st_netmask").innerHTML = COMM_IPv4INT2MASK(this.mask);
		    OBJ("st_dhcpserver_enable").innerHTML  = XG  (lan+"/inf/dhcps4")== "DHCPS4-1" ? "<?echo i18n("Enabled");?>":"<?echo i18n("Disabled");?>";
		}

		OBJ("lan_ethernet_block").style.display = "block";
		return true;
	},
	InitBridge: function()
	{
		var br = PXML.FindModule("RUNTIME.INF.BRIDGE-1");
		if (!br) { BODY.ShowAlert("InitBridge() ERROR !!!"); return false; }
		var wantype = XG(br+"/runtime/inf/inet/addrtype");
		var wantype_str = "Unknow WAN type";
		if (wantype=="ipv4")
		{
			if (XG(br+"/runtime/inf/udhcpc/inet")!="")
				wantype_str = "DHCP Client";
			else
				wantype_str = "Static IP";
		}
		else if (wantype=="ppp4")
			wantype_str = "PPPoE";
		OBJ("br_wantype").innerHTML = wantype_str;
		
		if (wantype=="ipv4")
		{
			var b = br+"/runtime/inf/inet/ipv4";
			if (XG(b+"/valid")=="1")
			{
				OBJ("br_ipaddr").innerHTML = XG(b+"/ipaddr");
				OBJ("br_netmask").innerHTML= COMM_IPv4INT2MASK(XG(b+"/mask"));
				OBJ("br_gateway").innerHTML= XG(b+"/gateway");
				OBJ("br_dns1").innerHTML   = XG(b+"/dns:1");
				OBJ("br_dns2").innerHTML   = XG(b+"/dns:2");
			}
		}
		else if (wantype=="ppp4")
		{
			var b = br+"/runtime/inf/inet/ppp4";
			if (XG(b+"/valid")=="1")
			{
				OBJ("br_ipaddr").innerHTML = XG(b+"/ipaddr");
				OBJ("br_netmask").innerHTML= COMM_IPv4INT2MASK(XG(b+"/mask"));
				OBJ("br_gateway").innerHTML= XG(b+"/gateway");
				OBJ("br_dns1").innerHTML   = XG(b+"/dns:1");
				OBJ("br_dns2").innerHTML   = XG(b+"/dns:2");
			}
		}
		OBJ("ethernet_block").style.display = "block";
		return true;
	},
	ResetXML: function()
	{
		COMM_GetCFG(
			false,
			PAGE.services,
			function(xml) {
				PXML.doc = xml;
			}
		);
	}

}
<?
	if (isfile("/htdocs/webinc/js/st_device_3G.php")==1)
		dophp("load", "/htdocs/webinc/js/st_device_3G.php");
?>	
</script>
