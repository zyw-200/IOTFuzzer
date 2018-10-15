<strong><?echo i18n("Helpful Hints");?>...</strong>
<br/><br/>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("Check the <strong>Application Name</strong> drop-down menu for a list of pre-defined applications that you can select from. If you select one of the pre-defined applications, click the arrow button next to the drop-down menu to fill out the appropriate fields.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("You can select your computer from the list of DHCP clients in the <strong>Computer Name</strong> drop-down menu, or enter the IP address manually of the computer you would like to open the specified port to.");
?></p>
<p<?if ($FEATURE_NOSCH=="1")echo ' style="display:none"';?>>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n('Select a schedule for when the port forwarding will be enabled. If you do not see the schedule you need in the list of schedules, go to the <a href="/tools_sch.php">Tools -&gt; Schedules</a> screen and create a new schedule.');
?></p>
<!--<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n('Select a filter that restricts the Internet hosts that can access this virtual server to hosts that you trust. If you do not see the filter you need in the list of filters, go to the <a href="/adv_inbound_filter.php">Advanced -&gt; InboundFilter</a> screen and create a new filter.');
?></p>-->
