<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "DEVICE.ACCOUNT,HTTP.WAN-1,HTTP.WAN-2",
	OnLoad: function()
	{
		if (!this.rgmode)
		{
			OBJ("en_remote").disabled = true;
			OBJ("remote_port").disabled = true;
		}
	},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		BODY.ShowContent();
		switch (code)
		{
		case "OK":
			if (COMM_Equal(OBJ("en_remote").getAttribute("modified"), "true") || COMM_Equal(OBJ("remote_port").getAttribute("modified"), "true"))
			{
				AUTH.Logout();
				BODY.ShowLogin();
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
			if (result.Get("/pigwidgeon/message")=="no power")
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
		PXML.doc = xml;
		if (!this.Initial()) return false;
		return true;
	},
	PreSubmit: function()
	{
		if (!this.SaveXML()) return null;
		PXML.ActiveModule("HTTP.WAN-1");
		PXML.ActiveModule("HTTP.WAN-2");
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	admin: null,
	usr: null,
	actp: null,
	captcha: null,
	rcp: null,
	rcp2: null,
	rport: null,
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	Initial: function()
	{
		this.actp = PXML.FindModule("DEVICE.ACCOUNT");
		this.rcp = PXML.FindModule("HTTP.WAN-1");
		this.rcp2 = PXML.FindModule("HTTP.WAN-2");
		if (!this.actp||!this.rcp||!this.rcp2)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		this.captcha = this.actp + "/device/session/captcha";
		this.actp += "/device/account";
		this.rcp += "/inf/web";
		this.rcp2 += "/inf/web";
		this.rport = XG(this.rcp);
		this.admin = OBJ("admin_p1").value = OBJ("admin_p2").value = XG(this.actp+"/entry:1/password");
		this.usr = OBJ("usr_p1").value = OBJ("usr_p2").value = XG(this.actp+"/entry:2/password");
		OBJ("en_captcha").checked = COMM_EqBOOL(XG(this.captcha), true);
		OBJ("en_remote").checked = !COMM_EqSTRING(this.rport, "");
		OBJ("remote_port").value = XG(this.rcp);
		this.OnClickEnRemote();
		return true;
	},
	SaveXML: function()
	{
		if (!COMM_EqSTRING(OBJ("admin_p1").value, OBJ("admin_p2").value))
		{
			BODY.ShowAlert("<?echo i18n("Password and Verify Password do not match. Please reconfirm admin password.");?>");
			return false;
		}
		if (!COMM_EqSTRING(OBJ("admin_p1").value, this.admin))
		{
			XS(this.actp+"/entry:1/password", OBJ("admin_p1").value);
		}
		if (!COMM_EqSTRING(OBJ("usr_p1").value, OBJ("usr_p2").value))
		{
			BODY.ShowAlert("<?echo i18n("Password and Verify Password do not match. Please reconfirm user password.");?>");
			return false;
		}
		if (!COMM_EqSTRING(OBJ("usr_p1").value, this.usr))
		{
			XS(this.actp+"/entry:2/password", OBJ("usr_p1").value);
		}
		if (OBJ("en_captcha").checked)
		{
			XS(this.captcha, "1");
			BODY.enCaptcha = true;
		}
		else
		{
			XS(this.captcha, "0");
			BODY.enCaptcha = false;
		}
		if (OBJ("en_remote").checked)
		{
			if (!TEMP_IsDigit(OBJ("remote_port").value))
			{
				BODY.ShowAlert("<?echo i18n("The remote admin port number is not valid.");?>");
				return false;
			}
			XS(this.rcp, OBJ("remote_port").value);
			XS(this.rcp2, OBJ("remote_port").value);
		}
		else
		{
			XS(this.rcp, "");
			XS(this.rcp2, "");
		}
		return true;
	},
	OnClickEnRemote: function()
	{
		if (OBJ("en_remote").checked)	OBJ("remote_port").disabled = false;
		else							OBJ("remote_port").disabled = true;
	}
}
</script>
