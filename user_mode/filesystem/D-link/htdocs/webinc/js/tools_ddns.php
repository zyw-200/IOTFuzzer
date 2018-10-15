<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "DDNS4.WAN-1, DDNS4.WAN-3, RUNTIME.DDNS4.WAN-1",    
	OnLoad: function()
	{
		if (!this.rgmode)
		{
			BODY.DisableCfgElements(true);
		}
	},
	OnUnload: function() {},
	OnSubmitCallback: function(code, result) { return false; },
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var p = PXML.FindModule("DDNS4."+this.devicemode);
		if (p === "") alert("ERROR!");
		OBJ("en_ddns").checked = (XG(p+"/inf/ddns4")!=="");
		var ddnsp = GPBT(p+"/ddns4", "entry", "uid", this.ddns, 0);
		OBJ("server").value	= XG(ddnsp+"/provider");
		OBJ("host").value	= XG(ddnsp+"/hostname");
		OBJ("user").value	= XG(ddnsp+"/username");
		OBJ("passwd").value	= XG(ddnsp+"/password");
		OBJ("report").innerHTML = "";

		return true;
	},
	PreSubmit: function()
	{
		PXML.ActiveModule("DDNS4.WAN-1");  
		PXML.ActiveModule("DDNS4.WAN-3");
		PXML.IgnoreModule("RUNTIME.DDNS4.WAN-1");
			
		var p = PXML.FindModule("DDNS4."+this.devicemode);

		XS(p+"/inf/ddns4", OBJ("en_ddns").checked ? this.ddns : "");
		
		if (OBJ("en_ddns").checked 
			|| (OBJ("server").value!=="")	|| (OBJ("host").value!=="")
			|| (OBJ("user").value!=="")		|| (OBJ("passwd").value!==""))
		{
			var ddnsp = GPBT(p+"/ddns4", "entry", "uid", this.ddns, 0);
			if (!ddnsp)
			{
				var c = XG(p+"/ddns4/count");
				var s = XG(p+"/ddns4/seqno");
				c += 1;
				s += 1;
				XS(p+"/ddns4/entry:"+c+"/uid", this.ddns);
				XS(p+"/ddns4/count", c);
				XS(p+"/ddns4/seqno", s);
				ddnsp = p+"/ddns4/entry:"+c;
			}
			XS(ddnsp+"/provider", OBJ("server").value);
			XS(ddnsp+"/hostname", OBJ("host").value);
			XS(ddnsp+"/username", OBJ("user").value);
			XS(ddnsp+"/password", OBJ("passwd").value);
		}
		if (this.devicemode == "WAN-3")	PXML.IgnoreModule("DDNS4.WAN-1");
		else				PXML.IgnoreModule("DDNS4.WAN-3");
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	devicemode: "<?if (query("/runtime/device/router/mode")=="3G") echo "WAN-3"; else echo "WAN-1";?>",
	ddns: "DDNS4-1",
	GrayItems: function(disabled)
	{
		var frmObj = document.forms[0];
		for (var idx = 0; idx < frmObj.elements.length; idx+=1)
		{
			var obj = frmObj.elements[idx];
			var name = obj.tagName.toLowerCase();
			if (name === "input" || name === "select")
			{
				obj.disabled = disabled;
			}
		}
	},
	
	GetReport: function()
	{
		var self = this;
		var ajaxObj = GetAjaxObj("getreport");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			//xml.dbgdump();
			ajaxObj.release();
			if (xml.Get("/ddns4/valid")==="1" && xml.Get("/ddns4/testtimeCheck")== self.ddns_testtime)  
			{
				var s = xml.Get("/ddns4/status");
				var r = xml.Get("/ddns4/result");
				var msg = "";
				if (s === "IDLE")
				{
					if		(r === "SUCCESS") msg = "<?echo i18n("The update was successful, and the hostname is now updated.");?>";
					else if (r === "NOAUTH")  msg = "<?echo i18n("The username or password specified are incorrect.");?>";
					else if (r === "NOTFQDN") msg = "<?echo i18n("The hostname specified is not a fully-qualified domain name (not in the form hostname.dyndns.org or domain.com).");?>";
					else if (r === "BADHOST") msg = "<?echo i18n("The hostname specified does not exist (or is not in the service specified in the system parameter)");?>";
					else if (r === "SVRERR")  msg = "<?echo i18n("Can't connect to server.");?>";
					else					  msg = "<?echo i18n("Update fail.");?>";  

					self.GrayItems(false); 
				}
				else
				{
					if		(s === "CONNECTING")msg = "<?echo i18n("Connecting");?>"	+ "...";
					else if (s === "UPDATING")	msg = "<?echo i18n("Updating");?>"		+ "...";
					else						msg = "<?echo i18n("Waiting");?>"	+ "...";
                    
                    self.ddns_count += 1 ;
					if (self.ddns_count < 10) setTimeout('PAGE.GetReport()', 1000);
					else
					{	
						self.GrayItems(false); 
						msg = "<?echo i18n("Update fail.");?>";  
					}
				}
			}
			else
			{	
				self.ddns_count += 1 ;
				if (self.ddns_count < 10) 
				{
					msg = "<?echo i18n("Waiting");?>"	+ "...";
					setTimeout('PAGE.GetReport()', 1000);
				}	
				else
				{
					self.GrayItems(false);
					msg = "<?echo i18n("Update fail.");?>";
				}	
			}
			OBJ("report").innerHTML = msg;
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("ddns_act.php", "act=getreport");
	},
	ddns_count: 0,
	ddns_testtime: "",
	OnClickUpdateNow: function()
	{	
		if(OBJ("host").value == "") return alert("Please input the host name.");
		if(OBJ("user").value == "") return alert("Please input the user account");
		if(OBJ("passwd").value == "") return alert("Please input the password");

		PXML.IgnoreModule("DDNS4.WAN-1");
		PXML.IgnoreModule("DDNS4.WAN-3");
		PXML.ActiveModule("RUNTIME.DDNS4.WAN-1");
		
		var self = this;
		var time_now = new Date();
		self.ddns_testtime = time_now.getHours().toString() + time_now.getMinutes().toString() + time_now.getSeconds().toString();
		
		var p = PXML.FindModule("RUNTIME.DDNS4.WAN-1");
		XS(p+"/runtime/inf/ddns4/provider", OBJ("server").value);
		XS(p+"/runtime/inf/ddns4/hostname", OBJ("host").value);
		XS(p+"/runtime/inf/ddns4/username", OBJ("user").value);
		XS(p+"/runtime/inf/ddns4/password", OBJ("passwd").value);
		XS(p+"/runtime/inf/ddns4/testtime", self.ddns_testtime);
		
		var xml = PXML.doc;
		PXML.UpdatePostXML(xml);
        COMM_CallHedwig(PXML.doc, function(xml){PXML.hedwig_callback(xml);});
 		
		this.GrayItems(true);   
		OBJ("report").innerHTML = "<?echo i18n("Start updating...");?>";
		self.ddns_count = 0 ;
		
		var ajaxObj = GetAjaxObj("updatenow");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			ajaxObj.release();
			self.GetReport();
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("ddns_act.php", "act=getreport");
	}
};

</script>
