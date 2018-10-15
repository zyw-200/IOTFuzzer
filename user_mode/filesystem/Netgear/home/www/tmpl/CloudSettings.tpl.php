<?php /* Smarty version 2.6.18, created on 2009-03-02 06:07:21
         compiled from IPSettings.tpl */ ?>
<!--
File name: CloudSettings.tpl.php
Author: Moulidaren, Arada Systems
Date of Creation: 22-Nov-2011.
Function: This file is the template file for the Cloud Page on the GUI.
-->
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'CloudSettings.tpl', 20, false),array('modifier', 'replace', 'CloudSettings.tpl', 31, false),array('function', 'ip_field', 'CloudSettings.tpl', 13, false),)), $this); ?>

<?php if ($this->_tpl_vars['data']['basicSettings']['cloudStatus']):
$fwVersionArr = explode(' ', conf_get('system:monitor:sysVersion'));
$fwVersion = $fwVersionArr[1];
//$upTime = `uptime | cut -f2 -d "p" | cut -f1 -d "l" | sed -e "s/ //g"`;
//$upTimeTrim = substr($upTime,0,strlen($upTime)-2);
$upTime = `cat /proc/uptime | awk '{print $1}' | cut -f1 -d "."`;
endif;
?>

<script language="javascript">
//Method to hide/show the link that redirects to the Cloud server page.
function hideLink(flag){
	if(document.getElementById('cloudLink')){
		if(flag==0)
			document.getElementById('cloudLink').style.display = "none";
		else
			document.getElementById('cloudLink').style.display = "block";
	}
}
</script>

	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<!-- Table header: Cloud Settings -->
					<td colspan="3"><script>tbhdr('Cloud Settings','CloudSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle" >
							<!-- Radio Button to Enable and Disable the Cloud Mode -->
							<!--<?php $this->assign('cloudStatus', $this->_tpl_vars['data']['basicSettings']['cloudStatus']); ?>
<?php if (($this->_tpl_vars['data']['basicSettings']['cloudStatus']) && ($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus'])): ?> 
							<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "ENABLED"), $this);?>
<?php elseif (($this->_tpl_vars['data']['basicSettings']['cloudStatus']) && !($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus'])): ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "DISCONNECTED"), $this);?>
<?php else: ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "DISABLED"), $this);?>
<?php endif; ?>-->
							<!--<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'radio','options' => "1-Enable,0-Disable", 'selectCondition' => "==".($this->_tpl_vars['cloudStatus']), 'disabled' => "true", 'onclick' => "grayOutForCloud(this);hideLink(this.value);"), $this);?>
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'hiddenCloud','name' => 'hiddenCloud','type' => 'hidden','value' => $this->_tpl_vars['cloudStatus']), $this);?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Mode','id' => 'enableMode','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'radio','options' => "1-Enable,0-Disable", 'selectCondition' => "==".($this->_tpl_vars['data']['basicSettings']['cloudStatus'])), $this);?> -->														<?php echo smarty_function_input_row(array('label' => 'Cloud Enabled','id' => 'enableMode','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'radio','options' => "1-YES,0-NO", 'selectCondition' => "==".($this->_tpl_vars['data']['basicSettings']['cloudStatus'])), $this);?>							<?php if ($this->_tpl_vars['data']['basicSettings']['cloudStatus']): ?>							<?php if ($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus']): ?>							<?php echo smarty_function_input_row(array('label' => 'Cloud Activation Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudConnectivityStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "ACTIVATED"), $this);?>							<?php else: ?>							<?php echo smarty_function_input_row(array('label' => 'Cloud Activation Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudConnectivityStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "PENDING"), $this);?>							<?php endif;?>														<?php $connectionStatus=explode(' ',exec("grep opswat.connection_state: /var/pal.cfg"));?>							<?php if ($connectionStatus[1] =='disconnected' || $connectionStatus[1] ==''): ?>							<?php echo smarty_function_input_row(array('label' => 'Cloud Connectivity Status','id' => 'cloudui','name' => 'cloudui','type' => 'text', 'disabled' => "true", 'value' => "DISCONNECTED"), $this);?>							<?php else:?>							<?php echo smarty_function_input_row(array('label' => 'Cloud Connectivity Status','id' => 'connStatus','name' => 'connStatus','type' => 'text' , 'disabled' => "true", 'value' => "CONNECTED"), $this);?>							<?php endif;?>							<?php endif;?>
	 <tr>          <td class="spacerHeight21"></td>      </tr> </table>      <table>               <tr>                  <td>                          <textarea name="activewin" id="activewin" class="smallfix2" cols="50" rows="5" wrap="on" readonly="readonly">NOTE: If Cloud Enabled is set to Yes, this Access Point will periodically attempt to contact NETGEAR Cloud Wireless Server to check if this Access Point has been registered for cloud management.                               </textarea>                   </td>         </tr>   											</table>					</td>					<td class="subSectionBodyDotRight">&nbsp;</td>				</tr>				<tr>					<td colspan="3" class="subSectionBottom">&nbsp;</td>				</tr>			</table>		</td>	</tr>				<tr>		<td class="spacerHeight21"></td>	</tr><tr><?php if ($this->_tpl_vars['data']['basicSettings']['cloudStatus']): ?> 					<!-- Table header: Cloud Settings --><tr>		<td>			<table class="tableStyle">				<tr>					<td colspan="3"><script>tbhdr('IP Settings','currentIPSettings')</script></td>				</tr>				<tr>					<td class="subSectionBodyDot">&nbsp;</td>					<td class="spacer100Percent paddingsubSectionBody">						<table class="tableStyle" >			
<!-- Radio Button to enable or disable Cloud DHCP Client -->
<?php if ($this->_tpl_vars['config']['DHCPCLIENT']['status']): ?>
							<?php $this->assign('dhcpClientStatus', $this->_tpl_vars['data']['basicSettings']['dhcpClientStatus']); ?>
							<?php echo smarty_function_input_row(array('label' => 'DHCP Client','id' => 'enabledhcp','name' => "system[basicSettings][dhcpClientStatus]",'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['dhcpClientStatus'],'selectCondition' => "==".($this->_tpl_vars['dhcpClientStatus']),'onclick' => "grayOutForCloud(this);"), $this);?>

<?php else: ?>
							<?php $this->assign('dhcpClientStatus', '0'); ?>
<?php endif; ?>
							<?php echo smarty_function_input_row(array('label' => 'IP Address','id' => 'ipaddr','name' => "system[basicSettings][ipAddr]",'type' => 'ipfield','value' => $this->_tpl_vars['data']['monitor']['ipAddress'],'disableCondition' => "1==".($this->_tpl_vars['dhcpClientStatus']),'validate' => "IpAddress, (( allowZero: false ))^Presence"), $this);?>


							<?php echo smarty_function_input_row(array('label' => 'IP Subnet Mask','id' => 'subnetmask','name' => "system[basicSettings][netmaskAddr]",'type' => 'ipfield','value' => $this->_tpl_vars['data']['monitor']['subNetMask'],'disableCondition' => "1==".($this->_tpl_vars['dhcpClientStatus']),'validate' => "IpAddress, (( onlyNetMask: true ))^Presence"), $this);?>


<?php if ($this->_tpl_vars['config']['NETWORK_INTEGRALITY']['status']): ?>
							<?php $this->assign('presenceString', "Presence, (( onlyIfChecked: 'cb_networkintegrality' ))^"); ?>
<?php endif; ?>
							<?php echo smarty_function_input_row(array('label' => 'Default Gateway','id' => 'gateway','name' => "system[basicSettings][gatewayAddr]",'type' => 'ipfield','value' => ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['defaultGateway'])) ? $this->_run_mod_handler('replace', true, $_tmp, '0.0.0.0', '') : smarty_modifier_replace($_tmp, '0.0.0.0', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true);",'disableCondition' => "1==".($this->_tpl_vars['dhcpClientStatus']),'validate' => ($this->_tpl_vars['presenceString'])." IpAddress, (( allowZero: false, allowEmpty: true, isMasked: 'gateway' ))"), $this);?>


							<?php echo smarty_function_input_row(array('label' => 'Primary DNS Server','id' => 'primarydns','name' => "system[basicSettings][priDnsAddr]",'type' => 'ipfield','value' => ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['primaryDNS'])) ? $this->_run_mod_handler('replace', true, $_tmp, '0.0.0.0', '') : smarty_modifier_replace($_tmp, '0.0.0.0', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true)",'disableCondition' => "1==".($this->_tpl_vars['dhcpClientStatus']),'validate' => "IpAddress, (( allowZero: false ))"), $this);?>


							<?php echo smarty_function_input_row(array('label' => 'Secondary DNS Server','id' => 'secondarydns','name' => "system[basicSettings][sndDnsAddr]",'type' => 'ipfield','value' => ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['secondaryDNS'])) ? $this->_run_mod_handler('replace', true, $_tmp, '0.0.0.0', '') : smarty_modifier_replace($_tmp, '0.0.0.0', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true)",'disableCondition' => "1==".($this->_tpl_vars['dhcpClientStatus']),'validate' => "IpAddress, (( allowZero: false ))"), $this);?>

<!--
<?php if ($this->_tpl_vars['config']['NETWORK_INTEGRALITY']['status']): ?>
							<?php echo smarty_function_input_row(array('label' => 'Network Integrity Check','id' => 'networkintegrality','name' => "system[basicSettings][networkIntegralityCheck]",'type' => 'checkbox','value' => $this->_tpl_vars['data']['basicSettings']['networkIntegralityCheck'],'selectCondition' => "!=0",'onclick' => "integralityOnEnable();"), $this);?>

<?php endif; ?> -->
							<td id="cloudLink" class="font10Bold padding4Top" style="text-align: left;"><a href="http://wms.opswat.com/cwm" target="_blank">Click here to open Cloud Management</td>
<!-- BEGIN: Show the radio only when the cloud connectivity status is set to 0 -->
<!--<?php if (!($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus'])): ?> 
							<?php $this->assign('cloudConnectivityUI', $this->_tpl_vars['data']['basicSettings']['cloudConnectivityUI']); ?>
							<?php echo smarty_function_input_row(array('label' => 'Show All Menu','id' => 'enableUi','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudConnectivityUI'],'type' => 'radio','options' => "1-Yes,0-No", 'selectCondition' => "==".($this->_tpl_vars['cloudConnectivityUI'])), $this);?>
<?php endif; ?> -->
<!-- END: Show the above radio only when the cloud connetivity status is set to 0 -->	</table>					</td>					<td class="subSectionBodyDotRight">&nbsp;</td>				</tr>				<tr>					<td colspan="3" class="subSectionBottom">&nbsp;</td>				</tr>			</table>		</td>	</tr>	<tr>		<td class="spacerHeight21"></td>	</tr><tr><tr>		<td>				<table class="tableStyle">				<tr>					<td colspan="3"><script>tbhdr('802.1Q VLAN','802_1QVLAN')</script></td>				</tr>				<tr>					<td class="subSectionBodyDot">&nbsp;</td>					<td class="spacer100Percent paddingsubSectionBody">						<table class="tableStyle">							<tr>								<?php $this->assign('untaggedVlanStatus', $this->_tpl_vars['data']['basicSettings']['untaggedVlanStatus']); ?>								<td class="DatablockLabel"><?php echo smarty_function_ip_field(array('id' => 'untaggedvlan','name' => $this->_tpl_vars['parentStr']['basicSettings']['untaggedVlanStatus'],'type' => 'checkbox','value' => $this->_tpl_vars['untaggedVlanStatus'],'selectCondition' => "=".($this->_tpl_vars['untaggedVlanStatus']),'onclick' => "fetchObjectById('untaggedvlanid').disabled!=this.checked;"), $this);?>&nbsp;Untagged VLAN</td>								<td class="DatablockContent"><input class="input" id="untaggedvlanid" name="<?php echo $this->_tpl_vars['parentStr']['basicSettings']['untaggedVlanID']; ?>" value="<?php echo $this->_tpl_vars['data']['basicSettings']['untaggedVlanID']; ?>" size="6" maxlength="4" type="text" <?php if ($this->_tpl_vars['data']['basicSettings']['untaggedVlanStatus'] != 1): ?>disabled="disabled"<?php endif; ?> label="Untagged VLAN" onkeydown="setActiveContent();" validate="Presence^Numericality,<?php echo '{ minimum:1, maximum: 4094, onlyInteger: true }'; ?>"></td>							</tr>							<?php echo smarty_function_input_row(array('label' => 'Management VLAN','id' => 'mgmtvlan','name' => $this->_tpl_vars['parentStr']['basicSettings']['managementVlanID'],'type' => 'text','class' => 'input','value' => $this->_tpl_vars['data']['basicSettings']['managementVlanID'],'size' => '6','maxlength' => '4','validate' => "Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence"), $this);?>						</table>					</td>					<td class="subSectionBodyDotRight">&nbsp;</td>				</tr>				<tr>					<td colspan="3" class="subSectionBottom"></td>				</tr>			</table>		</td>	</tr>	<tr>		<td class="spacerHeight21"></td>	</tr>					<!-- Table header: Cloud Settings --><tr>		<td>			<table class="tableStyle">				<tr>					<td colspan="3"><script>tbhdr('Reboot/Reset','resetSettings')</script></td>				</tr>				<tr>					<td class="subSectionBodyDot">&nbsp;</td>					<td class="spacer100Percent paddingsubSectionBody">						<table class="tableStyle" >
							<?php echo smarty_function_input_row(array('label' => 'Reboot','id' => 'rebootAP','name' => 'rebootAP','type' => 'radio','options' => "1-Yes,0-No",'selectCondition' => "==0"), $this);?>							<?php echo smarty_function_input_row(array('label' => 'Restore to factory default settings','id' => 'resetConfiguration','name' => 'resetConfiguration','type' => 'radio','options' => "1-Yes,0-No",'selectCondition' => "==0"), $this);?>							</table>					</td>					<td class="subSectionBodyDotRight">&nbsp;</td>				</tr>				<tr>					<td colspan="3" class="subSectionBottom">&nbsp;</td>				</tr>			</table>		</td>	</tr>	<tr>		<td class="spacerHeight21"></td>	</tr><tr>					<!-- Table header: Cloud Settings --><tr>		<td>			<table class="tableStyle">				<tr>					<td colspan="3"><script>tbhdr('Firmware Details','firmwareSettings')</script></td>				</tr>				<tr>					<td class="subSectionBodyDot">&nbsp;</td>					<td class="spacer100Percent paddingsubSectionBody">						<table class="tableStyle" >
							<?php echo smarty_function_input_row(array('label' => 'Firmware Version','id' => 'fwVer','name' => "fwVer",'type' => 'text','value' => $fwVersion, 'disabled' => "true"), $this);?>
							<!--<?php echo smarty_function_input_row(array('label' => 'Up Time','id' => 'upTime','name' => 'upTime','type' => 'text','value' => $upTime, 'disabled' => "true"), $this);?>-->
							<tr>
								<td class="DatablockLabel">Up Time</td>
								<td class="DatablockContent">
									<input class="input" size="2" maxlength="2" id="upTimeDays" label="Up Time Days" value="" type="text", disabled="disabled"> <small>days</small>
									<input class="input" size="2" maxlength="2" id="upTimeHours" label="Up Time Hours" value="" type="text", disabled="disabled"> <small>hours</small>
									<input class="input" size="2" maxlength="2" id="upTimeMinutes" label="Up Time Minutes" value="" type="text", disabled="disabled"> <small>minutes</small>
									<input type="hidden" name="upTime" id="upTime" value="<?php echo $upTime ?>">
									<script type="text/javascript">
									<!--
										$('upTimeDays').value =  convertLeaseTime($('upTime').value,'days');
										$('upTimeHours').value = convertLeaseTime($('upTime').value,'hours');
										$('upTimeMinutes').value = convertLeaseTime($('upTime').value,'minutes');
									-->
									</script>
								</td>
							</tr> 
<?php endif; ?> 
<!-- END: Show the above of the form only when the cloud status is set to 1 -->
						</table>
					</td>
					<td class="subSectionBodyDotRight">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="subSectionBottom">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
<script type="text/javascript">									<!--									<?php echo 'document.addEventListener(\'DOMContentLoaded\', function() {   cloudSta();});										'?>										<?php echo 'document.addEventListener(\'DOMContentLoaded\', function() {   cloudUI();});										'?>										<?php echo 'document.addEventListener(\'DOMContentLoaded\', function() {   cloudConnection();});										'?>									-->									</script>
