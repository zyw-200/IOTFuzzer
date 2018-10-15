<?php /* Smarty version 2.6.18, created on 2012-01-02 12:20:13
         compiled from ChangePassword.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'ChangePassword.tpl', 26, false),)), $this); ?>
<?php 
	$confdEnable = true;
	if ($confdEnable) {
			$file = '/tmp/sessionid';
			if (!file_exists($file)) return '';
			$fp = fopen($file, "rb");
			if (!$fp) return '';
			$str = file_get_contents($file);
			fclose($fp);
			$strArr = explode(",",$str);
			$this->_tpl_vars['user_login'] = $strArr[2];
			$this->_tpl_vars['user_identity'] = "user".$strArr[4].'pword';
	}
 ?>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Change Password','changePassword')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
						<?php if ($this->_tpl_vars['user_login'] == 'admin'): ?>
							<?php echo smarty_function_input_row(array('label' => 'Current Password','id' => 'oldAdminPasswd','name' => 'oldAdminPasswd','type' => 'password','value' => "",'size' => '20','maxlength' => '32','validate' => "Presence^Ascii",'onchange' => "if (this.value!='') $('dummy').setAttribute('checked',true);"), $this);?>

							<?php echo smarty_function_input_row(array('label' => 'New Password','id' => 'adminPasswd','name' => $this->_tpl_vars['parentStr']['basicSettings']['adminPasswd'],'type' => 'password','value' => "",'size' => '20','maxlength' => '32','validate' => "Presence^validPassword, (( match: 'adminPasswd' )),(( onlyIfChecked: 'dummy' ))^Ascii"), $this);?>

							<?php echo smarty_function_input_row(array('label' => 'Repeat New Password','id' => 'adminPasswdConfirm','name' => 'adminPasswdConfirm','type' => 'password','value' => "",'size' => '20','maxlength' => '32','validate' => "Confirmation, (( match: 'adminPasswd' ))"), $this);?>

							<!--?php $passwd = explode(' ', conf_get('system:basicSettings:adminPasswd'));
							$passwd = conf_decrypt($passwd[1]);
							if($passwd !='password'){?-->
							<?php echo smarty_function_input_row(array('label' => 'Restore Default Password','id' => 'restorePassword','name' => 'restorePassword','type' => 'radio','options' => "1-Yes,0-No",'onclick' => "graysomething(this);$('dummy').checked=(this.value=='1')?false:true;",'selectCondition' => "==0"), $this);?>
							<!--?php };?-->

							<input type="hidden" id='dummy' checked="false">
						 <?php endif; ?>
						 <?php if ($this->_tpl_vars['user_login'] != 'admin'): ?>
							<?php echo smarty_function_input_row(array('label' => 'Current Password','id' => 'oldUserPasswd','name' => 'oldUserPasswd','type' => 'password','value' => "",'size' => '20','maxlength' => '32','validate' => "Presence^Ascii",'onchange' => "if (this.value!='') $('dummy').setAttribute('checked',true);"), $this);?>

							<tr>
							<td class="DatablockLabel">New Password</td>
							<td class="DatablockContent"><input name="system['userSettings']['<?php echo $this->_tpl_vars['user_identity']; ?>
']" id="userPasswd" class="input input" label="New Password" maxlength="32" onkeydown="setActiveContent();" size="20" validate="Presence^AlphaNumericWithHU" value="" type="password"></td>							
							</tr>
							<?php echo smarty_function_input_row(array('label' => 'Repeat New Password','id' => 'userPasswdConfirm','name' => 'userPasswdConfirm','type' => 'password','value' => "",'size' => '20','maxlength' => '32','validate' => "Confirmation, (( match: 'userPasswd' ))"), $this);?>

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