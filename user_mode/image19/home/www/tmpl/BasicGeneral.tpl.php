<?php /* Smarty version 2.6.18, created on 2010-06-23 06:01:32
         compiled from BasicGeneral.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'BasicGeneral.tpl', 11, false),array('function', 'ip_field', 'BasicGeneral.tpl', 13, false),)), $this); ?>
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('General','basicSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php echo smarty_function_input_row(array('label' => 'Access Point Name','id' => 'apName','name' => $this->_tpl_vars['parentStr']['basicSettings']['apName'],'type' => 'text','class' => 'input','value' => $this->_tpl_vars['data']['basicSettings']['apName'],'size' => '16','maxlength' => '15','validate' => "Presence^AlphaNumericWithH^NotAllNums^NotLastH"), $this);?>

							<?php echo smarty_function_input_row(array('label' => "Country / Region",'id' => 'sysCountryRegion','name' => $this->_tpl_vars['parentStr']['basicSettings']['sysCountryRegion'],'type' => 'select','class' => 'select','options' => $this->_tpl_vars['countryList'],'selected' => $this->_tpl_vars['data']['basicSettings']['sysCountryRegion']), $this);?>

							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'sysCountry','name' => 'sysCountry','type' => 'hidden','value' => $this->_tpl_vars['data']['basicSettings']['sysCountryRegion']), $this);?>

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
      <tr>

                <td>

                        <table class="tableStyle">

                                <tr>

                                        <td colspan="3"><script>tbhdr('Cloud Settings','cloudSettings')</script></td>

                                </tr>

                                <tr>

                                        <td class="subSectionBodyDot">&nbsp;</td>

                                        <td class="spacer100Percent paddingsubSectionBody">

                                                <table class="tableStyle">
                                                <?php $this->assign('cloudStatus', $this->_tpl_vars['data']['basicSettings']['cloudStatus']); ?>
                                                        <?php echo smarty_function_input_row(array('label' => 'Cloud Enabled','id' => 'enableMode','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'radio','options' => "1-Yes,0-No", 'selectCondition' => "==".($this->_tpl_vars['data']['basicSettings']['cloudStatus']),'onclick' => "updateCloudStatus(this.value);"), $this);?>
                                                        <?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'hiddenCloud','name' => 'hiddenCloud','type' => 'hidden','value' => $this->_tpl_vars['cloudStatus']), $this);?>
							<!------- VVDN CODE START ------>
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'hidden_cloudStatus','name' => 'hidden_cloudStatus','type' => 'hidden'), $this);?>
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'hiddenAP_StandaloneMode','name' => 'hiddenAP_StandaloneMode','type' => 'hidden'), $this);?>
							<?php $serno=explode(' ',exec("grep serno /etc/board"));?>
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'serno','name' => 'serno','type' => 'hidden','value' => $serno[2]), $this);?>
							<!------ VVDN CODE ENDS --------->
<?php if ($this->_tpl_vars['data']['basicSettings']['cloudStatus']): ?>
							<?php if ($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus']): ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Activation Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudConnectivityStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "Activated"), $this);?>
							<?php else: ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Activation Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudConnectivityStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "Pending"), $this);?>
							<?php endif;?>
							
							<?php $connectionStatus=explode(' ',exec("grep opswat.connection_state: /var/pal.cfg"));?>
							<?php if ($connectionStatus[1] =='disconnected' || $connectionStatus[1] ==''): ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Connectivity Status','id' => 'connStatus','name' => 'connStatus','type' => 'text', 'disabled' => "true", 'value' => "Disconnected"), $this);?>
							<?php else:?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Connectivity Status','id' => 'connStatus','name' => 'connStatus','type' => 'text' , 'disabled' => "true", 'value' => "Connected"), $this);?>
							<?php endif;?>
<?php endif;?>
										<tr>          <td class="spacerHeight21"></td>      </tr>
										<td id="cloudLink" class="font10Bold padding4Top" style="text-align: left;"><a href="http://wireless.netgear.com" target="_blank">Click here to open Cloud Management</td>

						 <tr>          <td class="spacerHeight12"></td>      </tr> </table>      <table class="tableStyle">               <tr>                  <td>                          <textarea name="activewin" draggable="false" id="activewin" class="smallfix2" cols="60" style="resize: none;font-size:11px;" rows="4" wrap="on" readonly="readonly">NOTE: If Cloud Enabled is set to Yes, this Access Point will periodically attempt to contact NETGEAR Cloud Wireless Server to check if this Access Point has been registered for cloud management.</textarea> 
						 </td>
						 </tr> 
						 
						 </table>
						 </td>
						 
				<td class="subSectionBodyDotRight">&nbsp;</td>
				</tr>

                                <tr>

                                        <td colspan="3" class="subSectionBottom"></td>

                                </tr>


                </td>

        </tr>
