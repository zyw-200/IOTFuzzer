<? 
include "/htdocs/phplib/trace.php";
function divide_by($dec,$base)
{
	$res = 0;
	$val = $dec; 
	while($val>0)
	{
		$val=$val-$base;
		if($val>0) 
		{ 
			$res=$res+1;	
			continue; 
		}
		else 
			break;
	}
	return $res;
}

function _dectohexstr($dec)
{
	if($dec < 0)	{ return "0"; }
	
	if($dec<=9) 		{ return $dec; }
	else if($dec==10)	{ return "A"; }
	else if($dec==11)	{ return "B"; }
	else if($dec==12)	{ return "C"; }
	else if($dec==13)	{ return "D"; }
	else if($dec==14)	{ return "E"; }
	else if($dec==15)	{ return "F"; }
	else if($dec > 15)	{ return ""; }
}


function dectohex($dec)
{
	$finalstr = "";
	//example if $dec is 1000
	//with dividing by 16, will become 62mod8
	//					   then become (3mod14)mod8
	//					   we then convert it to hex --> 3E8
	$temp="";
	$res = $dec;
	//error checking
	if($dec < 0)		{ TRACE_error("error in dectohex"); return "0" ; }
	if($dec < 16)		{ return _dectohexstr($dec); }
	
	//TRACE_error("dec=".$dec);
	
	while($res>=16)
	{
		$mod = $res%16;
		$res = divide_by($res, 16);
		if($mod==0)	{ $res = $res+1; }	//for adding to next 
						
		//TRACE_error("dectohex=".$res.",mod=".$mod);
		$temp = $mod.",".$temp;
		if($res<=16) { $temp=$res.",".$temp;}
	}
	//well, now we have $temp=8,14,3, but remember what we need is 3E8, so ...
	$n_hex = cut_count($temp, ",")-1;
	$i=0;
	while($i < $n_hex)
	{
		$hex_element = cut($temp, $i, ',');
		//TRACE_error("$hex_element="._dectohexstr($hex_element));
		$finalstr = $finalstr._dectohexstr($hex_element);
		$i++; 	
	}
	return $finalstr;
}

//$macaddr format is xx:xx:xx:xx:xx:xx
function increment_mac($macaddr, $inc)
{
	$i = 0;
	$final_mac = substr($macaddr, 0, 15);
	//$macaddr = "00:11:22:33:44:AA";
	$delimiter = ":";
	
	$v = cut($macaddr, 5, $delimiter);
	if (strlen($v)!=2 || isxdigit($v)!=1) { TRACE_error("MAC error"); }
	
	//special case, if last 2 bytes is 0xFF, we just use 0x00 and increment it.
	if ($v == "FF")	{ $v = "00"; }
	
	$intvalue = strtoul($v, 16);
	//TRACE_error("$intvalue=".$intvalue);
	$intvalue = $intvalue+$inc;
	$str = dectohex($intvalue);
	//TRACE_error("final str = ".$str);
	
	if(strlen($str)==1)	{ $str = "0".$str; }
	$final_mac = $final_mac.$str;
	//TRACE_error("final macaddr = ".$final_mac);
	return $final_mac;
}
?> 