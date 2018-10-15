<?php /* Smarty version 2.6.18, created on 2012-08-22 10:22:07
         compiled from AdvancedRadiusServerSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ip_field', 'AdvancedRadiusServerSettings.tpl', 30, false),array('function', 'input_row', 'AdvancedRadiusServerSettings.tpl', 83, false),array('modifier', 'replace', 'AdvancedRadiusServerSettings.tpl', 30, false),)), $this); ?>
<script language="javascript">
<!--
<?php echo '
	var spaceMask=/^\\s{0,}$/g
'; ?>

-->
</script>
<?php 
	function special($str){
			include('specialChars.php');
			return $str;
	}

?>
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('IPv4 Radius Server Settings','radiusServerConfiguration')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
						<table class="tableStyle">
					<tr>
						<td>
							<div  id="BlockContentTable" name="station">
								<table class="BlockContentTable">
									<tr>
										<th>&nbsp;</th>
										<th>IPv4 Address</th>
										<th>Port</th>
										<th class="Last">Shared Secret</th>
									</tr>
									<tr>
										<th>Primary Authentication Server</th>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['authinfo']['priRadIpAddr'],'type' => 'ipfield','id' => 'priRadIpAddr','value' => ((is_array($_tmp=$this->_tpl_vars['data']['info802dot1x']['authinfo']['priRadIpAddr'])) ? $this->_run_mod_handler('replace', true, $_tmp, '0.0.0.0', '') : smarty_modifier_replace($_tmp, '0.0.0.0', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true)",'label' => 'Primary Auth Server IP Address','validate' => "IpAddress, (( allowZero: false ))"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['authinfo']['priRadPort'],'type' => 'text','id' => 'priRadPort','label' => 'Primary Auth Server Port Number','value' => $this->_tpl_vars['data']['info802dot1x']['authinfo']['priRadPort'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum:1, maximum: 65535, onlyInteger: true ))^Presence",'onkeypress' => "checkNum()"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['authinfo']['priRadSharedSecret'],'type' => 'password','id' => 'priRadSharedSecret','label' => 'Primary Auth Server Shared Secret','value' => special(conf_decrypt($this->_tpl_vars['data']['info802dot1x']['authinfo']['priRadSharedSecret'])),'size' => '20','maxlength' => '127','validate' => "Presence, (( allowQuotes: true, allowSpace: true ))"), $this);?>
</td>
									</tr>
									<tr class="Alternate">
										<th>Secondary Authentication Server</th>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['authinfo']['sndRadIpAddr'],'type' => 'ipfield','id' => 'sndRadIpAddr','value' => ((is_array($_tmp=$this->_tpl_vars['data']['info802dot1x']['authinfo']['sndRadIpAddr'])) ? $this->_run_mod_handler('replace', true, $_tmp, '0.0.0.0', '') : smarty_modifier_replace($_tmp, '0.0.0.0', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true)",'label' => 'Secondary Auth Server IP Address','validate' => "IpAddress, (( allowZero: false ))"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['authinfo']['sndRadPort'],'type' => 'text','id' => 'sndRadPort','label' => 'Secondary Auth Server Port Number','value' => $this->_tpl_vars['data']['info802dot1x']['authinfo']['sndRadPort'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum:1, maximum: 65535, onlyInteger: true ))^Presence",'onkeypress' => "checkNum()"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['authinfo']['sndRadSharedSecret'],'type' => 'password','id' => 'sndRadSharedSecret','label' => 'Secondary Auth Server Shared Secret','value' => special(conf_decrypt($this->_tpl_vars['data']['info802dot1x']['authinfo']['sndRadSharedSecret'])),'size' => '20','maxlength' => '127','validate' => "Presence, (( allowQuotes: true, allowSpace: true ))"), $this);?>
</td>
									</tr>
									<tr>
										<th>Primary Accounting Server</th>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['accntinfo']['priAcntIpAddr'],'type' => 'ipfield','id' => 'priAcntIpAddr','value' => ((is_array($_tmp=$this->_tpl_vars['data']['info802dot1x']['accntinfo']['priAcntIpAddr'])) ? $this->_run_mod_handler('replace', true, $_tmp, '0.0.0.0', '') : smarty_modifier_replace($_tmp, '0.0.0.0', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true)",'label' => 'Primary Acct Server IP Address','validate' => "IpAddress, (( allowZero: false ))"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['accntinfo']['priAcntPort'],'type' => 'text','id' => 'priAcntPort','label' => 'Primary Acct Server Port Number','value' => $this->_tpl_vars['data']['info802dot1x']['accntinfo']['priAcntPort'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum:1, maximum: 65535, onlyInteger: true ))^Presence",'onkeypress' => "checkNum()"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['accntinfo']['priAcntSharedSecret'],'type' => 'password','id' => 'priAcntSharedSecret','label' => 'Primary Acct Server Shared Secret','value' => special(conf_decrypt($this->_tpl_vars['data']['info802dot1x']['accntinfo']['priAcntSharedSecret'])),'size' => '20','maxlength' => '127','validate' => "Presence, (( allowQuotes: true, allowSpace: true ))"), $this);?>
</td>
									</tr>
									<tr class="Alternate">
										<th>Secondary Accounting Server</th>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['accntinfo']['sndAcntIpAddr'],'type' => 'ipfield','id' => 'sndAcntIpAddr','value' => ((is_array($_tmp=$this->_tpl_vars['data']['info802dot1x']['accntinfo']['sndAcntIpAddr'])) ? $this->_run_mod_handler('replace', true, $_tmp, '0.0.0.0', '') : smarty_modifier_replace($_tmp, '0.0.0.0', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true)",'label' => 'Secondary Acct Server IP Address','validate' => "IpAddress, (( allowZero: false ))"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['accntinfo']['sndAcntPort'],'type' => 'text','id' => 'sndAcntPort','label' => 'Secondary Acct Server Port Number','value' => $this->_tpl_vars['data']['info802dot1x']['accntinfo']['sndAcntPort'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum:1, maximum: 65535, onlyInteger: true ))^Presence",'onkeypress' => "checkNum()"), $this);?>
</td>
										<td><?php echo smarty_function_ip_field(array('name' => $this->_tpl_vars['parentStr']['info802dot1x']['accntinfo']['sndAcntSharedSecret'],'type' => 'password','id' => 'sndAcntSharedSecret','label' => 'Secondary Acct Server Shared Secret','value' => special(conf_decrypt($this->_tpl_vars['data']['info802dot1x']['accntinfo']['sndAcntSharedSecret'])),'size' => '20','maxlength' => '127','validate' => "Presence, (( allowQuotes: true, allowSpace: true ))"), $this);?>
</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
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
<?php if (! $this->_tpl_vars['config']['WNDAP660']['status'] && ! $this->_tpl_vars['config']['WNDAP620']['status'] && ! $this->_tpl_vars['config']['JWAP603']['status']): ?>		
<?php if ($this->_tpl_vars['config']['AUTH_SETTINGS']['status']): ?>
	<tr>
		<td class="spacerHeight21">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Authentication Settings','authenticationSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php echo smarty_function_input_row(array('label' => "Reauthentication Time (Seconds)",'id' => 'reauthTime','name' => $this->_tpl_vars['parentStr']['info802dot1x']['authSetting']['reauthTime'],'type' => 'text','size' => '6','maxlength' => '5','validate' => "Presence^Numericality, (( minimum:1, maximum: 99999, onlyInteger: true ))",'value' => $this->_tpl_vars['data']['info802dot1x']['authSetting']['reauthTime'],'onkeydown' => "setActiveContent();"), $this);?>


							<tr>
								<?php $this->assign('wpaGroupRekeyStatus', $this->_tpl_vars['data']['info802dot1x']['authSetting']['wpaGroupKeyUpdateCondition']); ?>
								<td class="DatablockLabel"><?php echo smarty_function_ip_field(array('id' => 'wpaGroupRekeyStatus','name' => $this->_tpl_vars['parentStr']['info802dot1x']['authSetting']['wpaGroupKeyUpdateCondition'],'type' => 'checkbox','value' => ($this->_tpl_vars['wpaGroupRekeyStatus']),'selectCondition' => "==1",'onclick' => "fetchObjectById('wpaGroupRekey').disabled=!this.checked;",'onkeydown' => "setActiveContent();"), $this);?>
&nbsp;Update Global Key Every (Seconds)</td>
								<td class="DatablockContent"><input class="input" id="wpaGroupRekey" name="<?php echo $this->_tpl_vars['parentStr']['info802dot1x']['authSetting']['wpaGroupKeyUpdateIntervalSecond']; ?>
" value="<?php echo $this->_tpl_vars['data']['info802dot1x']['authSetting']['wpaGroupKeyUpdateIntervalSecond']; ?>
" size="6" maxlength="5" type="text" <?php if ($this->_tpl_vars['data']['info802dot1x']['authSetting']['wpaGroupKeyUpdateCondition'] != 1): ?>disabled="disabled"<?php endif; ?> label="Global Key Update Frequency" onkeydown="setActiveContent();" validate="Presence^Numericality,<?php echo '{ minimum:1, maximum: 99999, onlyInteger: true }'; ?>
"></td>
							</tr>
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
<?php endif; ?>
<?php endif; ?>
	<input type="hidden" id="authType" name="authType" value="<?php echo $this->_tpl_vars['radiusUsed']; ?>
">
	<?php if ( $this->_tpl_vars['config']['WNDAP660']['status'] ||  $this->_tpl_vars['config']['WNDAP620']['status']): ?>
<?php if ($this->_tpl_vars['config']['AUTH_SETTINGS']['status']): ?>
	<tr>
		<td class="spacerHeight21">&nbsp;</td>
	</tr>
<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Authentication Settings','authenticationSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php echo smarty_function_input_row(array('label' => "Reauthentication Time (Seconds)",'id' => 'reauthTime','name' => $this->_tpl_vars['parentStr']['info802dot1x']['authSetting']['reauthTime'],'type' => 'text','size' => '6','maxlength' => '5','validate' => "Presence^Numericality, (( minimum:1, maximum: 99999, onlyInteger: true ))",'value' => $this->_tpl_vars['data']['info802dot1x']['authSetting']['reauthTime'],'onkeydown' => "setActiveContent();"), $this);?>


							<tr>
								<?php $this->assign('wpaGroupRekeyStatus', $this->_tpl_vars['data']['info802dot1x']['authSetting']['wpaGroupKeyUpdateCondition']); ?>
								<td class="DatablockLabel"><?php echo smarty_function_ip_field(array('id' => 'wpaGroupRekeyStatus','name' => $this->_tpl_vars['parentStr']['info802dot1x']['authSetting']['wpaGroupKeyUpdateCondition'],'type' => 'checkbox','value' => ($this->_tpl_vars['wpaGroupRekeyStatus']),'selectCondition' => "==1",'onclick' => "fetchObjectById('wpaGroupRekey').disabled=!this.checked;",'onkeydown' => "setActiveContent();"), $this);?>
&nbsp;Update Global Key Every (Seconds)</td>
								<td class="DatablockContent"><input class="input" id="wpaGroupRekey" name="<?php echo $this->_tpl_vars['parentStr']['info802dot1x']['authSetting']['wpaGroupKeyUpdateIntervalSecond']; ?>
" value="<?php echo $this->_tpl_vars['data']['info802dot1x']['authSetting']['wpaGroupKeyUpdateIntervalSecond']; ?>
" size="6" maxlength="5" type="text" <?php if ($this->_tpl_vars['data']['info802dot1x']['authSetting']['wpaGroupKeyUpdateCondition'] != 1): ?>disabled="disabled"<?php endif; ?> label="Global Key Update Frequency" onkeydown="setActiveContent();" validate="Presence^Numericality,<?php echo '{ minimum:1, maximum: 99999, onlyInteger: true }'; ?>
"></td>
							</tr>
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
<?php endif; ?>

	<input type="hidden" id="v6authType" name="authType" value="<?php echo $this->_tpl_vars['radiusUsed']; ?>
">
<?php endif; ?>
<script language="javascript">
<!--
<?php if ($this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 5): ?>
	Form.disable(document.dataForm);
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
	<?php if (! $this->_tpl_vars['config']['TWOGHZ']['status']): ?>
		form.tab2.activate();
	<?php endif; ?>	
	<?php if ($this->_tpl_vars['data']['radioStatus1'] == '1' && $this->_tpl_vars['data']['radioStatus0'] != '1' && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '5'): ?>
		form.tab2.activate();
	<?php endif; ?>
<?php endif; ?>	
-->
</script>
