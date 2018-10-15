<div class="orangebox">
	<h1><?echo i18n("Support Menu");?></h1>
	<ul>
		<li><a href="./support.php#Setup"><?echo i18n("Setup");?></a></li>
		<li><a href="./support.php#Advanced"><?echo i18n("Advanced");?></a></li>
		<li><a href="./support.php#Tools"><?echo i18n("Tools");?></a></li>
		<li><a href="./support.php#Status"><?echo i18n("Status");?></a></li>
	</ul>
</div>
<div class="blackbox">
	<h2><a name="Setup"><?echo i18n("Setup Help");?></a></h2>
	<ul>
		<li><a href="./spt_setup.php#Internet"><?echo i18n("Internet");?></a></li>
		<li><a href="./spt_setup.php#Wireless"><?echo i18n("Wireless Settings");?></a></li>
		<li><a href="./spt_setup.php#Network"><?echo i18n("Network Settings");?></a></li>
		<?if ($FEATURE_NOSMS =="0") echo '<li><a href="./spt_setup.php#SMS">'.i18n("Message Service").'</a></li>\n';?>
	</ul>
</div>
<div class="blackbox">
	<h2><a name="Advanced"><?echo i18n("Advanced Help");?></a></h2>
	<ul>
		<li><a href="./spt_adv.php#VSR"><?echo i18n("Virtual Server");?></a></li>
		<li><a href="./spt_adv.php#PFD"><?echo i18n("Port Forwarding");?></a></li>
		<?if ($FEATURE_NOAPP!="1")echo '<li><a href="./spt_adv.php#App">'.i18n("Application Rules").'</a></li>\n';?>
		<?if ($FEATURE_NOQOS!="1")echo '<li><a href="./spt_adv.php#QoS">'.i18n("QoS Engine").'</a></li>\n';?>
		<li><a href="./spt_adv.php#NetFilter"><?echo i18n("Network Filter");?></a></li>
		<li><a href="./spt_adv.php#WebFilter"><?echo i18n("Website Filter");?></a></li>
		<li><a href="./spt_adv.php#Firewall"><?echo i18n("Firewall Settings");?></a></li>
		<?if ($FEATURE_NORT!="1")echo '<li><a href="./spt_adv.php#Routing">'.i18n("Routing").'</a></li>\n';?>
		<li><a href="./spt_adv.php#Wireless"><?echo i18n("Advanced Wireless");?></a></li>
		<li><a href="./spt_adv.php#WPS"><?echo i18n("Wi-Fi Protected Setup");?></a></li>
		<li><a href="./spt_adv.php#Network"><?echo i18n("Advanced Network");?></a></li>
		<?if ($FEATURE_NOCALLMGR =="0") echo '<li><a href="./spt_adv.php#CallMgr">'.i18n("Call Setting").'</a></li>\n';?>
		<?if ($FEATURE_NOIPV6 == "0") echo '<li><a href="./spt_adv.php#IPv6">'.i18n("IPv6").'</a></li>\n';?>
	</ul>
</div>
<div class="blackbox">
	<h2><a name="Tools"><?echo i18n("Tools Help");?></a></h2>
	<ul>
		<li><a href="./spt_tools.php#Admin"><?echo i18n("Device Administration");?></a></li>
		<li><a href="./spt_tools.php#Time"><?echo i18n("Time");?></a></li>
		<li><a href="./spt_tools.php#Email"><?echo i18n("Email Settings");?></a></li>
		<li><a href="./spt_tools.php#System"><?echo i18n("System");?></a></li>
		<li><a href="./spt_tools.php#Firmware"><?echo i18n("Firmware");?></a></li>
		<li><a href="./spt_tools.php#DDNS"><?echo i18n("Dynamic DNS");?></a></li>
		<li><a href="./spt_tools.php#SystemCheck"><?echo i18n("System Check");?></a></li>
		<?if ($FEATURE_NOSCH!="1")echo '<li><a href="./spt_tools.php#Schedules">'.i18n("Schedules").'</a></li>\n';?>
	</ul>
</div>
<div class="blackbox">
	<h2><a name="Status"><?echo i18n("Status Help");?></a></h2>
	<ul>
		<li><a href="./spt_status.php#Device"><?echo i18n("Device Info");?></a></li>
		<li><a href="./spt_status.php#Logs"><?echo i18n("Logs");?></a></li>
		<li><a href="./spt_status.php#Statistics"><?echo i18n("Statistics");?></a></li>
		<li><a href="./spt_status.php#Sessions"><?echo i18n("Internet Sessions");?></a></li>
		<li><a href="./spt_status.php#Wireless"><?echo i18n("Wireless");?></a></li>
	</ul>
</div>
