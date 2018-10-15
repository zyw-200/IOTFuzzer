<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("IPV6 Internet Connection");?></h1>
	<p>
		<?echo i18n("There are two ways to set up your IPv6 Internet connection. You can use the Web-based IPv6 Internet Connection Setup Wizard, or you can manually configure the connection.");?>
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("IPV6 Internet Connection Setup Wizard");?></h2>
	<p>
		<?echo i18n("If you would like to utilize our easy to use Web-based Wizard to assist you in connecting your new D-Link Systems Router to the IPv6 Internet, click on the button below.");?>
	</p>
	<div class="centerline">
		<input type="button" id="inetwizard" value="<?echo i18n("IPv6 Internet Connection Setup Wizard");?>" onClick='self.location.href="wiz_wan_fresetv6.php";' />
	</div>
	<p>
		<strong><?echo i18n("Note");?>:</strong>
		<?echo i18n("Before launching the wizards, please make sure you have followed all steps outlined in the Quick Installation Guide included in the package.");?>
	</p>
	<div class="emptyline"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Manual IPV6 Internet Connection Option");?></h2>
	<p><?echo i18n("If you would like to configure the IPv6 Internet settings of your new D-Link Router manually, then click on the button below.");?></p>
	<div class="centerline">
		<input type="button" id="inetsetup" value="<?echo i18n("Manual IPv6 Internet Connection Setup");?>" onClick='self.location.href="bsc_ipv6.php";' usrmode="enable" />
	</div>
	<div class="emptyline"></div>
</div>
</form>
