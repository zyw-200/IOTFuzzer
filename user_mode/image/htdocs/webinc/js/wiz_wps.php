<script type="text/javascript">
function Page() {}
Page.prototype =
{
	//services: "WIFI.WLAN-1,RUNTIME.WPS.WLAN-1",
	services: "WIFI.PHYINF,RUNTIME.WPS.WLAN-1",
	OnLoad: function()
	{
		OBJ("auto").checked = true;
		OBJ("pin").checked = true;
		this.OnClickMode();
		this.ShowCurrentStage();
	},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		switch (code)
		{
		case "OK":
			this.WPSInProgress();
			break;
		default:
			BODY.ShowAlert(result);
			break;
		}
		return true;
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		if (!this.Initial("WLAN-1", "WIFI.PHYINF")) return false;
		if (!this.Initial("WLAN-2", "WIFI.PHYINF")) return false;
		return true;
	},
	PreSubmit: function() { return null; },
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	m_prefix: "<?echo i18n("Adding wireless device").": ";?>",
	m_success: "<?echo i18n("Succeeded").". ".i18n("To add another device click on the Cancel button below or click on the Wireless Status button to check wireless status.");?>",
	m_timeout: "<?echo i18n("Session Time-Out").".";?>",
	wifip: null,
	wpsp: null,
	statep: null,
	en_wps: false,
	method: null,
	start_count_down: false,
	wps_timer: null,
	phyinf:null,
	wifi_phyinf:null,
	stages: new Array ("wiz_stage_1", "wiz_stage_2_auto", "wiz_stage_2_manu", "wiz_stage_2_msg"),
	currentStage: 0,	// 0 ~ this.stages.length
	
	Initial: function(wlan_phyinf, wifi_phyinf)
	{
		//this.wifip	= PXML.FindModule("WIFI.WLAN-1");
		
		this.wifi_phyinf = PXML.FindModule(wifi_phyinf);
		this.wpsp	= PXML.FindModule("RUNTIME.WPS.WLAN-1");
		if (!this.wifi_phyinf||!this.wpsp)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		//this.wpsp += "/runtime/phyinf/media/wps/enrollee";
		
		
		this.phyinf = GPBT(this.wifi_phyinf, "phyinf", "uid",wlan_phyinf, false);
		var wifi_profile = XG(this.phyinf+"/wifi");
		this.wifip = GPBT(this.wifi_phyinf+"/wifi", "entry", "uid", wifi_profile, false);
		
		//this.wifip = GPBT(this.wifip+"/wifi", "entry", "uid", "WIFI-1", false);
		//PXML.IgnoreModule("WIFI.WLAN-1");
		
		PXML.IgnoreModule("WIFI.PHYINF");
		PXML.IgnoreModule("RUNTIME.WPS.WLAN-1");
		
		var freq = XG(this.phyinf+"/media/freq");
		if(freq == "5")
			str_Aband = "_Aband";
		else
			str_Aband = "";
		
		if (XG(this.wifip+"/wps/enable")=="1")	this.en_wps = true;
		
		if(str_Aband != "")
			OBJ("frequency"+str_Aband).innerHTML = "5 Ghz Frequency";
		else 
			OBJ("frequency"+str_Aband).innerHTML = "2.4 Ghz Frequency";		
		
		OBJ("ssid"+str_Aband).innerHTML = XG(this.wifip+"/ssid");
		switch (XG(this.wifip+"/authtype"))
		{
		case "WPA":
    		OBJ("security"+str_Aband).innerHTML = "<?echo i18n("WPA-EAP");?>"; 
			OBJ("cipher"+str_Aband).innerHTML = CipherTypeParse(XG(this.wifip+"/encrtype"));
			OBJ("pskkey"+str_Aband).innerHTML = XG(this.wifip+"/nwkey/psk/key");
			OBJ("st_cipher"+str_Aband).style.display = "block";
			OBJ("st_pskkey"+str_Aband).style.display = "block";
			break;	
		case "WPA2":
    		OBJ("security"+str_Aband).innerHTML = "<?echo i18n("WPA2-EAP");?>"; 
			OBJ("cipher"+str_Aband).innerHTML = CipherTypeParse(XG(this.wifip+"/encrtype"));
			OBJ("pskkey"+str_Aband).innerHTML = XG(this.wifip+"/nwkey/psk/key");
			OBJ("st_cipher"+str_Aband).style.display = "block";
			OBJ("st_pskkey"+str_Aband).style.display = "block";		
			break;
	
		case "WPAPSK":
			OBJ("security"+str_Aband).innerHTML = "<?echo i18n("WPA-PSK");?>";
			OBJ("cipher"+str_Aband).innerHTML = CipherTypeParse(XG(this.wifip+"/encrtype"));
			OBJ("pskkey"+str_Aband).innerHTML = XG(this.wifip+"/nwkey/psk/key");
			OBJ("st_cipher"+str_Aband).style.display = "block";
			OBJ("st_pskkey"+str_Aband).style.display = "block";
			break;
					
		case "WPA2PSK":
			OBJ("security"+str_Aband).innerHTML = "<?echo i18n("WPA2-PSK");?>";
			OBJ("cipher"+str_Aband).innerHTML = CipherTypeParse(XG(this.wifip+"/encrtype"));
			OBJ("pskkey"+str_Aband).innerHTML = XG(this.wifip+"/nwkey/psk/key");
			OBJ("st_cipher"+str_Aband).style.display = "block";
			OBJ("st_pskkey"+str_Aband).style.display = "block";
			break;
			
		case "WPA+2PSK":
			OBJ("security"+str_Aband).innerHTML = "<?echo i18n("Auto")." (".i18n("WPA or WPA2").") - ".i18n("Personal");?>";
			OBJ("cipher"+str_Aband).innerHTML = CipherTypeParse(XG(this.wifip+"/encrtype"));
			OBJ("pskkey"+str_Aband).innerHTML = XG(this.wifip+"/nwkey/psk/key");
			OBJ("st_cipher"+str_Aband).style.display = "block";
			OBJ("st_pskkey"+str_Aband).style.display = "block";
			break;
		case "WPA+2":
			OBJ("security"+str_Aband).innerHTML = "<?echo i18n("Auto")." (".i18n("WPA or WPA2").") - ".i18n("Enterprise");?>";
			OBJ("cipher"+str_Aband).innerHTML = CipherTypeParse(XG(this.wifip+"/encrtype"));
			OBJ("st_cipher"+str_Aband).style.display = "block";
			this.en_wps = false;
			DisableWPS();
			break;
		case "SHARED":
			var key_no = XG(this.wifip+"/nwkey/wep/defkey");
			OBJ("security"+str_Aband).innerHTML = "<?echo i18n("WEP")." - ".i18n("SHARED");?>";
			OBJ("wepkey"+str_Aband).innerHTML = key_no + ": " + XG(this.wifip+"/nwkey/wep/key:"+key_no);
			OBJ("pskkey"+str_Aband).innerHTML = XG(this.wifip+"/nwkey/psk/key");
			OBJ("st_wep"+str_Aband).style.display = "block";
			this.en_wps = false;
			DisableWPS();
			break;
		case "OPEN":
			if (XG(this.wifip+"/encrtype")=="WEP")
			{
				var key_no = XG(this.wifip+"/nwkey/wep/defkey");
				OBJ("security"+str_Aband).innerHTML = "<?echo i18n("WEP")." - ".i18n("OPEN");?>";
				OBJ("wepkey"+str_Aband).innerHTML = key_no + ": " + XG(this.wifip+"/nwkey/wep/key:"+key_no);
				OBJ("st_wep"+str_Aband).style.display = "block";
			}
			else
			{
				OBJ("security"+str_Aband).innerHTML = "<?echo i18n("None");?>";
			}
			break;
		}
		
		return true;
	},
	ShowCurrentStage: function()
	{
		for (var i=0; i<this.stages.length; i++)
		{
			if (i==this.currentStage)
				OBJ(this.stages[i]).style.display = "block";
			else
				OBJ(this.stages[i]).style.display = "none";
		}

		if (this.currentStage==0)
		{
			SetButtonDisabled("b_pre",	true);
			SetButtonDisabled("b_next",	false);
			SetButtonDisabled("b_send",	true);
			SetButtonDisabled("b_stat",	true);
		}
		else if (this.currentStage==1||this.currentStage==2)
		{
			if (this.en_wps)
				SetButtonDisabled("b_send", false);
			SetButtonDisabled("b_pre",	false);
			SetButtonDisabled("b_next",	true);
			SetButtonDisabled("b_stat",	false);
		}
		else
		{
			SetButtonDisabled("b_pre",	true);
			SetButtonDisabled("b_next",	true);
		}
	},
	ShowWPSMessage: function(state)
	{
		switch (state)
		{
		case "WPS_NONE":
			OBJ("msg").innerHTML = this.m_prefix + "<?echo i18n("Session Time-Out").".";?>";
			SetButtonDisabled("b_exit",	false);
			break;
		case "WPS_ERROR":
			OBJ("msg").innerHTML = this.m_prefix + "WPS_ERROR.";
			SetButtonDisabled("b_exit",	false);
			break;
		case "WPS_OVERLAP":
			OBJ("msg").innerHTML = this.m_prefix + "WPS_OVERLAP.";
			SetButtonDisabled("b_exit",	false);
			break;
		case "WPS_IN_PROGRESS":
			SetButtonDisabled("b_exit",	true);
			SetButtonDisabled("b_send",	true);
			SetButtonDisabled("b_stat",	true);
			break;
		case "WPS_SUCCESS":
			OBJ("msg").innerHTML = this.m_prefix + "<?echo i18n("Succeeded").". ".i18n("To add another device click on the Cancel button below or click on the Wireless Status button to check wireless status.");?>";
			SetButtonDisabled("b_exit",	false);
			SetButtonDisabled("b_stat",	false);
			SetButtonDisplayStyle("b_send",	"none");
			SetButtonDisplayStyle("b_stat",	"inline");
			break;
		}
		this.currentStage = 3;
		this.ShowCurrentStage();
		if (state=="WPS_IN_PROGRESS")	return;
		PAGE.start_count_down = false;
		if (this.cd_timer)	clearTimeout(this.cd_timer);
		if (this.wps_timer)	clearTimeout(this.wps_timer);
	},
	OnClickMode: function()
	{
		if (OBJ("auto").checked)
		{
			SetButtonDisplayStyle("b_send",	"inline");
			SetButtonDisplayStyle("b_stat",	"none");
		}
		else
		{
			SetButtonDisplayStyle("b_send",	"none");
			SetButtonDisplayStyle("b_stat",	"inline");
		}
	},
	OnClickPINCode: function()
	{
		OBJ("pin").checked = true;
	},
	OnClickPre: function()
	{
		this.currentStage = 0;
		this.ShowCurrentStage();
	},
	OnClickNext: function()
	{
		if (OBJ("auto").checked)
			this.currentStage = 1;
		else
			this.currentStage = 2;
		this.ShowCurrentStage();
	},
	OnClickCancel: function()
	{
		if (this.currentStage==3)
		{
			self.location.href = "./wiz_wps.php";
			return;
		}
		if (!COMM_IsDirty(false)||confirm("<?echo i18n("Do you want to abandon all changes you made to this wizard?");?>"))
			self.location.href = "./bsc_wlan_main.php";
	},
	OnSubmit: function()
	{
		var ajaxObj = GetAjaxObj("WPS");
		var action = (OBJ("pin").checked)? "PIN":"PBC";
		var uid = "WLAN-1";
		var value = OBJ("pincode").value;
		ajaxObj.createRequest();
		ajaxObj.onCallback = function (xml)
		{
			ajaxObj.release();
			PAGE.OnSubmitCallback(xml.Get("/wpsreport/result"), xml.Get("/wpsreport/reason"));
		}
		
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("wpsacts.php", "action="+action+"&uid="+uid+"&pin="+value);
		AUTH.UpdateTimeout();
	},
	WPSInProgress: function()
	{
		if (!this.start_count_down)
		{
			this.start_count_down = true;
			var str = "";
			if (OBJ("pin").checked)
			{
				str = "<?echo i18n("Please start WPS on the wireless device you are adding to your wireless network.");?><br />";
			}
			else
			{
				str = "<?echo i18n("Please press down the Push Button (physical or virtual) on the wireless device you are adding to your wireless network.");?><br />";
			}
			str += '<?echo i18n("Remain time in second");?>: <span id="ct">120</span><br /><br />';
			str += this.m_prefix + "<?echo i18n("Started");?>.";
			OBJ("msg").innerHTML = str;
			this.ShowWPSMessage("WPS_IN_PROGRESS");
			setTimeout('PAGE.WPSCountDown()',1000);
		}
		COMM_GetCFG(false, "RUNTIME.WPS.WLAN-1", function(xml) {PAGE.WPSInProgressCallBack(xml);});
	},
	WPSInProgressCallBack: function(xml)
	{
		if (this.statep==null)
			this.statep = "/postxml/module/runtime/phyinf/media/wps/enrollee/state";

		var state = xml.Get(this.statep);
		//if (state=="WPS_IN_PROGRESS" )
		//hendry, sometimes this node is null since hostapd not quick enough to set node.
		if (state=="WPS_IN_PROGRESS" || state=="")
			this.wps_timer = setTimeout('PAGE.WPSInProgress()',3000);
		else
			this.ShowWPSMessage(state);
	},
	WPSCountDown: function()
	{
		var time = parseInt(OBJ("ct").innerHTML, 10);
		if (time > 0)
		{
			time--;
			this.cd_timer = setTimeout('PAGE.WPSCountDown()',1000);
			OBJ("ct").innerHTML = time;
		}
		else
		{
			clearTimeout(this.cd_timer);
			this.ShowWPSMessage("WPS_NONE");
		}
	}
}

function CipherTypeParse(cipher)
{
	switch (cipher)
	{
	case "TKIP+AES":
		return "<?echo i18n("TKIP and AES");?>";
	case "TKIP":
		return "<?echo i18n("TKIP");?>";
	case "AES":
		return "<?echo i18n("AES");?>";
	}
}
function DisableWPS()
{
	OBJ("pin").disabled = true;
	OBJ("pbc").disabled = true;
	OBJ("pincode").disabled = true;
	SetButtonDisabled("b_send", true);
}

function SetButtonDisabled(name, isDisable)
{
	var button = document.getElementsByName(name);
	for (i=0; i<button.length; i++)
		button[i].disabled = isDisable;
}
function SetButtonDisplayStyle(name, style)
{
	var button = document.getElementsByName(name);
	for (i=0; i<button.length; i++)
		button[i].style.display = style;
}
</script>
