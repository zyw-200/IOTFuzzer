<?include "/htdocs/phplib/inet.php";?>
<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "DEVICE.ACCOUNT,DEVICE.TIME,DEVICE.HOSTNAME,PHYINF.WAN-1,INET.WAN-1,INET.WAN-2,WAN",
	OnLoad: function()
	{
		this.ShowCurrentStage();
	},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		self.location.href = "./bsc_internet.php";
		return true;
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		if (!this.Initial()) return false;
		if (!this.InitWANSettings()) return false;
		return true;
	},
	PreSubmit: function()
	{
		PXML.ActiveModule("DEVICE.ACCOUNT");
		PXML.ActiveModule("DEVICE.TIME");
		PXML.ActiveModule("DEVICE.HOSTNAME");
		PXML.CheckModule("INET.WAN-1", null, null, "ignore");
		PXML.CheckModule("INET.WAN-2", null, null, "ignore");
		if (COMM_Equal(OBJ("wiz_dhcp_mac").getAttribute("modified"), true))
		{
			PXML.ActiveModule("PHYINF.WAN-1");
			PXML.DelayActiveModule("PHYINF.WAN-1", "3");
			PXML.IgnoreModule("WAN");
		}
		else
		{
			PXML.CheckModule("PHYINF.WAN-1", null, null, "ignore");
			PXML.CheckModule("WAN", "ignore", "ignore", null);
		}
		if (!this.PrePasswd()) return null;
		if (!this.PreTZ()) return null;
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	passwdp: null,
	password: null,
	tzp: null,
	hostp: null,
	inet1p: null,
	inet2p: null,
	inf1p: null,
	inf2p: null,
	macaddrp: null,
	operatorp: null,
	stages: new Array ("stage_desc", "stage_passwd", "stage_tz", "stage_ether", "stage_ether_cfg", "stage_finish"),
	wanTypes: new Array ("DHCP", "DHCPPLUS", "PPPoE", "PPTP", "L2TP", "STATIC", "R_PPTP", "R_PPPoE"),
	currentStage: 0,	// 0 ~ this.stages.length
	currentWanType: 0,	// 0 ~ this.wanTypes.length
	Initial: function()
	{
		this.passwdp = PXML.FindModule("DEVICE.ACCOUNT");
		this.tzp = PXML.FindModule("DEVICE.TIME");
		if (!this.tzp||!this.passwdp)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		this.passwdp = GPBT(this.passwdp+"/device/account", "entry", "name", "admin", false);
		this.passwdp += "/password";
		this.tzp += "/device/time/timezone";
		this.password = XG(this.passwdp);
		OBJ("wiz_passwd").value = this.password;
		OBJ("wiz_passwd2").value = this.password;
		OBJ("wiz_tz").value = XG(this.tzp);
		return true;
	},
	PrePasswd: function()
	{
		if (OBJ("wiz_passwd").value!=this.password)
			XS(this.passwdp, OBJ("wiz_passwd").value);
		return true;
	},
	PreTZ: function()
	{
		XS(this.tzp, OBJ("wiz_tz").value);
		return true;
	},
	InitWANSettings: function()
	{
		this.hostp = PXML.FindModule("DEVICE.HOSTNAME");
		this.inet1p = PXML.FindModule("INET.WAN-1");
		this.inet2p = PXML.FindModule("INET.WAN-2");
		var phyinfp = PXML.FindModule("PHYINF.WAN-1");
		if (!this.hostp||!this.inet1p||!this.inet2p||!phyinfp)
		{
			BODY.ShowAlert("InitWANSettings() ERROR!!!");
			return false;
		}
		var inet1 = XG(this.inet1p+"/inf/inet");
		var inet2 = XG(this.inet2p+"/inf/inet");
		var eth = XG(phyinfp+"/inf/phyinf");
		this.inf1p = this.inet1p+"/inf";
		this.inf2p = this.inet2p+"/inf";
		this.inet1p = GPBT(this.inet1p+"/inet", "entry", "uid", inet1, false);
		this.inet2p = GPBT(this.inet2p+"/inet", "entry", "uid", inet2, false);
		phyinfp = GPBT(phyinfp, "phyinf", "uid", eth, false);
		this.macaddrp = phyinfp+"/macaddr";
		this.operatorp += "/runtime/services/operator";
		this.GetWanType();
		SetRadioValue("wan_mode", this.wanTypes[this.currentWanType]);
		/////////////////////////// initial PPPv4 hidden nodes ///////////////////////////
		OBJ("ppp4_timeout").value	= IdleTime(XG(this.inet1p+"/ppp4/dialup/idletimeout"));
		OBJ("ppp4_mode").value		= XG(this.inet1p+"/ppp4/dialup/mode");
		OBJ("ppp4_mtu").value		= XG(this.inet1p+"/ppp4/mtu");
		/////////////////////////// initial DHCP settings ///////////////////////////
		OBJ("ipv4_mtu").value		= XG(this.inet1p+"/ipv4/mtu");
		OBJ("wiz_dhcp_mac").value	= XG(this.macaddrp);
		OBJ("wiz_dhcp_host").value	= XG(this.hostp+"/device/hostname");
		OBJ("wiz_dhcpplus_user").value	= XG(this.inet1p+"/ipv4/dhcpplus/username");
		OBJ("wiz_dhcpplus_pass").value	= XG(this.inet1p+"/ipv4/dhcpplus/password");
		/////////////////////////// initial PPPoE settings ///////////////////////////
		OBJ("wiz_pppoe_ipaddr").value	= ResAddress(XG(this.inet1p+"/ppp4/ipaddr"));
		OBJ("wiz_pppoe_usr").value		= XG(this.inet1p+"/ppp4/username");
		OBJ("wiz_pppoe_passwd").value	= XG(this.inet1p+"/ppp4/password");
		OBJ("wiz_pppoe_passwd2").value	= XG(this.inet1p+"/ppp4/password");
		OBJ("wiz_pppoe_svc").value		= XG(this.inet1p+"/ppp4/pppoe/servicename");
		if (XG(this.inet2p+"/ipv4/static")=="1")
		{
			OBJ("wiz_rpppoe_ipaddr").value	= ResAddress(XG(this.inet2p+"/ipv4/ipaddr"));
			OBJ("wiz_rpppoe_mask").value	= COMM_IPv4INT2MASK(XG(this.inet2p+"/ipv4/mask"));
			OBJ("wiz_rpppoe_gw").value		= ResAddress(XG(this.inet2p+"/ipv4/gateway"));
			OBJ("wiz_rpppoe_dns1").value	= ResAddress(XG(this.inet2p+"/ipv4/dns/entry:1"));
			OBJ("wiz_rpppoe_dns2").value	= ResAddress(XG(this.inet2p+"/ipv4/dns/entry:2"));
		}
		/////////////////////////// initial PPTP settings ///////////////////////////
		OBJ("wiz_pptp_ipaddr").value	= ResAddress(XG(this.inet2p+"/ipv4/ipaddr"));
		OBJ("wiz_pptp_mask").value		= COMM_IPv4INT2MASK(XG(this.inet2p+"/ipv4/mask"));
		OBJ("wiz_pptp_gw").value		= ResAddress(XG(this.inet2p+"/ipv4/gateway"));
		OBJ("wiz_pptp_svr").value		= ResAddress(XG(this.inet1p+"/ppp4/pptp/server"));
		OBJ("wiz_pptp_usr").value		= XG(this.inet1p+"/ppp4/username");
		OBJ("wiz_pptp_passwd").value	= XG(this.inet1p+"/ppp4/password");
		OBJ("wiz_pptp_passwd2").value	= XG(this.inet1p+"/ppp4/password");
		/////////////////////////// initial L2TP settings ///////////////////////////
		OBJ("wiz_l2tp_ipaddr").value	= ResAddress(XG(this.inet2p+"/ipv4/ipaddr"));
		OBJ("wiz_l2tp_mask").value		= COMM_IPv4INT2MASK(XG(this.inet2p+"/ipv4/mask"));
		OBJ("wiz_l2tp_gw").value		= ResAddress(XG(this.inet2p+"/ipv4/gateway"));
		OBJ("wiz_l2tp_svr").value		= ResAddress(XG(this.inet1p+"/ppp4/l2tp/server"));
		OBJ("wiz_l2tp_usr").value		= XG(this.inet1p+"/ppp4/username");
		OBJ("wiz_l2tp_passwd").value	= XG(this.inet1p+"/ppp4/password");
		OBJ("wiz_l2tp_passwd2").value	= XG(this.inet1p+"/ppp4/password");
		/////////////////////////// initial STATIC IP settings ///////////////////////////
		OBJ("wiz_static_ipaddr").value	= ResAddress(XG(this.inet1p+"/ipv4/ipaddr"));
		OBJ("wiz_static_mask").value	= COMM_IPv4INT2MASK(XG(this.inet1p+"/ipv4/mask"));
		OBJ("wiz_static_gw").value		= ResAddress(XG(this.inet1p+"/ipv4/gateway"));
		OBJ("wiz_static_dns1").value	= ResAddress(XG(this.inet1p+"/ipv4/dns/entry:1"));
		OBJ("wiz_static_dns2").value	= ResAddress(XG(this.inet1p+"/ipv4/dns/entry:2"));
		
		if (XG(this.inet1p+"/ppp4/static")=="1")
		{
			document.getElementsByName("wiz_pppoe_conn_mode")[1].checked = true;
		}
		else
		{
			document.getElementsByName("wiz_pppoe_conn_mode")[0].checked = true;
		}
		if (XG(this.inet2p+"/ipv4/static")=="1")
		{
			document.getElementsByName("wiz_rpppoe_conn_mode")[1].checked = true;
			document.getElementsByName("wiz_pptp_conn_mode")[1].checked = true;
			document.getElementsByName("wiz_l2tp_conn_mode")[1].checked = true;
		}
		else
		{
			document.getElementsByName("wiz_rpppoe_conn_mode")[0].checked = true;
			document.getElementsByName("wiz_pptp_conn_mode")[0].checked = true;
			document.getElementsByName("wiz_l2tp_conn_mode")[0].checked = true;
		}
		this.OnChangeRussiaPPPoEMode();
		this.OnChangePPPoEMode();
		this.OnChangePPTPMode();
		this.OnChangeL2TPMode();
		return true;
	},
	PreWANSettings: function()
	{
		var type = GetRadioValue("wan_mode");
		var russia = false;
		XD(this.inet1p+"/ipv4");
		XD(this.inet1p+"/ppp4");
		XS(this.inf1p+"/lowerlayer", "");
		XS(this.inf1p+"/upperlayer", "");
		XS(this.inf1p+"/schedule", "");
		XS(this.inf2p+"/lowerlayer", "");
		XS(this.inf2p+"/upperlayer", "");
		XS(this.inf2p+"/schedule", "");
		XS(this.inf2p+"/active", 0);
		XS(this.inf2p+"/defaultroute", 0);
		XS(this.inf2p+"/nat", "");
		XS(this.macaddrp, OBJ("wiz_dhcp_mac").value);
		switch (type)
		{
		case "DHCPPLUS":
			XS(this.inet1p+"/ipv4/dhcpplus/username", OBJ("wiz_dhcpplus_user").value);
			XS(this.inet1p+"/ipv4/dhcpplus/password", OBJ("wiz_dhcpplus_pass").value);
		case "DHCP":
			if (type == "DHCPPLUS")
				XS(this.inet1p+"/ipv4/dhcpplus/enable", "1");
			else
				XS(this.inet1p+"/ipv4/dhcpplus/enable", "0");
			/////////////////////////// prepare DHCP settings ///////////////////////////
			XS(this.inet1p+"/addrtype", "ipv4");
			XS(this.inet1p+"/ipv4/static", 0);
			XS(this.inet1p+"/ipv4/mtu", OBJ("ipv4_mtu").value);
			XS(this.hostp+"/device/hostname", OBJ("wiz_dhcp_host").value);
			SetDNSAddress(this.inet1p+"/ipv4/dns", "", "");
			break;
		case "R_PPPoE":
			russia = true;
		case "PPPoE":
			/////////////////////////// prepare PPPoE settings ///////////////////////////
			var dynamic_pppoe = document.getElementsByName("wiz_pppoe_conn_mode")[0].checked ? true: false;
			XS(this.inet1p+"/addrtype", "ppp4");
			XS(this.inet1p+"/ppp4/over", "eth");
			XS(this.inet1p+"/ppp4/static", document.getElementsByName("wiz_pppoe_conn_mode")[0].checked ? 0:1);
			if (!dynamic_pppoe)	XS(this.inet1p+"/ppp4/ipaddr", OBJ("wiz_pppoe_ipaddr").value);
			XS(this.inet1p+"/ppp4/username", OBJ("wiz_pppoe_usr").value);
			XS(this.inet1p+"/ppp4/password", OBJ("wiz_pppoe_passwd").value);
			XS(this.inet1p+"/ppp4/pppoe/servicename", OBJ("wiz_pppoe_svc").value);
			SetDNSAddress(this.inet1p+"/ppp4/dns", "", "");
			if (russia)
			{
				XS(this.inf1p+"/lowerlayer",	"WAN-2");
				XS(this.inf2p+"/active",		1);
				XS(this.inf2p+"/nat",			"NAT-1");
				XS(this.inf2p+"/upperlayer",	"WAN-1");
				XS(this.inet2p+"/addrtype",		"ipv4");
				if (GetRadioValue("wiz_rpppoe_conn_mode")=="dynamic")
				{
					XS(this.inet2p+"/ipv4/static", 0);
				}
				else
				{
					XS(this.inet2p+"/ipv4/static",	1);
					XS(this.inet2p+"/ipv4/ipaddr",	OBJ("wiz_rpppoe_ipaddr").value);
					XS(this.inet2p+"/ipv4/mask",	COMM_IPv4MASK2INT(OBJ("wiz_rpppoe_mask").value));
					XS(this.inet2p+"/ipv4/gateway",	OBJ("wiz_rpppoe_gw").value);
					SetDNSAddress(this.inet2p+"/ipv4/dns", OBJ("wiz_rpppoe_dns1").value, OBJ("wiz_rpppoe_dns2").value);
				}
			}
			break;
		case "R_PPTP":
			russia = true;
		case "PPTP":
			/////////////////////////// prepare PPTP settings ///////////////////////////
			var dynamic_pptp = document.getElementsByName("wiz_pptp_conn_mode")[0].checked ? true: false;
			XS(this.inf2p+"/active",		1);
			XS(this.inet1p+"/addrtype",		"ppp4");
			XS(this.inet1p+"/ppp4/over",	"pptp");
			XS(this.inet1p+"/ppp4/static",	0);
			XS(this.inet2p+"/addrtype",		"ipv4");
			if (dynamic_pptp)
			{
				XS(this.inet2p+"/ipv4/static", 0);
			}
			else
			{
				XS(this.inet2p+"/ipv4/static",	1);
				XS(this.inet2p+"/ipv4/ipaddr",	OBJ("wiz_pptp_ipaddr").value);
				XS(this.inet2p+"/ipv4/mask",	COMM_IPv4MASK2INT(OBJ("wiz_pptp_mask").value));
				XS(this.inet2p+"/ipv4/gateway",	OBJ("wiz_pptp_gw").value);
			}
			XS(this.inet1p+"/ppp4/pptp/server",	OBJ("wiz_pptp_svr").value);
			XS(this.inet1p+"/ppp4/username",	OBJ("wiz_pptp_usr").value);
			XS(this.inet1p+"/ppp4/password",	OBJ("wiz_pptp_passwd").value);
			XS(this.inf1p+"/lowerlayer", "WAN-2");
			XS(this.inf2p+"/upperlayer", "WAN-1");
			SetDNSAddress(this.inet1p+"/ppp4/dns", "", "");
			if (russia)
			{
				XS(this.inf2p+"/nat", "NAT-1");
			}
			break;
		case "L2TP":
			/////////////////////////// prepare L2TP settings ///////////////////////////
			var dynamic_l2tp = document.getElementsByName("wiz_l2tp_conn_mode")[0].checked ? true: false;
			XS(this.inf2p+"/active",		1);
			XS(this.inet1p+"/addrtype",		"ppp4");
			XS(this.inet1p+"/ppp4/over",	"l2tp");
			XS(this.inet1p+"/ppp4/static",	0);
			XS(this.inet2p+"/addrtype",		"ipv4");
			if (dynamic_l2tp)
			{
				XS(this.inet2p+"/ipv4/static", 0);
			}
			else
			{
				XS(this.inet2p+"/ipv4/static",	1);
				XS(this.inet2p+"/ipv4/ipaddr",	OBJ("wiz_l2tp_ipaddr").value);
				XS(this.inet2p+"/ipv4/mask",	COMM_IPv4MASK2INT(OBJ("wiz_l2tp_mask").value));
				XS(this.inet2p+"/ipv4/gateway",	OBJ("wiz_l2tp_gw").value);
			}
			XS(this.inet1p+"/ppp4/l2tp/server",	OBJ("wiz_l2tp_svr").value);
			XS(this.inet1p+"/ppp4/username",	OBJ("wiz_l2tp_usr").value);
			XS(this.inet1p+"/ppp4/password",	OBJ("wiz_l2tp_passwd").value);
			XS(this.inf1p+"/lowerlayer", "WAN-2");
			XS(this.inf2p+"/upperlayer", "WAN-1");
			SetDNSAddress(this.inet1p+"/ppp4/dns", "", "");
			break;
		case "STATIC":
			/////////////////////////// prepare STATIC IP settings ///////////////////////////
			XS(this.inet1p+"/addrtype",		"ipv4");
			XS(this.inet1p+"/ipv4/static",	1);
			XS(this.inet1p+"/ipv4/ipaddr",	OBJ("wiz_static_ipaddr").value);
			XS(this.inet1p+"/ipv4/mask",	COMM_IPv4MASK2INT(OBJ("wiz_static_mask").value));
			XS(this.inet1p+"/ipv4/gateway",	OBJ("wiz_static_gw").value);
			XS(this.inet1p+"/ipv4/mtu",		OBJ("ipv4_mtu").value);
			SetDNSAddress(this.inet1p+"/ipv4/dns", OBJ("wiz_static_dns1").value, OBJ("wiz_static_dns2").value);
			break;
		}
		if (type=="DHCP"||type=="STATIC")
		{
			XS(this.inet2p+"/ipv4/static",  0);
			XS(this.inet2p+"/ipv4/ipaddr",  "");
			XS(this.inet2p+"/ipv4/mask",    "");
			XS(this.inet2p+"/ipv4/gateway", "");
		}
		else
		{
			/////////////////////////// prepare PPPv4 hidden nodes ///////////////////////////
			XS(this.inet1p+"/ppp4/dialup/idletimeout", OBJ("ppp4_timeout").value);
			XS(this.inet1p+"/ppp4/dialup/mode", (OBJ("ppp4_mode").value=="") ? "auto": OBJ("ppp4_mode").value);
			if (type != "PPPoE" && ( OBJ("ppp4_mtu").value < 576 || OBJ("ppp4_mtu").value > 1400 ) ) XS(this.inet1p+"/ppp4/mtu", "1400");  
			else XS(this.inet1p+"/ppp4/mtu", OBJ("ppp4_mtu").value);
		}

		return true;
	},
	ShowCurrentStage: function()
	{
		var i = 0;
		var type = "";
		for (i=0; i<this.wanTypes.length; i++)
		{
			type = this.wanTypes[i];
			if (type=="R_PPTP")			type = "PPTP";
			else if (type=="R_PPPoE")	type = "PPPoE";
			else if (type=="DHCPPLUS")	type = "DHCP";
			OBJ(type).style.display = "none";
		}
		for (i=0; i<this.stages.length; i++)
		{
			if (i==this.currentStage)
			{
				OBJ(this.stages[i]).style.display = "block";
				if (this.stages[this.currentStage]=="stage_ether_cfg")
				{
					type = this.wanTypes[this.currentWanType];
					if (type=="R_PPTP")			type = "PPTP";
					else if (type=="R_PPPoE")	type = "PPPoE";
					else if (type=="DHCPPLUS")	type = "DHCP";
					OBJ(type).style.display = "block";
				}
			}
			else
			{
				OBJ(this.stages[i]).style.display = "none";
			}
		}

		if (this.currentStage==0)
			OBJ("b_pre").disabled = true;
		else
			OBJ("b_pre").disabled = false;

		if (this.currentStage==this.stages.length-1)
		{
			OBJ("b_next").disabled = true;
			OBJ("b_send").disabled = false;
		}
		else
		{
			OBJ("b_next").disabled = false;
			OBJ("b_send").disabled = true;
		}
	},
	SetStage: function(offset)
	{
		var length = this.stages.length;
		switch (offset)
		{
		case 1:
			if (this.currentStage < length-1)
				this.currentStage += 1;
			break;
		case -1:
			if (this.currentStage > 0)
				this.currentStage -= 1;
			break;
		}
	},
	OnClickPre: function()
	{
		this.SetStage(-1);
		this.ShowCurrentStage();
	},
	OnClickNext: function()
	{
		var stage = this.stages[this.currentStage];
		if (stage == "stage_passwd")
		{
			if (OBJ("wiz_passwd").value!=OBJ("wiz_passwd2").value)
			{
				BODY.ShowAlert("<?echo i18n("Please make the two passwords the same and try again.");?>");
				return false;
			}
			this.SetStage(1);
			this.ShowCurrentStage();
		}
		else if (stage == "stage_ether_cfg")
		{
			this.PreWANSettings();
			var type = this.wanTypes[this.currentWanType];
			if (type=="R_PPTP")			type = "PPTP";
			else if (type=="R_PPPoE")	type = "PPPoE";
			else if (type=="DHCPPLUS")	type = "DHCP";
			CheckWANSettings(type);
		}
		else
		{
			this.SetStage(1);
			this.ShowCurrentStage();
		}
	},
	OnClickCancel: function()
	{
		if (!COMM_IsDirty(false)||confirm("<?echo i18n("Do you want to abandon all changes you made to this wizard?");?>"))
			self.location.href = "./bsc_internet.php";
	},
	OnChangeWanType: function(type)
	{
		for (var i=0; i<this.wanTypes.length; i++)
		{
			if (type=="R_PPPoE")	OBJ("R_PPPoE").style.display = "block";
			else					OBJ("R_PPPoE").style.display = "none";
			
			if (type=="DHCPPLUS")	OBJ("DHCPPLUS").style.display = "block";
			else					OBJ("DHCPPLUS").style.display = "none";

			if (this.wanTypes[i]==type)
				this.currentWanType = i;
		}
	},
	OnClickCloneMAC: function()
	{
		OBJ("wiz_dhcp_mac").value = "<?echo INET_ARP($_SERVER["REMOTE_ADDR"]);?>";
	},
	GetWanType: function()
	{
		var addrtype = XG(this.inet1p+"/addrtype");
		var type = null;
		switch (addrtype)
		{
		case "ipv4":
			if (XG(this.inet1p+"/ipv4/static")=="0")
			{
				if (XG(this.inet1p+"/ipv4/dhcpplus/enable")=="1")
					type = "DHCPPLUS";
				else
					type = "DHCP";
			}
			else
				type = "STATIC";
			break;
		case "ppp4":
			if (XG(this.inet1p+"/ppp4/over")=="eth")
			{
				if (XG(this.inf2p+"/active")=="1" && XG(this.inf2p+"/nat")=="NAT-1")
					type = "R_PPPoE";
				else
					type = "PPPoE";
			}
			else if (XG(this.inet1p+"/ppp4/over")=="pptp")
			{
				if (XG(this.inf2p+"/nat")=="NAT-1")
					type = "R_PPTP";
				else
					type = "PPTP";
			}
			else if (XG(this.inet1p+"/ppp4/over")=="l2tp")
			{
				type = "L2TP";
			}
			break;
		default:
			BODY.ShowAlert("Internal Error!!");
		}

		for (var i=0; i<this.wanTypes.length; i++)
		{
			if (this.wanTypes[i]==type)	this.currentWanType = i;
		}
		if (type=="R_PPPoE")	OBJ("R_PPPoE").style.display = "block";
		else					OBJ("R_PPPoE").style.display = "none";
		
		if (type=="DHCPPLUS")	OBJ("DHCPPLUS").style.display = "block";
		else					OBJ("DHCPPLUS").style.display = "none";
	},
	OnChangeRussiaPPPoEMode: function()
	{
		var disable = document.getElementsByName("wiz_rpppoe_conn_mode")[0].checked ? true: false;
		OBJ("wiz_rpppoe_ipaddr").disabled = disable;
		OBJ("wiz_rpppoe_mask").disabled = disable;
		OBJ("wiz_rpppoe_gw").disabled = disable;
		OBJ("wiz_rpppoe_dns1").disabled = disable;
		OBJ("wiz_rpppoe_dns2").disabled = disable;
	},
	OnChangePPPoEMode: function()
	{
		var disable = document.getElementsByName("wiz_pppoe_conn_mode")[0].checked ? true: false;
		OBJ("wiz_pppoe_ipaddr").disabled = disable;
	},
	OnChangePPTPMode: function()
	{
		var disable = document.getElementsByName("wiz_pptp_conn_mode")[0].checked ? true: false;
		OBJ("wiz_pptp_ipaddr").disabled = disable;
		OBJ("wiz_pptp_mask").disabled = disable;
		OBJ("wiz_pptp_gw").disabled = disable;
	},
	OnChangeL2TPMode: function()
	{
		var disable = document.getElementsByName("wiz_l2tp_conn_mode")[0].checked ? true: false;
		OBJ("wiz_l2tp_ipaddr").disabled = disable;
		OBJ("wiz_l2tp_mask").disabled = disable;
		OBJ("wiz_l2tp_gw").disabled = disable;
	}
}

function SetButtonDisabled(name, disable)
{
	var button = document.getElementsByName(name);
	for (i=0; i<button.length; i++)
	{
		button[i].disabled = disable;
	}
}
function GetRadioValue(name)
{
	var radio = document.getElementsByName(name);
	var value = null;
	for (i=0; i<radio.length; i++)
	{
		if (radio[i].checked)	return radio[i].value;
	}
}
function SetRadioValue(name, value)
{
	var radio = document.getElementsByName(name);
	for (i=0; i<radio.length; i++)
	{
		if (radio[i].value==value)	radio[i].checked = true;
	}
}
function ResAddress(address)
{
	if (address=="")
		return "0.0.0.0";
	else if (address=="0.0.0.0")
		return "";
	else
		return address;
}
function SetDNSAddress(path, dns1, dns2)
{
	var cnt = 0;
	var dns = new Array (false, false);
	if (dns1!="0.0.0.0"&&dns1!="") {dns[0] = true; cnt++;}
	if (dns2!="0.0.0.0"&&dns2!="") {dns[1] = true; cnt++;}
	XA(path+"/count", cnt);
	if (dns[0]) XA(path+"/entry", dns1);
	if (dns[1]) XA(path+"/entry", dns2);
}

function CheckWANSettings(type)
{
	PXML.IgnoreModule("DEVICE.ACCOUNT");
	PXML.IgnoreModule("DEVICE.TIME");
	PXML.IgnoreModule("DEVICE.HOSTNAME");
	PXML.IgnoreModule("RUNTIME.INF.WAN-1");
	PXML.IgnoreModule("WAN");
	PXML.CheckModule("INET.WAN-1", null, "ignore", "ignore");
	PXML.CheckModule("INET.WAN-2", null, "ignore", "ignore");
	switch (type)
	{
	case "DHCP":
		PXML.CheckModule("DEVICE.HOSTNAME", null, "ignore", "ignore");
		PXML.CheckModule("PHYINF.WAN-1", null, "ignore", "ignore");
		break;
	case "PPPoE":
		if (PAGE.wanTypes[PAGE.currentWanType]=="R_PPPoE" && document.getElementsByName("wiz_rpppoe_conn_mode")[1].checked)
		{
			if (OBJ("wiz_rpppoe_dns1").value==="" || OBJ("wiz_rpppoe_dns1").value==="0.0.0.0")
			{
				BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address.");?>");
				return false;
			}
		}
	case "PPTP":
	case "L2TP":
		if (OBJ("wiz_"+type.toLowerCase()+"_passwd").value!=
			OBJ("wiz_"+type.toLowerCase()+"_passwd2").value)
		{
			BODY.ShowAlert("<?echo i18n("Please make the two passwords the same and try again.");?>");
			return false;
		}
		break;
	case "STATIC":
		if (OBJ("wiz_static_dns1").value==="" || OBJ("wiz_static_dns1").value==="0.0.0.0")
		{
			BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address.");?>");
			return false;
		}
		break;
	}

	AUTH.UpdateTimeout();
	COMM_CallHedwig(PXML.doc, 
		function (xml)
		{
			switch (xml.Get("/hedwig/result"))
			{
			case "OK":
				PAGE.SetStage(1);
				PAGE.ShowCurrentStage();
				break;
			case "FAILED":
				BODY.ShowAlert(xml.Get("/hedwig/message"));
				break;
			}
		}
	);
}

function ChangeSelectorOptions(id, options)
{
	var slt = OBJ(id);
	for (var i=slt.length; i>=1; i--)
	{
		slt.remove(i);
	}

	for (var i=0; i<options.length; i++)
	{
		var item = document.createElement("option");
		item.text = options[i];
		item.value = options[i];
		try
		{
			slt.add(item, null);
		}
		catch(e)
		{
			slt.add(item);	// IE only
		}
	}
}

function IdleTime(value)
{
	if (value=="")
		return "0";
	else
		return parseInt(value, 10);
}
</script>
