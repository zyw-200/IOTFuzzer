<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("EMail Settings");?></h1>
	<p><?echo i18n("The Email feature can be used to send the system log files, router alert messages.");?></p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onClick="BODY.OnSubmit();" />
	    <input type="button" value="<?echo i18n("Don't Save Settings");?>" onClick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Email Settings");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("From Email Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="from_addr" type="text" size="20" maxlength="60"/></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("To Email Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="to_addr" type="text" size="20" maxlength="60"/></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Email Subject");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="email_subject" type="text" size="20" maxlength="60"/></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("SMTP Server Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="smtp_server_addr" type="text" size="20" maxlength="60"/></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Account Name");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="account_name" type="text" size="20" maxlength="60"/></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Password");?></span>
		<span class="delimiter">:</span>
		<span class="value"><input id="passwd" type="password" size="20" maxlength="60"/></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Verify Password");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="verify_passwd" type="password" size="20" maxlength="60"/>
			<input id="sendmail" type="button" value="<?echo i18n("Send Mail Now");?>" onClick="PAGE.OnClickSendMail();"/>
		</span>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
