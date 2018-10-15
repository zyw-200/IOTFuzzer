<?php /* Smarty version 2.6.18, created on 2009-09-01 10:46:59
         compiled from Snooping.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'Snooping.tpl', 13, false),)), $this); ?>
<?php if ($this->_tpl_vars['config']['DHCP_SNOOPING']['status']): ?>
		<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('DHCP Snooping','dhcpSnooping')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php $this->assign('dhcpSnooping', $this->_tpl_vars['data']['basicSettings']['dhcpSnooping']); ?>
							<?php echo smarty_function_input_row(array('label' => 'DHCP Snooping','id' => 'chkDHCP','name' => $this->_tpl_vars['parentStr']['basicSettings']['dhcpSnooping'],'type' => 'radio','options' => "1-Enable,0-Disable",'selectCondition' => "==".($this->_tpl_vars['dhcpSnooping'])), $this);?>
							
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
	<tr>
		<td class="spacerHeight21"></td>
	</tr>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['config']['IGMP_SNOOPING']['status']): ?>
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('IGMP Snooping','igmpSnooping')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php $this->assign('igmpSnooping', $this->_tpl_vars['data']['basicSettings']['igmpSnooping']); ?>
							<?php echo smarty_function_input_row(array('label' => 'IGMP Snooping','id' => 'chkIGMP','name' => $this->_tpl_vars['parentStr']['basicSettings']['igmpSnooping'],'type' => 'radio','options' => "1-Enable,0-Disable",'selectCondition' => "==".($this->_tpl_vars['igmpSnooping'])), $this);?>

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
	<tr>
		<td class="spacerHeight21"></td>
	</tr>
	<?php endif; ?>