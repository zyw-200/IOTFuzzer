<script type="text/javascript">
function Page() {}
Page.prototype =
{
	//services: "RUNTIME.INF.LAN-1,RUNTIME.PHYINF.WLAN-1,RUNTIME.PHYINF.WLAN-2,WIFI.WLAN-1,WIFI.WLAN-2",
	//services: "RUNTIME.INF.LAN-1,RUNTIME.PHYINF.WLAN-1,RUNTIME.PHYINF.WLAN-2",
	services: "WIFI.PHYINF,RUNTIME.INF.LAN-1,RUNTIME.PHYINF.WLAN-1,RUNTIME.PHYINF.WLAN-2",
	OnLoad: function() { BODY.CleanTable("client_list");BODY.CleanTable("client_list_Aband"); },
	OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return false; },
	
	//hendry
	str_Aband: null,
	wifip: null,
	runtime_phyinf : null,
	phyinf : null,
	
	InitValue: function(xml)
	{
		PXML.doc = xml;
		this.inf = PXML.FindModule("RUNTIME.INF.LAN-1");
		this.inf += "/runtime/inf/dhcps4/leases";
		PAGE.FillTable("WLAN-1", "WIFI.PHYINF", "RUNTIME.PHYINF.WLAN-1");
		PAGE.FillTable("WLAN-2", "WIFI.PHYINF", "RUNTIME.PHYINF.WLAN-2");
	},	
	
	FillTable : function (wlan_phyinf, wifi_phyinf ,runtime_phyinf)
	{
		/*Note : why we use "RUNTIME.PHYINF.WLAN-1" ? To get ssid !! */ 
		this.wifi_phyinf = PXML.FindModule(wifi_phyinf);
		this.runtime_phyinf = PXML.FindModule(runtime_phyinf);

		this.phyinf = GPBT(this.wifi_phyinf, "phyinf", "uid",wlan_phyinf, false);

		var wifi_profile = XG(this.phyinf+"/wifi");
		var freq = XG(this.phyinf+"/media/freq");
		this.wifip = GPBT(this.wifi_phyinf+"/wifi", "entry", "uid", wifi_profile, false);
		
		if(freq == "5")
			str_Aband = "_Aband";
		else
			str_Aband = "";
			
		this.runtime_phyinf = GPBT(this.runtime_phyinf+"/runtime","phyinf","uid",wlan_phyinf, false);
		this.runtime_phyinf += "/media/clients";
		
		if (!this.inf||!this.phyinf)
		{
			BODY.ShowAlert("Initial() ERROR!!!");
			return false;
		}
		
		/* Fill table */
		var cnt = XG(this.runtime_phyinf +"/entry#");
		var ssid = XG(this.wifip+"/ssid");
		
		if(cnt == "") cnt = 0; 
		OBJ("client_cnt"+str_Aband).innerHTML = cnt;
		for (var i=1; i<=cnt; i++)
		{
			var uid		= "DUMMY-"+i;
			var mac		= XG(this.runtime_phyinf+"/entry:"+i+"/macaddr");
			var ipaddr	= this.GetIP(mac);
			var mode	= XG(this.runtime_phyinf+"/entry:"+i+"/band");
			if(freq == "5")
			{
				if(mode=="11g")
				{
					mode="11a";
				}
				else if(mode=="11n")
				{
					mode="11n(5GHz)";
				}
			}
			var rate	= XG(this.runtime_phyinf+"/entry:"+i+"/rate");
			var data	= [ssid, mac, ipaddr, mode, rate];
			var type	= ["text", "text", "text", "text", "text"];
			BODY.InjectTable("client_list"+str_Aband, uid, data, type);
		}
	},
	
	PreSubmit: function() { return null; },
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	inf: null,
	phyinf: null,
	GetIP: function(mac)
	{
		var path = GPBT(this.inf, "entry", "macaddr", mac.toLowerCase(), false);
		return XG(path+"/ipaddr");
	}
}
</script> 
