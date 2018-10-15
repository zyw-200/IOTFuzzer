<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/trace.php";

/* I save the channel list into /runtime/freqrule/channellist/a & runtime/freqrule/channellist/g */
$path_a = "/runtime/freqrule/channellist/a";
$path_g = "/runtime/freqrule/channellist/g";

$c = query("/runtime/devdata/countrycode");
if ($c == "")
{
	TRACE_error("phplib/getchlist.php - GETCHLIST() ERROR: no Country Code!!! Please check if you board is initialized.");
	return;
}
if (isdigit($c)==1)
{
	TRACE_error("phplib/getchlist.php - GETCHLIST() ERROR: Country Code (".$c.") is not in ISO Name!! Please use ISO name insteads of Country Number.");
	return;
}

/* never set the channel list, so do it.*/
if (query($path_a)=="" || query($path_g)=="")
{
	/* map the region by country ISO name */
	if		($c == "AU")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,149,153,157,161,165";	}
	else if	($c == "CA")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11";	
								$list_a = "36,40,44,48,149,153,157,161,165";	}
	else if	($c == "CN")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "149,153,157,161,165";	}
	else if	($c == "SG")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,52,56,60,64,149,153,157,161,165";	}
	else if	($c == "LA")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11";	
								$list_a = "149,153,157,161";	}
	else if	($c == "IL")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,52,56,60,64";	}
	else if	($c == "KR")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,52,56,60,64,100,104,108,112,116,120,124,149,153,157,161";	}
	else if	($c == "JP")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,52,56,60,64,100,104,108,112,116,120,124,128,132,136,140";	}
	else if	($c == "EG")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13,14";	
								//$list_a = "36,40,44,48,52,56,60,64,149,153,157,161,165";	}
								$list_a = "36,40,44,48,52,56,60,64";	}
	else if	($c == "BR")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11";	
								$list_a = "36,40,44,48,149,153,157,161,165";	}
	else if	($c == "RU")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,52,56,60,64,132,136,140";	}
	else if	($c == "US")	{ 	$list_g = "1,2,3,4,5,6,7,8,9,10,11";	
								$list_a = "36,40,44,48,149,153,157,161,165";	}
	else if ($c == "EU") 	{	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,52,56,60,64,100,104,108,112,116,120,124,128,132,136,140";	}
	/* EU == GB */
	else if ($c == "GB") 	{	$list_g = "1,2,3,4,5,6,7,8,9,10,11,12,13";	
								$list_a = "36,40,44,48,52,56,60,64,100,104,108,112,116,120,124,128,132,136,140";	}								
	else if ($c == "TW") 	{	$list_g = "1,2,3,4,5,6,7,8,9,10,11";	
								$list_a = "52,56,60,64,149,153,157,161,165";	}
	else /* match no ISO name! return ERROR message. */
	{
		return "phplib/getchlist.php - GETCHLIST() ERROR: countrycode (".$c.") doesn't match any list in GETCHLIST(). Please check it.";
	}

	set($path_a, $list_a);
	set($path_g, $list_g);
}
?>
