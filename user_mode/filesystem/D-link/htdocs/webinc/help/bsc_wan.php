<strong><?echo i18n("Helpful Hints");?>...</strong>
<br/><br/>
<p<?if (query("/runtime/device/router/mode")=="3G") echo ' style="display:none"';?>>&nbsp;&#149;&nbsp;&nbsp;<?
	echo "<strong>".i18n("Internet Connection").":</strong><br>";
	echo i18n("When configuring the router to access the Internet, be sure to choose the correct <strong>Internet Connection Type</strong> from the drop down menu. If you are unsure of which option to choose, please contact your <strong>Internet Service Provider (ISP)</strong>.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo "<strong>".i18n("Support").":</strong><br>";
	echo i18n("If you are having trouble accessing the Internet through the router, double check any settings you have entered on this page and verify them with your ISP if needed.");
?></p>

