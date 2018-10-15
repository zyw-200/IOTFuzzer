<?
/* vi: set sw=4 ts=4: */

/* ------------------------------------------------------------------------ */
$MY_NAME="web_redirect_out";
$MY_MSG_FILE=$MY_NAME.".php";
$NO_NEED_AUTH="1";
$NO_SESSION_TIMEOUT="1";
$NOT_FRAME =1;
require("/www/model/__html_head.php");
$daddr_ip =  query("/runtime/webredirect/daddr_ip");

$url_enable = query("/wlan/inf:1/webredirect/url/enable");
$url_addr = query("/wlan/inf:1/webredirect/url");
$runtime_ipv6 = query("/runtime/web/display/ipv6");
if($url_enable == 1)
{
	$m_message = $m_pls_wait;
}
else
{
	$m_message = $m_pls_cls_win;
}
//set("/runtime/web/next_page",$first_frame);
?>

<script>

var connect_url="";

function init()
{
	var f=get_obj("frm");
	//if("<?=$url_enable?>" == 1 && "<?=$runtime_ipv6?>" != 1)
	if("<?=$url_enable?>" == 1 )
	{
		setTimeout("turn_url()", 200);
	}
}

function turn_url()
{
	parent.location.href = "//<?=$url_addr?>";
}

function do_url()
{

	parent.location.href = "//" + connect_url;	
}

function hexstr2int(str)
{
	var i = 0;
	if (is_hexdigit(str)==true) i = parseInt(str, [16]);
	return i;
}

function is_hexdigit(str)
{
	if (str.length==0) return false;
	for (var i=0;i < str.length;i++)
	{
		if (str.charAt(i) <= '9' && str.charAt(i) >= '0') continue;
		if (str.charAt(i) <= 'F' && str.charAt(i) >= 'A') continue;
		if (str.charAt(i) <= 'f' && str.charAt(i) >= 'a') continue;
		return false;
	}
	return true;
}	

function print_url()
{
	var ip = new Array();
	var i , j = 0 ,k = 0;
	var str1 = "<?=$daddr_ip?>";
	var str2 = "";
	var str3 = "";
	for(i = str1.length; i >= 0 ; i --)
	{
		if(i-2 >= 0)
		{
			ip[j] = str1.substr(i-2,2);	
		}
		else
		{
			ip[j] = str1.substr(i-1,1);	
		}
		i=i-1;		
		j=j+1;
	}
	str2 += hexstr2int(ip[3])+"."+hexstr2int(ip[2])+"."+hexstr2int(ip[1])+"."+hexstr2int(ip[0]);
	connect_url = str2;	
	str3 += "Return to the outside URL."; 
	document.write(str3);	
}
function onclick_ok()
{
	self.location.href="http://www.dlink.com";	
	
}
</script>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php">
<input type="hidden" name="ACTION_POST" value="">
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
				<td><?=$m_message?></td>
			</tr>
			<tr>
				<td><center><?=$m_thank_u?></center></td>
			</tr>
			<!--<tr>
				<td>
					<center><input type='button' name='ok' value='  <?=$m_ok?>  ' onclick='onclick_ok()'></center>
				</td>
			</tr>-->
			<!--<tr>
				<td>
					<a href="javascript:do_url()">
						<script>print_url();</script>
					</a>
				</td>
			</tr>-->
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
