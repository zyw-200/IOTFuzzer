<?
function wiz_buttons()
{
	echo '<div class="emptyline"></div>\n'.
		 '	<div class="centerline">\n'.
		 '		<input type="button" name="b_pre" value="'.i18n("Prev").'" onClick="PAGE.OnClickPre();" />&nbsp;&nbsp;\n'.
		 '		<input type="button" name="b_next" value="'.i18n("Next").'" onClick="PAGE.OnClickNext();" />&nbsp;&nbsp;\n'.
		 '		<input type="button" name="b_exit" value="'.i18n("Cancel").'" onClick="PAGE.OnClickCancel();" />&nbsp;&nbsp;\n'.
		 '		<input type="button" name="b_send" value="'.i18n("Connect").'" onClick="BODY.OnSubmit();" disabled="true" />&nbsp;&nbsp;\n'.
		 '	</div>\n'.
		 '	<div class="emptyline"></div>';
}
?>
<form id="mainform" onsubmit="return false;">
<!-- Start of Stage Description -->
<div id="stage_descv6" class="blackbox" style="display:none;">
	<h2><?echo i18n("WELCOME TO THE D-LINK IPv6 INTERNET CONNECTION SETUP WIZARD");?></h2>
	<div><p class="strong">
		<?echo i18n("This wizard will guide you through a step-by-step process to configure your new D-Link router and connect to the IPv6 Internet.");?>
	</p></div>
	<div>
		<ul>
			<li><?echo i18n("Step 1").": ".i18n("Configure your IPv6 Internet Connection");?></li>
			<li><?echo i18n("Step 2").": ".i18n("Save Settings and Connect");?></li>			
		</ul>
	</div>
	<? wiz_buttons();?>	
</div>
<!-- End of Stage Description -->
<!-- Start of Stage Wan Detect -->
<div id="stage_wan_detectv6" style="display:none;">
	<div id="wan_detectv6" style="display:none;">
		<div class="blackbox"> 
			<h2><?echo i18n("Step 1").": ".i18n("CONFIGURE YOUR IPv6 INTERNET CONNECTION");?></h2>
			<div><p class="strong">
				<?echo i18n("Router is detecting your IPv6 Internet connection type, please wait ...");?>...
			</p></div>
			<div align="center">
				<img src="/pic/wan_detect_process_bar.gif" width="300" height="30">
			</div>
			<? wiz_buttons();?>
		</div>
	</div>
	<div id="wantypev6_unkown" style="display:none;">
		<div class="blackbox"> 
			<h2><?echo i18n("Step 1").": ".i18n("CONFIGURE YOUR IPv6 INTERNET CONNECTION");?></h2>
			<div><p class="strong">
				<?echo i18n("Router is unable to detect your IPv6 Internet connection type.");?>
			</p></div>
			<div class="gap"></div>
			<div class="centerline">
				<input type="button" value="<?echo i18n("Cancel");?>" onClick="PAGE.OnClickCancel();" />&nbsp;&nbsp;
				<input type="button" value="<?echo i18n("Try again");?>" onClick="PAGE.WanDetectPre();" />&nbsp;&nbsp;
				<input type="button" id="b_next" value="<?echo i18n("Guide me through the IPv6 settings");?>" onClick="PAGE.OnClickNext();" />&nbsp;&nbsp;
			</div>
			<div class="gap"></div>	
		</div>
	</div>		
</div>
<!-- End of Stage Wan Detect -->
<!-- Start of Stage Ethernet -->
<div id="stage_etherv6" style="display:none;">
<div class="blackbox">
	<h2><?echo i18n("Step 1").": ".i18n("CONFIGURE YOUR IPv6 INTERNET CONNECTION");?></h2>
	<div class="gap"></div>
	<div><p class="strong">
		<?echo i18n("Please select your IPv6 Internet Connection type:");?>
	</p></div>
	<div class="wiz-l1">
		<input name="wanv6_mode" type="radio" value="PPPoE" onClick="PAGE.OnChangeWanv6Type(this.value);" />
		<?echo i18n("IPv6")." ".i18n("over")." ".i18n("PPPoE");?>
	</div>
	<div class="wiz-l2">
		<?echo i18n("Choose this option if your IPv6 Internet connection requires a username and password to get online. Most DSL modems use this type of connection.");?>
	</div>
	<div class="wiz-l1">
		<input name="wanv6_mode" type="radio" value="STATIC" onClick="PAGE.OnChangeWanv6Type(this.value);" />
		<?echo i18n("Static IPv6 address and Route");?>
	</div>
	<div class="wiz-l2">
		<?echo i18n("Choose this option if your Internet Setup Provider ")."(".i18n("ISP").")".i18n(" provided you with IPv6 Address information that has to be manually configured.");?>
	</div>
	<div class="wiz-l1">
		<input name="wanv6_mode" type="radio" value="6RD" onClick="PAGE.OnChangeWanv6Type(this.value);" />
		<?echo i18n("Tunneling Connection ")."(".i18n("6rd").")";?>
	</div>
	<div class="wiz-l2">
		<?echo i18n("Choose this option if your Internet Setup Provider ")."(".i18n("ISP").")".i18n(" provided you a IPv6 Internet Connection by using 6rd automatic tunneling mechanism.");?>
	</div>
	<? wiz_buttons();?>
</div>
</div>
<!-- End of Stage Ethernet -->
<!-- Start of Stage Ethernet WAN Settings -->
<div id="stage_ether_cfgv6" style="display:none;">
	<input id="ppp4_timeout" type="hidden" />
	<input id="ppp4_mode" type="hidden" />
	<input id="ppp4_mtu" type="hidden" />
	<input id="ipv4_mtu" type="hidden" />
	<!-- Start of DHCP -->
<!--
	<div id="DHCP">
		<div class="blackbox">
			<h2><?echo i18n("DHCP Connection")." (".i18n("Dynamic IP Address").")";?></h2>
			<div><p class="strong">
				<?echo i18n("To set up this connection, please make sure that you are connected to the D-Link Router with the PC that was originally connected to your broadband connection.")." ".
						i18n("If you are, then click the Clone MAC button to copy your computer's MAC Address to the D-Link Router.");?>
			</p></div>
			<div class="textinput">
				<span class="name"><?echo i18n("MAC Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_dhcp_mac" type="text" size="20" maxlength="17" />
					(<?echo i18n("optional");?>)
				</span>
			</div>
			<div class="textinput">
				<span class="name"></span>
				<span class="delimiter"></span>
				<span class="value">
					<input type="button" value="<?echo i18n("Clone Your PC's MAC Address");?>" onClick="PAGE.OnClickCloneMAC();" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Host Name");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_dhcp_host" type="text" size="25" maxlength="39" />
				</span>
			</div>
			<div id="DHCPPLUS" style="display:none">
				<div class="textinput">
					<span class="name"><?echo i18n("Username");?></span>
					<span class="delimiter">:</span>
					<span class="value">
						<input id="wiz_dhcpplus_user" type="text" size="25" maxlength="63" />
					</span>
				</div>
				<div class="textinput">
					<span class="name"><?echo i18n("Password");?></span>
					<span class="delimiter">:</span>
					<span class="value">
						<input id="wiz_dhcpplus_pass" type="password" size="25" maxlength="63" />
					</span>
				</div>
			</div>
			<? wiz_buttons();?>
		</div>
	</div>
-->
	<!-- End of DHCP -->
	<!-- Start of PPPoE -->
	<div id="PPPoE">
		<div class="blackbox">
			<h2><?echo i18n("SET USERNAME AND PASSWORD CONNECTION")." (".i18n("PPPoE").")";?></h2>
			<div><p class="strong">
				<?echo i18n("To set up this connection you will need to have a Username and Password from your IPv6 Internet Service Provider.")." ".
						i18n("If you do not have this information, please contact your ISP.");?>
			</p></div>
			<div class="textinput">
				<span class="name"><?echo i18n("PPPoE Session");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input name="wiz_pppoe_sess_type" type="radio" value="dynamic" checked onChange="PAGE.OnChangePPPoESessType();" />
					<?echo i18n("Share with IPv4");?>
					<input name="wiz_pppoe_sess_type" type="radio" value="static" onChange="PAGE.OnChangePPPoESessType();" />
					<?echo i18n("Create a new session");?>
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("User Name");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pppoe_usr" type="text" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pppoe_passwd" type="password" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Verify Password");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pppoe_passwd2" type="password" size="20" maxlength="63" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Service Name");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_pppoe_svc" type="text" size="20" maxlength="39" />
					(<?echo i18n("optional");?>)
				</span>
			</div>
			<p>
				<?echo i18n("Note:")." ".i18n("You may also need to provide a Service Name.")." ".
						i18n("If you do not have or know this information, please contact your ISP.");?>
			</p>
			<div class="gap"></div>
			<? wiz_buttons();?>
			<div class="gap"></div>		
		</div>
	</div>
	<!-- End of PPPoE -->
	<!-- Start of STATIC -->
	<div id="STATIC">
		<div class="blackbox">
			<h2><?echo i18n("SET STATIC IPv6 ADDRESS CONNECTION");?></h2>
			<div><p class="strong">
				<?echo i18n("To set up this connection you will need to have a complete list of IPv6 information provided by your IPv6 Internet Service Provider.")." ".
						i18n("If you have a Static IPv6 connection and do not have this information, please contact your ISP.");?>
			</p></div>
			<div class="textinput">
				<span class="name"><?echo i18n("Use Link-Local Address");?></span>
				<span class="delimiter">:</span>
				<span class="value"><input id="usell" value="" type="checkbox" onClick="PAGE.OnClickUsell();"/></span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("IPv6 Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_wan_v6addr" type="text" size="42" maxlength="45" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Subnet Prefix Length");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_pfxlen" type="text" size="4" maxlength="3" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Default Gateway");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_gw" type="text" size="42" maxlength="45" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Primary IPv6 DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_pridns6" type="text" size="42" maxlength="45" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Secondary IPv6 DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_secdns6" type="text" size="42" maxlength="45" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("LAN IPv6 Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_lan_v6addr" type="text" size="42" maxlength="45" />
					<span id="wiz_static_lan_pfxlen"></span>
				</span>
			</div>
			<br>
			<div class="gap"></div>
			<? wiz_buttons();?>
			<div class="gap"></div>		
		</div>		
<!--	
		<div class="blackbox" style="margin-top:0px;">
			<h2><?echo i18n("DNS Settings");?></h2>
			<div class="textinput">
				<span class="name"><?echo i18n("Primary DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_dns1" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Secondary DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_static_dns2" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<? wiz_buttons();?>
		</div>
-->
	</div>
	<!-- End of STATIC -->
	<!-- Start of 6rd -->
	<div id="6RD">
		<div class="blackbox">
			<h2><?echo i18n("SET UP 6rd TUNNELING CONNECTION");?></h2>
			<div><p class="strong">
				<?echo i18n("To set up this 6rd tunneling connection you will need to have the following information from your IPv6 Internet Service Provider.")." ".
						i18n("If you do not have this information, please contact your ISP.");?>
			</p></div>
			<div class="textinput">
				<span class="name"><?echo i18n("6rd IPv6 Prefix");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="wiz_6rd_prefix" type="text" size="20" maxlength="39" />	/
					<input id="wiz_6rd_pfxlen" type="text" size="4" maxlength="3" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("IPv4 Address");?></span>
				<span class="delimiter">:</span>
				<span>
					<input id="wiz_6rd_v4addr" type="text" size="15" maxlength="15" />
				</span>
				<span style="font-weight:bold;"><?echo i18n("Mask Length");?>
				:
				<input id="wiz_6rd_v4addr_mask" type="text" size="3" maxlength="2" /></span>	
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Assigned IPv6 Prefix");?></span>
				<span class="delimiter">:</span>
				<span class="value"><span id="wiz_6rd_v6addr"></span></span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("6rd Border Relay IPv4 Address");?></span>
				<span class="delimiter">:</span>
				<span class="value"><input id="wiz_6rd_relay" type="text" size="15" maxlength="15" /></span></span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("IPv6 DNS Server");?></span>
				<span class="delimiter">:</span>
				<span class="value"><input id="wiz_6rd_pridns6" type="text" size="42" maxlength="45" /></span>
			</div>
			<br>
			<div class="gap"></div>
			<? wiz_buttons();?>
			<div class="gap"></div>		
		</div>			
	</div>
	<!-- End of 6RD -->
<!--
	<div id="DNS">
		<div class="blackbox" style="margin-top:0px;">
			<h2><?echo i18n("DNS Settings");?></h2>
			<div class="textinput">
				<span class="name"><?echo i18n("Primary DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="dns1" type="text" size="20" maxlength="15" />
				</span>
			</div>
			<div class="textinput">
				<span class="name"><?echo i18n("Secondary DNS Address");?></span>
				<span class="delimiter">:</span>
				<span class="value">
					<input id="dns2" type="text" size="20" maxlength="15" />
				</span>
			</div>			
			<div class="gap"></div>
			<? wiz_buttons();?>
			<div class="gap"></div>		
		</div>	
	</div>
-->	
</div>
<!-- End of Stage Ethernet WAN Settings -->
<!-- Start of Stage Finish -->
<div id="stage_finishv6" class="blackbox" style="display:none;">
	<h2><?echo i18n("SETUP COMPLETE!");?></h2>
	<div><p class="strong">
		<?echo i18n("The IPv6 Internet Connection Setup Wizard has completed.")." ".
				i18n("Click the Connect button to save your settings and reboot the router.");?>
	</p></div>
	<div class="gap"></div>
	<? wiz_buttons();?>
</div>
<!-- End of Stage Finish -->
</form>
