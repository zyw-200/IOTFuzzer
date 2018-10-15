<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "ICMP.WAN-1,ICMP.WAN-2,PHYINF.WAN-1,MULTICAST,UPNP.LAN-1,WIFI.PHYINF",
	OnLoad: function()
	{
		if (!this.rgmode)
		{
			OBJ("upnp").disabled = true;
			OBJ("icmprsp").disabled = true;
			OBJ("wanspeed").disabled = true;
			OBJ("mcast").disabled = true;
		}
	},
	OnUnload: function() {},
	OnSubmitCallback: function ()	{},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var upnp = PXML.FindModule("UPNP.LAN-1");
		var icmprsp = PXML.FindModule("ICMP.WAN-1");
		var phy = PXML.FindModule("PHYINF.WAN-1");
		var wanphyuid = PXML.doc.Get(phy+"/inf/phyinf");
		var wan = PXML.doc.GetPathByTarget(phy, "phyinf", "uid", wanphyuid, false);
	
		var mcast = PXML.FindModule("MULTICAST");
		if (upnp==="" || icmprsp==="" || mcast==="" || wan==="")
		{ alert("InitValue ERROR!"); return false; }

		OBJ("upnp").checked = (XG(upnp+"/inf/upnp/count") == 1);
		OBJ("icmprsp").checked = (XG(icmprsp+"/inf/icmp")==="ACCEPT");
		
		var speed = XG(wan+"/media/linktype");
		if(speed === "AUTO")
			{OBJ("wanspeed").value = "0";}
		else if(speed === "10F")
			{OBJ("wanspeed").value = "1";}
		else if(speed === "100F")
			{OBJ("wanspeed").value = "2";}
		
		OBJ("mcast").checked = (XG(mcast+"/device/multicast/igmpproxy")==="1");
		OBJ("enhance").checked = (XG(mcast+"/device/multicast/wifienhance")==="1");
		//PAGE.Click_Multicast_Enable();
	
		return true;
	},
	PreSubmit: function()
	{
		if (this.rgmode)
		{
			var upnp = PXML.FindModule("UPNP.LAN-1");
			var icmprsp = PXML.FindModule("ICMP.WAN-1");
			var icmp2rsp = PXML.FindModule("ICMP.WAN-2");
			var phy = PXML.FindModule("PHYINF.WAN-1");
			var wanphyuid = PXML.doc.Get(phy+"/inf/phyinf");
			var wan = PXML.doc.GetPathByTarget(phy, "phyinf", "uid", wanphyuid, false);
			XS(icmprsp+"/inf/icmp",	OBJ("icmprsp").checked ? "ACCEPT":"DROP");
			XS(icmp2rsp+"/inf/icmp",	OBJ("icmprsp").checked ? "ACCEPT":"DROP");
			XS(upnp+"/inf/upnp/count",	OBJ("upnp").checked ? "1":"0");		
			if(OBJ("upnp").checked)
			{
				XS(upnp+"/inf/upnp/entry:1", "urn:schemas-upnp-org:device:InternetGatewayDevice:1");			
			}
			else
			{
				XS(upnp+"/inf/upnp/entry:1", "");
			}
			
			var wanspeed = OBJ("wanspeed").value;
			if(wanspeed === "0")
				{XS(wan+"/media/linktype", "AUTO");}
			else if(wanspeed === "1")
				{XS(wan+"/media/linktype", "10F");}
			else if(wanspeed === "2")
				{XS(wan+"/media/linktype", "100F");}
		}
		else
		{
			PXML.IgnoreModule("UPNP.LAN-1");
			PXML.IgnoreModule("ICMP.WAN-1");
			PXML.IgnoreModule("ICMP.WAN-2");
			PXML.IgnoreModule("PHYINF.WAN-1");
		}
		var mcast = PXML.FindModule("MULTICAST");
		XS(mcast+"/device/multicast/igmpproxy", OBJ("mcast").checked ? "1":"0");
		XS(mcast+"/device/multicast/wifienhance", OBJ("enhance").checked ? "1":"0");
		PXML.CheckModule("WIFI", null, "ignore", null);

		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},

	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	Click_Multicast_Enable: function()
	{
		
		OBJ("enhance").disabled = OBJ("mcast").checked ? false : true;
	}
}

</script>
