<style>
/* The CSS is only for this page.
 * Notice:
 *	If the items are few, we put them here,
 *	If the items are a lot, please put them into the file, htdocs/web/css/$TEMP_MYNAME.css.
 */
select.broad	{ width: 130px; }
select.narrow	{ width: 65px; }
</style>

<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "PFWD.NAT-1",
	OnLoad: function()
	{
		/* draw the 'Application Name' select */
		var str = "";
		for(var i=1; i<=<?=$PFWD_MAX_COUNT?>; i+=1)
		{
			str = "";
			str += '<select id="app_'+i+'" class="broad">'; // Joseph Chao
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
		var p = PXML.FindModule("PFWD.NAT-1");
		if (p === "") alert("ERROR!");
		p += "/nat/entry/portforward";
		TEMP_RulesCount(p, "rmd");
		var count = XG(p+"/count");
		var netid = COMM_IPv4NETWORK(this.lanip, this.mask);
		for (var i=1; i<=<?=$PFWD_MAX_COUNT?>; i+=1)
		{
			var b = p+"/entry:"+i;
			//var offset = XG(b+"/external/end") - XG(b+"/external/start");
			OBJ("uid_"+i).value = XG(b+"/uid");
			OBJ("en_"+i).checked = XG(b+"/enable")==="1";
			OBJ("dsc_"+i).value = XG(b+"/description");
			OBJ("port_tcp_"+i).value = XG(b+"/tport_str").replace(/:/g, "-");
			OBJ("port_udp_"+i).value = XG(b+"/uport_str").replace(/:/g, "-");
			//OBJ("pri_start_"+i).value = XG(b+"/internal/start");
			//if (OBJ("pri_start_"+i).value!="")
			//	OBJ("pri_end_"+i).value = S2I(OBJ("pri_start_"+i).value) + offset;
			//else
			//	OBJ("pri_end_"+i).value = "";
			//COMM_SetSelectValue(OBJ("pro_"+i), (XG(b+"/protocol")=="")? "TCP+UDP":XG(b+"/protocol"));
			<?
			if ($FEATURE_NOSCH!="1")
				echo 'COMM_SetSelectValue(OBJ("sch_"+i), (XG(b+"/schedule")=="")? "-1":XG(b+"/schedule"));\n';
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
		var p = PXML.FindModule("PFWD.NAT-1");
		p += "/nat/entry/portforward";
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
		for (var i=1; i<=<?=$PFWD_MAX_COUNT?>; i+=1)
		{
			if (OBJ("dsc_"+i).value!=="" && OBJ("port_tcp_"+i).value=="" && OBJ("port_udp_"+i).value=="")
			{
				BODY.ShowAlert("<?echo i18n("The input TCP and UDP field can't empty at the same time.");?>");
				OBJ("port_tcp_"+i).focus();
				return null;
			}
			if (OBJ("port_tcp_"+i).value!="" && !check_valid_port(OBJ("port_tcp_"+i).value))
			{
				BODY.ShowAlert("<?echo i18n("The input TCP port range is invalid.");?>");
				OBJ("port_tcp_"+i).focus();
				return null;
			}
			if (OBJ("port_udp_"+i).value!="" && !check_valid_port(OBJ("port_udp_"+i).value))
			{
				BODY.ShowAlert("<?echo i18n("The input UDP port range is invalid.");?>");
				OBJ("port_udp_"+i).focus();
				return null;
			}
			/*if (OBJ("pri_start_"+i).value!="" && !TEMP_IsDigit(OBJ("pri_start_"+i).value))
			{
				BODY.ShowAlert("<?echo i18n("The input private port range is invalid.");?>");
				OBJ("pri_start_"+i).focus();
				return null;
			}*/
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
					XS(b+"/uid",	"PFWD-"+cur_seqno);
					cur_seqno += 1;
				}
				<?
				if ($FEATURE_NOSCH!="1")
					echo 'XS(b+"/schedule",		(OBJ("sch_"+i).value==="-1") ? "" : OBJ("sch_"+i).value);\n';
				?>
				XS(b+"/description",	OBJ("dsc_"+i).value);
				//XS(b+"/protocol",		OBJ("pro_"+i).value);
				XS(b+"/internal/inf",	"LAN-1");
				if (OBJ("ip_"+i).value == "") XS(b+"/internal/hostid", "");
				else XS(b+"/internal/hostid",COMM_IPv4HOST(OBJ("ip_"+i).value, this.mask));
				/* change port style */
				XS(b+"/tport_str",	OBJ("port_tcp_"+i).value.replace(/-/g, ":"));
				XS(b+"/uport_str",	OBJ("port_udp_"+i).value.replace(/-/g, ":"));
				
				//XS(b+"/internal/start",	OBJ("pri_start_"+i).value);
				//XS(b+"/external/start",	OBJ("pub_start_"+i).value);
				//XS(b+"/external/end",	OBJ("pub_end_"+i).value);
			}
		}
		// Make sure the different rules have different names and public port ranges.
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
				/*if(OBJ("pub_start_"+i).value == OBJ("pub_start_"+j).value || OBJ("pub_start_"+i).value == OBJ("pub_end_"+j).value
					||  OBJ("pub_end_"+i).value == OBJ("pub_start_"+j).value || OBJ("pub_end_"+i).value == OBJ("pub_end_"+j).value)
				{
					BODY.ShowAlert("<?echo i18n("The public port ranges of different rules are overlapping.");?>");
					OBJ("pub_start_"+j).focus();
					return null;
				}	
				if(parseInt(OBJ("pub_start_"+i).value, 10) < parseInt(OBJ("pub_end_"+j).value, 10))
				{
					if(parseInt(OBJ("pub_start_"+j).value, 10) < parseInt(OBJ("pub_end_"+i).value, 10))
					{
						BODY.ShowAlert("<?echo i18n("The public port ranges of different rules are overlapping.");?>"); 
						OBJ("pub_start_"+j).focus();
						return null;
					}
				}*/

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
	apps: [	{name: "<?echo i18n("Application Name");?>",value:{tport:"", uport:""}},
			{name: "Age of Empires",					value:{tport:"2302-2400,6073",
															   uport:"2302-2400,6073"}},
			{name: "Aliens vs. Predator",               value:{tport:"80,2300-2400,8000-8999",                                                                 
															   uport:"80,2300-2400,8000-8999"}},
			{name: "America Army",                      value:{tport:"20045",                                                                                       
															   uport:"1716-1718,8777,27900"}},
			{name: "Asheron Call",                      value:{tport:"9000-9013",                                                                                         
															   uport:"2001,9000-9013"}},
			{name: "Battlefield 1942",                  value:{tport:"",                                                                             
															   uport:"14567,22000,23000-23009,27900,28900"}},
			{name: "Battlefield 2",                     value:{tport:"80,4711,29900,29901,29920,28910",                        
															   uport:"1500-4999,16567,27900,29900,29910,27901,55123,55124,55215"}},
			{name: "Battlefield: Vietnam",              value:{tport:"",                                                                                    
															   uport:"4755,23000,22000,27243-27245"}},
			{name: "BitTorrent",                        value:{tport:"6881-6889",uport:""}},                                                                               
			{name: "Black and White",                   value:{tport:"2611-2612,6500,6667,27900",                                                              
															   uport:"2611-2612,6500,6667,27900"}},
			{name: "Call of Duty",                      value:{tport:"28960",                                                                                          
															   uport:"20500,20510,28960"}},
			{name: "Command and Conquer Generals",      value:{tport:"80,6667,28910,29900,29920",                                                                             
															   uport:"4321,27900"}},
			{name: "Command and Conquer Zero Hour",     value:{tport:"80,6667,28910,29900,29920",                                                                             
															   uport:"4321,27900"}},
			{name: "Counter Strike",                    value:{tport:"1121,3040,28801,28805",       
															   uport:""}},                                                                      
			{name: "Crimson Skies",                     value:{tport:"1720,15328-15333",                                                                                     
															   uport:"15328-15333"}},
			{name: "D-Link DVC-1000",                   value:{tport:"26214",                                                                                                      
															   uport:"26214"}},
			{name: "Dark Reign 2",                      value:{tport:"3100-3999",                                                                                                   
															   uport:"3568"}},
			{name: "Delta Force",                       value:{tport:"6112-6119,4000",                                                                                         
															   uport:"6112-6119"}},
			{name: "Diablo I and II",                   value:{tport:"",                                                                                                           
															   uport:"27666"}},
			{name: "Doom 3",                            value:{tport:"",                                                                                                  
															   uport:"6073,2302-2400"}},
			{name: "Dungeon Siege",                     value:{tport:"4661-4662",                                                                                                   
															   uport:"4665"}},
			{name: "eDonkey",                           value:{tport:"4661-4662,4711",                                                                                         
															   uport:"4672,4665"}},
			{name: "eMule",                             value:{tport:"1024-6000,7000",                                                                                    
															   uport:"1024-6000,7000"}},
			{name: "Everquest",                         value:{tport:"",                                                                                                     
															   uport:"49001,49002"}},
			{name: "Far Cry",                           value:{tport:"25,80,110,443,50000-65535",                                                                            
															   uport:"50000-65535"}},
			{name: "Final Fantasy XI (PC)",             value:{tport:"1024-65535",                                                                                           
															   uport:"50000-65535"}},
			{name: "Final Fantasy XI (PC2)",            value:{tport:"",                                                                                                            
															   uport:"6500"}},
			{name: "Gamespy Arcade",                    value:{tport:"",                                                                                                            
															   uport:"6700"}},
			{name: "Gamespy Tunnel",                    value:{tport:"2346-2348",                                                                                              
															   uport:"2346-2348"}},
			{name: "Ghost Recon",                       value:{tport:"6346",                                                                                                     
															   uport:"6346"}},
			{name: "Gnutella",                          value:{tport:"6003,7002",                                                                               
															   uport:"27005,27010,27011,27015"}},
			{name: "Half Life",                         value:{tport:"",                                                                                                       
															   uport:"2302,2303"}},
			{name: "Combat Evolved",                    value:{tport:"28910",                                                                                                      
															   uport:"28910"}},
			{name: "Heretic II",                        value:{tport:"26900",                                                                                                      
															   uport:"26900"}},
			{name: "Hexen II",                          value:{tport:"",                                                                                   
															   uport:"28060,28061,28062,28070-28081"}},
			{name: "Jedi Knight II: Jedi Outcast",      value:{tport:"",                                                                                   
															   uport:"28060,28061,28062,28070-28081"}},
			{name: "Jedi Knight III: Jedi Academy",     value:{tport:"",
															   uport:"2213,6666"}},                                                                                                       
			{name: "KALI",                              value:{tport:"2300-2400,47624",                                                                                   
															   uport:"2300-2400,6073"}},
			{name: "Links",                             value:{tport:"12203-12204", uport:""}},                                                                                 
			{name: "Medal of Honor: Games",             value:{tport:"6667",                                                                                                 
															   uport:"28800-29000"}},
			{name: "MSN Game Zone",                     value:{tport:"2300-2400,47624",                                                                                        
															   uport:"2300-2400"}},
			{name: "MSN Game Zone (DX)",                value:{tport:"3453",                                                                                                        
															   uport:"3453"}},
			{name: "Myth",                              value:{tport:"9442",                                                                                                        
															   uport:"9442"}},
			{name: "Need Speed",                        value:{tport:"1030",                                                                                                        
															   uport:"1030"}},
			{name: "Need Speed 3",                      value:{tport:"8511,28900",                                                                           
															   uport:"1230,8512,27900,61200-61230"}},
			{name: "Need Speed: Hot Pursuit 2",         value:{tport:"",                                                                                      
															   uport:"5120-5300,6500,27900,28900"}},
			{name: "Neverwinter Nights",                value:{tport:"",                                                                                                            
															   uport:"3455"}},
			{name: "PainKiller",                        value:{tport:"4658,4659",                                                                                              
															   uport:"4658,4659"}},
			{name: "PlayStation2",                      value:{tport:"80",                                                                                         
															   uport:"7777-7779,27900,28900"}},
			{name: "Postal 2: Share the Pain",          value:{tport:"27910",                                                                                                      
															   uport:"27910"}},
			{name: "Quake 2",                           value:{tport:"27660,27960",                                                                                       
															   uport:"27660,27960"}},
			{name: "Quake 3",                           value:{tport:"2346",                                                                                                        
															   uport:"2346"}},                                                                                                        
			{name: "Rainbow Six",                       value:{tport:"",                                                                                             
															   uport:"7777-7787,8777-8787"}},                                    
			{name: "Rainbow Raven: Raven Shield",       value:{tport:"",                                                      
															   uport:"27950,27960,27965,27952"}},                               
			{name: "Return to Castle Wolfenstein",      value:{tport:"",                                                      
															   uport:"34987"}},                                                 
			{name: "Rise of Nations",                   value:{tport:"3782",                                                  
															   uport:"27900,28900,3782-3783"}},
			{name: "Roger Wilco",                       value:{tport:"2346",                                                  
															   uport:"2346"}},                                                  
			{name: "Rogue Spear",                       value:{tport:"25600-25605",                                           
															   uport:"25600-25605"}},                                           
			{name: "Serious Sam II",                    value:{tport:"6346",                                                  
															   uport:"6346"}},                                                  
			{name: "Shareaza",                          value:{tport:"3000",                                                  
															   uport:"3000"}},                                                  
			{name: "Silent Hunter II",                  value:{tport:"",                                                      
															   uport:"28901,28910,38900-38910,22100-23000"}},
			{name: "Soldier of Fortune",                value:{tport:"",                                                      
															   uport:"20100-20112"}},                                                                                         
			{name: "Soldier of Fortune II",             value:{tport:"40000-43000",                                                                                
															   uport:"44000-45001,7776,8888"}},                                       
			{name: "Splinter Cell: Pandora Tomorrow",   value:{tport:"",                                                  
															   uport:"29250,29256"}},                                                  
			{name: "Star Trek: Elite Force II",         value:{tport:"6112-6119,4000",                                    
															   uport:"6112-6119"}},                                        
			{name: "Starcraft",                         value:{tport:"",                                                                    
															   uport:"27999,28000"}},                                                                    
			{name: "Starsiege Tribes",                  value:{tport:"27030-27039",                                                         
															   uport:"1200,27000-27015"}},                                                         
			{name: "Steam",                             value:{tport:"",                                                                    
															   uport:"10480-10483"}},
			{name: "SWAT 4",                            value:{tport:"",                                                                    
															   uport:"8767"}},                                                                    
			{name: "TeamSpeak",                         value:{tport:"1140-1234,4000",                                    
															   uport:"1140-1234,4000"}},                                    
			{name: "Tiberian Sun",                      value:{tport:"80,443,1791-1792,13500,20801-20900,32768-65535",    
															   uport:"80,443,1791-1792,13500,20801-20900,32768-65535"}},    
			{name: "Tiger Woods 2K4",                   value:{tport:"7777,7778,28910",                                   
															   uport:"6500,7777,7778,27900"}},                                   
			{name: "Tribes of Vengeance",               value:{tport:"40000-42999",                                       
															   uport:"41005"}},                                            
			{name: "Ubi.com",                           value:{tport:"5001-5010,7775-7777,7875,8800-8900,9999",           
															   uport:"5001-5010,7775-7777,7875,8800-8900,9999"}},
			{name: "Ultima",                            value:{tport:"7777,8888,27900",                                            
															   uport:"7777-7781"}},                                            
			{name: "Unreal",                            value:{tport:"7777-7783,8080,27900",                                         
															   uport:"7777-7783,8080,27900"}},                                         
			{name: "Unreal Tournament",                 value:{tport:"28902",                                             
															   uport:"7777-7778,7787-7788"}},                                             
			{name: "Unreal Tournament_2004",            value:{tport:"",                                                                    
															   uport:"5425,15425,28900"}},                                                                    
			{name: "Vietcong",                          value:{tport:"6112-6119,4000",                                    
															   uport:"6112-6119"}},                                        
			{name: "Warcraft II",                       value:{tport:"6112-6119,4000",                                                      
															   uport:"6112-6119"}},
			{name: "Warcraft III",                      value:{tport:"6699",                                                                
															   uport:"6257"}},                                                                
			{name: "WinMX",                             value:{tport:"",                                                                    
															   uport:"27950,27960,27965,27952"}},                                                                    
			{name: "Wolfenstein: Enemy Territory",      value:{tport:"27000-27999",                                                        
															   uport:"15001,15101,15200,15400"}},                                                        
			{name: "WON Servers",                       value:{tport:"3724,6112,6881-6999", uport:""}},                                                 
			{name: "World of Warcraft",                 value:{tport:"3074",                                                                
															   uport:"88,3074"}},                                                                
			{name: "Xbox Live",                         value:{tport:"3074",                                                                
            												   uport:"88,3074"}}
		  ],
	lanip: "<? echo INF_getcurripaddr("LAN-1"); ?>",
	mask: "<? echo INF_getcurrmask("LAN-1"); ?>",
	CursorFocus: function(node)
	{
		var i = node.lastIndexOf("entry:");
		var idx = node.charAt(i+6);
		if (node.lastIndexOf("description") != "-1") OBJ("dsc_"+idx).focus();
		if (node.lastIndexOf("internal/hostid") != "-1") OBJ("ip_"+idx).focus();
		if (node.lastIndexOf("tport_str") != "-1") OBJ("port_tcp_"+idx).focus();
		if (node.lastIndexOf("uport_str") != "-1") OBJ("port_udp_"+idx).focus();
	}
};

function OnClickAppArrow(idx)
{
	var i = OBJ("app_"+idx).value;
	OBJ("dsc_"+idx).value = (i==="0") ? "" : PAGE.apps[i].name;
	//OBJ("pro_"+idx).value = PAGE.apps[i].protocol;
	//OBJ("pub_start_"+idx).value = OBJ("pri_start_"+idx).value = PAGE.apps[i].port.start;
	//OBJ("pub_end_"+idx).value = OBJ("pri_end_"+idx).value = PAGE.apps[i].port.end;
	OBJ("port_tcp_"+idx).value = PAGE.apps[i].value.tport;
	OBJ("port_udp_"+idx).value = PAGE.apps[i].value.uport;
	OBJ("app_"+idx).selectedIndex = 0;
}
function OnClickPCArrow(idx)
{
	OBJ("ip_"+idx).value = OBJ("pc_"+idx).value;
	OBJ("pc_"+idx).selectedIndex = 0;
}

function CheckPort(port)
{
	var vals = port.toString().split("-");
	switch (vals.length)
	{
	case 1:
		if (!TEMP_IsDigit(vals))
			return false;
		break;
	case 2:
		if (!TEMP_IsDigit(vals[0])||!TEMP_IsDigit(vals[1]))
			return false;
		break;
	default:
		return false;
	}
	return true;
}
function check_valid_port(list)
{
	var port = list.split(",");

	if (port.length > 1)
	{
		for (var i=0; i<port.length; i++)
		{
			if (!CheckPort(port[i]))
				return false;
		}
		return true;
	}
	else
	{
		return CheckPort(port);
	}
}

</script>
