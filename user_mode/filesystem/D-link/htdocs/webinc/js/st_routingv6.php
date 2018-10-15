<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "<?
		$layout = query("/runtime/device/layout");
	
		if ($layout=="router")
			echo "RUNTIME.ROUTE.DYNAMIC";
		//	echo "INET.WAN-1,INET.WAN-3,INET.WAN-4,INET.LAN-4,RUNTIME.INF.LAN-1,RUNTIME.INF.LAN-4,RUNTIME.INF.WAN-1,RUNTIME.INF.WAN-3,RUNTIME.INF.WAN-4";
		//else
		//	echo "RUNTIME.INF.BRIDGE-1";
		?>",
	OnLoad: function() { BODY.CleanTable("routingv6_list"); },
	OnUnload: function() {},
	OnSubmitCallback: function ()
	{
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		//PXML.doc.dbgdump();
		var stsp = PXML.FindModule("RUNTIME.ROUTE.DYNAMIC");
		var cnt = XG(stsp+"/runtime/dynamic/route6/entry#");
		if(cnt=="") cnt = 0;
		for (var i=1; i<=cnt; i++)
		{
			var uid = "DUMMY-"+i;
			var ipaddr = XG(stsp+"/runtime/dynamic/route6/entry:"+i+"/ipaddr");
			var pfxlen = XG(stsp+"/runtime/dynamic/route6/entry:"+i+"/prefix");
			var ipstr = ipaddr+"/"+pfxlen;
			var gw = XG(stsp+"/runtime/dynamic/route6/entry:"+i+"/gateway");
			var metric = XG(stsp+"/runtime/dynamic/route6/entry:"+i+"/metric");
			var str = XG(stsp+"/runtime/dynamic/route6/entry:"+i+"/inf");
			var index = str.lastIndexOf("-");
			var inf = str.substring(0,index);
			if(index=="-1") inf=str;
			var data = [ipstr, gw, metric, inf];
			var type = ["text", "text", "text", "text"];
			BODY.InjectTable("routingv6_list", uid, data, type);
		}
		return true;
	},
	PreSubmit: function()
	{
	},
	IsDirty: null,
	Synchronize: function() {}
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
}
</script>
