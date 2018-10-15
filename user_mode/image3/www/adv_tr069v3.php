<?
$MY_NAME      = "adv_tr069v3";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_tr069v3";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
        require("/www/model/__admin_check.php");
        require("/www/__action_adv.php");
        $ACTION_POST = "";
        exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");

/* --------------------------------------------------------------------------- */
$cfg_mac = query("/runtime/wan/inf:1/mac");
$cfg_lan_type = query("/wan/rg/inf:1/mode");

if($cfg_lan_type == 1)
{
        anchor("/wan/rg/inf:1/static");
}
else
{
        anchor("/runtime/wan/inf:1");
}
$cpe_url_value="http:\/\/".query("ip").query("/tr069v3/connectionrequesturl");

anchor("/tr069v3");
$cfg_tr069_enable=query("enable");
$acs_url_value=query("acsurl");
$acs_user_name_value=query("acsusername");
$acs_passwd_value=queryEnc("acspassword");
$cfg_tr069_periodic_enable=query("periodicinformenable");
$tr069_periodic_interval_value=query("periodicinforminterval");


$cpe_user_name_value=query("connectionrequestusername");
$cpe_passwd_value=queryEnc("connectionrequestpassword");

$upgrade_managed_enable=query("upgradesmanaged");
$product_class_value=query("productclass");
$manufacturer_oui_value=query("manufactureroui");
?>
<script>
function checkURL(url){
    var RegExp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    if(RegExp.test(url)){
        return true;
    }else{
        return false;
    }
}
function init()
{
    var f = get_obj("frm");
    if("<?=$cfg_tr069_enable?>" == 0)
    {
        f.acs_url.disabled=true;
        f.acs_user_name.disabled=true;
        f.acs_passwd.disabled=true;
        f.tr069_periodic_enable.disabled=true;
        f.tr069_periodic_interval.disabled=true;
        //f.cpe_url.disabled=false;
        f.cpe_user_name.disabled=true;
        f.cpe_passwd.disabled=true;
    }
    else
    {
        f.acs_url.disabled=false;
        f.acs_user_name.disabled=false;
        f.acs_passwd.disabled=false;
        f.tr069_periodic_enable.disabled=false;
	if("<?=$cfg_tr069_periodic_enable?>"==1)
        	f.tr069_periodic_interval.disabled=false;
	else
        	f.tr069_periodic_interval.disabled=true;
		
        //f.cpe_url.disabled=false;
        f.cpe_user_name.disabled=false;
        f.cpe_passwd.disabled=false;
    }
}
function change_tr069_enable()
{
    var f = get_obj("frm");
    if(f.tr069_enable.checked == false)
    {
    	f.acs_url.disabled=true;
    	f.acs_user_name.disabled=true;
    	f.acs_passwd.disabled=true;
    	f.tr069_periodic_enable.disabled=true;
    	f.tr069_periodic_interval.disabled=true;
   // 	f.cpe_url.disabled=false;
    	f.cpe_user_name.disabled=true;
    	f.cpe_passwd.disabled=true;
    }
    else
    {
        f.acs_url.disabled=false;
        f.acs_user_name.disabled=false;
        f.acs_passwd.disabled=false;
    	f.tr069_periodic_enable.disabled=false;
	if("<?=$cfg_tr069_periodic_enable?>"==1)
        	f.tr069_periodic_interval.disabled=false;
	else
        	f.tr069_periodic_interval.disabled=true;
     //   f.cpe_url.disabled=false;
        f.cpe_user_name.disabled=false;
        f.cpe_passwd.disabled=false;
    }
}
function change_periodic_enable()
{
    var f = get_obj("frm");
    if(f.tr069_periodic_enable.checked == false)
    {
    	f.tr069_periodic_interval.disabled=true;
    }
    else
    {
    	f.tr069_periodic_interval.disabled=false;
    }
}
function check()
{
    var f = get_obj("frm");
    if(checkURL(f.acs_url.value) == false)
    {
	alert("<?=$a_acs_url?>");
        field_focus(f.acs_url, "**");
        return false;
    }
	/*
    if(checkURL(f.cpe_url.value) == false)
    {
	alert("<?=$a_cpe_url?>");
        field_focus(f.cpe_url, "**");
        return false;
    }*/
    if(is_blank(f.acs_user_name.value) == true)
    {
	alert("<?=$m_acs_username_bank?>");
	return false;
    }
    if(is_blank(f.acs_passwd.value) == true)
    {
	alert("<?=$m_acs_passwd_bank?>");
	return false;
    }
    if(is_blank(f.cpe_user_name.value) == true)
    {
	alert("<?=$m_cpe_username_bank?>");
	return false;
    }
    if(is_blank(f.cpe_passwd.value) == true)
    {
	alert("<?=$m_cpe_passwd_bank?>");
	return false;
    }
    if(is_digit(f.tr069_periodic_interval.value) == false)
    {
	alert("<?=$a_periodic_inform_interval?>");
	field_focus(f.a_periodic_inform_interval, "*");
	return false
    }
    return true;	
}
function submit()
{
    var f = get_obj("frm");
    var ff = get_obj("final_form");
    if(check())
    {
	if(f.tr069_enable.checked == true){ ff.f_tr069_enable.value = 1; }
	else { ff.f_tr069_enable.value = 0; }
        ff.f_acs_url.value = f.acs_url.value;
        ff.f_acs_user_name.value = f.acs_user_name.value;
        ff.f_acs_passwd.value = f.acs_passwd.value;
        if(f.tr069_periodic_enable.checked == true) { ff.f_tr069_periodic_enable.value = 1; }
	else { ff.f_tr069_periodic_enable.value = 0; }
	ff.f_tr069_periodic_interval.value = f.tr069_periodic_interval.value;
//	ff.f_cpe_url.value = f.cpe_url.value;
        ff.f_cpe_user_name.value = f.cpe_user_name.value;
        ff.f_cpe_passwd.value = f.cpe_passwd.value;
	ff.submit();
    }
}
function gen_serial()
{
    var str = "<?=$cfg_mac?>";
    var str2 = str.substring(0,2)+str.substring(3,5)+
	       str.substring(6,8)+str.substring(9,11)+
	       str.substring(12,14)+str.substring(15,17);
    document.write("<td height=25>");
    document.write(str2);
    document.write("</td>");
}
</script>
<body <?=$G_BODY_ATTR?> onload="init();">
<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php"  onsubmit="return check();">
<input type="hidden" name="ACTION_POST" 		value="<?=$MY_ACTION?>">
<input type="hidden" name="f_tr069_enable"              value="">
<input type="hidden" name="f_acs_url"              	value="">
<input type="hidden" name="f_acs_user_name"    		value="">
<input type="hidden" name="f_acs_passwd"  		value="">
<input type="hidden" name="f_tr069_periodic_enable"     value="">
<input type="hidden" name="f_tr069_periodic_interval"   value="">
<!--
<input type="hidden" name="f_cpe_url" 		        value="">
-->
<input type="hidden" name="f_cpe_user_name"             value="">
<input type="hidden" name="f_cpe_passwd"		value="">
</form>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
    <tr>
        <td valign="top">
            <table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
                <tr>
                    <td id="td_header" valign="middle"><?=$m_tr069_title?></td>
                </tr>
            </table>
<!-- ________________________________ Main Content Start ______________________________ -->
            <table id="table_set_main"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
		<tr>
            		<td width=35% id="td_left"><?=$tr069_enable?></td>
                        <td height=25>
                             <input type="checkbox" id="tr069_enable" name="tr069_enable" value="0" onClick="change_tr069_enable()" <? if($cfg_tr069_enable==1){echo "checked";}?>>
                        </td>
                </tr>
		    <tr>
			<td width=35% id="td_left"><?=$acs_url?></td>
			<td height=25><input type=text class=text name="acs_url" value="<?=$acs_url_value?>" size=60 maxlength=60></td>
		    </tr>
		    <tr>
			<td width=35% id="td_left"><?=$acs_user_name?></td>
			<td height=25><input type=text class=text name="acs_user_name" value="<?=$acs_user_name_value?>" size=18 maxlength=15></td>
		    </tr>
		    <tr>
			<td width=35% id="td_left"><?=$acs_passwd?></td>
			<td height=25><input type=password class=text name="acs_passwd" value="<?=$acs_passwd_value?>" size=20 maxlength=15></td>
		    </tr>
                    <tr>
                        <td width=35% id="td_left"><?=$tr069_periodic_enalbe?></td>
                        <td height=25>
                             <input type="checkbox" id="tr069_periodic_enable" name="tr069_periodic_enable" value="1"  onClick="change_periodic_enable()" <? if($cfg_tr069_periodic_enable==1){echo "checked";}?>>
                        </td>
                    </tr>
		    <tr>
			<td width=35% id="td_left"><?=$tr069_periodic_interval?></td>
			<td height=25><input type=text class=text name="tr069_periodic_interval" value="<?=$tr069_periodic_interval_value?>" size=8 maxlength=8></td>
		    </tr>
		    <tr>
                        <td width=35% id="td_left"><?=$cpe_url?></td>
			<td height=25><?=$cpe_url_value?></td>
			<!--
                        <td height=25><input type=text class=text name="cpe_url" value="<?=$cpe_url_value?>" size=60 maxlength=60></td>
                    	-->
		    </tr>
                    <tr>
                        <td width=35% id="td_left"><?=$cpe_user_name?></td>
                        <td height=25><input type=text class=text name="cpe_user_name" value="<?=$cpe_user_name_value?>" size=18 maxlength=15></td>
                    </tr>
                    <tr>
                        <td width=35% id="td_left"><?=$cpe_passwd?></td>
                        <td height=25><input type=password class=text name="cpe_passwd" value="<?=$cpe_passwd_value?>" size=20 maxlength=15></td>
                    </tr>
		    <tr>
			<td width=35%><?=$upgrade_managed?></td>
			<td height=25><?=$upgrade_managed_enable?></td>
		    </tr>
		    <tr>
			<td width=35%><?=$product_class?></td>
			<td height=25><?=$product_class_value?></td>
		    </tr>
		    <tr>
			<td width=35%><?=$manufacturer_oui?></td>
			<td height=25><?=$manufacturer_oui_value?></td>
		    </tr>
		    <tr>
			<td width=35%><?=$serial_number?></td>
			<script>gen_serial();</script>
		    </tr>
	            <tr>
                        <td colspan="2">
				<?=$G_APPLY_BUTTON?>
                        </td>
                    </tr>        
	    </table>
	 </td>
	</tr>
    </table>
</body>		

