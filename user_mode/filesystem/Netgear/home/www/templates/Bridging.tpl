	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Bridging','wirelessBridgeSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
						<table class="tableStyle">
							<tr>
								<td style="width: 100%;">
									{include file="bandStrip.tpl"}
										<div id="IncludeTabBlock">
{if $config.CLIENT.status}
	{assign var="widthString" value="width: 606px;"}
				{php}
								$this->_tpl_vars['widthArrMoz'] = Array('1'   =>  '35.3', '2' =>  '44.3', '3' =>  '13.6');
								$this->_tpl_vars['widthArrIE'] = Array('1'   =>  '40.3', '2' =>  '45.3', '3' =>  '14.3');
				{/php}
{else}
	{assign var="widthString" value="width: 536px; _width: 555px;"}
				{php}
								$this->_tpl_vars['widthArrMoz'] = Array('0'   =>  '48.7', '1' =>  '49');
								$this->_tpl_vars['widthArrIE'] = Array('0'   =>  '49.9', '1' =>  '50');
				{/php}
{/if}
{if ((!($config.CLIENT.status)) AND (!($config.P2P.status)) AND (($config.P2MP.status)))}
	{assign var="widthString" value="width: 536px; _width: 455px;"}

				{php}
								$this->_tpl_vars['widthArrMoz'] = Array('1'   =>  '48.7', '2' =>  '100');
								$this->_tpl_vars['widthArrIE'] = Array('1'   =>  '49.9', '2' =>  '100');
				{/php}

{/if}
{if $config.TWOGHZ.status}
											<div  class="BlockContentTable" id="wlan1">
												<table style="{$widthString}" id="table_wlan1">
													{input_row label="Enable Wireless Bridging" id="wdsEnabled0" name="wdsOnEnabled0" type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan0.apMode selectCondition="!=0"}
													{input_row label="Local MAC Address" type="value" value=$data.monitor.wdsMacAddress.wlan0|replace:'-':':'}
													<input type="hidden" id="rogueApDetection" value="{$interface}">
													<input type="hidden" name="{$parentStr.wlanSettings.wlanSettingTable.wlan0.apMode}" id="apMode0" value="{$data.wlanSettings.wlanSettingTable.wlan0.apMode}">
                                                    <input type="hidden" name="{$parentStr.wlanSettings.wlanSettingTable.wlan0.operateMode}" id="operateMode0" value="{$data.wlanSettings.wlanSettingTable.wlan0.operateMode}">
													<input type="hidden" name="dummy" id="currentApMode0" value="{$data.wlanSettings.wlanSettingTable.wlan0.apMode}">
													<tr>
														<td colspan="2" style="margin: 0px; padding: 0px; width: 100%; white-space: nowrap;">
															<ul class="inlineSubTabs">
                                                                {foreach item=wdsDataItem from=$wdsData name="wdsDataLoop"}
                                                                    {assign var="cnt" value=$smarty.foreach.wdsDataLoop.iteration}
                                                                    {assign var="tabLabel" value=$wdsDataItem.tabLabel}
                                                                    {assign var="tabVal0" value=$wdsDataItem.tabVal0}
                                                                    {assign var="tabSelect0" value=$wdsDataItem.tabSelect0}
                                                                    {assign var="tabWdsVaps0" value=$wdsDataItem.noOfWdsVaps}
																<li id="includeSubTab0{$cnt}" style="width: {$widthArrMoz.$cnt}%; _width: {$widthArrIE.$cnt}%; text-align: left; white-space: nowrap;" {if $wdsDataItem.activeApMode0}class="Active"{/if}><input name="apmod1" id="wdsMode0" type="radio" value={$tabVal0} {if $tabSelect0}checked="checked"{/if}><b>{$tabLabel}</b></li>
                                                                {/foreach}
															</ul>
														</td>
													</tr>
													{if $config.CLIENT.status}
													<tr id="macClone_0" style="display: none;">
														<td colspan="2">
														<table class="BlockContent Trans" style="border: 1px solid #CCCCCC; margin-top: -2px;">
															{assign var="macClone" value=$data.staSettings.staSettingTable.wlan0.sta0.macClone}
															{input_row label="MAC Clone" id="macClone0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.macClone type="radio" options="1-Enable,0-Disable" value="$macClone" selectCondition="==$macClone" }
															{assign var="macCloneAddr" value=$data.staSettings.staSettingTable.wlan0.sta0.macCloneAddr|replace:'-':':'}
															{input_row label="MAC Clone Address" id="macCloneAddr0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.macCloneAddr type="text" value=$macCloneAddr|replace:'00:00:00:00:00:00':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="0==$macClone" validate="MACAddress^Presence"}
                                                   	    {if ($productId=='WN604')}
							    {input_row label="Roaming RSSI Threshold (0-95)" id="roamingRSSIThreshold0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.roamingSettings.rssiThreshold type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.roamingSettings.rssiThreshold size="5" maxlength="4" validate="Numericality, (( minimum: 0, maximum: 95, onlyInteger: true ))^Presence"}
                                                            {assign var="roamingRSSIVal" value=$data.staSettings.staSettingTable.wlan0.sta0.roamingSettings.rateThreshold}
                                                            {input_row label="Roaming Rate Threshold" id="roamingRateThreshold0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.roamingSettings.rateThreshold type="select" options=$roamingRateThresholdList selected=$roamingRSSIVal }
                                                            {input_row label="No Beacon Timeout (0-20000)" id="noBeaconTimeout0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.roamingSettings.noBeacon type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.roamingSettings.noBeacon size="5" maxlength="5" validate="Numericality, (( minimum: 0, maximum: 20000, onlyInteger: true ))^Presence"}
                                                            {input_row label="Hardware TX Retries (1-15)" id="hwTXRetries0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.roamingSettings.hardwareTxRetries type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.roamingSettings.hardwareTxRetries size="5" maxlength="5" validate="Numericality, (( minimum: 1, maximum: 15, onlyInteger: true ))^Presence"}
								{input_row label="Roam Debug Level (0-3)" id="roamDebugLevel0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.roamingSettings.debugLevel type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.roamingSettings.debugLevel size="5" maxlength="5" validate="Numericality, (( minimum: 0, maximum: 3, onlyInteger: true ))^Presence"}
                                							{assign var="backgrndScanStatus" value=$data.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanMode}
                                                            {input_row label="Background Scan" id="backgrndScanRadio0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanMode type="radio" options="1-On,0-Off" selectCondition="==$backgrndScanStatus" }
                                                            {assign var="scanType" value=$data.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanType}
                                                            {input_row label="Scan Type" id="scanTypeRadio0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanType type="radio" options="2-Active,3-Passive,4-Mixed" selectCondition="==$scanType" }
                                                            {input_row label="Background Scan Interval (100-6000)" id="bkGrdScanInterval0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanInterval type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanInterval size="5" maxlength="5" validate="Numericality, (( minimum: 100, maximum: 6000, onlyInteger: true ))^Presence"}
                                                            {input_row label="Background Scan Time Per Channel (5-100)" id="bkGrdScanTime0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanTimePerCha type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanTimePerCha size="5" maxlength="5" validate="Numericality, (( minimum: 5, maximum: 100, onlyInteger: true ))^Presence"}
                                                            {assign var="clearBSSList" value=$data.staSettings.staSettingTable.wlan0.sta0.scanSettings.clearBssList}
                                                            {input_row label="Clear BSS List When Scan" id="clearBSSListRadio0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.scanSettings.clearBssList type="radio" options="1-On,0-Off" selectCondition="==$clearBSSList" }
                                                            {input_row label="BSS Aging Period (0-65536)" id="bssAgingPeriod0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.scanSettings.bssAgeingPeriod type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.scanSettings.bssAgeingPeriod size="5" maxlength="5" validate="Numericality, (( minimum: 0, maximum: 65536, onlyInteger: true ))^Presence"}
                                                            {*==============*}
 <tr>

                                                                <td class="DatablockLabel">Background Scan Channel List</td>
                                                                <td class="DatablockContent">
                                                                        <table width="80%">
                                                                                <tr align="center"><td "width=10%">1</td><td "width=10%">2</td><td "width=10%">3</td><td "width=10%">4</td><td "width=10%">5</td><td "width=10%">6</td><td "width=10%">7</td><td "width=10%">8</td><td "width=10%">9</td><td "width=10%">10</td><td "width=10%">11</td></tr>
                                                                                <tr align="center">
                                                                                    <td><Input type="checkbox" id="bgChanList00" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>
                                                                                    <input type="hidden" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanChaList}" id="scanChanListString0" value="{$data.staSettings.staSettingTable.wlan0.sta0.scanSettings.scanChaList}">

                                                                                    <td><Input type="checkbox" id="bgChanList01" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList02" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList03" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList04" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList05" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList06" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList07" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList08" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList09" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList010" onclick="convertScanChanToString();">

                                                                                </tr>
                                                                        </table>

                                                                </td>
                                                        </tr>
                                                </tr>
						{/if}
                                                            {*==============*}
														</table>
														</td>
													</tr>
													{/if}
													<tr>
														<td colspan="2">
															<table class="BlockContentTable Trans" style="border-top: 1px solid #CCCCCC; margin-top: -1px;" id="profilesList_0">
																<thead>
																	<tr id="WCArow_0">
																		<td class="DatablockLabel" colspan="3" style="border-top: 0px; border-right: 0px;">Enable Wireless Client Association</td>
																		<td class="DatablockContent" colspan="2" style="border-top: 0px; border-left: 0px;"><input name="WCAenabled0" id="WCAenabled0" type="checkbox" {if $data.wlanSettings.wlanSettingTable.wlan0.apMode eq '2'}checked="checked"{/if}></td>
																	</tr>
																	<tr>
																		<th style="width:5%">&nbsp;</th>
																		<th style="width:5%">#</th>
																		<th style="width:45%">Profile Name</th>
																		<th style="width:35%">Security</th>
																		<th style="width:10%" class="Last">Enable</th>
																	</tr>
																</thead>
																<tbody id="tbody_0">
																	{foreach name=profiles key=key item=value from=$data.wdsSettings.wdsSettingTable.wlan0}
																	{assign var="wdsIndex" value=$value.wdsIndex-1}
																	{if ($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
																	<tr  disabled="disabled">
																		<td ><input type="radio" id="profileid_0" name="profileid0" value="{$wdsIndex}" {if $value.wdsIndex eq '1'}checked="checked"{/if}></td>
																		<td >{$value.wdsIndex}</td>
																		<td>{$value.wdsProfileName}</td>
																		{php}
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		{/php}
																		<td>{$wdsAuthType}</td>
																		<td><input type="checkbox" id=cb_wdsProfileStatus{$wdsIndex} name="cb_wdsProfileStatus0{$wdsIndex}" onclick="$('wdsProfileStatus0{$wdsIndex}_0').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" {if $value.wdsProfileStatus eq '1'}checked="checked"{/if}><input type="hidden" name="{php}echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan0]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus];{/php}" id="wdsProfileStatus0{$wdsIndex}_0" value="{$value.wdsProfileStatus}"></td>
																	</tr>
																	{else}
																	<tr {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="radio" id="profileid_0" name="profileid0" value="{$wdsIndex}" {if $value.wdsIndex eq '1'}checked="checked"{/if}></td>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.wdsIndex}</td>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.wdsProfileName}</td>
																		{php}
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		{/php}
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$wdsAuthType}</td>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id=cb_wdsProfileStatus0{$wdsIndex} name="cb_wdsProfileStatus{$wdsIndex}" onclick="$('wdsProfileStatus0{$wdsIndex}_0').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" {if $value.wdsProfileStatus eq '1'}checked="checked"{/if}><input type="hidden" name="{php}echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan0]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus];{/php}" id="wdsProfileStatus0{$wdsIndex}_0" value="{$value.wdsProfileStatus}"></td>
																	</tr>
																	{/if}

																	{/foreach}
																</tbody>
															</table>
														</td>
													</tr>
												</table>
											</div>
{/if}
<!--@@@FIVEGHZSTART@@@-->
{if $config.FIVEGHZ.status}
											<div  class="BlockContentTable" {if $config.TWOGHZ.status}style="display:none;"{/if} id="wlan2">
												<table style="{$widthString}" id="table_wlan2">
													{input_row label="Enable Wireless Bridging" id="wdsEnabled1" name="wdsOnEnabled1" type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan1.apMode selectCondition="!=0"}
													{input_row label="Local MAC Address" type="value" value=$data.monitor.wdsMacAddress.wlan1|replace:'-':':'}
													<input type="hidden" id="rogueApDetection" value="{$interface}">
													<input type="hidden" name="{$parentStr.wlanSettings.wlanSettingTable.wlan1.apMode}" id="apMode1" value="{$data.wlanSettings.wlanSettingTable.wlan1.apMode}">
													<input type="hidden" name="dummy" id="currentApMode1" value="{$data.wlanSettings.wlanSettingTable.wlan1.apMode}">
													<tr>
														<td colspan="2" style="margin: 0px; padding: 0px; width: 100%; white-space: nowrap;">
															<ul class="inlineSubTabs">
                                                                {foreach item=wdsDataItem from=$wdsData name="wdsDataLoop"}
                                                                    {if !($wdsDataItem.tabLabel eq "Client")}
                                                                    {assign var="cnt" value=$smarty.foreach.wdsDataLoop.iteration}
                                                                    {assign var="tabLabel" value=$wdsDataItem.tabLabel}
                                                                    {assign var="tabVal1" value=$wdsDataItem.tabVal1}
                                                                    {assign var="tabSelect1" value=$wdsDataItem.tabSelect1}
                                                                    {assign var="tabWdsVaps1" value=$wdsDataItem.noOfWdsVaps}
																<li id="includeSubTab1{$cnt}" style="width: {$widthArrMoz.$cnt}%; _width: {$widthArrIE.$cnt}%; text-align: left; white-space: nowrap;" {if $wdsDataItem.activeApMode1}class="Active"{/if}><input name="apmod2" id="wdsMode1" type="radio" value={$tabVal1} {if $tabSelect1}checked="checked"{/if}><b>{$tabLabel}</b></li>
                                                                    {/if}
                                                                {/foreach}
															</ul>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<table class="BlockContentTable Trans" style="border-top: 1px solid #CCCCCC; margin-top: -1px;" id="profilesList_1">
																<thead>
																	<tr id="WCArow_1">
																		<td class="DatablockLabel" colspan="3" style="border-top: 0px; border-right: 0px;">Enable Wireless Client Association</td>
																		<td class="DatablockContent" colspan="2" style="border-top: 0px; border-left: 0px;"><input name="WCAenabled1" id="WCAenabled1" type="checkbox" {if $data.wlanSettings.wlanSettingTable.wlan1.apMode eq '2'}checked="checked"{/if}></td>
																	</tr>
																	<tr>
																		<th style="width:5%">&nbsp;</th>
																		<th style="width:5%">#</th>
																		<th style="width:45%">Profile Name</th>
																		<th style="width:35%">Security</th>
																		<th style="width:10%" class="Last">Enable</th>
																	</tr>
																</thead>
																<tbody id="tbody_1">
																	{foreach name=profiles key=key item=value from=$data.wdsSettings.wdsSettingTable.wlan1}
																	{assign var="wdsIndex" value=$value.wdsIndex-1}
																	{if ($data.wlanSettings.wlanSettingTable.wlan1.apMode eq 5)}
																	<tr  disabled="disabled">
																		<td ><input type="radio" id="profileid_1" name="profileid1" value="{$wdsIndex}" {if $value.wdsIndex eq '1'}checked="checked"{/if}></td>
																		<td >{$value.wdsIndex}</td>
																		<td>{$value.wdsProfileName}</td>
																		{php}
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		{/php}
																		<td>{$wdsAuthType}</td>
																		<td><input type="checkbox" id=cb_wdsProfileStatus{$wdsIndex} name="cb_wdsProfileStatus1{$wdsIndex}" onclick="$('wdsProfileStatus{$wdsIndex}_1').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" {if $value.wdsProfileStatus eq '1'}checked="checked"{/if}><input type="hidden" name="{php}echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan1]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus];{/php}" id="wdsProfileStatus{$wdsIndex}_1" value="{$value.wdsProfileStatus}"></td>
																	</tr>
																	{else}
																	<tr {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="radio" id="profileid_1" name="profileid1" value="{$wdsIndex}" {if $value.wdsIndex eq '1'}checked="checked"{/if}></td>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.wdsIndex}</td>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.wdsProfileName}</td>
																		{php}
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		{/php}
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$wdsAuthType}</td>
																		<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id=cb_wdsProfileStatus1{$wdsIndex} name="cb_wdsProfileStatus{$wdsIndex}" onclick="$('wdsProfileStatus{$wdsIndex}_1').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" {if $value.wdsProfileStatus eq '1'}checked="checked"{/if}><input type="hidden" name="{php}echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan1]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus];{/php}" id="wdsProfileStatus{$wdsIndex}_1" value="{$value.wdsProfileStatus}"></td>
																	</tr>
																	{/if}

																	{/foreach}
																</tbody>
															</table>
														</td>
													</tr>
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

		{if $config.TWOGHZ.status}
			{if $data.wlanSettings.wlanSettingTable.wlan0.channel eq '0' AND ($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 0 OR $data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
				var disableWDSonChannel0 = true;
			{/if}
			{if $data.wlanSettings.wlanSettingTable.wlan0.radioStatus neq '0'}
				{if  ($data.wlanSettings.wlanSettingTable.wlan0.channel eq '0')}
					{assign var="checkWlan0ApStatus" value="disabled='disabled'"}
					{assign var="checkEditDisable" value="disabled='disabled'"}
				{/if}
			{/if}
		{/if}
//<!--@@@FIVEGHZSTART@@@-->
		{if $config.FIVEGHZ.status}
			{if $data.wlanSettings.wlanSettingTable.wlan1.channel eq '0' AND ($data.wlanSettings.wlanSettingTable.wlan1.apMode eq 0 OR $data.wlanSettings.wlanSettingTable.wlan1.apMode eq 5)}
				var disableWDSonChannel1 = true;
			{/if}
			{if $data.wlanSettings.wlanSettingTable.wlan1.radioStatus neq '0'}
				{if  ($data.wlanSettings.wlanSettingTable.wlan1.channel eq '0')}
					{assign var="checkWlan1ApStatus" value="disabled='disabled'"}
					{assign var="checkEditDisable" value="disabled='disabled'"}
				{/if}
			{/if}
		{/if}

//<!--@@@FIVEGHZEND@@@-->
		{literal}
			function doEdit()
			{
				if(window.top.frames['action'].$('edit').src.indexOf('edit_on')!== -1)
				{
					{/literal}
					prepareURL('{$navigation.4}','{$navigation.3}','{$navigation.2}','{$navigation.1}','profileid',String(parseInt(form.activeTab)-1),'wdsprofile');
					{literal}
					return false;
				}
				else
				{
					window.top.frames['action'].$('edit').disabled=true;
				}
			}
		{/literal}


		{if $checkEditDisable neq ''}
			window.top.frames['action'].$('edit').disabled=true;
			window.top.frames['action'].$('edit').src="images/edit_off.gif";
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

		{if $config.TWOGHZ.status}

		{literal}
			$('cb_wdsEnabled0').observe('click',form.tab1.wdsOnEnable.bindAsEventListener(form.tab1));
			var i = 1;
			$RD('wdsMode0').each( function(radio) {
         {/literal}
            {if $data.radioStatus0 eq '1'}
         {literal}
                    $(radio).observe('click',form.tab1.activateSubTab.bindAsEventListener(form.tab1, i++));
         {/literal}
            {/if}
         {literal}
			});
        {/literal}
            {if $config.CLIENT.status}
                {literal}
                $RD('macClone0').each( function(radio) {
                    $(radio).observe('click',form.tab1.toggleMACCloneAddress.bindAsEventListener(form.tab1));
                });
                {/literal}
            {/if}
         {literal}
			$('WCAenabled0').observe('click',form.tab1.setApModeWCA.bindAsEventListener(form.tab1));
		{/literal}
		{/if}
//<!--@@@FIVEGHZSTART@@@-->
		{if $config.FIVEGHZ.status}
	{literal}
			$('cb_wdsEnabled1').observe('click',form.tab2.wdsOnEnable.bindAsEventListener(form.tab2));
			var i = 1;
			$RD('wdsMode1').each( function(radio) {
     {/literal}
                {if $data.radioStatus1 eq '1'}
     {literal}
                    $(radio).observe('click',form.tab2.activateSubTab.bindAsEventListener(form.tab2, i++));
     {/literal}
                {/if}
     {literal}
			});
        {/literal}
            {if $config.CLIENT.status}
            {literal}
                $RD('macClone1').each( function(radio) {
                    $(radio).observe('click',form.tab2.toggleMACCloneAddress.bindAsEventListener(form.tab2));
                });
            {/literal}
            {/if}
        {literal}
			$('WCAenabled1').observe('click',form.tab2.setApModeWCA.bindAsEventListener(form.tab2));
		{/literal}
		{/if}

//<!--@@@FIVEGHZEND@@@-->

{if $config.CLIENT.status}
            {literal}
                convertbkgrdScanChanList();
            {/literal}
{/if}
	-->
	</script>
