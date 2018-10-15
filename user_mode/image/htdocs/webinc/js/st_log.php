<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "DEVICE.LOG,RUNTIME.LOG",
	OnLoad: function(){},
	OnUnload: function() {},
	OnSubmitCallback: function (){},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var logl = PXML.FindModule("DEVICE.LOG");
		if (logl === "")
		{ alert("InitValue ERROR!"); return false; }
		var logLevel = XG(logl+"/device/log/level");
		if(logLevel === "WARNING")	OBJ("LOG_warn").checked = true;
		else if(logLevel === "NOTICE")	OBJ("LOG_info").checked = true;
		else if(logLevel === "DEBUG")	OBJ("LOG_dbg").checked = true;

		OBJ("sysact").checked = true;
		this.logType = "sysact";
		this.ReNewVars();
		this.DrawLog();
		return true;
	},
	PreSubmit: function()
	{
		var logl = PXML.FindModule("DEVICE.LOG");
		if (OBJ("LOG_warn").checked)		XS(logl+"/device/log/level", "WARNING");
		else if(OBJ("LOG_info").checked)	XS(logl+"/device/log/level", "NOTICE");
		else if(OBJ("LOG_dbg").checked)		XS(logl+"/device/log/level", "DEBUG");

		PXML.IgnoreModule("RUNTIME.LOG");
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	logType: "sysact",
	pageInx: 0,
	msgItems: 10,
	logPages: 0,
	logItems : 0,

	ReNewVars:function()
	{
		var base = PXML.FindModule("RUNTIME.LOG");
		base += "/runtime/log/" + this.logType + "/entry#";
		this.logItems = XG(base);
		this.logPages = Math.floor(this.logItems/10);
		var isint = this.logItems/10;
		if(isint == this.logPages)
		{
			this.logPages = this.logPages-1;
		}
		this.pageInx = 0;
		return true;
	},
	OnClickToPage:function(to)
	{
		if(to == "-1" && this.pageInx > 0)
		{
			this.pageInx--;
		}
		else if(to == "+1" && this.pageInx < this.logPages)
		{
			this.pageInx++;
		}
		else if(to == "1")
		{
			this.pageInx = 0;
		}
		else if(to == "0")
		{
			this.pageInx = this.logPages;
		}
		else
		{return false;}
		this.DrawLog();
	},
	DrawLog:function()
	{
		if (this.logPages == "0")
		{
			OBJ("pp").disabled=true;
			OBJ("np").disabled=true;
		}
		else
		{
			if(this.pageInx == "0")
			{
				OBJ("pp").disabled=true;
				OBJ("np").disabled=false;
			}
			if(this.pageInx == this.logPages)
			{
				OBJ("pp").disabled=false;
				OBJ("np").disabled=true;
			}
			if(this.pageInx > "0" && this.pageInx < this.logPages)
			{
				OBJ("pp").disabled=false;
				OBJ("np").disabled=false;
			}
		}
		var str = "<p><?echo i18n("Page");?> "+ (this.pageInx + 1) + " <?echo i18n("of");?> " + (1 + this.logPages) + "</p>";
		str += "<table class=\"general\"><tr>";
		str += '<th width="128px">' + "<?echo i18n("Time");?>" + "</th>";
		str += '<th width="396px">' + "<?echo i18n("Message");?>" + "</th>";
		str += "</tr>";
		
		var base = PXML.FindModule("RUNTIME.LOG");
		base += "/runtime/log/" + this.logType + "/entry";
		
		for(var inx=(this.logItems-this.pageInx*this.msgItems); inx > this.logItems-(this.pageInx+1)*this.msgItems && inx > 0; inx--)
		{
			var time = XG(base + ":" + inx + "/time");
			var msg = XG(base + ":" + inx + "/message");
			str += "<tr>";
			str += "<td>" + time + "</td>";
			str += "<td class=\"msg\">" + msg + "</td>";
			str += "</tr>";
		}
		str += "</table>";
		OBJ("sLog").innerHTML = str;
	},
	OnClickClear:function()
	{
		OBJ("clear").disabled = true;
		var ajaxObj = GetAjaxObj("clear");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			BODY.OnReload(xml);
			OBJ("clear").disabled = false;
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("log_clear.php", "act=clear&logtype="+this.logType+"&SERVICES="+"RUNTIME.LOG");

	},
	OnClickChangeType:function(type)
	{
		if(type != "logtype")
		{
			this.logType = type;
		}
		else	OBJ("sysact").checked = true;
		this.ReNewVars();
		this.DrawLog();
	}
}
</script>
