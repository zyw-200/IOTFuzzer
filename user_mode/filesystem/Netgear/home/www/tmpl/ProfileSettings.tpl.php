<?php /* Smarty version 2.6.18, created on 2009-10-28 14:08:38
         compiled from ProfileSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ProfileSettings.tpl', 136, false),)), $this); ?>
	<?php if (( $this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == '1' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == '5' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == '4' ) && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['radioStatus'] == '1'): ?>
		<?php $this->assign('checkWlan0ApStatus', "disabled='disabled'"); ?>
		<?php $this->assign('checkEditDisable', "disabled='disabled'"); ?>
	<?php endif; ?>
	<?php if (( $this->_tpl_vars['config']['FIVEGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '1' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '5' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '4' ) && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['radioStatus'] == '1'): ?>
		<?php $this->assign('checkWlan1ApStatus', "disabled='disabled'"); ?>
		<?php $this->assign('checkEditDisable', "disabled='disabled'"); ?>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['checkEditDisable'] != ''): ?>
	<input type='hidden' id='formDisabled' value='true'>
	<?php endif; ?>

<!--@@@DUAL_CONCURRENTSTART@@@-->
<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>
    <?php if (( $this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == '1' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == '4' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == '5' ) && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['radioStatus'] == '1'): ?>
        <input type='hidden' id='formTwoGHzDisable' value='true'>
    <?php else: ?>
        <input type='hidden' id='formTwoGHzDisable' value='false'>
    <?php endif; ?>
    <?php if (( $this->_tpl_vars['config']['FIVEGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '1' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '4' || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '5') && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['radioStatus'] == '1'): ?>
        <input type='hidden' id='formFiveGHzDisable' value='true'>
    <?php else: ?>
        <input type='hidden' id='formFiveGHzDisable' value='false'>
    <?php endif; ?>
<?php endif; ?>
<!--@@@DUAL_CONCURRENTEND@@@-->


	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3">
						<table class='tableStyle'>
							<tr>
								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Profile Settings</td>
								<td class='subSectionTabTopRight spacer40Percent' style="font-size: 11px;"><a href='javascript: void(0);' onclick="showHelp('Security Profiles','securityProfiles');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>
							</tr>
							<tr>
								<td colspan='3' class='subSectionTabTopShadow'></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
						<table class="tableStyle">
							<tr>
								<td>
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bandStrip.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									<div id="IncludeTabBlock">
<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
										<div  class="BlockContentTable" id="wlan1">
											<table class="BlockContentTable" id="table_wlan1">
												<tr>
													<th>&nbsp;</th>
													<th>#</th>
													<th>Profile Name</th>
													<th>SSID</th>
													<th>Security</th>
													<th>VLAN</th>
													<th class="Last">Enable</th>
												</tr>
												<?php $_from = $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profiles'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profiles']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['profiles']['iteration']++;
?>
												<?php $this->assign('vapIndex', $this->_tpl_vars['value']['vapIndex']-1); ?>
												<tr <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="radio" id="profileid_0" name="profileid0" value="<?php echo $this->_tpl_vars['vapIndex']; ?>
" <?php if ($this->_tpl_vars['value']['vapIndex'] == '1'): ?>checked="checked"<?php endif; ?> <?php echo $this->_tpl_vars['checkWlan0ApStatus']; ?>
></td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['vapIndex']; ?>
</td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['vapProfileName']; ?>
</td>
													<?php 
													$this->_tpl_vars['ssid'] = $this->_tpl_vars['value']['ssid'];
													 ?>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['ssid']; ?>
</td>
													<?php 
														$this->_tpl_vars['authType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['authenticationType']];
													 ?>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['authType']; ?>
</td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['vlanID']; ?>
</td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id=cb_vapProfileStatus<?php echo $this->_tpl_vars['vapIndex']; ?>
 name="cb_vapProfileStatus<?php echo $this->_tpl_vars['vapIndex']; ?>
" <?php echo $this->_tpl_vars['checkWlan0ApStatus']; ?>
 onclick="setActiveContent();$('vapProfileStatus<?php echo $this->_tpl_vars['vapIndex']; ?>
_0').value=(this.checked)?'1':'0';" value="1" <?php if ($this->_tpl_vars['value']['vapProfileStatus'] == '1'): ?>checked="checked"<?php endif; ?> <?php if ($this->_tpl_vars['value']['vapIndex'] == '8' && $this->_tpl_vars['data']['monitor']['productId'] == 'WG102'): ?>disabled="true"<?php endif; ?>><input type="hidden" name="<?php echo $this->_tpl_vars[parentStr][vapSettings][vapSettingTable][wlan0]['vap'.$this->_tpl_vars[vapIndex]][vapProfileStatus]; ?>" id="vapProfileStatus<?php echo $this->_tpl_vars['vapIndex']; ?>
_0" value="<?php echo $this->_tpl_vars['value']['vapProfileStatus']; ?>
"></td>
												</tr>
												<?php endforeach; endif; unset($_from); ?>
											</table>
										</div>
<?php endif; ?>
<!--@@@FIVEGHZSTART@@@-->
<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
										<div  class="BlockContentTable" <?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>style="display:none;"<?php endif; ?> id="wlan2">
											<table class="BlockContentTable" id="table_wlan2">
												<tr>
													<th>&nbsp;</th>
													<th>#</th>
													<th>Profile Name</th>
													<th>SSID</th>
													<th>Security</th>
													<th>VLAN</th>
													<th class="Last">Enable</th>
												</tr>
												<?php $_from = $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profiles'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profiles']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['profiles']['iteration']++;
?>
												<?php $this->assign('vapIndex', $this->_tpl_vars['value']['vapIndex']-1); ?>
												<tr <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="radio" id="profileid_1" name="profileid1" value="<?php echo $this->_tpl_vars['vapIndex']; ?>
" <?php if ($this->_tpl_vars['value']['vapIndex'] == '1'): ?>checked="checked"<?php endif; ?> <?php echo $this->_tpl_vars['checkWlan1ApStatus']; ?>
></td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['vapIndex']; ?>
</td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['vapProfileName']; ?>
</td>
													<?php 
													$this->_tpl_vars['ssid'] = $this->_tpl_vars['value']['ssid'];
													 ?>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['ssid']; ?>
</td>
													<?php 
														$this->_tpl_vars['authType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['authenticationType']];
													 ?>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['authType']; ?>
</td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['vlanID']; ?>
</td>
													<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id=cb_vapProfileStatus1<?php echo $this->_tpl_vars['vapIndex']; ?>
 name="cb_vapProfileStatus1<?php echo $this->_tpl_vars['vapIndex']; ?>
" <?php echo $this->_tpl_vars['checkWlan1ApStatus']; ?>
 onclick="setActiveContent();$('vapProfileStatus<?php echo $this->_tpl_vars['vapIndex']; ?>
_1').value=(this.checked)?'1':'0';" value="1" <?php if ($this->_tpl_vars['value']['vapProfileStatus'] == '1'): ?>checked="checked"<?php endif; ?>><input type="hidden" name="<?php echo $this->_tpl_vars[parentStr][vapSettings][vapSettingTable][wlan1]['vap'.$this->_tpl_vars[vapIndex]][vapProfileStatus]; ?>" id="vapProfileStatus<?php echo $this->_tpl_vars['vapIndex']; ?>
_1" value="<?php echo $this->_tpl_vars['value']['vapProfileStatus']; ?>
"></td>
												</tr>
												<?php endforeach; endif; unset($_from); ?>
											</table>
										</div>
<?php endif; ?>
<!--@@@FIVEGHZEND@@@-->
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
	<script language="javascript">
	<!--
        var prevInterface = <?php echo ((is_array($_tmp=@$_POST['previousInterfaceNum'])) ? $this->_run_mod_handler('default', true, $_tmp, "''") : smarty_modifier_default($_tmp, "''")); ?>
;
		var buttons = new buttonObject();
		buttons.getStaticButtons(['edit']);
		<?php echo '
		if ($(\'formDisabled\') != undefined) {
			disableButtons(["edit"]);
		}

		function doEdit()
		{
			if(window.top.frames[\'action\'].$(\'edit\').src.indexOf(\'edit_on\')!== -1)
			{
				'; ?>

				prepareURL('<?php echo $this->_tpl_vars['navigation']['4']; ?>
','<?php echo $this->_tpl_vars['navigation']['3']; ?>
','<?php echo $this->_tpl_vars['navigation']['2']; ?>
','<?php echo $this->_tpl_vars['navigation']['1']; ?>
','profileid',String(parseInt(form.activeTab)-1));
				<?php echo '
				return false;
			}
			else
			{
				window.top.frames[\'action\'].$(\'edit\').disabled=true;
			}
		}
		'; ?>

//		toggleDisplay('<?php echo $this->_tpl_vars['interface']; ?>
');
		<?php if (( $this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 5 ) || ( $this->_tpl_vars['config']['FIVEGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == 5 )): ?>
			disableButtons(["edit"]);
		<?php endif; ?>
			var form = new formObject();
<?php echo '
            if (prevInterface != \'\') {
                    if(prevInterface == \'1\'){
                        form.tab1.activate();
                    }
                    else if(prevInterface == \'2\'){
                        form.tab2.activate();
                    }
             }
             else {
'; ?>

            <?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
                    form.tab1.activate();
            <?php endif; ?>
//<!--@@@FIVEGHZSTART@@@-->
            <?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
        	<?php if (!$this->_tpl_vars['config']['TWOGHZ']['status'] ): ?>
                    form.tab2.activate();
                <?php endif; ?>
	        <?php if ($this->_tpl_vars['data']['radioStatus1'] == '1' && $this->_tpl_vars['data']['radioStatus0'] != '1'): ?>
                    form.tab2.activate();
                <?php endif; ?>
            <?php endif; ?>
//<!--@@@FIVEGHZEND@@@-->
<?php echo '
            }
'; ?>

	-->
	</script>
