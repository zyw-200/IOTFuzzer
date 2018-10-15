<?
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/trace.php";
$addwl_disable_str = "disabled";
foreach("/phyinf")
{
	$uid = query("uid");
	$s = cut($uid, 0, "-");
	if ($s == "WLAN")
	{
		/* Get the phyinf */
		$phy1	= XNODE_getpathbytarget("", "phyinf", "uid", $uid, 0);	if ($phy1 == "")	return;
		$active1 = query($phy1."/active");
		if ($active1==1)
		{
			$addwl_disable_str = "";
			break;
		}
	}
}
//TRACE_error("$addwl_disable_str = ".$addwl_disable_str);
?>

<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Wireless Settings");?></h1>
	<p><?echo i18n("The following Web-based wizards are designed to assist you in your wireless network setup and wireless device connection.");?></p>
	<p><?echo i18n("Before launching these wizards, please make sure you have followed all steps outlined in the Quick Installation Guide included in the package.");?></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Wireless Network Setup Wizard");?></h2>
	<p>
		<?echo i18n("This wizard is designed to assist you in your wireless network setup.")." ".
				i18n("It will guide you through step-by-step instructions on how to set up your wireless network and how to make it secure.");?>
	</p>
	<div class="centerline">
		<input type="button" id="wlan_wiz" value="<?echo i18n("Wireless Connection Setup Wizard");?>" onClick='self.location.href="wiz_wlan.php";' />
	</div>
	<p>
		<strong><?echo i18n("Note");?>:</strong>
		<?echo i18n("Some changes made using this Setup Wizard may require you to change some settings on your wireless client adapters so they can still connect to the D-Link Router.");?>
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Add Wireless Device WITH WPS (WI-FI PROTECTED SETUP) Wizard");?></h2>
	<p>
		<?echo i18n("This wizard is designed to assist you in connecting your wireless device to your wireless router.")." ".
				i18n("It will guide you through step-by-step instructions on how to get your wireless device connected.")." ".
				i18n("Click the button below to begin.");?>
	</p>
	<div class="centerline">
		<input type="button" id="wldevicesetup" value="<?echo i18n("Add Wireless Device with WPS");?>" onClick='self.location.href="wiz_wps.php";' <?=$addwl_disable_str?> />
	</div>
	<div class="emptyline"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Manual Wireless Network Setup");?></h2>
	<p>
		<?echo i18n("If your wireless network is already set up with Wi-Fi Protected Setup, manual configuration of the wireless network will destroy the existing wireless network.")." ".
				i18n("If you would like to configure the wireless settings of your new D-Link Systems Router manually, then click on the Manual Wireless Network Setup button below.");?>
	</p>
	<div class="centerline">
		<input type="button" id="inetsetup" value="<?echo i18n("Manual Wireless Connection Setup");?>" onClick='self.location.href="bsc_wlan.php";' usrmode="enable" />
	</div>
	<div class="emptyline"></div>
</div>
</form>
