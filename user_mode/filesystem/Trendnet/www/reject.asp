<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
<title>TRENDNET | modelName</title>
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
$(function(){
	$('#product_desc').append(get_words('PRODUCT_DESC')).append('<br>').append(dev_info.model);
});
</script>
</style>
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
					<td class="CT"><script>show_words('fb_FbWbAc')</script></td>
				</tr>
				<tr>
					<td class="CELL">
						<p align="center" id="desc_1"><script>show_words('fb_p_1')</script></p>
						<p align="center" id="desc_2"><script>show_words('fb_p_2')</script></p>
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
</html>