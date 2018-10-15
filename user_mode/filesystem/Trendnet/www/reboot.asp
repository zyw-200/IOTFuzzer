<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title>TRENDNET | modelName | Reboot</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/css_router.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="uk.js"></script>
<script type="text/javascript" src="public.js"></script>
<script type="text/javascript" src="public_msg.js"></script>
<script type="text/javascript" src="pandoraBox.js"></script>
<script type="text/javascript" src="menu_all.js"></script>
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

	var menu = new menuObject();
	menu.setSupportUSB(dev_info.KCode_USB);

	var count;
	var renewTime=80;
	var totalWaitTime;
	var redirectURL;
	var endClock;
	var percent=null;
	$(function(){
		//remove menu expand animate
		$('div.menuheader.expandable').removeAttr('onclick');
		//remove menu link
		$('.arrowlistmenu a').attr('disabled', true).removeAttr('href')
		count = 60;
		var temp_count = gup('count');
		if(temp_count != ""){
			count = parseInt(temp_count);
		}

		var msg = gup("msg");
		if(msg == "fwupgrade" || msg == 'reboot')
		{
			$('.ip_info').hide();
		}
		else
		{
			$('.ip_info').show();
		}
		totalWaitTime=count;
		reboot_page();
	});

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
	
	function back(){
		var login_who=dev_info.login_info;
		var newIP = gup("newIP");
		var redirectPage = (login_who!= "w"?"index.asp":"login.asp");
		if(newIP!="")
			window.location.assign(location.protocol+"//"+newIP+"/"+redirectPage);
		else
			window.location.href = redirectPage;
	}

function reboot_page()
{
	if (percent==null)
	{
		endClock = new Date().getTime()+(totalWaitTime*1000);
		//redirectURL = ".." + location.pathname;
		document.getElementById("mainform").style.display = "none";
//		document.getElementById("waitform").innerHTML = "<table bolder=\"0\"><tr><td colspan=\"2\"><font color=white>"+get_words('_processing_plz_wait')+"</font></td></tr><tr><td width=\"95%\"><table align=\"left\" bgcolor=\"#FFFFFF\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" style=\"border-style: solid; border-width: 1px\"><tr><td width=545 align=\"left\"><table id=\"progress\" bgcolor=\"blue\" height=\"25\"><tr><td></td></tr></table></td></tr></table></td><td width=\"95%\"><font color=white><span id=\"progressValue\">&nbsp;</span></font></td></tr></table>";
	}
	
	percent = (totalWaitTime*1000-(endClock-new Date().getTime()))/(totalWaitTime*10)
	if (percent >= 100) {
		is_page_work();
		return;
	}
	document.getElementById("progress").style.width = Math.round(percent) + "%";
	document.getElementById("progressValue").innerHTML = Math.round(percent) + "%";
	window.setTimeout("reboot_page()", renewTime);
}
	function is_page_work(){
		var newIP = gup("newIP");
		var ajax_param = {
			url				:	(newIP!=''?'http://'+newIP:'')+'/jsonp',
			type			:	'OPTIONS',
			success			:	function(json){
				if(json=='OK')
					back();
			},
			error			:	function(json){
				($('#dot').html().length>3?$('#dot').html(''):$('#dot').append('.'));
				setTimeout(function(){is_page_work();}, 1000);
			}
		};
		
		try{
			$.ajax(ajax_param);
		} catch(e) {//not support CORS
			back();
		}
	}
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
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
			<td><img src="/image/bg_topl.gif" width="270" height="7" /></td>
			<td><img src="/image/bg_topr_01.gif" width="680" height="7" /></td>
		</tr>
		<!-- End of upper frame -->

		<tr>
			<!-- left menu -->
			<td style="background-image:url('/image/bg_l.gif');background-repeat:repeat-y;vertical-align:top;" width="270">
				<div style="padding-left:6px;">
				<script>document.write(menu.build_structure(1,-1,0))</script>
				<p>&nbsp;</p>
				</div>
				<img src="/image/bg_l.gif" width="270" height="5" />
			</td>
			<!-- End of left menu -->

			<td style="background-image:url('/image/bg_r.gif');background-repeat:repeat-y;vertical-align:top;" width="680">
				<img src="/image/bg_topr_02.gif" width="680" height="5" />
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top" style="width:650px;padding-left:10px;">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td valign="top">
							<p>&nbsp;</p>
							<iframe class="rebootRedirect" name="rebootRedirect" id="rebootRedirect" frameborder="0" width="1" height="1" scrolling="no" src="" style="visibility: hidden;">redirect</iframe>
							<div id="waitform" style="width:100%;"></div>
							<table border="0">
							<tr><td colspan="2"><font color="red"><script>show_words('_rebooting_plz_wait');</script></font></td></tr>
							<tr>
								<td>
									<table align="left" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border-style: solid; border-width: 1px">
										<tr><td width="545" align="left">
										<table id="progress" bgcolor="blue" height="25"><tr><td></td></tr></table>
										</td></tr>
									</table>
								</td>
								<td><font color="white"><span id="progressValue">&nbsp;</span><span id="dot"></span></font></td>
							</tr>
							</table>
							<div id="waitPad" style="display:none;"></div>
							<div id="mainform">
								<div id="main_content">
									<table width="70%" border="0" align="center">
									<tr>
										<td height="100">
										<div id="box_header2">
											<div align="center">
												<p class="centered"><script>show_words('rb_wait')</script></p>
												<p class="ip_info"><script>show_words('rb_change')</script></p>
												<br/>
											</div>
										</div>
										</td>
									</tr>
									</table>
									<p>&nbsp;</p>
								</div>
								<!-- End of main content -->
								<br/>
							</div>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<!-- lower frame -->
		<tr>
			<td><img src="/image/bg_butl.gif" width="270" height="12" /></td>
			<td><img src="/image/bg_butr.gif" width="680" height="12" /></td>
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

	</td>
</tr>
</table><br/>
</div>
</body>
</html>
