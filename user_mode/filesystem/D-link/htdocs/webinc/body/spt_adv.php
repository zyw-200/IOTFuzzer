<div class="orangebox">
	<h1><?echo i18n("Advanced Help");?></h1>
	<ul>
		<li><a href="#VSR"><?echo i18n("Virtual Server");?></a></li>
		<li><a href="#PFD"><?echo i18n("Port Forwarding");?></a></li>
		<?if ($FEATURE_NOAPP!="1") echo '<li><a href="#App">'.i18n("Application Rules").'</a></li>';?>
		<?if ($FEATURE_NOQOS!="1") echo '<li><a href="#QoS">'.i18n("QoS Engine").'</a></li>';?>
		<li><a href="#NetFilter"><?echo i18n("Network Filter");?></a></li>
		<li><a href="#WebFilter"><?echo i18n("Website Filter");?></a></li>
		<li><a href="#Firewall"><?echo i18n("Firewall Settings");?></a></li>
		<?if ($FEATURE_NORT!="1") echo '<li><a href="#Routing">'.i18n("Routing").'</a></li>';?>
		<li><a href="#Wireless"><?echo i18n("Advanced Wireless");?></a></li>
		<li><a href="#WPS"><?echo i18n("Wi-Fi Protected Setup");?></a></li>
		<li><a href="#Network"><?echo i18n("Advanced Network");?></a></li>
		<li style="display:none;"><a href="#CallMgr"><?echo i18n("Call Setting");?></a></li>
		<? 	if ($FEATURE_NOIPV6 == "0") echo '<li><a href="#IPv6Firewall">'.i18n("IPv6 Firewall").'</a></li>\n'; ?>
		<? 	if ($FEATURE_NOIPV6 == "0") echo '<li><a href="#Routing">'.i18n("IPv6 Routing").'</a></li>\n'; ?>	
	</ul>
</div>
<div class="blackbox">
	<h2><a name="VSR"><?echo i18n("Virtual Server");?></a></h2>
	<p><?
		echo i18n('The Virtual Server option gives Internet users access to services on your LAN. This feature is useful for hosting online services such as FTP, Web, or game servers. For each Virtual Server, you define a public port on your router for redirection to an internal LAN IP Address and LAN port.');
	?></p>
	<div class="help_example">
	<dl>
		<dt><strong><?echo i18n("Example");?>: </strong></dt>
		<dd><?echo i18n("You are hosting a Web Server on a PC that has LAN IP Address of 192.168.0.50 and your ISP is blocking Port 80.");?>
			<ol>
				<li><?echo i18n("Name the Virtual Server (for example: <code>Web Server</code>)");?></li>
				<li><?echo i18n("Enter the IP Address of the machine on your LAN (for example: <code>192.168.0.50</code>)");?></li>
				<li><?echo i18n("Enter the Private Port as [80]");?></li>
				<li><?echo i18n("Enter the Public Port as [8888]");?></li>
				<li><?echo i18n("Select the Protocol (for example <code>TCP</code>).");?></li>
				<? if ($FEATURE_NOSCH!="1")echo '<li>'.i18n("Ensure the schedule is set to <code>Always</code>").'</li>\n';?>
				<li><?echo i18n("Repeat these steps for each Virtual Server Rule you wish to add. After the list is complete, click <span class='button_ref'>Save Settings</span> at the top of the page.");?></li>
			</ol>
			<?echo i18n("With this Virtual Server entry, all Internet traffic on Port 8888 will be redirected to your internal web server on port 80 at IP Address 192.168.0.50.");?>
		</dd>
	</dl>
	</div>
	<dl>
		<dt><strong><?echo i18n("Virtual Server Parameters");?></strong></dt>
		<dd>
			<dl>
				<dt><?echo i18n("Name");?></dt>
				<dd><?
					echo i18n("Assign a meaningful name to the virtual server, for example <code>Web Server</code>. Several well-known types of virtual server are available from the 'Application Name' drop-down list. Selecting one of these entries fills some of the remaining parameters with standard values for that type of server.");
				?></dd>
				<dt><?echo i18n("IP Address");?></dt>
				<dd><?
					echo i18n("The IP address of the system on your internal network that will provide the virtual service, for example <code>192.168.0.50</code>. You can select a computer from the list of DHCP clients in the 'Computer Name' drop-down menu, or you can manually enter the IP address of the server computer.");
				?></dd>
				<dt><?echo i18n("Traffic Type");?></dt>
				<dd><?
					echo i18n("Select the protocol used by the service. The common choices -- UDP, TCP, and All -- can be selected from the drop-down menu.");
				?></dd>
				<dt><?echo i18n("Private Port");?></dt>
				<dd><?echo i18n("The port that will be used on your internal network.");?></dd>
				<dt><?echo i18n("Public Port");?></dt>
				<dd><?echo i18n("The port that will be accessed from the Internet.");?></dd>
				<?
				if ($FEATURE_NOSCH!="1")
				{
					echo '<dt>'.i18n("Schedule").'</dt>\n';
					echo '<dd>'.i18n("Select a schedule for when the service will be enabled.").'\n';
					echo i18n("If you do not see the schedule you need in the list of schedules, go to the <a href='tools_sch.php'> Tools --> Schedules</a> screen and create a new schedule.").'</dd>\n';
				}
				?>
			</dl>
		</dd>
		<dt><strong><?=$VSVR_MAX_COUNT?> -- <?echo i18n("Virtual Servers List");?></strong></dt>
		<dd><?echo i18n("Use the checkboxes at the left to activate or deactivate completed Virtual Server entries.");?></dd>
	</dl>
</div>
<div class="blackbox">
	<h2><a name="PFD"><?echo i18n("Port Forwarding");?></a></h2>
	<p><?
		echo i18n('The Port Forwarding option gives Internet users access to services on your LAN. This feature is useful for hosting online services such as FTP, Web or game servers. For each entry, you define a public port on your router for redirection to an internal LAN IP Address and LAN port.');
	?></p>
	<dl>
		<dt><strong><?echo i18n("Port Forwarding Parameters");?></strong></dt>
		<dd>
			<dl>
				<dt><strong><?echo i18n("Name");?></strong></dt>
				<dd><?
					echo i18n('Assign a meaningful name to the port forwarding, for example Web Server. Several well-known types of port forwarding are available from the "Application Name" drop-down list. Selecting one of these entries fills some of the remaining parameters with standard values for that type of server.');
				?></dd>
				<dt><strong><?echo i18n('IP Address');?></strong></dt>
				<dd><?
					echo i18n('The IP address of the system on your internal network that will provide the virtual service, for example 192.168.0.50. You can select a computer from the list of DHCP clients in the "Computer Name" drop-down menu, or you can manually enter the IP address of the server computer.');
				?></dd>
				<dt><strong><?echo i18n('Application Name');?></strong></dt>
				<dd><?
					echo i18n('A list of pre-defined popular applications that users can choose from for faster configuration.');
				?></dd>
				<dt><strong><?echo i18n('Computer Name');?></strong></dt>
				<dd><?
					echo i18n('A list of DHCP clients.');
				?></dd>
				<dt><strong><?echo i18n('Traffic Type');?></strong></dt>
				<dd><?
					echo i18n('Select the protocol used by the service. The common choices -- UDP, TCP and All -- can be selected from the drop-down menu.');
				?></dd>
				<dt><strong><?echo i18n('Private Port');?></strong></dt>
				<dd><?
					echo i18n('The port that will be used on your internal network.');
				?></dd>
				<dt><strong><?echo i18n('Public Port');?></strong></dt>
				<dd><?
					echo i18n('The port that will be accessed from the Internet.');
				?></dd>
			</dl>
		</dd>
	</dl>
</div>
<div class="blackbox"<? if ($FEATURE_NOAPP=="1") echo ' style="display:none;"';?>>
	<h2><a name="App"><?echo i18n("Application Rules");?></a></h2>
	<p><?
		echo i18n('Some applications require multiple connections, such as Internet gaming, video conferencing, Internet telephony and others. These applications have difficulties working through NAT (Network Address Translation). If you need to run applications that require multiple connections, specify the port normally associated with an application in the "Trigger Port" field, select the protocol type as TCP (Transmission Control Protocol) or UDP (User Datagram Protocol), then enter the public ports associated with the trigger port in the Firewall Port field to open them for inbound traffic. There are already defined well-known applications in the Application Name drop down menu.');
	?></p>
	<dl>
		<dt><strong><?echo i18n("Name");?></strong></dt>
		<dd><?
			echo i18n('This is the name referencing the application.');
		?></dd>
		<dt><strong><?echo i18n("Trigger Port");?></strong></dt>
		<dd><?
			echo i18n('This is the port used to trigger the application. It can be either a single port or a range of ports.');
		?></dd>
		<dt><strong><?echo i18n("Traffic Type");?></strong></dt>
		<dd><?
			echo i18n('This is the protocol used to trigger the application.');
		?></dd>
		<dt><strong><?echo i18n("Firewall Port");?></strong></dt>
		<dd><?
			echo i18n('This is the port number on the WAN side that will be used to access the application. You may define a single port or a range of ports. You can use a comma to add multiple ports or port ranges.');
		?></dd>
		<dt><strong><?echo i18n("Traffic Type");?></strong></dt>
		<dd><?
			echo i18n('This is the protocol used for the application. ');
		?></dd>
		<?
		if ($FEATURE_NOSCH!="1")
		{
			echo '<dt>'.i18n("Schedule").'</dt>\n';
			echo '<dd>'.i18n("Select a schedule for when the service will be enabled.").'\n';
			echo i18n("If you do not see the schedule you need in the list of schedules, go to the <a href='tools_sch.php'> Tools --> Schedules</a> screen and create a new schedule.").'</dd>\n';
		}
		?>
	</dl>
</div>
<div class="blackbox"<? if ($FEATURE_NOQOS=="1") echo ' style="display:none;"';?>>
	<h2><a name="QoS"><?echo i18n("QoS Engine");?></a></h2>
	<p><?echo i18n('Smart QoS improves VoIP voice quality or streaming by ensuring your VoIP or streaming traffic is prioritized over other network traffic, such as FTP or Web.');?></p>
	<dl>
		<dt><strong><?echo i18n("Enable QoS Engine");?></strong></dt>
		<dd><?
			echo i18n('Check this option if you want to enable QoS function.');
		?></dd>
		<dt><strong><?echo i18n("Automatic Uplink Speed");?></strong></dt>
		<dd><?
			echo i18n('Check this option to automatically get the optimal performance.');
		?></dd>
		<dt style="display:none;"><strong><?echo i18n("Measured Uplink Speed");?></strong></dt><!-- hide this for DIR-412 -->
		<dd><?
			echo i18n('You may see the current measured uplink speed here.');
		?></dd>
		<dt><strong><?echo i18n("Manual Uplink Speed");?></strong></dt>
		<dd><?
			echo i18n('If the measured uplink speed is not optimal, please disable the Automatic Uplink Speed and select the transmission rate from drop-down menu.');
		?></dd>
		<dt><strong><?echo i18n("Connection type");?></strong></dt>
		<dd><?
			echo i18n('You can choose auto-detect for the known connection type. Or choose xDSL or cable etc if you know what connection type you use for the Internet network.');
		?></dd>
	<dl>
</div>
<div class="blackbox">
	<h2><a name="NetFilter"><?echo i18n("Network Filter (MAC Address Filter)");?></a></h2>
	<p><?
		echo i18n('Use MAC Filters to deny computers within the local area network from accessing the Internet. You can either manually add a MAC address or select the MAC address from the list of clients that are currently connected to the unit.');
	?></p>
	<p><?
		echo i18n('Select "Turn MAC Filtering ON and ALLOW computers with MAC address listed below to access the network" if you only want selected computers to have network access and all other computers not to have network access.');
	?></p>
	<p><?
		echo i18n('Select "Turn MAC Filtering ON and DENY computers with MAC address listed below to access the network" if you want all computers to have network access except those computers in the list.');
	?></p>
	<dl>
		<dt><strong><?echo i18n("MAC Address");?></strong></dt>
		<dd><?
			echo i18n('The MAC address of the network device to be added to the MAC Filter List.');
		?></dd>
		<dt><strong><?echo i18n("DHCP Client List");?></strong></dt>
		<dd><?
			echo i18n("DHCP clients will have their hostname in the Computer Name drop down menu. You can select the client computer you want to add to the MAC Filter List and click arrow button. This will automatically add that computer's MAC address to the appropriate field.");
		?></dd>
	  </dd>
	</dl>
	<p<?if ($FEATURE_NOSCH=="1") echo ' style="display:none;"';?>><?
		echo i18n('Users can use the <strong>Always</strong> drop down menu to select a previously defined schedule or click the <strong>New Schedule</strong> button to add a new schedule.');
	?></p>
	<p><?
		echo i18n('The check box is used to enable or disable a particular entry.');
	?></p>
</div>
<div class="blackbox">
	<h2><a name="WebFilter"><?echo i18n("Website Filter");?></a></h2>
	<p><?
		echo i18n('Website Filter is used to allow or deny computers on your network from accessing specific web sites by keywords or specific Domain Names. Select "ALLOW computers access to ONLY these sites" in order only allow computers on your network to access the specified URLs and Domain Names. "DENY computers access to ONLY these sites" in order deny computers on your network to access the specified URLs and Domain Names.');
	?></p>
	<dl>
		<dt><strong><?echo i18n("Example");?> 1:</strong></dt>
		<dd><?
			echo i18n('If you wanted to block LAN users from any website containing a URL pertaining to shopping, you would need to select "DENY computers access to ONLY these sites" and then enter "shopping" into the Website Filtering Rules list. Sites like these will be denied access to LAN users because they contain the keyword in the URL.');
			?>
			<ul>
				<li>http://shopping.yahoo.com/</li>
				<li>http://shopping.msn.com/</li>
			</ul>
		</dd>
	</dl>
	<dl>
		<dt><strong><?echo i18n("Example");?> 2:</strong></dt>
		<dd><?
			echo i18n('If you want your children to only access particular sites, you would then choose "ALLOW computers access to ONLY these sites" and then enter in the domains you want your children to have access to.');
			?>
			<ul>
				<li>Google.com</li>
				<li>Cartoons.com</li>
				<li>Discovery.com</li>
			</ul>
		</dd>
	</dl>
</div>
<div class="blackbox">
	<h2><a id="Firewall" name="Firewall"><?echo i18n("Firewall Settings");?></a></h2>
	<p><?
		echo i18n('The Firewall Settings section contains the option to configure a DMZ Host.');
	?></p>
	<dl>
		<dt><strong><?echo i18n("Enable SPI");?></strong></dt>
		<dd><?
			echo i18n('SPI ("stateful packet inspection" also known as "dynamic packet filtering") helps to prevent cyber attacks by tracking more state per session. It validates that the traffic passing through that session conforms to the protocol. When the protocol is TCP, SPI checks that packet sequence numbers are within the valid range for the session, discarding those packets that do not have valid sequence number.');
			echo " ";
			echo i18n("Whether SPI is enabled or not, the router always tracks TCP connection states and ensures that each TCP packet's flags are valid for the current state.");
		?></dd>
		<dt><strong><?echo i18n("DMZ");?></strong></dt>
		<dd><?
			echo i18n('If you have a computer that cannot run Internet applications properly from behind the router, then you can allow the computer to have unrestricted Internet access. Enter the IP address of that computer as a DMZ (Demilitarized Zone) host with unrestricted Internet access. Adding a client to the DMZ may expose that computer to a variety of security risks; so only use this option as a last resort.');
		?></dd>
		<dt><strong><?echo i18n("Firewall Rules");?></strong></dt>
		<dd><?
			echo i18n('The firewall rules is used to allow or deny traffic coming in or going out to the router based on the source and destination IP addresses as well as the traffic type and the specific port the data runs on.');
			?>
			<dl>
				<dt><strong><?echo i18n("Name");?></strong></dt>
				<dd><?echo i18n("Users can specify a name for a firewall rule.");?></dd>
				<dt><strong><?echo i18n("Action");?></strong></dt>
				<dd><?echo i18n("Users can choose to allow or deny traffic.");?></dd>
				<dt><strong><?echo i18n("Source Interface");?></strong></dt>
				<dd><?
					echo i18n("Use the <strong>Source</strong> drop down menu to select the starting point of the traffic that's to be allowed or denied is from LAN or WAN interface.");
				?></dd>
				<dt><strong><?echo i18n("Dest Interface");?></strong></dt>
				<dd><?
					echo i18n("Use the <strong>Dest</strong> drop down menu to select the ending point of the traffic that's to be allowed or denied is arriving at LAN or WAN interface.");
				?></dd>
				<dt><strong><?echo i18n("IP Address");?></strong></dt>
				<dd><?
					echo i18n('Here you can specify a single source or dest IP by entering the IP in the top box or enter a range of IPs by entering the first IP of the range in the top box and the last IP of the range in the bottom one.');
				?></dd>
				<dt><strong><?echo i18n("Protocol");?></strong></dt>
				<dd><?
					echo i18n('Use the <strong>Protocol</strong> drop down menu to select the traffic type.');
				?></dd>
				<dt><strong><?echo i18n("Port Range");?></strong></dt>
				<dd><?
					echo i18n('Enter the same port number in both boxes to specify a single port or enter the first port of the range in the top box and last port of the range in the bottom one to specify a range of ports.');
				?></dd>
				<dt<?if ($FEATURE_NOSCH=="1") echo ' style="display:none;"';?>><strong><?echo i18n("Schedule");?></strong></dt>
				<dd<?if ($FEATURE_NOSCH=="1") echo ' style="display:none;"';?>><?
					echo i18n('Use the <strong>Always</strong> drop down menu to select a previously defined schedule or click on <strong>New Schedule</strong> button to add a new schedule.');
				?></dd>
			</dl>
		</dd>
	</dl>
</div>
<div class="blackbox"<?if ($FEATURE_NORT=="1") echo ' style="display:none;"';?>>
	<h2><a name="Routing"><?echo i18n("Routing");?></a></h2>
	<p><?echo i18n("The Routing option allows you to define fixed routes to defined destinations.");?></p>
	<dl>
		<dt><strong><?echo i18n("Enable");?></strong></dt>
		<dd><?
			echo i18n("Specifies whether the entry will be enabled or disabled.");
		?></dd>
		<dt><strong><?echo i18n("Interface");?></strong></dt>
		<dd><?
			echo i18n("Specifies the interface -- WAN or WAN Physical -- that the IP packet must use to transit out of the router, when this route is used.");
		?></dd>
		<dt><strong><?echo i18n("Interface (WAN)");?></strong></dt>
		<dd><?
			echo i18n("This is the interface to receive the IP Address on from the ISP to access the Internet.");
		?></dd>
		<dt><strong><?echo i18n("Interface (WAN Physical)");?></strong></dt>
		<dd><?
			echo i18n("This is the interface to receive the IP Address on from the ISP to access the ISP's.");
		?></dd>
		<dt><strong><?echo i18n("Destination");?></strong></dt>
		<dd><?
			echo i18n("The IP address of packets that will take this route.");
		?></dd>
		<dt><strong><?echo i18n("Subnet Mask");?></strong></dt>
		<dd><?
			echo i18n("One bit in the mask specify which bits of the IP address must match.");
		?></dd>
		<dt><strong><?echo i18n("Gateway");?></strong></dt>
		<dd><?
			echo i18n("Specifies the next hop to be taken if this route is used. A gateway of 0.0.0.0 implies there is no next hop, and the IP address matched is directly connected to the router on the interface specified: WAN or WAN Physical.");
		?></dd>
	</dl>
</div>
<div class="blackbox">
	<h2><a name="Wireless"><?echo i18n("Advanced Wireless");?></a></h2>
	<p><?
		echo i18n('The options on this page should be changed by advanced users or if you are instructed to by one of our support personnel, as they can negatively affect the performance of your router if configured improperly.');
	?>
	<dl>
		<dt><strong><?echo i18n("Transmit Power");?></strong></dt>
		<dd><?
			echo i18n("You can lower the output power of the router by selecting lower percentage Transmit Power values from the drop down. Your choices are: 100%, 50%, 25%, and 12.5%.");
		?></dd>
		<dt><strong><?echo i18n("Beacon Interval");?></strong></dt>
		<dd><?
			echo i18n("Beacons are packets sent by an Access Point to synchronize a wireless network. Specify a Beacon interval value between 20 and 1000. The default value is set to 100 milliseconds.");
		?></dd>
		<dt><strong><?echo i18n("RTS Threshold");?></strong></dt>
		<dd><?
			echo i18n(" This value should remain at its default setting of 2346. If you encounter inconsistent data flow, only minor modifications to the value range between 256 and 2346 are recommended. The default value for RTS Threshold is set to 2346.");
		?></dd>
		<dt><strong><?echo i18n("Fragmentation");?></strong></dt>
		<dd><?
			echo i18n('This value should remain at its default setting of 2346. If you experience a high packet error rate, you may slightly increase your "Fragmentation" value within the value range of 1500 to 2346. Setting the Fragmentation value too low may result in poor performance.');
		?></dd>
		<dt><strong><?echo i18n("DTIM Interval");?></strong></dt>
		<dd><?
			echo i18n("Enter a value between 1 and 255 for the Delivery Traffic Indication Message (DTIM). A DTIM is a countdown informing clients of the next window for listening to broadcast and multicast messages. When the Access Point has buffered broadcast or multicast messages for associated clients, it sends the next DTIM with a DTIM Interval value. AP clients hear the beacons and awaken to receive the broadcast and multicast messages. The default value for DTIM interval is set to 1.");
		?></dd>
		<dt><strong><?echo i18n("Preamble Type");?></strong></dt>
		<dd><?
			echo i18n("The Preamble Type defines the length of the CRC (Cyclic Redundancy Check) block for communication between the Access Point and roaming wireless adapters. Make sure to select the appropriate preamble type and click the Apply button.");
		?></dd>
		<dd><?
			echo i18n("Note").": ";
			echo i18n("High network traffic areas should use the shorter preamble type. CRC is a common technique for detecting data transmission errors.");
		?></dd>
		<dt><? echo i18n("Wireless Mode"); ?></dt>
		<dd>
		<? echo i18n('If all of the wireless devices you want to connect with this access point can connect in the same transmission mode, you can improve performance slightly by choosing the appropriate "Only" mode. ');
		echo i18n('If you have some devices that use a different transmission mode, choose the appropriate "Mixed" mode.');
		?></dd>
		<dt><? echo i18n("Band Width"); ?></dt>
		<dd>
		<? echo i18n('The "Auto 20/40 MHz" option is usually best. The other options are available for special circumstances.');
		?></dd>
		<dt><? echo i18n("Short Guard Interval"); ?></dt>
		<dd>
		<? echo i18n("Using a short guard interval can increase throughput. However, it can also increase error rate in some installations, due to increased sensitivity to radio-frequency reflections. Select the option that works best for your installation
.");
		?></dd>
		<!--
		<dt><strong><?echo i18n("CTS Mode");?></strong></dt>
		<dd><?
			echo i18n("Select None to disable this feature. Select Always to force the router to require each wireless device on the network to perform and RTS/CTS handshake before they are allowed to transmit data. Select Auto to allow the router to decide when RTS/CTS handshakes are necessary.");
		?></dd>
		-->
	</dl>
</div>
<div class="blackbox">
	<h2><a name="WPS"><?echo i18n("Wi-Fi Protected Setup");?></a></h2>
	<dl>
		<dt><strong><?echo i18n("Wi-Fi Protected Setup");?></strong></dt>
		<dd>
			<dl>
				<dt><strong><?echo i18n("Enable");?></strong></dt>
				<dd><?echo i18n("Enable the Wi-Fi Protected Setup feature.");?></dd>
			</dl>
		</dd>
		<dt><strong><?echo i18n("PIN Settings");?></strong></dt>
		<dd>
			<p><?
				echo i18n('A PIN is a unique number that can be used to add the router to an existing network or to create a new network. The default PIN may be printed on the bottom of the router. For extra security, a new PIN can be generated. You can restore the default PIN at any time. Only the Administrator ("admin" account) can change or reset the PIN.');
			?></p>
			<dl>
				<dt><strong><?echo i18n("PIN");?></strong></dt>
				<dd><?echo i18n("Shows the current value of the router's PIN.");?></dd>
				<dt><strong><?echo i18n("Reset PIN to Default");?></strong></dt>
				<dd><?echo i18n("Restore the default PIN of the router.");?></dd>
				<dt><strong><?echo i18n("Generate New PIN");?></strong></dt>
				<dd><?
					echo i18n("Create a random number that is a valid PIN. This becomes the router's PIN. You can then copy this PIN to the user interface of the registrar.");
				?></dd>
			</dl>
		</dd>
		<dt><strong><?echo i18n("Add Wireless Station");?></strong></dt>
		<dd>
			<p><?echo i18n("This Wizard helps you add wireless devices to the wireless network.");?></p>
			<p><?
				echo i18n("The wizard will either display the wireless network settings to guide you through manual configuration, prompt you to enter the PIN for the device, or ask you to press the configuration button on the device. If the device supports Wi-Fi Protected Setup and has a configuration button, you can add it to the network by pressing the configuration button on the device and then the on the router within 120 seconds. The status LED on the router will flash three times if the device has been successfully added to the network.");
			?></p>
			<p><?
				echo i18n('There are several ways to add a wireless device to your network. Access to the wireless network is controlled by a "registrar". A registrar only allows devices onto the wireless network if you have entered the PIN, or pressed a special Wi-Fi Protected Setup button on the device. The router acts as a registrar for the network, although other devices may act as a registrar as well.');
			?></p>
			<dl>
				<dt><strong><?echo i18n("Connect your Wireless Device");?></strong></dt>
				<dd><?echo i18n("Start the wizard.");?></dd>
			</dl>
		</dd>
	</dl>
</div>
<div class="blackbox">
	<h2><a name="Network"><?echo i18n("Advanced Network");?></a></h2>
	<p><?
		echo i18n('This section contains settings which can change the way the router handles certain types of traffic. We recommend that you not change any of these settings unless you are already familiar with them or have been instructed to change them by one of our support personnel.');
	?></p>
	<dl>
		<dt><strong><?echo i18n("UPnP");?></strong></dt>
		<dd><?
			echo i18n('UPnP is short for Universal Plug and Play which is a networking architecture that provides compatibility among networking equipment, software, and peripherals. The device is a UPnP enabled router, meaning it will work with other UPnP devices/software. If you do not want to use the UPnP functionality, it can be disabled by selecting "Disabled".');
		?></dd>
		<dt><strong><?echo i18n("WAN Ping Response");?></strong></dt>
		<dd><?
			echo i18n("When you Enable WAN Ping response, you are causing the public WAN (Wide Area Network) IP address on the device to respond to ping commands sent by Internet users. Pinging public WAN IP addresses is a common method used by hackers to test whether your WAN IP address is valid.");
		?></dd>
		<dt><strong><?echo i18n("WAN Port Speed");?></strong></dt>
		<dd><?
			echo i18n("This allows you to select the speed of the WAN interface of the router: Choose 100Mbps, 10Mbps, or 10/100Mbps Auto.");
		?></dd>
		<dt><strong><?echo i18n("Multicast Streams");?></strong></dt>
		<dd><?
			echo i18n("Enable this option to allow Multicast traffic to pass from the Internet to your network more efficiently.");
		?></dd>
		<dt><strong><?echo i18n("Enable Multicast Streams");?></strong></dt>
		<dd><?
			echo i18n("Enable this option if you are receiving video on demand type of service from the Internet. The router uses the IGMP protocol to support efficient multicasting -- transmission of identical content, such as multimedia, from a source to a number of recipients. This option must be enabled if any applications on the LAN participate in a multicast group. If you have a multimedia LAN application that is not receiving content as expected, try enabling this option.");
		?></dd>
	</dl>
</div>
<div class="blackbox" <?if ($FEATURE_NOCALLMGR !="0") echo ' style="display:none;"';?>>
        <h2><a name="CallMgr"><?echo i18n("CALL SETTING");?></a></h2>
	<p><?
                echo i18n("The Call Setting allows you to set caller's actions.");
        ?></p>
	<dl>
		<dt><strong><?echo i18n("Caller ID Display");?></strong></dt>
		<dd><?
                        echo i18n('Allows you to see the Callers ID when they make a call to you.');
                ?></dd>
		<dt><strong><?echo i18n("Caller ID Delivery");?></strong></dt>
		<dd><?
                        echo i18n('When Enabled this will block your Outgoing Caller ID so that when you make a call it will make you appear to be an anonymous caller.');
                ?></dd>
		<dt><strong><?echo i18n("Call Waiting");?></strong></dt>
		<dd><?
                        echo i18n('When Eabled this will allow you to answer another incoming call when you have already one call in progress.');
                ?></dd>
		<dt><strong><?echo i18n("Call Forwarding");?></strong></dt>
		<dd>
                        <dl>
                                <dt><strong><?echo i18n("Unconditional");?></strong></dt>
                                <dd><?echo i18n("Allows you to Enable or Disable the Call Forwarding Unconditional feature. When Enabled it will relay all calls to a different specified number.");?></dd>
                                <dt><strong><?echo i18n("Busy");?></strong></dt>
                                <dd><?echo i18n("Allows you to Enable or Disable the Call Forwarding Busy feature. When Enabled it will relay all calls when you are already on another call to a different specified number.");?></dd>
                                <dt><strong><?echo i18n("No Answer");?></strong></dt>
                                <dd><?
                                        echo i18n("Allows you to Enable or Disable the Call Forwarding No Answer feature. When Enabled it will relay all calls when you do not answer the call within specified time to a different specified number.");
                                ?></dd>
                                <dt><strong><?echo i18n("No Answer Timer");?></strong></dt>
                                <dd><?
                                        echo i18n("This will be the number that calls you do not answer the phone are to be forwarded to when No Answer is Enabled.");
                                ?></dd>
                                <dt><strong><?echo i18n("Not reachable");?></strong></dt>
                                <dd><?
                                        echo i18n('Allows you to Enable or Disable the Call Forwarding Not reachable feature. When Enabled it will relay all calls when you are not reachable to a different specified number.');
                                ?></dd>
                        </dl>
                </dd>
		
	</dl>
</div>

<div class="blackbox" <?if ($FEATURE_NOIPV6 == "1") echo ' style="display:none;"';?>>
	<h2><a name="IPv6Firewall"><?echo i18n("IPv6 Firewall");?></a></h2>
	<dt><strong><?echo i18n("IPv6 Firewall");?></strong></dt>
	<dd><?
		echo i18n("For each rule you can create a name and control the direction of traffic. You can also allow or deny a range of IP Addresses, the protocol and a port range.In order to apply a schedule to a firewall rule, your must first define a schedule on the Tools -> Schedules page");
		?>
	</dd>
</div>