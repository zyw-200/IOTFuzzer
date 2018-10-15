<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="main";
$MY_MSG_FILE	=$MY_NAME.".php";
/* --------------------------------------------------------------------------- */

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_tree.php");
require("/www/comm/__js_defineMytree.php");
//require("/www/locale/en/frame_menu.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
$frame_name	=query("/runtime/web/next_page");
/* --------------------------------------------------------------------------- */
$cfg_sw_enable = query("/sys/swcontroller/enable");
$cfg_bsw_enable = query("/sys/b_swcontroller/enable");
?>

<script>

/* page init functoin */
function init()
{
<?
if(query("/runtime/web/login")=="1")
{
	echo "var str=\"index.xgi?\";\n";
	echo "str+=\"setPath=/runtime/time/&date=".query("/runtime/web/date")."&time=".query("/runtime/web/time")."&endSetPath=1\";\n";
	echo "self.location.href=str;\n";	
	set("/runtime/web/login",0);
}
?>
}

/* parameter checking */
function check()
{

}

var tool_list = new Array();
tool_list[0] = ["<?=$tool_admin?>", "tool_admin.php"];
tool_list[1] = ["<?=$tool_fw?>", "tool_fw.php"];
tool_list[2] = ["<?=$tool_config?>", "tool_config.php"];
if("<?=$cfg_sw_enable?>" != 1)
{
	tool_list[3] = ["<?=$tool_sntp?>", "tool_sntp.php"];
}

var config_list = new Array();
config_list[0] = ["<?=$m_menu_config_save?>", "__action.php?ACTION_POST=sys_setting"];
config_list[1] = ["<?=$m_menu_config_discard?>", "/sys_cfg_valid.xgi?exeshell=submit REBOOT"];
function genVerticalMenu(x, y, list, id)
{
	var str="";
	x = get_obj("MainTable").offsetLeft + x;

	str+="<div id='"+id+"' class='menu' style='display:none;position:absolute;top:"+y+"px;left:"+x+"px;'>";
	str+="<ul>";
	if(id != "config")
	{
	for(var i=0; i<list.length; i++)
	{
		str+="<li><a href='"+list[i][1]+"' target='ifrMain'>"+list[i][0]+"</a></li>";
	}
	}
	else
	{
		for(var i=0; i<list.length; i++)
		{
			str+="<li><a href='"+list[i][1]+"'>"+list[i][0]+"</a></li>";
		}
	}
	str+="</ul>";
	str+="</div>";
	document.write(str);
}


function genLogOutMenu(x, y, msg, id)
{
	var str="";
	x = get_obj("MainTable").offsetLeft + x;
	str+="<div id='"+id+"' class='menu'  style='display:none;position:absolute;top:"+y+"px;left:"+x+"px;'>";
	str+="<a href='logout.php' class='logout'>"+msg+"</a>";
	str+="</div>";
	document.write(str);
}

function HideFrame()
{
	showlist("tool", "");
	showlist("logout", "");
	showlist("config", "");
}
function Hide(a)
{
	if (a=="config")
	{
		showlist("tool", "");
		showlist("logout", "");
	}
	else if(a=="tool")
	{
		showlist("logout", "");
		showlist("config", "");
	}
	else if(a=="logout")
	{
		showlist("tool", "");
		showlist("config", "");	
}

}
function showlist(name, flag)
{
	if(flag == "yes")
		document.getElementById(name).style.display = "";
	else
		document.getElementById(name).style.display = "none";
}

function gen_banner_td(name, flag)
{
	var str="";
	var menu_name="";

	if(name == "home")
		menu_name = "<?=$m_menu_home?>";
	else if(name == "tool")
		menu_name = "<?=$m_menu_tool?>";
	else if(name == "sys")
		menu_name = "<?=$m_menu_sys?>";
	else if(name == "logout")
		menu_name = "<?=$m_menu_logout?>";
	else if(name == "help")
		menu_name = "<?=$m_menu_help?>";
	else if(name == "config")
		menu_name = "<?=$m_menu_config?>";	

	if(flag == "a_href")
	{
		str+="<td class='banner'>";
		if(name == "sys")
			str+="<a href='sys_setting.php' target='ifrMain'>";
		else if(name == "help")
			str+="<a href='help.php' target='_blank'>";
		else
			str+="<a href='home_sys.php' target='ifrMain'>";
	}
	else
	{
		str+="<td class='banner' onclick=\"showlist('"+name+"','yes')\" onMouseOver=\"Hide('"+name+"')\" >";
	}
/*	if(name == "logout")
	{
		str+="<img src='/pic/tool_bar_v.jpg' height='18' border='0' hspace='5'>";
	}*/
	str+="<img src='/pic/"+name+".gif' width='16' border='0' hspace='10'>";

	str+="<span id='banner_"+name+"' class='word'>"+menu_name+"</span>";
	if(flag == "" && name != "logout")
	{
		str+="<span class='img'><img src='/pic/triangle.gif' hspace='8'></span>";
	}
	if(name != "help")
	{
		str+="<img src='/pic/tool_bar_v.jpg' height='18' border='0' hspace='10'>";
	}
	if(flag == "a_href")
	{
		str+="</a>";
	}
	str+="</td>";

	document.write(str);

}
</script>
<body onload="init();" bgcolor="#CCDCE2" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0" style="overflow-x: auto; overflow-y: auto;" >
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
<input type="hidden" name="require_frame_name" value="">
<center>
<table id="MainTable" border="0" <?=$G_MAIN_TABLE_ATTR?>>
<tr>
	<td colspan="2" onMouseOver="HideFrame();">
	<?require("/www/model/__logo.php");?>
	</td>
</tr>
<tr>
	<td width="775" colspan="2" background="/pic/tool_bar.jpg" style="backgroundcolor: transparent;">
	<table border="0" width="100%" <?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
<script>
gen_banner_td("home", "a_href");
gen_banner_td("tool", "");
gen_banner_td("config", "");
gen_banner_td("sys", "a_href");
gen_banner_td("logout", "");
gen_banner_td("help", "a_href");
</script>
	</tr>
	</table>
	</td>
</tr>

<tr>
	<td width="210" valign="top" bgcolor="#CCDCE2">
		<table id="table_tree" frame="box" border="1" <?=$G_TABLE_ATTR_CELL_ZERO?> onMouseOver="HideFrame();">
		<tr>
			<td valign="top" id="alink_tree">
				<script language="javascript">
				initializeDocument()
				</script>
			</td>
		</tr>
		</table>
	</td>
	<td width="565" valign="top">
		<table id="table_page" frame="box" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> onMouseOver="">
		<tr>
			<td>
				<iframe id="ifrMain" name="ifrMain"  src="/<?=$frame_name?>.php" height="100%" frameborder="0" scrolling="no" width="555" onLoad=""></iframe>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</center>
<script>
genVerticalMenu(90, 78, tool_list, "tool");
genVerticalMenu(255, 78, config_list, "config");
genLogOutMenu(520, 78, "<?=$logout_msg?>", "logout");
function resize()
{
 	var x = get_obj("MainTable").offsetLeft;
 	get_obj("tool").style.left= (x+90+"px");
 	get_obj("logout").style.left= (x+520+"px");
 	get_obj("config").style.left= (x+255+"px");
}
window.onresize = resize;
</script>
</form>
</body>
<script>
var ifrMain = get_obj("ifrMain");
</script>
</html>
