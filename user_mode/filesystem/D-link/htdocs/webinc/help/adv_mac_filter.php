<strong><?echo i18n("Helpful Hints");?>...</strong>
<br/><br/>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("Create a list of MAC addresses and choose whether to allow or deny them access to your network.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("Computers that have obtained an IP address from the router's DHCP server will be in the DHCP Client List. Select a device from the drop down menu and click the arrow to add that device's MAC to the list.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("Use the check box on the left to either enable or disable a particular entry.");
?></p>
<p<?if ($FEATURE_NOSCH=="1")echo ' style="display:none"';?>>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("Use the <strong>Always</strong> drop down menu if you have previously defined a schedule in the router. If not, click on the <strong>New Schedule</strong> button to add one.");
?></p>
