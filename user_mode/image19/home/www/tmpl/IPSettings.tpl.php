<?php /* Smarty version 2.6.18, created on 2009-03-02 06:07:21
         compiled from IPSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'IPSettings.tpl', 20, false),array('modifier', 'replace', 'IPSettings.tpl', 31, false),array('function', 'ip_field', 'AdvancedGeneral.tpl', 40, false),)), $this); ?>
<script language="javascript">
<!--
<?php echo '
	var spaceMask=/^\\s{0,}$/g
'; ?>

-->
</script>
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('IP Settings','ipConfiguration')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
<?php if ($this->_tpl_vars['config']['DHCPCLIENT']['status']): ?>
							<?php $this->assign('dhcpClientStatus', $this->_tpl_vars['data']['basicSettings']['dhcpClientStatus']); ?>
							<?php echo smarty_function_input_row(array('label' => 'DHCP Client','id' => 'enabledhcp','name' => "system[basicSettings][dhcpClientStatus]",'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['dhcpClientStatus'],'selectCondition' => "==".($this->_tpl_vars['dhcpClientStatus']),'onclick' => "graysomething(this,false);"), $this);?>

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

 <?php if ($this->_tpl_vars['data']['basicSettings']['cloudConnectivityUI']):?>
<?php if ($this->_tpl_vars['config']['NETWORK_INTEGRALITY']['status']): ?>
							<?php echo smarty_function_input_row(array('label' => 'Network Integrity Check','id' => 'networkintegrality','name' => "system[basicSettings][networkIntegralityCheck]",'type' => 'checkbox','value' => $this->_tpl_vars['data']['basicSettings']['networkIntegralityCheck'],'selectCondition' => "!=0",'onclick' => "integralityOnEnable();"), $this);?>

<?php endif; ?>
<?php endif; ?>
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
	<?php if (!($this->_tpl_vars['data']['basicSettings']['cloudConnectivityUI'])):?>
	<tr>
       <td class="spacerHeight21"></td>
    </tr><tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('802.1Q VLAN','802_1QVLAN')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<tr>
								<?php $this->assign('untaggedVlanStatus', $this->_tpl_vars['data']['basicSettings']['untaggedVlanStatus']); ?>
								<td class="DatablockLabel"><?php echo smarty_function_ip_field(array('id' => 'untaggedvlan','name' => $this->_tpl_vars['parentStr']['basicSettings']['untaggedVlanStatus'],'type' => 'checkbox','value' => $this->_tpl_vars['untaggedVlanStatus'],'selectCondition' => "=".($this->_tpl_vars['untaggedVlanStatus']),'onclick' => "fetchObjectById('untaggedvlanid').disabled!=this.checked;"), $this);?>
&nbsp;Untagged VLAN</td>
								<td class="DatablockContent"><input class="input" id="untaggedvlanid" name="<?php echo $this->_tpl_vars['parentStr']['basicSettings']['untaggedVlanID']; ?>
" value="<?php echo $this->_tpl_vars['data']['basicSettings']['untaggedVlanID']; ?>
" size="6" maxlength="4" type="text" <?php if ($this->_tpl_vars['data']['basicSettings']['untaggedVlanStatus'] != 1): ?>disabled="disabled"<?php endif; ?> label="Untagged VLAN" onkeydown="setActiveContent();" validate="Presence^Numericality,<?php echo '{ minimum:1, maximum: 4094, onlyInteger: true }'; ?>
"></td>
							</tr>
							<?php echo smarty_function_input_row(array('label' => 'Management VLAN','id' => 'mgmtvlan','name' => $this->_tpl_vars['parentStr']['basicSettings']['managementVlanID'],'type' => 'text','class' => 'input','value' => $this->_tpl_vars['data']['basicSettings']['managementVlanID'],'size' => '6','maxlength' => '4','validate' => "Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence"), $this);?>

						</table>
					</td>
					<td class="subSectionBodyDotRight">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="subSectionBottom"></td>
				</tr>
			</table>
		</td>
	</tr>
<?php endif;?>
