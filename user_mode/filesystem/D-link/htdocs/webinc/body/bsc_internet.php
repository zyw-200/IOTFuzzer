<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Internet Connection");?></h1>
	<p>
		<?echo i18n("If you are configuring the device for the first time, we recommend that you click on the Internet Connection Setup Wizard, and follow the instructions on the screen.");?>
		<?echo i18n("If you wish to modify or configure the device settings manually, click the Manual Internet Connection Setup.");?>
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Internet Connection Setup Wizard");?></h2>
	<p>
		<?echo i18n("If you would like to utility our easy to use Web-based Wizard to assist you in connecting your new D-Link Systems Router to the Internet, click on the button below.");?>
	</p>
	<div class="centerline">
		<input type="button" id="inetwizard" value="<?echo i18n("Internet Connection Setup Wizard");?>" onClick='self.location.href="wiz_wan.php";' />
	</div>
	<p>
		<strong><?echo i18n("Note");?>:</strong>
		<?echo i18n("Before launching the wizard, please make sure you have followed all steps outlined in the Quick Installation Guide included in the package.");?>
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Manual Internet Connection Option");?></h2>
	<p><?echo i18n("If you would like to configure the Internet settings of your new D-Link Router manually, then click on the button below.");?></p>
	<div class="centerline">
		<input type="button" id="inetsetup" value="<?echo i18n("Manual Internet Connection Setup");?>" onClick='self.location.href="bsc_wan.php";' usrmode="enable" />
	</div>
	<div class="emptyline"></div>
</div>
</form>
