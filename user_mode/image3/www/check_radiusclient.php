<?
$MY_NAME="check_radiusclient";
$MY_MSG_FILE=$MY_NAME.".php";
/* vi: set sw=4 ts=4: */
$NO_NEED_AUTH="1";
$NO_SESSION_TIMEOUT="1";
$NOT_FRAME =1;
require("/www/model/__html_head.php");
$SUBMIT_STR	=query("/runtime/web/sub_str");
$check_radiusclient_state = query("/wlan/inf:1/radiusclient_auth_state");
echo $G_TAG_SCRIPT_START."\n";
?>
var str="";
var dot_str="";
var do_wait_dot_count = 6;
function get_obj(name)
{
	if (document.getElementById)	return document.getElementById(name);//.style;
	if (document.all)				return document.all[name].style;
	if (document.layers)			return document.layers[name];
}
function dot()
{		
		do_wait_dot_count--;
		if( 1 < do_wait_dot_count )
		{
			dot_str = dot_str+".";
			get_obj('wait_dot').innerHTML = dot_str;
			setTimeout('dot()',1000);
		}
		else
		{
			dot_str = ".";
			get_obj('wait_dot').innerHTML = dot_str;	
			self.location.href=str;
		}
		
}
function exe_str(str_shellPath)
{
	var temp_str="";
	myShell = str_shellPath.split(";");
	for(i=0; i<myShell.length; i++)
	{
		temp_str+="&"+"exeshell="+myShell[i];
	}
	return temp_str;
}
<?
echo "function init(){\n"; 
if($check_flag =="")
{
	
	echo "str=\"check_radiusclient.xgi?check_flag=1\";\n";
	echo "str+=exe_str(\"submit RADIUSCLIENT\");\n";
	echo "self.location.href=str;}\n";
	
}
else if($check_flag ==1)	
{
	if($check_radiusclient_state==0)
	{
		echo "str=\"check_radiusclient.xgi?check_flag=2\";";
		echo "dot();}";
		
	
	}
	else if($check_radiusclient_state==1)
	{
		unlink("/var/proc/web/session:".$sid."/user/ac_auth");
		unlink("/var/proc/web/session:".$sid."/mac");
		unlink("/var/proc/web/session:".$sid."/ip");
		unlink("/var/proc/web/session:".$sid."/port");
		unlink("/var/proc/web/session:".$sid."/time");
		set("/wlan/inf:1/radiusclient_auth_state",0);
		echo "self.location.href=\"login_fail.php\";}\n";		
	}
	else
	{
		$login_user=query("/wlan/inf:1/radiusclient_username");
		$group=query("/sys/user:1/group");
		$prefix="/var/proc/web/session:".$sid."/user";
		fwrite($prefix."/name",$login_user);
		fwrite($prefix."/group", $group);
		fwrite($prefix."/ac_auth",  "1");
		set("/wlan/inf:1/radiusclient_auth_state",0);
		echo "self.location.href=\"index.php\";}\n";
	}
}
else if($check_flag ==2)	
{
	if($check_radiusclient_state==0)
	{
		echo "str=\"check_radiusclient.xgi?check_flag=3\";";
		echo "dot();}";
		
	}
	else if($check_radiusclient_state==1)
	{
		unlink("/var/proc/web/session:".$sid."/user/ac_auth");
		unlink("/var/proc/web/session:".$sid."/mac");
		unlink("/var/proc/web/session:".$sid."/ip");
		unlink("/var/proc/web/session:".$sid."/port");
		unlink("/var/proc/web/session:".$sid."/time");
		set("/wlan/inf:1/radiusclient_auth_state",0);
		echo "self.location.href=\"login_fail.php\";}\n";		
	}
	else
	{
		$login_user=query("/wlan/inf:1/radiusclient_username");
		$group=query("/sys/user:1/group");
		$prefix="/var/proc/web/session:".$sid."/user";
		fwrite($prefix."/name",$login_user);
		fwrite($prefix."/group", $group);
		fwrite($prefix."/ac_auth",  "1");
		set("/wlan/inf:1/radiusclient_auth_state",0);
		echo "self.location.href=\"index.php\";}\n";
	}
}
else if($check_flag ==3)	
{
	if($check_radiusclient_state==0)
	{
		unlink("/var/proc/web/session:".$sid."/user/ac_auth");
		unlink("/var/proc/web/session:".$sid."/mac");
		unlink("/var/proc/web/session:".$sid."/ip");
		unlink("/var/proc/web/session:".$sid."/port");
		unlink("/var/proc/web/session:".$sid."/time");
		set("/wlan/inf:1/radiusclient_auth_state",0);
		echo "self.location.href=\"login_fail.php?timeout=1\";}\n";		
	}
	else if($check_radiusclient_state==1)
	{
		unlink("/var/proc/web/session:".$sid."/user/ac_auth");
		unlink("/var/proc/web/session:".$sid."/mac");
		unlink("/var/proc/web/session:".$sid."/ip");
		unlink("/var/proc/web/session:".$sid."/port");
		unlink("/var/proc/web/session:".$sid."/time");
		set("/wlan/inf:1/radiusclient_auth_state",0);
		echo "self.location.href=\"login_fail.php\";}\n";		
	}
	else
	{
		$login_user=query("/wlan/inf:1/radiusclient_username");
		$group=query("/sys/user:1/group");
		$prefix="/var/proc/web/session:".$sid."/user";
		fwrite($prefix."/name",$login_user);
		fwrite($prefix."/group", $group);
		fwrite($prefix."/ac_auth",  "1");
		set("/wlan/inf:1/radiusclient_auth_state",0);
		echo "self.location.href=\"index.php\";}\n";
	}
}

echo $G_TAG_SCRIPT_END."\n";
?>
<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
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
			<h1><?=$m_context_title?></h1><br><br>
			<center><table width="30%"><tr><td align="right" width="40%" ><?=$m_pls_wait?></td>
					<td align="left"><span id='wait_dot' align="left"></span></td></tr></table>
			</center>
			<br>
		</td>
	</tr>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
<center>
</form>
</body>
</html>
