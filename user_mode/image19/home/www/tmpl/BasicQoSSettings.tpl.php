<?php /* Smarty version 2.6.18, created on 2010-06-17 08:05:51
         compiled from BasicQoSSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'BasicQoSSettings.tpl', 20, false),array('modifier', 'default', 'BasicQoSSettings.tpl', 58, false),)), $this); ?>
	<tr>

		<td>

			<table class="tableStyle">

				<tr>

					<td colspan="3"><script>tbhdr('Qos Settings','qOSSettings')</script></td>

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

										<div class="BlockContent" id="wlan1">

											<table class="BlockContent" id="table_wlan1">

                                                <input type="hidden" name="dummyAPMode0" id="ApMode0" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']; ?>
">

												<?php $this->assign('wmmSupport', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['wmmSupport']); ?>

												<?php echo smarty_function_input_row(array('label' => "Enable Wi-Fi Multimedia (WMM)",'id' => 'idwmmSupport0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['wmmSupport'],'type' => 'radio','options' => "1-Enable,0-Disable",'selectCondition' => "==".($this->_tpl_vars['wmmSupport']),'onclick' => "disableWMMPS(this)"), $this);?>


												<?php if ($this->_tpl_vars['config']['WMM']['WMM_PS']['status']): ?>

												<?php $this->assign('uapsdStatus', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['uapsdStatus']); ?>

												<?php echo smarty_function_input_row(array('label' => 'WMM Powersave','id' => 'idwmmPowersave0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['uapsdStatus'],'type' => 'radio','options' => "1-Enable,0-Disable",'selectCondition' => "==".($this->_tpl_vars['uapsdStatus']),'disableCondition' => "1!=".($this->_tpl_vars['wmmSupport'])), $this);?>


												<?php endif; ?>

											</table>

										</div>

										<?php endif; ?>

<!--@@@FIVEGHZSTART@@@-->

										<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>

										<div class="BlockContent" id="wlan2">

											<table class="BlockContent" id="table_wlan2">

                                                <input type="hidden" name="dummyAPMode1" id="ApMode1" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode']; ?>
">

												<?php $this->assign('wmmSupport', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['wmmSupport']); ?>

												<?php echo smarty_function_input_row(array('label' => "Enable Wi-Fi Multimedia (WMM)",'id' => 'idwmmSupport1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['wmmSupport'],'type' => 'radio','options' => "1-Enable,0-Disable",'selectCondition' => "==".($this->_tpl_vars['wmmSupport']),'onclick' => "disableWMMPS1(this)"), $this);?>

												<?php if ($this->_tpl_vars['config']['WMM']['WMM_PS']['status']): ?>

												<?php $this->assign('uapsdStatus', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['uapsdStatus']); ?>

												<?php echo smarty_function_input_row(array('label' => 'WMM Powersave','id' => 'idwmmPowersave1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['uapsdStatus'],'type' => 'radio','options' => "1-Enable,0-Disable",'selectCondition' => "==".($this->_tpl_vars['uapsdStatus']),'disableCondition' => "1!=".($this->_tpl_vars['wmmSupport'])), $this);?>

												<?php endif; ?>

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
