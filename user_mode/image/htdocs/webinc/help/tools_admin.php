<strong><?echo i18n("Helpful Hints");?>...</strong>
<br/><br/>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	if ($USR_ACCOUNTS=="1")
		echo i18n("For security reasons, it is recommended that you change the password for the Admin account.");
	else
		echo i18n("For security reasons, it is recommended that you change the password for the Admin and User accounts.");
	echo " ";
	echo i18n("Be sure to write down the new password to avoid having to reset the router in case they are forgotten.");
?></p>
<!--
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("When enabling Remote Management, you can specify the IP address of the computer on the Internet that you want to have access to your router, or leave it blank to allow access to any computer on the Internet.");
?></p>
-->
