<style>
/* The CSS is only for this page.
 * Notice:
 *  If the items are few, we put them here,
 *  If the items are a lot, please put them into the file, htdocs/web/css/$TEMP_MYNAME.css.
 */
select.broad	{ width: 110px; }
select.narrow	{ width: 65px; }
</style>

<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "DEVICE.HOSTNAME,INET.LAN-1,DHCPS4.LAN-1,RUNTIME.INF.LAN-1,URLCTRL",
	OnLoad: function()
	{
		SetDelayTime(500);	//add delay for event updatelease finished
		BODY.CleanTable("reserves_list");
		BODY.CleanTable("leases_list");
		if (!this.rgmode)
		{
			BODY.DisableCfgElements(true);
		}
	},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result)
	{
		BODY.ShowContent();
		switch (code)
		{
		case "OK":
			if (this.ipdirty)
			{
				var msgArray =
				[
					'<?echo i18n("The LAN IP address of this device is changing.");?>',
					'<?echo i18n("It needs several seconds for the changes to take effect.");?>',
					'<?echo i18n("You may need to change the IP address of your computer to access the device.");?>',
					'<?echo i18n("You can access the device by clicking the link below.");?>',
					'<a href="http://'+OBJ("ipaddr").value+'" style="color:#0000ff;">http://'+OBJ("ipaddr").value+'</a>'
				];
				BODY.ShowMessage('<?echo i18n("LAN IP Address Changed");?>', msgArray);
			}
			else
			{
				BODY.OnReload();
			}
			break;
		case "BUSY":
			BODY.ShowAlert("<?echo i18n("Someone is configuring the device, please try again later.");?>");
			break;
		case "HEDWIG":
			if (result.Get("/hedwig/result")=="FAILED")
			{
				FocusObj(result);
				BODY.ShowAlert(result.Get("/hedwig/message"));
			}
			break;
		case "PIGWIDGEON":
			BODY.ShowAlert(result.Get("/pigwidgeon/message"));
			break;
		}
		return true;
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		if (!this.InitHostname()) return false;
		if (!this.InitLAN()) return false;
		if (!this.InitDHCPS()) return false;
		return true;
	},
	PreSubmit: function()
	{
		if (!this.PreHostname()) return null;
		if (!this.PreLAN()) return null;
		if (!this.PreDHCPS()) return null;
		PXML.IgnoreModule("DEVICE.LAYOUT");
		PXML.IgnoreModule("RUNTIME.INF.LAN-1");
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	lanip: null,
	inetp: null,
	dhcps4: null,
	dhcps4_inet: null,
	leasep: null,
	mask: null,
	ipdirty: false,
	InitHostname: function()
	{
		var b = PXML.FindModule("DEVICE.HOSTNAME");
		if (!b)
		{
			BODY.ShowAlert("InitHostname() ERROR!!!");
			return false;
		}

		OBJ("device").value = XG(b+"/device/hostname");
		return true;
	},
	PreHostname: function()
	{
		if (COMM_Equal(OBJ("device").getAttribute("modified"), "true"))
		{
		var b = PXML.FindModule("DEVICE.HOSTNAME");
		XS(b+"/device/hostname", OBJ("device").value);
			PXML.ActiveModule("DEVICE.HOSTNAME");
		}
		else
			PXML.IgnoreModule("DEVICE.HOSTNAME");
		return true;
	},
	InitLAN: function()
	{
		var lan	= PXML.FindModule("INET.LAN-1");
		var inetuid = XG(lan+"/inf/inet");
		this.inetp = GPBT(lan+"/inet", "entry", "uid", inetuid, false);
		if (!this.inetp)
		{
			BODY.ShowAlert("InitLAN() ERROR!!!");
			return false;
		}

		if (XG(this.inetp+"/addrtype") == "ipv4")
		{
			var b = this.inetp+"/ipv4";
			this.lanip = XG(b+"/ipaddr");
			this.mask = XG(b+"/mask");
			OBJ("ipaddr").value	= this.lanip;
			OBJ("netmask").value= COMM_IPv4INT2MASK(this.mask);
			OBJ("dnsr").checked	= XG(lan+"/inf/dns4")!="" ? true : false;
		}
		return true;
	},
	PreLAN: function()
	{
		var lan = PXML.FindModule("INET.LAN-1");
		var b = this.inetp+"/ipv4";

		var vals = OBJ("ipaddr").value.split(".");
		if (vals.length!=4)
		{
			BODY.ShowAlert("<?echo i18n("Invalid IP address");?>");
			OBJ("ipaddr").focus();
			return false;
		}
		for (var i=0; i<4; i++)
		{
			if (!TEMP_IsDigit(vals[i]) || vals[i]>255)
			{
				BODY.ShowAlert("<?echo i18n("Invalid IP address");?>");
				OBJ("ipaddr").focus();
				return false;
			}
		}
		this.mask = COMM_IPv4MASK2INT(OBJ("netmask").value);
		XS(b+"/ipaddr", OBJ("ipaddr").value);
		XS(b+"/mask", this.mask);
		if (OBJ("dhcpsvr").checked)	XS(lan+"/inf/dhcps4", "DHCPS4-1");
		else						XS(lan+"/inf/dhcps4", "");
		if (OBJ("dnsr").checked)	XS(lan+"/inf/dns4", "DNS4-1");
		else						XS(lan+"/inf/dns4", "");

		if (COMM_EqBOOL(OBJ("ipaddr").getAttribute("modified"), true))
		{
			this.ipdirty = true;
		}
		if (this.ipdirty||
			COMM_EqBOOL(OBJ("netmask").getAttribute("modified"), true)||
			COMM_EqBOOL(OBJ("dnsr").getAttribute("modified"), true)||
			COMM_EqBOOL(OBJ("dhcpsvr").getAttribute("modified"), true)
			)
		{
			PXML.DelayActiveModule("INET.LAN-1", "3");
		}
		else
		{
			PXML.IgnoreModule("INET.LAN-1");
		}

		return true;
	},
	InitDHCPS: function()
	{
		var svc = PXML.FindModule("DHCPS4.LAN-1");
		var inf1p = PXML.FindModule("RUNTIME.INF.LAN-1");
		if (!svc || !inf1p)
		{
			BODY.ShowAlert("InitDHCPS() ERROR !");
			return false;
		}
		this.dhcps4 = GPBT(svc+"/dhcps4", "entry", "uid", "DHCPS4-1", false);
		this.dhcps4_inet = svc + "/inet/entry";
		this.leasep = GPBT(inf1p+"/runtime", "inf", "uid", "LAN-1", false);
		TEMP_RulesCount(this.dhcps4+"/staticleases", "rmd");
		if (!this.dhcps4)
		{
			BODY.ShowAlert("InitDHCPS() ERROR !");
			return false;
		}
		this.leasep += "/dhcps4/leases";
		var tmp_ip = OBJ("ipaddr").value.substring(OBJ("ipaddr").value.lastIndexOf('.')+1, OBJ("ipaddr").value.length);
		var tmp_mask = OBJ("netmask").value.substring(OBJ("netmask").value.lastIndexOf('.')+1, OBJ("netmask").value.length)
		var startip = parseInt(XG(this.dhcps4+"/start"),10) + (tmp_ip & tmp_mask);
		var endip = parseInt(XG(this.dhcps4+"/end"),10) + (tmp_ip & tmp_mask);

		OBJ("domain").value		= XG(this.dhcps4+"/domain");
		OBJ("dhcpsvr").checked	= (XG(svc+"/inf/dhcps4")!="")? true : false;
		OBJ("startip").value	= startip;
		OBJ("endip").value		= endip;
		OBJ("leasetime").value	= Math.floor(XG(this.dhcps4+"/leasetime")/60);
		this.OnClickDHCPSvr();

		if (!this.leasep)	return true;	// in bridge mode, the value of this.leasep is null.
		entry = this.leasep+"/entry";
		cnt = XG(entry+"#");
		for (var i=1; i<=cnt; i++)
		{
			var uid		= "DUMMY_"+i;
			var host	= XG(entry+":"+i+"/hostname");
			var ipaddr	= XG(entry+":"+i+"/ipaddr");
			var mac		= XG(entry+":"+i+"/macaddr");
			var expires	= XG(entry+":"+i+"/expire");
			if(parseInt(expires, 10) == 0)
			{
				continue;
			}
			if (parseInt(expires, 10) > 6000000)
			{
				expires = "Never";
			}
			else if (parseInt(expires, 10) < 60)
			{
				expires = "< 1 <?echo i18n("Minute");?>";
			}
			else
			{
				var time= COMM_SecToStr(expires);
				expires = "";
				if (time["day"]>1)
				{
					expires = time["day"]+" <?echo i18n("Days");?> ";
				}
				else if (time["day"]>0)
				{
					expires = time["day"]+" <?echo i18n("Day");?> ";
				}
				if (time["hour"]>1)
				{
					expires += time["hour"]+" <?echo i18n("Hours");?> ";
				}
				else if (time["hour"]>0)
				{
					expires += time["hour"]+" <?echo i18n("Hour");?> ";
				}
				if (time["min"]>1)
				{
					expires += time["min"]+" <?echo i18n("Minutes");?>";
				}
				else if (time["min"]>0)
				{
					expires += time["min"]+" <?echo i18n("Minute");?>";
				}
			}
			var data	= [host, ipaddr, mac, expires];
			var type	= ["text", "text", "text", "text"];
			if (expires == "Never")
				BODY.InjectTable("reserves_list", uid, data, type);
			else
				BODY.InjectTable("leases_list", uid, data, type);
		}

		cnt = XG(this.dhcps4+"/staticleases/entry#");
		for (var i=1; i <= <?=$DHCP_MAX_COUNT?>; i++)
		{
			if (i <= cnt)
			{
				OBJ("en_"+i).checked = COMM_EqNUMBER(XG(this.dhcps4+"/staticleases/entry:"+i+"/enable"), 1);
				OBJ("desc_"+i).value = XG(this.dhcps4+"/staticleases/entry:"+i+"/description");
				OBJ("name_"+i).value = XG(this.dhcps4+"/staticleases/entry:"+i+"/hostname");
				OBJ("ip_"+i).value = COMM_IPv4IPADDR(this.lanip, this.mask, XG(this.dhcps4+"/staticleases/entry:"+i+"/hostid"));
				OBJ("mac_"+i).value = XG(this.dhcps4+"/staticleases/entry:"+i+"/macaddr");
			}
			else
			{
				OBJ("en_"+i).checked = false;
				OBJ("desc_"+i).value = OBJ("name_"+i).value = OBJ("ip_"+i).value = OBJ("mac_"+i).value = "";
			}
		}

		return true;
	},
	PreDHCPS: function()
	{
		var lan = PXML.FindModule("DHCPS4.LAN-1");
		var ipaddr = COMM_IPv4NETWORK(OBJ("ipaddr").value, "24");
		var maxhost = COMM_IPv4MAXHOST(this.mask)-1;
		var network = ipaddr.substring(0, ipaddr.lastIndexOf('.')+1);
		var hostid = parseInt(COMM_IPv4HOST(OBJ("ipaddr").value, this.mask), 10);
		var tmp_ip = OBJ("ipaddr").value.substring(OBJ("ipaddr").value.lastIndexOf('.')+1, OBJ("ipaddr").value.length);
		var tmp_mask = OBJ("netmask").value.substring(OBJ("netmask").value.lastIndexOf('.')+1, OBJ("netmask").value.length)
		var startip = parseInt(OBJ("startip").value, 10) - (tmp_ip & tmp_mask);
		var endip = parseInt(OBJ("endip").value, 10) - (tmp_ip & tmp_mask);

		if (isDomain(OBJ("domain").value))
			XS(this.dhcps4+"/domain", OBJ("domain").value);
		else
		{
			BODY.ShowAlert("<?echo i18n("The input domain name is illegal.");?>");
			OBJ("domain").focus();
			return false;
		}
		if (OBJ("dhcpsvr").checked)	XS(lan+"/inf/dhcps4",	"DHCPS4-1");
		else						XS(lan+"/inf/dhcps4",	"");
		if (OBJ("dnsr").checked)	XS(lan+"/inf/dns4",		"DNS4-1");
		else						XS(lan+"/inf/dns4",		"");
		if (COMM_EqBOOL(OBJ("dhcpsvr").checked, true))
		{
			if (!TEMP_IsDigit(OBJ("startip").value) || !TEMP_IsDigit(OBJ("endip").value))
			{
				BODY.ShowAlert("<?echo i18n("DHCP IP Address Range is invalid.");?>"); 
				return false;
			}
			if (hostid>=parseInt(OBJ("startip").value, 10) && hostid<=parseInt(OBJ("endip").value, 10))
			{
				BODY.ShowAlert("<?echo i18n("The Router IP Address is belong to the lease pool of DHCP server.");?>");
				return false;
			}
			if (!TEMP_IsDigit(OBJ("leasetime").value))
			{
				BODY.ShowAlert("<?echo i18n("The input lease time is invalid.");?>");
				return false;
			}
			if (this.mask >= 24 && (startip < 1 || endip > maxhost))
			{
				BODY.ShowAlert("<?echo i18n("DHCP IP Address Range is out of the boundary.");?>");  
				return false;
			}
			if (parseInt(OBJ("startip").value, 10) >= parseInt(OBJ("endip").value, 10))
			{
				BODY.ShowAlert("<?echo i18n("The start of DHCP IP Address Range should be smaller than the end.");?>");
				return false;
			}
	
			XS(this.dhcps4_inet+"/ipv4/ipaddr", OBJ("ipaddr").value);
			XS(this.dhcps4_inet+"/ipv4/mask", this.mask);
			XS(this.dhcps4+"/start", startip);
			XS(this.dhcps4+"/end", endip);
			XS(this.dhcps4+"/leasetime", OBJ("leasetime").value*60);
		}
		var cnt = XG(this.dhcps4+"/staticleases/count");
		for (var i=1; i<= cnt; i++)	XD(this.dhcps4+"/staticleases/entry");
		XS(this.dhcps4+"/staticleases/count", "0");
		for (var i=1; i <= <?=$DHCP_MAX_COUNT?>; i++)
		{
			if (OBJ("en_"+i).checked || OBJ("desc_"+i).value != "" || OBJ("ip_"+i).value != "" || OBJ("mac_"+i).value != "")
			{
				var mac = GetMAC(OBJ("mac_"+i).value);
				if (mac=="")
				{
					BODY.ShowAlert("<?echo i18n("Invalid MAC address value.");?>");
					OBJ("mac_"+i).focus();
					return false;
				}
				else
				{
					OBJ("mac_"+i).value = mac;
				}

				if (OBJ("desc_"+i).value=="")
				{
					BODY.ShowAlert("<?echo i18n("Invalid Computer Name");?>");
					OBJ("desc_"+i).focus();
					return false;
				}
				if (TEMP_CheckNetworkAddr(OBJ("ip_"+i).value, OBJ("ipaddr").value, this.mask))
				{
					var path = COMM_AddEntry(PXML.doc, this.dhcps4+"/staticleases", "STIP-");
					XS(path+"/enable",		OBJ("en_"+i).checked?"1":"0");
					XS(path+"/description",	OBJ("desc_"+i).value);
					XS(path+"/hostname",	OBJ("desc_"+i).value);
					XS(path+"/macaddr",		OBJ("mac_"+i).value);
					XS(path+"/hostid",		COMM_IPv4HOST(OBJ("ip_"+i).value, this.mask));
				}
				else
				{
					BODY.ShowAlert("<?echo i18n("Invalid IP address");?>");
					OBJ("ip_"+i).focus();
					return false;
				}
			}
		}

		PXML.ActiveModule("DHCPS4.LAN-1");
		return true;
	},
	OnClickDHCPSvr: function()
	{
		if (OBJ("dhcpsvr").checked && this.rgmode)
		{
			OBJ("startip").setAttribute("modified", "false");
			OBJ("endip").setAttribute("modified", "false");
			OBJ("leasetime").setAttribute("modified", "false");
			OBJ("startip").disabled = false;
			OBJ("endip").disabled = false;
			OBJ("leasetime").disabled = false;
		}
		else
		{
			OBJ("startip").setAttribute("modified",	"ignore");
			OBJ("endip").setAttribute("modified", "ignore");
			OBJ("leasetime").setAttribute("modified", "ignore");
			OBJ("startip").disabled = true;
			OBJ("endip").disabled = true;
			OBJ("leasetime").disabled = true;
		}
	},
	OnChangeGetClient: function(idx)
	{
		var ipaddr = OBJ("pc_"+idx).value;
		var entry = PAGE.leasep+"/entry";
		var cnt = XG(entry+"#");
		if (ipaddr == "")	return;
		for (var i=1; i<=cnt; i++)
		{
			if (XG(entry+":"+i+"/ipaddr") == ipaddr)
			{
				OBJ("en_"+idx).checked= true;
				OBJ("desc_"+idx).value= XG(entry+":"+i+"/hostname");
				OBJ("name_"+idx).value= XG(entry+":"+i+"/hostname");
				OBJ("ip_"+idx).value  = XG(entry+":"+i+"/ipaddr");
				OBJ("mac_"+idx).value = XG(entry+":"+i+"/macaddr");
				OBJ("pc_"+idx).selectedIndex = 0;
				return;
			}
		}
	}
}

function FocusObj(result)
{
	var found = true;
	var node = result.Get("/hedwig/node");
	var nArray = node.split("/");
	var len = nArray.length;
	var name = nArray[len-1];
	if (node.match("inet"))
	{
		switch (name)
		{
		case "ipaddr":
			OBJ("ipaddr").focus();
			break;
		case "mask":
			OBJ("netmask").focus();
			break;
		default:
			found = false;
			break;
		}
	}
	else if (node.match("dhcps4"))
	{
		switch (name)
		{
		case "start":
			OBJ("startip").focus();
			break;
		case "end":
			OBJ("endip").focus();
			break;
		case "leasetime":
			OBJ("leasetime").focus();
			break;
		case "hostid":
			OBJ("ip_"+GetEntryNo(nArray[len-2])).focus();
			break;
		case "macaddr":
			OBJ("mac_"+GetEntryNo(nArray[len-2])).focus();
			break;
		default:
			found = false;
			break;
		}
	}
	else
	{
		found = false;
	}

	return found;
}

function isDomain(domain)
{
	var rlt = true;
	var dArray = new Array();
	if (domain.length==0)	return rlt;
	else					dArray = domain.split(".");

	/* the total length of a domain name is restricted to 255 octets or less. */
	if (domain.length > 255)
	{
		rlt = false;
	}
	for (var i=0; i<dArray.length; i++)
	{
		var reg = new RegExp("[A-Za-z0-9\-]{"+dArray[i].length+"}");
		/* the label must start with a letter */
		if (!dArray[i].match(/^[A-Za-z]/))
		{
			rlt = false;
			break;
		}
		/* the label must end with a letter or digit. */
		else if (!dArray[i].match(/[A-Za-z0-9]$/))
		{
			rlt = false;
			break;
		}
		/* the label must be 63 characters or less. */
		else if (dArray[i].length>63)
		{
			rlt = false;
			break;
		}
		/* the label has interior characters that only letters, digits and hyphen */
		else if (!reg.exec(dArray[i]))
		{
			rlt = false;
			break;
		}
	}

	return rlt;
}

function GetIPLastField(ipaddr)
{
	return ipaddr.substring(ipaddr.lastIndexOf('.')+1);
}
function SetDelayTime(millis)
{
	var date = new Date();
	var curDate = null;
	curDate = new Date();
	do { curDate = new Date(); }
	while(curDate-date < millis);
}
function GetMAC(m)
{
	var myMAC="";
	if (m.search(":") != -1)	var tmp=m.split(":");
	else				var tmp=m.split("-");
	if (m == "" || tmp.length != 6)
		return "";

	for (var i=0; i<tmp.length; i++)
	{
		if (tmp[i].length==1)
			tmp[i]="0"+tmp[i];
		else if (tmp[i].length==0||tmp[i].length>2)
			return "";
	}
	myMAC = tmp[0];
	for (var i=1; i<tmp.length; i++)
	{
		myMAC = myMAC + ':' + tmp[i];
	}
	return myMAC;
}
function GetEntryNo(en)
{
	return en.substring(en.lastIndexOf(':')+1);
}
</script>
