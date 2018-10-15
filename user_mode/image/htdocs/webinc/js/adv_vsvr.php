<style>
/* The CSS is only for this page.
 * Notice:
 *	If the items are few, we put them here,
 *	If the items are a lot, please put them into the file, htdocs/web/css/$TEMP_MYNAME.css.
 */
select.broad	{ width: 135px; }
select.narrow	{ width: 65px; }
</style>

<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "VSVR.NAT-1",
	OnLoad: function()
	{
		/* draw the 'Application Name' select */
		var str = "";
		for(var i=1; i<=<?=$VSVR_MAX_COUNT?>; i+=1)
		{
			str = "";
			str += '<select id="app_'+i+'" class="broad">';
			for(var j=0; j<this.apps.length; j+=1)
				str += '<option value="'+j+'">'+this.apps[j].name+'</option>';
			str += '</select>';
			OBJ("span_app_"+i).innerHTML = str;
		}
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
		var p = PXML.FindModule("VSVR.NAT-1");
		if (p === "") alert("ERROR!");
		p += "/nat/entry/virtualserver";
		TEMP_RulesCount(p, "rmd");
		var count = XG(p+"/count");
		var netid = COMM_IPv4NETWORK(this.lanip, this.mask);
		for (var i=1; i<=<?=$VSVR_MAX_COUNT?>; i+=1)
		{
			var b = p+"/entry:"+i;
			OBJ("uid_"+i).value = XG(b+"/uid");
			OBJ("en_"+i).checked = XG(b+"/enable")==="1";
			OBJ("dsc_"+i).value = XG(b+"/description");
			if (XG(b+"/protocol")!=="")	OBJ("pro_"+i).value = XG(b+"/protocol");
			OBJ("pubport_"+i).value = (XG(b+"/external/start")==="") ? "" : XG(b+"/external/start");
			OBJ("priport_"+i).value = (XG(b+"/internal/start")==="") ? "" : XG(b+"/internal/start");
			COMM_SetSelectValue(OBJ("pro_"+i), (XG(b+"/protocol")=="")? "TCP+UDP":XG(b+"/protocol"));
			<?
			if ($FEATURE_NOSCH!="1")
			{
				echo 'COMM_SetSelectValue(OBJ("sch_"+i), (XG(b+"/schedule")=="")? "-1":XG(b+"/schedule"));\n';
			}
			?>
			var hostid = XG(b+"/internal/hostid");
			if (hostid !== "")	OBJ("ip_"+i).value = COMM_IPv4IPADDR(netid, this.mask, hostid);
			else				OBJ("ip_"+i).value = "";
			OBJ("pc_"+i).value = "";
		}
		return true;
	},
	PreSubmit: function()
	{
		var p = PXML.FindModule("VSVR.NAT-1");
		p += "/nat/entry/virtualserver";
		var old_count = parseInt(XG(p+"/count"), 10);
		var cur_count = 0;
		var cur_seqno = parseInt(XG(p+"/seqno"), 10);
		/* delete the old entries
		 * Notice: Must delte the entries from tail to head */
		while(old_count > 0)
		{
			XD(p+"/entry:"+old_count);
			old_count -= 1;
		}
		/* update the entries */
		for (var i=1; i<=<?=$VSVR_MAX_COUNT?>; i+=1)
		{
			if (OBJ("pubport_"+i).value!="" && !TEMP_IsDigit(OBJ("pubport_"+i).value))
			{
				BODY.ShowAlert("<?echo i18n("The input public port is invalid.");?>");
				OBJ("pubport_"+i).focus();
				return null;
			}
			if (OBJ("priport_"+i).value!="" && !TEMP_IsDigit(OBJ("priport_"+i).value))
			{
				BODY.ShowAlert("<?echo i18n("The input private port is invalid.");?>");
				OBJ("priport_"+i).focus();
				return null;
			}
			if (OBJ("ip_"+i).value!="" && !TEMP_CheckNetworkAddr(OBJ("ip_"+i).value, null, null))
			{
				BODY.ShowAlert("<?echo i18n("Invalid host IP address.");?>");
				OBJ("ip_"+i).focus();
				return null;
			}
			/* if the description field is empty, it means to remove this entry,
			 * so skip this entry. */
			if (OBJ("dsc_"+i).value!=="")
			{
				cur_count+=1;
				var b = p+"/entry:"+cur_count;
				XS(b+"/enable",			OBJ("en_"+i).checked ? "1" : "0");
				XS(b+"/uid",			OBJ("uid_"+i).value);
				if (OBJ("uid_"+i).value == "")
				{
					XS(b+"/uid",	"VSVR-"+cur_seqno);
					cur_seqno += 1;
				}
				XS(b+"/description",	OBJ("dsc_"+i).value);
				XS(b+"/external/start",	OBJ("pubport_"+i).value);
				XS(b+"/protocol",		OBJ("pro_"+i).value);
				<?
				if ($FEATURE_NOSCH!="1")
				{
					echo 'XS(b+"/schedule",		(OBJ("sch_"+i).value==="-1") ? "" : OBJ("sch_"+i).value);\n';
				}
				?>
				XS(b+"/internal/inf",	"LAN-1");
				if (OBJ("ip_"+i).value == "") XS(b+"/internal/hostid", "");
				else XS(b+"/internal/hostid",COMM_IPv4HOST(OBJ("ip_"+i).value, this.mask));
				XS(b+"/internal/start",	OBJ("priport_"+i).value);
			}
		}
		// Make sure the different rules have different names and public ports.
		for (var i=1; i<cur_count; i+=1)
		{
			for (var j=i+1; j<=cur_count; j+=1)
			{
				if(OBJ("dsc_"+i).value == OBJ("dsc_"+j).value) 
				{
					BODY.ShowAlert("<?echo i18n("The different rules could not set the same name.");?>");
					OBJ("dsc_"+j).focus();
					return null;
				}	
				if(OBJ("pubport_"+i).value == OBJ("pubport_"+j).value) 
				{
					BODY.ShowAlert("<?echo i18n("The different rules could not set the same public port.");?>");
					OBJ("pubport_"+j).focus();
					return null;
				}
			}	
		}	
		XS(p+"/count", cur_count);
		XS(p+"/seqno", cur_seqno);
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	apps: [	{name: "<?echo i18n("Application name");?>",
									protocol:"TCP", port:{ pri:"0",		pub:"0"}},
			{name: "TELNET",		protocol:"TCP", port:{ pri:"23",	pub:"23" }},
			{name: "HTTP",			protocol:"TCP", port:{ pri:"80",	pub:"80" }},
			{name: "HTTPS",			protocol:"TCP", port:{ pri:"443",	pub:"443" }},
			{name: "FTP",			protocol:"TCP", port:{ pri:"21",	pub:"21" }},
			{name: "DNS",			protocol:"UDP", port:{ pri:"53",	pub:"53" }},
			{name: "SMTP",			protocol:"TCP", port:{ pri:"25",	pub:"25" }},
			{name: "POP3",			protocol:"TCP", port:{ pri:"110",	pub:"110" }},
			{name: "H.323",			protocol:"TCP", port:{ pri:"1720",	pub:"1720" }},
			{name: "REMOTE DESKTOP",protocol:"TCP", port:{ pri:"3389",	pub:"3389" }},
			{name: "PPTP",			protocol:"TCP", port:{ pri:"1723",	pub:"1723" }},
			{name: "L2TP",			protocol:"UDP", port:{ pri:"1701",	pub:"1701" }}
		  ],
	lanip:	"<? echo INF_getcurripaddr("LAN-1"); ?>",
	mask:	"<? echo INF_getcurrmask("LAN-1"); ?>",
	OnClickAppArrow: function(idx)
	{
		var i = OBJ("app_"+idx).value;
		OBJ("dsc_"+idx).value = (i==="0") ? "" : PAGE.apps[i].name;
		OBJ("pro_"+idx).value = (PAGE.apps[i].protocol==="") ? "TCP+UDP" : PAGE.apps[i].protocol;
		OBJ("pubport_"+idx).value	= (PAGE.apps[i].port.pub==="") ? "0" : PAGE.apps[i].port.pub;
		OBJ("priport_"+idx).value	= (PAGE.apps[i].port.pri==="") ? "0" : PAGE.apps[i].port.pri;
	},
	OnClickPCArrow: function(idx)
	{
		OBJ("ip_"+idx).value = OBJ("pc_"+idx).value;
	},
	CursorFocus: function(node)
	{
		var i = node.lastIndexOf("entry:");
		var idx = node.charAt(i+6);
		if (node.lastIndexOf("description") != "-1") OBJ("dsc_"+idx).focus();
		if (node.lastIndexOf("internal/hostid") != "-1") OBJ("ip_"+idx).focus();
		if (node.lastIndexOf("external/start") != "-1") OBJ("pubport_"+idx).focus();
		if (node.lastIndexOf("internal/start") != "-1") OBJ("priport_"+idx).focus();
	}
};

</script>
