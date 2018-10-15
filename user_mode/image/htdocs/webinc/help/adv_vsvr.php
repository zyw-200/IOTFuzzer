<strong><?echo i18n("Helpful Hints");?>...</strong>
<br/><br/>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("Check the <strong>Application Name</strong> drop down menu for a list of predefined server types. If you select one of the predefined server types, click the arrow button next to the drop down menu to fill out the corresponding field.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n("You can select a computer from the list of DHCP clients in the <strong>Computer Name</strong> drop down menu, or you can manually enter the IP address of the computer at which you would like to open the specified port.");
?></p>
<p<?if ($FEATURE_NOSCH=="1")echo ' style="display:none"';?>>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n('Select a schedule for when the virtual server will be enabled. If you do not see the schedule you need in the list of schedules, go to the <a href="/tools_sch.php"> Tools -&gt; Schedules</a> screen and create a new schedule.');
?></p>
<!--<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo i18n('Select a filter that restricts the Internet hosts that can access this virtual server to hosts that you trust. If you do not see the filter you need in the list of filters, go to the <a href="/adv_inbound_filter.php">Advanced -&gt; InboundFilter</a> screen and create a new filter.');
?></p>-->
