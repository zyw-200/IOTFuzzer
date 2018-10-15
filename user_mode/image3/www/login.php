<?
/* vi: set sw=4 ts=4: */
$cfg_autorekey_g=query("/wlan/inf:1/autorekey/ssid/enable");
$cfg_autorekey_a=query("/wlan/inf:2/autorekey/ssid/enable");
if($cfg_autorekey_g==1 || $cfg_autorekey_a==1)
{
	$cfg_autorekey = 1;
}
$cfg_ntp_enable=query("/time/syncwith");/*disabled=0,enabled=2*/
if ($ACTION_POST!="")
{
	require("/www/auth/__login.php");
	if		($AUTH_RESULT=="401")	{$HEADER_URL="login_fail.php"; require("/www/comm/__header.php");}
	else if ($AUTH_RESULT=="full")	{$HEADER_URL="session_full.php"; require("/www/comm/__header.php");}
	else if ($AUTH_RESULT=="radiusclient")	{$HEADER_URL="check_radiusclient.php"; require("/www/comm/__header.php");}
	if($cfg_autorekey=="1" && $cfg_ntp_enable!="2")
	{
		set("/runtime/web/login",1);
		set("/runtime/web/date",$f_date);
		set("/runtime/web/time",$f_time);
	}
	$HEADER_URL="index.php";
	require("/www/comm/__header.php");
}

/* ------------------------------------------------------------------------ */
$MY_NAME="login";
$MY_MSG_FILE=$MY_NAME.".php";
$NO_NEED_AUTH="1";
$NO_SESSION_TIMEOUT="1";
$NOT_FRAME =1;
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
?>

<script>
//-------------------------------------------------christian chen add----------------------------------------------------------
function getRandomString(len)
{
    var chars = "abcdefghijklmnopqrstuvwxyz";
    var pwd = '';
    var str_len = chars.length;
    for (var i=0;i<len;i++){
        pwd += chars.charAt(Math.floor(Math.random()*str_len + 1));
    }
    return pwd;
}

function getCookie(cookie_name)
{       
    var allcookie = document.cookie;
    var cookie_pos = allcookie.indexOf(cookie_name);
    if (cookie_pos != -1){
        cookie_pos += cookie_name.length + 1;
        var cookie_end = allcookie.indexOf(";",cookie_pos);
        if (cookie_end == -1) cookie_end = allcookie.length;
        var cookie_value = allcookie.substring(cookie_pos,cookie_end);
        return cookie_value;
    }
    return null;
}       
        
function setCookie(cookie_name)
{
    document.cookie= cookie_name+"="+getRandomString(10);
}       

function checkCookie(cookie_name)
{
    var uid = getCookie(cookie_name);
    if (uid != null)
        return uid;
    else{
        setCookie(cookie_name);
    }
}
//-------------------------------------------------christian chen add----------------------------------------------------------
/* page init functoin */
function init()
{
	if (navigator.cookieEnabled)
		checkCookie('session_uid');
	var f=get_obj("frm");
	f.LOGIN_USER.focus();
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	if(f.LOGIN_USER.value=="")
	{
		alert("<?=$a_invalid_user_name?>");
		f.LOGIN_USER.focus();
		return false;
	}
	if("<?=$cfg_autorekey?>"=="1" && "<?=$cfg_ntp_enable?>"!="2")
	{
		var f_date = new Date();
		f.f_date.value=(f_date.getMonth()+1)+"/"+(f_date.getDate())+"/"+f_date.getFullYear();
		f.f_time.value=f_date.getHours()+":"+f_date.getMinutes()+":"+f_date.getSeconds();
	}
	return true;
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="login.php">
<input type="hidden" name="ACTION_POST" value="LOGIN">
<input type="hidden" name="f_date" value="">
<input type="hidden" name="f_time" value="">
<center>
<?require("/www/model/__logo.php");?>
<table <?=$G_LOG_MAIN_TABLE_ATTR?>>
<tr valign="middle" align="center">
	<td>
	<br>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table width="80%">
	<tr>
		<td id="box_header">
			<h1><?=$m_context_title?></h1>
			<?=$m_login_ap?>
			<br><br><center>
			<table>
			<tr>
				<td><?=$m_user_name?></td>
				<td><input type="text" class="text" name="LOGIN_USER" maxlength="64"></td>
			</tr>
			<tr>
				<td><?=$m_password?></td>
				<td><input type="password" class="text" name="LOGIN_PASSWD" maxlength="64"></td>
				<td><input type="submit" name="login" value="<?=$m_log_in?>" onclick="return check()"></td>
			</tr>
			</table>
			</center><br>
		</td>
	</tr>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
</center>
</form>
</body>
</html>
