<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "<?
		$layout = query("/runtime/device/layout");
	
		echo "RUNTIME.PHYINF,";
		if ($layout=="router")
			echo "INET.WAN-1,INET.WAN-3,INET.WAN-4,INET.LAN-4,RUNTIME.INF.LAN-1,RUNTIME.INF.LAN-4,RUNTIME.INF.WAN-1,RUNTIME.INF.WAN-3,RUNTIME.INF.WAN-4";
		else
			echo "RUNTIME.INF.BRIDGE-1";
		?>",
	OnLoad: function() { BODY.CleanTable("client6_list"); },
	OnUnload: function() {},
	OnSubmitCallback: function ()
	{
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var rlan1 = PXML.FindModule("RUNTIME.INF.LAN-1");
		var rlan4 = PXML.FindModule("RUNTIME.INF.LAN-4");
		//PXML.doc.dbgdump();
<?
		echo "\t\tif (\"".$layout."\"==\"router\")";
?>
		{
			if (!this.InitWAN()) return false;
			if (!this.InitLAN()) return false;
		}
		else
		{
			OBJ("ipv6_bridge").style.display = "block";
			OBJ("ipv6").style.display 		 = "none";
			OBJ("ipv6_client").style.display = "none";
		}

		var pfxlen = XG(rlan4+"/runtime/inf/dhcps6/prefix");
		var cntlan1 = XG(rlan1+"/runtime/inf/dhcps4/leases/entry#");
		var cntlan4 = XG(rlan4+"/runtime/inf/dhcps6/leases/entry#");
		if(cntlan1=="") cntlan1 = 0;
		if(cntlan4=="") cntlan4 = 0;
		for (var i=1; i<=cntlan4; i++)
		{
			var uid = "DUMMY-"+i;
			var ipaddr = XG(rlan4+"/runtime/inf/dhcps6/leases/entry:"+i+"/ipaddr");
			var ipstr = ipaddr+"/"+pfxlen;
			var mac = XG(rlan4+"/runtime/inf/dhcps6/leases/entry:"+i+"/macaddr");
			for (var j=1; j<=cntlan1; j++)
			{
				var mactmp = XG(rlan1+"/runtime/inf/dhcps4/leases/entry:"+j+"/macaddr");
				if(mactmp.toUpperCase() == mac)
				{
					var hostname = XG(rlan1+"/runtime/inf/dhcps4/leases/entry:"+j+"/hostname");
					break;
				}
			}
			var data = [ipstr, hostname];
			var type = ["text", "text"];
			BODY.InjectTable("client6_list", uid, data, type);
		}
		return true;
	},
	PreSubmit: function()
	{
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	waninetp: null,
	rwanphyp: null,
	rlanphyp: null,
	wanip: null,
	lanip: null,
	inetp: null,
	prefix: null,
	is_ll: null,
	
	InitWAN: function ()
	{
		var wan	= PXML.FindModule("INET.WAN-4");
		var wan1 = PXML.FindModule("INET.WAN-1");
		var wan3 = PXML.FindModule("INET.WAN-3");
		var rphy = PXML.FindModule("RUNTIME.PHYINF");
		var rwan = PXML.FindModule("RUNTIME.INF.WAN-4");
		var is_ppp6 = 0;
		var is_ppp10 = 0;
		//var is_ll = 0;
		var wan3inetuid = XG(wan3+"/inf/inet");
		var wan3inetp = GPBT(wan3+"/inet", "entry", "uid", wan3inetuid, false);
		var wan1inetuid = XG(wan1+"/inf/inet");
		var wan1inetp = GPBT(wan1+"/inet", "entry", "uid", wan1inetuid, false);
		if(XG(wan3inetp+"/addrtype") == "ppp6")
		{
			wan = PXML.FindModule("INET.WAN-3");
			rwan = PXML.FindModule("RUNTIME.INF.WAN-3");
			is_ppp6 = 1;
		}
		if(XG(wan1inetp+"/addrtype") == "ppp10")
		{
			wan = PXML.FindModule("INET.WAN-1");
			rwan = PXML.FindModule("RUNTIME.INF.WAN-1");
			is_ppp10 = 1;
		}
		var waninetuid = XG  (wan+"/inf/inet");
		var wanphyuid = XG  (wan+"/inf/phyinf");
		this.waninetp = GPBT(wan+"/inet", "entry", "uid", waninetuid, false);
		this.rwanphyp = GPBT(rphy+"/runtime", "phyinf", "uid", wanphyuid, false);
		//if(XG(this.waninetp+"/ipv6/mode") == "" && is_ppp6 != 1 && is_ppp10 != 1)
		if(XG(wan+"/inf/active") == 0 && is_ppp6 != 1 && is_ppp10 != 1)
		{
			wan = PXML.FindModule("INET.WAN-3");
			rwan = PXML.FindModule("RUNTIME.INF.WAN-3");
			this.is_ll = 1;
		}

		if(this.is_ll==1) 
		{
			OBJ("ll_ipv6").style.display 		 = "";
		}
		else
		{
			OBJ("ipv6").style.display 		 = "";
		}

		var str_networkstatus = str_Disconnected = "<?echo i18n("Disconnected");?>";
		var str_Connected = "<?echo i18n("Connected");?>";
		var wancable_status=0;
		if ((!this.waninetp))
		{
			BODY.ShowAlert("InitWAN() ERROR!!!");
			return false;
		}

		if((XG  (this.rwanphyp+"/linkstatus")!="0")&&(XG  (this.rwanphyp+"/linkstatus")!=""))
		{
		   wancable_status=1;
		}
			
		if(!this.is_ll)
			OBJ("status").innerHTML  = wancable_status==1 ? str_Connected:str_Disconnected;
		else
		{
			OBJ("status").innerHTML  = str_Disconnected;
		   	wancable_status=0;
		}
			

		rwan = rwan+"/runtime/inf/inet";
		if ((XG  (this.waninetp+"/addrtype") == "ipv6")&& wancable_status==1)
		{
			var str_wantype = XG(this.waninetp+"/ipv6/mode");
			var str_wanipaddr = XG(rwan+"/ipv6/ipaddr");
			var str_wanprefix = "/"+XG(rwan+"/ipv6/prefix");
			var str_wangateway = XG(rwan+"/ipv6/gateway");
			var str_wanDNSserver  = XG(rwan+"/ipv6/dns:1")?XG(rwan+"/ipv6/dns:1"):"";
			var str_wanDNSserver2 = XG(rwan+"/ipv6/dns:2")?XG(rwan+"/ipv6/dns:2"):"";
		}
		else if (is_ppp6 == 1 && wancable_status==1)
		{
			rwan = PXML.FindModule("RUNTIME.INF.WAN-3");
			rwan = rwan+"/runtime/inf/inet";
			var rwan4 = PXML.FindModule("RUNTIME.INF.WAN-4");
			var str_wantype = "PPPoE";
			var str_wanipaddr = XG(rwan+"/ppp6/local");
			var str_wanprefix = "/64";
			var str_wangateway = XG(rwan+"/ppp6/peer");
			var str_wanDNSserver = XG(rwan+"/ppp6/dns:1");
			var str_wanDNSserver2 = XG(rwan+"/ppp6/dns:2");
		}
		else if (is_ppp10 == 1 && wancable_status==1)
		{
			rwan = PXML.FindModule("RUNTIME.INF.WAN-1");
			rwanc = rwan+"/runtime/inf/child";
			var rwan4 = PXML.FindModule("RUNTIME.INF.WAN-4");
			var str_wantype = "PPPoE";
			var str_wanipaddr = XG(rwanc+"/ipaddr");
			var str_wanprefix = "/64";
			var str_wangateway = XG(rwanc+"/ppp6/peer");
			var str_wanDNSserver = XG(rwan+"/runtime/inf/inet/ppp6/dns:1");
			var str_wanDNSserver2 = XG(rwan+"/runtime/inf/inet/ppp6/dns:2");
		}
		else if (this.is_ll == 1)
		{
			var str_wantype = "Link-Local";
			var str_wanipaddr = "";
			var str_wanprefix = "";
			var str_wangateway = "None";
			var str_wanDNSserver = "";
			var str_wanDNSserver2 = "";
		}
		else if(wancable_status==0)
		{
			if(XG(this.waninetp+"/addrtype")=="ipv6")
				var str_wantype = XG(this.waninetp+"/ipv6/mode");
			else if(is_ppp6==1 || is_ppp10==1)	
				var str_wantype = "PPPoE";

			var str_wanipaddr = "";
			var str_wanprefix = "";
			var str_wangateway = "";
			var str_wanDNSserver = "";
			var str_wanDNSserver2 = "";
		}
		
		if(this.is_ll==1)
			OBJ("ll_type").innerHTML = str_wantype;
		else
		{
			if(str_wantype=="STATIC") str_wantype = "Static";
			else if(str_wantype=="AUTO")
			{
				var rwan4 = PXML.FindModule("RUNTIME.INF.WAN-4");
				var rwanmode = XG(rwan4+"/runtime/inf/inet/ipv6/mode");
				if(rwanmode=="STATEFUL") str_wantype = "DHCPv6";
				else if(rwanmode=="STATELESS") str_wantype = "SLAAC";
			}
			OBJ("type").innerHTML = str_wantype;
		}
		OBJ("wan_address").innerHTML  = str_wanipaddr;
		OBJ("wan_address_pl").innerHTML  = str_wanprefix;
		if(this.is_ll==1)
			OBJ("ll_gateway").innerHTML  =  str_wangateway;
		else
			OBJ("gateway").innerHTML  =  str_wangateway;
		if(wancable_status==1)
		{
			if(str_wanDNSserver == "" && this.is_ll==0) str_wanDNSserver = XG(this.waninetp+"/ipv6/dns/entry:1");
			if(str_wanDNSserver2 == "" && this.is_ll==0 ) str_wanDNSserver2 = XG(this.waninetp+"/ipv6/dns/entry:2");
		}
		OBJ("br_dns1").innerHTML  = str_wanDNSserver!="" ? str_wanDNSserver:"N/A";
		OBJ("br_dns2").innerHTML  = str_wanDNSserver2!="" ? str_wanDNSserver2:"N/A";

		return true;
	},
	InitLAN: function()
	{
		var lan	= PXML.FindModule("INET.LAN-4");
		var rlan = PXML.FindModule("RUNTIME.INF.LAN-4");
		var inetuid = XG  (lan+"/inf/inet");
		var phyuid = XG  (lan+"/inf/phyinf");
		var phy = PXML.FindModule("RUNTIME.PHYINF");
		/*this.inetp = GPBT(lan+"/inet", "entry", "uid", inetuid, false);*/
		this.rlanphyp = GPBT(phy+"/runtime", "phyinf", "uid", phyuid, false);
		/*
		if (!this.inetp)
		{
			BODY.ShowAlert("InitLAN() ERROR!!!");
			return false;
		}
		*/

		if(this.is_ll)
		{
			OBJ("ll_lan_ll_address").innerHTML = XG(this.rlanphyp+"/ipv6/link/ipaddr");
			OBJ("ll_lan_ll_pl").innerHTML = "/64";
		}
		else
		{
			OBJ("lan_ll_address").innerHTML = XG(this.rlanphyp+"/ipv6/link/ipaddr");
			OBJ("lan_ll_pl").innerHTML = "/64";
		}

		var b = rlan+"/runtime/inf/dhcps6/pd";
		var enpd = XG(b+"/enable");
		var pdnetwork = XG(b+"/network");
		var pdpfx = XG(b+"/prefix");
		if(this.is_ll==1)
			OBJ("ll_enable_pd").innerHTML = "Disabled"; 
		else
			OBJ("enable_pd").innerHTML = (enpd=="1")? "Enabled":"Disabled"; 
		if(pdnetwork!="" && enpd=="1")
		{
			OBJ("pd_prefix").innerHTML = pdnetwork;
			OBJ("pd_pl").innerHTML = "/"+pdpfx;
		}

		b = rlan+"/runtime/inf/inet/ipv6";
		this.lanip = XG(b+"/ipaddr");
		this.prefix = XG(b+"/prefix");
		if(this.lanip!="")
		{
			OBJ("lan_address").innerHTML = this.lanip;
			OBJ("lan_pl").innerHTML = "/"+this.prefix;
		}
			
		return true;
	}
}
</script>
