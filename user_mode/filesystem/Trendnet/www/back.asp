<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Back</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/css_router.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="uk.js"></script>
<script type="text/javascript" src="public.js"></script>
<script type="text/javascript" src="public_msg.js"></script>
<script type="text/javascript" src="pandoraBox.js"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/xml.js"></script>
<script type="text/javascript" src="js/object.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/ccpObject.js"></script>
<script type="text/javascript">
	var def_title = document.title;
	var misc = new ccpObject();
	var dev_info = misc.get_router_info();
	document.title = def_title.replace("modelName", dev_info.model);

	var hw_version 	= dev_info.hw_ver;
	var version 	= dev_info.fw_ver;
	var model		= dev_info.model;
	var login_Info 	= dev_info.login_info;
	
	var redirectpage = "";
	var count = 0;
	var evt = getUrlEntry("event");
	//alert(evt);
	function gup( name ){  
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
		var regexS = "[\\?&]"+name+"=([^&#]*)";  
		var regex = new RegExp( regexS );  
		var results = regex.exec( window.location.href );  
		if( results == null )    
			return "";  
		else    
			return results[1];
	}
	
	function lan_action()
	{
		var old_ip = getUrlEntry("old_ip");
		var old_mask = getUrlEntry("old_mask");
		var new_ip = getUrlEntry("new_ip");
		var new_mask = getUrlEntry("new_mask");
		var pc_ip = getUrlEntry("pc_ip");
		
		if(old_ip == "(nill)")
			old_ip = "0.0.0.0";
		if(old_mask == "(nill)")
			old_mask = "0.0.0.0";
		if(new_ip == "(nill)")
			new_ip = "0.0.0.0";
		if(new_mask == "(nill)")
			new_mask = "0.0.0.0";
			
		var temp_old_ip_obj = new addr_obj(old_ip.split("."), "", true, false);
		var temp_old_mask_obj = new addr_obj(old_mask.split("."), "", true, false);
		var temp_new_ip_obj = new addr_obj(new_ip.split("."), "", true, false);
		var temp_new_mask_obj = new addr_obj(new_mask.split("."), "", true, false);
		var temp_pc_ip_obj = new addr_obj(pc_ip.split("."), "", true, false);

		//alert(temp_old_ip_obj.addr);
		var obj = new ccpObject();
		obj.set_param_url('get_set.ccp');
		obj.set_ccp_act('doEvent');
		obj.add_param_event(evt);
		obj.get_config_obj();
		if(check_domain(temp_old_ip_obj, temp_old_mask_obj, temp_pc_ip_obj) == false) // from wan
		{
			//get_by_id("newlink").innerHTML = '<a href="lan.htm" onclick="return jump_if();"><u>Link</u></a>';
			//window.location.href = "lan.htm";
			redirect_target = "lan.asp";
		}
		else	//from lan
		{
			/*
			if(new_ip == old_ip)
			{
				//alert("same domain and use old lan ip");
			}
			else
			{
				//alert("same domain and but lan ip changed");
				//get_by_id("newlink").innerHTML = '<a href="http://'+new_ip+'"><u>Link</u></a>';
				redirect_target = "http://" + new_ip;
			}
			*/
			redirect_target = "http://" + new_ip;
		}

		count = parseInt(config_val("count_down"));
		//alert("Lan IP has been changed, please wait for "+ count +" seconds.");
		//redirectpage = "lan.htm";
	}
	
	function devModeChange()
	{
		var new_ip = getUrlEntry("new_ip");
		redirect_target = "http://" + new_ip;
	}
	
	function onPageLoad(){
		if( evt == "CCP_SUB_LAN" )
			lan_action();
		else if(evt == 'devModeChange')
			devModeChange();
	}
	function back(){
		//if(login_Info!= "w")
			//window.location.href ="lan.htm";
		//else
			//window.location.href = redirectpage;
			//window.location.href = get_by_id("html_response_return_page").value;
		//get_by_id("newlink").style.display = "";
		window.location.href = redirect_target;
	}
	function do_count_down(){
		get_by_id("show_sec").innerHTML = count;
		
		if (count == 0) {	  
			//alert("count=0");
			if( evt == "CCP_SUB_LAN" )
			{
				get_by_id("button").disabled = false;
				//back();
			}
			else if(evt == 'devModeChange'){
				get_by_id("newlink").style.display = "";
				get_by_id("button").disabled = false;
			}
				
	        return false;
	    }

		if (count > 0) {
	        count--;
	        setTimeout('do_count_down()',1000);
	    }
	
	}
		
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(model);
});
</script>
</head>
<body>
<div class="wrapper"> 
<table border="0" width="950" cellpadding="0" cellspacing="0" align="center">
<!-- banner and model description-->
<tr>
	<td class="header_1">
		<table border="0" cellpadding="0" cellspacing="0" style="position:relative;width:920px;top:8px;" class="maintable">
		<tr>
			<td valign="top"><img src="/image/logo.png" /></td>
			<td id="product_desc" align="right" valign="middle" class="description" style="width:600px;line-height:1.5em;"></td>
		</tr>
		</table>
	</td>
</tr>
<!-- End of banner and model description-->
<tr>
	<td>
		<table border="0" cellpadding="0" cellspacing="0" style="position:relative;width:950px;top:10px;margin-left:5px;" class="maintable">
		<!-- upper frame -->
		<tr>
			<td style="width:12px;"><img src="/image/bg_topl_login.gif" width="12" height="12" /></td>
			<td style="width:927px;"><img src="/image/bg_top_login.gif" width="927" height="12" /></td>
			<td style="width:11px;"><img src="/image/bg_topr_login.gif" width="11" height="12" /></td>
		</tr>
		<!-- End of upper frame -->

		<!-- main content -->
		<tr>
			<td style="background-image:url('/image/bg_l_login.gif');background-repeat:repeat-y;vertical-align:top;" width="12">
			<td style="background-image:url('/image/bg_login.gif');background-repeat:repeat-x repeat-y;vertical-align:top;" width="927">

			<table align="center" class="tbl_main" style="width:500px; margin-left:auto; margin-right:auto; margin-top:30px">
			<tr>
			<td valign="top">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabBigTitle">
				<tr>
					<td class="CT">&nbsp;</td>
				</tr>
				<tr align="center">
					<td class="CELL">
						<p align="center"><script>show_words('rb_wait')</script></p>
						<p align="center"><script>document.write(get_words('sc_intro_sv'));</script></p>
						<p><script>document.write(get_words('rb_change'));</script></p>
					</td>
				</tr>
				<tr>
					<td align="center" class="CELL">
						<input name="button" id="button" type="button" class="ButtonSmall" value="" onClick="back()" disabled />
						<script>get_by_id("button").value = get_words('_continue');</script>
					</td>
				</tr>
				</table>
				<br/>
			</td>
			</tr>
			</table>
			<br/><br/>
			</td>
			<td style="background-image:url('/image/bg_r_login.gif');background-repeat:repeat-y;vertical-align:top;" width="11">
		</tr>
		<!-- End of main content -->

		<!-- lower frame -->
		<tr>
			<td style="width:12px;"><img src="/image/bg_butl_login.gif" width="12" height="12" /></td>
			<td style="width:927px;"><img src="/image/bg_but_login.gif" width="927" height="12" /></td>
			<td style="width:11px;"><img src="/image/bg_butr_login.gif" width="11" height="12" /></td>
		</tr>
		<!-- End of lower frame -->

		</table>
		<!-- footer -->
		<div class="footer">
			<table border="0" cellpadding="0" cellspacing="0" style="width:920px;" class="maintable">
			<tr>
				<td align="left" valign="top" class="txt_footer">
				<br><script>show_words("_copyright");</script></td>
				<td align="right" valign="top" class="txt_footer">
				<br><a href="http://www.trendnet.com/register" target="_blank"><img src="/image/icons_warranty_1.png" style="border:0px;vertical-align:middle;padding-right:10px;" border="0" /><script>show_words("_warranty");</script></a></td>
			</tr>
			</table>
		</div>
		<!-- end of footer -->
</div>
</body>
<script>
	var count = 15;
	onPageLoad();
	do_count_down();
</script>
</html>
