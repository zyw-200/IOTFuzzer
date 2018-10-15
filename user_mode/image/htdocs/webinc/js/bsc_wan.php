<?include "/htdocs/phplib/inet.php";?>
<?include "/htdocs/phplib/inf.php";?>
<?
	$inet = INF_getinfinfo("LAN-1", "inet");
	$ipaddr = INET_getinetinfo($inet, "ipv4/ipaddr");
?>
<script type="text/javascript">
var mac_clone_changed = 0;
function Page() {}
Page.prototype =
{
	//services: "DEVICE.LAYOUT,DEVICE.HOSTNAME,PHYINF.WAN-1,INET.BRIDGE-1,INET.INF,WAN",
	services: "DEVICE.LAYOUT,DEVICE.HOSTNAME,PHYINF.WAN-1,INET.BRIDGE-1,INET.INF,WAN,RUNTIME.INF.WAN-3,INET.LAN-4,RUNTIME.INF.WAN-1,RUNTIME.INF.WAN-4",
	OnLoad: function() {},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		BODY.ShowContent();
		switch (code)
		{
		case "OK":
			if ( mac_clone_changed==1 || COMM_Equal(OBJ("rgmode").getAttribute("modified"), true))
			{
				if (mac_clone_changed==1)
				{
					var msgArray =
					[
						'<?echo i18n("You may need to change the IP address of your computer to access the device.");?>',
						'<?echo i18n("You can access the device by clicking the link below.");?>',
						'<a href="http://<?echo $ipaddr;?>" style="color:#0000ff;">http://<?echo $ipaddr;?></a>'
					];
				}
				else if (OBJ("rgmode").checked)
				{
					if (this.bridge_addrtype==="static")
					{
						/* change to bridge mode and use static ip. */
						var url = '<a href="http://'+this.bridge_ipaddr+'" style="color:#0000ff;">http://'+this.bridge_ipaddr+'</a>';
						var msgArray =
						[
							'<?echo i18n("The device is changing to AP mode.");?>',
							'<?echo i18n("You can access the device by clicking the link below.");?>',
							url
						];
					}
					else
					{
						/* change to bridge mode and use DHCP. */
						var msgArray =
						[
							'<?echo i18n("The device is changing to AP mode.");?>',
							'<?echo i18n("The device will dynamically get an IP address from DHCP server.");?>',
							'<?echo i18n("Please check the DHCP server for the IP address of the device.");?>',
							'<?echo i18n("Or use UPnP tools to discover the device.");?>'
						];
					}
				}
				else
				{
					/* change to router mode. */
					var msgArray =
					[
						'<?echo i18n("The device is changing to router mode.");?>',
						'<?echo i18n("You may need to change the IP address of your computer to access the device.");?>',
						'<?echo i18n("You can access the device by clicking the link below.");?>',
						'<a href="http://<?echo $ipaddr;?>" style="color:#0000ff;">http://<?echo $ipaddr;?></a>'
					];
				}
				BODY.ShowCountdown('<?echo i18n("Device Mode");?>', msgArray, this.bootuptime, null);
			}
			else
			{
				BODY.OnReload();
			}
			break;
		case "BUSY":
			BODY.ShowAlert("<?echo i18n("Someone is configuring the device, please try again later.");?>");
			break;
		case "HEDWIG":
			BODY.ShowAlert(result.Get("/hedwig/message"));
			break;
		case "PIGWIDGEON":
			if (result.Get("/pigwidgeon/message")==="no power")
			{
				BODY.NoPower();
			}
			else
			{
				BODY.ShowAlert(result.Get("/pigwidgeon/message"));
			}
			break;
		}
		return true;
	},
	InitValue: function(xml)
	{
		mac_clone_changed = 0;
		this.defaultCFGXML = xml;
		PXML.doc = xml;

		/* init the WAN-# & br-# obj */
		var base = PXML.FindModule("INET.INF");
		this.wan1.infp	= GPBT(base, "inf", "uid", "WAN-1", false);
		this.wan1.inetp	= GPBT(base+"/inet", "entry", "uid", XG(this.wan1.infp+"/inet"), false);
		var b = PXML.FindModule("PHYINF.WAN-1");
		this.wan1.phyinfp = GPBT(b, "phyinf", "uid", XG(b+"/inf/phyinf"), false);
		
		this.wan2.infp	= GPBT(base, "inf", "uid", "WAN-2", false);
		this.wan2.inetp	= GPBT(base+"/inet", "entry", "uid", XG(this.wan2.infp+"/inet"), false);
		this.wan3.infp	= GPBT(base, "inf", "uid", "WAN-3", false);
		this.wan3.inetp	= GPBT(base+"/inet", "entry", "uid", XG(this.wan3.infp+"/inet"), false);
		this.wan4.infp	= GPBT(base, "inf", "uid", "WAN-4", false);
		this.wan4.inetp	= GPBT(base+"/inet", "entry", "uid", XG(this.wan4.infp+"/inet"), false);

		this.br1.infp	= GPBT(base, "inf", "uid", "BRIDGE-1", false);
		this.br1.inetp	= GPBT(base+"/inet", "entry", "uid", XG(this.br1.infp+"/inet"), false);

		this.lan4.infp	= GPBT(base, "inf", "uid", "LAN-4", false);
		this.lan4.inetp	= GPBT(base+"/inet", "entry", "uid", XG(this.lan4.infp+"/inet"), false);

		if (!base) { alert("InitValue ERROR!"); return false; }

		var layout = PXML.FindModule("DEVICE.LAYOUT");
		if (!layout) { alert("InitLayout ERROR !"); return false; }

		this.device_host = PXML.FindModule("DEVICE.HOSTNAME");
		if (!this.device_host) { alert("Init Device Host ERROR !"); return false; }

		OBJ("rgmode").checked = XG(layout+"/device/layout")==="bridge" ? true :false;

		/* init wan type */
		var wan1addrtype = XG(this.wan1.inetp+"/addrtype");
		if (wan1addrtype === "ipv4")
		{
			if (XG(this.wan1.inetp+"/ipv4/static")==="1")	COMM_SetSelectValue(OBJ("wan_ip_mode"), "static");
			else
			{
				OBJ("dhcpplus_username").value = XG(this.wan1.inetp+"/ipv4/dhcpplus/username");
				OBJ("dhcpplus_password").value = XG(this.wan1.inetp+"/ipv4/dhcpplus/password");
				if (XG(this.wan1.inetp+"/ipv4/dhcpplus/enable")==="1")
					COMM_SetSelectValue(OBJ("wan_ip_mode"), "dhcpplus");
				else
					COMM_SetSelectValue(OBJ("wan_ip_mode"), "dhcp");
			}

			if (XG(this.wan1.inetp+"/ipv4/ipv4in6/mode")!="")	
				COMM_SetSelectValue(OBJ("wan_ip_mode"), XG(this.wan1.inetp+"/ipv4/ipv4in6/mode"));
		}
		else if (wan1addrtype === "ppp4")
		{
			var over = XG(this.wan1.inetp+"/ppp4/over");
			if (over === "eth")
			{
				if (XG(this.wan2.infp+"/nat") === "NAT-1" && XG(this.wan2.infp+"/active")==="1")
					COMM_SetSelectValue(OBJ("wan_ip_mode"), "r_pppoe");
				else
					COMM_SetSelectValue(OBJ("wan_ip_mode"), "pppoe");
			}
			else if (over === "pptp")
			{
				if (XG(this.wan2.infp+"/nat") === "NAT-1" && XG(this.wan2.infp+"/active")==="1")
					COMM_SetSelectValue(OBJ("wan_ip_mode"), "r_pptp");
				else
					COMM_SetSelectValue(OBJ("wan_ip_mode"), "pptp");
			}
			else if (over === "l2tp")
			{
				COMM_SetSelectValue(OBJ("wan_ip_mode"), "l2tp");
			}
		}
		else if (wan1addrtype === "ppp10")
		{
			var over = XG(this.wan1.inetp+"/ppp4/over");
			if (over === "eth")
			{
				COMM_SetSelectValue(OBJ("wan_ip_mode"), "pppoe");
			}
		}
		/* init ip setting */
		if (!this.InitIpv4Value()) return false;
		if (!this.InitPpp4Value()) return false;

		if(wan1addrtype === "ppp10")
		{
			var over = XG(this.wan1.inetp+"/ppp4/over");
			switch (over)
			{
				case "eth":
					if (XG(this.wan1.inetp+"/ppp4/static")==="1")	OBJ("pppoe_static").checked = true;
					else						OBJ("pppoe_dynamic").checked = true;
					OBJ("pppoe_ipaddr").value		= XG(this.wan1.inetp+"/ppp4/ipaddr");
					OBJ("pppoe_username").value		= XG(this.wan1.inetp+"/ppp6/username");
					OBJ("pppoe_password").value		= XG(this.wan1.inetp+"/ppp6/password");
					OBJ("confirm_pppoe_password").value	= XG(this.wan1.inetp+"/ppp6/password");
					OBJ("pppoe_service_name").value	= XG(this.wan1.inetp+"/ppp6/pppoe/servicename");
					/*
					var dialup = XG(this.wan1.inetp+"/ppp6/dialup/mode");
					if		(dialup === "auto")		OBJ("pppoe_alwayson").checked = true;
					else if	(dialup === "manual")	OBJ("pppoe_manual").checked = true;
					else							OBJ("pppoe_ondemand").checked = true;
					*/
					OBJ("pppoe_max_idle_time").value = XG(this.wan1.inetp+"/ppp6/dialup/idletimeout");
					if (XG(this.wan1.inetp+"/ppp4/dns/count") > 0)	OBJ("dns_manual").checked = true;
					else OBJ("dns_isp").checked = true;
					OBJ("pppoe_dns1").value = XG(this.wan1.inetp+"/ppp4/dns/entry:1");
					if (XG(this.wan1.inetp+"/ppp4/dns/count")>=2) OBJ("pppoe_dns2").value = XG(this.wan1.inetp+"/ppp4/dns/entry:2");
					break;
			}
		}
		this.OnClickRgmode("InitValue");
		return true;
	},
	PreSubmit: function()
	{
		/* disable all modules */
		PXML.IgnoreModule("DEVICE.LAYOUT");
		PXML.IgnoreModule("DEVICE.HOSTNAME");
		PXML.IgnoreModule("PHYINF.WAN-1");
		PXML.IgnoreModule("INET.BRIDGE-1");
		PXML.IgnoreModule("WAN");
		PXML.IgnoreModule("RUNTIME.INF.WAN-1");
		PXML.IgnoreModule("RUNTIME.INF.WAN-3");
		PXML.IgnoreModule("RUNTIME.INF.WAN-4");

		/* router/bridge mode setting */
		if (COMM_Equal(OBJ("rgmode").getAttribute("modified"), "true"))
		{
			var layout = PXML.FindModule("DEVICE.LAYOUT")+"/device/layout";

			PXML.ActiveModule("DEVICE.LAYOUT");
			PXML.CheckModule("INET.BRIDGE-1", "ignore", "ignore", null);

			if (OBJ("rgmode").checked)
			{
				/* router -> bridge mode */
				XS(layout, "bridge");
				this.bridge_addrtype = "dhcp";
				/* If WAN-1 uses static IP address, use the IP as the bridge's IP. */
				if (XG(this.wan1.inetp+"/addrtype")==="ipv4" && XG(this.wan1.inetp+"/ipv4/static")==="1")
				{
					XS(this.br1.infp+"/previous/inet", XG(this.br1.infp+"/inet"));
					XS(this.br1.infp+"/inet", XG(this.wan1.infp+"/inet"));
					this.bridge_addrtype = "static";
					this.bridge_ipaddr = XG(this.wan1.inetp+"/ipv4/ipaddr");
				}
				/* ignore other services */

				return PXML.doc;
			}
			else
			{
				/* bridge -> router */
				XS(layout, "router");

				/* restore the inet of bridge */
				if (XG(this.br1.infp+"/previous/inet")!=="")
				{
					XS(this.br1.infp+"/inet", XG(this.br1.infp+"/previous/inet"));
					XD(this.br1.infp+"/previous/inet");
				}
			}
		}

		/* clear WAN-2 & clone mac */
		var russia = false;
		XS(this.wan1.infp+"/schedule","");
		XS(this.wan1.infp+"/lowerlayer","");
		XS(this.wan1.infp+"/upperlayer","");
		XS(this.wan2.infp+"/schedule","");
		XS(this.wan2.infp+"/lowerlayer","");
		XS(this.wan2.infp+"/upperlayer","");
		XS(this.wan2.infp+"/active", "0");
		XS(this.wan2.infp+"/nat","");
		XS(this.wan1.inetp+"/ipv4/ipv4in6/mode","");
		XS(this.wan1.infp+"/infprevious", "");
		XS(this.wan4.infp+"/infnext", "");
	
		if (COMM_Equal(OBJ("dhcp_host_name").getAttribute("modified"), "true"))
			PXML.ActiveModule("DEVICE.HOSTNAME");
		else
			PXML.IgnoreModule("DEVICE.HOSTNAME");

		/* disable dns6 relay */
		//var base = PXML.FindModule("INET.LAN-4");
		//var lan4_infp	= GPBT(base, "inf", "uid", "LAN-4", false);
		//XS(lan4_infp+"/dns6", "");
		//XS(lan4_infp+"/dnsrelay", "0");
		XS(this.lan4.infp+"/dns6", "");
		XS(this.lan4.infp+"/dnsrelay", "0");

		var mtu_obj = "ipv4_mtu";
		var mac_obj = "ipv4_macaddr";
		switch(OBJ("wan_ip_mode").value)
		{
		case "static":
			if (!this.PreStatic()) return null;
			break;
		case "dhcp":
		case "dhcpplus":
			if (!this.PreDhcp()) return null;
			break;
		case "r_pppoe":
			if (!this.PreRPppoe()) return null;
		case "pppoe":
			if (!this.PrePppoe()) return null;
			mtu_obj = "ppp4_mtu";
			mac_obj = "ppp4_macaddr";
			break;
		case "r_pptp":
			if (!this.PrePptp("russia")) return null;
			mtu_obj = "ppp4_mtu";
			mac_obj = "ppp4_macaddr";
			break;
		case "pptp":
			if (!this.PrePptp()) return null;
			mtu_obj = "ppp4_mtu";
			mac_obj = "ppp4_macaddr";
			break;
		case "l2tp":
			if (!this.PreL2tp()) return null;
			mtu_obj = "ppp4_mtu";
			mac_obj = "ppp4_macaddr";
			break;		
		case "dslite":
			if (!this.PreDSLite()) return null;
			break;
		}
		if (!TEMP_IsDigit(OBJ(mtu_obj).value))
		{
			BODY.ShowAlert("<?echo i18n("The MTU value is invalid.");?>");
			return null;
		}

		/* If mac is changed, restart PHYINF.WAN-1, else restart WAN. */
		if (COMM_Equal(OBJ(mac_obj).getAttribute("modified"), true))
		{
			var p = PXML.FindModule("PHYINF.WAN-1");
			var b = GPBT(p, "phyinf", "uid", XG(p+"/inf/phyinf"), false);
			XS(b+"/macaddr", OBJ(mac_obj).value);
			PXML.ActiveModule("PHYINF.WAN-1");
			PXML.DelayActiveModule("PHYINF.WAN-1", "3");
			mac_clone_changed = 1;
		}
		else
		{
			PXML.CheckModule("WAN", null, "ignore", null);
			PXML.CheckModule("INET.LAN-4", "ignore", "ignore", null);
		}
		PXML.CheckModule("INET.INF", null, null, "ignore");

		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////
	bootuptime: <?

		$bt=query("/runtime/device/bootuptime");
		if ($bt=="")	$bt=30;
		else			$bt=$bt+10;
		echo $bt;

	?>,
	defaultCFGXML: null,
	device_host: null,
	wan1:	{infp: null, inetp:null, phyinfp:null},
	wan2:	{infp: null, inetp:null},
	wan3:	{infp: null, inetp:null},
	wan4:	{infp: null, inetp:null},
	br1:	{infp: null, inetp:null},
	lan4:	{infp: null, inetp:null},
	/* for bridge/router mode changing */
	bridge_addrtype: null,
	bridge_ipaddr: null,
	InitIpv4Value: function()
	{
		/* static ip */
		OBJ("st_ipaddr").value	= XG(this.wan1.inetp+"/ipv4/ipaddr");
		OBJ("st_mask").value	= COMM_IPv4INT2MASK(XG(this.wan1.inetp+"/ipv4/mask"));
		OBJ("st_gw").value		= XG(this.wan1.inetp+"/ipv4/gateway");
		/* dns server */
		var cnt = XG(this.wan1.inetp+"/ipv4/dns/count");
		OBJ("ipv4_dns1").value	= cnt > 0 ? XG(this.wan1.inetp+"/ipv4/dns/entry:1") : "";
		OBJ("ipv4_dns2").value	= cnt > 1 ? XG(this.wan1.inetp+"/ipv4/dns/entry:2") : "";
		OBJ("ipv4_mtu").value			= XG(this.wan1.inetp+"/ipv4/mtu");
		/* dhcp & dhcp plus */
		OBJ("dhcp_host_name").value	= XG(this.device_host+"/device/hostname");
		OBJ("dhcpplus_username").vlaue	= XG(this.ipv4+"/dhcpplus/username");
		OBJ("dhcpplus_password").vlaue	= XG(this.ipv4+"/dhcpplus/password");
		/* mac addr */
		OBJ("ipv4_macaddr").value = XG(this.wan1.phyinfp+"/macaddr");

		/* ds-lite */
		var rwan1 = PXML.FindModule("RUNTIME.INF.WAN-1");
		var remote = XG(this.wan1.inetp+"/ipv4/ipv4in6/remote");
		OBJ("aftr_ipaddr6").value = remote? remote:XG(rwan1+"/runtime/inf/inet/ipv4/ipv4in6/remote");
		OBJ("aftr_ipaddr6").disabled = remote? false: true;
		OBJ("b4_ipaddr_1").innerHTML = "192.0.0.";
		var b4_ipaddr = XG(this.wan1.inetp+"/ipv4/ipaddr");
		index = b4_ipaddr.lastIndexOf(".");
		OBJ("b4_ipaddr_2").value = b4_ipaddr.substring(index+1);

		var rwan3 = PXML.FindModule("RUNTIME.INF.WAN-3");
		var rwan4 = PXML.FindModule("RUNTIME.INF.WAN-4");
		var rwan3type = XG(this.wan3.inetp+"/addrtype");
		if(rwan3type =="ppp6")
		{
			var child = XG(this.wan3.inetp+"/child/uid");
			//if(child!="")
				OBJ("dslite_wan_ipaddr6").innerHTML = XG(rwan4+"/runtime/inf/inet/ipv6/ipaddr");
			//else
			//	OBJ("dslite_wan_ipaddr6").innerHTML = XG(rwan3+"/runtime/inf/inet/ppp6/local");
			
			OBJ("dslite_gw_ipaddr6").innerHTML = XG(rwan3+"/runtime/inf/inet/ppp6/peer");
		}
		else 
		{
			OBJ("dslite_wan_ipaddr6").innerHTML = XG(rwan4+"/runtime/inf/inet/ipv6/ipaddr");
			OBJ("dslite_gw_ipaddr6").innerHTML = XG(rwan4+"/runtime/inf/inet/ipv6/gateway");
		}
		return true;
	},
	InitPpp4Value: function()
	{

		/* set/clear to default */
		/* pppoe */
		OBJ("pppoe_dynamic").checked		= true;
		OBJ("pppoe_ipaddr").value			= "";
		OBJ("pppoe_username").value			= "";
		OBJ("pppoe_mppe").checked			= false;
		OBJ("pppoe_password").value			= "";
		OBJ("confirm_pppoe_password").value = "";
		OBJ("pppoe_service_name").value		= "";
		OBJ("pppoe_ondemand").checked		= true;
		OBJ("pppoe_max_idle_time").value	= "";
		OBJ("dns_isp").checked				= true;
		OBJ("pppoe_dns1").value				= "";
		OBJ("pppoe_dns2").value				= "";
		OBJ("en_fakeos").checked			= false;
		COMM_SetSelectValue(OBJ("pppoe_schedule"), "");
		/* pptp */
		OBJ("pptp_dynamic").checked			= true;
		OBJ("pptp_ipaddr").value			= "";
		OBJ("pptp_mask").value				= "";
		OBJ("pptp_gw").value				= "";
		OBJ("pptp_username").value			= "";
		OBJ("pptp_mppe").checked			= false;
		OBJ("pptp_password").value			= "";
		OBJ("confirm_pptp_password").value	= "";
		OBJ("pptp_ondemand").checked		= true;
		OBJ("pptp_max_idle_time").value		= "";
		OBJ("pptp_dns1").value				= "";
		OBJ("pptp_dns2").value				= "";
		COMM_SetSelectValue(OBJ("pptp_schedule"), "");
		/* l2tp */
		OBJ("l2tp_dynamic").checked			= true;
		OBJ("l2tp_ipaddr").value			= "";
		OBJ("l2tp_mask").value				= "";
		OBJ("l2tp_gw").value				= "";
		OBJ("l2tp_server").value			= "";
		OBJ("l2tp_username").value			= "";
		OBJ("l2tp_password").value			= "";
		OBJ("confirm_l2tp_password").value	= "";
		OBJ("l2tp_ondemand").checked		= true;
		OBJ("l2tp_max_idle_time").value		= "";
		OBJ("l2tp_dns1").value				= "";
		OBJ("l2tp_dns2").value				= "";
		COMM_SetSelectValue(OBJ("l2tp_schedule"), "");
		/* common */
		OBJ("ppp4_mtu").value = XG(this.wan1.inetp+"/ppp4/mtu");
		OBJ("ppp4_macaddr").value = XG(this.wan1.phyinfp+"/macaddr");

		/* init */
		/* rpppoe */
		if (XG(this.wan2.inetp+"/ipv4/static")==="1")	OBJ("rpppoe_static").checked = true;
		else											OBJ("rpppoe_dynamic").checked = true;
		var cnt = XG(this.wan2.inetp+"/ipv4/dns/count");
		OBJ("rpppoe_ipaddr").value = XG(this.wan2.inetp+"/ipv4/ipaddr");
		OBJ("rpppoe_mask").value = COMM_IPv4INT2MASK(XG(this.wan2.inetp+"/ipv4/mask"));
		OBJ("rpppoe_gw").value = XG(this.wan2.inetp+"/ipv4/gateway");
		OBJ("rpppoe_dns1").value = (cnt>0)? XG(this.wan2.inetp+"/ipv4/dns/entry:1") : "";
		OBJ("rpppoe_dns2").value = (cnt>1)? XG(this.wan2.inetp+"/ipv4/dns/entry:2") : "";

		var over = XG(this.wan1.inetp+"/ppp4/over");
		switch (over)
		{
		case "eth":
			if (XG(this.wan1.inetp+"/ppp4/static")==="1")	OBJ("pppoe_static").checked = true;
			else											OBJ("pppoe_dynamic").checked = true;
			OBJ("pppoe_ipaddr").value		= XG(this.wan1.inetp+"/ppp4/ipaddr");
			OBJ("pppoe_username").value		= XG(this.wan1.inetp+"/ppp4/username");
			OBJ("pppoe_mppe").checked		= XG(this.wan1.inetp+"/ppp4/mppe/enable")==="1" ? true : false;
			OBJ("pppoe_password").value		= XG(this.wan1.inetp+"/ppp4/password");
			OBJ("confirm_pppoe_password").value	= XG(this.wan1.inetp+"/ppp4/password");
			OBJ("pppoe_service_name").value	= XG(this.wan1.inetp+"/ppp4/pppoe/servicename");
			var dialup = XG(this.wan1.inetp+"/ppp4/dialup/mode");
			if		(dialup === "auto")		OBJ("pppoe_alwayson").checked = true;
			else if	(dialup === "manual")	OBJ("pppoe_manual").checked = true;
			else							OBJ("pppoe_ondemand").checked = true;
			OBJ("pppoe_max_idle_time").value = XG(this.wan1.inetp+"/ppp4/dialup/idletimeout");
			if (XG(this.wan1.inetp+"/ppp4/dns/count") > 0)	OBJ("dns_manual").checked = true;
			else OBJ("dns_isp").checked = true;
			OBJ("pppoe_dns1").value = XG(this.wan1.inetp+"/ppp4/dns/entry:1");
			if (XG(this.wan1.inetp+"/ppp4/dns/count")>=2) OBJ("pppoe_dns2").value = XG(this.wan1.inetp+"/ppp4/dns/entry:2");
			COMM_SetSelectValue(OBJ("pppoe_schedule"), XG(this.wan1.infp+"/schedule"));
			OBJ("en_fakeos").checked = XG(this.wan1.inetp+"/ppp4/pppoe/fakeos/enable")==="1" ? true : false;
			break;
		case "pptp":
			if (XG(this.wan2.inetp+"/ipv4/static")==="1")	OBJ("pptp_static").checked = true;
			else											OBJ("pptp_dynamic").checked = true;
			OBJ("pptp_ipaddr").value	= XG(this.wan2.inetp+"/ipv4/ipaddr");
			OBJ("pptp_mask").value		= COMM_IPv4INT2MASK(XG(this.wan2.inetp+"/ipv4/mask"));
			OBJ("pptp_gw").value		= XG(this.wan2.inetp+"/ipv4/gateway");
			OBJ("pptp_server").value	= XG(this.wan1.inetp+"/ppp4/pptp/server");
			OBJ("pptp_username").value	= XG(this.wan1.inetp+"/ppp4/username");
			OBJ("pptp_mppe").checked	= XG(this.wan1.inetp+"/ppp4/mppe/enable")==="1" ? true : false;
			OBJ("pptp_password").value	= XG(this.wan1.inetp+"/ppp4/password");
			OBJ("confirm_pptp_password").value	= XG(this.wan1.inetp+"/ppp4/password");
			var dialup = XG(this.wan1.inetp+"/ppp4/dialup/mode");
			if		(dialup === "auto")		OBJ("pptp_alwayson").checked= true;
			else if	(dialup === "manual")	OBJ("pptp_manual").checked = true;
			else							OBJ("pptp_ondemand").checked = true;
			OBJ("pptp_max_idle_time").value	= XG(this.wan1.inetp+"/ppp4/dialup/idletimeout");
			var dnscount = XG(this.wan2.inetp+"/ipv4/dns/count");
			if (dnscount > 0)	OBJ("pptp_dns1").value	= XG(this.wan2.inetp+"/ipv4/dns/entry:1");
			if (dnscount > 1)	OBJ("pptp_dns2").value	= XG(this.wan2.inetp+"/ipv4/dns/entry:2");
			COMM_SetSelectValue(OBJ("pptp_schedule"), XG(this.wan1.infp+"/schedule"));
			break;
		case "l2tp":
			if (XG(this.wan2.inetp+"/ipv4/static")==="1")	OBJ("l2tp_static").checked = true;
			else											OBJ("l2tp_dynamic").checked = true;
			OBJ("l2tp_ipaddr").value	= XG(this.wan2.inetp+"/ipv4/ipaddr");
			OBJ("l2tp_mask").value		= COMM_IPv4INT2MASK(XG(this.wan2.inetp+"/ipv4/mask"));
			OBJ("l2tp_gw").value		= XG(this.wan2.inetp+"/ipv4/gateway");
			OBJ("l2tp_server").value	= XG(this.wan1.inetp+"/ppp4/l2tp/server");
			OBJ("l2tp_username").value	= XG(this.wan1.inetp+"/ppp4/username");
			OBJ("l2tp_password").value	= XG(this.wan1.inetp+"/ppp4/password");
			OBJ("confirm_l2tp_password").value	= XG(this.wan1.inetp+"/ppp4/password");
			var dialup = XG(this.wan1.inetp+"/ppp4/dialup/mode");
			if		(dialup === "auto")		OBJ("l2tp_alwayson").checked= true;
			else if	(dialup === "manual")	OBJ("l2tp_manual").checked = true;
			else							OBJ("l2tp_ondemand").checked = true;
			OBJ("l2tp_max_idle_time").value	= XG(this.wan1.inetp+"/ppp4/dialup/idletimeout");
			var dnscount = XG(this.wan2.inetp+"/ipv4/dns/count");
			if (dnscount > 0)	OBJ("l2tp_dns1").value	= XG(this.wan2.inetp+"/ipv4/dns/entry:1");
			if (dnscount > 1)	OBJ("l2tp_dns2").value	= XG(this.wan2.inetp+"/ipv4/dns/entry:2");
			COMM_SetSelectValue(OBJ("l2tp_schedule"), XG(this.wan1.infp+"/schedule"));
			break;
		}
		return true;
	},
	/* for Pre-Submit */
	PreStatic: function()
	{
		var cnt;
		XS(this.wan1.inetp+"/addrtype",		"ipv4");
		XS(this.wan1.inetp+"/ipv4/static",	"1");
		XS(this.wan1.inetp+"/ipv4/ipaddr",	OBJ("st_ipaddr").value);
		XS(this.wan1.inetp+"/ipv4/mask",	COMM_IPv4MASK2INT(OBJ("st_mask").value));
		XS(this.wan1.inetp+"/ipv4/gateway",	OBJ("st_gw").value);
		XS(this.wan1.inetp+"/ipv4/mtu",		OBJ("ipv4_mtu").value);

		var st_ip = OBJ("st_ipaddr").value;
		if(!check_ip_validity(st_ip))
		{
			BODY.ShowAlert("Invalid IP address");
			OBJ("st_ipaddr").focus();
			return null;
		}

		cnt = 0;
		if(OBJ("ipv4_dns1").value === "")
		{
			BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address .");?>");
			return null;
		}
		XS(this.wan1.inetp+"/ipv4/dns/entry", OBJ("ipv4_dns1").value);
		cnt+=1;
		if (OBJ("ipv4_dns2").value !== "")
		{
			XS(this.wan1.inetp+"/ipv4/dns/entry:2", OBJ("ipv4_dns2").value);
			cnt+=1;
		}
		XS(this.wan1.inetp+"/ipv4/dns/count", cnt);
		return true;
	},
	PreDhcp: function()
	{
		var cnt;
		XS(this.wan1.inetp+"/addrtype",			"ipv4");
		XS(this.wan1.inetp+"/ipv4/static",		"0");
		XS(this.device_host+"/device/hostname",	OBJ("dhcp_host_name").value);
		
		cnt = 0;
		if(OBJ("ipv4_dns1").value !== "")
		{
			XS(this.wan1.inetp+"/ipv4/dns/entry", OBJ("ipv4_dns1").value);
			cnt+=1;
		}
		if (OBJ("ipv4_dns2").value !== "")
		{
			XS(this.wan1.inetp+"/ipv4/dns/entry:2", OBJ("ipv4_dns2").value);
			cnt+=1;
		}
		XS(this.wan1.inetp+"/ipv4/dns/count", cnt);
		XS(this.wan1.inetp+"/ipv4/mtu", OBJ("ipv4_mtu").value);
		if (OBJ("wan_ip_mode").value === "dhcpplus")
		{
			XS(this.wan1.inetp+"/ipv4/dhcpplus/enable", "1");
			XS(this.wan1.inetp+"/ipv4/dhcpplus/username", OBJ("dhcpplus_username").value);
			XS(this.wan1.inetp+"/ipv4/dhcpplus/password", OBJ("dhcpplus_password").value);
		}
		else
		{
			XS(this.wan1.inetp+"/ipv4/dhcpplus/enable", "0");
		}
		return true;
	},
	PrePppoe: function()
	{
		var temp_value="";
		var cnt;
		if (OBJ("pppoe_password").value !== OBJ("confirm_pppoe_password").value)
		{
			BODY.ShowAlert("<?echo i18n("The password is mismatched.");?>");
			return null;
		}
		var wan1addrtype = XG(this.wan1.inetp+"/addrtype");
		var over = XG(this.wan1.inetp+"/ppp4/over");
		//XS(this.wan1.inetp+"/addrtype", "ppp4");
		if(wan1addrtype=="ppp10" && over=="eth")
		{
			XS(this.wan1.inetp+"/addrtype", "ppp10");
			XS(this.wan1.inetp+"/ppp6/username", OBJ("pppoe_username").value);
			XS(this.wan1.inetp+"/ppp6/password", OBJ("pppoe_password").value);
			XS(this.wan1.inetp+"/ppp6/pppoe/servicename", OBJ("pppoe_service_name").value);
			XS(this.wan1.inetp+"/ppp6/mtu", OBJ("ppp4_mtu").value);
			XS(this.wan1.inetp+"/ppp6/over", "eth");
		}
		else
			XS(this.wan1.inetp+"/addrtype", "ppp4");

		XS(this.wan1.inetp+"/ppp4/over", "eth");
		XS(this.wan1.inetp+"/ppp4/username", OBJ("pppoe_username").value);
		var mppe = 0;
		if(OBJ("pppoe_mppe").checked && OBJ("wan_ip_mode").value==="r_pppoe")mppe = "1";
		XS(this.wan1.inetp+"/ppp4/mppe/enable", mppe);
		XS(this.wan1.inetp+"/ppp4/password", OBJ("pppoe_password").value);
		XS(this.wan1.inetp+"/ppp4/pppoe/servicename", OBJ("pppoe_service_name").value);
		if (OBJ("pppoe_dynamic").checked)
		{
			XS(this.wan1.inetp+"/ppp4/static", "0");
			XD(this.wan1.inetp+"/ppp4/ipaddr");
		}
		else
		{
			XS(this.wan1.inetp+"/ppp4/static", "1");
			XS(this.wan1.inetp+"/ppp4/ipaddr", OBJ("pppoe_ipaddr").value);
			if (OBJ("dns_manual").checked && OBJ("pppoe_dns1").value === "")
			{
				BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address .");?>");
				return null;
			}
		}
		/* star fakeos */
		XS(this.wan1.inetp+"/ppp4/pppoe/fakeos/enable", OBJ("en_fakeos").checked ? "1" : "0");

		/* dns */
		cnt = 0;
		if (OBJ("dns_isp").checked)
		{
			XS(this.wan1.inetp+"/ppp4/dns/entry:1","");
			XS(this.wan1.inetp+"/ppp4/dns/entry:2","");
		}
		else
		{
			if (OBJ("pppoe_dns1").value !== "")
			{
				XS(this.wan1.inetp+"/ppp4/dns/entry", OBJ("pppoe_dns1").value);
				cnt+=1;
			}
			if (OBJ("pppoe_dns2").value !== "")
			{
				XS(this.wan1.inetp+"/ppp4/dns/entry:2", OBJ("pppoe_dns2").value);
				cnt+=1;
			}
		}
		XS(this.wan1.inetp+"/ppp4/dns/count", cnt);
		XS(this.wan1.inetp+"/ppp4/mtu", OBJ("ppp4_mtu").value);
		if (OBJ("pppoe_max_idle_time").value==="") OBJ("pppoe_max_idle_time").value = 0;
		if (!TEMP_IsDigit(OBJ("pppoe_max_idle_time").value))
		{
			BODY.ShowAlert("<?echo i18n("Invalid value for idle timeout.");?>");
			return null;
		}
		XS(this.wan1.inetp+"/ppp4/dialup/idletimeout", OBJ("pppoe_max_idle_time").value);
		var dialup = "ondemand";
		if(OBJ("pppoe_alwayson").checked)
		{	
			dialup = "auto";
			XS(this.wan1.infp+"/schedule", OBJ("pppoe_schedule").value);
		}
		else if	(OBJ("pppoe_manual").checked)	dialup = "manual";
		XS(this.wan1.inetp+"/ppp4/dialup/mode", dialup);

		return true;
	},
	PreRPppoe: function()
	{
		var rpppoe_static = OBJ("rpppoe_static").checked;
		var cnt = 0;
		XS(this.wan2.infp+"/active", "1");
		XS(this.wan2.infp+"/nat","NAT-1");
		XS(this.wan2.inetp+"/ipv4/static",	(rpppoe_static)? "1":"0");
		XS(this.wan2.inetp+"/ipv4/ipaddr",	(rpppoe_static)? OBJ("rpppoe_ipaddr").value:"");
		XS(this.wan2.inetp+"/ipv4/mask",	(rpppoe_static)? COMM_IPv4MASK2INT(OBJ("rpppoe_mask").value):"");
		XS(this.wan2.inetp+"/ipv4/gateway",	(rpppoe_static)? OBJ("rpppoe_gw").value:"");
		if (rpppoe_static)
		{
			if (OBJ("rpppoe_dns1").value!=="")
			{
				XS(this.wan2.inetp+"/ipv4/dns/entry", OBJ("rpppoe_dns1").value);
				cnt+=1;
			}
			else
			{
				BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address .");?>");
				return null;
			}
			if (OBJ("rpppoe_dns2").value!=="")
			{
				XS(this.wan2.inetp+"/ipv4/dns/entry:2", OBJ("rpppoe_dns2").value);
				cnt+=1;
			}
		}
		XS(this.wan2.inetp+"/ipv4/dns/count", cnt);

		return true;
	},
	PrePptp: function(type)
	{
		if (OBJ("pptp_password").value !== OBJ("confirm_pptp_password").value)
		{
			BODY.ShowAlert("<?echo i18n("The password is mismatched.");?>");
			return null;
		}
		/* Note : Russia mode need two WANs to be active simultaneously. So we remove the lowerlayer connection. 
		For normal pptp, the lowerlayer/upperlayer connection still remains. */
		
		if(type == "russia")	//normal pptp
		{
			/* defaultroute value will become metric value.
			As for Russia, physical WAN (wan2) priority should be lower than 
			ppp WAN (wan1) */
			XS(this.wan1.infp+"/defaultroute", "100");
			XS(this.wan2.infp+"/defaultroute", "200");
		}
		else
		{
			XS(this.wan1.infp+"/defaultroute", "100");
			XS(this.wan2.infp+"/defaultroute", "");

			XS(this.wan1.infp+"/lowerlayer", "WAN-2");
			XS(this.wan2.infp+"/upperlayer", "WAN-1");
		}
		
		XS(this.wan2.infp+"/active", "1");
		XS(this.wan2.infp+"/nat", (OBJ("wan_ip_mode").value==="r_pptp") ? "NAT-1" : "");
		XS(this.wan1.inetp+"/addrtype", "ppp4");
		XS(this.wan1.inetp+"/ppp4/over", "pptp");
		XS(this.wan1.inetp+"/ppp4/static", "0");
		XS(this.wan1.inetp+"/ppp4/username", OBJ("pptp_username").value);
		XS(this.wan1.inetp+"/ppp4/password", OBJ("pptp_password").value);
		XS(this.wan1.inetp+"/ppp4/pptp/server", OBJ("pptp_server").value);
		var cnt = 0;
		if (OBJ("pptp_static").checked)
		{
			XS(this.wan2.inetp+"/ipv4/static",	"1");
			XS(this.wan2.inetp+"/ipv4/ipaddr",	OBJ("pptp_ipaddr").value);
			XS(this.wan2.inetp+"/ipv4/mask",	COMM_IPv4MASK2INT(OBJ("pptp_mask").value));
			XS(this.wan2.inetp+"/ipv4/gateway",	OBJ("pptp_gw").value);
			if (OBJ("pptp_dns1").value === "")
			{
				BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address .");?>");
				return null;
			}
			else
			{
				XS(this.wan2.inetp+"/ipv4/dns/entry:1", OBJ("pptp_dns1").value);
				cnt++;
			}
			if (OBJ("pptp_dns2").value !== "") { XS(this.wan2.inetp+"/ipv4/dns/entry:2", OBJ("pptp_dns2").value); cnt++; }
		}
		else
		{
			XS(this.wan2.inetp+"/ipv4/static", "0");
		}
		XS(this.wan1.inetp+"/ppp4/dns/count", "0");
		XS(this.wan2.inetp+"/ipv4/dns/count", cnt);
		var mppe = "0";
		if(OBJ("pptp_mppe").checked && OBJ("wan_ip_mode").value=="r_pptp")	mppe ="1";
		XS(this.wan1.inetp+"/ppp4/mppe/enable", mppe);
		XS(this.wan1.inetp+"/ppp4/mtu", OBJ("ppp4_mtu").value);
		if (OBJ("pptp_max_idle_time").value==="") OBJ("pptp_max_idle_time").value = 0;
		if (!TEMP_IsDigit(OBJ("pptp_max_idle_time").value))
		{
			BODY.ShowAlert("<?echo i18n("Invalid value for idle timeout.");?>");
			return null;
		}
		XS(this.wan1.inetp+"/ppp4/dialup/idletimeout", OBJ("pptp_max_idle_time").value);
		var dialup = "ondemand";
		if(OBJ("pptp_alwayson").checked)
		{
			dialup = "auto";
			XS(this.wan1.infp+"/schedule", OBJ("pptp_schedule").value);
		}
		else if	(OBJ("pptp_manual").checked)	dialup = "manual";
		XS(this.wan1.inetp+"/ppp4/dialup/mode", dialup);
		return true;
	},
	PreL2tp: function()
	{
		var cnt;
		if (OBJ("l2tp_password").value !== OBJ("confirm_l2tp_password").value)
		{
			BODY.ShowAlert("<?echo i18n("The password is mismatched.");?>");
			return null;
		}
		XS(this.wan1.infp+"/lowerlayer", "WAN-2");
		XS(this.wan2.infp+"/upperlayer", "WAN-1");
		XS(this.wan2.infp+"/active", "1");
		XS(this.wan1.inetp+"/addrtype", "ppp4");
		XS(this.wan1.inetp+"/ppp4/over", "l2tp");
		XS(this.wan1.inetp+"/ppp4/static", "0");
		XS(this.wan1.inetp+"/ppp4/username", OBJ("l2tp_username").value);
		XS(this.wan1.inetp+"/ppp4/password", OBJ("l2tp_password").value);
		XS(this.wan1.inetp+"/ppp4/l2tp/server", OBJ("l2tp_server").value);
		cnt = 0;
		if (OBJ("l2tp_static").checked)
		{
			XS(this.wan2.inetp+"/ipv4/static", "1");
			XS(this.wan2.inetp+"/ipv4/ipaddr",	OBJ("l2tp_ipaddr").value);
			XS(this.wan2.inetp+"/ipv4/mask",	COMM_IPv4MASK2INT(OBJ("l2tp_mask").value));
			XS(this.wan2.inetp+"/ipv4/gateway",	OBJ("l2tp_gw").value);
			if (OBJ("l2tp_dns1").value != "")	{ XS(this.wan2.inetp+"/ipv4/dns/entry:1", OBJ("l2tp_dns1").value); cnt++; }
			else								{ BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address .");?>"); return null; }
			if (OBJ("l2tp_dns2").value != "")	{ XS(this.wan2.inetp+"/ipv4/dns/entry:2", OBJ("l2tp_dns2").value); cnt++; }
		}
		else
		{
			XS(this.wan2.inetp+"/ipv4/static", "0");
		}
		if (OBJ("l2tp_max_idle_time").value === "") OBJ("l2tp_max_idle_time").value = 0;
		if (!TEMP_IsDigit(OBJ("l2tp_max_idle_time").value))
		{
			BODY.ShowAlert("<?echo i18n("Invalid value for idle timeout.");?>");
			return null;
		}
		XS(this.wan1.inetp+"/ppp4/dns/count", "0");
		XS(this.wan2.inetp+"/ipv4/dns/count", cnt);
		XS(this.wan1.inetp+"/ppp4/mtu", OBJ("ppp4_mtu").value);
		XS(this.wan1.inetp+"/ppp4/dialup/idletimeout", OBJ("l2tp_max_idle_time").value);
		var dialup = "ondemand";
		if(OBJ("l2tp_alwayson").checked)
		{
			dialup = "auto";
			XS(this.wan1.infp+"/schedule", OBJ("l2tp_schedule").value);
		}
		else if	(OBJ("l2tp_manual").checked)	dialup = "manual";
		XS(this.wan1.inetp+"/ppp4/dialup/mode", dialup);
		return true;
	},
	PreDSLite: function()
	{
		var addrtype_wan1 = XG(this.wan1.inetp+"/addrtype");
		var addrtype_wan3 = XG(this.wan3.inetp+"/addrtype");

		if(addrtype_wan1 == "ppp10" || addrtype_wan3 == "ppp6")
		{
			//ppp6 or ppp10
			if(addrtype_wan3 == "ppp6")
			{
				XS(this.wan1.infp+"/infprevious", "WAN-4");
				XS(this.wan4.infp+"/infnext", "WAN-1");
				XS(this.lan4.infp+"/dns6", "DNS6-1");
				XS(this.lan4.infp+"/dnsrelay", "1");
			}	
			else
			{
				//ppp10
				//never happen
			}
		}
		else
		{
			var wanmode6 = XG(this.wan4.inetp+"/ipv6/mode");
			var wanactive6 = XG(this.wan4.infp+"/active");
			if(wanmode6 !="" && wanactive6 !="0") 
			{
				if(wanmode6=="6TO4" || wanmode6=="6RD" || wanmode6=="6IN4")
				{
					alert("Please choose one IPv6 connection type besides Link-Local Only, 6to4, 6rd, and IPv6 in IPv4 Tunnel !"); 
					return false;
				}
				XS(this.wan1.infp+"/infprevious", "WAN-4");
				XS(this.wan4.infp+"/infnext", "WAN-1");

				/* enable dns proxy */
				XS(this.lan4.infp+"/dns6", "DNS6-1");
				XS(this.lan4.infp+"/dnsrelay", "1");
			}
			else
			{
				//LL
				alert("Please choose one IPv6 connection type besides Link-Local Only, 6to4, 6rd, and IPv6 in IPv4 Tunnel !"); 
				return false; 
			}
		}
		if(OBJ("b4_ipaddr_2").value!="")
		{
			var b4addr = "192.0.0."+OBJ("b4_ipaddr_2").value;
			XS(this.wan1.inetp+"/ipv4/ipaddr",	b4addr);
		}
		else
		{
			XS(this.wan1.inetp+"/ipv4/ipaddr",	"");
		}	
		XS(this.wan1.infp+"/nat",			"");
		XS(this.wan1.inetp+"/addrtype",			"ipv4");
		XS(this.wan1.inetp+"/ipv4/static",		"0");
		XS(this.wan1.inetp+"/ipv4/ipv4in6/mode",	"dslite");

		if (OBJ("dslite_static").checked)
		{
			XS(this.wan1.inetp+"/ipv4/ipv4in6/remote", OBJ("aftr_ipaddr6").value);
			XS(this.wan4.infp+"/dhcpc6",	"0");
		}
		else
		{
			XS(this.wan1.inetp+"/ipv4/ipv4in6/remote", "");

			/* check ipv6 wan mode to decide if we need to do dhcpv6 info req */
			if(addrtype_wan3 != "ppp6")
			{
				wanmode6 = XG(this.wan4.inetp+"/ipv6/mode");
				if(wanmode6=="STATIC") XS(this.wan4.infp+"/dhcpc6",	"1");
			}
			else
			{
				//ppp6 && no pd && no dns
				//because we don't have static ppp6, so we don't do that?
				/*
				dhcp6_pdns = XG(this.wan3.inetp+"/ppp6/dns/entry:1");
				child = XG(this.wan4.infp+"/child");
				if(dhcp6_pdns=="" && child=="") 
					XS(this.wan3.infp+"/dhcpc6",	"1");
				*/
			}
			
		}
		
		return true;
	},
	OnChangeWanIpMode: function()
	{
		OBJ("ipv4_setting").style.display		= "none";
		OBJ("ppp4_setting").style.display		= "none";

		OBJ("box_wan_static").style.display		= "none";
		OBJ("box_wan_dhcp").style.display		= "none";
		OBJ("box_wan_dhcpplus").style.display	= "none";
		OBJ("box_wan_static_body").style.display= "none";
		OBJ("box_wan_dhcp_body").style.display	= "none";
		OBJ("box_wan_ipv4_common_body").style.display = "none";

		OBJ("box_wan_pppoe").style.display		= "none";
		OBJ("box_wan_pptp").style.display		= "none";
		OBJ("box_wan_l2tp").style.display		= "none";
		OBJ("box_wan_ru_pppoe").style.display	= "none";
		OBJ("box_wan_ru_pptp").style.display	= "none";
		OBJ("show_pppoe_mppe").style.display	= "none";
		OBJ("show_pptp_mppe").style.display		= "none";
		OBJ("box_wan_pppoe_body").style.display	= "none";
		OBJ("box_wan_pptp_body").style.display	= "none";
		OBJ("box_wan_l2tp_body").style.display	= "none";
		OBJ("box_wan_ppp4_comm_body").style.display = "none";
		OBJ("R_PPPoE").style.display			= "none";
		OBJ("box_wan_dslite").style.display	= "none";
		OBJ("box_wan_dslite_body").style.display	= "none";

		//for dslite
		var wanmode = OBJ("wan_ip_mode").value;
		RemoveItemFromSelect(OBJ("wan_ip_mode"),"dslite");
		var wanmode6 = XG(this.wan4.inetp+"/ipv6/mode");
		var wanactive6 = XG(this.wan4.infp+"/active");
		var addrtype_wan1 = XG(this.wan1.inetp+"/addrtype");
		var addrtype_wan3 = XG(this.wan3.inetp+"/addrtype");
		/*
		if(wanactive6 == "1")
		{
			if(wanmode6 != "6TO4" && wanmode6 != "6RD" && wanmode6 != "6IN4" && addrtype_wan1 != "ppp10" && addrtype_wan3 != "ppp6")
				AddItemFromSelect(OBJ("wan_ip_mode"),"DS-Lite","dslite");
		}
		*/
		AddItemFromSelect(OBJ("wan_ip_mode"),"DS-Lite","dslite");
		OBJ("wan_ip_mode").value = wanmode;

		var over = XG(this.wan1.inetp+"/ppp4/over");
		switch(OBJ("wan_ip_mode").value)
		{
		case "static":
			OBJ("ipv4_setting").style.display				= "block";
			OBJ("box_wan_static").style.display				= "block"; 
			OBJ("box_wan_static_body").style.display		= "block";
			OBJ("box_wan_ipv4_common_body").style.display	= "block";
			break;
		case "dhcpplus":
		case "dhcp":
			OBJ("ipv4_setting").style.display				= "block";
			OBJ("box_wan_dhcp").style.display				= (OBJ("wan_ip_mode").value === "dhcpplus") ? "none"  : "block";
			OBJ("box_wan_dhcpplus").style.display			= (OBJ("wan_ip_mode").value === "dhcpplus") ? "block" : "none";
			OBJ("box_wan_dhcp_body").style.display			= "block";
			OBJ("dhcpplus").style.display					= (OBJ("wan_ip_mode").value === "dhcpplus") ? "block" : "none";
			OBJ("box_wan_ipv4_common_body").style.display	= "block";
			break;
		case "r_pppoe":
			OBJ("show_pppoe_mppe").style.display			= "inline";
			OBJ("R_PPPoE").style.display					= "block";
			this.OnClickRPppoeAddrType();
		case "pppoe":
			OBJ("ppp4_setting").style.display				= "block";
			OBJ("box_wan_pppoe_body").style.display			= "block";
			OBJ("box_wan_pppoe").style.display				= "block";
			OBJ("box_wan_ppp4_comm_body").style.display		= "block";
			if (XG(this.wan1.inetp+"/ppp4/mtu")=="")		OBJ("ppp4_mtu").value = "1492";
			this.OnClickPppoeAddrType();
			this.OnClickPppoeReconnect();
			this.OnClickDnsMode();
			break;
		case "r_pptp":
			OBJ("ppp4_setting").style.display				= "block";
			OBJ("box_wan_ru_pptp").style.display			= "block";
			OBJ("box_wan_pptp_body").style.display			= "block";
			OBJ("box_wan_ppp4_comm_body").style.display 	= "block";
			OBJ("show_pptp_mppe").style.display				= "inline";
			if (XG(this.wan1.inetp+"/ppp4/mtu")=="" || 
			   (over!="r_pptp" && parseInt(XG(this.wan1.inetp+"/ppp4/mtu"))>1400))		OBJ("ppp4_mtu").value = "1400";
			this.OnClickPptpAddrType();
			this.OnClickPptpReconnect();
			break;
		case "pptp":
			OBJ("ppp4_setting").style.display				= "block";
			OBJ("box_wan_pptp").style.display				= "block";
			OBJ("box_wan_pptp_body").style.display			= "block";
			OBJ("box_wan_ppp4_comm_body").style.display 	= "block";
			if (XG(this.wan1.inetp+"/ppp4/mtu")=="" || 
			   (over!="pptp" && parseInt(XG(this.wan1.inetp+"/ppp4/mtu"))>1400))		OBJ("ppp4_mtu").value = "1400";
			this.OnClickPptpAddrType();
			this.OnClickPptpReconnect();
			break;
		case "l2tp":		
			OBJ("ppp4_setting").style.display				= "block";
			OBJ("box_wan_l2tp").style.display				= "block";
			OBJ("box_wan_l2tp_body").style.display			= "block";
			OBJ("box_wan_ppp4_comm_body").style.display		= "block";
			if (XG(this.wan1.inetp+"/ppp4/mtu")=="" || 
			   (over!="l2tp" && parseInt(XG(this.wan1.inetp+"/ppp4/mtu"))>1400))		OBJ("ppp4_mtu").value = "1400";
			this.OnClickL2tpAddrType();
			this.OnClickL2tpReconnect();
			break;
		case "dslite":
			OBJ("box_wan_dslite").style.display = "block";
			OBJ("box_wan_dslite_body").style.display = "block";
			if(XG(this.wan1.inetp+"/ipv4/ipv4in6/remote")!="")
			{
				OBJ("dslite_static").checked = true;
				OBJ("aftr_ipaddr6").disabled = false;
			}
			else
			{
				OBJ("dslite_dynamic").checked = true;
				OBJ("aftr_ipaddr6").disabled = true;
			}	
			break;
		}
	},
	/* PPPoE */
	OnClickPppoeAddrType: function()
	{
		OBJ("pppoe_ipaddr").disabled = OBJ("pppoe_dynamic").checked ? true: false;
	},
	OnClickPppoeReconnect: function()
	{
		if(OBJ("pppoe_alwayson").checked)
		{
			OBJ("pppoe_schedule").disabled = false;
			OBJ("pppoe_schedule_button").disabled = false;
			OBJ("pppoe_max_idle_time").disabled = true;
		}
		else if(OBJ("pppoe_ondemand").checked)
		{
			OBJ("pppoe_schedule").disabled = true;
			OBJ("pppoe_schedule_button").disabled = true;
			OBJ("pppoe_max_idle_time").disabled = false;
		}
		else
		{
			OBJ("pppoe_schedule").disabled = true;
			OBJ("pppoe_schedule_button").disabled = true;
			OBJ("pppoe_max_idle_time").disabled = true;
		}
	},
	OnClickDnsMode: function()
	{
		var dis = OBJ("dns_isp").checked;
		OBJ("pppoe_dns1").disabled = dis;
		OBJ("pppoe_dns2").disabled = dis;
	},
	/* pptp */
	OnClickPptpAddrType: function()
	{
		var dis = OBJ("pptp_dynamic").checked ? true: false;
		OBJ("pptp_ipaddr").disabled	= dis;
		OBJ("pptp_mask").disabled	= dis;
		OBJ("pptp_gw").disabled		= dis;
		OBJ("pptp_dns1").disabled	= dis;
		OBJ("pptp_dns2").disabled	= dis;
	},
	OnClickPptpReconnect: function()
	{
		if(OBJ("pptp_alwayson").checked)
		{
			OBJ("pptp_schedule").disabled = false;
			OBJ("pptp_schedule_button").disabled = false;
			OBJ("pptp_max_idle_time").disabled = true;
		}
		else if(OBJ("pptp_ondemand").checked)
		{
			OBJ("pptp_schedule").disabled = true;
			OBJ("pptp_schedule_button").disabled = true;
			OBJ("pptp_max_idle_time").disabled = false;
		}
		else
		{
			OBJ("pptp_schedule").disabled = true;
			OBJ("pptp_schedule_button").disabled = true;
			OBJ("pptp_max_idle_time").disabled = true;
		}
	},
	/* l2tp */
	OnClickL2tpAddrType: function()
	{
		var dis = OBJ("l2tp_dynamic").checked ? true: false;
		OBJ("l2tp_ipaddr").disabled	= dis;
		OBJ("l2tp_mask").disabled	= dis;
		OBJ("l2tp_gw").disabled		= dis;
		OBJ("l2tp_dns1").disabled	= dis;
		OBJ("l2tp_dns2").disabled	= dis;
	},
	OnClickL2tpReconnect: function()
	{
		if(OBJ("l2tp_alwayson").checked)
		{
			OBJ("l2tp_schedule").disabled = false;
			OBJ("l2tp_schedule_button").disabled = false;
			OBJ("l2tp_max_idle_time").disabled = true;
		}
		else if(OBJ("l2tp_ondemand").checked)
		{
			OBJ("l2tp_schedule").disabled = true;
			OBJ("l2tp_schedule_button").disabled = true;
			OBJ("l2tp_max_idle_time").disabled = false;
		}
		else
		{
			OBJ("l2tp_schedule").disabled = true;
			OBJ("l2tp_schedule_button").disabled = true;
			OBJ("l2tp_max_idle_time").disabled = true;
		}
	},
	/* RPPPoE*/
	OnClickRPppoeAddrType: function()
	{
		var dis = OBJ("rpppoe_dynamic").checked ? true: false;
		OBJ("rpppoe_ipaddr").disabled	= dis;
		OBJ("rpppoe_mask").disabled	= dis;
		OBJ("rpppoe_gw").disabled		= dis;
		OBJ("rpppoe_dns1").disabled	= dis;
		OBJ("rpppoe_dns2").disabled	= dis;
	},
	/* DS-Lite */
	OnClickDsliteAddrType: function()
	{
		OBJ("aftr_ipaddr6").disabled = OBJ("dslite_dynamic").checked ? true: false;
	},
	OnClickMacButton: function(objname)
	{
		OBJ(objname).value="<?echo INET_ARP($_SERVER["REMOTE_ADDR"]);?>";
	},
	OnClickRgmode: function(from)
	{
		if (OBJ("rgmode").checked)
		{
			/* reinit the all setting. */
			if (from === "checkbox")	this.InitValue(this.defaultCFGXML);
			OBJ("rgmode").checked = true;
			this.OnChangeWanIpMode();
			BODY.DisableCfgElements(true);
			if (AUTH.AuthorizedGroup < 100)
			{
				OBJ("rgmode").disabled = false;
				OBJ("topsave").disabled = false;
				OBJ("topcancel").disabled = false;
				OBJ("bottomsave").disabled = false;
				OBJ("bottomcancel").disabled = false;
			}
		}
		else
		{
			BODY.DisableCfgElements(false);
			this.OnChangeWanIpMode();
		}
	},
	DisableDNS: function()
	{
		if(XG(this.wan1.infp+"/open_dns/type")==="advance") var open_dns_srv = "adv_dns_srv";
		else if(XG(this.wan1.infp+"/open_dns/type")==="family") var open_dns_srv = "family_dns_srv";
		else var open_dns_srv = "parent_dns_srv"; 
		var opendns_dns1 = XG(this.wan1.infp+"/open_dns/"+open_dns_srv+"/dns1");
		var opendns_dns2 = XG(this.wan1.infp+"/open_dns/"+open_dns_srv+"/dns2");		
		
		OBJ("ipv4_dns1").disabled = OBJ("ipv4_dns2").disabled = true;
		OBJ("pppoe_dns1").disabled = OBJ("pppoe_dns2").disabled = OBJ("dns_isp").disabled = OBJ("dns_manual").disabled = true;
		OBJ("pptp_dns1").disabled = OBJ("pptp_dns2").disabled = true;
		OBJ("l2tp_dns1").disabled = OBJ("l2tp_dns2").disabled = true;
		OBJ("rpppoe_dns1").disabled = OBJ("rpppoe_dns2").disabled = true;
		OBJ("ipv4_dns1").title = OBJ("ipv4_dns2").title = "<?echo i18n("Locked by parental control");?>";		
		OBJ("pppoe_dns1").title = OBJ("pppoe_dns2").title = "<?echo i18n("Locked by parental control");?>";		
		OBJ("pptp_dns1").title = OBJ("pptp_dns2").title = "<?echo i18n("Locked by parental control");?>";		
		OBJ("l2tp_dns1").title = OBJ("l2tp_dns2").title = "<?echo i18n("Locked by parental control");?>";		
		OBJ("rpppoe_dns1").title = OBJ("rpppoe_dns2").title = "<?echo i18n("Locked by parental control");?>";			
		OBJ("ipv4_dns1").value = OBJ("pppoe_dns1").value = OBJ("pptp_dns1").value = OBJ("l2tp_dns1").value = OBJ("rpppoe_dns1").value = opendns_dns1; 
		OBJ("ipv4_dns2").value = OBJ("pppoe_dns2").value = OBJ("pptp_dns2").value = OBJ("l2tp_dns2").value = OBJ("rpppoe_dns2").value = opendns_dns2;	
	}
}


function IdleTime(value)
{
	if (value=="")
		return "0";
	else
		return parseInt(value, 10);
}

function check_ip_validity(ipstr)
{
	var vals = ipstr.split(".");
	if (vals.length!=4) 
		return false;
	
	for (var i=0; i<4; i++)
	{
		if (!TEMP_IsDigit(vals[i]) || vals[i]>255)
			return false;
	}
	return true;
}

function RemoveItemFromSelect(objSelect,objectItemValue)
{
	//judge if exist
	for(var i=0;i<objSelect.length;i++)
	{
		if(objSelect[i].value==objectItemValue)
		{
			objSelect.remove(i);
			break;
		}
	}
	return;
}

function AddItemFromSelect(objSelect,objItemText,objectItemValue)
{
	//judge if exist
	for(var i=0;i<objSelect.length;i++)
	{
		if(objSelect[i].value==objectItemValue) return;
	}
	var varItem = document.createElement("option");
	varItem.text = objItemText;
	varItem.value = objectItemValue;
	try {objSelect.add(varItem, null);}
	catch(e){objSelect.add(varItem);}
	return;
}
</script>
