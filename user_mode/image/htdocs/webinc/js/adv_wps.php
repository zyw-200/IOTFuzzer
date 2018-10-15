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
		this.wifi_phyinf = PXML.FindModule("WIFI.PHYINF");
		this.phyinf = GPBT(this.wifi_phyinf, "phyinf", "uid","WLAN-1", false);
		this.phyinf2 = GPBT(this.wifi_phyinf, "phyinf", "uid","WLAN-2", false);
		
		this.wifip = XG(this.phyinf+"/wifi");
		this.wifip2 = XG(this.phyinf2+"/wifi");
		
		if (!this.wifip)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		
		this.wifip = GPBT(this.wifi_phyinf+"/wifi", "entry", "uid", this.wifip, false);
		this.wifip2 = GPBT(this.wifi_phyinf+"/wifi", "entry", "uid", this.wifip2, false);
		
		var wps_enable 		= XG(this.wifip+"/wps/enable");
		var wps_configured  = XG(this.wifip+"/wps/configured");
		var str_info = "";
		
		OBJ("en_wps").checked = COMM_ToBOOL(wps_enable);
		if (XG(this.wifip+"/wps/pin")=="")
			this.curpin = OBJ("pin").innerHTML = this.defpin;
		else
			this.curpin = OBJ("pin").innerHTML = XG(this.wifip+"/wps/pin");
		this.OnClickEnWPS();
		
		if(wps_enable == "1") 		str_info =  "Enabled"; else str_info = "Disabled";
		if(wps_configured == "1") 	str_info += " / Configured"; else str_info += " / Not configured";			
		OBJ("wifi_info_str").innerHTML = str_info;
		
		return true;
	},
	PreSubmit: function()
	{
		XS(this.wifip+"/wps/enable", (OBJ("en_wps").checked)? "1":"0");
		XS(this.wifip2+"/wps/enable", (OBJ("en_wps").checked)? "1":"0");
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function()
	{
		if (OBJ("pin").innerHTML!=this.curpin)
		{
			OBJ("mainform").setAttribute("modified", "true");
			XS(this.wifip+"/wps/pin", OBJ("pin").innerHTML);
			XS(this.wifip2+"/wps/pin", OBJ("pin").innerHTML);
		}
	},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	wifip: null,
	defpin: "<?echo query("/runtime/devdata/pin");?>",
	curpin: null,
	OnClickEnWPS: function()
	{
		var en_wlan = XG(this.phyinf+"/active");
		var en_wlan2 = XG(this.phyinf2+"/active");
		
		if (OBJ("en_wps").checked )
		{
			if (XG(this.wifip+"/wps/configured")=="0" && XG(this.wifip2+"/wps/configured")=="0")
				OBJ("reset_cfg").disabled = true;
			else
				OBJ("reset_cfg").disabled = false;
			OBJ("reset_pin").disabled	= false;
			OBJ("gen_pin").disabled		= false;
			OBJ("go_wps").disabled		= false;
		}
		else
		{
			OBJ("reset_cfg").disabled	= true;
			OBJ("reset_pin").disabled	= true;
			OBJ("gen_pin").disabled		= true;
			OBJ("go_wps").disabled		= true;
		}
		
		if(en_wlan == 0 && en_wlan2 == 0)
		{
			OBJ("en_wps").disabled		= true;
			OBJ("reset_cfg").disabled	= true;
			OBJ("reset_pin").disabled	= true
			OBJ("gen_pin").disabled		= true;
			OBJ("go_wps").disabled		= true;
		}
		
		
	},
	OnClickResetCfg: function()
	{
		if (confirm("<?echo i18n("Are you sure you want to reset the device to Unconfigured?")."\\n".
					i18n("This will cause wireless settings to be lost.");?>"))
		{
			OBJ("mainform").setAttribute("modified", "true");
			XS(this.wifip+"/ssid",			"dlink"	);
			XS(this.wifip+"/authtype",		"OPEN"	);
			XS(this.wifip+"/encrtype",		"NONE"	);
			XS(this.wifip+"/wps/configured","0"		);			
			XS(this.wifip2+"/ssid",			"dlink_media");
			XS(this.wifip2+"/authtype",		"OPEN"	);
			XS(this.wifip2+"/encrtype",		"NONE"	);
			XS(this.wifip2+"/wps/configured","0"	);
			BODY.OnSubmit();
		}
	},
	OnClickResetPIN: function()
	{
		OBJ("pin").innerHTML = this.defpin;
	},
	OnClickGenPIN: function()
	{
		var pin = "";
		var sum = 0;
		var check_sum = 0;
		var r = 0;
		for(var i=0; i<7; i++)
		{
			r = (Math.floor(Math.random()*9));
			pin += r;
			sum += parseInt(r, [10]) * (((i%2)==0) ? 3:1);
		}
		check_sum = (10-(sum%10))%10;
		pin += check_sum;
		OBJ("pin").innerHTML = pin;
	}
}
</script>
