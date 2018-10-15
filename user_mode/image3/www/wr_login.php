<?
/* vi: set sw=4 ts=4: */
if ($ACTION_POST!="")
{
	require("/www/auth/__wr_login.php");
	if		($WR_AUTH=="401")	{$HEADER_URL="wr_login_fail.php"; require("/www/comm/__header.php");}
	//else if ($WR_AUTH=="full")	{$HEADER_URL="wr_session_full.php"; require("/www/comm/__header.php");}

	$HEADER_URL="web_redirect_out.php";
	require("/www/comm/__header.php");
}

/* ------------------------------------------------------------------------ */
$MY_NAME="wr_login";
$MY_MSG_FILE=$MY_NAME.".php";
$NO_NEED_AUTH="1";
$NO_SESSION_TIMEOUT="1";
$NOT_FRAME =1;
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
?>

<script>
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	f.WR_LOGIN_USER.focus();
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	if(f.WR_LOGIN_USER.value=="")
	{
		alert("<?=$a_invalid_user_name?>");
		f.WR_LOGIN_USER.focus();
		return false;
	}
	return true;
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="wr_login.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="LOGIN">
<center>
<?require("/www/model/__logo.php");?>
<table <?=$G_TABLE_ATTR_CELL_ZERO?>>
<tr valign="middle" align="center">
	<td>
	<br>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table width="400">
	<tr>
		<td id="box_header">
			<h1><?=$m_context_title?></h1>
			<?=$m_login_ap?>
			<br><br><center>
			<table>
			<tr>
				<td><?=$m_username?></td>
				<td><input type="text" class="text" name="WR_LOGIN_USER" maxlength="64"></td>
			</tr>
			<tr>
				<td><?=$m_password?></td>
				<td><input type="password" class="text" name="WR_LOGIN_PASSWD" maxlength="64"></td>
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
