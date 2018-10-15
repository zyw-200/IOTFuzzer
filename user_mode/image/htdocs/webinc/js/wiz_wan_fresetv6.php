<?include "/htdocs/phplib/inet.php";?>
<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "RUNTIME.PHYINF,INET.WAN-1,INET.WAN-2,INET.WAN-3,INET.WAN-4,INET.LAN-4,RUNTIME.INF.WAN-1,RUNTIME.INF.WAN-4,WAN,DHCPS6.LAN-4",
	OnLoad: function()
	{
		this.ShowCurrentStage();
	},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		/* reboot */
		Service("REBOOT");
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
		PXML.IgnoreModule("RUNTIME.PHYINF");
		PXML.IgnoreModule("RUNTIME.INF.WAN-1");
		PXML.IgnoreModule("RUNTIME.INF.WAN-4");
		PXML.CheckModule("INET.WAN-1", null, null, "ignore");
		PXML.CheckModule("INET.WAN-3", null, null, "ignore");
		PXML.CheckModule("INET.WAN-4", null, null, "ignore");
		PXML.CheckModule("INET.LAN-4", null, null, "ignore");
		PXML.CheckModule("DHCPS6.LAN-4", null, null, "ignore");
		//PXML.CheckModule("WAN", "ignore", "ignore", null);
		/* reboot */
		//Service("REBOOT");
		
		//PXML.doc.dbgdump();
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	bootuptime: <?
		$bt=query("/runtime/device/bootuptime");
		if ($bt=="")	$bt=30;
		else			$bt=$bt+10;
		echo $bt;
	?>,
	inet1p: null,
	inet2p: null,
	inet3p: null,
	inet4p: null,
	laninetp: null,
	inf1p: null,
	inf2p: null,
	inf3p: null,
	inf4p: null,
	laninfp: null,
	rwan1: null,
	rwan4: null,
	dhcps6p: null,
	stages: new Array ("stage_descv6", "stage_wan_detectv6", "stage_etherv6", "stage_ether_cfgv6", "stage_finishv6"),
	wanTypes: new Array ("PPPoE", "AUTO", "STATIC", "6RD"),
	wanDetectProcess: new Array ("wan_detectv6", "wantypev6_unkown"),
	wanDetectNum: 0,
	currentStage: 0,	// 0 ~ this.stages.length
	currentWanType: 0,	// 0 ~ this.wanTypes.length
	isppp10: 0,
	isppp6: 0,
	ori_inet1p: null,
	Initial: function()
	{
		return true;
	},
	InitWANSettings: function()
	{
		this.inet1p = PXML.FindModule("INET.WAN-1");
		this.inet2p = PXML.FindModule("INET.WAN-2");
		this.inet3p = PXML.FindModule("INET.WAN-3");
		this.inet4p = PXML.FindModule("INET.WAN-4");
		this.laninetp = PXML.FindModule("INET.LAN-4");
		this.rwan1 = PXML.FindModule("RUNTIME.INF.WAN-1");
		this.rwan4 = PXML.FindModule("RUNTIME.INF.WAN-4");
		this.dhcps6p = PXML.FindModule("DHCPS6.LAN-4");
		//var phyinfp = PXML.FindModule("PHYINF.WAN-1");
		if (!this.inet1p||!this.inet3p||!this.inet4p)
		{
			BODY.ShowAlert("InitWANSettings() ERROR!!!");
			return false;
		}
		var inet1 = XG(this.inet1p+"/inf/inet");
		var inet2 = XG(this.inet2p+"/inf/inet");
		var inet3 = "INET-8"; //may be null
		var inet4 = XG(this.inet4p+"/inf/inet");
		var laninet = XG(this.laninetp+"/inf/inet");
		if(laninet=="") laninet= "INET-7";
		//var eth = XG(phyinfp+"/inf/phyinf");
		this.inf1p = this.inet1p+"/inf";
		this.inf2p = this.inet2p+"/inf";
		this.inf3p = this.inet3p+"/inf";
		this.inf4p = this.inet4p+"/inf";
		this.laninfp = this.laninetp+"/inf";
		this.inet1p = GPBT(this.inet1p+"/inet", "entry", "uid", inet1, false);
		this.inet2p = GPBT(this.inet2p+"/inet", "entry", "uid", inet2, false);
		this.inet3p = GPBT(this.inet3p+"/inet", "entry", "uid", inet3, false);
		this.inet4p = GPBT(this.inet4p+"/inet", "entry", "uid", inet4, false);
		this.laninetp = GPBT(this.laninetp+"/inet", "entry", "uid", laninet, false);
		var dhcps6 = XG(this.laninetp+"/inf/dhcps6");
		if(dhcps6=="") dhcps6 = "DHCPS6-1";
		this.dhcps6p = GPBT(this.dhcps6p+"/dhcps6", "entry", "uid", dhcps6, false);
		//phyinfp = GPBT(phyinfp, "phyinf", "uid", eth, false);
		//this.macaddrp = phyinfp+"/macaddr";
		this.GetWanType();
		SetRadioValue("wanv6_mode", this.wanTypes[this.currentWanType]);
		/////////////////////////// initial PPPoE settings ///////////////////////////
		if(this.isppp10==1)
		{
			OBJ("wiz_pppoe_usr").value	= XG(this.inet1p+"/ppp6/username");
			OBJ("wiz_pppoe_passwd").value	= XG(this.inet1p+"/ppp6/password");
			OBJ("wiz_pppoe_passwd2").value	= XG(this.inet1p+"/ppp6/password");
			OBJ("wiz_pppoe_svc").value	= XG(this.inet1p+"/ppp6/pppoe/servicename");
			document.getElementsByName("wiz_pppoe_sess_type")[0].checked = true;
		}
		else
		{
			OBJ("wiz_pppoe_usr").value	= XG(this.inet3p+"/ppp6/username");
			OBJ("wiz_pppoe_passwd").value	= XG(this.inet3p+"/ppp6/password");
			OBJ("wiz_pppoe_passwd2").value	= XG(this.inet3p+"/ppp6/password");
			OBJ("wiz_pppoe_svc").value	= XG(this.inet3p+"/ppp6/pppoe/servicename");
			if(this.isppp6==1)
				document.getElementsByName("wiz_pppoe_sess_type")[1].checked = true;
		}
		//this.OnChangePPPoESessType();
		/////////////////////////// initial STATIC IPv6 settings ///////////////////////////
		var tRTPHYsvcs = GPBT("/postxml", "module", "service", "RUNTIME.PHYINF", false);
		var wan3phyinf = XG(this.inf3p+"/phyinf");
		var tRTPHYphyinf = GPBT(tRTPHYsvcs+"/runtime", "phyinf", "uid", wan3phyinf);
		var ipv6ll = XG(tRTPHYphyinf+"/ipv6/link/ipaddr");
		var ipv6ip = XG(this.inet4p+"/ipv6/ipaddr");
		if(ipv6ip)
		{
			if(ipv6ip==ipv6ll)
			{
				OBJ("usell").checked = true;
				OBJ("wiz_static_wan_v6addr").disabled	= true;
				OBJ("wiz_static_pfxlen").disabled	= true;

			}
			else
			{
				OBJ("usell").checked = false;
				OBJ("wiz_static_wan_v6addr").disabled	= false;
				OBJ("wiz_static_pfxlen").disabled	= false;
			}
			OBJ("wiz_static_wan_v6addr").value	= XG(this.inet4p+"/ipv6/ipaddr");
			OBJ("wiz_static_pfxlen").value	= XG(this.inet4p+"/ipv6/prefix");
		}
		else
		{
			tRTPHYphyinf = GPBT(tRTPHYsvcs+"/runtime", "phyinf", "uid", "ETH-2");
			ipv6ll = XG(tRTPHYphyinf+"/ipv6/link/ipaddr");
			OBJ("usell").checked = true;
			OBJ("wiz_static_wan_v6addr").disabled	= true;
			OBJ("wiz_static_pfxlen").disabled	= true;
			OBJ("wiz_static_wan_v6addr").value	= ipv6ll;
			OBJ("wiz_static_pfxlen").value	= 64;
		}
		
		OBJ("wiz_static_gw").value	= XG(this.inet4p+"/ipv6/gateway");
		OBJ("wiz_static_pridns6").value	= XG(this.inet4p+"/ipv6/dns/entry:1");
		OBJ("wiz_static_secdns6").value	= XG(this.inet4p+"/ipv6/dns/entry:2");
		OBJ("wiz_static_lan_v6addr").value	= XG(this.laninetp+"/ipv6/ipaddr");
		OBJ("wiz_static_lan_pfxlen").innerHTML = "/64";
		/////////////////////////// initial 6RD settings ///////////////////////////
		OBJ("wiz_6rd_v4addr").disabled = true;
		OBJ("wiz_6rd_prefix").value	= XG(this.inet4p+"/ipv6/ipv6in4/rd/ipaddr");
		OBJ("wiz_6rd_pfxlen").value	= XG(this.inet4p+"/ipv6/ipv6in4/rd/prefix");

		var wan1mode = XG(this.inet1p+"/addrtype");
		if(wan1mode=="ppp4")
			OBJ("wiz_6rd_v4addr").value	= XG(this.rwan1+"/runtime/inf/inet/ppp4/local");
		else
			OBJ("wiz_6rd_v4addr").value	= XG(this.rwan1+"/runtime/inf/inet/ipv4/ipaddr");

		OBJ("wiz_6rd_v4addr_mask").value	= XG(this.inet4p+"/ipv6/ipv6in4/rd/v4mask");

		var str = XG(this.rwan4+"/runtime/inf/inet/ipv6/ipaddr");
		var str1 = XG(this.rwan4+"/runtime/inf/inet/ipv6/prefix");
		if(str)
		{
			index = str.lastIndexOf("::");
			str = str.substring(0,index);
			OBJ("wiz_6rd_v6addr").innerHTML = str+"::/"+str1;
		}

		OBJ("wiz_6rd_relay").value	= XG(this.inet4p+"/ipv6/ipv6in4/relay");
		OBJ("wiz_6rd_pridns6").value	= XG(this.inet4p+"/ipv6/dns/entry:1");
		this.CreateBackupInet1p();
		return true;
	},
	
	LoadOriInet1p: function()
	{
		XS(this.inet1p+"/addrtype", 			this.ori_inet1p[0]);
		XS(this.inet1p+"/ppp4/over", 			this.ori_inet1p[1]);
		XS(this.inet1p+"/ppp6/over", 			this.ori_inet1p[2]);
		XS(this.inet1p+"/ppp4/username", 		this.ori_inet1p[3]);
		XS(this.inet1p+"/ppp6/username", 		this.ori_inet1p[4]);
		XS(this.inet1p+"/ppp4/password", 		this.ori_inet1p[5]);
		XS(this.inet1p+"/ppp6/password", 		this.ori_inet1p[6]);
		XS(this.inet1p+"/ppp4/pppoe/servicename", this.ori_inet1p[7]);
		XS(this.inet1p+"/ppp6/pppoe/servicename", this.ori_inet1p[8]);
		XS(this.inet1p+"/ppp4/dialup/mode", 	this.ori_inet1p[9]);
		XS(this.inet1p+"/ppp6/dialup/mode", 	this.ori_inet1p[10]);
		XS(this.inet3p+"/addrtype", 			this.ori_inet1p[11]);
	},
	CreateBackupInet1p: function()
	{
		this.ori_inet1p = new Array();
		this.ori_inet1p[0] = XG(this.inet1p+"/addrtype");
		this.ori_inet1p[1] = XG(this.inet1p+"/ppp4/over");
		this.ori_inet1p[2] = XG(this.inet1p+"/ppp6/over");
		this.ori_inet1p[3] = XG(this.inet1p+"/ppp4/username");
		this.ori_inet1p[4] = XG(this.inet1p+"/ppp6/username");
		this.ori_inet1p[5] = XG(this.inet1p+"/ppp4/password");
		this.ori_inet1p[6] = XG(this.inet1p+"/ppp6/password");
		this.ori_inet1p[7] = XG(this.inet1p+"/ppp4/pppoe/servicename");
		this.ori_inet1p[8] = XG(this.inet1p+"/ppp6/pppoe/servicename");
		this.ori_inet1p[9] = XG(this.inet1p+"/ppp4/dialup/mode");
		this.ori_inet1p[10] = XG(this.inet1p+"/ppp6/dialup/mode");
		this.ori_inet1p[11] = XG(this.inet3p+"/addrtype");
	},	

	PreWANSettings: function()
	{
		var type = GetRadioValue("wanv6_mode");
		//XD(this.inet1p+"/ipv4");
		//XD(this.inet1p+"/ppp4");
		XS(this.inf1p+"/child", "");
		XS(this.inf1p+"/infnext", "");
		XS(this.inf3p+"/infprevious", "");
		XS(this.inf3p+"/infnext", "");
		XS(this.inf3p+"/inet", "INET-8");
		XS(this.inf3p+"/child", "");
		XS(this.inet3p+"/addrtype", "ipv6");
		XS(this.inet3p+"/ipv6/dhcpopt", "");
		XS(this.inet3p+"/ipv6/mode", "LL");
		XS(this.inf4p+"/active", "1");
		XS(this.inf4p+"/child", "");
		XS(this.inf4p+"/infprevious", "");
		XS(this.inf4p+"/infnext", "");
		XS(this.inf4p+"/child", "");
		XS(this.inet4p+"/ipv6/mode", "");
		XS(this.laninfp+"/inet",	"");

		if(this.wanTypes[this.currentWanType]=="AUTO")
		{
			XS(this.inf4p+"/defaultroute", "1");
			XS(this.inf4p+"/child", "LAN-4");
			XS(this.inet4p+"/ipv6/mode", "AUTO");
			XS(this.inet4p+"/ipv6/dhcpopt", "IA-NA+IA-PD");
			return true;
		}	

		switch (type)
		{
			case "PPPoE":
				/////////////////////////// prepare PPPoE settings ///////////////////////////
				var share_pppoe = document.getElementsByName("wiz_pppoe_sess_type")[0].checked ? true: false;
				if(share_pppoe)
				{
					XS(this.inf1p+"/child", "WAN-3");
					XS(this.inf3p+"/inet", "");
					XS(this.inf3p+"/infnext", "WAN-4");
					XS(this.inf4p+"/infprevious", "WAN-3");
				}
				else
				{
					XS(this.inf3p+"/infnext", "WAN-4");
					XS(this.inf3p+"/inet", "INET-8");
					XS(this.inf4p+"/infprevious", "WAN-3");
				}
				XS(this.inf4p+"/child", "LAN-4");
				XS(this.inet4p+"/ipv6/mode", "AUTO");
				XS(this.inet4p+"/ipv6/dhcpopt", "IA-PD");
	
				var stage = this.stages[this.currentStage];
				if(stage == "stage_ether_cfgv6")
				{
					this.LoadOriInet1p();
					if(share_pppoe)
					{
						XS(this.inet1p+"/addrtype", "ppp10");
						XS(this.inet1p+"/ppp4/over", "eth");
						XS(this.inet1p+"/ppp6/over", "eth");
						XS(this.inet1p+"/ppp4/username", OBJ("wiz_pppoe_usr").value);
						XS(this.inet1p+"/ppp6/username", OBJ("wiz_pppoe_usr").value);
						XS(this.inet1p+"/ppp4/password", OBJ("wiz_pppoe_passwd").value);
						XS(this.inet1p+"/ppp6/password", OBJ("wiz_pppoe_passwd").value);
						XS(this.inet1p+"/ppp4/pppoe/servicename", OBJ("wiz_pppoe_svc").value);
						XS(this.inet1p+"/ppp6/pppoe/servicename", OBJ("wiz_pppoe_svc").value);
						var dialup = "auto";
						XS(this.inet1p+"/ppp4/dialup/mode", dialup);
						XS(this.inet1p+"/ppp6/dialup/mode", dialup);
						XS(this.inet3p+"/addrtype", "ipv6");
					}
					else
					{
						//XS(this.inet1p+"/addrtype", "ppp4");
						XS(this.inet3p+"/addrtype", "ppp6");
						XS(this.inet3p+"/ppp6/username", OBJ("wiz_pppoe_usr").value);
						XS(this.inet3p+"/ppp6/password", OBJ("wiz_pppoe_passwd").value);
						XS(this.inet3p+"/ppp6/pppoe/servicename", OBJ("wiz_pppoe_svc").value);
						XS(this.inet3p+"/ppp6/over", "eth");
						XS(this.inet3p+"/ppp6/dialup/mode", "auto");
					}
				}
				else
				{
					XS(this.inet1p+"/addrtype", "ppp10");
					XS(this.inet1p+"/ppp4/over", "eth");
					XS(this.inet1p+"/ppp6/over", "eth");
					var value = XG(this.inet1p+"/ppp4/username");
					XS(this.inet1p+"/ppp6/username", value);
					var value = XG(this.inet1p+"/ppp4/password");
					XS(this.inet1p+"/ppp6/password", value);
					var value = XG(this.inet1p+"/ppp4/pppoe/servicename");
					XS(this.inet1p+"/ppp6/pppoe/servicename", value);
					var dialup = "auto";
					XS(this.inet1p+"/ppp4/dialup/mode", dialup);
					XS(this.inet1p+"/ppp6/dialup/mode", dialup);
					XS(this.inet3p+"/addrtype", "ipv6");
				}

				/* lan setting */
				XS(this.laninfp+"/active",	"1");
				XS(this.laninfp+"/inet",	"");
				/* lan dhcps6 pd setting */
				XS(this.dhcps6p+"/pd/enable", "1");
				XS(this.dhcps6p+"/pd/mode", "1"); /* 0: generic, 1: dlink */				
				break;

			case "STATIC":
				/////////////////////////// prepare STATIC IP settings ///////////////////////////
				XS(this.inet4p+"/addrtype",		"ipv6");
				XS(this.inet4p+"/ipv6/mode",		"STATIC");
				XS(this.inet4p+"/ipv6/ipaddr",	OBJ("wiz_static_wan_v6addr").value);
				XS(this.inet4p+"/ipv6/prefix",	OBJ("wiz_static_pfxlen").value);
				if (OBJ("wiz_static_gw").value != "") 
				{	XS(this.inf4p+"/defaultroute", "1");	}
				else
				{	XS(this.inf4p+"/defaultroute", "0");	}
				XS(this.inet4p+"/ipv6/gateway",	OBJ("wiz_static_gw").value);
				var dnscnt = 0;
				if (OBJ("wiz_static_pridns6").value != "")	dnscnt++;
				else {
					BODY.ShowAlert("<?echo i18n("Invalid primary dns.");?>");
					OBJ("wiz_static_pridns6").focus();
					return false;
				}
					
				if (OBJ("wiz_static_secdns6").value != "")	dnscnt++;
				XS(this.inet4p+"/ipv6/dns/count", dnscnt);
				XS(this.inet4p+"/ipv6/dns/entry:1",	OBJ("wiz_static_pridns6").value);
				XS(this.inet4p+"/ipv6/dns/entry:2",	OBJ("wiz_static_secdns6").value);
				/* static lan setting */
				XS(this.laninfp+"/active",	"1");
				XS(this.laninfp+"/inet",	"INET-7");
				XS(this.laninetp+"/ipv6/mode",	"STATIC");
				XS(this.laninetp+"/ipv6/ipaddr",	OBJ("wiz_static_lan_v6addr").value);
				XS(this.laninetp+"/ipv6/prefix",	"64");
				/* lan dhcps6 pd setting */
				XS(this.dhcps6p+"/pd/enable", "0");
				break;
			case "6RD":
				/////////////////////////// prepare TUNNEL settings ///////////////////////////
				XS(this.inf1p+"/infnext", "WAN-4");
				XS(this.inet3p+"/addrtype", "ipv6");
				XS(this.inf4p+"/child", "LAN-4");
				XS(this.inf4p+"/infprevious", "WAN-1");
				XS(this.inf4p+"/active", "1");
				XS(this.inf4p+"/defaultroute", "1");
				XS(this.inet4p+"/ipv6/mode", "6RD");
				XS(this.inet4p+"/ipv6/ipv6in4/rd/ipaddr", OBJ("wiz_6rd_prefix").value);
				XS(this.inet4p+"/ipv6/ipv6in4/rd/prefix", OBJ("wiz_6rd_pfxlen").value);
				XS(this.inet4p+"/ipv6/ipv6in4/rd/v4mask", OBJ("wiz_6rd_v4addr_mask").value);
				XS(this.inet4p+"/ipv6/ipv6in4/rd/relay", OBJ("wiz_6rd_relay").value);

				/* clean ppp10 addrtype */
				var wan1type = XG(this.inet1p+"/addrtype");
				if(wan1type == "ppp10")
				{
					XS(this.inet1p+"/addrtype", "ipv4");
					XS(this.inet1p+"/ipv4/static", "0");
				}

				/* dns */
				var dnscnt = 0;
				if (OBJ("wiz_6rd_pridns6").value != "")	dnscnt++;
				XS(this.inet4p+"/ipv6/dns/count", dnscnt);
				XS(this.inet4p+"/ipv6/dns/entry:1",	OBJ("wiz_6rd_pridns6").value);

				/* lan setting */
				XS(this.laninfp+"/active",	"1");
				XS(this.laninfp+"/inet",	"");
				/* lan dhcps6 pd setting */
				XS(this.dhcps6p+"/pd/enable", "0");

				break;
		}
		
		return true;
	},
	OnClickUsell: function()
	{
		if (OBJ("usell").checked)
		{
			OBJ("wiz_static_wan_v6addr").disabled 	= true;
			OBJ("wiz_static_pfxlen").disabled 		= true;
		}
		else
		{
			OBJ("wiz_static_wan_v6addr").disabled 	= false;
			OBJ("wiz_static_pfxlen").disabled 		= false;
		}		
	},
	ShowCurrentStage: function()
	{
		var i = 0;
		var type = "";
		
		if(this.stages[this.currentStage] == "stage_etherv6")
		{
			if(this.wanTypes[this.currentWanType]=="AUTO")
			{
				this.currentWanType = 0;
				SetRadioValue("wanv6_mode", this.wanTypes[this.currentWanType]);
			}
		}
		
		for (i=0; i<this.wanTypes.length; i++)
		{
			type = this.wanTypes[i];
			//if (type=="R_PPTP")			type = "PPTP";
			//else if (type=="R_PPPoE")	type = "PPPoE";
			//else if (type=="DHCPPLUS")	type = "DHCP";
			if(type!="AUTO")
				OBJ(type).style.display = "none";
		}
		for (i=0; i<this.stages.length; i++)
		{
			if (i==this.currentStage)
			{
				OBJ(this.stages[i]).style.display = "block";
				if (this.stages[this.currentStage]=="stage_ether_cfgv6")
				{
					type = this.wanTypes[this.currentWanType];
					//if (type=="R_PPTP")			type = "PPTP";
					//else if (type=="R_PPPoE")	type = "PPPoE";
					//else if (type=="DHCPPLUS")	type = "DHCP";
					OBJ(type).style.display = "block";
				}
			}
			else	OBJ(this.stages[i]).style.display = "none";
		}
		//if(this.wanTypes[this.currentWanType]=="DHCP" || this.wanTypes[this.currentWanType]=="DHCPPLUS" || this.wanTypes[this.currentWanType]=="STATIC" || this.wanTypes[this.currentWanType]=="R_PPPoE")	
		//if(this.wanTypes[this.currentWanType]=="STATIC")	
		//	OBJ("DNS").style.display = "none";
		//else	OBJ("DNS").style.display = "block";

		if (this.stages[this.currentStage]=="stage_wan_detectv6")	this.WanDetectPre();
		else	for (var j=0; j<this.wanDetectProcess.length; j++)	OBJ(this.wanDetectProcess[j]).style.display = "none";

		if (this.currentStage==0)	
			SetButtonDisabled("b_pre", true);
		else	
			SetButtonDisabled("b_pre", false);

		if (this.currentStage==this.stages.length-1)
		{
			SetButtonDisabled("b_next", true);
			SetButtonDisabled("b_send", false);
		}
		else
		{
			SetButtonDisabled("b_next", false);
			SetButtonDisabled("b_send", true);
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
		/*
		if (stage == "stage_passwd")
		{
			if (OBJ("wiz_passwd").value!=OBJ("wiz_passwd2").value)
			{
				BODY.ShowAlert("<?echo i18n("Please make the two passwords the same and try again.");?>");
				return false;
			}
		}
		else if (stage == "stage_adv_dns")
		{
			if (OBJ("en_adv_dns").checked)
			{
				OBJ("wiz_rpppoe_dns1").value = OBJ("wiz_static_dns1").value	= OBJ("dns1").value = this.opendns_adv_dns1;
				OBJ("wiz_rpppoe_dns2").value = OBJ("wiz_static_dns2").value	= OBJ("dns2").value = this.opendns_adv_dns2;
				OBJ("wiz_rpppoe_dns1").disabled = OBJ("wiz_static_dns1").disabled = OBJ("dns1").disabled = true;
				OBJ("wiz_rpppoe_dns2").disabled = OBJ("wiz_static_dns2").disabled = OBJ("dns2").disabled = true;
				OBJ("wiz_rpppoe_dns1").title = OBJ("wiz_static_dns1").title	= OBJ("dns1").title = "<?echo i18n("Locked by parental control");?>";
				OBJ("wiz_rpppoe_dns2").title = OBJ("wiz_static_dns2").title	= OBJ("dns2").title = "<?echo i18n("Locked by parental control");?>";				
			}
			else
			{
				OBJ("wiz_rpppoe_dns1").value = OBJ("wiz_static_dns1").value	= OBJ("dns1").value = "";
				OBJ("wiz_rpppoe_dns2").value = OBJ("wiz_static_dns2").value	= OBJ("dns2").value = "";
				OBJ("wiz_rpppoe_dns1").disabled = OBJ("wiz_static_dns1").disabled = OBJ("dns1").disabled = false;
				OBJ("wiz_rpppoe_dns2").disabled = OBJ("wiz_static_dns2").disabled = OBJ("dns2").disabled = false;
				OBJ("wiz_rpppoe_dns1").title = OBJ("wiz_static_dns1").title	= OBJ("dns1").title = "";
				OBJ("wiz_rpppoe_dns2").title = OBJ("wiz_static_dns2").title	= OBJ("dns2").title = "";											
			}		
		}		
		else if (stage == "stage_ether_cfgv6")
		*/
		if (stage == "stage_etherv6")
		{
			var v4mode = XG(this.inet1p+"/ipv4/ipv4in6/mode");
			var v6mode = GetRadioValue("wanv6_mode");
			if(v4mode == "dslite" && v6mode=="6RD")
			{
				alert("<?echo i18n("Can't select this mode since IPv4 Internet Connection is 'DS-Lite'.");?>");
				return;
			}
		}
		
		if (stage == "stage_ether_cfgv6")
		{
			if(!this.PreWANSettings()) return;
			var type = this.wanTypes[this.currentWanType];
			CheckWANSettings(type);
		}
		else
		{
			this.SetStage(1);
			this.ShowCurrentStage();
		}
		//this.SetStage(1);
		//this.ShowCurrentStage();
	},
	OnClickCancel: function()
	{
		if (!COMM_IsDirty(false)||confirm("<?echo i18n("Do you want to abandon all changes you made to this wizard?");?>"))
			self.location.href = "./bsc_internetv6.php";
	},
	OnChangeWanv6Type: function(type)
	{
		for (var i=0; i<this.wanTypes.length; i++)
		{
			//if (type=="R_PPPoE")	OBJ("R_PPPoE").style.display = "block";
			//else					OBJ("R_PPPoE").style.display = "none";
			
			//if (type=="DHCPPLUS")	OBJ("DHCPPLUS").style.display = "block";
			//else					OBJ("DHCPPLUS").style.display = "none";

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
		var wan1_addrtype = XG(this.inet1p+"/addrtype");
		var wan3_addrtype = XG(this.inet3p+"/addrtype");
		var wan4_addrtype = XG(this.inet4p+"/addrtype");
		var type = null;
		var found= 0;
	
		if(wan1_addrtype=="ppp10")
		{
			if (XG(this.inet1p+"/ppp4/over")=="eth")
			{	
				if (XG(this.inf2p+"/active")!="1")
				{
					this.isppp10 = 1;
					type = "PPPoE";
				}
			}
		}
		else if(wan3_addrtype=="ppp6")	
		{
			this.isppp6 = 1;
			type = "PPPoE";
		}
		else if(wan4_addrtype=="ipv6")	type = XG(this.inet4p+"/ipv6/mode");

		for (var i=0; i<this.wanTypes.length; i++)
		{
			if (this.wanTypes[i]==type)
			{	
				found = 1;
				this.currentWanType = i;
			}
		}

		/* Not PPPoE, STATIC, 6RD, so we use PPPoE as default */ 
		if(found==0) this.currentWanType = 0;
	},
	OnChangePPPoESessType: function()
	{
		//var disable = document.getElementsByName("wiz_pppoe_sess_type")[0].checked ? true: false;
	},
	WanDetectPre: function()
	{
		OBJ("wantypev6_unkown").style.display = "none";
		OBJ("wan_detectv6").style.display = "block";
		this.wanDetectNum = 0;
		this.WanDetectv6("WANDETECTV6");
	},
	WanDetectv6: function(action)
	{
		if(this.stages[this.currentStage]!=="stage_wan_detectv6")	return;
		var ajaxObj = GetAjaxObj(action);
		ajaxObj.createRequest();
		ajaxObj.onCallback = function (xml)
		{
			ajaxObj.release();
			PAGE.WanDetectCallback(xml.Get("/wandetectreport/result"), xml.Get("/wandetectreport/reason"));
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("wandetect.php", "action="+action);
		AUTH.UpdateTimeout();
	},
	WanDetectCallback: function(result, reason)
	{
		if(this.stages[this.currentStage]!=="stage_wan_detectv6")	return;
		switch (result)
		{
			case "OK":
				setTimeout('PAGE.WanDetectv6("WANTYPERESULT")', 20000);
				break;
			case "PPPoE":
				for(var i=0; i < this.stages.length; i++)	if(this.stages[i]==="stage_finishv6")	this.currentStage=i;
				for(var i=0; i < this.wanTypes.length; i++)	if(this.wanTypes[i]==="PPPoE")	this.currentWanType=i;
				SetRadioValue("wanv6_mode", "PPPoE");	
				OBJ("mainform").setAttribute("modified", "true");	
				this.PreWANSettings();
				this.ShowCurrentStage();	
				break;
			case "STATEFUL":
			case "STATELESS":
				for(var i=0; i < this.stages.length; i++)	if(this.stages[i]==="stage_finishv6")	this.currentStage=i;
				for(var i=0; i < this.wanTypes.length; i++)	if(this.wanTypes[i]==="AUTO")	this.currentWanType=i;
				//SetRadioValue("wanv6_mode", "PPPoE");	
				OBJ("mainform").setAttribute("modified", "true");	
				this.PreWANSettings();
				this.ShowCurrentStage();	
				break;
			case "None":
			case "unknown":
				OBJ("wan_detectv6").style.display = "none";
				OBJ("wantypev6_unkown").style.display = "block";			
			case "":
				if (this.wanDetectNum < 2)
				{
					setTimeout('PAGE.WanDetectv6("WANTYPERESULT")', 10000);
					this.wanDetectNum++;
				}
				else
				{
					OBJ("wan_detectv6").style.display = "none";
					OBJ("wantypev6_unkown").style.display = "block";
				}
				break;
			case "FAIL":
			default:
				for(var i=0; i < this.stages.length; i++)	if(this.stages[i]==="stage_ether")	this.currentStage=i;
				this.ShowCurrentStage();				
				break;
		}
	}			
}

function SetButtonDisabled(name, disable)
{
	var button = document.getElementsByName(name);
	for (i=0; i<button.length; i++)	button[i].disabled = disable;
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
function SetDNSAddress(path, dns1, dns2)
{
	var cnt = 0;
	var dns = new Array (false, false);
	if (dns1!="0.0.0.0"&&dns1!="") {dns[0] = true; cnt++;}
	if (dns2!="0.0.0.0"&&dns2!="") {dns[1] = true; cnt++;}
	XS(path+"/count", cnt);
	if (dns[0]) XS(path+"/entry", dns1);
	if (dns[1]) XS(path+"/entry:2", dns2);
}

function CheckWANSettings(type)
{
	PXML.IgnoreModule("RUNTIME.PHYINF");
	PXML.IgnoreModule("RUNTIME.INF.WAN-1");
	PXML.IgnoreModule("RUNTIME.INF.WAN-4");
	PXML.IgnoreModule("WAN");
	//PXML.CheckModule("INET.WAN-1", null, "ignore", "ignore");
	//PXML.CheckModule("INET.WAN-2", null, "ignore", "ignore");
	switch (type)
	{
		case "PPPoE":
			/*
			if (PAGE.wanTypes[PAGE.currentWanType]=="R_PPPoE" && document.getElementsByName("wiz_rpppoe_conn_mode")[1].checked)
			{
				if (OBJ("wiz_rpppoe_dns1").value==="" || OBJ("wiz_rpppoe_dns1").value==="0.0.0.0")
				{
					BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address.");?>");
					return false;
				}
			}
			*/
			break;
		case "STATIC":
			/*
			if (OBJ("wiz_static_dns1").value==="" || OBJ("wiz_static_dns1").value==="0.0.0.0")
			{
				BODY.ShowAlert("<?echo i18n("Invalid Primary DNS address.");?>");
				return false;
			}
			*/
			break;
	}

	AUTH.UpdateTimeout();
	//PXML.doc.dbgdump();
	COMM_CallHedwig(PXML.doc, 
		function (xml)
		{
			switch (xml.Get("/hedwig/result"))
			{
				case "OK":
					//BODY.OnSubmit();
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

function Service(svc)
{	
	var banner = "<?echo i18n("Rebooting");?>...";
	var msgArray = ["<?echo i18n("If you changed the IP address of the router you will need to change the IP address in your browser before accessing the configuration web page again.");?>"];
	var delay = 10;
	var sec = <?echo query("/runtime/device/bootuptime");?> + delay;
	var url = null;
	var ajaxObj = GetAjaxObj("SERVICE");
	if (svc=="FRESET")		url = "http://192.168.0.1/index.php";
	else if (svc=="REBOOT")	url = "http://<?echo $_SERVER["HTTP_HOST"];?>/index.php";
	else					return false;
	ajaxObj.createRequest();
	ajaxObj.onCallback = function (xml)
	{
		ajaxObj.release();
		if (xml.Get("/report/result")!="OK")
			BODY.ShowAlert("Internal ERROR!\nEVENT "+svc+": "+xml.Get("/report/message"));
		else
			BODY.ShowCountdown(banner, msgArray, sec, url);
	}
	ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxObj.sendRequest("service.cgi", "EVENT="+svc);
}
</script>
