var subnet_mask = new Array(0, 128, 192, 224, 240, 248, 252, 254, 255);

var month = new Array(get_words('tt_Jan'), get_words('tt_Feb'), get_words('tt_Mar'), get_words('tt_Apr'), get_words('tt_May'), get_words('tt_Jun'), get_words('tt_Jul'), 
						get_words('tt_Aug'), get_words('tt_Sep'), get_words('tt_Oct'), get_words('tt_Nov'), get_words('tt_Dec'));
var month_device = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec");
						
var sequence = new Array(get_words('tt_week_1'), get_words('tt_week_2'), get_words('tt_week_3'), get_words('tt_week_4'), get_words('tt_week_5'), get_words('tt_week_6'));
/*
var msg = new Array("The IP Address entered is invalid",	//INVALID_IP_ADDRESS
					"The IP Address cannot be zero.",	//ZERO_IP_ADDRESS
					"IP Address",	//IP_ADDRESS_DESC
					"The Subnet Mask entered is invalid",	//INVALID_MASK_ADDRESS
					"The Subnet Mask cannot be zero",	//ZERO_MASK_ADDRESS
					"Subnet Mask",	//MASK_ADDRESS_DESC
					"The Gateway IP Address entered is invalid",	//INVALID_GATEWAY_ADDRESS
					"The Gateway IP Address cannot be zero",	//ZERO_GATEWAY_ADDRESS
					"Gateway IP Address",	//GATEWAY_ADDRESS_DESC
					"%s Gateway IP address %s must be within the WAN subnet.",	//NOT_SAME_DOMAIN
					"The Starting IP Address entered is invalid (IP Range: 1~254)",	//INVALID_START_IP
					"Please enter another Starting IP Address",	//ZERO_START_IP
					"Starting IP Address",	//START_IP_DESC
					"The LAN IP Address and the Start IP Address are not in the same subnet",	//START_INVALID_DOMAIN
					"The Ending IP Address entered is invalid (IP Range: 1~254)",	//INVALID_END_IP
					"Please enter another Ending IP Address",	//ZERO_END_IP
					"Ending IP Address",	//END_IP_DESC
					"The LAN IP Address and the End IP Address are not in the same subnet",	//END_INVALID_DOMAIN
					"The Primary DNS Address entered is invalid",	//INVALID_DNS_ADDRESS
					"The Primary DNS Address cannot be zero",	//ZERO_DNS_ADDRESS
					"Primary DNS Address",	//DNS_ADDRESS_DESC
					"The SSID field cannot be blank",	//SSID_EMPTY_ERROR					
					"WEP cannot be disabled when the Authentication type is set to Shared Key",	//AUTH_TYPE_ERROR
					"The length of the Passphrase must be at least 8 characters",	//PSK_LENGTH_ERROR
					"The Confirmed Passphrase does not match the Passphrase",	//PSK_MATCH_ERROR
					"The Confirmed Password does not match the New Password",	//MATCH_PWD_ERROR
					"The selected WEP key field cannot be blank",	//WEP_KEY_EMPTY
					"Please enter another Key",	//WIZARD_KEY_EMPTY
					"Quit setup wizard and discard settings?",	//QUIT_WIZARD
					"The MAC Address entered is invalid.",	//MAC_ADDRESS_ERROR	 																	
					"The Ending IP Address must be greater than the Starting IP Address",	//IP_RANGE_ERROR
					"The Secondary DNS Address entered is invalid",	//INVALID_SEC_DNS_ADDRESS
					"The Secondary DNS Address cannot be zero",	//ZERO_SEC_DNS_ADDRESS
					"Secondary DNS Address",	//SEC_DNS_ADDRESS_DESC
					"The confirmed password does not match the new Admin password",	//ADMIN_PASS_ERROR
					"The confirmed password does not match the new User password",	//USER_PASS_ERROR
					"Please enter another Server Name",	//DDNS_SERVER_ERROR
					"The Host Name is invalid.",	//DDNS_HOST_ERROR
					"Please enter another User Name",	//DDNS_USER_ERROR
					"Please enter another Password",	//DDNS_PASS_ERROR
					"Are you sure you want to reset the device to its factory default settings?\nThis will cause all current settings to be lost.",	//RESTORE_DEFAULT
					"Are you sure you want to reboot the device?\nRebooting will disconnect any active internet sessions.",	//REBOOT_ROUTER
					"Load settings from a saved configuration file?",	//LOAD_SETTING
					"You must enter the name of a configuration file first.",	//LOAD_FILE_ERROR
					"Only the Admin account can download the settings",	//DOWNLOAD_SETTING_ERROR
					"Please enter either a Host Name or an IP Address",	//PING_IP_ERROR
					"Please enter another SMTP Server or IP Address",	//SMTP_SERVER_ERROR
					"Please enter a valid email Address",	//EMAIL_ADDRESS_ERROR
					"Are you sure that you want to delete this Virtual Server Rule?",	//DEL_SERVER_MSG
					"Are you sure that you want to delete this Application Rule",	//DEL_APPLICATION_MSG
					"Are you sure that you want to delete this Filter?",	//DEL_FILTER_MSG
					"Are you sure that you want to delete this Route?",	//DEL_ROUTE_MSG
					"Are you sure that you want to delete this MAC Address?",	//DEL_MAC_MSG
					"Are you sure that you want to delete this Keyword?",	//DEL_KEYWORD_MSG
					"Are you sure that you want to delete this Domain?",	//DEL_DOMAIN_MSG
					"Are you sure that you want to delete this Entry?",	//DEL_ENTRY_MSG
					"Are you sure that you want to delete this DHCP Reservation?",	//DEL_STATIC_DHCP_MSG
					"Please enter another name",	//NAME_ERROR
					"Please enter a Trigger Port number",	//TRIGGER_PORT_ERROR
					"Please enter a Firewall Port number",	//PUBLIC_PORT_ERROR
					"Please enter another Private Port number",	//PRIVATE_PORT_ERROR
					"Please enter a Private IP Address.",	//PRIVATE_IP_ERROR
					"Please enter another port number",	//PORT_ERROR
					"Please select a Keyword to delete",	//DEL_KEYWORD_ERROR
					"The Keyword entered already exists in the list",	//SAME_KEYWORD_ERROR
					"Please enter another Keyword",	//KEYWORD_ERROR
					"Unable to add another Keyword",	//ADD_KEYWORD_ERROR
					"Please select a Blocked Domain to delete",	//DEL_BLOCK_DOMAIN_ERROR
					"Please select a Permitted Domain to delete",	//DEL_PERMIT_DOMAIN_ERROR
					"The Domain entered already exists in the list of Blocked Domains",	//SAME_BLOCK_DOMAIN
					"Please enter another Blocked Domain",	//BLOCK_DOMAIN_ERROR
					"Unable to add another Blocked Domain",	//ADD_BLOCK_DOMAIN_ERROR
					"The Domain entered already exists in the list of Permitted Domains",	//SAME_PERMIT_DOMAIN
					"Please enter another Permitted Domain",	//PERMIT_DOMAIN_ERROR
					"Unable to add another Permitted Domain",	//ADD_PERMIT_DOMAIN_ERROR
					"Please select a Firmware file to upgrade the router to",	//FIRMWARE_UPGRADE_ERROR
					"Please enter another Domain",	//DOMAIN_ERROR
					"Unable to add another Control Domains",	//ADD_CONTROL_DOMAIN_ERROR
					"Please select a Control Domain to delete",	//DEL_CONTROL_DOMAIN_ERROR
					"Please enter at least one Control Domain",	//CONTROL_DOMAIN_ERROR
					"The RADIUS Server 1 IP Address entered is invalid",	//INVALID_RADIUS_SERVER1_IP
					"The Radius Server 1 IP Address cannot be zero or blank",	//ZERO_RADIUS_SERVER1_IP
					"Radius Server 1 IP Address",	//RADIUS_SERVER1_IP_DESC
					"The RADIUS Server 2 IP Address entered is invalid",	//INVALID_RADIUS_SERVER2_IP
					"The Radius Server 2 IP Address cannot be zero or blank",	//ZERO_RADIUS_SERVER2_IP
					"Radius Server 2 IP Address",	//RADIUS_SERVER2_IP_DESC
					"The IP Address entered is invalid (IP Range: 1~254)",	//INVALID_STATIC_DHCP_IP
					"Please enter another IP Address",	//ZERO_STATIC_DHCP_IP
					"Please enter another Name",	//STATIC_DHCP_NAME
					"The Server IP Address entered is invalid",	//INVALID_SERVER_IP
					"The Server IP Address cannot be zero or blank",	//ZERO_SERVER_IP
					"Server IP Address",	//SERVER_IP_DESC
					"The Passwords entered do not match",	//MATCH_WIZARD_PWD_ERROR
					"The Source Start IP Address entered is invalid",	//INVALID_SOURCE_START_IP
					"The Source Start IP Address cannot be zero or blank",	//ZERO_SOURCE_START_IP
					"Source Start IP Address",	//SOURCE_START_IP_DESC
					"The Source End IP Address entered is invalid",	//INVALID_SOURCE_END_IP
					"The Source End IP Address cannot be zero or blank",	//ZERO_SOURCE_END_IP
					"Source End IP Address",	//SOURCE_END_IP_DESC
					"The Destination Start IP Address entered is invalid",	//INVALID_DEST_START_IP
					"The Destination Start IP Address cannot be zero or blank",	//ZERO_DEST_START_IP
					"Destination Start IP Address",	//DEST_START_IP_DESC
					"The Destination End IP Address entered is invalid",	//INVALID_DEST_END_IP
					"The Destination End IP Address cannot be zero or blank",	//ZERO_DEST_END_IP
					"Destination End IP Address",	//DEST_END_IP_DESC
					"The length of the Passphrase must be between 8 and 63 characters",	//PSK_OVER_LEN
					"Reset JumpStart?",	//RESET_JUMPSTAR
					"Are you sure that you want to delete this rule?",	//DEL_RULE_MSG
					"Are you sure that you want to delete this schedule?",	// DEL_SCHEDULE_MSG
					"Unable to add another schedule",			// ADD_SCHEDULE_ERROR
					"Schedule Name can not empty",	//SCHEDULE_NAME_ERROR
					"Schedule Name can not enter all space",	//SCHEDULE_NAME_SPACE_ERROR
					"The Start Time entered is invalid",	// START_TIME_ERROR
					"The End Time entered is invalid",	// END_TIME_ERROR,
					"The Start Time cannot be greater than the End Time",	//	TIME_RANGE_ERROR
					"Please select a machine first",		// SELECT_MACHINE_ERROR
					"Please select an Application Name first",	// SELECT_APPLICATION_ERROR
					"Please select a Computer Name first",		// SELECT_COMPUTER_ERROR
					"Please enter another Wireless Security Password",	// SECURITY_PWD_ERROR
					"The Parental Control rule entered is already in the list",		//	DUPLICATE_URL_ERROR
					"Login Name error",  //LOGIN_NAME_ERROR
					"Login Password error",	//LOGIN_PASS_ERROR
					"%s is conflicted with LAN IP address, please enter again.",	// THE_SAME_LAN_IP
					"The PSK should Hex.",	// THE_PSK_IS_HEX
					"The IP Address and the reservation IP Address are not in the same subnet.",	// SER_NOT_SAME_DOMAIN
					"There is unsaved data on this page. Do you want to abandon it?\n IF not, press Cancel and then click Save Settings.\nIf so, press OK.",	//IS_CHANGE_DATA
					"The confirmed password does not match the new User password",	//DDNS_PASS_ERROR_MARTH
					"Nothing has changed, save anyway?",		//FORM_MODIFIED_CHECK
					"Rule name can not be empty string!",		//INBOUND_NAME_ERROR
					"The Ending Port number must be greater than the Starting Port number",	//PORT_RANGE_ERROR
					"Are you sure that you want to enable/disable",	//CHECK_ENABLE
					"Are you sure that you want to delete ",	//DEL_MSG
					"You must abandon all your changes in order to define a new schedule.\n Press 'Ok' to abandon these changes and display the Schedule page.\n Otherwise press 'Cancel'.", //GO_SCHEDULE
					"Please enter user name" //PPP_USERNAME_EMPTY
				);
var INVALID_IP_ADDRESS = 0;
var ZERO_IP_ADDRESS = 1;
var IP_ADDRESS_DESC = 2;
var INVALID_MASK_ADDRESS = 3;
var ZERO_MASK_ADDRESS = 4;
var MASK_ADDRESS_DESC = 5;
var INVALID_GATEWAY_ADDRESS = 6;
var ZERO_GATEWAY_ADDRESS = 7;
var GATEWAY_ADDRESS_DESC = 8;
var NOT_SAME_DOMAIN = 9;
var INVALID_START_IP = 10;
var ZERO_START_IP = 11;
var START_IP_DESC = 12;
var START_INVALID_DOMAIN = 13;
var INVALID_END_IP = 14;
var ZERO_END_IP = 15;
var END_IP_DESC = 16;
var END_INVALID_DOMAIN = 17;
var INVALID_DNS_ADDRESS = 18;
var ZERO_DNS_ADDRESS = 19;
var DNS_ADDRESS_DESC = 20;
var SSID_EMPTY_ERROR = 21;
var AUTH_TYPE_ERROR = 22;
var PSK_LENGTH_ERROR = 23;
var PSK_MATCH_ERROR = 24;
var MATCH_PWD_ERROR = 25;
var WEP_KEY_EMPTY = 26;
var WIZARD_KEY_EMPTY = 27;
var QUIT_WIZARD = 28;
var MAC_ADDRESS_ERROR = 29;
var IP_RANGE_ERROR = 30;
var INVALID_SEC_DNS_ADDRESS = 31;
var ZERO_SEC_DNS_ADDRESS = 32;
var SEC_DNS_ADDRESS_DESC = 33;
var ADMIN_PASS_ERROR = 34;
var USER_PASS_ERROR = 35;
var DDNS_SERVER_ERROR = 36;
var DDNS_HOST_ERROR = 37;
var DDNS_USER_ERROR = 38;
var DDNS_PASS_ERROR = 39;
var RESTORE_DEFAULT = 40;
var REBOOT_ROUTER = 41;
var LOAD_SETTING = 42;
var LOAD_FILE_ERROR = 43;
var DOWNLOAD_SETTING_ERROR = 44;
var PING_IP_ERROR = 45;
var SMTP_SERVER_ERROR = 46;
var EMAIL_ADDRESS_ERROR = 47;
var DEL_SERVER_MSG = 48;
var DEL_APPLICATION_MSG = 49;
var DEL_FILTER_MSG = 50;
var DEL_ROUTE_MSG = 51;
var DEL_MAC_MSG = 52;
var DEL_KEYWORD_MSG = 53;
var DEL_DOMAIN_MSG = 54;
var DEL_ENTRY_MSG = 55;
var DEL_STATIC_DHCP_MSG = 56;
var NAME_ERROR = 57;
var TRIGGER_PORT_ERROR = 58;
var PUBLIC_PORT_ERROR = 59;
var PRIVATE_PORT_ERROR = 60;
var PRIVATE_IP_ERROR = 61;
var PORT_ERROR = 62;
var DEL_KEYWORD_ERROR = 63;
var SAME_KEYWORD_ERROR = 64;
var KEYWORD_ERROR = 65;
var ADD_KEYWORD_ERROR = 66;
var DEL_BLOCK_DOMAIN_ERROR = 67;
var DEL_PERMIT_DOMAIN_ERROR = 68;
var SAME_BLOCK_DOMAIN = 69;
var BLOCK_DOMAIN_ERROR = 70;
var ADD_BLOCK_DOMAIN_ERROR = 71;
var SAME_PERMIT_DOMAIN = 72;
var PERMIT_DOMAIN_ERROR = 73;
var ADD_PERMIT_DOMAIN_ERROR = 74;
var FIRMWARE_UPGRADE_ERROR = 75;
var DOMAIN_ERROR = 76;
var ADD_CONTROL_DOMAIN_ERROR = 77;
var DEL_CONTROL_DOMAIN_ERROR = 78;
var CONTROL_DOMAIN_ERROR = 79;
var INVALID_RADIUS_SERVER1_IP = 80;
var ZERO_RADIUS_SERVER1_IP = 81;
var RADIUS_SERVER1_IP_DESC = 82;
var INVALID_RADIUS_SERVER2_IP = 83;
var ZERO_RADIUS_SERVER2_IP = 84;
var RADIUS_SERVER2_IP_DESC = 85;
var INVALID_STATIC_DHCP_IP = 86;
var ZERO_STATIC_DHCP_IP = 87;
var STATIC_DHCP_NAME = 88;
var INVALID_SERVER_IP = 89;
var ZERO_SERVER_IP = 90;
var SERVER_IP_DESC = 91;
var MATCH_WIZARD_PWD_ERROR = 92;
var INVALID_SOURCE_START_IP = 93;
var ZERO_SOURCE_START_IP = 94;
var SOURCE_START_IP_DESC = 95;
var INVALID_SOURCE_END_IP = 96;
var ZERO_SOURCE_END_IP = 97;
var SOURCE_END_IP_DESC = 98;
var INVALID_DEST_START_IP = 99;
var ZERO_DEST_START_IP = 100;
var DEST_START_IP_DESC = 101;
var INVALID_DEST_END_IP = 102;
var ZERO_DEST_END_IP = 103;
var DEST_END_IP_DESC = 104;
var PSK_OVER_LEN = 105;
var RESET_JUMPSTAR = 106;
var DEL_RULE_MSG = 107;
var DEL_SCHEDULE_MSG = 108;
var ADD_SCHEDULE_ERROR = 109;
var SCHEDULE_NAME_ERROR = 110
var SCHEDULE_NAME_SPACE_ERROR = 111;
var START_TIME_ERROR = 112;
var END_TIME_ERROR = 113;
var TIME_RANGE_ERROR = 114;
var SELECT_MACHINE_ERROR = 115;
var SELECT_APPLICATION_ERROR = 116;
var SELECT_COMPUTER_ERROR = 117;
var SECURITY_PWD_ERROR = 118;
var DUPLICATE_URL_ERROR = 119;
var LOGIN_NAME_ERROR = 120;
var LOGIN_PASS_ERROR = 121;
var THE_SAME_LAN_IP = 122;
var THE_PSK_IS_HEX = 123;
var SER_NOT_SAME_DOMAIN = 124;
var IS_CHANGE_DATA = 125;
var DDNS_PASS_ERROR_MARTH = 126;
var FORM_MODIFIED_CHECK = 127;
var INBOUND_NAME_ERROR = 128;
var PORT_RANGE_ERROR = 129;
var CHECK_ENABLE = 130;
var DEL_MSG = 131;
var GO_SCHEDULE = 132;
var PPP_USERNAME_EMPTY = 133;
*/
var default_virtual = new Array(new rule_obj(get_words('gw_vs_0'), "6", 23, 23),
							 				new rule_obj(get_words('gw_vs_1'), "6", 80, 80),
							 				new rule_obj(get_words('gw_vs_2'), "6", 443, 443),
							 				new rule_obj(get_words('_FTP'), "6", 21, 21),
							 				new rule_obj(get_words('gw_vs_4'), "17", 53, 53),
							 				new rule_obj(get_words('gw_vs_5'), "6", 25, 25),
							 				new rule_obj(get_words('gw_vs_6'), "6", 110, 110),
							 				new rule_obj(get_words('_H323'), "6", 1720, 1720),
							 				new rule_obj(get_words('gw_vs_8'), "6", 3389, 3389),
							 				new rule_obj(get_words('_PPTP'), "6", 1723, 1723),
							 				new rule_obj(get_words('_L2TP'), "17", 1701, 1701),
							 				new rule_obj(get_words('_WOL'), "17", 9, 9)
						  );


var default_rule = new Array(new rule_obj("Age of Empires", "TCP", "2302-2400,6073", "2302-2400,6073"),
							 new rule_obj("Aliens vs. Predator", "TCP", "80,2300-2400,8000-8999", "80,2300-2400,8000-8999"),
							 new rule_obj("America's Army", "TCP", "20045", "1716-1718,8777,27900"),
							 new rule_obj("Asheron's Call", "TCP", "9000-9013", "2001,9000-9013"),
							 new rule_obj("Battlefield 1942", "TCP", "", "14567,22000,23000-23009,27900,28900"),
							 new rule_obj("Battlefield 2", "TCP", "80,4711,29900,29901,29920,28910", "1500-4999,16567,27900,29900,29910,27901,55123,55124,55215"),
							 new rule_obj("Battlefield: Vietnam", "TCP", "", "4755,23000,22000,27243-27245"),
							 new rule_obj("BitTorrent", "TCP", "6881-6889", ""),
							 new rule_obj("Black and White", "TCP", "2611-2612,6500,6667,27900", "2611-2612,6500,6667,27900"),
							 new rule_obj("Call of Duty", "TCP", "28960", "20500,20510,28960"),
							 new rule_obj("Command and Conquer Generals", "TCP", "80,6667,28910,29900,29920", "4321,27900"),
							 new rule_obj("Command and Conquer Zero Hour", "TCP", "80,6667,28910,29900,29920", "4321,27900"),
							 new rule_obj("Counter Strike", "TCP", "27030-27039", "1200,27000-27015"),
							 new rule_obj("D-Link DVC-1000", "TCP", "1720,15328-15333", "15328-15333"),
							 new rule_obj("Dark Reign 2", "TCP", "26214", "26214"),
							 new rule_obj("Delta Force", "TCP", "3100-3999", "3568"),
							 new rule_obj("Diablo I and II", "TCP", "6112-6119,4000", "6112-6119"),
							 new rule_obj("Doom 3", "TCP", "", "27666"),
							 new rule_obj("Dungeon Siege", "TCP", "", "6073,2302-2400"),
							 new rule_obj("eDonkey", "TCP", "4661-4662", "4665"),
							 new rule_obj("eMule", "TCP", "4661-4662,4711", "4672,4665"),
							 new rule_obj("Everquest", "TCP", "1024-6000,7000", "1024-6000,7000"),
							 new rule_obj("Far Cry", "TCP", "", "49001,49002"),
							 new rule_obj("Final Fantasy XI (PC)", "TCP", "25,80,110,443,50000-65535", "50000-65535"),
							 new rule_obj("Final Fantasy XI (PS2)", "TCP", "1024-65535", "50000-65535"),
							 new rule_obj("Gamespy Arcade", "TCP", "", "6500"),
							 new rule_obj("Gamespy Tunnel", "TCP", "", "6700"),
							 new rule_obj("Ghost Recon", "TCP", "2346-2348", "2346-2348"),
							 new rule_obj("Gnutella", "TCP", "6346", "6346"),
							 new rule_obj("Half Life", "TCP", "6003,7002", "27005,27010,27011,27015"),
							 new rule_obj("Halo: Combat Evolved", "TCP", "", "2302,2303"),
							 new rule_obj("Heretic II", "TCP", "28910", "28910"),
							 new rule_obj("Hexen II", "TCP", "26900", "26900"),
							 new rule_obj("Jedi Knight II: Jedi Outcast", "TCP", "", "28060,28061,28062,28070-28081"),
							 new rule_obj("Jedi Knight III: Jedi Academy", "TCP", "", "28060,28061,28062,28070-28081"),
							 new rule_obj("KALI", "TCP", "", "2213,6666"),
							 new rule_obj("Links", "TCP", "2300-2400,47624", "2300-2400,6073"),
							 new rule_obj("Medal of Honor: Games", "TCP", "12203-12204", ""),
							 new rule_obj("MSN Game Zone", "TCP", "6667", "28800-29000"),
							 new rule_obj("MSN Game Zone (DX)", "TCP", "2300-2400,47624", "2300-2400"),
							 new rule_obj("Myth", "TCP", "3453", "3453"),
							 new rule_obj("Need for Speed", "TCP", "9442", "9442"),
							 new rule_obj("Need for Speed 3", "TCP", "1030", "1030"),
							 new rule_obj("Need for Speed: Hot Pursuit 2", "TCP", "8511,28900", "1230,8512,27900,61200-61230"),
							 new rule_obj("Neverwinter Nights", "TCP", "", "5120-5300,6500,27900,28900"),
							 new rule_obj("PainKiller", "TCP", "", "3455"),
							 new rule_obj("PlayStation2", "TCP", "4658,4659", "4658,4659"),
							 new rule_obj("Postal 2: Share the Pain", "TCP", "80", "7777-7779,27900,28900"),
							 new rule_obj("Quake 2", "TCP", "27910", "27910"),
							 new rule_obj("Quake 3", "TCP", "27660,27960", "27660,27960"),
							 new rule_obj("Rainbow Six", "TCP", "2346", "2346"),
							 new rule_obj("Rainbow Six: Raven Shield", "TCP", "", "7777-7787,8777-8787"),
							 new rule_obj("Return to Castle Wolfenstein", "TCP", "", "27950,27960,27965,27952"),
							 new rule_obj("Rise of Nations", "TCP", "", "34987"),
							 new rule_obj("Roger Wilco", "TCP", "3782", "27900,28900,3782-3783"),
							 new rule_obj("Rogue Spear", "TCP", "2346", "2346"),
							 new rule_obj("Serious Sam II", "TCP", "25600-25605", "25600-25605"),
							 new rule_obj("Shareaza", "TCP", "6346", "6346"),
							 new rule_obj("Silent Hunter II", "TCP", "3000", "3000"),
							 new rule_obj("Soldier of Fortune", "TCP", "", "28901,28910,38900-38910,22100-23000"),
							 new rule_obj("Soldier of Fortune II: Double Helix", "TCP", "", "20100-20112"),
							 new rule_obj("Splinter Cell: Pandora Tomorrow", "TCP", "40000-43000", "44000-45001,7776,8888"),
							 new rule_obj("Star Trek: Elite Force II", "TCP", "", "29250,29256"),
							 new rule_obj("Starcraft", "TCP", "6112-6119,4000", "6112-6119"),
							 new rule_obj("Starsiege Tribes", "TCP", "", "27999,28000"),
							 new rule_obj("Steam", "TCP", "27030-27039", "1200,27000-27015"),
							 new rule_obj("SWAT 4", "TCP", "", "10480-10483"),
							 new rule_obj("TeamSpeak", "TCP", "", "8767"),
							 new rule_obj("Tiberian Sun", "TCP", "1140-1234,4000", "1140-1234,4000"),
							 new rule_obj("Tiger Woods 2K4", "TCP", "80,443,1791-1792,13500,20801-20900,32768-65535", "80,443,1791-1792,13500,20801-20900,32768-65535"),
							 new rule_obj("Tribes of Vengeance", "TCP", "7777,7778,28910", "6500,7777,7778,27900"),
							 new rule_obj("Ubi.com", "TCP", "40000-42999", "41005"),
							 new rule_obj("Ultima", "TCP", "5001-5010,7775-7777,7875,8800-8900,9999", "5001-5010,7775-7777,7875,8800-8900,9999"),
							 new rule_obj("Unreal", "TCP", "7777,8888,27900", "7777-7781"),
							 new rule_obj("Unreal Tournament", "TCP", "7777-7783,8080,27900", "7777-7783,8080,27900"),
							 new rule_obj("Unreal Tournament 2004", "TCP", "28902", "7777-7778,7787-7788"),
							 new rule_obj("Vietcong", "TCP", "", "5425,15425,28900"),
							 new rule_obj("Warcraft II", "TCP", "6112-6119,4000", "6112-6119"),
							 new rule_obj("Warcraft III", "TCP", "6112-6119,4000", "6112-6119"),
							 new rule_obj("WinMX", "TCP", "6699", "6257"),
							 new rule_obj("Wolfenstein: Enemy Territory", "TCP", "", "27950,27960,27965,27952"),
							 new rule_obj("WON Servers", "TCP", "27000-27999", "15001,15101,15200,15400"),
							 new rule_obj("World of Warcraft", "TCP", "3724,6112,6881-6999", ""),
							 new rule_obj("Xbox Live", "TCP", "3074", "88,3074")
						  );

var default_appl = new Array(new appl_obj("AIM Talk", "0", "4099", "0", "5190"),
							 new appl_obj("BitTorrent", "0", "6969", "0", "6881-6889"),
							 new appl_obj("Calista IP phone", "0", "5190", "1", "3000"),
							 new appl_obj("ICQ", "1", "4000", "0", "20000,20019,20039,20059"),
							 new appl_obj("PalTalk", "0", "5001-5020", "2", "2090,2091,2095")
							);
							
var default_QoSappl = new Array(new Qosappl_obj("bittorrent", "Bittorrent", "P2P filesharing / publishing tool - http://www.bittorrent.com", "6881-6889",""),
							 new Qosappl_obj("cvs", "CVS", "Concurrent Versions System","2401","2401"),
							 new Qosappl_obj("smtp", "SMTP", "Simple Mail Transfer Protocol - RFC 2821 (See also RFC 1869)","25","25"),
							 new Qosappl_obj("snmp", "SNMP", "Simple Network Management Protocol - RFC 1157","161,162","161,162"),
							 new Qosappl_obj("qq", "Tencent QQ Protocol", "Chinese instant messenger protocol - http://www.qq.com","80","8000"),
							 new Qosappl_obj("yahoo", "Yahoo messenger", "an instant messenger protocol - http://yahoo.com","5050",""),
							 new Qosappl_obj("subspace", "Subspace", "2D asteroids-style space game - http://sscentral.com","",""),
							 new Qosappl_obj("mute", "MUTE", "P2P filesharing - http://mute-net.sourceforge.net","4900,5100",""),
							 new Qosappl_obj("vnc", "VNC", "Virtual Network Computing.  Also known as RFB - Remote Frame Buffer","5800,5900",""),
							 new Qosappl_obj("jabber", "Jabber (XMPP)", "open instant messenger protocol - RFC 3920 - http://jabber.org","5222,5223,5269,8010",""),
							 new Qosappl_obj("doom3", "Doom 3", "computer game","27650,27666","27650,27666"),
							 new Qosappl_obj("ftp", "FTP", "File Transfer Protocol - RFC 959","21",""),
							 new Qosappl_obj("thecircle", "The Circle", "P2P application - http://thecircle.org.au","",""),
							 new Qosappl_obj("cimd", "CIMD", "Computer Interface to Message Distribution, an SMSC protocol by Nokia","3000",""),
							 new Qosappl_obj("teamfortress2", "Team Fortress 2", "network game - http://www.valvesoftware.com","","3478,4379,4380,27000-27015"),
							 new Qosappl_obj("http-rtsp", "RTSP in HTTP", "RTSP tunneled within HTTP","554","554"),
							 new Qosappl_obj("applejuice", "Apple Juice", "P2P filesharing - http://www.applejuicenet.de","9022,9851",""),
							 new Qosappl_obj("imap", "IMAP", "Internet Message Access Protocol (A common e-mail protocol)","143,993",""),
							 new Qosappl_obj("ssl", "SSL and TLS", "Secure Socket Layer / Transport Layer Security - RFC 2246","443",""),
							 new Qosappl_obj("h323", "H.323", "Voice over IP.","1720",""),
							 new Qosappl_obj("shoutcast", "Shoutcast and Icecast", "streaming audio","",""),
							 new Qosappl_obj("http", "HTTP", "HyperText Transfer Protocol - RFC 2616","80","80"),
							 new Qosappl_obj("mohaa", "Medal of Honor Allied Assault", "an Electronic Arts game","12203-12218,12300,22000,23000-23009","12203-12218,12300,22000,23000-23009"),
							 new Qosappl_obj("smb", "Samba/SMB", "Server Message Block - Microsoft Windows filesharing","139,445","137,138"),
							 new Qosappl_obj("dns", "DNS", "Domain Name System - RFC 1035","","53"),
							 new Qosappl_obj("imesh", "iMesh", "the native protocol of iMesh, a P2P application - http://imesh.com","",""),
							 new Qosappl_obj("nbns", "NBNS", "NetBIOS name service","137","137"),
							 new Qosappl_obj("directconnect", "Direct Connect", "P2P filesharing - http://www.neo-modus.com","411,412",""),
							 new Qosappl_obj("soulseek", "Soulseek", "P2P filesharing - http://slsknet.org","6645","6645"),
							 new Qosappl_obj("openft", "OpenFT", "P2P filesharing (implemented in giFT library)","1215,1216","1215,1216"),
							 new Qosappl_obj("hddtemp", "hddtemp", "Hard drive temperature reporting","7634",""),
							 new Qosappl_obj("freenet", "Freenet", "Anonymous information retrieval - http://freenetproject.org","8888",""),
							 new Qosappl_obj("aim", "AIM", "AOL instant messenger (OSCAR and TOC)","5190",""),
							 new Qosappl_obj("soribada", "Soribada", "A Korean P2P filesharing program/protocol - http://www.soribada.com","36035",""),
							 new Qosappl_obj("skypetoskype", "Skype to Skype", "UDP voice call (program to program) - http://skype.com","",""),
							 new Qosappl_obj("ssdp", "SSDP", "Simple Service Discovery Protocol - easy discovery of network devices","1900",""),
							 new Qosappl_obj("quake-halflife", "HL1, Quake, CS", "Half Life 1 engine games (HL 1, Quake 2/3/World, Counterstrike 1.6, etc.)","27015",""),
							 new Qosappl_obj("code_red", "Code Red", "a worm that attacks Microsoft IIS web servers","80",""),
							 new Qosappl_obj("irc", "IRC", "Internet Relay Chat - RFC 1459","194",""),
							 new Qosappl_obj("gopher", "Gopher", "A precursor to HTTP - RFC 1436","70",""),
							 new Qosappl_obj("xboxlive", "XBox Live", "Console gaming","88,3070",""),
							 new Qosappl_obj("rtp", "RTP", "Real-time Transport Protocol - RFC 3550","","5004"),
							 new Qosappl_obj("bgp", "BGP", "Border Gateway Protocol - RFC 1771","179",""),
							 new Qosappl_obj("ares", "Ares", "P2P filesharing - http://aresgalaxy.sf.net","",""),
							 new Qosappl_obj("aimwebcontent", "AIM web content", "ads/news content downloaded by AOL Instant Messenger","5190-5193",""),
							 new Qosappl_obj("tor", "Tor", "The Onion Router - used for anonymization - http://tor.eff.org","22,443,465,587,993,995,3478,3479,5190,5222,5223,5269,7777",""),
							 new Qosappl_obj("ventrilo", "Ventrilo", "VoIP - http://ventrilo.com","3487","3487"),
							 new Qosappl_obj("rtsp", "RTSP", "Real Time Streaming Protocol - http://www.rtsp.org - RFC 2326","554","554"),
							 new Qosappl_obj("tsp", "TSP", "Berkely UNIX Time Synchronization Protocol","",""),
							 new Qosappl_obj("whois", "Whois", "query/response system, usually used for domain name info - RFC 3912","43",""),
							 new Qosappl_obj("skypeout", "Skype to phone", "UDP voice call (program to POTS phone) - http://skype.com","","1023"),
							 new Qosappl_obj("armagetron", "Armagetron Advanced", "open source Tron/snake based multiplayer game","","4534"),
							 new Qosappl_obj("radmin", "Famatech Remote Administrator", "remote desktop for MS Windows","3389",""),
							 new Qosappl_obj("nntp", "NNTP", "Network News Transfer Protocol - RFCs 977 and 2980","119,443",""),
							 new Qosappl_obj("replaytv-ivs", "ReplayTV Internet Video Sharing", "Digital Video Recorder - http://replaytv.com","",""),
							 new Qosappl_obj("gkrellm", "Gkrellm", "a system monitor - http://gkrellm.net","",""),
							 new Qosappl_obj("nimda", "Nimda", "a worm that attacks Microsoft IIS web servers, and MORE!","80",""),
							 new Qosappl_obj("dayofdefeat-source", "Day of Defeat: Source", "game (Half-Life 2 mod) - http://www.valvesoftware.com","27045",""),
							 new Qosappl_obj("ipp", "IP printing", "a new standard for UNIX printing - RFC 2911","631","631"),
							 new Qosappl_obj("rdp", "RDP", "Remote Desktop Protocol (used in Windows Terminal Services)","3389","3389"),
							 new Qosappl_obj("teamspeak", "TeamSpeak", "VoIP application - http://goteamspeak.com","2008,10011,27015,30033,41144","2010,9987"),
							 new Qosappl_obj("halflife2-deathmatch", "Half", "ife 2 Deathmatch - popular computer game","27015",""),
							 new Qosappl_obj("ciscovpn", "Cisco VPN", "VPN client software to a Cisco VPN server","500","5000,10000"),
							 new Qosappl_obj("counterstrike-source", "Counterstrike (using the new Source engine)", "network game","27015","27015"),
							 new Qosappl_obj("xunlei", "Xunlei", "Chinese P2P filesharing - http://xunlei.com","80","15000"),
							 new Qosappl_obj("edonkey", "eDonkey2000", "P2P filesharing - http://edonkey2000.com and others","4662","12155"),
							 new Qosappl_obj("finger", "Finger", "User information server - RFC 1288","79",""),
							 new Qosappl_obj("pop3", "POP3", "Post Office Protocol version 3 (popular e-mail protocol) - RFC 1939","110",""),
							 new Qosappl_obj("ssh", "SSH", "Secure SHell","22",""),
							 new Qosappl_obj("dhcp", "DHCP", "Dynamic Host Configuration Protocol - RFC 1541","546,547","67,68,546,547"),
							 new Qosappl_obj("worldofwarcraft", "World of Warcraft", "popular network game - http://blizzard.com/","3724,6112,6881-6999",""),
							 new Qosappl_obj("socks", "SOCKS Version 5", "Firewall traversal protocol - RFC 1928","1080",""),
							 new Qosappl_obj("ident", "Ident", "Identification Protocol - RFC 1413","113",""),
							 new Qosappl_obj("fasttrack", "FastTrack", "P2P filesharing (Kazaa, Morpheus, iMesh, Grokster, etc)","",""),
							 new Qosappl_obj("subversion", "Subversion", "a version control system","3609",""),
							 new Qosappl_obj("telnet", "Telnet", "Insecure remote login - RFC 854","23","23"),
							 new Qosappl_obj("gnucleuslan", "GnucleusLAN", "LAN-only P2P filesharing","",""),
							 new Qosappl_obj("tftp", "TFTP", "Trivial File Transfer Protocol - used for bootstrapping - RFC 1350","","69"),
							 new Qosappl_obj("citrix", "Citrix ICA", "proprietary remote desktop application - http://citrix.com","1949",""),
							 new Qosappl_obj("validcertssl", "Valid Cert. SSL", "Valid certificate SSL","",""),
							 new Qosappl_obj("chikka", "Chikka", "SMS service which can be used without phones - http://chikka.com","6301",""),
							 new Qosappl_obj("ncp", "NCP", "Novell Core Protocol","524","524"),
							 new Qosappl_obj("msnmessenger", "MSN Messenger", "Microsoft Network chat client","1863",""),
							 new Qosappl_obj("gnutella", "Gnutella", "P2P filesharing","6346,6347","6346,6347"),
							 new Qosappl_obj("battlefield1942", "Battlefield 1942", "An EA game","23000",""),
							 new Qosappl_obj("x11", "X Windows Version 11", "Networked GUI system used in most Unices","6000",""),
							 new Qosappl_obj("sip", "SIP", "Session Initiation Protocol - Internet telephony - RFC 3261","5060,5061","5060,5061"),
							 new Qosappl_obj("ntp", "(S)NTP", "(Simple) Network Time Protocol - RFCs 1305 and 2030","","123"),
							 new Qosappl_obj("napster", "Napster", "P2P filesharing","8875-8888","")						
							);

var all_ip_addr_msg = new Array(LangMap.which_lang['MSG006'],	// INVALID_IP
						       LangMap.which_lang['MSG007'],	// ZERO_IP
						       LangMap.which_lang['MSG002'],	// FIRST_IP_ERROR
						       LangMap.which_lang['MSG003'],	// SECOND_IP_ERROR
						       LangMap.which_lang['MSG004'],	// THIRD_IP_ERROR
						       LangMap.which_lang['MSG005'],	// FOURTH_IP_ERROR
						       LangMap.which_lang['MSG026'],			// FIRST_RANGE_ERROR
						       LangMap.which_lang['MSG027'],			// SECOND_RANGE_ERROR
						       LangMap.which_lang['MSG028'],			// THIRD_RANGE_ERROR
						       LangMap.which_lang['MSG029'],		// FOURTH_RANGE_ERROR
						       LangMap.which_lang['MSG008'],		// RADIUS_SERVER_PORT_ERROR
						       LangMap.which_lang['MSG009'],		// RADIUS_SERVER_SECRET_ERROR
						       LangMap.which_lang['MSG010']			// MULTICASE_IP_ERROR
							   );

var subnet_mask_msg = new Array(LangMap.which_lang['ar_alert_5'],	// INVALID_IP
					           LangMap.which_lang['ar_alert_5'],	// ZERO_IP
					           LangMap.which_lang['ar_alert_5'],	// FIRST_IP_ERROR
					    	   LangMap.which_lang['ar_alert_5'],	// SECOND_IP_ERROR
					    	   LangMap.which_lang['ar_alert_5'],	// THIRD_IP_ERROR
					    	   LangMap.which_lang['ar_alert_5'],	// FOURTH_IP_ERROR
					    	   LangMap.which_lang['ar_alert_5'],			// FIRST_RANGE_ERROR
					    	   LangMap.which_lang['ar_alert_5'],			// SECOND_RANGE_ERROR
					    	   LangMap.which_lang['ar_alert_5'],			// THIRD_RANGE_ERROR
					    	   LangMap.which_lang['ar_alert_5']			// FOURTH_RANGE_ERROR
					          );
var metric_msg = new Array(LangMap.which_lang['MSG043'],
						 LangMap.which_lang['MSG043'],
						 LangMap.which_lang['ar_alert_3']
						 );	

var INVALID_IP = 0;
var ZERO_IP = 1;
var FIRST_IP_ERROR = 2;
var SECOND_IP_ERROR = 3;
var THIRD_IP_ERROR = 4;
var FOURTH_IP_ERROR = 5;
var FIRST_RANGE_ERROR = 6;
var SECOND_RANGE_ERROR = 7;
var THIRD_RANGE_ERROR = 8;
var FOURTH_RANGE_ERROR = 9;
var RADIUS_SERVER_PORT_ERROR = 10;	// for radius server
var RADIUS_SERVER_SECRET_ERROR = 11; // for radius server
var MULTICASE_IP_ERROR = 12;
//var IP_RANGE_ERROR = 13;

var check_num_msg = new Array(LangMap.which_lang['MSG012'],
						 LangMap.which_lang['MSG013'],
						 LangMap.which_lang['MSG014'],
						 LangMap.which_lang['MSG015']
						 );

var EMPTY_VARIBLE_ERROR = 0;
var INVALID_VARIBLE_ERROR = 1;
var VARIBLE_RANGE_ERROR = 2;
var EVEN_NUMBER_ERROR = 3;
/** the end of check_varible() using **/
/** for get_key_len_msg() using **/
var key_len_error = new Array(LangMap.which_lang['MSG016'],
							  LangMap.which_lang['MSG017']
							   );

var illegal_key_error = new Array(LangMap.which_lang['TEXT042_1']+LangMap.which_lang['TEXT042_2'],
								  LangMap.which_lang['TEXT042_1']+LangMap.which_lang['TEXT042_2'],
								  LangMap.which_lang['TEXT042_1']+LangMap.which_lang['TEXT042_2'],
								  LangMap.which_lang['TEXT042_1']+LangMap.which_lang['TEXT042_2']								 
								  );
/** the end of get_key_len_msg() using **/	


/** for ipv6 error message as following **/    

var all_ipv6_addr_msg = new Array(LangMap.which_lang['MSG006'],	// INVALID_IP
						       LangMap.which_lang['MSG007'],	// ZERO_IP such as '::' symbol
						       LangMap.which_lang['MSG018'],	// FIRST_IP_ERROR
						       LangMap.which_lang['MSG019'],	// SECOND_IP_ERROR
						       LangMap.which_lang['MSG020'],	// THIRD_IP_ERROR
						       LangMap.which_lang['MSG021'],	// FOURTH_IP_ERROR
						       LangMap.which_lang['MSG022'],	// FIFTH_IP_ERROR
						       LangMap.which_lang['MSG023'],	// SIXTH_IP_ERROR
						       LangMap.which_lang['MSG024'],	// SEVENTH_IP_ERROR
						       LangMap.which_lang['MSG025'],	// EIGHTH_IP_ERROR
						       LangMap.which_lang['MSG026'],		// FIRST_RANGE_ERROR
						       LangMap.which_lang['MSG027'],		// SECOND_RANGE_ERROR
						       LangMap.which_lang['MSG028'],		// THIRD_RANGE_ERROR
						       LangMap.which_lang['MSG029'],		// FOURTH_RANGE_ERROR
						       LangMap.which_lang['MSG030'],		// FIFTH_RANGE_ERROR
						       LangMap.which_lang['MSG031'],		// SIXTH_RANGE_ERROR
						       LangMap.which_lang['MSG032'],		// SEVENTH_RANGE_ERROR
						       LangMap.which_lang['MSG033'],		// EIGHTH_RANGE_ERROR
						       "", 						// RADIUS_SERVER_PORT_ERROR
						       "",						// RADIUS_SERVER_SECRET_ERROR
  						       LangMap.which_lang['MSG034'],  // LOOPBACK_IP_ERROR
  						       LangMap.which_lang['MSG035']  // MULTICASE_IP_ERROR	
						      );
 						      
var IPv6_INVALID_IP = 0;
var IPv6_ZERO_IP = 1;
var IPv6_FIRST_IP_ERROR = 2;
var IPv6_SECOND_IP_ERROR = 3;
var IPv6_THIRD_IP_ERROR = 4;
var IPv6_FOURTH_IP_ERROR = 5;
var IPv6_FIFTH_IP_ERROR =6;
var IPv6_SIXTH_IP_ERROR =7;
var IPv6_SEVENTH_IP_ERROR =8;
var IPv6_EIGHTH_IP_ERROR=9;
var IPv6_FIRST_RANGE_ERROR = 10;
var IPv6_SECOND_RANGE_ERROR = 11;
var IPv6_THIRD_RANGE_ERROR = 12;
var IPv6_FOURTH_RANGE_ERROR = 13;
var IPv6_FIFTH_RANGE_ERROR =14;
var IPv6_SIXTH_RANGE_ERROR =15;
var IPv6_SEVENTH_RANGE_ERROR = 16;
var IPv6_EIGHTH_RANGE_ERROR =17;
var IPv6_RADIUS_SERVER_PORT_ERROR = 18;	// for radius server
var IPv6_RADIUS_SERVER_SECRET_ERROR = 19; // for radius server
var IPv6_LOOPBACK_IP_ERROR =20;	
var IPv6_MULTICASE_IP_ERROR =21;                   
