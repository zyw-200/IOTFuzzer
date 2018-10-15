<script type="text/javascript">
function Page() {}
Page.prototype =
{
	//services: "WIFI.WLAN-1,WIFI.WLAN-2",
	services: "WIFI.PHYINF",
	
	OnLoad: function() {},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return false; },
	InitValue: function(xml)
	{
		PXML.doc = xml;
		if (!this.Initial("WLAN-1", "WIFI.PHYINF")) return false;
		if (!this.Initial("WLAN-2", "WIFI.PHYINF")) return false;
		return true;
	},
	PreSubmit: function()
	{
		if (!this.SaveXML("WLAN-1", "WIFI.PHYINF")) return null;
		if (!this.SaveXML("WLAN-2", "WIFI.PHYINF")) return null;
		return PXML.doc;
	},
	IsDirty: null,
	str_Aband: null,
	
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	phyinf: null,
	Initial: function(wlan_phyinf,wifi_phyinf)
	{
		this.phyinf = PXML.FindModule(wifi_phyinf);
		if (!this.phyinf)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		
		this.phyinf = GPBT(this.phyinf, "phyinf", "uid", wlan_phyinf, false);
		var freq = XG(this.phyinf+"/media/freq");
		if(freq == "5")
			str_Aband = "_Aband";
		else
			str_Aband = "";
		
		this.phyinf += "/media";
		COMM_SetSelectValue(OBJ("tx_power"+str_Aband), XG(this.phyinf+"/txpower"));
		//COMM_SetSelectValue(OBJ("wlan_mode"+str_Aband), XG(this.phyinf+"/wlmode"));
		//COMM_SetSelectValue(OBJ("bw"+str_Aband), XG(this.phyinf+"/dot11n/bandwidth"));

		SetRadioValue("preamble"+str_Aband, XG(this.phyinf+"/preamble"));
		
		OBJ("beacon"+str_Aband).value	= XG(this.phyinf+"/beacon");
		OBJ("rts"+str_Aband).value	= XG(this.phyinf+"/rtsthresh");
		OBJ("frag"+str_Aband).value	= XG(this.phyinf+"/fragthresh");
		OBJ("dtim"+str_Aband).value	= XG(this.phyinf+"/dtim");
		OBJ("sgi"+str_Aband).checked	= COMM_EqNUMBER(XG(this.phyinf+"/dot11n/guardinterval"), 400);
		if (/n/.test(XG(this.phyinf+"/wlmode")))	OBJ("sgi"+str_Aband).disabled	= false;
		else										OBJ("sgi"+str_Aband).disabled	= true;
		return true;
	},
	SaveXML: function(wlan_phyinf , wifi_phyinf)
	{
		this.phyinf = PXML.FindModule(wifi_phyinf);
		if (!this.phyinf)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		
		this.phyinf = GPBT(this.phyinf, "phyinf", "uid", wlan_phyinf, false);
		var freq = XG(this.phyinf+"/media/freq");
		if(freq == "5")
			str_Aband = "_Aband";
		else
			str_Aband = "";

		this.phyinf += "/media";
		if (!TEMP_IsDigit(OBJ("beacon"+str_Aband).value))
		{
			BODY.ShowAlert("<?echo i18n("The input beacon interval is invalid.");?>");
			OBJ("beacon"+str_Aband).focus();
			return null;
		}
		else if (!TEMP_IsDigit(OBJ("rts"+str_Aband).value))
		{
			BODY.ShowAlert("<?echo i18n("The input RTS threshold is invalid.");?>");
			OBJ("rts"+str_Aband).focus();
			return null;
		}
		else if (!TEMP_IsDigit(OBJ("frag"+str_Aband).value))
		{
			BODY.ShowAlert("<?echo i18n("The input fragmentation is invalid.");?>");
			OBJ("frag"+str_Aband).focus();
			return null;
		}
		else if (!TEMP_IsDigit(OBJ("dtim"+str_Aband).value))
		{
			BODY.ShowAlert("<?echo i18n("The input DTIM interval is invalid.");?>");
			OBJ("dtim"+str_Aband).focus();
			return null;
		}
		XS(this.phyinf+"/txpower",		OBJ("tx_power"+str_Aband).value);
		XS(this.phyinf+"/beacon",		OBJ("beacon"+str_Aband).value);
		XS(this.phyinf+"/rtsthresh",	OBJ("rts"+str_Aband).value);
		XS(this.phyinf+"/fragthresh",	OBJ("frag"+str_Aband).value);
		XS(this.phyinf+"/dtim",			OBJ("dtim"+str_Aband).value);
		
		XS(this.phyinf+"/preamble",		GetRadioValue("preamble"+str_Aband));
//		XS(this.phyinf+"/ctsmode",		GetRadioValue("cts"+str_Aband));
//		XS(this.phyinf+"/wlmode",		OBJ("wlan_mode"+str_Aband).value);
		return true;
	},
	GetIP: function(mac)
	{
		var path = PXML.doc.GetPathByTarget(this.inf, "entry", "mac", mac.toLowerCase(), false);
		return XG(path+"/ipaddr");
	}
}

function GetRadioValue(name)
{
	var obj = document.getElementsByName(name);
	for (var i=0; i<obj.length; i++)
	{
		if (obj[i].checked)	return obj[i].value;
	}
}
function SetRadioValue(name, value)
{
	var obj = document.getElementsByName(name);
	for (var i=0; i<obj.length; i++)
	{
		if (obj[i].value==value)
		{
			obj[i].checked = true;
			break;
		}
	}
}
</script>
