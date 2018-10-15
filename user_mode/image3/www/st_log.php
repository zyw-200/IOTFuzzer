<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_log";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_log";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";

echo "-->";
$row_num="18";  //the row number per page
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
?>
dataLists=[<?inclog("[\"%0\",\"%1\"],","/var/log/messages");?>["",""]];
var d_len=dataLists.length-1;
var row_num=parseInt("<?=$row_num?>", [10]);
var max=(d_len%row_num==0? d_len/row_num : parseInt(d_len/row_num, [10])+1);
var type_msg= new Array(2);
function genTablelength(str_tab,len)//victor add 2009-3-6
{
	var str = "";

	for(var i=0; i < str_tab.length; i++)
	{
		if(i!=0 && (i%len)==0)// change line
		{
			str+="<br \>";	
		}		
		str+=str_tab.charAt(i);
	}
	return(str);	
}
function showSysLog()
{
	var str=new String("");
	var f=document.getElementById("frm");
	var p=parseInt(f.curpage.value, [10]);
	if (max==0 || max==1)
	{
		f.Pp1.disabled=true;
		f.Np1.disabled=true;
	}
	else
	{
		if (p==1)
		{
			f.Pp1.disabled=true;
			f.Np1.disabled=false;
		}
		if (p==max)
		{
			f.Pp1.disabled=false;
			f.Np1.disabled=true;
		}
		if (p > 1 && p < max)
		{
			f.Pp1.disabled=false;
			f.Np1.disabled=false;
		}
	}

	if (document.layers) return true;
	{
		str+="<p><?=$m_page?> "+p+" <?=$m_of?> "+max+"</p>";
		str+="<table borderColor=#ffffff cellSpacing=1 cellPadding=2 width='98%' bgColor=#dfdfdf border=1>";
		str+="<tr>";
		str+="<td align=left width='100'><b><?=$m_time?></b></td>";
		str+="<td align=left><b><?=$m_type?></b></td><td align=left><b><?=$m_message?></b></td>";
		str+="</tr>";
		if(max!=0)
		{
		for (var i=((p-1)*row_num);i < p*row_num;i++)
		{
			if (i>=dataLists.length) break;
			for(var chh=0;chh<dataLists[i][1].length;chh++)
			{
				if(dataLists[i][1].charAt(chh) == "]")
					break;
			}
			var spot=chh+1;
			var priority_message_st = dataLists[i][1].substring(0,spot);
			var priority_message_end = dataLists[i][1].substring(spot);
			if(priority_message_end != null)
			{
				str+="<tr border=1 borderColor='#ffffff' bgcolor='#dfdfdf'>";
				str+="<td>"+dataLists[i][0]+"</td>";
				str+="<td>"+priority_message_st+"</td><td>"+genTablelength(priority_message_end,50)+"</td>";
				str+="</tr>";
			}
		}
		}
		str+="</table>";
	}

	if (document.all)           document.all("sLog").innerHTML=str;
	else if (document.getElementById)   document.getElementById("sLog").innerHTML=str;

	AdjustHeight();
}

function ToPage(p)
{
	if (document.layers)
	{
		alert("<?=$a_your_browser_is_not_support_this_function?><?=$a_upgrade_the_browser?>");
	}
	if (dataLists.length==0) return;
	var f=document.getElementById("frm");

	switch (p)
	{
		case "0":
			f.curpage.value=max;
		break;
		case "1":
			f.curpage.value=1;
		break;
		case "-1":
			f.curpage.value=(parseInt(f.curpage.value, [10])-1 <=0? 1:parseInt(f.curpage.value, [10])-1);
		break;
		case "+1":
			f.curpage.value=(parseInt(f.curpage.value, [10])+1 >=max? max:parseInt(f.curpage.value, [10])+1);
		break;
	}
	showSysLog();
}
function doClear()
{
<?	if ($AUTH_GROUP=="0")	
	{
		echo "var str=new String(\"".$MY_NAME.".xgi?\");\n";
		echo "str+=\"set/runtime/syslog/clear=1\";\n";
		echo "self.location.href=str;\n";
	}
?>
}
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	showSysLog();
	
	AdjustHeight();
}

/* parameter checking */
function check()
{
	var f = get_obj("frm");

	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="curpage" value="1">
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table id="table_set_main"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td colspan="2">
						<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td align="right">
									<div align="left">
										<input type="button" value="<?=$m_first_page?>" id="Fp1" name="Fp1" onclick="ToPage('1')">
										<input type="button" value="<?=$m_last_page?>" id="Lp1" name="Lp1" onclick="ToPage('0')">
										<input type="button" value="<?=$m_previous?>" id="Pp1" name="Pp1" onclick="ToPage('-1')">
										<input type="button" value="<?=$m_next?>" id="Np1" name="Np1" onclick="ToPage('+1')">
										<input type="button" value="<?=$m_clear?>" id="clear" name="clear" onclick="doClear()"<?if ($AUTH_GROUP!="0"){echo " disabled";}?>>
										</div>
									</td>
								</tr>
							<tr>
								<td>
									<div id="sLog"></div>
								</td>
							</tr>
						</table>					
					</td>	
				</tr>				
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
</html>			
