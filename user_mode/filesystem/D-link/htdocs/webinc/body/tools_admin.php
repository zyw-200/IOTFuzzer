<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Administrator Settings");?></h1>
	<p><?
		if ($USR_ACCOUNTS=="1")
			echo i18n("The 'admin' account can access the management interface.")." ".
			i18n("The admin has read/write access and can change password.");
		else
			echo i18n("The 'admin' and 'user' accounts can access the management interface.")." ".
			i18n("The admin has read/write access and can change passwords, while the user has read-only access.");
	?></p>
	<p><?echo i18n("By default there is no password configured.")." ".
		i18n("It is highly recommended that you create a password to keep your router secure.");?></p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
	    <input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Admin Password");?></h2>
	<p class="strong"><?echo i18n("Please enter the same password into both boxes, for confirmation.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Password");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="admin_p1" type="password" size="20" maxlength="15" /></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Verify Password");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="admin_p2" type="password" size="20" maxlength="15" /></span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox" <?if ($USR_ACCOUNTS=="1") echo 'style="display:none;"';?>>
	<h2><?echo i18n("User Password");?></h2>
	<p class="strong"><?echo i18n("Please enter the same password into both boxes, for confirmation.");?></p>
	<div class="textinput">
		<span class="name"><?echo i18n("Password");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="usr_p1" type="password" size="20" maxlength="15" /></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Verify Password");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="usr_p2" type="password" size="20" maxlength="15" /></span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Administration");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Graphical Authentication");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="en_captcha" type="checkbox" /></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Enable Remote Management");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="en_remote" type="checkbox" onClick="PAGE.OnClickEnRemote();" /></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Remote Admin Port");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="remote_port" type="text" size="5" maxlength="5" /></span>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
<form>
