function genSelect(id, val, text, func)
{
	var str = "";

	if(id.length == 0)
	{
		document.write("ID Error!!!");
		return;
	}

	if(val.length != text.length)
	{
		document.write("Length Error!!!");
		return;
	}
	str += "<select id=\"" + id + "\" name=\"" + id + "\" onChange=\"" + func + "\">";
	for(var i=0; i<val.length; i++)
	{
		str += "<option value=\"" + val[i] + "\">" + text[i] + "</option>";
	}
	str += "</select>";
	document.write(str);
}

function genRaidoEnableDisable(id, func)
{
	var str = "";
	str += "<input type=\"radio\" id=\"disable_" + id + "\" name=\"" + id + "\" value=\"0\" onClick=\"" + func + "\">";
	str += "<?=$m_disable?>";
	str += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
	str += "<input type=\"radio\" id=\"enable_" + id + "\" name=\"" + id + "\" value=\"1\" onClick=\"" + func + "\">";
	str += "<?=$m_enable?>";
	document.write(str);
}

function genChannel(flag, id)
{
	var f = get_obj("frm");
	var str = "";
	str += "<select id=\"" + id + "\" name=\"" + id + "\" onChange=\"on_change_channel()\">";
	if(flag == "g")
	{
<?
		$ch_g="";
		for("/runtime/stats/wlan/inf:1/channeltable/channel")
		{
			$ch_g = query("/runtime/stats/wlan/inf:1/channeltable/channel:".$@."/channel");
				echo "str+=\"<option value='".$ch_g."'>".$ch_g."</option>\" \n";		
		}
?>		
	}
	else 
	{
		if(id == "ch_a_wds")
		{
<?	
			$ch_a_wds="";
			for("/runtime/stats/wlan/inf:2/channeltable/wdschannel")
			{
				$ch_a_wds = query("/runtime/stats/wlan/inf:2/channeltable/wdschannel:".$@."/channel");
				echo "str+=\"<option value='".$ch_a_wds."'>".$ch_a_wds."</option>\" \n";		
			}
?>			
		}
		else
		{
<?			
			$ch_a="";
			for("/runtime/stats/wlan/inf:2/channeltable/channel")
			{
				$ch_a = query("/runtime/stats/wlan/inf:2/channeltable/channel:".$@."/channel");
				echo "str+=\"<option value='".$ch_a."'>".$ch_a."</option>\" \n";		
			}
?>			
		}				
			
	}
	str += "</select>";
	document.write(str);
}

function genOdChannel(flag, id)
{
	var f = get_obj("frm");
	var str = "";
	str += "<select id=\"" + id + "\" name=\"" + id + "\" onChange=\"on_change_channel()\">";
	if(flag == "g")
	{
<?
		$ch_g="";
		for("/runtime/stats/wlan/inf:1/channeltable/channel")
		{
			$ch_g = query("/runtime/stats/wlan/inf:1/channeltable/channel:".$@."/channel");
				echo "str+=\"<option value='".$ch_g."'>".$ch_g."</option>\" \n";		
		}
?>		
	}
	else 
	{
		if(id == "ch_a_wds")
		{
<?	
			$ch_a_wds="";
			for("/runtime/stats/wlan/inf:2/channeltable/od_wdschannel")
			{
				$ch_a_wds = query("/runtime/stats/wlan/inf:2/channeltable/od_wdschannel:".$@."/channel");
				echo "str+=\"<option value='".$ch_a_wds."'>".$ch_a_wds."</option>\" \n";		
			}
?>			
		}
		else
		{
<?			
			$ch_a="";
			for("/runtime/stats/wlan/inf:2/channeltable/od_channel")
			{
				$ch_a = query("/runtime/stats/wlan/inf:2/channeltable/od_channel:".$@."/channel");
				echo "str+=\"<option value='".$ch_a."'>".$ch_a."</option>\" \n";		
			}
?>			
		}				
			
	}
	str += "</select>";
	document.write(str);
}


function gentextWDSMac(num)
{
	var str = "";
	var i , j = 1;
	str+="<table width=\"100%\" border=\"0\">";
	str+="<tr>";
	for(i = 1 ; i <= num ; i++)
	{
		if(j == 5)
		{
			j = 1;
			str+= "</tr><tr>";
		}
		str+="<td width=\"25%\">";
		str+= i+".";	
		str+="<input name=\"wds_mac"+i+"\" id=\"wds_mac"+i+"\" type=\"text\" size=\"17\" maxlength=\"17\" value='' \>&nbsp;</td>";
		j++;
	}	
str+="</tr></table>";
	document.write(str);
}
function genTableName(tab,len)
{
	var str = "";
	var ssid = "";
	ssid = tab;
	for(var i=0; i < ssid.length; i++)
	{
		if(i!=0 && (i%parseInt(len,[10]))==0)// change line
		{
			str+="<br \>";	
		}		
		if(ssid.charAt(i)==" ")
		{
    		str+="&nbsp;";
		}
		else if(ssid.charAt(i)=="<")
		{
		str+="&lt;";
		}
		else if(ssid.charCodeAt(i) == 34)
		{
			str+="&quot;";
		}
		else
		{
		str+=ssid.charAt(i);
	}
}
	document.write(str);	
}
function genTableSSID(tab,id)
{
	var str = "";
	var ssid = "";
	
	if(id == 0)
	{
		ssid = tab;
	}
	else
	{
		ssid = get_obj(tab+id).value;	
	}
	for(var i=0; i < ssid.length; i++)
	{
		if(i!=0 && (i%11)==0)// change line
		{
			str+="<br \>";	
		}		
		if(ssid.charAt(i)==" ")
		{
    		str+="&nbsp;";
		}
		else if(ssid.charAt(i)=="<")
		{
		str+="&lt;";
		}
		else
		{
		str+=ssid.charAt(i);
	}
}
	document.write(str);	
}
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
function genCheckBox(id,func)
{
	var str = "";
	str+="<input name=\"" + id + "\" id=\"" + id + "\" type=\"checkbox\" onclick=\""+func+"\" value=\"1\">";
	
	document.write(str);
}


