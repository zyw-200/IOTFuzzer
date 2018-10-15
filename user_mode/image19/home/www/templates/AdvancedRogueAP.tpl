		<tr>
				<td>
						<table class="tableStyle">
								<tr>
										<td colspan="3"><script>tbhdr('Rogue AP','rogueAPDetection')</script></td>
								</tr>
								<tr>
										<td class="subSectionBodyDot">&nbsp;</td>
										<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
												<table class="tableStyle">
										<tr>
												<td>
														{include file="bandStrip.tpl"}
														<div id="IncludeTabBlock">
                                                                                                                    <input type="hidden" name="apMode" id="apMode" value="{$data.wlanSettings.wlanSettingTable.wlan0.apMode}">
{if $config.TWOGHZ.status}
																<div class="BlockContent" id="wlan1">
																		<table class="BlockContent" id="table_wlan1">
																				{input_row label="Turn Rogue AP Detection On" id="rogueApDetection0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.rogueApDetection type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan0.rogueApDetection selectCondition="!=0" size="16" maxlength="15" }

																				{input_row label="Import AP List from a file" id="merge_rogue_ap_list0" name="merge_rogue_ap_list0" type="radio" options="1-Replace,2-Merge" selected="==1"}
																				<input type="hidden" id="rogueApDetection" value="{$interface}">
																				<tr>
																						<td class="DatablockLabel">&nbsp;</td>
																						<td class="DatablockContent"><input class="input" id="uploadFile0" name="macListFile0" type="file" oncontextmenu="return false" onkeydown="this.blur()" onpaste="return false" onchange="setActiveContent();"></td>
																				</tr>
											<tr>
												<td colspan="2" style="padding: 0px; margin: 0px;">
																		<table class="BlockContent Trans">
																				<tr>
																						<td style="vertical-align: top; width: 100%;">
																								{data_header label="Known AP List" backgroundColor="transparent" headerType="inline" actionButtons="<input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('knownAPList0','0');setActiveContent(); return false;\" value=\"Delete\">"}
																						</td>
																						<td style="padding: 0px; margin: 0px;vertical-align: middle; width: 37px; text-align: center;">&nbsp;</td>
																						<td style="vertical-align: top; width: 100%;">
																						{data_header label="Unknown AP List" backgroundColor="transparent" headerType="inline"}																			</td>
																					</tr>
																				<tr>
																						{php}
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['knownApTable']['wlan0']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																						}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																							}
																						{/php}
																						<td style="vertical-align: top; padding: 0px;">
																								<div  class="BlockContentTable" id="div_knownAPList0" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 370px;">
																										<table class="BlockContentTable" id="knownAPList0">
																												<thead>
																														<tr id="headRow">
																																{sortable_header_row sortable="false" tableid="knownAPList0" rowid="0" content="<input type=\"checkbox\" id=\"knownAPitem0_main\" onclick=\"toggleCheckBoxes('knownAPitem0', this);\">"}
																																{sortable_header_row sortable="true" tableid="knownAPList0" rowid="1" content="MAC Address"}
																																{sortable_header_row sortable="true" tableid="knownAPList0" rowid="2" content="SSID"}
																																{sortable_header_row sortable="true" tableid="knownAPList0" rowid="3" last="true" content="Channel"}
																														</tr>
																												</thead>
																												<tbody>
																														{foreach name=knownAP key=key item=value from=$data.monitor.apList.knownApTable.wlan0}
																																<tr {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="knownAPitem0" onclick="if (!this.checked) $('knownAPitem0_main').checked=false;"></td>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper}</td>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>{if $value.knownApSsid neq '0'}{$value.knownApSsid|wordwrap:16:'<br />':true}{/if}&nbsp;</td>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>{if $value.knownApChannel neq '0'}{$value.knownApChannel}{/if}&nbsp;</td>
																																</tr>
																														{php}
																																$this->_tpl_vars['knownApList_0'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																														{/php}
																														{/foreach}
																														<input id="rowCount0" name="rowCount0" value="{$smarty.foreach.knownAP.total}" type="hidden">
																														{if $config.WNDAP660.status || $config.WNDAP620.status || $config.JWAP603.status}
																														<input type="hidden" name="Deauth0" value="" id="Deauth0">
																														{/if}
																														<input id="addedAPs0" name="system[apList][knownApTable][wlan0]" value="" type="hidden">
																														<input id="deletedAPs0" name="delete[apList][knownApTable][wlan0]" value="" type="hidden">
																														<input id="oldKnownAps0" name="apList[knownApTable][wlan0]" value="{$knownApList_0}" type="hidden">
																												</tbody>
																										</table>
																								</div>
																						</td>
																						<td style="padding: 2px;vertical-align: middle; width: 37px; text-align: center;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addKnownAP(true,0);setActiveContent();return false;"></td>
																						{php}
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['unknownApTable']['wlan0']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																								}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																							}
																						{/php}
																						<td style="vertical-align: top;padding: 0px;">
																								<div  class="BlockContentTable" id="div_unknownAPList0" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 370px;">
																										<table class="BlockContentTable" id="unknownAPList0">
																												<thead>
																														<tr id="headRow">
																																{sortable_header_row sortable="false" tableid="unknownAPList0" rowid="0" content="<input type=\"checkbox\" id=\"unknownAPitem0_main\" onclick=\"toggleCheckBoxes('unknownAPitem0', this);\">"}
																																{sortable_header_row sortable="true" tableid="unknownAPList0" rowid="1" content="MAC Address"}
																																{sortable_header_row sortable="true" tableid="unknownAPList0" rowid="2" content="SSID"}
																																{sortable_header_row sortable="true" tableid="unknownAPList0" rowid="3" last="true" content="Channel"}
																														</tr>
																												</thead>
																												<tbody id="offTblBdy">
																														{foreach name=unknownAP key=key item=value from=$data.monitor.apList.unknownApTable.wlan0}
																																<tr {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="unknownAPitem0" onclick="if (!this.checked) $('unknownAPitem0_main').checked=false;"></td>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper}</td>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApSsid|wordwrap:16:'<br />':true}</td>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApChannel}</td>
																																</tr>
																														{/foreach}
																												</tbody>
																										</table>
																								</div>
																						</td>
																				</tr>
																		</table>
												</td>
											</tr>
										</table>
																</div>
{/if}
{if $config.FIVEGHZ.status}
																<div  class="BlockContent" id="wlan2">
																		<table class="BlockContent" id="table_wlan2">
																				{input_row label="Turn Rogue AP Detection On" id="rogueApDetection1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.rogueApDetection type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan1.rogueApDetection selectCondition="!=0" size="16" maxlength="15" }

																				{input_row label="Import AP List from a file" id="merge_rogue_ap_list1" name="merge_rogue_ap_list1" type="radio" options="1-Replace,2-Merge" selected="==1"}

																				<tr>
																						<td class="DatablockLabel">&nbsp;</td>
																						<td class="DatablockContent"><input class="input" id="uploadFile1" name="macListFile1" type="file" onchange="setActiveContent();"></td>
																				</tr>
											<tr>
												<td colspan="2" style="padding: 0px; margin: 0px;">
																		<table class="BlockContent Trans">
																				<tr>
																					<td style="vertical-align: top; width: 100%;">
																								{data_header label="Known AP List" backgroundColor="transparent" headerType="inline" actionButtons="<input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('knownAPList1','1');setActiveContent();return false;\" value=\"Delete\">"}
																						</td>
																						<td style="padding: 0px; margin: 0px;vertical-align: middle; width: 37px; text-align: center;">&nbsp;</td>
																						<td style="vertical-align: top; width: 100%;">
																						{data_header label="Unknown AP List" backgroundColor="transparent" headerType="inline"}
																					</td>
																				</tr>
																				<tr>
																						{php}
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['knownApTable']['wlan1']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																								}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																								}
																						{/php}
																						<td style="vertical-align: top; padding: 0px;">
																								<div  class="BlockContentTable" id="div_knownAPList1" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 370px;">
																										<table class="BlockContentTable" id="knownAPList1">
																												<thead>
																														<tr id="headRow">
																																{sortable_header_row sortable="false" tableid="knownAPList1" rowid="0" content="<input type=\"checkbox\" id=\"knownAPitem1_main\" onclick=\"toggleCheckBoxes('knownAPitem1', this);\">"}
																																{sortable_header_row sortable="true" tableid="knownAPList1" rowid="1" content="MAC Address"}
																																{sortable_header_row sortable="true" tableid="knownAPList1" rowid="2" content="SSID"}
																																{sortable_header_row sortable="true" tableid="knownAPList1" rowid="3" last="true" content="Channel"}
																														</tr>
																												</thead>
																												<tbody>
																														{foreach name=knownAP key=key item=value from=$data.monitor.apList.knownApTable.wlan1}
																																<tr {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="knownAPitem1" onclick="if (!this.checked) $('knownAPitem1_main').checked=false;"></td>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper}</td>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>{if $value.knownApSsid neq '0'}{$value.knownApSsid|wordwrap:16:'<br />':true}{/if}&nbsp;</td>
																																		<td {if $smarty.foreach.knownAP.iteration%2 eq 0}class="Alternate"{/if}>{if $value.knownApChannel neq '0'}{$value.knownApChannel}{/if}&nbsp;</td>
																																</tr>
																														{php}
																																$this->_tpl_vars['knownApList_1'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																														{/php}
																														{/foreach}
																														<input id="rowCount1" name="rowCount1" value="{$smarty.foreach.knownAP.total}" type="hidden">
																														{if $config.WNDAP660.status || $config.WNDAP620.status || $config.JWAP603.status}
																														<input type="hidden" name="Deauth1" value="" id="Deauth1">
																														{/if}
																														<input id="addedAPs1" name="system[apList][knownApTable][wlan1]" value="" type="hidden">
																														<input id="deletedAPs1" name="delete[apList][knownApTable][wlan1]" value="" type="hidden">
																														<input id="oldKnownAps1" name="apList[knownApTable][wlan1]" value="{$knownApList_1}" type="hidden">
																												</tbody>
																										</table>
																								</div>
																						</td>
																						<td style="padding: 2px;vertical-align: middle; width: 37px; text-align: center;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addKnownAP(true,1);setActiveContent();return false;"></td>
																						{php}
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['unknownApTable']['wlan1']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																								}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																								}
																						{/php}
																						<td style="vertical-align: top;padding: 0px;">
																								<div  class="BlockContentTable" id="div_unknownAPList1" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 370px;">
																										<table class="BlockContentTable" id="unknownAPList1">
																												<thead>
																														<tr id="headRow">
																																{sortable_header_row sortable="false" tableid="unknownAPList1" rowid="0" content="<input type=\"checkbox\" id=\"unknownAPitem1_main\" onclick=\"toggleCheckBoxes('unknownAPitem1', this);\">"}
																																{sortable_header_row sortable="true" tableid="unknownAPList1" rowid="1" content="MAC Address"}
																																{sortable_header_row sortable="true" tableid="unknownAPList1" rowid="2" content="SSID"}
																																{sortable_header_row sortable="true" tableid="unknownAPList1" rowid="3" last="true" content="Channel"}
																														</tr>
																												</thead>
																												<tbody id="offTblBdy">
																														{foreach name=unknownAP key=key item=value from=$data.monitor.apList.unknownApTable.wlan1}
																																<tr {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="unknownAPitem1" onclick="if (!this.checked) $('unknownAPitem1_main').checked=false;"></td>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper}</td>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApSsid|wordwrap:16:'<br />':true}</td>
																																		<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApChannel}</td>
																																</tr>
																														{/foreach}
																												</tbody>
																										</table>
																								</div>
																						</td>
																				</tr>
																		</table>
												</td>
											</tr>
										</table>
																</div>
{/if}
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
		var form = new formObject();
		form.tab1.activate();
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
                {if $data.radioStatus0 eq '1'}
                    form.tab1.activate();
                {/if}
            {/if}
            {if $config.FIVEGHZ.status}
		{if !$config.TWOGHZ.status}
                        form.tab2.activate();
                {/if}
                {if $data.radioStatus1 eq '1' AND $data.radioStatus0 neq '1'}
                    form.tab2.activate();
                {/if}
            {/if}
{literal}
            }
{/literal}
        {if $config.TWOGHZ.status}
		{literal}
			$('cb_rogueApDetection0').observe('click',form.tab1.toggleFields.bindAsEventListener(form.tab1,'rogueApDetection'));

		{/literal}
		{/if}
		{if $config.FIVEGHZ.status}
		{literal}
			$('cb_rogueApDetection1').observe('click',form.tab2.toggleFields.bindAsEventListener(form.tab2,'rogueApDetection'));
		{/literal}
		{/if}

        {literal}
        if($('apMode').value == "5"){
            Form.disable(document.dataForm);
            disableButtons(["refresh"]);
        }
        {/literal}

-->
</script>
