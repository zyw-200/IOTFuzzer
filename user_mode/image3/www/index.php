<?
/* vi: set sw=4 ts=4: */
$MY_NAME="index";
$MSG_FILE="";
$TITLE=query("/sys/web/sysname");
$DISNAME = query("/sys/hostname");
$ELBOX_MODEL_NAME = query("/sys/elbox_model_name");
$NO_SESSION_TIMEOUT=1;
$NOT_FRAME =1;


/* ------------------------------ */
//AP_display
if($DISNAME== "DAP-2553")
{
	require("/www/dap2553_webdisplay.php");
	if($ELBOX_MODEL_NAME == "DAP-2553c")
	{
		require("/www/dap2553c_webdisplay.php");
	}
	if($ELBOX_MODEL_NAME == "DAP-2553b")
	{
		require("/www/dap2553b_webdisplay.php");
	}
}
else if($DISNAME== "DAP-3520")
{
	require("/www/dap3520_webdisplay.php");
}
else if($DISNAME== "DAP-2590")
{
	require("/www/dap2590_webdisplay.php");
}
else if($DISNAME== "DAP-2695")
{
	require("/www/dap2695_webdisplay.php");
}
else if($DISNAME== "DAP-3662")
{
	require("/www/dap3662_webdisplay.php");
}
else if($DISNAME== "DAP-3665")
{
	require("/www/dap3665_webdisplay.php");
}
else if($DISNAME== "DAP-2690" && $TITLE == "DAP-2690b" )
{
 require("/www/dap2690b_webdisplay.php");
	
}
else if($DISNAME== "DAP-2690" && $TITLE == "DAP-2690" )
{
	require("/www/dap2690_webdisplay.php");
}
else if($DISNAME== "NEC-Magnus")
{
 require("/www/NEC-Magnus_webdisplay.php");
	
}
else if($DISNAME== "DAP-1353")
{
	require("/www/dap1353b_webdisplay.php");
}
else if($DISNAME== "DAP-3690")
{
    require("/www/dap3690_webdisplay.php");
}
else if($DISNAME== "DAP-2360")
{
	require("/www/dap2360_webdisplay.php");
	if($ELBOX_MODEL_NAME == "DAP-2360b")
	{
		require("/www/dap2360b_webdisplay.php");
	}
}
else if($DISNAME== "DAP-3320")
{
	require("/www/dap3320_webdisplay.php");
}
else if($DISNAME== "DAP-2230")
{
        require("/www/dap2230_webdisplay.php");
}
else if($DISNAME== "DAP-2660")
{
	require("/www/dap2660_webdisplay.php");
}
else if($DISNAME== "DAP-2460")
{
	require("/www/dap2460_webdisplay.php");
}
else if($DISNAME== "DAP-3340")
{
	require("/www/dap3340_webdisplay.php");
}
else if($DISNAME== "DWL-2100AP")
{
	require("/www/dwl2100_webdisplay.php");
}
else if($DISNAME== "DAP-2310")
{
	require("/www/dap2310_webdisplay.php");
	if($ELBOX_MODEL_NAME == "DAP-2310b")
	{
		require("/www/dap2310b_webdisplay.php");
	}
}
else if($DISNAME== "DWP-2360")
{
	require("/www/dwp2360_webdisplay.php");
	if($ELBOX_MODEL_NAME == "DWP-2360b")
	{
		require("/www/dwp2360b_webdisplay.php");
	}
}
else if($DISNAME== "DWL-8500AP")
{
    require("/www/dap2690_webdisplay.php");
	require("/www/dap2590_webdisplay.php");
}
else if($DISNAME == "WAP-N08A")
{
	require("/www/WAP-N08A_webdisplay.php");
}
else if($DISNAME == "DAP-2330")
{
	require("/www/dap2330_webdisplay.php");
}
else if($TITLE == "WAP-N25A")
{
	require("/www/wapn25a_webdisplay.php");
}
else if($TITLE == "WAP-AC09A")
{
	require("/www/wapac09a_webdisplay.php");
}
else if($TITLE == "WAP-AC02A")
{
	require("/www/wapac02a_webdisplay.php");
}
/* ------------------------------ */
$group=fread("/var/proc/web/session:".$sid."/user/group");

if ($group==0 && query("/time/syncwith")==1)
{
	$NEXT_LINK=$G_HOME_PAGE;
	require("/www/comm/__msync.php");
}
else
{
	require("/www/main.php");
}
?>

