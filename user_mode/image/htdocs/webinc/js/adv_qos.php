<?include "/htdocs/phplib/inet.php";?>
<?include "/htdocs/phplib/inf.php";?>
<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "QOS, INET.INF",
	OnLoad: function()
	{		
		if (!this.rgmode)
		{
			BODY.DisableCfgElements(true);
		}
	},
	OnUnload: function() {},
	OnSubmitCallback: function () {},
	InitValue: function(xml)
	{		
		PXML.doc = xml;
		var qos = PXML.FindModule("QOS");						
		var base = PXML.FindModule("INET.INF");
		if (!base) { alert("InitValue ERROR!"); return false; }
											
		if (this.activewan==="")
		{
			BODY.ShowAlert("<?echo i18n("There is no interface can access the Internet!  Please check the cable, and the Internet settings!");?>");
			return false;
		}
		if (qos === "")		{ alert("InitValue ERROR!"); return false; }		
		OBJ("en_qos").checked = (XG(qos+"/device/qos/enable")==="1");
		OBJ("auto_speed").checked = (XG(qos+"/device/qos/autobandwidth")==="1");
		OBJ("upstream").value = XG(qos+"/inf/bandwidth/upstream");
		OBJ("conntype").value = XG(qos+"/inf/bandwidth/type");		
		if(OBJ("conntype").value == "") OBJ("conntype").value="AUTO";
		//////////////////////////////////////////// by bisonpan
		if(XG(qos+"/device/qos/enable")==="1")
		{									
			var infp	= GPBT(base, "inf", "uid", "WAN-1", false);			
			var inetp	= GPBT(base+"/inet", "entry", "uid", XG(infp+"/inet"), false);			
			var wan1addrtype = XG(inetp+"/addrtype");
						
			if(OBJ("conntype").value == "AUTO")
			{
				if (wan1addrtype === "ipv4")
				{
					OBJ("st_type").innerHTML  = "Cable Or Other Broadband Network";	
				}
				else if (wan1addrtype === "ppp4")
				{
					OBJ("st_type").innerHTML  = "xDSL Or Other Frame Relay Network";
				}	
			}
			else 
			if(OBJ("conntype").value == "ADSL") 
			{
				OBJ("st_type").innerHTML  = "xDSL Or Other Frame Relay Network";
			}	
			else 
			if(OBJ("conntype").value == "CABLE")
			{ 
				OBJ("st_type").innerHTML  = "Cable Or Other Broadband Network";					
			}									
		}
		else
		{
			OBJ("st_type").innerHTML = "";
		}
		if(XG(qos+"/device/qos/autobandwidth")==="1")
		{			
			var bwup = XG(qos+"/runtime/device/qos/bwup");			
			if( bwup == "" || bwup == "0" )
			OBJ("st_upstream").innerHTML = "Not Estimated";
			else
			OBJ("st_upstream").innerHTML  = bwup+" kbps";
		}
		else
		{
			OBJ("st_upstream").innerHTML = "Not Estimated";
		}
		////////////////////////////////////////////////////////
		this.OnClickQosEnable();
				return true;
	},
	
	PreSubmit: function()
	{
		var qos = PXML.FindModule("QOS");
				
		if (this.activewan==="")
		{
			BODY.ShowAlert("<?echo i18n("There is no interface can access the Internet!  Please check the cable, and the Internet settings!");?>");
			return null;
		}
		
		if (OBJ("upstream").value!="" && !TEMP_IsDigit(OBJ("upstream").value))
		{			
			BODY.ShowAlert("<?echo i18n("The input uplink speed is invalid.");?>");
			OBJ("upstream").focus();
			return null;
		}
		else 
		if(OBJ("en_qos").checked && !OBJ("auto_speed").checked && (OBJ("upstream").value > 102400 || OBJ("upstream").value < 10)) 
		{						
			BODY.ShowAlert("<?echo i18n("The input uplink speed is invalid, value range is 12400~10.");?>");
			OBJ("upstream").focus();
			return null;		
		}	
		XS(qos+"/device/qos/enable", OBJ("en_qos").checked ? "1":"0");
		XS(qos+"/device/qos/autobandwidth", OBJ("auto_speed").checked ? "1":"0");
		XS(qos+"/inf/bandwidth/upstream",	OBJ("upstream").value);		
		XS(qos+"/inf/bandwidth/type",	OBJ("conntype").value);
		if(!OBJ("en_qos").checked) 
		{
			XS(qos+"/inf/bandwidth/upstream", "");			
			XS(qos+"/runtime/device/qos/bwup", "0");
		}	
		XS(qos+"/runtime/device/qos/monitor", "0");
		return PXML.doc;
	},

	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	rgmode: function()
	{	
		devmode = XG(qos+"/runtime/device/layout");
		if(devmode == "bridge") return false;
		return true;
	},			
	activewan: function()
	{
		wan = XG(qos+"/runtime/device/activewan");		
		return wan;				
	},			
	OnClickQosEnable: function()
	{
		if (OBJ("en_qos").checked)
		{
			OBJ("auto_speed").setAttribute("modified", "false");
			OBJ("auto_speed").disabled = false;
			this.OnClickQosAuto();
			OBJ("conntype").setAttribute("modified", "false");
			OBJ("conntype").disabled = false;
		}
		else
		{
			OBJ("auto_speed").setAttribute("modified", "ignore");
			OBJ("auto_speed").disabled = true;
			OBJ("upstream").setAttribute("modified", "ignore");
			OBJ("upstream").disabled = true;
			OBJ("select_upstream").disabled = true;
			OBJ("conntype").setAttribute("modified", "ignore");
			OBJ("conntype").disabled = true;
		}
	},
	OnClickQosAuto: function()
	{
		if (OBJ("auto_speed").checked)
		{
			OBJ("upstream").setAttribute("modified", "ignore");
			OBJ("upstream").disabled = true;
			OBJ("select_upstream").disabled = true;
		}
		else
		{
			OBJ("upstream").setAttribute("modified", "false");
			OBJ("upstream").disabled = false;
			OBJ("select_upstream").disabled = false;
		}
	},
	OnChangeQosUpstream: function()
	{
		OBJ("upstream").value = OBJ("select_upstream").value;
		OBJ("select_upstream").value=0;
	}
}
</script>
