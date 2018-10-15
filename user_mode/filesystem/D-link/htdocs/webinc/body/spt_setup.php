<div class="orangebox">
	<h1><?echo i18n("Setup Help");?></h1>
	<ul>
		<li><a href="#Internet"><?echo i18n("Internet");?></a></li>
		<li><a href="#Wireless"><?echo i18n("Wireless Settings");?></a></li>
		<li><a href="#Network"><?echo i18n("Network Settings");?></a></li>
		<?if ($FEATURE_NOIPV6 == "0") echo '<li><a href="#IPv6">'.i18n("IPv6").'</a></li>\n';?>
		<?if ($FEATURE_NOSMS =="0") echo '<li><a href="#SMS">'.i18n("Message Service").'</a></li>\n';?>
	</ul>
</div>
<div class="blackbox">
	<h2><a name="Internet"><?echo i18n("Internet");?></a></h2>
	<p><?
		echo i18n("If you are configuring the device for the first time, we recommend that you click on the Internet Connection Setup Wizard, and follow the instructions on the screen. If you wish to modify or configure the device settings manually, click Manual Internet Connection Setup.");
	?></p>
	<dl>
		<dt><?echo i18n("Internet Connection Setup Wizard");?></dt>
		<dd><?
			echo i18n("Click this button to have the router walk you through a few simple steps to help you connect your router to the Internet.");
		?></dd>
		<dt><?echo i18n("Manual Internet Connection Setup");?></dt>
		<dd><?
			echo i18n("Choose this option if you would like to input the settings needed to connect your router to the Internet manually.");?>
			<dl>
				<dt <?if ($FEATURE_NOAPMODE=="1") echo ' style="display:none;"';?>><?echo i18n("Access Point Mode");?></dt>
				<dd <?if ($FEATURE_NOAPMODE=="1") echo ' style="display:none;"';?>><?
					echo i18n('Enable "Access Point Mode" will make the device function like a wireless AP. All the NAT functions will be disabled except settings related to the wireless connection.');
				?></dd>
				<dt><?echo i18n("Internet Connection Type ");?></dt>
				<dd><?
					echo i18n('The Internet Connection Settings are used to connect the router to the Internet. Any information that needs to be entered on this page will be provided to you by your ISP and often times referred to as "public settings". Please select the appropriate option for your specific ISP. If you are unsure of which option to select, please contact your ISP.');?>
					<dl>
						<dt><?echo i18n("Static IP Address");?></dt>
						<dd><?
							echo i18n('Select this option if your ISP (Internet Service Provider) has provided you with an IP address, Subnet Mask, Default Gateway, and a DNS server address. Enter this information in the appropriate fields. If you are unsure of what to enter in these fields, please contact your ISP.');
						?></dd>
						<dt><?echo i18n("Dynamic IP Address");?></dt>
						<dd><?
							echo i18n('Select this option if your ISP (Internet Service Provider) provides you with an IP address automatically. Cable modem providers typically use dynamic assignment of IP Addresses.');?>
							<dl>
							<p><?
								echo '<strong>'.i18n('Host Name').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('The Host Name field is optional but may be required by some Internet Service Providers. The default host name is the model number of the router.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MAC Address').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('The MAC (Media Access Control) Address field is required by some Internet Service Providers (ISP). The default MAC address is set to the MAC address of the WAN interface on the router. You can use the "Clone MAC Address" button to automatically copy the MAC address of the Ethernet Card installed in the computer used to configure the device. It is only necessary to fill in the field if required by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Primary DNS Address').'</strong><br/>';
								echo i18n('Enter the Primary DNS (Domain Name Service) server IP address provided to you by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Secondary DNS Address').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('If you were given a secondary DNS server IP address from your ISP, enter it in this field.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MTU').'</strong><br/>';
								echo i18n('MTU (Maximum Transmission/Transfer Unit) is the largest packet size that can be sent over a network. Messages larger than the MTU are divided into smaller packets. 1500 is the default value for this option. Changing this number may adversely affect the performance of your router. Only change this number if instructed to by one of our Technical Support Representatives or by your ISP.');
							?></p>
							</dl>
						</dd>
						<dt><?echo i18n("PPPoE");?></dt>
						<dd><?
							echo i18n('Select this option if your ISP requires you to use a PPPoE (Point to Point Protocol over Ethernet) connection. DSL providers typically use this option. Select Dynamic PPPoE to obtain an IP address automatically for your PPPoE connection (used by majority of PPPoE connections). Select Static PPPoE to use a static IP address for your PPPoE connection.');?>
							<dl>
							<p><?
								echo '<strong>'.i18n('User Name').'</strong><br/>';
								echo i18n('Enter your PPPoE username.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Password').'</strong><br/>';
								echo i18n('Enter your PPPoE password.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Service Name').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('If your ISP uses a service name for the PPPoE connection, enter the service name here.');
							?></p>
							<p><?
								echo '<strong>'.i18n('IP Address').'</strong><br/>';
								echo i18n('This option is only available for Static PPPoE. Enter in the static IP address for the PPPoE connection.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MAC Address').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('The MAC (Media Access Control) Address field is required by some Internet Service Providers (ISP). The default MAC address is set to be the MAC address of the WAN interface on the router. You can use the "Clone MAC Address" button to automatically copy the MAC address of the Ethernet Card installed in the computer that is being used to configure the device. It is only necessary to fill in this field if required by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Primary DNS Address').'</strong><br/>';
								echo i18n('Primary DNS (Domain Name System) server IP address, which may be provided by your ISP. You should only need to enter this information if you selected Static PPPoE. If Dynamic PPPoE is chosen, leave this field at its default value as your ISP will provide you this information automatically.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Secondary DNS Address').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('If you were given a secondary DNS server IP address from your ISP, enter it in this field.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Maximum Idle time').'</strong><br/>';
								echo i18n('The amount of inactivity time (in minutes) before the device will disconnect your PPPoE session. Enter a Maximum Idle Time (in minutes) to define a maximum period of time for which the Internet connection is maintained during inactivity. If the connection is inactive for longer than the defined Maximum Idle Time, then the connection will be dropped. This option only applies to the Connect-on-demand Connection mode.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MTU').'</strong><br/>';
								echo i18n('MTU (Maximum Transmission/Transfer Unit) is the largest packet size that can be sent over a network. Messages larger than the MTU are divided into smaller packets. 1492 is the default value for this option. Changing this number may adversely affect the performance of your router. Only change this number if instructed to by one of our Technical Support Representatives or by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Connect mode select').'</strong><br/>';
								echo i18n('Select Always-on if you would like the router to never disconnect the PPPoE session. Select Manual if you would like to control when the router is connected and disconnected from the Internet. The Connect-on-demand option allows the router to establish a connection to the Internet only when a device on your network tries to access a resource on the Internet.');
							?></p>
							</dl>
						</dd>
						<dt<?if ($FEATURE_NOPPTP=="1") echo ' style="display:none;"';?>><?echo i18n('PPTP');?></dt>
						<dd<?if ($FEATURE_NOPPTP=="1") echo ' style="display:none;"';?>><?
							echo i18n('Select this option if your ISP uses a PPTP (Point to Point Tunneling Protocol) connection and has assigned you a username and password in order to access the Internet. Select Dynamic PPTP to obtain an IP address automatically for your PPTP connection. Select Static PPTP to use a static IP address for your PPTP connection.');?>
							<dl>
							<p><?
								echo '<strong>'.i18n('IP Address').'</strong><br/>';
								echo i18n('Enter the IP address that your ISP has assigned to you.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Subnet Mask').'</strong><br/>';
								echo i18n('Enter the Subnet Mask that your ISP has assigned to you.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Gateway').'</strong><br/>';
								echo i18n('Enter the Gateway IP address assigned to you by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('DNS').'</strong><br/>';
								echo i18n('Enter the DNS address assigned to you by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Server IP').'</strong><br/>';
								echo i18n('Enter the IP address of the server, which will be provided by your ISP, that you will be connecting to.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Username').'</strong><br/>';
								echo i18n('Enter your PPTP Username.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Password').'</strong><br/>';
								echo i18n('Enter your PPTP Password.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Maximum Idle time').'</strong><br/>';
								echo i18n('The amount of time of inactivity before the device will disconnect your PPTP session. Enter a Maximum Idle Time (in minutes) to define a maximum period of time for which the Internet connection is maintained during inactivity. If the connection is inactive for longer than the specified Maximum Idle Time, the connection will be dropped. This option only applies to the Connect-on-demand Connection mode.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MTU').'</strong><br/>';
								echo i18n('MTU (Maximum Transmission/Transfer Unit) is the largest packet size that can be sent over a network. Messages larger than the MTU are divided into smaller packets. 1400 is the default value for this option. Changing this number may adversely affect the performance of your router. Only change this number if instructed to by one of our Technical Support Representatives or by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Connect mode select').'</strong><br/>';
								echo i18n('Select Always-on if you would like the router to never disconnect the PPTP session. Select Manual if you would like to control when the router is connected and disconnected from the Internet. The Connect-on-demand option allows the router to establish a connection to the Internet only when a device on your network tries to access a resource on the Internet.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MAC Address').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('The MAC (Media Access Control) Address field is required by some Internet Service Providers (ISP). The default MAC address is set to the MAC address of the WAN interface on the router. You can use the "Clone MAC Address" button to automatically copy the MAC address of the Ethernet Card installed in the computer used to configure the device. It is only necessary to fill in the field if required by your ISP.');
							?></p>
							</dl>
						</dd>
						<dt<?if ($FEATURE_NOL2TP=="1") echo ' style="display:none;"';?>><?echo i18n('L2TP');?></dt>
						<dd<?if ($FEATURE_NOL2TP=="1") echo ' style="display:none;"';?>><?
							echo i18n('Select this option if your ISP uses a L2TP (Layer 2 Tunneling Protocol) connection and has assigned you a username and password in order to access the Internet. Select Dynamic L2TP to obtain an IP address automatically for your L2TP connection. Select Static L2TP to use a static IP address for your L2TP connection.');?>
							<dl>
							<p><?
								echo '<strong>'.i18n('IP Address').'</strong><br/>';
								echo i18n('Enter the IP address that your ISP has assigned to you.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Subnet Mask').'</strong><br/>';
								echo i18n('Enter the Subnet Mask that your ISP has assigned to you.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Gateway').'</strong><br/>';
								echo i18n('Enter the Gateway IP address assigned to you by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('DNS').'</strong><br/>';
								echo i18n('Enter the DNS address assigned to you by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Server IP').'</strong><br/>';
								echo i18n('Enter the IP address of the server, which will be provided by your ISP, that you will be connecting to.');
							?></p>
							<p><?
								echo '<strong>'.i18n('L2TP Username').'</strong><br/>';
								echo i18n('Enter your L2TP Username.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Password').'</strong><br/>';
								echo i18n('Enter your L2TP Password.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Maximum Idle time').'</strong><br/>';
								echo i18n('The amount of inactivity time (in minutes) before the device will disconnect your L2TP session. Enter a Maximum Idle Time (in minutes) to define a maximum period of time for which the Internet connection is maintained during inactivity. If the connection is inactive for longer than the defined Maximum Idle Time, then the connection will be dropped. This option only applies to the Connect-on-demand Connection mode.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MTU').'</strong><br/>';
								echo i18n('MTU (Maximum Transmission/Transfer Unit) is the largest packet size that can be sent over a network. Messages larger than the MTU are divided into smaller packets. 1400 is the default value for this option. Changing this number may adversely affect the performance of your router. Only change this number if instructed to by one of our Technical Support Representatives or by your ISP.');
							?></p>
							<p><?
								echo '<strong>'.i18n('Connect mode select').'</strong><br/>';
								echo i18n('Select Always-on if you would like the router to never disconnect the L2TP session. Select Manual if you would like to control when the router is connected and disconnected from the Internet. The Connect-on-demand option allows the router to establish a connection to the Internet only when a device on your network tries to access a resource on the Internet.');
							?></p>
							<p><?
								echo '<strong>'.i18n('MAC Address').'</strong> ('.i18n('optional').')<br/>';
								echo i18n('The MAC (Media Access Control) Address field is required by some Internet Service Providers (ISP). The default MAC address is set to the MAC address of the WAN interface on the router. You can use the "Clone MAC Address" button to automatically copy the MAC address of the Ethernet Card installed in the computer used to configure the device. It is only necessary to fill in the field if required by your ISP.');
							?></p>							
							</dl>
						</dd>
						<dt<?if ($FEATURE_NORUSSIAPPTP=="1") echo ' style="display:none;"';?>>
							<?echo i18n('Russian PPTP (Dual Access)');?>
						</dt>
						<dd<?if ($FEATURE_NORUSSIAPPTP=="1") echo ' style="display:none;"';?>><?
							echo i18n('To configure a Russian PPTP Internet connection, configure as previously described for PPTP connections.')." "; if($FEATURE_NORT=="0") echo i18n('If any static route needs to be setup by your ISP, please refer to the "Routing" function in "ADVANCED" menu for further setup.');
						?></dd>
						<dt<?if ($FEATURE_NORUSSIAPPPOE=="1") echo ' style="display:none;"';?>>
							<?echo i18n('Russian PPPoE (Dual Access)');?>
						</dt>
						<dd<?if ($FEATURE_NORUSSIAPPPOE=="1") echo ' style="display:none;"';?>><?
							echo i18n('Some PPPoE connections use a static IP route to the ISP in addition to the global IP settings for the connection. This requires an added step to define IP settings for the physical WAN port. To configure a Russian PPPoE Internet connection, configure as previously described for PPPoE connections and add the physical WAN IP settings as instructed by your ISP.')." "; if($FEATURE_NORT=="0") echo i18n('If any static route needs to be setup by your ISP, please refer to the "Routing" function in "ADVANCED" menu for further setup.');
						?></dd>
					</dl>
				</dd>
			</dl>
		</dd>
	</dl>
</div>
<div class="blackbox">
	<h2><a name="Wireless"><?echo i18n("Wireless Settings");?></a></h2>
	<p><?
		echo i18n('The Wireless Setup page contains the settings for the (Access Point) Portion of the router. This page allows you to customize your wireless network or configure the router to fit an existing wireless network that you may already have setup.');
	?></p>
	<dl>
		<!--
		<dt><?echo i18n('Wi-Fi Protected Setup (Also called WCN 2.0 in Windows Vista)');?></dt>
		<dd><?
			echo i18n("This feature provides users a more intuitive way of setting up wireless security. It is available in two formats: PIN number and Push button. Enter the PIN number that comes with the device in the wireless card utility or Windows Vista's wireless client utility if the wireless card has a certified Windows Vista driver to automatically set up wireless security between the router and the client. The wireless card will have to support Wi-Fi Protected Setup in either format in order to take advantage of this feature.");
		?></dd>
		-->
		<dt><?echo i18n('Wireless Network Name');?></dt>
		<dd><?
			echo i18n('Also known as the SSID (Service Set Identifier), this is the name of your Wireless Local Area Network (WLAN). This can be easily changed to establish a new wireless network or to add the router to an existing wireless network.');
		?></dd>
		<?
		if ($FEATURE_NOSCH!="1")
		{
			echo '<dt>'.i18n("Schedule").'</dt>\n';
			echo '<dd>'.i18n("Select a schedule for when the service will be enabled.").'\n';
			echo i18n("If you do not see the schedule you need in the list of schedules, go to the <a href='tools_sch.php'> Tools --> Schedules</a> screen and create a new schedule.").'</dd>\n';
		}
		?>
		<dt><?echo i18n('Enable Auto Channel Selection');?></dt>
		<dd><?
			echo i18n('Enable Auto Channel Selection let the router can select the best possible channel for your wireless network to operate on.');
		?></dd>		
		<dt><?echo i18n('Wireless Channel');?></dt>
		<dd><?
			echo i18n('Indicates which channel the router is operating on. By default the channel is set to 6. This can be changed to fit the channel setting for an existing wireless network or to customize your new wireless network. Click the Enable Auto Scan checkbox to have the router automatically select the channel that it will operate on. This option is recommended because the router will choose the channel with the least amount of interference.');
		?></dd>
		<dt><?echo i18n("Transmission (TX) Rates");?></dt>
		<dd><?
			echo i18n("Select the basic transfer rates based on the speed of wireless adapters on the WLAN (wireless local area network).");
		?></dd>
		<dt><?echo i18n('WMM');?></dt>
		<dd><?
			echo i18n('Select Enable to turn on QoS for the wireless interface of the router.');
		?></dd>
		<dt><?echo i18n('Enable Hidden Wireless');?></dt>
		<dd><?
			echo i18n('Select Enabled if you would not like the SSID of your wireless network to be broadcasted by the router. If this option is Enabled, the SSID of the router will not be seen by Site Survey utilities, so when setting up your wireless clients, you will have to know the SSID of your router and enter it manually in order to connect to the router. This option is enabled by default.');
		?></dd>
		<dt><?echo i18n('Wireless Security Mode');?></dt>
		<dd><?
			echo i18n('Securing your wireless network is important as it is used to protect the integrity of the information being transmitted over your wireless network. The router is capable of 2 types of wireless security; WEP and WPA/WPA2 (auto-detect)');
			?>
			<dl>
				<dt><?echo i18n('WEP');?></dt>
				<dd><?
					echo i18n('Wired Equivalent Protocol (WEP) is a wireless security protocol for Wireless Local Area Networks (WLAN). WEP provides security by encrypting the data that is sent over the WLAN. The router supports 2 levels of WEP Encryption: 64-bit and 128-bit. WEP is disabled by default. The WEP setting can be changed to fit an existing wireless network or to customize your wireless network.');
					?>
					<dl>
						<dt><?echo i18n('Authentication');?></dt>
						<dd><?
						echo i18n('Authentication is a process by which the router verifies the identity of a network device that is attempting to join the wireless network. There are two types authentication for this device when using WEP.');
						?></dd>
						<dl>
							<dt><?echo i18n('Open System');?></dt>
							<dd><?
								echo i18n('Select this option to allow all wireless devices to communicate with the router before they are required to provide the encryption key needed to gain access to the network.');
							?></dd>
							<dt><?echo i18n('Shared Key');?></dt>
							<dd><?
								echo i18n('Select this option to require any wireless device attempting to communicate with the router to provide the encryption key needed to access the network before they are allowed to communicate with the router.');
							?></dd>
						</dl>
						<dt><?echo i18n('WEP Encryption');?></dt>
						<dd><?
							echo i18n('Select the level of WEP Encryption that you would like to use on your network. The two supported levels of WEP encryption are 64-bit and 128-bit.');
						?></dd>
						<dt><?echo i18n('Key Type');?></dt>
						<dd><?
							echo i18n('The Key Types that are supported by the router are HEX (Hexadecimal) and ASCII (American Standard Code for Information Interchange.) The Key Type can be changed to fit an existing wireless network or to customize your wireless network.');
						?></dd>
						<dt><?echo i18n('Keys');?></dt>
						<dd><?
							echo i18n('Keys 1-4 allow you to easily change wireless encryption settings to maintain a secure network. Simply select the specific key to be used for encrypting wireless data on the network.');
						?></dd>
					</dl>
				</dd>
				<dt><?echo i18n('WPA/WPA2');?></dt>
				<dd><?
					echo i18n('Wi-Fi Protected Access (2) authorizes and authenticates users onto the wireless network. WPA(2) uses stronger security than WEP and is based on a key that changes automatically at regular intervals.');
					?>
					<dl>
						<dt><?echo i18n('Cipher Type');?></dt>
						<dd><?
							echo i18n('The router supports two different cipher types when WPA is used as the Security Type. These two options are TKIP (Temporal Key Integrity Protocol) and AES (Advanced Encryption Standard).');
						?></dd>
						<dt><?echo i18n('PSK/EAP');?></dt>
						<dd><?
							echo i18n('When PSK is selected, your wireless clients will need to provide a Passphrase for authentication. When EAP is selected, you will need to have a RADIUS server on your network which will handle the authentication of all your wireless clients.');
						?></dd>
						<dt><?echo i18n('Network Key');?></dt>
						<dd><?
							echo i18n('This is what your wireless clients will need in order to communicate with your router, When PSK is selected enter 8-63 alphanumeric characters. Be sure to write this Passphrase down as you will need to enter it on any other wireless devices you are trying to add to your network.');
						?></dd>
						<dt><?echo i18n('RADIUS server');?></dt>
						<dd><?
							echo i18n('This means that WPA authentication will be used in conjunction with a RADIUS server that must be present on your network. Enter the IP address, port, and Shared Secret that your RADIUS is configured for. You also have the option to enter information for a second RADIUS server in the event that there are two on your network that you are using to authenticate wireless clients.');
						?></dd>
					</dl>
				</dd>
			</dl>
		</dd>
	</dl>
</div>
<div class="blackbox">
	<h2><a name="Network" name="Network"><?echo i18n("Network Settings");?></a></h2>
	<dl>
		<dt><?echo i18n('LAN Setup');?></dt>
		<dd><?
			echo i18n('These are the settings of the LAN (Local Area Network) interface for the device. These settings may be referred to as "private settings". You may change the LAN IP address if needed. The LAN IP address is private to your internal network and cannot be seen on the Internet. The default IP address is 192.168.0.1 with a subnet mask of 255.255.255.0.');
			?>
			<dl>
			<p><?
				echo '<strong>'.i18n('IP Address').'</strong><br/>';
				echo i18n('IP address of the router, default is 192.168.0.1.');
			?></p>
			<p><?
				echo '<strong>'.i18n('Subnet Mask').'</strong><br/>';
				echo i18n('Subnet Mask of the router, default is 255.255.255.0.');
			?></p>
			<p><?
				echo '<strong>'.i18n('Host Name').'</strong><br/>';
				echo i18n('The default host name is the model number of the router.');
			?></p>		
			<p><?
				echo '<strong>'.i18n('Local Domain Name').'</strong> ('.i18n('optional').')<br/>';
				echo i18n('Enter in the local domain name for your network.');
			?></p>
			<p><?
				echo '<strong>'.i18n('DNS Relay').'</strong><br/>';
				echo i18n("When DNS Relay is enabled, DHCP clients of the router will be assigned the router's LAN IP address as their DNS server. All DNS requests that the router receives will be forwarded to your ISPs DNS servers. When DNS relay is disabled, all DHCP clients of the router will be assigned the ISP's DNS server.");
			?></p>
			</dl>
		</dd>
		<dt><?echo i18n('DHCP Server');?></dt>
		<dd><?
			echo i18n('DHCP stands for Dynamic Host Control Protocol. The DHCP server assigns IP addresses to devices on the network that request them. These devices must be set to "Obtain the IP address automatically". By default, the DHCP Server is enabled on the router. The DHCP address pool contains the range of IP addresses that will automatically be assigned to the clients on the network.');
			?>
			<dl>
				<dt><?echo i18n('DHCP Reservation');?></dt>
				<dd><?
					echo i18n('Enter the "Computer Name", "IP Address" and "MAC Address" manually for the PC that you want the router to statically assign the same IP to or choose the PC from the drop-down menu, which shows current DHCP clients.');
				?></dd>
				<dt><?echo i18n('Starting IP address');?></dt>
				<dd><?
					echo i18n("The starting IP address for the DHCP server's IP assignment.");
				?></dd>
				<dt><?echo i18n('Ending IP address');?></dt>
				<dd><?
					echo i18n("The ending IP address for the DHCP server's IP assignment.");
				?></dd>
				<dt><?echo i18n('Lease Time');?></dt>
				<dd><?
					echo i18n('The length of time in minutes for the IP lease.');
				?></dd>
		</dd>
		<dd><?
			echo i18n('Dynamic DHCP client computers connected to the unit will have their information displayed in the Dynamic DHCP Client Table. The table will show the Host Name, IP Address, MAC Address, and Expired Time of the DHCP lease for each client computer.');
		?></dd>
	</dl>
</div>


<div class="blackbox">
	<h2><a name="IPv6" <?if ($FEATURE_NOIPV6 == "1") echo ' style="display:none;"';?>><?echo i18n("IPv6");?></a></h2>
	<dl>
		<dt><strong><?echo i18n("IPv6");?></strong></dt>
		<dd><?
			echo i18n('The IPv6 (Internet Protocol version 6) section is where you configure your IPv6 Connection type.');
			?>
		</dd>
	</dl>
	<dl>
		<dt><strong><?echo i18n("IPv6 Connection Type");?> </strong></dt>
		<dd>
			<?
			echo i18n('There are several connection types to choose from: Link-local, Static IPv6, DHCPv6, Stateless Autoconfiguration, PPPoE, IPv6 over IPv4 Tunnel and 6to4. If you are unsure of your connection method, please contact your IPv6 Internet Service Provider. Note: If using the PPPoE option, you will need to ensure that any PPPoE client software on your computers has been removed or disabled.');
			?>
			<dl>
				<p>	<dt><strong><?echo i18n("Link-local Only ");?></strong></dt>
				<dd><?echo i18n("The Link-local address is used by nodes and routers when communicating with neighboring nodes on the same link. This mode enables IPv6-capable devices to communicate with each other on the LAN side.");?></dd></p>
				<p>	<dt><strong><?echo i18n("Static IPv6 Mode ");?></strong></dt>
				<dd><?echo i18n("This mode is used when your ISP provides you with a set IPv6 addresses that does not change. The IPv6 information is manually entered in your IPv6 configuration settings. You must enter the IPv6 address, Subnet Prefix Length, Default Gateway, Primary DNS Server, and Secondary DNS Server. Your ISP provides you with all this information. ");?></dd></p>
				<p> <dt><strong><?echo i18n("DHCPv6 Mode ");?></strong></dt>
				<dd><?echo i18n("This is a method of connection where the ISP assigns your IPv6 address when your router requests one from the ISP's server. Some ISP's require you to make some settings on your side before your router can connect to the IPv6 Internet. ");?></dd></p>
				<p> <dt><strong><?echo i18n("PPPoE ");?></strong></dt>
				<dd><?echo i18n("Select this option if your ISP requires you to use a PPPoE (Point to Point Protocol over Ethernet) connection to IPv6 Internet. DSL providers typically use this option. This method of connection requires you to enter a <strong>Username</strong> and <strong>Password</strong> (provided by your Internet Service Provider) to gain access to the IPv6 Internet. The supported authentication protocols are PAP and CHAP.");?></dd>
				<dt><strong><?echo i18n("Dynamic IP:");?></strong></dt>
				<dd><?
					echo i18n('Select this option if the ISP\'s servers assign the router\'s WAN IPv6 address upon establishing a connection.');
				?></dd>
				<dt><strong><?echo i18n("Static IP:");?></strong></dt>
				<dd><?
					echo i18n('If your ISP has assigned a fixed IPv6 address, select this option. The ISP provides the value for the <strong>IPv6 Address</strong>.');
				?></dd>
				<dt><strong><?echo i18n("Service Name:");?></strong></dt>
				<dd><?
					echo i18n('Some ISP\'s may require that you enter a Service Name. Only enter a Service Name if your ISP requires one.');
				?></dd>
				</p>
				<p>	<dt><strong><?echo i18n("IPv6 over IPv4 Tunnel Mode ");?></strong></dt>
				<dd><?
					echo i18n('IPv6 over IPv4 tunneling encapsulate of IPv6 packets in IPv4 packets so that IPv6 packets can be sent over an IPv4 infrastructure.');
				?></dd> </p>
				<p> <dt><strong><?echo i18n("6to4 Mode");?></strong></dt>				
				<dd><?
					echo i18n('6to4 is an IPv6 address assignment and automatic tunneling technology that used to provide unicast IPv6 connectivity between IPv6 sites and hosts across the IPv4 Internet.');
				?></dd>
				<dd><?
					echo i18n('The following options apply to all WAN modes.');
				?></dd>
				<dd><?
					echo i18n('Primary DNS Server, Secondary DNS Server: Enter the IPv6 addresses of the DNS Servers. Leave the field for the secondary server empty if not used.');
				?></dd></p>
			</dl>
		</dd>
	</dl>
	
	<dl>
		<dt><strong><?echo i18n("LAN IPv6 ADDRESS SETTINGS ");?></strong></dt>
		<dd><?
			echo i18n('These are the settings of the LAN (Local Area Network) IPv6 interface for the router. The router\'s LAN IPv6 Address configuration is based on the IPv6 Address and Subnet assigned by your ISP. (A subnet with prefix /64 is supported in LAN.)');
			?>
		</dd>
	</dl>
	<dl>
		<dt><strong><?echo i18n("LAN ADDRESS AUTOCONFIGURATION SETTINGS ");?></strong></dt>
		<dd><?
			echo i18n('Use this section to set up IPv6 Autoconfiguration to assign an IPv6 address to the computers on your local network. A Stateless and a Stateful Autoconfiguration method are provided.');
			?>
			<dl>
				<dt><strong><?echo i18n("Enable Autoconfiguration ");?></strong></dt>
				<dd><?
					echo i18n('These two values (from and to) define a range of IPv6 addresses that the DHCPv6 Server uses when assigning addresses to computers and devices on your Local Area Network. Any addresses that are outside this range are not managed by the DHCPv6 Server. However, these could be used for manually configuring devices or devices that cannot use DHCPv6 to automatically obtain network address details. ');
					?>
				</dd>
				<dd><?
					echo i18n('When you select Stateful (DHCPv6), the following options are displayed.');
					?>
				</dd>
				<dd><?
					echo i18n('The computers (and other devices) connected to your LAN also need to have their TCP/IP configuration set to "DHCPv6" or "Obtain an IPv6 address automatically".');
					?>
				</dd>						
			</dl>
			<dl>
				<dt><strong><?echo i18n("IPv6 Address Range (DHCPv6)");?></strong></dt>
				<dd>
					<?
					echo i18n('Once your D-Link router is properly configured and this option is enabled, the router will manage the IPv6 addresses and other network configuration information for computers and other devices connected to your Local Area Network. There is no need for you to do this yourself.');
					?>
				</dd>
				<dd>
					<?
					echo i18n('It is possible for a computer or device that is manually configured to have an IPv6 address that does reside within this range.');
					?>
				</dd>
			</dl>
			<dl>
				<dt><strong><?echo i18n("IPv6 Address Lifetime ");?></strong></dt>
				<dd>
					<?
					echo i18n('The amount of time that a computer may have an IPv6 address before it is required to renew the lease.');
					?>
				</dd>
			</dl>
		</dd>
	</dl>

</div>


<div class="blackbox" <? if ($FEATURE_NOSMS !="0") echo ' style="display:none;"';?>>
        <h2><a name="SMS"><?echo i18n("Message Service");?></a></h2>
        <dl>
		<dd><?
			 echo i18n('Message Service');
                ?>.
		</dd>
		<dl>
			<dt><?echo i18n('Short Message Service (SMS)');?></dt>
                        <dd><?
				echo i18n('With The Short Message Service (SMS) network service you can send/receive short text messages.');
			?></dd>
                        <dt><?echo i18n('SMS Inbox');?></dt>
                        <dd><?echo i18n("You can browser received short text messages here.");?></dd>
                        <dt><?echo i18n('Create Message');?></dt>
                        <dd><?
                                echo i18n("You can write and edit text messages of up to 511 characters. You can send short text messages to phones which have SMS capability.");
                        ?></dd>
		</dl>
	</dl>
</div>
