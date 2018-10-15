<script type="text/javascript">
function Page() {}
Page.prototype =
{
	//services: "WIFI.WLAN-1",
	services: "WIFI.PHYINF",
	OnLoad: function()
	{
		this.ShowCurrentStage();
	},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		switch (code)
		{
			case "OK":
				self.location.href = "./bsc_wlan_main.php";
				return true;
				break;
			default : 
				this.currentStage--;
				this.ShowCurrentStage();
				return false;
		}
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		if (!this.Initial()) return false;
		return true;
	},
	PreSubmit: function()
	{
		if (!this.SaveXML()) return null;
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	wifip: null,
	wifip2: null,
	phyinf: null,
	phyinf2: null,
	randomkey: null,
	stages: new Array ("wiz_stage_1", "wiz_stage_2", "wiz_stage_3"),
	currentStage: 0,	// 0 ~ this.stages.length
	Initial: function()
	{
		this.wlanbase = PXML.FindModule("WIFI.PHYINF");
		
		this.phyinf = GPBT(this.wlanbase, "phyinf", "uid", "WLAN-1", false);
		this.phyinf2 = GPBT(this.wlanbase, "phyinf", "uid", "WLAN-2", false);
		var wifi_profile1 = XG(this.phyinf+"/wifi");
		var wifi_profile2 = XG(this.phyinf2+"/wifi");
		this.wifip = GPBT(this.wlanbase+"/wifi", "entry", "uid", wifi_profile1, false);
		this.wifip2 = GPBT(this.wlanbase+"/wifi", "entry", "uid", wifi_profile2, false);
		
		if (!this.wifip || !this.wifip2)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		this.randomkey = RandomHex(64);

		OBJ("wiz_ssid").value = XG(this.wifip+"/ssid");
		OBJ("wiz_ssid_Aband").value = XG(this.wifip2+"/ssid");
		
		OBJ("wiz_key_Aband").style.display = "";
		
		//OBJ("ssid_Aband").innerHTML = XG(this.wifip2+"/ssid");
		return true;
	},
	SaveXML: function()
	{
		var wifip=null;
		var phyinf=null;
		var str_Aband=null;

		XS(this.wifip+"/ssid", OBJ("wiz_ssid").value);		
		XS(this.wifip2+"/ssid", OBJ("wiz_ssid_Aband").value);		
		
		for(i=0;i<2;i++)
		{
			if(i==0){
				str_Aband = "";
				wifip = this.wifip;
				phyinf = this.phyinf;
			}else{
				wifip = this.wifip2;
				phyinf = this.phyinf2;
				str_Aband = "_Aband";
			}
			XS(wifip+"/ssidhidden", "0");
			XS(wifip+"/authtype", "WPA+2PSK");
			XS(wifip+"/encrtype", "TKIP+AES");
			XS(wifip+"/nwkey/psk/passphrase", "");
			if (OBJ("autokey").checked)
				XS(wifip+"/nwkey/psk/key", this.randomkey);
			else
				XS(wifip+"/nwkey/psk/key", OBJ("wiz_key"+str_Aband).value);
			XS(wifip+"/wps/configured", "1");
			XS(this.phyinf+"/active", "1");
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
			SetButtonDisabled("b_pre", true);
		else
			SetButtonDisabled("b_pre", false);

		if (this.currentStage==this.stages.length-1)
		{
			SetButtonDisabled("b_next", true);
			SetButtonDisabled("b_send", false);
			OBJ("mainform").setAttribute("modified", "true");
			UpdateCFG();
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
		this.currentStage += offset;
	},
	OnClickPre: function()
	{
		switch (this.currentStage)
		{
		case 2:
			if (OBJ("autokey").checked)
				this.SetStage(-2);
			else
				this.SetStage(-1);
			this.ShowCurrentStage();
			break;
		default:
			this.SetStage(-1);
			this.ShowCurrentStage();
		}
	},
	OnClickNext: function()
	{
		switch (this.currentStage)
		{
		case 0:
			if (OBJ("wiz_ssid").value=="")
			{
				BODY.ShowAlert("<?echo i18n("The SSID field can not be blank.");?>");
				return;
			}
			if (OBJ("wiz_ssid_Aband").value=="")
			{
				BODY.ShowAlert("<?echo i18n("The SSID field can not be blank.");?>");
				return;
			}			
			if (OBJ("autokey").checked)
				this.SetStage(1);
			break;
		case 1:
			if(OBJ("set_5g_security_id").checked)	OBJ("wiz_key_Aband").value = OBJ("wiz_key").value;
			
			if (OBJ("wiz_key").value.length < 8 || OBJ("wiz_key_Aband").value.length < 8)
			{
				BODY.ShowAlert("<?echo i18n("Incorrect key length, should be 8 to 63 characters long.");?>");
				return;
			}
			if ( (OBJ("wiz_key").value.length == 64 && OBJ("wiz_key").value.match(/\W/)) || (OBJ("wiz_key_Aband").value.length == 64 && OBJ("wiz_key_Aband").value.match(/\W/)) )
			{
				BODY.ShowAlert("<?echo i18n("Invalid key, should be 64 characters using 0-9 and A-F.");?>");
				return;
			}
			break;
		default:
		}
		this.SetStage(1);
		this.ShowCurrentStage();
	},
	
	
	OnClickCancel: function()
	{
		if (!COMM_IsDirty(false)||confirm("<?echo i18n("Do you want to abandon all changes you made to this wizard?");?>"))
			self.location.href = "./bsc_wlan_main.php";
	}
}

function set_5g_security(value)
 {
    if (value) {
		OBJ("wl_sec").innerHTML = "Wireless Security Password";
		OBJ("wl_sec_Aband_div").style.display = "none";
    } else {
    	OBJ("wl_sec_Aband_div").style.display = "block";
	   	OBJ("wl_sec").innerHTML = "2.4Ghz Wireless Security Password";
    	OBJ("wl_sec_Aband").innerHTML = "5Ghz Wireless Security Password";
    }
}


function SetButtonDisabled(name, disable)
{
	var button = document.getElementsByName(name);
	for (i=0; i<button.length; i++)
		button[i].disabled = disable;
}

function UpdateCFG()
{
	//OBJ("ssid").innerHTML = OBJ("wiz_ssid").value;
	//OBJ("ssid_Aband").innerHTML = OBJ("wiz_ssid_Aband").value;
	//OBJ("ssid_Aband").innerHTML = this.ssid_Aband;
	
	for(i=0;i<2;i++)
	{
		if(i==0)	var str_Aband = "";
		else		var str_Aband = "_Aband";
		
		OBJ("ssid"+str_Aband).innerHTML = OBJ("wiz_ssid"+str_Aband).value;
		if (OBJ("autokey").checked)
		{
			OBJ("s_key"+str_Aband).style.display = "none";
			OBJ("l_key"+str_Aband).style.display = "block";
			OBJ("l_key"+str_Aband).innerHTML = PAGE.randomkey;
		}
		else if (OBJ("wiz_key"+str_Aband).value.length > 50)
		{
			OBJ("s_key"+str_Aband).style.display = "none";
			OBJ("l_key"+str_Aband).style.display = "block";
			OBJ("l_key"+str_Aband).innerHTML = OBJ("wiz_key"+str_Aband).value;
		}
		else
		{
			OBJ("l_key"+str_Aband).style.display = "none";
			OBJ("s_key"+str_Aband).style.display = "block";
			OBJ("s_key"+str_Aband).innerHTML = OBJ("wiz_key"+str_Aband).value;
		}
	}
}

function RandomHex(len)
{
	var c = "0123456789abcdef";
	var str = '';
	for (var i = 0; i < len; i+=1)
	{
		var rand_char = Math.floor(Math.random() * c.length);
		str += c.substring(rand_char, rand_char + 1);
	}
	return str;
}
</script>
