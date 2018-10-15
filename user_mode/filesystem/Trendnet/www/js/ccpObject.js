/*
** ccpObject
** 		- prevent the global variable 'gConfig' be modified by global function 'get_router_info' and 'get_config_obj', so make an object to handle it.
** @author:	moa
** @date:	2013-01-05
** @notice:	global function 'get_config_obj', 'get_router_info' and other "similar function" will be delete.
** @param:	ccp - specify which ccp (optional)
** 			act - specify ccp_act (optional)
*/
function ccpObject(ccp,act) {
	this.gConfig = null;
	this.errorMSG = null;
	
	/* private member below */
	var param = {'url':(ccp!=undefined?ccp:''),'arg':''};
	var param_arg = [];
	var ccp_act = (act!=undefined?act:'');
	var param_arg_event = [];
	var param_misc = [];
	var that = this;
	
	// Trendnet does not need to set GUI timeout
	var gui_timeout = false;
	
	/**
	 *	config_val() : get config value of the input tag name
	 *
	 *	Parameter(s) :
	 *		name : tag name
	 *
	 * Return : the node value of the input tag name.
	 *
	 **/
	this.config_val = function(name) {
		return get_node_value(this.gConfig, name);
	}
	
	this.config_attr = function(name) {
		return get_node_attribute(this.gConfig, name);
	}

	this.config_inst = function(name) {
		var attr = this.config_attr(name);
		var inst = attr.split(",");

		return inst;
	}

	/**
	 *	config_str() : Get config string of the input tag name.
	 *				   It will be shown on page directly.
	 *
	 *	Parameter(s) :
	 *		name : tag name
	 *
	 * Return : the node string of the input tag name.
	 *
	 **/
	this.config_str = function(name) {
		return document.write(get_node_value(this.gConfig, name));
	}

	this.config_str_multi = function(name) {
		var obj = $(this.gConfig).find(name);
		var size = obj.size();
		
		if (size == 0)
			return null;
			
		var i=0;
		var r = new Array(size);
		
		for (i=0; i<size; i++) {
			r[i] = obj.eq(i).text();
		}
		return r;
	}

	this.config_inst_multi = function(name) {
		name += " ";
		var obj = $(this.gConfig).find(name);
		var size = obj.size()
		
		if (size == 0)
			return null;

		var i=0;
		var r = new Array(size);

		for (i=0; i<size; i++) {
			//r[i] = $(this.gConfig).find(name).eq(i).attr("inst");
			var tmp = obj.eq(i).attr("inst");
			r[i] = tmp.split(",");
			//alert("test("+name +") ="+ $(this.gConfig).find(name).eq(i).attr("inst"));
		}

		return r;
	}

	/**
	 *	get_router_info
	 *
	 *	return:	a object has the following elements.
	 *
	 *	parameters:
	 *		hw_ver:	router's hardware version
	 *		sw_ver:	router's firmware version
	 *		model:	device model
	 *		login_info:	current user's grant
	 */
	this.get_router_info = function() {
		var param1 = {
			url: "misc.ccp",
			arg: "action=getmisc"
		};
		this.set_param_url(param1.url);
		this.get_config_obj(param1);
		
		var info = {
			'hw_ver':		this.config_val("hw_version"),
			'fw_ver':		this.config_val("version"),
			'ver_date':		this.config_val("version_date"),
			'domain':		this.config_val('RF_Domain'),//DIR not used
			'model':		this.config_val("model"),
			'login_info':	this.config_val("login_Info"),
			'cli_mac':		this.config_val("cli_mac"),
			'graph_auth':	this.config_val("graph_auth"),
			'lanIP':		this.config_val('lan_ip'),
			'lanMask':		this.config_val('lan_mask'),
			'es_conf': 		this.config_val('es_configured'),
			'wan_mac':		this.config_val('wan_mac'),
			'region':		this.config_val('region'),
			'v4v6_support':	this.config_val('v4v6_support'),
			'media_server':	this.config_val('media_server'),
			'wireless_band':this.config_val('wireless_band'),
			'gigabit':		this.config_val('true_gigabit'),
			'ac_mode':		this.config_val('ac_mode'),
			'wps_verify':	this.config_val('WPS_verify'),
			'dev_mode':		this.config_val('dev_mode'),
			'ch2_lst':		this.config_val('ch2list'),
			'ch5_lst':		this.config_val('ch5list'),
			'ch5_DFS_lst':	this.config_val('ch5DFSlist'),
			'Logo_FW':		this.config_val('DFS_CE_Logo'),
			'is_configured':this.config_val('is_configured'),
			'KCode_USB':	this.config_val('KCode_USB')||0
		};
		

		if (typeof(page_title) != "undefined") {
			document.title = LangMap.which_lang[page_title];
		}

		return info;
	}

	this.get_config_obj = function(para) {
		//check if is new functional format
		if (para == null || para.url == null)
		{
			if(param.url == '')//please setting url before
				return;
			
			para = this.get_param();
		}
		this.set_param_url(para.url);
		//*
		var hasLogin = getCookie('hasLogin');  
		if (hasLogin == null || hasLogin == '0') {
			if (needAuth(getDocName()) == 1) {
				document.cookie = 'hasLogin=0;';
				setTimeout(function() {
					location.replace('login.asp');
				}, 0);
			}
		}
		//*/
		var time=new Date().getTime();
		var thisObj = this;
		var ajax_param = {
			type: 	"POST",
			async:	false,
			url: 	para.url,
			data: 	para.arg+"&"+time+"="+time,
			dataType: "xml",
			success: function(data) {
				thisObj.gConfig = data;
			},
			error: function(xhr, ajaxOptions, thrownError){
				if (xhr.status == 200) {
					try {
						if (xhr.responseText.indexOf('<?xml') == -1) {
							setTimeout(function() {
								document.write(xhr.responseText);
							}, 0);
						}
					} catch (e) {
						thisObj.errorMSG = e;
					}
				} else {
					thisObj.gConfig = "error";
				}
			}
		};
		
		try {
			//setTimeout(function() {
			if(gui_timeout && need_timeout()) {
				this.removeGUITimeout();
				this.addGUITimeout();
			}
				$.ajax(ajax_param);
			//}, 0);
		} catch (e) {
			thisObj.errorMSG = e;
		}
	}
	
	/*
	** Date:	2013-10-29
	** Author:	Moa Chung
	** Reason:	check if page defined in not require timeout list;
	** Note: modify from isWizard();
	*/
	var need_timeout = function(){
		var page = location.pathname.substring(1);
		if (noTimeoutPage == null || page == null)
			return true;

		for (var i=0; i<noTimeoutPage.length; i++) {
			if (page == noTimeoutPage[i])
				return false;
		}
		return true;
	};
	
	this.get_config_obj_test = function(param) {
		if (param == null || param.url == null)
			return;
		var time=new Date().getTime();	
		var ajax_param = {
			type: 	"POST",
			async:	false,
			url: 	param.url,
			data: 	param.arg+"&"+time+"="+time,
			dataType: "text",
			success: function(data) {
				this.gConfig = data;
			},
			error: function(xhr, ajaxOptions, thrownError){
				if (xhr.status == 200) {
					try {
						if (xhr.responseText.indexOf('<?xml') == -1) {
							setTimeout(function() {
								document.write(xhr.responseText);
							}, 0);
						}
					} catch (e) {
						this.errorMSG = e;
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
			this.errorMSG = e;
		}
	}

	this.set_host_list = function( ref ) {
		var allHostName = this.config_str_multi("igdLanHostStatus_HostName_");
		var allHostIp = this.config_str_multi("igdLanHostStatus_HostIPv4Address_");
		var allHostMac = this.config_str_multi("igdLanHostStatus_HostMACAddress_");
		var allHostType = this.config_str_multi("igdLanHostStatus_HostAddressType_");
		
		if(allHostIp != null)
		{
			for (var i=0; i<allHostIp.length; i++)
			{
				if(ref == 'ip')
				{
					if(allHostIp[i] != '')
						document.write('<option value='+allHostIp[i]+'>'+allHostName[i]+'('+allHostIp[i]+')</option>');
				}
				else if(ref == 'mac')
				{
					if(allHostMac[i]!= '')
						document.write('<option value='+allHostMac[i]+'>'+allHostName[i]+'('+allHostMac[i]+')</option>');
				}
				else
					document.write('<option value='+allHostName[i]+'>'+allHostName[i]+'</option>');
			}
		}
	}

	this.get_host_list = function( ref ) {
		var allHostName = this.config_str_multi("igdLanHostStatus_HostName_");
		var allHostIp = this.config_str_multi("igdLanHostStatus_HostIPv4Address_");
		var allHostMac = this.config_str_multi("igdLanHostStatus_HostMACAddress_");
		var allHostType = this.config_str_multi("igdLanHostStatus_HostAddressType_");
		
		if(allHostIp != null)
		{
			for (var i=0; i<allHostIp.length; i++)
			{
				if(allHostName[i] == '')
				continue;

				if(allHostName[i] == 'Unknowable')
					continue;

				if(ref == 'ip')
				{
					if(allHostIp[i] != '')
						document.write('<option value='+allHostIp[i]+'>'+allHostName[i]+'('+allHostIp[i]+')</option>');
				}
				else if(ref == 'mac')
				{
					if(allHostMac[i]!= '')
						document.write('<option value='+allHostMac[i]+'>'+allHostName[i]+'('+allHostMac[i]+')</option>');
				}
				else
					document.write('<option value='+allHostName[i]+'>'+allHostName[i]+'</option>');
				
			}
		}
	}
	
	this.set_host_list_1 = function( ref ) {
		var allHostName = this.config_str_multi("igdLanHostStatus_HostName_");
		var allHostIp = this.config_str_multi("igdLanHostStatus_HostIPv4Address_");
		var allHostMac = this.config_str_multi("igdLanHostStatus_HostMACAddress_");
		var allHostType = this.config_str_multi("igdLanHostStatus_HostAddressType_");
		
		var list_str = "";
		
		if(allHostIp != null)
		{
			for (var i=0; i<allHostIp.length; i++)
			{
				if(allHostName[i] == '')
					continue;
				
				if(allHostName[i] == 'Unknowable')
					continue;
				
				if(ref == 'ip')
				{
					if(allHostIp[i] != '')
						list_str += '<option value='+allHostIp[i]+'>'+allHostName[i]+'('+allHostIp[i]+')</option>';
				}
				else if(ref == 'mac')
				{
					if(allHostMac[i]!= '')
						list_str += '<option value='+allHostMac[i]+'>'+allHostName[i]+'('+allHostMac[i]+')</option>';
				}
				else
					list_str += '<option value='+allHostName[i]+'>'+allHostName[i]+'</option>';
			}
		}
		
		return list_str;
	}

/*
	this.config_val_by_inst = function(obj_name, param_name, inst) {
		if ($(gConfig).find(name).size() == 0)
			return null;
		var size = $(gConfig).find(name).size()
		var i=0;

		for
	}
*/
	/*
	** Date:	2013-05-27
	** Author:	Moa Chung
	** Reason:	Extends the function, we can use more object-oriented to setup the ccp request.
	** Note: compare between old and new function
	
	// old functional format
	var unknownObj = new ccpObject();
	var paramHost={
		'url': 	'get_set.ccp',
		'arg': 	'ccp_act=get&num_inst=2'+
				'&oid_1=IGD_LANDevice_i_ConnectedAddress_i_&inst_1=1100'+
				'&oid_2=IGD_LANDevice_i_LANHostConfigManagement_&inst_2=1110'
	};
	unknownObj.get_config_obj(paramHost);
	
	// new functional format
	var unknownObj2 = new ccpObject();
	unknownObj2.set_param_url('get_set.ccp');
	unknownObj2.set_ccp_act('get');
	unknownObj2.add_param_arg('IGD_LANDevice_i_ConnectedAddress_i_', 1100);
	unknownObj2.add_param_arg('IGD_LANDevice_i_LANHostConfigManagement_', 1110);
	unknownObj2.get_config_obj();
	*/
	this.set_ccp_act = function(act) {
		ccp_act = act;
	};
	this.set_param_url = function(url) {
		param.url = url;
	};
	this.set_param_arg = function(arg) {
		param_misc.push(arg);
	};
	this.set_param_option = function(key,val) {
		param_misc.push(key+'='+tryEncodeURI(val));
	};
	this.set_param_next_page = function(nextPage) {
		this.set_param_option('nextPage',tryEncodeURI(nextPage));
	};
	this.set_param_query_page = function(queryPage) {
		this.set_param_option('queryPage',tryEncodeURI(queryPage));
	};
	this.add_param_event = function(event) {
		param_arg_event.push(event);
	};
	this.add_param_arg = function(oid, inst, val) {
		param_arg.push({'oid':oid,'inst':inst,'val':tryEncodeURI(val)});
	};
	
	/*
	** try EncodeURI to prevent GUI already encode once
	** @author:	Moa Chung
	** @date:	2013-11-15
	*/
	var tryEncodeURI = function(val){
		var val_enc;
		try{
			val_enc = encodeURIComponent(decodeURIComponent(val));
		}catch(e){
			val_enc = encodeURIComponent(val);
		}
		return val_enc;
	}
	
	/* private function below */
	var parse_param_arg_misc = function(){
		for(var i=0;i<param_misc.length;i++)
			param.arg += ('&'+param_misc[i]);
	}
	var parse_event_to_param_arg = function(){
		for(var i=0;i<param_arg_event.length;i++)
			param.arg += ((i==0?'&ccpSubEvent=':'&ccpSubEvent'+(i+1)+'=')+param_arg_event[i]);
	}
	var parse_param_arg_by_ccp_act = function(){
		if(ccp_act=='set')
		{
			for(var i=0;i<param_arg.length;i++)
			{
				param.arg += '&'+param_arg[i].oid+param_arg[i].inst+'='+param_arg[i].val;
			}
		}
		else
		{
			for(var i=0;i<param_arg.length;i++)
			{
				param.arg += '&oid_'+(i+1)+'='+param_arg[i].oid+'&inst_'+(i+1)+'='+param_arg[i].inst;
			}
			if(param_arg.length>0)
				param.arg += '&num_inst='+param_arg.length;
		}
	};
	
	var refine_param_arg = function(){
		if(param.arg.substr(0,1)=='&')
			param.arg = param.arg.substring(1);
	};
	
	this.addGUITimeout = function() {
		ccpObject.session_timer.push(setTimeout(function(){redirect_login();}, (180*1000)));
	};
	this.removeGUITimeout = function() {
		for(var i=0;i<ccpObject.session_timer.length;i++)
		{
			if(ccpObject.session_timer[i])clearTimeout(ccpObject.session_timer[i]);
		}
		ccpObject.session_timer.length = 0;//clear
	};
	
	this.get_param = function() {
		if((ccp_act!=undefined) && (ccp_act!=''))
			param.arg = 'ccp_act='+ccp_act;
		else
			param.arg = '';
		parse_param_arg_misc();
		parse_event_to_param_arg();
		parse_param_arg_by_ccp_act();
		refine_param_arg();
		return param;
	};
	
	/*
	** send an jQuery ajax without callback handler
	** .ajax_submit([isAsync], [handler(PlainObject data, String textStatus, jqXHR jqXHR)])
	** @author:	Moa Chung
	** @date:	2013-07-17
	** @param:	isAsync - asynchronized or not (optional, default=true)
	** 			cb - success callback function
	*/
	this.ajax_submit = function(isAsync, cb) {
		if(typeof(this.ajax_submit.arguments[0]) == 'function')
		{
			cb = this.ajax_submit.arguments[0];
			isAsync = undefined;
		}
		
		var param = this.get_param();
		var time=new Date().getTime();
		var thisObj = this;
		var ajax_param = {
			type: 	"POST",
			async:	(isAsync==undefined?true:isAsync),
			url: 	param.url,
			data: 	param.arg+"&"+time+"="+time,
			success: function(data){
				thisObj.gConfig = data;
				if(typeof(cb) == 'function')
					cb(data);
			}
		};
		$.ajax(ajax_param);
	};
	
	/*
	** new auto-generated member of ccpObject from xml
	** It works with any ccp which return an XML format
	** 
	** Format(use IGD_WANDevice_i_ for example):
	** 		its nickname is wanDev
	** 		one of elements is CurrentConnObjType
	** so It will generate a member of ccpObject call: "this.wanDev[0].CurrentConnObjType" or "this.wanDev['1.1.0.0'].CurrentConnObjType"
	** 
	** this.{nickname}[0].dynamic is present that the member is generated by function make_member() (get_set.ccp)
	** this.{nickname}[0].oid is present that the nickname's full OID (get_set.ccp)
	**
	*/
	this.make_member = function(){
		var getNodeValue = function(n){if($(n).children().length==0){return (n.firstChild?n.firstChild.nodeValue:'');}return null;};
		var getNodeName = function(n){return n.nodeName;};
		var getChildNodes = function(n){return $(n).children();};
		var getInstance = function(n){return (n.getAttribute('inst')?n.getAttribute('inst').replace(/,/g,'.'):'');};
		var initObj = function(o,k,v){if(o === undefined){o={};}if(o[k] === undefined){o[k] = v;}};
		var countTag = function(t){return (tag[t]!==undefined?(++tag[t]):(tag[t]=0));};
		var doEach = function(node,obj,isDatamodel){
			var each_cb = function(i){
				var nn = getNodeName(this);
				T = countTag(getNodeName(this));
				if((nv = getNodeValue(this))==null){
					I = getInstance(this);
					O = getNodeName(this);
					doEach(this,obj,isDatamodel);
				}
				else{
					if(isDatamodel){
						var vari = nn.split('_')[0];
						var key = nn.split('_')[1];
						initObj(obj,vari,{dynamic:true,oid:O});
						initObj(obj[vari],T,{});
						initObj(obj[vari][T],key,nv);
						initObj(obj[vari],I,{});
						initObj(obj[vari][I],key,nv);
					}
					else{
						if((O!=undefined) && (O!='root')){
							initObj(obj,O,{});
							initObj(obj[O],T,{});
							initObj(obj[O][T],nn,nv);
						}
						else{
							initObj(obj,T,{});
							initObj(obj[T],nn,nv);
						}
					}
				}
			};
			getChildNodes(node).each(each_cb);
		};
		var T,I,O,tag = {};
		var ccp_name = param.url.split('.ccp')[0];
		if((ccp_name=='get_set') || (ccp_name=='easy_setup')){
			doEach(this.gConfig,this,true);
			return this;
		}
		else{
			this[ccp_name] = {};
			doEach(this.gConfig,this[ccp_name],false);
			return this[ccp_name];
		}
	}
	
	/*
	** add by Silvester
	** to get data in array instead of object, easier to deal with iterator
	** can not execute before make_member()
	** param:
	** 		tagName: (string) nickname of datamodel obj
	**		isInst: (boolean) return with instance or integer
	*/

	this.get_member_array = function(tagName, isInst){
		isInst = isInst || false;
		var obj = that[tagName];
		var member_array = [];
		for(var key in obj){
			if(key != 'dynamic' && key != 'oid' && isInst === (key.indexOf('.')>0))
				member_array.push($.extend(obj[key], {'key':key}));
		}
		return member_array;
	}
}

ccpObject.session_timer = [];