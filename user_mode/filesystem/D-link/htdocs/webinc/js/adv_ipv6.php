<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "INET.WAN-1,INET.LAN-3,INET.LAN-4,INET.WAN-3,INET.WAN-4, RUNTIME.PHYINF,RUNTIME.INF.LAN-4,RUNTIME.INF.WAN-4",
	OnLoad: function()
	{
		if (!this.rgmode)		{BODY.DisableCfgElements(true);}
	},
	OnUnload: function() {},
	OnSubmitCallback: function ()	{},

	InitValue: function(xml)
	{
		PXML.doc = xml;

		//PXML.doc.dbgdump();

		this.ParseAll();

		if (!this.InitWANConnMode()) return false;
		if (!this.InitWANInfo()) return false;
		if (!this.InitWANLLInfo()) return false;
		if (!this.InitLANInfo()) return false;
		if (!this.InitLANLLInfo()) return false;
		if (!this.InitLANAutoConf()) return false;
		this.OnChangewan_ipv6_mode();
		return true;
	},
	PreSubmit: function()
	{
		if (!this.PreWAN()) return null;
		if (!this.PreLAN()) return null;
		if (!this.PreLANAutoConf()) return null;
		PXML.IgnoreModule("INET.LAN-3");
		PXML.IgnoreModule("INET.WAN-3");
		PXML.IgnoreModule("RUNTIME.PHYINF");
		PXML.IgnoreModule("RUNTIME.INF.WAN-4");
		PXML.IgnoreModule("RUNTIME.INF.LAN-4");
		//PXML.doc.dbgdump();
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},

	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////

	/*********************************************************
	    lanact, lanllact, wanact and wanllact:
		this architecture existe at least one drawback:
		1. only accept one active wan, wanll, lan, lanll.
		2. 
	*********************************************************/
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	laninfo: [  {svcsname: "INET.LAN-3", svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null },
			{svcsname: "INET.LAN-4", svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null }
		],
	waninfo: [  {svcsname: "INET.WAN-4", svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null },
			{svcsname: "INET.WAN-3", svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null }
		],
	lanact:   { svcsname: null, svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null },
	lanllact: { svcsname: null, svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null },
	wanact:   { svcsname: null, svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null },
	wanllact: { svcsname: null, svcs: null, inetuid: null, inetp: null, phyinf: null, ipv6ll: null },
	rwan: null,
	rlan: null,

	OnChangewan_ipv6_mode: function()
	{
		OBJ("box_wan_static").style.display			= "none";
		OBJ("box_wan_static_body").style.display	= "none";
		OBJ("box_wan_6to4").style.display			= "none";
		OBJ("box_wan_6to4_body").style.display		= "none";
		OBJ("box_wan_ll_body").style.display		= "none";
		OBJ("box_lan").style.display				= "none";
		OBJ("box_lan_static_body").style.display	= "none";
		OBJ("box_lan_6to4_body").style.display 		= "none";
		OBJ("box_lan_ll_body").style.display 		= "none";
		OBJ("box_lan_auto").style.display 			= "none";
		OBJ("box_lan_auto_body").style.display 	= "none";

		switch(OBJ("wan_ipv6_mode").value)
		{
			case "STATIC":
				OBJ("box_wan_static").style.display = "";
				OBJ("box_wan_static_body").style.display = "";
				OBJ("box_wan_ll_body").style.display = "";
				OBJ("box_lan").style.display = "";
				OBJ("box_lan_static_body").style.display = "";
				OBJ("box_lan_ll_body").style.display = "";
				OBJ("box_lan_auto").style.display = "";
				OBJ("box_lan_auto_body").style.display = "";
				break;

			case "DHCP":
				break;

			case "RA":
				break;

			case "PPPOE":
				break;

			case "6IN4TUN":
					break;

			case "6TO4":
				OBJ("box_wan_6to4").style.display = "";
				OBJ("box_wan_6to4_body").style.display = "";
				OBJ("box_wan_ll_body").style.display = "";
				OBJ("box_lan").style.display = "";
				OBJ("box_lan_6to4_body").style.display = "";
				OBJ("box_lan_ll_body").style.display = "";
				OBJ("box_lan_auto").style.display = "";
				OBJ("box_lan_auto_body").style.display = "";
				break;
		}
		
	},

	/* Get lanact, lanllact, wanact and wanllact */
	ParseAll: function()
	{
		//alert("length"+this.laninfo.length);
		for ( var lidx=0; lidx < this.laninfo.length; lidx++)
		{
			this.laninfo[lidx].svcs		= GPBT("/postxml", "module", "service", this.laninfo[lidx].svcsname, false);
			this.laninfo[lidx].inetuid	= XG(this.laninfo[lidx].svcs+"/inf/inet");
			this.laninfo[lidx].inetp	= GPBT(this.laninfo[lidx].svcs+"/inet", "entry", "uid", this.laninfo[lidx].inetuid, false);
			this.laninfo[lidx].phyinf	= XG(this.laninfo[lidx].svcs+"/inf/phyinf");
			var tRTPHYsvcs			= GPBT("/postxml", "module", "service", "RUNTIME.PHYINF", false);
			var tRTPHYphyinf		= GPBT(tRTPHYsvcs+"/runtime", "phyinf", "uid", this.laninfo[lidx].phyinf);
			this.laninfo[lidx].ipv6ll	= XG(tRTPHYphyinf+"/ipv6ll");

			//this.MyArrayShowAlert("laninfo", this.laninfo[lidx]);

			if ( XG(this.laninfo[lidx].svcs+"/inf/active") == "1" )
			{
				if ( XG(this.laninfo[lidx].inetp+"/ipv6/mode") == "LL" )
					this.FillArrayData( this.lanllact, this.laninfo[lidx] ); 
				else
					this.FillArrayData( this.lanact, this.laninfo[lidx] );
			}
		}

		for ( var widx=0; widx < this.waninfo.length; widx++)
		{
			this.waninfo[widx].svcs		= GPBT("/postxml", "module", "service", this.waninfo[widx].svcsname, false);
			this.waninfo[widx].inetuid	= XG(this.waninfo[widx].svcs+"/inf/inet");
			this.waninfo[widx].inetp	= GPBT(this.waninfo[widx].svcs+"/inet", "entry", "uid", this.waninfo[widx].inetuid, false);
			this.waninfo[widx].phyinf	= XG(this.waninfo[widx].svcs+"/inf/phyinf");
			var tRTPHYsvcs			= GPBT("/postxml", "module", "service", "RUNTIME.PHYINF", false);
			var tRTPHYphyinf		= GPBT(tRTPHYsvcs+"/runtime", "phyinf", "uid", this.waninfo[widx].phyinf);
			this.waninfo[widx].ipv6ll	= XG(tRTPHYphyinf+"/ipv6ll");

			//this.MyArrayShowAlert("laninfo", this.waninfo[widx]);

			if ( XG(this.waninfo[widx].svcs+"/inf/active") == "1" )
			{
				if ( XG(this.waninfo[widx].inetp+"/ipv6/mode") == "LL" )
					this.FillArrayData( this.wanllact, this.waninfo[widx] ); 
				else
					this.FillArrayData( this.wanact, this.waninfo[widx] );
			}
		}
		//this.MyArrayShowAlert("lanact", this.lanact);
		//this.MyArrayShowAlert("lanllact", this.lanllact);
		//this.MyArrayShowAlert("wanact", this.wanact);
		//this.MyArrayShowAlert("wanllact", this.wanllact);
	},
	FillArrayData: function( to, from)
	{
		to.svcsname	= from.svcsname;
		to.svcs		= from.svcs;
		to.inetuid	= from.inetuid;
		to.inetp	= from.inetp;
		to.phyinf	= from.phyinf;
		to.ipv6ll	= from.ipv6ll;
	},

	InitWANConnMode: function()
	{
		if( this.wanact.svcsname != null )
			COMM_SetSelectValue(OBJ("wan_ipv6_mode"), XG(this.wanact.inetp+"/ipv6/mode"));
		else 
			COMM_SetSelectValue(OBJ("wan_ipv6_mode"), "STATIC");
		return true;
	},

	InitWANInfo: function()
	{
		if( this.wanact.svcsname != null )
		{
			switch (XG(this.wanact.inetp+"/ipv6/mode"))
			{
				case "STATIC":
					OBJ("w_st_ipaddr").value	= XG(this.wanact.inetp+"/ipv6/ipaddr");
					OBJ("w_st_pl").value		= XG(this.wanact.inetp+"/ipv6/prefix");
					OBJ("w_st_gw").value		= XG(this.wanact.inetp+"/ipv6/gateway");
					OBJ("w_st_pdns").value		= XG(this.wanact.inetp+"/ipv6/dns/entry:1");
					OBJ("w_st_sdns").value		= XG(this.wanact.inetp+"/ipv6/dns/entry:2");
					break;

				case "DHCP":
					break;

				case "RA":
					break;

				case "PPPOE":
					break;

				case "6IN4TUN":
					break;

				case "6TO4":
					/* we get 6to4 info from runtime */
					this.rwan = PXML.FindModule("RUNTIME.INF.WAN-4");
					this.rlan = PXML.FindModule("RUNTIME.INF.LAN-4");
					OBJ("w_6to4_ipaddr").innerHTML = XG(this.rwan+"/runtime/inf/inet/ipv6/ipaddr");
					OBJ("l_6to4_ipaddr").innerHTML = XG(this.rlan+"/runtime/inf/inet/ipv6/ipaddr");
					OBJ("w_6to4_pdns").value	= XG(this.wanact.inetp+"/ipv6/dns/entry:1");
					OBJ("w_6to4_sdns").value	= XG(this.wanact.inetp+"/ipv6/dns/entry:2");
					break;
			}
		}

		/* fill some fixed info */
		OBJ("w_6to4_pl").innerHTML = "/16";

		return true;
	},

	InitWANLLInfo: function()
	{
		if( this.wanllact.svcsname != null ) /* this should not happen */
		{
			//this.MyArrayShowAlert(this.wanllact);
			OBJ("wan_ll").innerHTML    = this.wanllact.ipv6ll;
			OBJ("wan_ll_pl").innerHTML    = "/64";
		}
		return true;
	},
	InitLANInfo: function()
	{
		//this.MyArrayShowAlert("lanact",this.lanact);
		if( this.lanact.svcsname != null )  /* lan mode has be set -> lanact is not null -> we need fill all lan info*/
		{
			switch (XG(this.lanact.inetp+"/ipv6/mode"))
			{
				case "STATIC":
					OBJ("l_st_ipaddr").value	= XG(this.lanact.inetp+"/ipv6/ipaddr");
					break;

				case "DHCP":
					break;

				case "RA":
					break;

				case "PPPOE":
					break;

				case "6IN4TUN":
					break;

				case "6TO4":
					/* TODO */
					break;
			}
		}

		/* fill some fixed info */
		OBJ("l_st_pl").innerHTML	= "/64";
		OBJ("l_6to4_pl").innerHTML	= "/64";

		return true;
	},
	InitLANLLInfo: function(addrmode)
	{
		if( this.lanllact.svcsname != null ) /* this should not happen */
		{
			OBJ("lan_ll").innerHTML    = this.lanllact.ipv6ll;
			OBJ("lan_ll_pl").innerHTML    = "/64";
		}
		return true;
	},
	InitLANAutoConf: function()
	{
		/* need TODO...*/
		return true;
	},
	PreWAN: function()
	{
		if (this.wanact.svcsname == null )
		{
			for ( var widx=0; widx < this.waninfo.length; widx++)
			{
				if ( this.waninfo[widx].svcsname == "INET.WAN-4" )
				{
					this.FillArrayData( this.wanact, this.waninfo[widx] );
					break;
				}
			}
		}
		var wan1 = PXML.FindModule("INET.WAN-1");
		var wan4 = PXML.FindModule("INET.WAN-4");
		// get "wan mode" then set wan info
		switch(OBJ("wan_ipv6_mode").value)
		{
			case "STATIC":
				XS(wan1+"/inf/inf6to4", "");
				XS(wan4+"/inf/child", "");
				XS(this.wanact.svcs+"/inf/active", "1");
				XS(this.wanact.inetp+"/ipv6/mode", "STATIC");

				/* ipaddr, prefix */
				XS(this.wanact.inetp+"/ipv6/ipaddr", OBJ("w_st_ipaddr").value);
				XS(this.wanact.inetp+"/ipv6/prefix", OBJ("w_st_pl").value);

				/* gateway */
				if (OBJ("w_st_gw").value != "") 
				{	XS(this.wanact.svcs+"/inf/defaultroute", "1");	}
				else
				{	XS(this.wanact.svcs+"/inf/defaultroute", "0");	}
				XS(this.wanact.inetp+"/ipv6/gateway", OBJ("w_st_gw").value);

				/* dns */
				var dnscnt = 0;
				if (OBJ("w_st_pdns").value != "")	dnscnt++;
				if (OBJ("w_st_sdns").value != "")	dnscnt++;
				XS(this.wanact.inetp+"/ipv6/dns/count", dnscnt);
				XS(this.wanact.inetp+"/ipv6/dns/entry:1", OBJ("w_st_pdns").value);
				XS(this.wanact.inetp+"/ipv6/dns/entry:2", OBJ("w_st_sdns").value);
				break;

			case "DHCP":
				break;

			case "RA":
				break;

			case "PPPOE":
				break;

			case "6IN4TUN":
				break;

			case "6TO4":
				XS(wan1+"/inf/inf6to4", "WAN-4");
				XS(wan4+"/inf/child", "LAN-4");
				XS(this.wanact.svcs+"/inf/active", "1");
				XS(this.wanact.inetp+"/ipv6/mode", "6TO4");

				/* dns */
				var dnscnt = 0;
				if (OBJ("w_6to4_pdns").value != "")	dnscnt++;
				if (OBJ("w_6to4_sdns").value != "")	dnscnt++;
				XS(this.wanact.inetp+"/ipv6/dns/count", dnscnt);
				XS(this.wanact.inetp+"/ipv6/dns/entry:1", OBJ("w_6to4_pdns").value);
				XS(this.wanact.inetp+"/ipv6/dns/entry:2", OBJ("w_6to4_sdns").value);
				break;
		}
		
		return true;
	},

	PreLAN: function()
	{
		if (this.lanact.svcsname == null )
		{
			for ( var lidx=0; lidx < this.laninfo.length; lidx++)
			{
				if ( this.laninfo[lidx].svcsname == "INET.LAN-4" )
				{
					this.FillArrayData( this.lanact, this.laninfo[lidx] );
					break;
				}
			}
		}
		// get "wan mode" then set lan info
		switch(OBJ("wan_ipv6_mode").value)
		{
			case "STATIC":
				XS(this.lanact.svcs+"/inf/active", "1");
				XS(this.lanact.inetp+"/ipv6/mode", "STATIC");

				/* ipaddr, prefix*/
				XS(this.lanact.inetp+"/ipv6/ipaddr", OBJ("l_st_ipaddr").value);
				var tmp = OBJ("l_st_pl").innerHTML;
				var tmplstpl = tmp.split("/"); /*cut slash */	
				XS(this.lanact.inetp+"/ipv6/prefix", tmplstpl[1]);
				break;

			case "DHCP":
				break;

			case "RA":
				break;

			case "PPPOE":
				break;

			case "6IN4TUN":
				break;

			case "6TO4":
				XS(this.lanact.svcs+"/inf/active", "1");
				XS(this.lanact.inetp+"/ipv6/mode", "DHCP"); /* if 6to4, set lan side mode as DHCP without other info */
				break;
		}
		return true;
	},

	PreLANAutoConf: function()
	{
		/* need TODO...*/
		return true;
	},

	MyArrayShowAlert: function( promptInfo, target )
	{
		alert( promptInfo	+ ": " +
			target.svcsname	+ "  " +
			target.svcs		+ "  " +
			target.inetuid	+ "  " +
			target.inetp	+ "  " +
			target.phyinf	+ "  " +
			target.ipv6ll);	
	}
}
</script>
