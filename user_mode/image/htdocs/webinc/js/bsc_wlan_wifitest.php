<?include "/htdocs/phplib/phyinf.php";?>
<script type="text/javascript">
function Page() {}
Page.prototype =
{
	//services: "WIFI.WLAN-1,RUNTIME.PHYINF.WLAN-1",
	//services: "WIFI.WLAN-1,RUNTIME.PHYINF.WLAN-1,WIFI.WLAN-2,RUNTIME.PHYINF.WLAN-2",
	services: "WIFI.PHYINF,RUNTIME.PHYINF",
	OnLoad: function() {},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return false; },
	InitValue: function(xml)
	{
		PXML.doc = xml;
		//if(!this.Initial("WIFI.WLAN-1", "RUNTIME.PHYINF.WLAN-1")) return false; 
		//if(!this.Initial("WIFI.WLAN-2", "RUNTIME.PHYINF.WLAN-2")) return false;
		if(!this.Initial("WLAN-1","WIFI.PHYINF","RUNTIME.PHYINF")) return false; 
		if(!this.Initial("WLAN-2","WIFI.PHYINF","RUNTIME.PHYINF")) return false; 
		
		return true;
	},
	PreSubmit: function()
	{
		if(!this.SaveXML("WLAN-1","WIFI.PHYINF","RUNTIME.PHYINF")) return null; 
		if(!this.SaveXML("WLAN-2","WIFI.PHYINF","RUNTIME.PHYINF")) return null; 
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	wifip: null,
	phyinf: null,
	runtime_phyinf: null,
	sec_type: null,
	sec_type_Aband: null,
	wlanMode: null,
	bandWidth: null,
	shortGuard: null,
	wps: true,

	str_Aband: null,
	feature_nosch: null,
		
	Initial: function(wlan_phyinf,wifi_phyinf,runtime_phyinf)
	{
		this.phyinf = this.wifip = PXML.FindModule(wifi_phyinf);
		this.runtime_phyinf = PXML.FindModule(runtime_phyinf);

		if (!this.wifip)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		this.phyinf = GPBT(this.phyinf, "phyinf", "uid",wlan_phyinf, false);

		var wifi_profile = XG(this.phyinf+"/wifi");
		var freq = XG(this.phyinf+"/media/freq");
		this.wifip = GPBT(this.wifip+"/wifi", "entry", "uid", wifi_profile, false);
		
		if(freq == "5")
			str_Aband = "_Aband";
		else
			str_Aband = "";
		
		//this.runtime_phyinf += "/runtime/phyinf/" + wlan_phyinf;
		this.runtime_phyinf = GPBT(this.runtime_phyinf, "phyinf","uid",wlan_phyinf, false);
		
		this.wlanMode = XG(this.phyinf+"/media/wlmode");
		OBJ("en_wifi"+str_Aband).checked = COMM_ToBOOL(XG(this.phyinf+"/active"));
		
		<? if($FEATURE_NOSCH!="1")echo 'this.feature_nosch=0;'; else echo 'this.feature_nosch=1;'; ?>
		
		if(this.feature_nosch==0)
			COMM_SetSelectValue(OBJ("sch"+str_Aband), XG(this.phyinf+"/schedule"));
		
		OBJ("ssid"+str_Aband).value = XG(this.wifip+"/ssid");
		OBJ("auto_ch"+str_Aband).checked = (XG(this.phyinf+"/media/channel")=="0")? true : false;
		if (OBJ("auto_ch"+str_Aband).checked)
			COMM_SetSelectValue(OBJ("channel"+str_Aband), XG(this.runtime_phyinf+"/media/channel"));
		else
			COMM_SetSelectValue(OBJ("channel"+str_Aband), XG(this.phyinf+"/media/channel"));
		if (this.wlanMode=="n")
		{
			this.bandWidth	= XG(this.phyinf+"/media/dot11n/bandwidth");
			this.shortGuard	= XG(this.phyinf+"/media/dot11n/guardinterval");
			DrawTxRateList(this.bandWidth, this.shortGuard);
			var rate = XG(this.phyinf+"/media/dot11n/mcs/index");
			if (rate=="") rate = "-1";
			COMM_SetSelectValue(OBJ("txrate"+str_Aband), rate);
		}
		OBJ("en_wmm"+str_Aband).checked = COMM_ToBOOL(XG(this.phyinf+"/media/wmm/enable"));
		if (/n/.test(this.wlanMode))
			OBJ("en_wmm"+str_Aband).disabled = true;
		OBJ("suppress"+str_Aband).checked = COMM_ToBOOL(XG(this.wifip+"/ssidhidden"));
		
		if(str_Aband == "")	//g band
		{
			if (!OBJ("en_wifi"+str_Aband).checked)
				this.sec_type = "";
			else if (XG(this.wifip+"/encrtype")=="WEP")
				this.sec_type = "wep";
			else if (XG(this.wifip+"/authtype")=="WPAPSK" ||
				 XG(this.wifip+"/authtype")=="WPAEAP")
				this.sec_type = "WPA"
			else if (XG(this.wifip+"/authtype")=="WPA2PSK" ||
				 XG(this.wifip+"/authtype")=="WPA2EAP")
				this.sec_type = "WPA2"
			else if (XG(this.wifip+"/authtype")=="WPA+2PSK" ||
				 XG(this.wifip+"/authtype")=="WPA+2EAP")
				this.sec_type = "WPA+2"
			else
				this.sec_type = "";
				
			COMM_SetSelectValue(OBJ("security_type"+str_Aband), this.sec_type);
		}
		else //a band
		{
			if (!OBJ("en_wifi"+str_Aband).checked)
				this.sec_type_Aband = "";
			else if (XG(this.wifip+"/encrtype")=="WEP")
				this.sec_type_Aband = "wep";
			else if (/WPA/.test(XG(this.wifip+"/authtype")))
				this.sec_type_Aband = "wpa";
			else
				this.sec_type_Aband = "";
			
			COMM_SetSelectValue(OBJ("security_type"+str_Aband), this.sec_type_Aband);
		}
		
		///////////////// initial WEP /////////////////
		var auth = XG(this.wifip+"/authtype");
		var len = (XG(this.wifip+"/nwkey/wep/size")=="")? "64" : XG(this.wifip+"/nwkey/wep/size");
		var defkey = (XG(this.wifip+"/nwkey/wep/defkey")=="")? "1" : XG(this.wifip+"/nwkey/wep/defkey");
		this.wps = COMM_ToBOOL(XG(this.wifip+"/wps/enable"));
		OBJ("auth_type"+str_Aband).disabled = this.wps;
		if (auth!="SHARED") auth = "OPEN";
		COMM_SetSelectValue(OBJ("auth_type"+str_Aband),	auth);
		COMM_SetSelectValue(OBJ("wep_key_len"+str_Aband),	len);
		COMM_SetSelectValue(OBJ("wep_def_key"+str_Aband),	defkey);
		for (var i=1; i<5; i++)
			OBJ("wep_"+len+"_"+i+str_Aband).value = XG(this.wifip+"/nwkey/wep/key:"+i);
		///////////////// initial WPA /////////////////
		var cipher = XG(this.wifip+"/encrtype");
		var type = null;
		switch (XG(this.wifip+"/authtype"))
		{
			case "WPA":
			case "WPA2":
			case "WPA+2":
				type = "eap";
				break;
			default:
				type = "psk";
		}
		switch (cipher)
		{
			case "TKIP":
			case "AES":
				break;
			default:
				cipher = "TKIP+AES";
		}
		COMM_SetSelectValue(OBJ("cipher_type"+str_Aband), cipher);
		COMM_SetSelectValue(OBJ("psk_eap"+str_Aband), type);

		OBJ("wpapsk"+str_Aband).value	= XG(this.wifip+"/nwkey/psk/key");
		OBJ("srv_ip"+str_Aband).value	= XG(this.wifip+"/nwkey/eap/radius");
		OBJ("srv_port"+str_Aband).value	= XG(this.wifip+"/nwkey/eap/port");
		OBJ("srv_sec"+str_Aband).value	= XG(this.wifip+"/nwkey/eap/secret");
		
		this.OnChangeSecurityType(str_Aband);
		this.OnChangeWEPKey(str_Aband);
		this.OnChangeWPAAuth(str_Aband);
		
		this.OnClickEnWLAN(str_Aband);
		this.OnClickEnAutoChannel(str_Aband);
		return true;
	},
	SaveXML: function(wlan_phyinf,wifi_phyinf,runtime_phyinf)
	{
		this.phyinf = this.wifip = PXML.FindModule(wifi_phyinf);
		this.phyinf = GPBT(this.phyinf,"phyinf","uid",wlan_phyinf,false);
		var wifi_profile = XG(this.phyinf+"/wifi");
		this.wifip = GPBT(this.wifip+"/wifi", "entry", "uid", wifi_profile, false);
		var freq = XG(this.phyinf+"/media/freq");

		if(freq == "5")
			str_Aband = "_Aband";
		else
			str_Aband = "";
		
		if (OBJ("en_wifi"+str_Aband).checked)
			XS(this.phyinf+"/active", "1");
		else
		{
			XS(this.phyinf+"/active", "0");
			return true;
		}

		if(this.feature_nosch==0)
			XS(this.phyinf+"/schedule",	OBJ("sch"+str_Aband).value);
		
		XS(this.wifip+"/ssid",		OBJ("ssid"+str_Aband).value);

		if (OBJ("auto_ch"+str_Aband).checked)
			XS(this.phyinf+"/media/channel", "0");
		else
			XS(this.phyinf+"/media/channel", OBJ("channel"+str_Aband).value);
		if (OBJ("txrate"+str_Aband).value=="-1")
		{
			XS(this.phyinf+"/media/dot11n/mcs/auto", "1");
			XS(this.phyinf+"/media/dot11n/mcs/index", "");
		}
		else
		{
			XS(this.phyinf+"/media/dot11n/mcs/auto", "0");
			XS(this.phyinf+"/media/dot11n/mcs/index", OBJ("txrate"+str_Aband).value);
		}
		XS(this.phyinf+"/media/wmm/enable",	SetBNode(OBJ("en_wmm"+str_Aband).checked));
		XS(this.wifip+"/ssidhidden",		SetBNode(OBJ("suppress"+str_Aband).checked));
		if (OBJ("ssid"+str_Aband).getAttribute("modified")=="true"||OBJ("security_type"+str_Aband).getAttribute("modified")=="true")
			XS(this.wifip+"/wps/configured", "1");
		if (OBJ("security_type"+str_Aband).value=="wep")
		{
			if (OBJ("auth_type"+str_Aband).value=="SHARED")
				XS(this.wifip+"/authtype", "SHARED");
			else
				XS(this.wifip+"/authtype", "OPEN");
			XS(this.wifip+"/encrtype",			"WEP");
			XS(this.wifip+"/nwkey/wep/size",	"");
			XS(this.wifip+"/nwkey/wep/ascii",	"");
			XS(this.wifip+"/nwkey/wep/defkey",	OBJ("wep_def_key"+str_Aband).value);
			for (var i=1, len=OBJ("wep_key_len"+str_Aband).value; i<5; i++)
			{
				if (i==OBJ("wep_def_key"+str_Aband).value)
					XS(this.wifip+"/nwkey/wep/key:"+i, OBJ("wep_"+len+"_"+i+str_Aband).value);
				else
					XS(this.wifip+"/nwkey/wep/key:"+i, "");
			}
		}
		else if (OBJ("security_type"+str_Aband).value=="WPA"  ||
				 OBJ("security_type"+str_Aband).value=="WPA2" ||
				 OBJ("security_type"+str_Aband).value=="WPA+2")
		{
			XS(this.wifip+"/encrtype", OBJ("cipher_type"+str_Aband).value);
			if (OBJ("psk_eap"+str_Aband).value=="psk")
			{
				XS(this.wifip+"/authtype",				OBJ("security_type"+str_Aband).value+"PSK");
				XS(this.wifip+"/nwkey/psk/passphrase",	"");
				XS(this.wifip+"/nwkey/psk/key",			OBJ("wpapsk"+str_Aband).value);
			}
			else
			{
				XS(this.wifip+"/authtype",			OBJ("security_type"+str_Aband).value);
				XS(this.wifip+"/nwkey/eap/radius",	OBJ("srv_ip"+str_Aband).value);
				XS(this.wifip+"/nwkey/eap/port",	OBJ("srv_port"+str_Aband).value);
				XS(this.wifip+"/nwkey/eap/secret",	OBJ("srv_sec"+str_Aband).value);
			}
		}
		else
		{
			XS(this.wifip+"/authtype", "OPEN");
			XS(this.wifip+"/encrtype", "NONE");
		}
		//PXML.IgnoreModule("WIFI.PHYINF");
		PXML.IgnoreModule("RUNTIME.PHYINF");
		//PXML.IgnoreModule("RUNTIME.PHYINF.WLAN-2");
		return true;
	},
	
	OnClickEnWLAN: function(str_Aband)
	{
		if (AUTH.AuthorizedGroup >= 100) return;
		if (OBJ("en_wifi"+str_Aband).checked)
		{
			if(this.feature_nosch==0)
			{
				OBJ("sch"+str_Aband).disabled		= false;
				OBJ("go2sch"+str_Aband).disabled	= false;
			}
			
			OBJ("ssid"+str_Aband).disabled	= false;
			OBJ("auto_ch"+str_Aband).disabled	= false;
			if (!OBJ("auto_ch"+str_Aband).checked) OBJ("channel"+str_Aband).disabled = false;
			OBJ("txrate"+str_Aband).disabled	= false;
			if (/n/.test(this.wlanMode)) OBJ("en_wmm"+str_Aband).disabled = true;
			OBJ("suppress"+str_Aband).disabled= false;
			OBJ("security_type"+str_Aband).disabled= false;
			
			if(str_Aband == "")
				COMM_SetSelectValue(OBJ("security_type"+str_Aband), this.sec_type);
			else
				COMM_SetSelectValue(OBJ("security_type"+str_Aband), this.sec_type_Aband);
			this.OnChangeSecurityType(str_Aband);
		}
		else
		{
			if(this.feature_nosch==0)
			{
				OBJ("sch"+str_Aband).disabled		= true;
				OBJ("go2sch"+str_Aband).disabled	= true;
			}
			
			OBJ("ssid"+str_Aband).disabled	= true;
			OBJ("auto_ch"+str_Aband).disabled	= true;
			OBJ("channel"+str_Aband).disabled	= true;
			OBJ("txrate"+str_Aband).disabled	= true;
			if (/n/.test(this.wlanMode)) OBJ("en_wmm"+str_Aband).disabled = true;
			OBJ("suppress"+str_Aband).disabled= true;
			OBJ("security_type"+str_Aband).disabled= true;
			
			if(str_Aband == "")
				this.sec_type = OBJ("security_type"+str_Aband).value;
			else 
				this.sec_type_Aband = OBJ("security_type"+str_Aband).value;

			COMM_SetSelectValue(OBJ("security_type"+str_Aband), "");
			this.OnChangeSecurityType(str_Aband);
		}
	},
	OnClickEnAutoChannel: function(str_Aband)
	{
		if (OBJ("auto_ch"+str_Aband).checked || !OBJ("en_wifi"+str_Aband).checked)
			OBJ("channel"+str_Aband).disabled = true;
		else
			OBJ("channel"+str_Aband).disabled = false;
	},
	OnChangeSecurityType: function(str_Aband)
	{
		switch (OBJ("security_type"+str_Aband).value)
		{
			case "":
				OBJ("wep"+str_Aband).style.display = "none";
				OBJ("wpa"+str_Aband).style.display = "none";
				OBJ("pad").style.display = "block";
				break;
			case "wep":
				OBJ("wep"+str_Aband).style.display = "block";
				OBJ("wpa"+str_Aband).style.display = "none";
				OBJ("pad").style.display = "none";
				break;
			case "WPA":
			case "WPA2":
			case "WPA+2":
				OBJ("wep"+str_Aband).style.display = "none";
				OBJ("wpa"+str_Aband).style.display = "block";
				OBJ("pad").style.display = "none";
		}
	},
	OnChangeWEPKey: function(str_Aband)
	{
		var no = S2I(OBJ("wep_def_key"+str_Aband).value) - 1;
		
		switch (OBJ("wep_key_len"+str_Aband).value)
		{
			case "64":
				OBJ("wep_64"+str_Aband).style.display = "block";
				OBJ("wep_128"+str_Aband).style.display = "none";
				SetDisplayStyle(null, "wepkey_64"+str_Aband, "none");
				document.getElementsByName("wepkey_64"+str_Aband)[no].style.display = "inline";
				break;
			case "128":
				OBJ("wep_64"+str_Aband).style.display = "none";
				OBJ("wep_128"+str_Aband).style.display = "block";
				SetDisplayStyle(null, "wepkey_128"+str_Aband, "none");
				document.getElementsByName("wepkey_128"+str_Aband)[no].style.display = "inline";
		}
	},
	OnChangeWPAAuth: function(str_Aband)
	{
		switch (OBJ("psk_eap"+str_Aband).value)
		{
			case "psk":
				SetDisplayStyle("div", "psk"+str_Aband, "block");
				SetDisplayStyle("div", "eap"+str_Aband, "none");
				break;
			case "eap":
				SetDisplayStyle("div", "psk"+str_Aband, "none");
				SetDisplayStyle("div", "eap"+str_Aband, "block");
		}
	}
}

function SetBNode(value)
{
	if (COMM_ToBOOL(value))
		return "1";
	else
		return "0";
}

function SetDisplayStyle(tag, name, style)
{
	if (tag)	var obj = GetElementsByName_iefix(tag, name);
	else		var obj = document.getElementsByName(name);
	for (var i=0; i<obj.length; i++)
	{
		obj[i].style.display = style;
	}
}
function GetElementsByName_iefix(tag, name)
{
	var elem = document.getElementsByTagName(tag);
	var arr = new Array();
	for(i = 0,iarr = 0; i < elem.length; i++)
	{
		att = elem[i].getAttribute("name");
		if(att == name)
		{
			arr[iarr] = elem[i];
			iarr++;
		}
	}
	return arr;
}

function DrawTxRateList(bw, sgi)
{
	var listOptions = null;
	var cond = bw+":"+sgi;
	switch(cond)
	{
	case "20:800":
		listOptions = new Array("0 - 6.5","1 - 13.0","2 - 19.5","3 - 26.0","4 - 39.0","5 - 52.0","6 - 58.5","7 - 65.0"<?
						$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", "WLAN-1", 0);
						$ms = query($p."/media/multistream");
						if ($ms != "1T1R")
							echo ',"8 - 13.0","9 - 26.0","10 - 39.0","11 - 52.0","12 - 78.0","13 - 104.0","14 - 117.0","15 - 130.0"';
						?>);
		break;
	case "20:400":
		listOptions = new Array("0 - 7.2","1 - 14.4","2 - 21.7","3 - 28.9","4 - 43.3","5 - 57.8","6 - 65.0","7 - 72.0"<?
						if ($ms != "1T1R")
							echo ',"8 - 14.444","9 - 28.889","10 - 43.333","11 - 57.778","12 - 86.667","13 - 115.556","14 - 130.000","15 - 144.444"';
						?>);
		break;
	case "20+40:800":
		listOptions = new Array("0 - 13.5","1 - 27.0","2 - 40.5","3 - 54.0","4 - 81.0","5 - 108.0","6 - 121.5","7 - 135.0"<?
						if ($ms != "1T1R")
							echo ',"8 - 27.0","9 - 54.0","10 - 81.0","11 - 108.0","12 - 162.0","13 - 216.0","14 - 243.0","15 - 270.0"';
						?>);
		break;
	case "20+40:400":
		listOptions = new Array("0 - 15.0","1 - 30.0","2 - 45.0","3 - 60.0","4 - 90.0","5 - 120.0","6 - 135.0","7 - 150.0"<?
						if ($ms != "1T1R")
							echo ',"8 - 30.0","9 - 60.0","10 - 90.0","11 - 120.0","12 - 180.0","13 - 240.0","14 - 270.0","15 - 300.0"';
						?>);
		break;
	}

	for(var idx=0; idx<OBJ("txrate"+str_Aband).length;)
	{
		OBJ("txrate"+str_Aband).remove(idx);
	}
	for(var idx=0; idx<listOptions.length; idx++)
	{
		var item = document.createElement("option");
		item.value = idx;
		item.text = listOptions[idx];
		try		{ OBJ("txrate"+str_Aband).add(item, null); }
		catch(e){ OBJ("txrate"+str_Aband).add(item); }
	}
}
</script>
