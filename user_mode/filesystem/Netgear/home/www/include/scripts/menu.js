var menuObject;
Object.extend(Array.prototype, {
	removeItem: function(search) {
		for(var i=0;i<this.length;i++) {
			if (this[i] == search) {
				return this.splice(i,1);
			}
		}
	}
});
var menuClass = Class.create({
	data: $H({
//BEGIN Change from Mouli: Adding Cloud to the GUI.
//Level 1 Navigation: Cloud
//Level 2 Navigation: Cloud
//Level 3 Navigation: Cloud Settings
		/*'cloud': $H({
			"System": $H({
				"General": [],
				"IP Settings": [],
				"Reset": []
			})
		}),*/
//END of change from Moulidaren/*		'Password': $H({			"Password": $H({				"Change Password": []			})		}),*/
		'Configuration': $H({
			"System": $H({
				"General": [],
				"IP Settings": [],
				"Reset": [],
				"Basic": ["General", "Time"],
				"Advanced": ["General", "Hotspot", "Syslog", "Ethernet", "Ethernet LLDP","User Accounts"]
			}),
			"IP": $H({
				"IP Settings": [],
				"IPv6 Settings": [],								
				"DHCP Server Settings": [],
				"Snooping": []
			}),
			"Wireless": $H({
				"Basic": ["Wireless Settings", "Wireless On-Off", "QoS Settings"],
				"Advanced": ["Wireless Settings", "QoS Settings", "QoS Policies"]
			}),
			"Security": $H({
				"Profile Settings": [],
				"Advanced": ["Rogue AP", "MAC Authentication", "Radius Server Settings"]
			}),
			"Wireless Bridge": $H({
				"Bridging": []
			}),
			"WPS": $H({
				"WPS Settings": [],
				"Add WPS Client": []
			}),
			"IDS/IPS": $H({
				"IDS/IPS": [],				
				"IDS/IPS Mail Settings": []								
			})
		}),

		'Monitoring': $H({
			"System": $H({
				"System": []
			}),
			"Wireless Stations": $H({
				"Wireless Stations": []
			}),
			"Rogue AP": $H({
				"Unknown AP List": [],
				"Known AP List": []
			}),
			"Logs": $H({
				"Logs": []
			}),
			"Statistics": $H({
				"Statistics": []
			}),
			"Packet Capture": $H({
				"Packet Capture": []
			}),			
			"IDS/IPS": $H({
				"Traps":[],
				"Counters":[],
				"Adhoc Networks":[]
			})						
		}),

		'Maintenance': $H({
			"Password": $H({
				"Change Password": []
			}),
			"Reset": $H({
				"Reboot AP": [],
				"Restore Defaults": []
			}),
			"Remote Management": $H({
				"SNMP": [],
				"TR 069": [],
				"Remote Console": []
				
			}),
			"Upgrade": $H({
				"Firmware Upgrade": [],
				"Firmware Upgrade TFTP": [],
				"Backup Settings": [],
				"Restore Settings": []
			})
		}),
		'Support': $H({
			"Documentation": $H({
				"Documentation": []
			}),
			"Registration": $H({
				"Product Registration": []
			})
		})
	}),
	initialize: function () {
		this.processConfig();
		this.pointer = {first: 0, second: 0, third: 0, fourth: 0};
		this.currentData = { first: null, second: null, third: null, fourth: [] };
		this.initialLoad = true;
		this.getFirstLevelData();
			
		//this.updateMenu('first',0);
	},
	processConfig: function() {		/*var pword;	var oOptions = {            method: "get",            asynchronous: false,			parameters: { action: 'passwo'},            onSuccess: function (oXHR, oJson) {                var response = oXHR.responseText;				//alert("response = "+response);				pword=response;				},            onFailure: function (oXHR, oJson) {                alert("There was an error with the process, Please try again!");            }        };		var req = new Ajax.Request('forcePasswordChange.php?id='+Math.random(10000,99999), oOptions);		if(pword!="password"){	//this.data.get("Password").unset("Password");	this.data.unset("Password");	}*/
	var cloudSta;
	var oOptions = {
            method: "get",
            asynchronous: false,
			parameters: { action: 'cloudStatus'},
            onSuccess: function (oXHR, oJson) {
                var response = oXHR.responseText;
				//alert("response = "+response);
				if(response==1){
				cloudSta=response;
				}
				else if(response==0){
				cloudSta=response;
				}
				},
            onFailure: function (oXHR, oJson) {
                alert("There was an error with the process, Please try again!");
            }
        };
		var req = new Ajax.Request('cloud.php?id='+Math.random(10000,99999), oOptions);	
		if(cloudSta==1){
		this.data.get("Configuration").get("System").unset("General");
		this.data.get("Configuration").get("System").unset("IP Settings");
		this.data.get("Configuration").get("System").unset("Reset");
		}
		else if(cloudSta==0){
		this.data.get("Configuration").get("System").unset("Basic");
		}
		if (!config.CLOUD.status) {
				//this.data.get("Configuration").get("System").unset("Advanced");
				this.data.unset("Cloud");
			}
		if (window.top.frames['header']._apmode == false || (!config.ARADA_IPS.status)) {
			this.data.get("Configuration").unset("IDS/IPS");
			this.data.get("Monitoring").unset("IDS/IPS");
		}
		
		if (!config.ARADA_IPS.status) {
			this.data.get("Configuration").unset("IDS/IPS");
			this.data.get("Configuration").unset("IDS/IPS Mail Settings");
			this.data.get("Monitoring").unset("IDS/IPS");
		}			
		if (!config.IPV6.status) {		
			this.data.get("Configuration").get("IP").unset("IPv6 Settings");									
		}
		if (!config.ARADA_LLDP.status) {				
			this.data.get("Configuration").get("System").get("Advanced").removeItem("Ethernet LLDP");		
		}
		if (!config.USERACCOUNTS.status) {				
			this.data.get("Configuration").get("System").get("Advanced").removeItem("User Accounts");
		}
		if (!config.ARADA_QOS.status) {							
			this.data.get("Configuration").get("Wireless").get("Advanced").removeItem("QoS Policies");
		}
		if ((!config.MBSSID.status && !config.HTTPREDIRECT.status && !config.SYSLOGD.status && !config.ETHERNET_CONFIG.status)) {
			this.data.get("Configuration").get("System").unset("Advanced");
			this.data.get("Monitoring").unset("Logs");
		}
		
		
		else {
			if (!config.MBSSID.status) {
				this.data.get("Configuration").get("System").get("Advanced").removeItem("General");
			}
			if (!config.HTTPREDIRECT.status) {
				this.data.get("Configuration").get("System").get("Advanced").removeItem("Hotspot");
			}
			if (!config.SYSLOGD.status) {
				this.data.get("Configuration").get("System").get("Advanced").removeItem("Syslog");
				this.data.get("Monitoring").unset("Logs");
			}
			if (!config.ETHERNET_CONFIG.status) {
				this.data.get("Configuration").get("System").get("Advanced").removeItem("Ethernet");
			}
		}
		
        if(!config.PACKET_CAPTURE.status) {
            this.data.get("Monitoring").unset("Packet Capture");
        }
		if (!config.DHCPSERVER.status) {
			this.data.get("Configuration").get("IP").unset("DHCP Server Settings");
		}
		if (!config.DHCP_SNOOPING.status && !config.IGMP_SNOOPING.status) {
			this.data.get("Configuration").get("IP").unset("Snooping");
		}
        if(config.ARIES.status){
           if(!config2.WG102.status){
            this.data.get("Configuration").get("IP").unset("Snooping");
			this.data.get("Maintenance").get("Remote Management").unset("TR 069");
                    }
       }
		if(cloudSta==0){
		this.data.get("Configuration").unset("IP");
		this.data.get("Configuration").unset("Wireless");
		this.data.get("Configuration").unset("Security");
		this.data.get("Configuration").unset("Wireless Bridge");
		this.data.get("Configuration").get("System").unset("Advanced");
		this.data.get("Configuration").unset("IDS/IPS");
		this.data.get("Monitoring").unset("System");
		this.data.get("Monitoring").unset("Wireless Stations");
		this.data.get("Monitoring").unset("Rogue AP");
		this.data.get("Monitoring").unset("Packet Capture");
		this.data.get("Monitoring").unset("Statistics");
		this.data.get("Monitoring").unset("IDS/IPS");

		}
		if (!config.WMM.status) {
			this.data.get("Configuration").get("Wireless").get("Basic").removeItem("QoS Settings");
			this.data.get("Configuration").get("Wireless").get("Advanced").removeItem("QoS Settings");
		}
		if(!config.SCH_WIRELESS_ON_OFF.status){
			this.data.get("Configuration").get("Wireless").get("Basic").removeItem("Wireless On-Off");
		}
		if (!config.MACACL.status) {
			this.data.get("Configuration").get("Security").get("Advanced").removeItem("MAC Authentication");
		}
                if (config.WN604.status) {
			this.data.get("Configuration").get("Security").get("Advanced").removeItem("Radius Server Settings");
		}

		if (!config.ROGUEAP.status) {
			this.data.get("Configuration").get("Security").get("Advanced").removeItem("Rogue AP");
			this.data.get("Monitoring").unset("Rogue AP");
		}
		if (!config.SNMP.status && ((!config.SSH.status && !config.TELNET.status) || (!config.CLI.status)) &&(!config.TR69.status))  {
			this.data.get("Maintenance").unset("Remote Management");
		}
		else {
			if (!config.SNMP.status) {
				this.data.get("Maintenance").get("Remote Management").unset("SNMP");
			}
			
			if (((!config.SSH.status && !config.TELNET.status) || (!config.CLI.status))) {
				this.data.get("Maintenance").get("Remote Management").unset("Remote Console");
			}
			if (!config.TR69.status) {
				this.data.get("Maintenance").get("Remote Management").unset("TR 069");
			}
		}
		if(!config.WPS.status){
			this.data.get("Configuration").unset('WPS');
		}
                if(!config.FMU_GUI_TFTP.status){
                        this.data.get("Maintenance").get("Upgrade").unset('Firmware Upgrade TFTP');
                }

	},
	updateItem: function(item, value, key) {
		var key = (key == undefined)?0:key;
		switch(item) {
			case 'first':
				this.currentData.first = value;
				break;
			case 'second':
				this.currentData.second = value;
				break;
			case 'third':
				this.currentData.third = value;
				break;
			case 'fourth':
				this.currentData.fourth[key] = value;
				break;
		}
	},
	updatePointer: function(level, value) {
		switch(level) {
			case 'first':
				this.pointer.first = value;
				break;
			case 'second':
				this.pointer.second = value;
				break;
			case 'third':
				this.pointer.third = value;
				break;
			case 'fourth':
				this.pointer.fourth = value;
				break;
		}
	},
	getLevel: function(level,id) {
		switch(level) {
			case 'first':
				return this.currentData.first[id];
				break;
			case 'second':
				return this.currentData.second[id];
				break;
			case 'third':
				return this.currentData.third[id];
				break;
			case 'fourth':
				return this.currentData.fourth[id];
				break;
		}
		return this.first[id];
	},
	getPointer: function(level) {
		switch(level) {
			case 'first':
				return this.pointer.first;
				break;
			case 'second':
				return this.pointer.second;
				break;
			case 'third':
				return this.pointer.third;
				break;
			case 'fourth':
				return this.pointer.fourth;
				break;
		}
	},
	resetPointer: function(level) {
		switch(level) {
			case 'first':
				this.updatePointer('second',0);
				break;
			case 'second':
				this.updatePointer('third',0);
				break;
			case 'third':
				this.updatePointer('fourth',0);
				break;
		}
	},
	getFirstLevelData:  function() {
		this.updateItem('first',this.data.keys());
		this.getSecondLevelData()
	},
	getSecondLevelData: function() {
		var dataValues = this.data.values();
		for (var i=0; i<dataValues.length;i++) {
			if (i == this.getPointer('first')) {
				this.updateItem('second',dataValues[i].keys());
				//alert('Second Level['+i+'] Updated with '+dataValues[i].keys());
			}
		}
		this.getThirdLevelData();
	},
	getThirdLevelData: function() {
		var dataValues = this.data.values();
		for (var key=0; key<dataValues.length;key++) {
			if (key == this.getPointer('first')) {
				var itemValues = dataValues[key].values();
				for (var key2=0; key2<itemValues.length;key2++) {
					if (key2 == this.getPointer('second')) {
						this.updateItem('third',itemValues[key2].keys());
						//alert('Third Level['+key+']['+key2+'] Updated with '+itemValues[key2].keys());
					}
				}
			}
		}
		this.getFourthLevelData();
	},
	getFourthLevelData: function() {
		var dataValues = this.data.values();
		for (var key=0; key<dataValues.length;key++) {
			if (key == this.getPointer('first')) {
				var itemValues = dataValues[key].values();
				for (var key2=0; key2<itemValues.length;key2++) {
					if (key2 == this.getPointer('second')) {
						var item2Values = itemValues[key2].values();
						for (var key3=0; key3<item2Values.length;key3++) {
							this.updateItem('fourth', item2Values[key3], key3);
							//alert('Fourth Level['+key+']['+key2+']['+key3+'] Updated with '+item2Values[key3]);
						}
					}
				}
			}
		}
	},
	updateMenu: function(level, pointer, start) {
		if (window.top.frames['master']._disableAll != undefined && window.top.frames['master']._disableAll == true) {
			return ;
		}
/*		if (!window.top.frames['header']._initiateMenu) {
			if (window.top.frames['master'].progressBar == undefined || window.top.frames['master'].progressBar.isOpened() == true) {
				//if (!confirm('Page is currently loading!\nAre you sure you want to navigate to this page?'))
					//return;
			}
		}*/

		if (start!=undefined) {
			this.initialLoad = start;
		}
		this.updatePointer(level,pointer);
		switch(level) {
			case 'first':
				this.getFirstLevelData();
				this.updateFirstMenu();
				break;
			case 'second':
				this.getSecondLevelData();
				this.updateSecondMenu();
				break;
			case 'third':
				this.getThirdLevelData();
				if (!this.initialLoad) {
					this.updateThirdMenu();
				}
				else {
					loadThird = setTimeout(loadThirdMenu, 50);
				}
				break;
		}
		if (!this.initialLoad) {
			showPage('',this.getLevel('third',this.getPointer('third')),this.getLevel('second',this.getPointer('second')),this.getLevel('first',this.getPointer('first')),[this.getPointer('third'),0],false);
		}
		return;
	},
	updateFirstMenu: function() {
		var primaryTabs = $('primaryNav').immediateDescendants();
		for (var x=0; x< primaryTabs.length; x++) {
		if (this.getLevel('first',x)!=undefined) {
			if (this.getPointer('first') == x) {
				primaryTabs[x].replace("<LI class='Active'><A href='#' onclick=\"javascript:menuObject.updateMenu('first',"+x+", false);\">"+this.getLevel('first',x)+"</A></LI>");
			}
			else {	
				primaryTabs[x].replace("<LI><A href='#' onclick=\"javascript:menuObject.updateMenu('first',"+x+",false);\">"+this.getLevel('first',x)+"</A></LI>");
			}
			}
			else {
				primaryTabs[x].hide();
			}
			
		}
		if (this.getLevel('first', this.getPointer('first')) == 'Monitoring' || this.getLevel('first', this.getPointer('first')) == 'Support') {
			if (typeof(window.top.frames['action'].$) == 'function' && window.top.frames['action'].$('ButtonsDiv') != undefined)
				window.top.frames['action'].$('standardButtons').hide();
		}
		else {
			if (typeof(window.top.frames['action'].$) == 'function' && window.top.frames['action'].$('ButtonsDiv') != undefined)
				window.top.frames['action'].$('standardButtons').show();
		}
		this.updatePointer('second',0);
		this.getSecondLevelData();
		this.updateSecondMenu();
	},
	updateSecondMenu: function() {
		var secondaryTabs = $('secondaryNav').immediateDescendants();
		for (var x=0; x< ((secondaryTabs.length > this.currentData.second.length)?secondaryTabs.length:this.currentData.second.length); x++) {
			var str = "<LI><A href='javascript:void(0)' onclick=\"menuObject.updateMenu('second',"+x+",false);\" ";
			if (this.getPointer('second') == x) {
				str = str + "class='Active'";
			}
			str = str + ">"+this.getLevel('second',x)+"</A>";
			if (this.currentData.second.length != x+1)
				str =  str + '&nbsp;<img src="images/tab_separator.gif" class="separatorImage">&nbsp;';
			str = str + '</LI>';
			if (this.getLevel('second',x)!=undefined) {
				secondaryTabs[x].show();
				secondaryTabs[x].replace(str);
			}
			else {
				secondaryTabs[x].hide();
			}
		}
		this.updatePointer('third',0);
		//alert($('secondaryNav').immediateDescendants());
		//alert(this.getPointer('first')+'----'+this.getPointer('second')+'----'+this.getPointer('third')+'----'+this.getPointer('fourth'));

		this.getThirdLevelData();
		if (!this.initialLoad)
			this.updateThirdMenu();
		if (this.getLevel('first', this.getPointer('first')) == 'Monitoring' || this.getLevel('first', this.getPointer('first')) == 'Support') {
			if (typeof(window.top.frames['action'].$) == 'function' && window.top.frames['action'].$('ButtonsDiv') != undefined)
				window.top.frames['action'].$('standardButtons').hide();
		}
		else {
			if (typeof(window.top.frames['action'].$) == 'function' && window.top.frames['action'].$('ButtonsDiv') != undefined)
				window.top.frames['action'].$('standardButtons').show();
		}			
	},
	updateThirdMenu: function() {
		var x = 0;
		if (window.top.frames['thirdmenu'].$('thirdMenuTable') == undefined) {
			this.resetPointer('third');
		}
		this.prepareThirdMenu();
	},
	prepareThirdMenu: function() {
		var thirdMenuTable = $CE('TABLE',{ className: 'tableStyle', id: 'thirdMenuTable' });
		//thirdMenuTableBody = $CE('TBODY',{ id: 'thirdMenuTableBody' });
		var x = 0, flag = true;
		var thirdTab = this.currentData.third;
		for (var x=0; x<thirdTab.length; x++) {
			var thirdMenuRow = thirdMenuTable.appendRow({ id: 'TR_Main_'+x });
			var str = '';
			var imgStr = 'right';
			if (x == this.getPointer('third')) {
				if(config.AWSDAP350.status || config.INDUS.status || config.AUGMENTIX.status){
					str = 'style="color: #B3C188;"';
				}else{
					str = 'style="color: #FFA400;"';
				}
				imgStr = 'down';
			}
			//alert(this.getLevel('fourth',x));
			var linkStr = '<A onclick="window.top.frames[\'header\'].menuObject.updateMenu(\'third\','+x+', false);" href="javascript:void(0)" class="TertiaryNav" '+ str +'><strong>'+thirdTab[x]+'</strong></A>';
			if (this.getLevel('fourth',x).length==0) {
				flag = false;
				imgStr = 'right';
			}
			thirdMenuRow.appendCell({ id: 'TD_PriArrow_'+x, width: '10px', height: '10px', vAlign: 'top', className: 'padAll noPadRight' }).update('<img src="images/arrow_'+imgStr+'.gif" id="img_Basic" style="border: 0px; margin: 0px; margin-top: 3px; _margin-top: 0px; float: both; vertical-align: middle;">');
			thirdMenuRow.appendCell({ id: 'TD_Main_'+x, colSpan: 2, className: 'padAll noPadLeft' }).update(linkStr);
			if (x == this.getPointer('third')) {
				this.prepareFourthMenu(x, thirdMenuTable);
			}
			//thirdMenuTableBody.appendChild(thirdMenuRow);
		}
		//thirdMenuTable.appendChild(thirdMenuTableBody);
		//alert(thirdMenuTable.innerHTML);
		if (flag) {
			var buttons = new buttonObject();
			var bList = buttons.getButtons(this.getPointer('first'),this.getPointer('second'),this.getPointer('third'),this.getPointer('fourth'));
		}
		try {
			window.top.frames['thirdmenu'].$('TreeFrame').innerHTML = '';
            if(navigator.appName == 'Microsoft Internet Explorer')
                window.top.frames['thirdmenu'].$('TreeFrame').update(thirdMenuTable.outerHTML);
            else
                window.top.frames['thirdmenu'].$('TreeFrame').appendChild(thirdMenuTable);
		}
		catch(e) {
			window.top.frames['thirdmenu'].$('TreeFrame').update(thirdMenuTable.outerHTML);
		}
		//alert(this.getLevel('first',this.getPointer('first')) + '---' + this.getLevel('second',this.getPointer('second')) + '---' +this.getLevel('third',this.getPointer('third')));
		//alert(window.top.frames['thirdmenu'].$('TreeFrame').innerHTML);
	},
	prepareFourthMenu: function(level, body) {
		var items = this.currentData.fourth.item(level);
		for (var x=0; x < items.length; x++) {
			var fourthLevelRow = body.appendRow({ id: 'TR_Second_'+level+x });
			var str = (x == this.getPointer('fourth'))?'nOrange':'nBlue';

			fourthLevelRow.appendCell({ className: str, id: 'TD_Dummy_'+level+x});
			fourthLevelRow.appendCell({ className: str, id: 'TD_SecArrow_'+level+x, width: '8px', height: '8px', vAlign: 'top' }).update('&raquo;');
			fourthLevelRow.appendCell({ className: str, id: 'TD_Second_'+level+x, className: 'FourthLevelNav fourthLevelLink' }).update('<a href="javascript:void(0)" class="'+str+'" style="text-decoration: none;" onclick="showPage(\''+items[x]+'\',\''+this.getLevel('third',level)+'\',\''+this.getLevel('second',this.getPointer('second'))+'\',\''+this.getLevel('first',this.getPointer('first'))+'\',['+level+','+x+']);">'+items[x]+'</A>');
		}
	},
	updateFourthMenu: function(level) {
		var x = this.getPointer('third');
		for (var i=0; i< this.currentData.fourth[this.getPointer('third')].length; i++) {
			var thirdPointer = $(window.top.frames['thirdmenu'].$('TR_Second_'+this.getPointer('third')+i));
			if ((thirdPointer != undefined) && (typeof(thirdPointer.descendants) == 'function'))
			thirdPointer.descendants().each(function(tdItem) {
				if (level[0] == x && level[1] == i) {
					tdItem.removeClassName('nBlue');
					tdItem.addClassName('nOrange');
					if ($(tdItem).down() != null) {
						$(tdItem).down().className = 'nOrange';
					}
				}
				else {
					tdItem.removeClassName('nOrange');
					tdItem.addClassName('nBlue');
					if ($(tdItem).down() != null) {
						$(tdItem).down().className = 'nBlue';
					}
				}
			});
		}
		var buttons = new buttonObject();
		var bList = buttons.getButtons(this.getPointer('first'),this.getPointer('second'),x,level[1]);
	},
	test: function() {
		alert("First = "+this.pointer.first+"\nSecond = "+this.pointer.second+"\nThird = "+this.pointer.third+"\nFourth = "+this.pointer.fourth);
	}
});

menuObject = new menuClass();
//menuObject.updateFirstMenu();

Event.onDOMReady ( function() {
	if (window.top.frames['header']._initiateMenu != undefined && window.top.frames['header']._initiateMenu != false) {
		window.top.frames['header'].menuObject.updateMenu('first', 0, true);
	//	window.top.frames['header'].menuObject.test();
	}
});
function initiateMenu(start)
{
	window.top.frames['header'].menuObject.updateMenu('first',0, true);
	window.top.frames['header'].menuObject.test();
}


//var menu1 = new menuData();
