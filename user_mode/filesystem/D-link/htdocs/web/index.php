<?
include "/htdocs/webinc/feature.php";
if ($FEATURE_NOEASYSETUP!="1")
{
	if (query("/runtime/device/devconfsize")=="0")
		dophp("load", "/htdocs/web/bsc_easy.php");
	else if (query("/device/features/easysetup/enable")=="1")
		dophp("load", "/htdocs/web/bsc_easy.php");
	else
		dophp("load", "/htdocs/web/bsc_internet.php");
}
else
{
	dophp("load", "/htdocs/web/bsc_internet.php");
}
?>
