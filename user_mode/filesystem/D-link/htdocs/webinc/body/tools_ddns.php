<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Dynamic DNS");?></h1>
	<p>
		<?echo i18n("The Dynamic DNS feature allows you to host a server (Web, FTP, Game Server, etc...) using a domain name that you have purchased (www.whateveryournameis.com) with your dynamically assigned IP address. Most broadband Internet Service Providers assign dynamic (changing) IP addresses. Using a DDNS service provider, your friends can enter your host name to connect to your game server no matter what your IP address is.");?>
	</p>
	<p>
		<a href="http://www.dlinkddns.com/"><?echo i18n("Sign up for D-Link's Free DDNS service at www.DLinkDDNS.com.");?></a>
	</p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Dynamic DNS Settings");?></h2>
	<div class="centerline" align="center">
		<div class="textinput">
			<span class="name"><?echo i18n("Enable DDNS");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="checkbox" id="en_ddns"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Server Address");?></span>
			<span class="delimiter">:</span>
			<span class="value">
				<select id="server">
					<option value="DLINK">dlinkddns.com(Free)</option>
					<option value="DYNDNS.C">DynDns.org(Custom)</option>
					<option value="DYNDNS">DynDns.org(Free)</option>
					<option value="DYNDNS.S">DynDns.org(Static)</option>
				</select>
			</span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Host Name");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="host" maxlength="60" size="40"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("User Account");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="user" maxlength="16" size="40"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Password");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="password" id="passwd" maxlength="16" size="40"></span>
		</div>
		<div class="textinput">
			<span class="name">&nbsp;</span>
			<span class="delimiter">&nbsp;</span>
			<span class="value"><input type="button" value="<?echo i18n("DDNS Account Testing");?>" onclick="PAGE.OnClickUpdateNow();"></span>
		</div>
		<div class="gap"></div>
		<div>
			<p id="report"></p>
		</div>
	</div>
	<div class="gap"></div>
</div>
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
