<?php /* Smarty version 2.6.18, created on 2012-06-08 05:54:06
         compiled from BasicTime.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'BasicTime.tpl', 11, false),array('modifier', 'replace', 'BasicTime.tpl', 26, false),)), $this); ?>
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Time Settings','timeSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php echo smarty_function_input_row(array('label' => 'Time Zone','id' => 'timeZone','name' => $this->_tpl_vars['parentStr']['timeSettings']['timeZone'],'type' => 'select','options' => $this->_tpl_vars['timeZones'],'selected' => $this->_tpl_vars['data']['timeSettings']['timeZone'],'style' => "width: 240px;"), $this);?>

							<tr>
								<td class="DatablockLabel" >Current Time</td>
								 <td class="DatablockContent"><?php  echo `/usr/local/bin/date.sh `; ?></td>	
							</tr>
<?php if ($this->_tpl_vars['config']['NTP']['status']): ?>
							<?php $this->assign('ntpClientStatus', $this->_tpl_vars['data']['timeSettings']['ntpClientStatus']); ?>
							<?php echo smarty_function_input_row(array('label' => 'NTP Client','id' => 'ntpServerRadio','name' => $this->_tpl_vars['parentStr']['timeSettings']['ntpClientStatus'],'type' => 'radio','options' => "1-Enable,0-Disable",'selectCondition' => "==".($this->_tpl_vars['ntpClientStatus']),'onclick' => "toggleNTPServer(this.value);"), $this);?>


							<?php $this->assign('customNtpServer', $this->_tpl_vars['data']['timeSettings']['customNtpServer']); ?>
							<?php echo smarty_function_input_row(array('label' => 'Use Custom NTP Server','id' => 'customntp','name' => $this->_tpl_vars['parentStr']['timeSettings']['customNtpServer'],'type' => 'checkbox','value' => ($this->_tpl_vars['customNtpServer']),'selectCondition' => "==1",'disableCondition' => "1!=".($this->_tpl_vars['ntpClientStatus']),'onclick' => "checkCustomNTPServer(this.checked);"), $this);?>


							<?php if ($this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['WNDAP620']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>
							<?php $this->assign('HostnameIPAddress', $this->_tpl_vars['data']['timeSettings']['ntpAddr']); ?>
							<?php $this->assign('tmpv4', $this->_tpl_vars['data']['timeSettings']['ntpAddr']); ?>
							<?php $this->assign('tmpv6', ((is_array($_tmp=$this->_tpl_vars['data']['timeSettings']['ntpAddr'])) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':'))); ?>
							<?php 
							if (preg_match("/--/i", $this->_tpl_vars['HostnameIPAddress']))
							$this->_tpl_vars['HostnameIPAddress']=$this->_tpl_vars['tmpv6'];
							else
							$this->_tpl_vars['HostnameIPAddress']=$this->_tpl_vars['tmpv4'];
							 ?>
							<?php echo smarty_function_input_row(array('label' => "Hostname / IP Address",'id' => 'ntpservername','name' => $this->_tpl_vars['parentStr']['timeSettings']['ntpAddr'],'type' => 'text','value' => $this->_tpl_vars['HostnameIPAddress'],'disableCondition' => "1!=".($this->_tpl_vars['ntpClientStatus'])." || 1!=".($this->_tpl_vars['customNtpServer']),'size' => '16','maxlength' => '128','validate' => "Presence^IpAddress, (( allowURL: true, allowZero: false, allowGeneric: false, allowIpV6: true ))"), $this);?>

							<?php else: ?>
							<?php echo smarty_function_input_row(array('label' => "Hostname / IP Address",'id' => 'ntpservername','name' => $this->_tpl_vars['parentStr']['timeSettings']['ntpAddr'],'type' => 'text','value' => $this->_tpl_vars['data']['timeSettings']['ntpAddr'],'disableCondition' => "1!=".($this->_tpl_vars['ntpClientStatus'])." || 1!=".($this->_tpl_vars['customNtpServer']),'size' => '16','maxlength' => '128','validate' => "Presence^IpAddress, (( allowURL: true, allowZero: false, allowGeneric: false ))"), $this);?>
							
							<?php endif; ?>
							<?php echo smarty_function_input_row(array('label' => "",'name' => $this->_tpl_vars['parentStr']['timeSettings']['ntpAddr'],'type' => 'hidden','value' => $this->_tpl_vars['ntpservername']['value'],'id' => 'hiddenntpservername','disabled' => 'true'), $this);?>

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