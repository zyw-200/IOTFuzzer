<style>
/* The CSS is only for this page.
 * Notice:
 * If the items are few, we put them here,
 * If the items are a lot, please put them into the file, htdocs/web/css/$TEMP_MYNAME.css.
 */
</style>

<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "ACL,DMZ.NAT-1,FIREWALL",
	OnLoad: function()
	{
		if (!this.rgmode)
		{
			BODY.DisableCfgElements(true);
		}
	},
	OnUnload: function() {},
	OnSubmitCallback: function ()	{},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var acl = PXML.FindModule("ACL");
		if (acl===""){ alert("InitValue ERROR!"); return false; }
		OBJ("spi").checked = (XG(acl+"/acl/spi/enable")==="1");
		
		if (!this.InitDMZ()) return false;
		if (!this.InitFWR()) return false;
		
		return true;
	},
	
	PreSubmit: function()
	{
		var acl = PXML.FindModule("ACL");
		XS(acl+"/acl/spi/enable",	OBJ("spi").checked ? "1":"0");
		
		if (!this.PreDMZ()) return null;
		if (!this.PreFWR()) return null;

		PXML.IgnoreModule("RUNTIME.INF.LAN-1");
		return PXML.doc;
	},

	IsDirty: null,
	Synchronize: function() {},

	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	dmz: null,
	passth: null,
	lanip: "<? echo INF_getcurripaddr("LAN-1"); ?>",
	mask: "<? echo INF_getcurrmask("LAN-1"); ?>",

	InitDMZ: function()
	{
		this.dmz = PXML.FindModule("DMZ.NAT-1");
		if (this.dmz==="")
		{
			alert("InitDMZ() ERROR!!");
			return false;
		}
		this.dmz += "/nat/entry/dmz";
		var hostid = XG(this.dmz+"/hostid");
		if (hostid=="")
		{
			OBJ("dmzhost").value = "";
		}
		else
		{
			var network = COMM_IPv4NETWORK(this.lanip, this.mask);
			OBJ("dmzhost").value = COMM_IPv4IPADDR(network, this.mask, hostid);
		}
		OBJ("dmzenable").checked = (XG(this.dmz+"/enable") === "1");
		COMM_SetSelectValue(OBJ("hostlist"), "");

		this.OnClickDMZEnable();
		return true;
	},

	InitFWR: function()
	{
		var acl = PXML.FindModule("FIREWALL");
		if (acl === "") {alert("ERROR!"); return false;}
		var fw = acl+"/acl/firewall";
		TEMP_RulesCount(fw, "rmd");
		var count = XG(fw+"/count");
		for (var i=1; i<=<?=$FW_MAX_COUNT?>; i+=1)
		{
			var b = fw+"/entry:"+i;
			OBJ("en_"+i).checked = XG(b+"/enable")==="1";
			OBJ("dsc_"+i).value = XG(b+"/description");

			if(XG(b+"/src/inf") !== "")	OBJ("src_inf_"+i).value = XG(b+"/src/inf");
			else				OBJ("src_inf_"+i).value = "";

			var startip = XG(b+"/src/host/start");
			var endip = XG(b+"/src/host/end");
			if(XG(b+"/description")!=="" && startip === "" && endip === "")
			{
				OBJ("src_startip_"+i).value = "*";
				OBJ("src_endip_"+i).value = "";
			}
			else
			{
				OBJ("src_startip_"+i).value = XG(b+"/src/host/start");
				OBJ("src_endip_"+i).value = XG(b+"/src/host/end");
			}

			if(XG(b+"/protocol") !== "")	OBJ("pro_"+i).value = XG(b+"/protocol");
			else				OBJ("pro_"+i).value = "ALL";
			if(XG(b+"/policy") !== "")	OBJ("action_"+i).value = XG(b+"/policy");
			else				OBJ("action_"+i).value = "ACCEPT";
			if(XG(b+"/dst/inf") !== "")	OBJ("dst_inf_"+i).value = XG(b+"/dst/inf");
			else				OBJ("dst_inf_"+i).value	= "";

			startip = XG(b+"/dst/host/start");
			endip = XG(b+"/dst/host/end");
			if(XG(b+"/description")!=="" && startip === "" && endip === "")
			{
				OBJ("dst_startip_"+i).value = "*";
				OBJ("dst_endip_"+i).value = "";
			}
			else
			{
				OBJ("dst_startip_"+i).value = XG(b+"/dst/host/start");
				OBJ("dst_endip_"+i).value = XG(b+"/dst/host/end");
			}
			
			if(XG(b+"/protocol") === "TCP" || XG(b+"/protocol") === "UDP")
			{
				if(XG(b+"/description")!=="" && XG(b+"/dst/port/start")==="" && XG("/dst/port/end")==="")
				{
					OBJ("dst_startport_"+i).value	= "*";
					OBJ("dst_endport_"+i).value	= "";
				}
				else
				{
					OBJ("dst_startport_"+i).value	= XG(b+"/dst/port/start");
					OBJ("dst_endport_"+i).value	= XG(b+"/dst/port/end");
				}
			}
			
			<?
			if ($FEATURE_NOSCH!="1")
			{
				echo 'if (XG(b+"/schedule")!=="")	OBJ("sch_"+i).value = XG(b+"/schedule");\n';
				echo 'else				OBJ("sch_"+i).value = "-1";\n';
			}
			?>

			this.OnChangeProt(i);
		}
		return true;
	},
	
	PreDMZ: function()
	{
		if (OBJ("dmzenable").checked)
		{
			var network = COMM_IPv4NETWORK(this.lanip, this.mask);

			var hostip	= OBJ("dmzhost").value;
			var hostnet	= COMM_IPv4NETWORK(hostip, this.mask);
			var maxhost	= COMM_IPv4MAXHOST(this.mask);
			if (network !== hostnet)
			{
				BODY.ShowAlert("<?echo i18n("The DMZ host should be in the same network of LAN-1!");?>");
				return null;
			}

			var hostid = COMM_IPv4HOST(hostip, this.mask);
			if (hostid === 0 || hostid === maxhost)
			{
				BODY.ShowAlert("<?echo i18n("Invalid DMZ host ID !");?>");
				return null;
			}

			XS(this.dmz+"/enable",	"1");
			XS(this.dmz+"/inf",		"LAN-1");
			XS(this.dmz+"/hostid",	COMM_IPv4HOST(hostip, this.mask));
		}
		else
		{
			XS(this.dmz+"/enable",	"0");
			XS(this.dmz+"/inf",		"");
			XS(this.dmz+"/hostid",	"");
		
		}
		return true;
	},
	
	PreFWR: function()
	{
		var acl = PXML.FindModule("FIREWALL");
		var fw = acl+"/acl/firewall";
		var old_count = XG(fw+"/count");
		var cur_count = 0;

		var network = COMM_IPv4NETWORK(this.lanip, this.mask);
		var maxhost = COMM_IPv4MAXHOST(this.mask);
		
		/* delete the old entries
		 * Notice: Must delte the entries from tail to head */
		while(old_count > 0)
		{
			XD(fw+"/entry:"+old_count);
			old_count -= 1;
		}
		/* update the entries */
		for (var i=1; i<=<?=$FW_MAX_COUNT?>; i+=1)
		{
			/* if the description field is empty, it means to remove this entry,
			 * so skip this entry. */
			if (OBJ("en_"+i).checked && OBJ("dsc_"+i).value === "")
			{
				BODY.ShowAlert("<?echo i18n("The Firewall Name can not be empty !");?>");
				OBJ("dsc_"+i).focus();
				return null;
			}
			if (OBJ("dsc_"+i).value!=="")
			{
				cur_count+=1;
				var b = fw+"/entry:"+cur_count;
				XS(b+"/uid",		"FWL-"+i);
				XS(b+"/enable",		OBJ("en_"+i).checked ? "1" : "0");
				XS(b+"/description",	OBJ("dsc_"+i).value);

				var sinf = OBJ("src_inf_"+i).value;
				XS(b+"/src/inf",	sinf);

				var sipstart = OBJ("src_startip_"+i).value;
				if(sinf !== "LAN-1" && sipstart === "*")
				{
					XS(b+"/src/host/start", "");
					XS(b+"/src/host/end", "");
				}
				else
				{
					var srcip1 = OBJ("src_startip_"+i).value;
					var srcip2 = OBJ("src_endip_"+i).value;
					var srcnet1 = COMM_IPv4NETWORK(srcip1, this.mask);
					var srcnet2 = COMM_IPv4NETWORK(srcip2, this.mask);
					
				
					if (srcip1 === "")
					{
						BODY.ShowAlert("<?echo i18n("The starting IP address of source can't be empty.");?>");
						OBJ("src_startip_"+i).focus();
						return null;
					}
					else if( !check_ip_validity(srcip1) )
					{
						BODY.ShowAlert("<?echo i18n("The starting IP address of source isn't valid.");?>");
						OBJ("src_startip_"+i).focus();
						return null;
					}

					if( srcip2 !== "" && !check_ip_validity(srcip2))
					{
						BODY.ShowAlert("<?echo i18n("The ending IP address of source isn't valid.");?>");
						OBJ("src_endip_"+i).focus();
						return null;
					}
					
					if (sinf === "LAN-1")
					{
						if (srcnet1 === "0.0.0.0") 
						{
							BODY.ShowAlert("<?echo i18n("Incorrect source IP address. The start IP address is invalid.");?>");
							OBJ("src_startip_"+i).focus();
							return null;						
						}
						if (srcip2 !== "" && srcnet2 === "0.0.0.0") 
						{
							BODY.ShowAlert("<?echo i18n("Incorrect source IP address. The end IP address is invalid.");?>");
							OBJ("src_endip_"+i).focus();
							return null;						
						}
						if(network !== srcnet1)
						{
							BODY.ShowAlert("<?echo i18n("The Source IP should be in the same network of LAN!");?>");
							OBJ("src_startip_"+i).focus();
							return null;
						}
						if(srcip2 !== "" && network !== srcnet2)
						{
							BODY.ShowAlert("<?echo i18n("The Source IP should be in the same network of LAN!");?>");
							OBJ("src_endip_"+i).focus();
							return null;
						}
					}
					XS(b+"/src/host/start", OBJ("src_startip_"+i).value);
					XS(b+"/src/host/end", OBJ("src_endip_"+i).value);
				}

				XS(b+"/protocol",	OBJ("pro_"+i).value);
				XS(b+"/policy",		OBJ("action_"+i).value);
			
				var dinf = OBJ("dst_inf_"+i).value;
				XS(b+"/dst/inf",	dinf);

				var dipstart = OBJ("dst_startip_"+i).value;
				if(dinf !== "LAN-1" && dipstart === "*")
				{
					XS(b+"/dst/host/start","");
					XS(b+"/dst/host/end","");
				}
				else
				{
					var dstip1 = OBJ("dst_startip_"+i).value;
					var dstip2 = OBJ("dst_endip_"+i).value;
					var dstnet1 = COMM_IPv4NETWORK(dstip1, this.mask);
					var dstnet2 = COMM_IPv4NETWORK(dstip2, this.mask);
					
					if (dstip1 === "")
					{
						BODY.ShowAlert("<?echo i18n("The starting IP address of destination can't be empty.");?>");
						OBJ("dst_startip_"+i).focus();
						return null;
					}else if( !check_ip_validity(dstip1) )
					{
						BODY.ShowAlert("<?echo i18n("The starting IP address of destination isn't valid.");?>");
						OBJ("dst_startip_"+i).focus();
						return null;
					}
					
					if( dstip2 !== "" && !check_ip_validity(dstip2) )
					{
						BODY.ShowAlert("<?echo i18n("The ending IP address of destination isn't valid.");?>");
						OBJ("dst_endip_"+i).focus();
						return null;
					}
					
					if (dinf === "LAN-1")
					{
						if (dstnet1 === "0.0.0.0") 
						{
							BODY.ShowAlert("<?echo i18n("Incorrect Dest IP address. The start IP address is invalid.");?>");
							OBJ("dst_startip_"+i).focus();
							return null;						
						}
						if (dstip2 !== "" && dstnet2 === "0.0.0.0") 
						{
							BODY.ShowAlert("<?echo i18n("Incorrect Dest IP address. The end IP address is invalid.");?>");
							OBJ("dst_endip_"+i).focus();
							return null;						
						}
						if(network !== dstnet1 || (dstip2 !== "" && network !== dstnet2))
						{
							BODY.ShowAlert("<?echo i18n("The Dest IP should be in the same network of LAN!");?>");
							OBJ("dst_startip_"+i).focus();
							return null;
						}
					}
					XS(b+"/dst/host/start", OBJ("dst_startip_"+i).value);
					XS(b+"/dst/host/end", 	OBJ("dst_endip_"+i).value);
				}

				var dstartport = OBJ("dst_startport_"+i).value;
				var dendport = OBJ("dst_endport_"+i).value;
				if(OBJ("pro_"+i).value === "TCP" || OBJ("pro_"+i).value === "UDP")
				{
					if(dstartport === "")
					{
						BODY.ShowAlert("<?echo i18n("The starting port of destination can't be empty.");?>");
						OBJ("dst_startport_"+i).focus();
						return null;
					}
					if(dstartport !== "")
					{
						if(dstartport.charAt(0) === "0")
						{
							BODY.ShowAlert("<?echo i18n("Invalid Port !");?>");
							OBJ("dst_startport_"+i).focus();
							return null;
						}
					}
					if(dendport !== "")
					{
						if(dendport.charAt(0) === "0")
						{
							BODY.ShowAlert("<?echo i18n("Invalid Port !");?>");
							OBJ("dst_endport_"+i).focus();
							return null;
						}
					}
					if(dstartport === "*")
					{
						XS(b+"/dst/port/start",	"");
						XS(b+"/dst/port/end",	"");
					}
					else
					{
						XS(b+"/dst/port/start",	dstartport);
						XS(b+"/dst/port/end",	dendport);
					}
				}
				<?
				if ($FEATURE_NOSCH!="1")
				{
					echo 'XS(b+"/schedule",	(OBJ("sch_"+i).value==="-1") ? "" : OBJ("sch_"+i).value);\n';
				}
				?>
				//check the different rules has the same neme or not
				for(var j="1"; j < i ; j++)
				{
					var dsc = OBJ("dsc_"+i).value;
					if(OBJ("dsc_"+i).value === OBJ("dsc_"+j).value)
					{
						BODY.ShowAlert('<?echo i18n("The Name ");?>"'+dsc+'\"<?echo i18n(" is already used !");?>');
						return null;
					}
				}
				//check same rule exist or not
				for(j="1"; j < i ; j++)
				{
					var dsc = OBJ("dsc_"+i).value;
					if(OBJ("src_inf_"+j).value === OBJ("src_inf_"+i).value
					&& OBJ("src_startip_"+j).value === OBJ("src_startip_"+i).value
					&& OBJ("src_endip_"+j).value === OBJ("src_endip_"+i).value
					&& OBJ("pro_"+j).value === OBJ("pro_"+i).value
					&& OBJ("action_"+j).value === OBJ("action_"+i).value
					&& OBJ("dst_inf_"+j).value === OBJ("dst_inf_"+i).value
					&& OBJ("dst_startip_"+j).value === OBJ("dst_startip_"+i).value
					&& OBJ("dst_endip_"+j).value === OBJ("dst_endip_"+i).value<?
					if ($FEATURE_NOSCH!="1") echo '&& OBJ("sch_"+j).value === OBJ("sch_"+i).value\n';
					?>&& ((OBJ("pro_"+j).value !== "TCP" && OBJ("pro_"+j).value !== "UDP")||
					(OBJ("dst_startport_"+j).value === OBJ("dst_startport_"+i).value
					&& OBJ("dst_endport_"+j).value === OBJ("dst_endport_"+i).value)))
					{
						BODY.ShowAlert('<?echo i18n("The Rule ");?>"'+dsc+'\"<?echo i18n(" is already existed !");?>');
						return null;
					}
				}
			}
		}
		/* we only handle 'count' here, the 'seqno' and 'uid' will handle by setcfg.
		 * so DO NOT modified/generate 'seqno' and 'uid' here. */
		XS(fw+"/count", cur_count);
		return true;
	},

	OnClickDMZEnable: function()
	{
		if (OBJ("dmzenable").checked)
		{
			OBJ("dmzhost").setAttribute("modified", "false");
			OBJ("dmzhost").disabled = false;
			OBJ("dmzadd").disabled = false;
			OBJ("hostlist").disabled = false;
		}
		else
		{
			OBJ("dmzhost").setAttribute("modified", "ignore");
			OBJ("dmzhost").disabled = true;
			OBJ("dmzadd").disabled = true;
			OBJ("hostlist").disabled = true;
		}
	},
	OnClickDMZAdd: function()
	{
		if(OBJ("hostlist").value === "")
		{
			BODY.ShowAlert("<?echo i18n("Please select a machine first!");?>");
			return null;
		}
		OBJ("dmzhost").value = OBJ("hostlist").value;
	},
	OnChangeProt: function(index)
	{
		var prot = OBJ("pro_"+index).value;

		if (prot==="TCP" || prot==="UDP")
		{
			OBJ("dst_startport_"+index).disabled = false;
			OBJ("dst_endport_"+index).disabled = false;
		}
		else
		{
			OBJ("dst_startport_"+index).disabled = true;
			OBJ("dst_endport_"+index).disabled = true;
		}
	},
	CursorFocus: function(node)
	{
		var i = node.lastIndexOf("entry:");
		var idx = node.charAt(i+6);
		if (node.lastIndexOf("inf") != "-1") OBJ("dsc_"+idx).focus();
		if (node.lastIndexOf("src/host") != "-1") OBJ("src_startip_"+idx).focus();
		if (node.lastIndexOf("dst/host") != "-1") OBJ("dst_startip_"+idx).focus();
		if (node.lastIndexOf("dst/port") != "-1") OBJ("dst_startport_"+idx).focus();
	}
}


function check_ip_validity(ipstr)
{
	var vals = ipstr.split(".");
	if (vals.length!=4) 
		return false;
	
	for (var i=0; i<4; i++)
	{
		if (!TEMP_IsDigit(vals[i]) || vals[i]>255)
			return false;
	}
	return true;
}
</script>
