/**
 * menuObject
 *  - 
 * author: Moa Chung
 * date:   2013-08-22
 * notice: 
 */
function menuObject() {
	this.v4v6;
	this.usb;
	//main
	var basic_L1 =
		[
			{title:'_networkstate',page:'basic_status.asp',img:'but_network_status'},
			{title:'_wireless',page:'basic_wireless.asp',img:'but_wireless'},
			{title:'_guest_network',page:'basic_guest.asp',img:'but_guest_network'},
			{title:'_parental_control',page:'basic_parental_control.asp',img:'but_parental_control'}
		];
	
	var advanced_L1 =
		[
			{title:'ADMIN',page:'adm_status.asp',img:'but_administrator'},
			{title:'_setup',page:'internet_lan.asp',img:'but_setup'},
			{title:'_wireless_2',page:'wireless_wds.asp',img:'but_wireless_24'},
			{title:'_wireless_5',page:'wireless2_wds.asp',img:'but_wireless_5'},
			{title:'ES_security',page:'adv_access_control.asp',img:'but_security'},
			{title:'_firewall',page:'adv_dmz.asp',img:'but_firewall'},
			{title:'_usb',page:'smbserver.asp',img:'but_usb'}
		];
	var advanced_L2 = [
		//administrator(0)
		[
			{title:'_status',page:'adm_status.asp'},				{title:'_ipv6_status',page:'adm_ipv6status.asp'},
			{title:'_system_log',page:'adm_syslog.asp'},			{title:'_advnetwork',page:'adv_network.asp'},
			{title:'_settings_management',page:'adm_settings.asp'},	{title:'_time_cap',page:'adm_time.asp'}
		],
		//setup(1)
		[
			{title:'_lan_setting',page:'internet_lan.asp'},			{title:'_wan_setting',page:'internet_wan.asp'},
			{title:'_routing',page:'adv_routing.asp'},				{title:'_ipv6_setting',page:'internet_ipv6.asp'},
			/*{title:'help660',page:'internet_qos.asp'},*/			{title:'_sched',page:'adv_schedule.asp'},
			{title:'_upgrade_firmw',page:'adm_upload_firmware.asp'},{title:'_management',page:'adm_management.asp'},
			{title:'bd_DHCP',page:'internet_dhcpcliinfo.asp'},		{title:'_wizard',page:'wizard_router.asp'}
		],
		//wireless 2.4GHz(2)
		[
			{title:'help743',page:'wireless_wds.asp'},				{title:'_advanced',page:'wireless_advanced.asp'},
			{title:'mult_ssid',page:'wireless_mssid.asp'},			{title:'_mac_filter',page:'wireless_security.asp'},
			{title:'_WPS',page:'wireless_wps.asp'},					{title:'_statlst',page:'wireless_stainfo.asp'}
		],
		//wireless 5GHz(3)
		[
			{title:'help743',page:'wireless2_wds.asp'},				{title:'_advanced',page:'wireless2_advanced.asp'},
			{title:'mult_ssid',page:'wireless2_mssid.asp'},			{title:'_mac_filter',page:'wireless2_security.asp'},
			{title:'_WPS',page:'wireless2_wps.asp'},				{title:'_statlst',page:'wireless2_stainfo.asp'}
		],
		//security(4)
		[
			{title:'_acccon',page:'adv_access_control.asp'},		{title:'_inboundfilter',page:'adv_inbound_filter.asp'}
		],
		//firewall(5)
		[
			{title:'help488',page:'adv_dmz.asp'},					{title:'_virtserv',page:'adv_virtual.asp'},
			{title:'_specapps',page:'adv_port_trigger.asp'},		{title:'_gaming',page:'adv_port_range.asp'},
			{title:'_alg',page:'adv_alg.asp'}
		],
		//usb(6)
		[
			{title:'_samba_server',page:'smbserver.asp'},			{title:'_ftp_server',page:'ftpserver.asp'}
		],
		//help(7)
		[
			{title:'ish_menu',page:'help_menu.asp'},				{title:'_network',page:'help_network.asp'},
			{title:'_wireless',page:'help_wireless.asp'},			{title:'_advanced',page:'help_advanced.asp'},
			{title:'ADMIN',page:'help_administrator.asp'}
		]
	];

	
	this.build_structure = function(category, idx, sub_idx)
	{
		var content_main="";
		var which;
		var total;
		content_main += '<div class="arrowlistmenu">';
		content_main += '<div class="homenav" style="margin-bottom:20px;">';
		if(category=='0'){
			which = basic_L1;
			total = which.length;
			content_main += '<a href="/basic_status.asp"><span class="category_1" id="category_basic">'+get_words('_basic')+'</span></a>';
			content_main += '<a href="/adm_status.asp"><span class="category_0" id="category_advanced">'+get_words('_advanced')+'</span></a>';
		}else{
			which = advanced_L1;
			total = (this.usb?which.length:--which.length);
			content_main += '<a href="/basic_status.asp"><span class="category_0" id="category_basic">'+get_words('_basic')+'</span></a>';
			content_main += '<a href="/adm_status.asp"><span class="category_1" id="category_advanced">'+get_words('_advanced')+'</span></a>';
		}
		content_main += '</div>';
		content_main += '<div class="borderbottom"> </div>';
		for(var i=0; i<total; i++)
		{
			if(category==0){
				content_main += '<div><div onclick="menuObject.animoa(this,\''+ which[i].page +'\');" class="menuheader expandable '+(i == idx?'openheader':'closeheader')+'"><img src="/image/'+which[i].img+'_'+(i==idx?'1':'0')+'.png" class="CatImage" /><span class="CatTitle">'+ get_words(which[i].title) +'</span></div>';
			}
			else{
				if(i == idx)
					content_main += '<div><div onclick="menuObject.animoa(this);" class="menuheader expandable openheader"><img src="/image/'+which[i].img+'_1.png" class="CatImage" /><span class="CatTitle">'+ get_words(which[i].title) +'</span></div>';
				else
					content_main += '<div><div onclick="menuObject.animoa(this);" class="menuheader expandable closeheader"><img src="/image/'+which[i].img+'_0.png" class="CatImage" /><span class="CatTitle">'+ get_words(which[i].title) +'</span></div>';
			}
			
			content_main += this.build_sub_structure(category, i, sub_idx, (i == idx));
			content_main += '</div>';
		}
		content_main += '</div>';
		//$("#main_title").html(content_main);
		return content_main;
	};

	this.build_sub_structure = function(category, idx, sub_idx, expand)
	{
		var which = new Array();
		var content_sub='';
		if(category==1)
		{
			which = advanced_L2[idx];
		}
		if(expand)
			content_sub += '<ul class="categoryitems">';
		else
			content_sub += '<ul class="categoryitems" style="display:none;">';
		for(var j=0; j<which.length; j++)
		{
			if(j==sub_idx && expand)
				content_sub += '<li><a href="'+ which[j].page +'" style="color:#00aff0;">'+ get_words(which[j].title) +'</a></li>';
			else
				content_sub += '<li><a href="'+ which[j].page +'">'+ get_words(which[j].title) +'</a></li>';
		}
		content_sub += '</ul>';
		return content_sub;
	};
	
	this.setSupportUSB = function(is){
		this.usb = is;
	};
}

menuObject.animoa = function(node, redirect){
//	console.log(redirect);
	var src = $('.menuheader.expandable.openheader').find('img').attr('src');
	if(src != undefined)
		$('.menuheader.expandable.openheader').find('img').attr('src', src.replace('_1.','_0.'));
	$('.menuheader.expandable.openheader').toggleClass('openheader').toggleClass('closeheader');
	$(node).toggleClass('closeheader').toggleClass('openheader');
	src = $(node).find('img').attr('src');
	if(src != undefined)
		$(node).find('img').attr('src', src.replace('_0.','_1.'));
	$('.categoryitems').slideUp();
	$(node).parent().children('ul').slideDown(400, function(){
		if(redirect!=undefined)
			location.assign(redirect);
	});
};