<style>
/* The CSS is only for this page.
 * Notice:
 *	If the items are few, we put them here,
 *	If the items are a lot, please put them into the file, htdocs/web/css/$TEMP_MYNAME.css.
 */
</style>

<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "ROUTE.STATIC",
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
		var p = PXML.FindModule("ROUTE.STATIC");
		if (p === "") { alert("InitValues() ERROR !"); return false; }
		p += "/route/static";
		
		TEMP_RulesCount(p, "rmd");
		/* load rules into table */
		var count = XG(p+"/count");
		for (var i=1; i<=<?=$ROUTING_MAX_COUNT?>; i++)
		{
			var b = p+"/entry:"+i;
			OBJ("uid_"+i).value		= XG(b+"/uid");
			OBJ("enable_"+i).checked	= (XG(b+"/enable") == "1") ? true:false;
			OBJ("dstip_"+i).value		= XG(b+"/network");
			if(XG(b+"/mask") != "")		OBJ("netmask_"+i).value	= COMM_IPv4INT2MASK(XG(b+"/mask"));
			else				OBJ("netmask_"+i).value = "";
			if(XG(b+"/inf") != "")		OBJ("inf_"+i).value = XG(b+"/inf");
			else				OBJ("inf_"+i).value = "WAN-1";
			OBJ("gateway_"+i).value		= XG(b+"/via");
		}
		return true;
	},
	PreSubmit: function()
	{
		
		var p = PXML.FindModule("ROUTE.STATIC");
		p += "/route/static";
		var old_count = XG(p + "/count");
		var cur_count = 0;
		/* delete the old entries
		 * Notice: Must delte the entries from tail to head */
		while(old_count > 0)
		{
			XD(p + "entry:" + old_count);
			old_count--;
		}
		
		for(var i = 1;i <= <?=$ROUTING_MAX_COUNT?>;i++)
		{
			var en		= OBJ("enable_"+i).checked ? "1" : "0";
			var inf		= OBJ("inf_"+i).value;
			var dip		= OBJ("dstip_"+i).value;
			var netmask	= OBJ("netmask_"+i).value;
			var gw		= OBJ("gateway_"+i).value;
			if(en === "1" || dip != "" || netmask != "" || gw != "")
			{
				var vals = dip.split(".");
				if (vals.length!=4 || dip === "0.0.0.0")
				{
					BODY.ShowAlert("<?echo i18n("Invalid Destination IP address.");?>");
					OBJ("dstip_"+i).focus();
					return null;
				}
				for (var j=0; j<4; j++)
				{
					if (!TEMP_IsDigit(vals[j]) || vals[j]>255)
					{
						BODY.ShowAlert("<?echo i18n("Invalid Destination IP address.");?>");
						OBJ("dstip_"+i).focus();
						return null;
					}
				}
				if(netmask === "")
				{
					BODY.ShowAlert("<?echo i18n("Invalid Subnet Mask.");?>");
					OBJ("netmask_"+i).focus();
					return null;
				}
				if(gw === "")
				{
					BODY.ShowAlert("<?echo i18n("Invalid Gateway.");?>");
					OBJ("gateway_"+i).focus();
					return null;
				}
				cur_count++;
				var b = p + "/entry:" + cur_count;
				XS(b + "/uid",		"SRT-"+cur_count);
				XS(b + "/enable",	en);
				XS(b + "/inf",		inf);
				var mask = COMM_IPv4MASK2INT(netmask);
				OBJ("dstip_"+i).value = COMM_IPv4NETWORK(dip, mask);
				XS(b + "/network",	OBJ("dstip_"+i).value);
				XS(b + "/mask",		mask);
				XS(b + "/via",		gw);
			}
		}
		
		XS(p + "/count", cur_count);
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	CursorFocus: function(node)
	{
		var i = node.lastIndexOf("entry:");
		var idx = node.charAt(i+6);
		if (node.lastIndexOf("network") != "-1") OBJ("dstip_"+idx).focus();
		if (node.lastIndexOf("mask") != "-1") OBJ("netmask_"+idx).focus();
		if (node.lastIndexOf("via") != "-1") OBJ("gateway_"+idx).focus();
	}	  	
};

</script>
