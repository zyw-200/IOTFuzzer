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
	services: "ROUTE6.STATIC",
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
		var p = PXML.FindModule("ROUTE6.STATIC");
		if (p === "") { alert("InitValues() ERROR !"); return false; }
		p += "/route6/static";
		
		//TEMP_RulesCount(p, "rmd");
		/* load rules into table */
		var count = XG(p+"/count");
		for (var i=1; i<=<?=$ROUTING_MAX_COUNT?>; i++)
		{
			var b = p+"/entry:"+i;
			//OBJ("uid_"+i).value		= XG(b+"/uid");
			OBJ("dsc_"+i).value		= XG(b+"/description");
			OBJ("enable_"+i).checked	= (XG(b+"/enable") == "1") ? true:false;
			OBJ("dest1_"+i).value		= XG(b+"/network");
			OBJ("dest2_"+i).value		= XG(b+"/prefix");
			if(XG(b+"/inf") != "")		OBJ("inf_"+i).value = XG(b+"/inf");
			else				OBJ("inf_"+i).value = "WAN-4";
			OBJ("gateway_"+i).value		= XG(b+"/via");
			OBJ("metric_"+i).value		= XG(b+"/metric");
		}
		return true;
	},
	PreSubmit: function()
	{
		
		var p = PXML.FindModule("ROUTE6.STATIC");
		p += "/route6/static";
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
			var dsc		= OBJ("dsc_"+i).value;
			var inf		= OBJ("inf_"+i).value;
			var network	= OBJ("dest1_"+i).value;
			var pfxlen	= OBJ("dest2_"+i).value;
			var gw		= OBJ("gateway_"+i).value;
			var mtrc	= OBJ("metric_"+i).value;
			if(en === "1" || network != "" || pfxlen != "" || gw != "")
			{
				cur_count++;
				var b = p + "/entry:" + cur_count;
				XS(b + "/uid",		"SRT-"+cur_count);
				XS(b + "/description",	dsc);
				XS(b + "/enable",	en);
				XS(b + "/inf",		inf);
				XS(b + "/network",	network);
				XS(b + "/prefix",	pfxlen);
				XS(b + "/via",		gw);
				XS(b + "/metric",	mtrc);
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
		if(node.charAt(i+7)==="/") var idx = parseInt(node.charAt(i+6), 10);
		else var idx = parseInt(node.charAt(i+6), 10)*10 + parseInt(node.charAt(i+7), 10);
		var indx = 1;
		var valid_dsct_cnt = 0;		
		for(indx=1; indx <= <?=$ROUTING_MAX_COUNT?>; indx++)
		{
			if(OBJ("dest1_"+indx).value!=="") valid_dsct_cnt++;
			if(valid_dsct_cnt===idx) break;
		}
		if(node.match("network"))		OBJ("dest1_"+indx).focus();
		else if(node.match("prefix"))	OBJ("dest2_"+indx).focus();
		else if(node.match("via"))		OBJ("gateway_"+indx).focus();
	}	  	
};

</script>
