	{if ($config.TWOGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan0.apMode eq '1' OR $data.wlanSettings.wlanSettingTable.wlan0.apMode eq '5' OR $data.wlanSettings.wlanSettingTable.wlan0.apMode eq '4') and $data.wlanSettings.wlanSettingTable.wlan0.radioStatus eq '1'}
		{assign var="checkWlan0ApStatus" value="disabled='disabled'"}
		{assign var="checkEditDisable" value="disabled='disabled'"}
	{/if}
	{if ($config.FIVEGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan1.apMode eq '1' OR $data.wlanSettings.wlanSettingTable.wlan1.apMode eq '5' OR $data.wlanSettings.wlanSettingTable.wlan1.apMode eq '4') and $data.wlanSettings.wlanSettingTable.wlan1.radioStatus eq '1'}
		{assign var="checkWlan1ApStatus" value="disabled='disabled'"}
		{assign var="checkEditDisable" value="disabled='disabled'"}
	{/if}
	{if $checkEditDisable neq ''}
	<input type='hidden' id='formDisabled' value='true'>
	{/if}

<!--@@@DUAL_CONCURRENTSTART@@@-->
{if $config.DUAL_CONCURRENT.status}
    {if ($config.TWOGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan0.apMode eq '1' OR $data.wlanSettings.wlanSettingTable.wlan0.apMode eq '3' ) and $data.wlanSettings.wlanSettingTable.wlan0.radioStatus eq '1'}
        <input type='hidden' id='formTwoGHzDisable' value='true'>
    {else}
        <input type='hidden' id='formTwoGHzDisable' value='false'>
    {/if}
    {if ($config.FIVEGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan1.apMode eq '1' OR $data.wlanSettings.wlanSettingTable.wlan1.apMode eq '3' ) and $data.wlanSettings.wlanSettingTable.wlan1.radioStatus eq '1'}
        <input type='hidden' id='formFiveGHzDisable' value='true'>
    {else}
        <input type='hidden' id='formFiveGHzDisable' value='false'>
    {/if}
{/if}
<!--@@@DUAL_CONCURRENTEND@@@-->


	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3">
						<table class='tableStyle'>
							<tr>
								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Profile Settings</td>
								<td class='subSectionTabTopRight spacer40Percent' style="font-size: 11px;">{*<input type='image' src='images/edit_on.gif' name='edit' id='edit' value='Edit' $checkEditDisable style="margin-right: 2px;" onclick="prepareURL('{$nav4}','{$nav3}','{$nav2}','{$nav1}','profileid',$('currentInterfaceNum').value);return false;">*}<a href='javascript: void(0);' onclick="showHelp('Security Profiles','securityProfiles');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>
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
									{include file="bandStrip.tpl"}
									<div id="IncludeTabBlock">
{if $config.TWOGHZ.status}
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
												{foreach name=profiles key=key item=value from=$data.vapSettings.vapSettingTable.wlan0}
												{assign var="vapIndex" value=$value.vapIndex-1}
												<tr {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="radio" id="profileid_0" name="profileid0" value="{$vapIndex}" {if $value.vapIndex eq '1'}checked="checked"{/if} {$checkWlan0ApStatus}></td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.vapIndex}</td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.vapProfileName}</td>
													{php}
													$this->_tpl_vars['ssid'] = $this->_tpl_vars['value']['ssid'];
													{/php}
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$ssid}</td>
													{php}
														$this->_tpl_vars['authType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['authenticationType']];
													{/php}
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$authType}</td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.vlanID}</td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id=cb_vapProfileStatus{$vapIndex} name="cb_vapProfileStatus{$vapIndex}" {$checkWlan0ApStatus} onclick="setActiveContent();$('vapProfileStatus{$vapIndex}_0').value=(this.checked)?'1':'0';" value="1" {if $value.vapProfileStatus eq '1'}checked="checked"{/if} {if $value.vapIndex eq '8' AND $data.monitor.productId eq 'WG102'}disabled="true"{/if}><input type="hidden" name="{php}echo $this->_tpl_vars[parentStr][vapSettings][vapSettingTable][wlan0]['vap'.$this->_tpl_vars[vapIndex]][vapProfileStatus];{/php}" id="vapProfileStatus{$vapIndex}_0" value="{$value.vapProfileStatus}"></td>
												</tr>
												{/foreach}
											</table>
										</div>
{/if}
<!--@@@FIVEGHZSTART@@@-->
{if $config.FIVEGHZ.status}
										<div  class="BlockContentTable" {if $config.TWOGHZ.status}style="display:none;"{/if} id="wlan2">
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
												{foreach name=profiles key=key item=value from=$data.vapSettings.vapSettingTable.wlan1}
												{assign var="vapIndex" value=$value.vapIndex-1}
												<tr {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="radio" id="profileid_1" name="profileid1" value="{$vapIndex}" {if $value.vapIndex eq '1'}checked="checked"{/if} {$checkWlan1ApStatus}></td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.vapIndex}</td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.vapProfileName}</td>
													{php}
													$this->_tpl_vars['ssid'] = $this->_tpl_vars['value']['ssid'];
													{/php}
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$ssid}</td>
													{php}
														$this->_tpl_vars['authType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['authenticationType']];
													{/php}
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$authType}</td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.vlanID}</td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id=cb_vapProfileStatus1{$vapIndex} name="cb_vapProfileStatus1{$vapIndex}" {$checkWlan1ApStatus} onclick="setActiveContent();$('vapProfileStatus{$vapIndex}_1').value=(this.checked)?'1':'0';" value="1" {if $value.vapProfileStatus eq '1'}checked="checked"{/if}><input type="hidden" name="{php}echo $this->_tpl_vars[parentStr][vapSettings][vapSettingTable][wlan1]['vap'.$this->_tpl_vars[vapIndex]][vapProfileStatus];{/php}" id="vapProfileStatus{$vapIndex}_1" value="{$value.vapProfileStatus}"></td>
												</tr>
												{/foreach}
											</table>
										</div>
{/if}
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
        var prevInterface = {$smarty.post.previousInterfaceNum|default:"''"};
		var buttons = new buttonObject();
		buttons.getStaticButtons(['edit']);
		{literal}
		if ($('formDisabled') != undefined) {
			disableButtons(["edit"]);
		}

		function doEdit()
		{
			if(window.top.frames['action'].$('edit').src.indexOf('edit_on')!== -1)
			{
				{/literal}
				prepareURL('{$navigation.4}','{$navigation.3}','{$navigation.2}','{$navigation.1}','profileid',String(parseInt(form.activeTab)-1));
				{literal}
				return false;
			}
			else
			{
				window.top.frames['action'].$('edit').disabled=true;
			}
		}
		{/literal}
//		toggleDisplay('{$interface}');
		{if ($config.TWOGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5) OR ($config.FIVEGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan1.apMode eq 5)}
			disableButtons(["edit"]);
		{/if}
			var form = new formObject();
{literal}
            if (prevInterface != '') {
                    if(prevInterface == '1'){
                        form.tab1.activate();
                    }
                    else if(prevInterface == '2'){
                        form.tab2.activate();
                    }
             }
             else {
{/literal}
            {if $config.TWOGHZ.status}
                    form.tab1.activate();
            {/if}
//<!--@@@FIVEGHZSTART@@@-->
            {if $config.FIVEGHZ.status}
		{if !$config.TWOGHZ.status}
                        form.tab2.activate();
                {/if}
                {if $data.radioStatus1 eq '1' AND $data.radioStatus0 neq '1'}
                    form.tab2.activate();
                {/if}
            {/if}
//<!--@@@FIVEGHZEND@@@-->
{literal}
            }
{/literal}
	-->
	</script>
