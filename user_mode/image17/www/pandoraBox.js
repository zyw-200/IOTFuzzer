var noAuthPage = new Array('login.asp', 'login_fail.asp', 'logout.asp', 'es_base.asp', 
		'es_display.asp', 'summary.asp', 'reject.asp', 'time_ctl.asp', 'error-404.asp', 
		'wizard_router.asp', 'wa_login.asp', 'reject.htm', 'goto_mydlink.asp', 'setup_wizard.asp');
var noRedirectLogin = new Array('tools_firmw.asp', 'login.asp', 'reject.asp', 'time_ctl.asp', 'st_device.asp',
		'back.asp', 'error-404.asp', 'wps_back.asp', 'reboot.asp', 'wizard_router.asp', 'wa_login.asp', 'reject.asp',
		"wizard_mydlink.asp", 'goto_mydlink.asp', 'setup_wizard.asp','');
var noTimeoutPage = new Array();
function get_time_str(str)
{
	try {
		var n = parseInt(str);
		
		if (n < 10)
			return ('0'+str);
		else
			return str;
	} catch (e) {
		return '00';
	}
}

function show_schedule_detail(idx, sch_obj){
	var detail = '';
	var s_day = '';
	var str_day = new String(sch_obj.days);

	for(var j = 0; j < 8; j++){			
		if(str_day.substr(j, 1) == "1"){
			s_day += (Week[j] + ' ');
		}
	}

	if(sch_obj.allweek == '1' || s_day == ''){
		s_day = "All week";
	}

	var str_time = get_time_str(sch_obj.start_h)+':'+get_time_str(sch_obj.start_mi)+
				'~'+get_time_str(sch_obj.end_h)+':'+get_time_str(sch_obj.end_mi);
	if(sch_obj.allday == '1' || str_time== '00:00~24:00'){
		str_time = "All Day";
	}
	
	detail = s_day + ", " + str_time;
	return detail;
}

function getCookieValue(val) {
	if ((endOfCookie = document.cookie.indexOf(";", val)) == -1) {
		endOfCookie = document.cookie.length;
	}
	return unescape(document.cookie.substring(val,endOfCookie));
}

function getCookie(name) {
	if (document.cookie == null)
		return null
	
	var ckLen = document.cookie.length;
	var sName = name + "=";
	var cookieLen = sName.length;
	var x = 0;
	while (x <= ckLen) {
		var y = (x + cookieLen);
		if (document.cookie.substring(x, y) == sName)
			return getCookieValue(y);
		x = document.cookie.indexOf(" ", x) + 1;
		if (x == 0){
			break;
		}
	}
	return null;
}

function getDocName() {
	var file_name = document.location.pathname;
	var end = (file_name.indexOf("?") == -1) ? file_name.length : file_name.indexOf("?");
	return file_name.substring(file_name.lastIndexOf("/")+1, end);
}

function needAuth(page) {
	if (noAuthPage == null || page == null)
		return 1;

	for (var i=0; i<noAuthPage.length; i++) {
		if (page == noAuthPage[i])
			return 0;
	}
	
	return 1;
}

function inst_array_to_string(inst)
{
	var size = inst.length;
	var string = "";
	for(var i=0; i<size; i++)
	{
		string += inst[i];
	}
	//alert("string = "+ string);
	return string;
}

/**
 *	make_req_entry
 *
 *	obj_name:	name of obj (ex: schRule_RuleName_)
 *	obj_value:	value of obj
 *	obj_inst:	instance of obj (ex: 11000 or 1.1.0.0.0)
 *
 *	return: entry name of the element
 */
function make_req_entry(obj_name, obj_value, obj_inst)
{
	var r = obj_name;
	var s = new String(obj_inst);

	// instance already contains 'dot'
	if (s.indexOf('.') != -1) {
		r += obj_inst+'='+obj_value;
		return r;
	}
	
	for (var i=0; i<obj_inst.length-1; i++) {
		r += (s.substr(i, 1) + '.');
	}
	
	r += s.substr(obj_inst.length-1, 1);
	r += '='+obj_value;
	
	return r;
}

/**
 *	check_addr_order
 *	
 *	return: true: 	start ip is behind end ip
 *			false: 	start ip is after end ip
 *
 *	parameters:
 *		ip_s:	a string of start ip
 *		ip_e:	a string of end ip
 */
function check_addr_order(ip_s, ip_e)
{
	var arr_ips = ip_s.split('.');
	var arr_ipe = ip_e.split('.');

	if (arr_ips == null || arr_ipe == null || 
		arr_ips.length != 4 || arr_ipe.length != 4) {
		return false;
	}
	
	for (var i=0; i<4; i++) {
		if (arr_ips[i] > arr_ipe[i])
			return false;
	}
	
	return true;
}

/**
 *	check_addr
 *	
 *	return: true: 	input ip is a LAN IP
 *			false: 	input ip is NOT a LAN IP
 *
 *	parameters:
 *		ip:		a string of ip we want to check
 *		lanip:	a string of lan ip
 *		mask:	subnet mask
 */
function check_addr(ip, lanip, mask)
{
	if (ip == null || lanip == null || mask == null)
		return false;

	var arr_ip 		= ip.split('.');
	var arr_lanip 	= lanip.split('.');
	var arr_mask	= mask.split('.');
	var err = 0;
	
	// input is not an IP
	if (arr_ip == null || arr_ip.length != 4) {
		alert(LangMap.msg['INVALID_IP']);
		return false;
	}
	
	// check the ip is "0.0.0.0" or not
	if (ip[0] == "0" && ip[1] == "0" && ip[2] == "0" && ip[3] == "0"){
		alert(LangMap.msg['INVALID_IP']);
		return false;
	}
	
	for (var i=0; i<4; i++) {
		if ((arr_ip[i] & arr_mask[i]) == 0) {
			if (arr_ip[i] != 0)
				continue;
		}
		
		if (arr_ip[i] == arr_lanip[i])
			continue;
		err++;
	}
	
	if (err > 0)
		return false;
	else
		return true;
}

/**
 *	getUrlEntry
 *	
 *	return: string: 	value of input key
 *			null: 		not found
 *
 *	parameters:
 *		key:		a string we want to find in url entry
 */
function getUrlEntry(key)
{
	var search=location.search.slice(1);
	//alert(search);
	var my_id=search.split("&");
	//alert(my_id);
	try {
		for(var i=0;i<my_id.length;i++)
		{
			var ar=my_id[i].split("=");
			if(ar[0]==key)
			{
				return ar[1];
			}
		}
	} catch (e) {
	}
	
	return null;
} 

function redirect_login()
{
	var file = window.location.pathname.replace(/^.*\/(\w{2})\.asp$/i, "$1").replace('/', '');
	
	for (var i=0; i<noRedirectLogin.length; i++) {
		if (file == noRedirectLogin[i])
			return;
	}

	document.cookie = 'hasLogin=0;';
	
	setTimeout(function() {
		location.replace('login.asp');
	}, 0);
}

function do_logout() {
	document.cookie = 'hasLogin=0;';
	
	var param1 = {
		url: "login.ccp",
		arg: "act=logout"
	};
	
	var ajax_param = {
		type: 	"POST",
		async:	false,
		url: 	"login.ccp",
		data: 	"act=logout",
		success: function(data) {
			document.write(data);
		},
		error: function(xhr, ajaxOptions, thrownError){
			if (xhr.status == 200) {
				try {

					document.write(xhr.responseText);
				} catch (e) {
				}
			} else {
			}
		}
	};

	try {
		//setTimeout(function() {
			$.ajax(ajax_param);
		//}, 0);
	} catch (e) {
	}
}

function urlencode(text){
	text = text.toString();
	var matches = text.match(/[\x90-\xFF]/g);
	if (matches)
	{
		for (var matchid = 0; matchid < matches.length; matchid++)
		{
			var char_code = matches[matchid].charCodeAt(0);
			text = text.replace(matches[matchid], '%u00' + (char_code & 0xFF).toString(16).toUpperCase());
		}
	}
	//return escape(text).replace(/\+/g, "%2B");
	return text.replace(/[<>+\&\"\'\=\%]/g, function(c) { return '%' + (c.charCodeAt(0) & 0xFF).toString(16).toUpperCase() + ''; });
}

function disableDiv(divname, opt)
{
	var divObj=document.getElementById(divname);
	var elInput = divObj.getElementsByTagName("input");
	for(i=0;i<elInput.length;i++)
	{
		elInput[i].disabled=opt;
	}
}


/**
 * the following 2 functions are used to check if port range is overlapped
 *
 * add_into_timeline()
 * 
 * parameters:
 * 		timeline: 	an input variable for recording all port range.
 *		port_s:		start port of the range.
 *		port_e:		end port of the range. (if single port, this parameter should be empty or null)
 *
 * return:
 * 		timeline:	timeline for the following reference.
 */
function add_into_timeline(timeline, port_s, port_e)
{
	var cur_state = 0;
	
	// inital
	if (timeline == null || timeline == '') {
		if (port_e == null || port_e == '') {
			timeline = new Array(2);
			timeline[0] = port_s;
			timeline[1] = 0;			// single port
		} else {
			timeline = new Array(4);
			timeline[0] = port_s;
			timeline[1] = 1;			// up
			timeline[2] = port_e;
			timeline[3] = 2;			// down
		}
		return timeline;				// successfully added into timeline
	}
	
	// check if there exist something wrong in timeline
	var rec_port_s = 0;
	var length = timeline.length;
	for (var i=0; i<length; i+=2) {
		// add port_s first
		if (parseInt(timeline[i]) > parseInt(port_s) && rec_port_s == 0) {
			if (port_e == null || port_e == '')	{ 	//single port
				timeline.splice(i, 0, 0);			// add state first
				timeline.splice(i, 0, port_s);		// add port number
				return timeline;					// successfully added into timeline
			} else {
				var addPort_e = false;
				rec_port_s = 1;
				timeline.splice(i, 0, 1);			// add state first
				timeline.splice(i, 0, port_s);		// add port number
				for (var j=i; j<timeline.length; j+=2) {
					if (parseInt(timeline[j]) > parseInt(port_e)) {
						timeline.splice(j, 0, 2);			// add state first
						timeline.splice(j, 0, port_e);		// add port number
						addPort_e = true;
						return timeline;
					}
				}
				
				if (addPort_e == false) {
					var append_idx = timeline.length;
					timeline.splice(append_idx, 0, 2);
					timeline.splice(append_idx, 0, port_e);
					return timeline;
				}
				continue;
			}
		}
		
		if (rec_port_s == 0)
			continue;
		
		// add port_e
		if (parseInt(timeline[i]) > parseInt(port_e)) {
			timeline.splice(i, 0, 2);			// add state first
			timeline.splice(i, 0, port_e);	// add port number
			break;
		}
	}
	
	if (timeline.length == length) {			// append to last of timeline
		if (port_e == null || port_e == '') {	// single port
			timeline.splice(length, 0, 0);
			timeline.splice(length, 0, port_s);
		} else {
			timeline.splice(length, 0, 2);
			timeline.splice(length, 0, port_e);
			timeline.splice(length, 0, 1);
			timeline.splice(length, 0, port_s);
		}
	}
	
	return timeline;
}

/**
 * 	check_timeline()
 *
 *	parameter:
 *		timeline:	an input timeline for checking.
 *
 *	return:
 *		true:		no overlapped.
 *		false:		contains an overlapped.
 */
function check_timeline(timeline)
{
	var prev_port = -1;
	var prev_stat = 0;
	
	if (timeline == null || timeline == '')
		return true;

	for (var i=0; i<timeline.length; i+=2) {
		if (prev_port == parseInt(timeline[i]))
			return false;
		
		if (prev_stat == 1 && timeline[i+1] != 2)
			return false;
		
		prev_port = timeline[i];
		if (timeline[i+1] != 0)
			prev_stat = timeline[i+1];
	}
	
	return true;
}

function translateFormObjToAJAXArg(form_id)
{
	var df = document.forms[form_id];
	if (!df) {
		return;
	}
	
	var str = "";
	for (var i = 0, k = df.elements.length; i < k; i++) {
		var obj = df.elements[i];

		var name = obj.name;
		var value = obj.value;
		
		str +="&"+name+"="+value;
	}
	
	return str;
}

function check_hw_nat_enable()
{
	if(get_checked_value(get_by_id('HW_NAT_Enable')) == "1")
	{
		if((spi_enable == "1") || (trafficshap_enable == "1"))
			return confirm(get_words("alert_hw_nat_1"));
	}
	return true;
}

function escape(text)
{
	return text.replace(/[<>\&\"\']/g, function(c) { return '&#' + c.charCodeAt(0) + ';'; });
}


function json_ajax(param)
{
	var time=new Date().getTime();
	var myData = null;
	var ajax_param = {
		type: 	"POST",
		async:	false,
		url: 	param.url,
		data: 	param.arg+"&"+time+"="+time,
		dataType: "json",
		success: function(data) {
			if (data['status'] != 'fail') {
				myData = data;
				return;
			}
			
			alert('error: '+data['errno']);
			location.replace('wa_login.asp');
			
		},
		error: function(xhr, ajaxOptions, thrownError){
			if (xhr.status == 200) {
				try {
					setTimeout(function() {
						document.write(xhr.responseText);
					}, 0);
				} catch (e) {
				}
			} else {
			}
		}
	};
	
	try {
		//setTimeout(function() {
			$.ajax(ajax_param);
			return myData;
		//}, 0);
	} catch (e) {
	}	
}

function sp_words(passwd)	//20120112 silvia add
{
	//var wd = new Array(passwd);
	var wd = passwd.split('');
	var nwd = '';
	var wds = '';
	var  len = passwd.length;
	for (var i = 0;i < len; i++)
	{
		switch (wd[i]){
			case '&':
				wds = '&amp;';
				break;
			case '"':
				wds = '&quot;';
				break;
			case '<':
				wds = '&lt;';
				break;
			case '>':
				wds = '&gt;';
				break;
			case ' ':
				wds = '&nbsp;';
				break;
			default :
				wds = wd[i];
				break;
		}
		nwd += wds;
	}
	return nwd;
}


function check_browser()	//chk support bookmark and lang
	{
		var isMSIE = (-[1,]) ? false : true;
		var is_support =0;
		if(window.sidebar && window.sidebar.addPanel){ //Firefox
			is_support = 1;
		}else if (isMSIE && window.external) {  //IE favorite
			is_support = 2;
		}
		return is_support;
	}
	
function chk_browser_lang()
	{
		var tem_lang;
		var is_support=check_browser();
		if (is_support == 2)	//only for ie
			tmp_lang = language = window.navigator.userLanguage;
		else	// for other browser
			tmp_lang = window.navigator.language;
		currLindex = lang_compare(tmp_lang);
		return currLindex;
	} 
	
function lang_compare(tlang)
	{
		var lang;
		if(tlang.indexOf('en')==0)
			lang = '1';
		else if(tlang.indexOf('es')==0)
			lang = '2';
		else if(tlang.indexOf('de')==0)
			lang = '3';
		else if(tlang.indexOf('fr')==0)
			lang = '4';
		else if(tlang.indexOf('it')==0)
			lang = '5';
		else if(tlang.indexOf('ru')==0)
			lang = '6';
		else if(tlang.indexOf('pt-BR')==0)
			lang = '21';
		else if(tlang.indexOf('pt')==0)
			lang = '7';
		else if(tlang.indexOf('ja')==0)
			lang = '8';
		else if((tlang.indexOf('tw')!=-1) || (tlang.indexOf('TW')!=-1))
			lang = '9';
		else if((tlang.indexOf('cn')!=-1) || (tlang.indexOf('CN')!=-1))
			lang = '10';
		else if(tlang.indexOf('ko')==0)
			lang = '11';
		else if(tlang.indexOf('cs')==0)
			lang = '12';
		else if(tlang.indexOf('da')==0)
			lang = '13';
		else if(tlang.indexOf('el')==0)
			lang = '14';
		else if(tlang.indexOf('fi')==0)
			lang = '15';
		else if(tlang.indexOf('hr')==0)
			lang = '16';
		else if(tlang.indexOf('hu')==0)
			lang = '17';
		else if(tlang.indexOf('nl')==0)
			lang = '18';
		else if(tlang.indexOf('no')==0)
			lang = '19';
		else if(tlang.indexOf('pl')==0)
			lang = '20';
		else if(tlang.indexOf('ro')==0)
			lang = '22';
		else if(tlang.indexOf('sl')==0)
			lang = '23';
		else if(tlang.indexOf('sv')==0)
			lang = '24';
		else
			lang = '1';
		return lang;
	}

function termsOfUse_link(num_lang){
	//var num_lang =chk_browser_lang();
	var lang ="";
	switch(num_lang){
		case "1":
			lang = "en";
			break;
		case "2":
			lang = "es";
			break;
		case "3":
			lang = "de";
			break;
		case "4":
			lang = "fr";
			break;
		case "5":
			lang = "it";
			break;
		case "6":
			lang = "ru";
			break;
		case "7":
			lang = "pt";
			break;
		case "8":
			lang = "ja";
			break;
		case "9":
			lang = "zh_TW";
			break;
		case "10":
			lang = "zh_CN";
			break;
		case "11":
			lang = "ko";
			break;
		case "12":
			lang = "cs";
			break;
		case "13":
			lang = "da";
			break;
		case "14":
			lang = "el";
			break;
		case "15":
			lang = "fi";
			break;
		case "16":
			lang = "hr";
			break;
		case "17":
			lang = "hu";
			break;
		case "18":
			lang = "nl";
			break;
		case "19":
			lang = "no";
			break;
		case "20":
			lang = "pl";
			break;
		case "21":
			lang = "pt_BR";
			break;
		case "22":
			lang = "ro";
			break;
		case "23":
			lang = "sl";
			break;
		case "24":
			lang = "sv";
			break;
		default:
			lang = "en";
			break; 
		}
	return lang;
	}

	function mail_addr_test(str)
	{
		var rlt = 0;
		var tmp = str.split("@");
		try{
	        if(tmp.length == 2 && /^([+]?)*([a-zA-Z0-9]*[_|\-|\.|\+|\%|\*|\?|\!|\\]?)*[a-zA-Z0-9]*([+]?)+$/.test(tmp[0]) && /^([a-zA-Z0-9]*[_|\-|\.|\+|\%|\*|\?|\!|\\]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,6}$/.test(tmp[1])){
	            rlt = 1
	        }
		}catch(e){}
		return rlt;
	}

	function media_server_chk(str)
	{
		var rlt = 0;
		try{
	        if(str != '' && /^([+]?)*([a-zA-Z0-9]*[_|\-|\.|\+|\%|\*|\?|\!|\\]?)*[a-zA-Z0-9]*([+]?)+$/.test(str)){
	            rlt = 1
	        }
		}catch(e){}
		return rlt;
	}
	
	function mac_format(str)
	{
		var mac="";
		for(var i=0; i<6; i++)
		{
			if(i!=5)
				mac+= str.substring(i*2,i*2+2) + ":";
			else
				mac+= str.substring(i*2,i*2+2);
		}		
		return mac;
	}

	//20130122 Silvia add for verfify NetBIOS --> Filter sp words
	function chk_chars(tmp_name)
	{
		var prohibit_char = new Array("!", "@", "#", "$", "%", "^", "&", "(", ")", "-", "_" , "'", "{", 
					"}", "." , "~", "\\" , "*", "+" , "=", "|", ":", ";", "/", "?" , "<", ">", ",", "[", "]");

		for(var i=0; i < tmp_name.length; i++)
		{
			for(var j=0; j < prohibit_char.length; j++)
			{
				if( (tmp_name.charAt(i) == prohibit_char[j]))
				{
					return false;
				}
			}
		}
		return true;
	}

/*
**    Date:		2013-04-11
**    Author:	Silvia Chang
**    Reason:   Move it!! For Gateway Name use.
**/
function check_dev_name(devName)
{
	if (devName == null)
		return false;

	var all_num = true;

	for (var i=0; i<devName.length; i++) {
		var data = devName.substring(i, i+1);

		// check if at least one char is not an number
		if ((data >= 'A' && data <= 'Z') || (data >= 'a' && data <= 'z')) {
			all_num = false;
		}

		// first bit must be a-z or A-Z, 0-9
		if ((i == 0) && !(data >= 'A' && data <= 'Z') && !(data >= 'a' && data <= 'z') && !(data >= '0' && data <= '9')) {
			return false;
		}

		if ((data >= 'A' && data <= 'Z') || (data >= '0' && data <= '9') || (data >= 'a' && data <= 'z') || data == '-' || data == '_') {
			continue;
		}

		return false;
	}

	if (all_num == true) {
		return false;
	}

	return true;
}

/*
**    Date:		2013-04-11
**    Author:	Silvia Chang
**    Reason:   Check Host name cannot entry  `;:|'"\
**/
function check_client_name(name)
{
	var re = /[`;:|'"\\]/;
	if(re.test(name)){
		return false;
	}
	return true;
}

/*
** Check PPTP or L2TP Server IP Address is IP pattern or not
** @author:	Pascal Pai
** @date:	2013-05-23
** @param:	ip_str - ip address in string
** 			flag - 1 check v4 only
** 					2 check v6 only
** 					3 check both v4/v6(default)
** @note:		Moa: 2013-05-29 fixed for ip extract and shorter regex
				Moa: 2013-08-05 add for check if ipv6 address or not
				Moa: 2013-10-23 enhance: It can check which ip version by 2nd parameter
**/
function ip_pattern(ip_str,flag)
{
	if(flag==undefined)
		flag=3;
	var v4_pattern = /^\d+(\.\d+){3}$/;
	var v6_pattern = /^((?=.*::)(?!.*::.+::)(::)?([\dA-F]{1,4}:(:|\b)|){5}|([\dA-F]{1,4}:){6})((([\dA-F]{1,4}((?!\3)::|:\b|$))|(?!\2\3)){2}|(((2[0-4]|1\d|[1-9])?\d|25[0-5])\.?\b){4})$/i;
	if((flag & 0x1) && v4_pattern.test(ip_str)){
		return true;
	}
	if((flag & 0x2) && v6_pattern.test(ip_str)){
		return true;
	}
	return false;
}

/*
** page's  callback handler of permission
** @author:	Moa Chung
** @date:	2013-08-29
** @param:	perm - permission of current user
** 			wcb - permission 'w' callback function
** 			scb - permission 's' callback function
** 			rcb - permission 'r' callback function
*/
function page_permission(perm, wcb, scb, rcb)
{
	if(perm=='w')
	{
		wcb();
	}
	else if(perm=='s')
	{
		scb();
	}
	else if(perm=='r')
	{
		rcb();
	}
	else// impossable
	{
	}
}

/*
** show/hide between input[type=password] and input[type=text]
** tested under chrome,safari,opera,fx,IE8,IE11
** checked - password, unckecked - text
** @author:	Moa Chung
** @date:	2014-02-06
** @param:	that - the checkbox DOM
** 			ids - all id of password box (support multi by ,)
** 			revert - set checked type, true=password,false=text(default:false)
*/
function showHideBox(that, ids, revert)
{
	var aIds = ids.split(',');
	$(aIds).each(function(idx,id){
		if($(that).is(':checked') ^ revert){
			$('#'+id+'[type=text]').hide();
			$('#'+id+'[type=password]').show();
		}
		else{
			$('#'+id+'[type=text]').show();
			$('#'+id+'[type=password]').hide();
		}
	});
}

/*
**    Date:		2013-10-04
**    Author:	Silvia Chang
**    Reason:	schedule/inbound filter drop down box and link buttton dep with checkbox
**    Note:		EnableID:		checkbox name --> name + "_" + num
				selectIDArray:	selectbox id array
				button name:	href button --> "ln_btn_" + num
**/
function setEnable(EnableID)
{
	var func = function(){
		num = EnableID.split('_')[2];
		var enable = $('#'+EnableID).attr('checked');
		if(enable)
			$('#'+selectIDArray[num]+', #ln_btn_'+num).removeAttr('disabled');
		else
			$('#'+selectIDArray[num]+', #ln_btn_'+num).attr('disabled','disabled');
	};
	func();
	$('#'+EnableID).change(func);
}

/*
** keep page session
** @author:	Moa Chung
** @date:	2013-11-15
*/
function poke(){
	setInterval(function(){
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('get');
		obj.add_param_arg('IGD_',1000);
		obj.ajax_submit();
	},120000);
}

/*
** convert subnet mask format to prefix length format
** @author:	Moa Chung
** @date:	2013-12-13
** @param:	s_ip - string of ip format
** @note:	if parameter is not a mask, It will return null
*/
function mask2prefix(s_ip){
	var a_ip = s_ip.split('.');
	var i_ip = ip_num(a_ip);
	var b_ip_r = i_ip.toString(2).split('').reverse().join('');
	var i_ip_r = parseInt(b_ip_r,2);
	var prefix = 0;
	
	while(i_ip_r & 0x1){
			prefix++;
			i_ip_r = i_ip_r>>>1;
	}
	if(i_ip_r!=0)
		return null;
	return prefix;
}

/*
** check if a valid domain name or not
** @author:	Moa Chung
** @date:	2014-02-24
** @param:	string - domain name to check
*/
function isDomainName(string){
    string += "";
    return string.match(/^[\x2D-\x2E\x30-\x39\x41-\x5A\x5F\x61-\x7A]+$/) ? true : false;
}