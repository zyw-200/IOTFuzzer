<script type="text/javascript">
function Page() {} 
Page.prototype =
{
	services: "RUNTIME.INF.LAN-1,DEVICE.LOG",
	OnLoad: function(){},
	OnUnload: function() {},
	OnSubmitCallback: function ()	{},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var logl = PXML.FindModule("DEVICE.LOG");
		if (logl === "")
		{ alert("InitValue ERROR!"); return false; }
		if (!this.InitSYSLOG()) return false;
		return true;
	},
	
	PreSubmit: function()
	{
		if (!this.PreSYSLOG()) return null;
		PXML.IgnoreModule("RUNTIME.INF.LAN-1");
		return PXML.doc;
			
	},

	IsDirty: null,
	Synchronize: function() {},
	
	lanip: "<? echo INF_getcurripaddr("LAN-1"); ?>",
	mask: "<? echo INF_getcurrmask("LAN-1"); ?>",
   
	InitSYSLOG: function()
	{
		this.syslog = PXML.FindModule("DEVICE.LOG");
		if (this.syslog==="")
		{
			alert("InitSYSLOG() ERROR!!");
			return false;
		}
		this.syslog += "/device/log/remote/";
		var hostid = XG(this.syslog+"/ipv4/ipaddr");
		if (hostid=="")
		{
			OBJ("sysloghost").value = "";
		}
		else
		{
			var network = COMM_IPv4NETWORK(this.lanip, this.mask);
			OBJ("sysloghost").value = hostid;
		}
		
		OBJ("syslogenable").checked = (XG(this.syslog+"/enable") === "1");
		COMM_SetSelectValue(OBJ("hostlist"), "");
     	this.OnClickSYSLOGEnable();
		return true;
	},

PreSYSLOG: function()
	{
		if (OBJ("syslogenable").checked)
		{
			var network = COMM_IPv4NETWORK(this.lanip, this.mask);

			var hostip	= OBJ("sysloghost").value;
			var hostnet	= COMM_IPv4NETWORK(hostip, this.mask);
			var maxhost	= COMM_IPv4MAXHOST(this.mask);
			if (network !== hostnet)
			{
				BODY.ShowAlert("<?echo i18n("The SYSLOG host should be in the same network of LAN!");?>");
				return null;
			}
           	var hostid = COMM_IPv4HOST(hostip, this.mask);
			if (hostid === 0 || hostid === maxhost)
			{
				BODY.ShowAlert("<?echo i18n("Invalid SYSLOG host ID !");?>");
				return null;
			}
    		XS(this.syslog+"/enable",	"1");
    		XS(this.syslog+"/ipv4/ipaddr",	hostip);
		}
		else
		{
			XS(this.syslog+"/enable",	"0");
			XS(this.syslog+"/ipv4/ipaddr",	"");
		
		}
		return true;
	},
	

	OnClickSYSLOGEnable: function()
	{
		if (OBJ("syslogenable").checked)
		{
			OBJ("sysloghost").setAttribute("modified", "false");
			OBJ("sysloghost").disabled = false;
			OBJ("syslogadd").disabled = false;
			OBJ("hostlist").disabled = false;
		}
		else
		{
			OBJ("sysloghost").setAttribute("modified", "ignore");
			OBJ("sysloghost").disabled = true;
			OBJ("syslogadd").disabled = true;
			OBJ("hostlist").disabled = true;
		}
	},
	
	OnClickSYSLOGAdd: function()
	{
		if(OBJ("hostlist").value === "")
		{
			BODY.ShowAlert("<?echo i18n("Please select a machine first!");?>");
			return null;
		}
		OBJ("sysloghost").value = OBJ("hostlist").value;
	}


}



</script>
