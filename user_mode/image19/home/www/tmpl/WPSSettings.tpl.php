<?php /* Smarty version 2.6.18, created on 2010-01-19 09:30:07
         compiled from WPSSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'WPSSettings.tpl', 13, false),)), $this); ?>
	<tr>
		<td>	
			<table class="tableStyle" id="wpsTable">
				<tr>
					<td colspan="3"><script>tbhdr('WPS Settings','wpssettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php $this->assign('wpsStatus', $this->_tpl_vars['data']['wpsSettings']['wpsDisable']); ?>
                                                        <?php $this->assign('onclickStr', "graysomething(this,false);"); ?>
							<?php echo smarty_function_input_row(array('label' => 'WPS','id' => 'chkWPS','name' => $this->_tpl_vars['parentStr']['wpsSettings']['wpsDisable'],'type' => 'radio','options' => "0-Enable,1-Disable",'selectCondition' => "==".($this->_tpl_vars['wpsStatus']),'onclick' => ($this->_tpl_vars['onclickStr']).";setDisableRouter()"), $this);?>

                                                        <tr>
								<td class="DatablockLabel">AP's PIN:</td>
								<td class="DatablockLabel" id="apPinLabel">&nbsp&nbsp <?php echo $this->_tpl_vars['data']['monitor']['wpsPin']; ?>
</td>
							</tr>
                                                        <tr>
                                                        
                                                             <td align="left"><Input type="checkbox" id="disableRouterPinl" onclick="setActiveContent();toggleAPPINDisplay();$('disableRouterPin').disabled=true;" <?php if ($this->_tpl_vars['data']['monitor']['wpsGetApPinStatus'] == '01' || $this->_tpl_vars['data']['monitor']['wpsGetApPinStatus'] == '00'): ?>checked="checked"<?php endif; ?><?php if ($this->_tpl_vars['wpsStatus'] == '1' || $this->_tpl_vars['data']['monitor']['wpsGetApPinStatus'] == '00'): ?>disabled="disabled"<?php endif; ?>><td align="left" class="DatablockLabel">&nbsp&nbsp Disable AP's PIN</td>
                                                             <input type="hidden" name="disableRouterPin" id="disableRouterPin" value="<?php echo $this->_tpl_vars['data']['monitor']['wpsGetApPinStatus']; ?>
" disabled="disabled">
                                                        </tr>
                                                        <tr>
                                                            <td align="left"><Input type="checkbox" onclick="setActiveContent();$('keepExisting').value=this.checked?'1':'0';" <?php if ($this->_tpl_vars['wpsStatus'] == '1'): ?>disabled="disabled"<?php endif; ?> <?php if ($this->_tpl_vars['data']['wpsSettings']['wpsConfigured'] == '1'): ?>checked="checked"<?php endif; ?>><td align="left" class="DatablockLabel">&nbsp&nbsp Keep Existing Wireless Settings
                                                            <input type="hidden" name="<?php echo $this->_tpl_vars['parentStr']['wpsSettings']['wpsConfigured']; ?>
" id="keepExisting" value="<?php echo $this->_tpl_vars['data']['wpsSettings']['wpsConfigured']; ?>
">
                                                            </td>
                                                        </tr>
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
        <input type="hidden" name="bridgingStatus" id="bridgingStatus" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']; ?>
">
        <input type="hidden" id="secutityStatus" name="secutityStatus" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap0']['authenticationType']; ?>
">
<script language="javascript">
	<!--
	window.top.frames['action'].$('standardButtons').show();
        <?php echo '
        if(($(\'errorMessageBlock\').style.display != \'none\') && ($(\'br_head\').innerHTML == \'Wireless Radio is turned off!\')){
            Form.disable(document.dataForm);
        }
        if(($(\'bridgingStatus\').value == \'5\')){
            Form.disable(document.dataForm);
        }
        '; ?>

        <?php if ($this->_tpl_vars['data']['wpsSettings']['wpsDisable'] == '1'): ?>
                $('apPinLabel').disabled=true;
                $('apPinLabel').style.color="grey";
        <?php endif; ?>
        <?php echo '
        if($(\'disableRouterPin\').value == \'00\' || $(\'disableRouterPin\').value == \'01\'){
            $(\'apPinLabel\').disabled=true;
            $(\'apPinLabel\').style.color="grey";
        }
        '; ?>

	-->
</script>	