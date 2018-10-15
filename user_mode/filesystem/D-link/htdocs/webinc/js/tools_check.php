<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "",
	OnLoad: function()
	{
	},
	OnUnload: function() {},
	OnSubmitCallback: function(code, result) { return false; },
	InitValue: function(xml)
	{
		return true;
	},
	PreSubmit: function()
	{
		return null;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////
	wcount: 0,
	OnClick_Ping: function()
	{
		this.ResetPing();
		OBJ("ping").disabled = true;
		OBJ("dst").value= COMM_EatAllSpace(OBJ("dst").value);
		if (OBJ("dst").value==="")
		{
			BODY.ShowAlert("<?echo i18n("Please enter a host name or IP address for ping.");?>");
			OBJ("dst").focus();
			this.ResetPing();
			return false;
		}

		var self = this;
		var ajaxObj = GetAjaxObj("ping");

		OBJ("report").innerHTML = "<?echo i18n("Pinging...");?>"

		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			ajaxObj.release();
			self.GetPingReport();
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("diagnostic.php", "act=ping&dst="+OBJ("dst").value);
	},
	
	OnClick_Ping_ipv6: function()
	{
		this.ResetPing();
		OBJ("ping_ipv6").disabled = true;
		OBJ("dst_ipv6").value= COMM_EatAllSpace(OBJ("dst_ipv6").value);
		if (OBJ("dst_ipv6").value==="")
		{
			BODY.ShowAlert("<?echo i18n("Please enter a host name or IP address for ping.");?>");
			OBJ("dst_ipv6").focus();
			this.ResetPing();
			return false;
		}

		var self = this;
		var ajaxObj = GetAjaxObj("dst_ipv6");

		OBJ("report").innerHTML = "<?echo i18n("Pinging...");?>"

		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			ajaxObj.release();
			self.GetPingReport();
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("diagnostic.php", "act=ping&dst="+OBJ("dst_ipv6").value);
	},
	
	GetPingReport: function()
	{
		if (this.wcount > 5)
		{
			OBJ("report").innerHTML = "<?echo i18n("Ping timeout.");?>";
			this.ResetPing();
			return;
		}

		var self = this;
		var ajaxObj = GetAjaxObj("pingreport");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			ajaxObj.release();
			if (xml.Get("/diagnostic/report")==="")
			{
				setTimeout('PAGE.GetPingReport()',1000);
				self.wcount += 1;
			}
			else
			{
				OBJ("report").innerHTML = self.WrapRetString( xml.Get("/diagnostic/report") );
				self.ResetPing();
			}
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("diagnostic.php", "act=pingreport");
	},
	ResetPing: function()
	{
		this.wcount = 0;
		OBJ("ping").disabled = false;
		OBJ("ping_ipv6").disabled = false;
	},
	
	StrArray: ["is alive", "No response from"],
	WrapRetString: function(OrgStr)
	{
		var WrappedStr = OrgStr;
		var index;
		
		for(var i=0; i<this.StrArray.length; i+=1)
		{
			index = OrgStr.indexOf(this.StrArray[i]);
			if(index != -1)
			{
				var BgnStr = OrgStr.substring(0, index);
				var EndStr = OrgStr.substring(index+this.StrArray[i].length, OrgStr.length);
				var MidStr = null;
				switch(i)
				{
				case 0:
					MidStr = "<?echo i18n("is alive");?>";
					break;
				case 1:
					MidStr = "<?echo i18n("No response from");?>";
				}
				WrappedStr = BgnStr+MidStr+EndStr;
				break;
			}
		}
		return WrappedStr;
	}
};
</script>
