{php}
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
{/php}
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
						{if $user_login eq admin}
							{input_row label="Current Password" id="oldAdminPasswd" name="oldAdminPasswd" type="password" value="" size="20" maxlength="32" validate="Presence^Ascii" onchange="if (this.value!='') $('dummy').setAttribute('checked',true);"}
							{input_row label="New Password" id="adminPasswd" name='adminPasswd' type="password" value=""  size="20" maxlength="32" validate="Presence, (( onlyIfChecked: 'dummy' ))^Ascii"}
							{input_row label="Repeat New Password" id="adminPasswdConfirm" name="adminPasswdConfirm" type="password" value=""  size="20" maxlength="32"  validate="Confirmation, (( match: 'adminPasswd' ))"}

							{input_row label="Restore Default Password" id="restorePassword" name="restorePassword" type="radio" options="1-Yes,0-No" onclick="graysomething(this);$('dummy').checked=(this.value=='1')?false:true;" selectCondition="==0"}
							<input type="hidden" id='dummy' checked="false">
						 {/if}
						 {if $user_login neq admin}
							{input_row label="Current Password" id="oldUserPasswd" name="oldUserPasswd" type="password" value="" size="20" maxlength="32" validate="Presence^Ascii" onchange="if (this.value!='') $('dummy').setAttribute('checked',true);"}
							<tr>
							<td class="DatablockLabel">New Password</td>
							<td class="DatablockContent"><input name="system['userSettings']['{$user_identity}']" id="userPasswd" class="input input" label="New Password" maxlength="32" onkeydown="setActiveContent();" size="20" validate="Presence^AlphaNumericWithHU" value="" type="password"></td>							
							</tr>
							{input_row label="Repeat New Password" id="userPasswdConfirm" name="userPasswdConfirm" type="password" value=""  size="20" maxlength="32"  validate="Confirmation, (( match: 'userPasswd' ))"}
						 {/if}
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
