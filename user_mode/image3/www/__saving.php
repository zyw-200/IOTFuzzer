<?
$MY_NAME="__saving";
$MY_MSG_FILE=$MY_NAME.".php";
/* vi: set sw=4 ts=4: */
require("/www/model/__html_head.php");
$SUBMIT_STR	=query("/runtime/web/sub_str");
$SUBMIT_ACL =query("/runtime/web/sub_acl");
$SUBMIT_CAPU=query("/runtime/web/sub_capu");
$SUBMIT_CAPU_V2=query("/runtime/web/sub_capu_v2");
$XGISET_STR	=query("/runtime/web/sub_xgi_str");
$change_ip = query("/runtime/web/check/change_ip");
if($change_ip == ""){$change_ip = 0;}
$check_wireless_setting = query("/runtime/wireless/setting/status");
$check_flag="";
if($check_wireless_setting == ""){$check_wireless_setting = 0;}

echo $G_TAG_SCRIPT_START."\n";
if($DOSAVE == "" || $WLAN_CHECK ==1 || $WLAN_CHECK ==2 || $IP_CHANGE_CHECK ==1 )
{
	if($DOSAVE == "" && $WLAN_CHECK !=1 && $WLAN_CHECK !=2 && $IP_CHANGE_CHECK !=1)
	{
	echo "function init(){"; 
	echo "var f_date = new Date();\n";
	echo "m_date=(f_date.getMonth()+1)+\"/\"+(f_date.getDate())+\"/\"+f_date.getFullYear();\n";
	echo "m_time=f_date.getHours()+\":\"+f_date.getMinutes()+\":\"+f_date.getSeconds();\n";
	if($f_rule_del!="")
	{
		echo "parent.parent.location.href=\"__saving.php?DOSAVE=1\";}";
	}
	else
	{
		echo "parent.location.href=\"__saving.php?DOSAVE=1\";}";
	}	
	echo $G_TAG_SCRIPT_END."\n";
	echo "<body onload=\"init();\" ".$G_BODY_ATTR.">\n";
	echo "</body></html>\n";
	exit;
	}
/*	else if($IP_CHANGE_CHECK ==1)
	{
		echo "function init() { \n";
		echo "}\n";
	}
	else
	{
		echo "function init() { \n";
		echo "var str_reload=\"__saving.xgi?WLAN_CHECK=2\";\n";
		echo "if(".$check_wireless_setting." ==1)\n"; // wlan.sh end
		echo "{setTimeout(\"do_wait_dot('2000')\",1000);}\n";
		echo "else if(".$check_wireless_setting." ==0 && ".$WLAN_CHECK."==1)\n";
		echo "{self.location.href=str_reload;}\n";
		echo "else{setTimeout(\"do_wait_dot('3000')\",1000);}\n";
		echo "}\n";	
		
		echo "var dot_str=\"..\";\n";
		echo "var do_wait_dot_count = 0;\n";
		
		echo "function do_wait_dot(time)\n";
		echo "{ \n";
		echo "var str=\"index.xgi?\";\n";
		echo "var str_wait=\"__saving.xgi?WLAN_CHECK=1\";\n";
		echo "do_wait_dot_count++;\n";
		echo "if(do_wait_dot_count < 4){\n";
		echo "dot_str += dot_str;get_obj('wait_dot').innerHTML = dot_str;\n";
		echo "setTimeout('do_wait_dot()',time);}\n";
		echo "else { if(".$check_wireless_setting." ==1){\n";
		echo "self.location.href=str_wait; }\n";
		echo "else{str+=exe_str(\"submit SLEEP 5;\"); self.location.href=str;}}\n";
		echo "}\n";	
	}*/
}
else
{
	if(query("/time/syncwith")!=2 && query("/runtime/time/sntp") != 1)
    {
        $XGISET_STR="setPath=/runtime/time/";
        $XGISET_STR=$XGISET_STR."&date=".$m_date;
        $XGISET_STR=$XGISET_STR."&time=".$m_time;
        $XGISET_STR=$XGISET_STR."&endSetPath=1";
    }
	set("/runtime/time/sntp", 0);
	echo "function init()\n";
	echo "{\n";
	echo "var str=\"\";\n";
	/*echo "var str_wlan=\"__saving.xgi?WLAN_CHECK=1\";\n";
	echo "var str_ip_change=\"__saving.xgi?IP_CHANGE_CHECK=1\";\n";
	echo "var submit_str = \"".$SUBMIT_STR."\";\n";*/
	
	if($SUBMIT_ACL != "")
	{
		echo "  str=\"index.xgi?random=\"+generate_random_str();+\"&\"\n";
		echo "  str+=exe_str(\"submit COMMIT;".$SUBMIT_ACL."\");\n";
	}
	else if($SUBMIT_CAPU != "")
	{
			echo "  str=\"index.xgi?random=\"+generate_random_str();+\"&\"\n";
		echo "  str+=exe_str(\"submit COMMIT;".$SUBMIT_CAPU."\");\n";
	}
	else if($SUBMIT_CAPU_V2 != "")
	{
		echo "  str=\"index.xgi?random=\"+generate_random_str();+\"&\"\n";
		echo "  str+=exe_str(\"submit COMMIT;".$SUBMIT_CAPU_V2."\");\n";
	}
	else if ($XGISET_STR!="")
	{
		echo "  str=\"index.xgi?random=\"+generate_random_str();\n";
		echo "	str+=\"&".$XGISET_STR."\";\n";
	}
	else
	{
		echo "  str=\"index.php?random=\"+generate_random_str();\n";
	}
	echo "self.location.href=str;}\n";
	/*if($SUBMIT_STR == "COMMIT")
	{
		echo "	str+=exe_str(\"submit COMMIT;\");\n";
	}
	else if ($SUBMIT_STR!="")
	{
		echo "	str+=exe_str(\"submit COMMIT;".$SUBMIT_STR."\");\n";
		echo "	str_wlan+=exe_str(\"submit COMMIT;".$SUBMIT_STR.";submit SLEEP 3;\");\n";
		echo "	str_ip_change+=exe_str(\"submit COMMIT;".$SUBMIT_STR."\");\n";
	}
	if ($XGISET_AFTER_COMMIT_STR!="")
	{
		echo "	str+=\"&".$XGISET_AFTER_COMMIT_STR."\";\n";
	}

	echo " if(submit_str.indexOf(\"WLAN\") == -1){\n";
	echo " if(".$change_ip." == 1){\n";
	echo "self.location.href=str_ip_change;}\n";
	echo "else {self.location.href=str;}}\n";	
	echo "else{self.location.href=str_wlan;}\n";
	echo "}\n";*/
}
echo $G_TAG_SCRIPT_END."\n";
?>

<body onload="init();" <?=$G_BODY_ATTR?>>
<!--<form name="frm" id="frm">
<center>
<?require("/www/model/__logo.php");?>
<table <?=$G_MAIN_TABLE_ATTR?>>
<tr valign="middle" align="center">
	<td>
	<br>-->
	<!-- ________________________________ Main Content Start ______________________________ -->
	<!--<table width="80%" border ="0">
	<tr>
		<td id="box_header">
			<h1><?=$m_saving_title?></h1><br><br><br>
			<center><?=$m_saving_dsc?></center>
			<br><br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
<?
if($change_ip == 1 || $IP_CHANGE_CHECK == 1)		
{	
	echo $m_saving_dsc_change_ip;
}
else
{
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";	
	echo $m_saving_dsc_wait;
	if($WLAN_CHECK == 1 || $WLAN_CHECK == 2)
	{
		echo "<span id='wait_dot'></span> \n";
	}
}
?>
			<br><br><br><br>
		</td>
	</tr>
	</table>-->
	<!-- ________________________________  Main Content End _______________________________ -->
<!--	<br>
	</td>
</tr>
</table>
</center>
</form>-->
<?
//set("/runtime/web/check/change_ip",0);
?>
</body>
</html><? exit; ?>
