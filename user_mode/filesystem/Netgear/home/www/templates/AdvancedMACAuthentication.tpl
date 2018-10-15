<tr>

	<td>

		<table class="tableStyle">

			<tr>

				<td colspan="3"><script>tbhdr('MAC Authentication','wirelessMACAuthentication')</script></td>

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

									<div class="BlockContent" id="wlan1">

										<table class="BlockContent" id="table_wlan1">

											{input_row label="Turn Access Control On" id="accessControlMode0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.accessControlMode type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan0.accessControlMode selectCondition="!=0" size="16" maxlength="15" dependent="accesscontroldb" }

											<input type="hidden" id="rogueApDetection" value="{$interface}">

                                            <input type="hidden" name="dummyAPMode0" id="ApMode0" value="{$data.wlanSettings.wlanSettingTable.wlan0.apMode}">
											{if $config.WNDAP360.status || $config.WNDAP660.status || $config.JWAP603.status}
											{input_row label="Import MAC Address List from a file" id="merge_mac_acl_list0" name="merge_mac_acl_list0" type="radio" options="1-Replace,2-Merge" selected="==1"}
											<input type="hidden" id="rogueApDetection" value="{$interface}">
											<tr>
													<td class="DatablockLabel">&nbsp;</td>
													<td class="DatablockContent"><input class="input" id="uploadFile0" name="macListFile0" type="file" oncontextmenu="return false" onkeydown="this.blur()" onpaste="return false" onchange="setActiveContent();"></td>
											</tr>
											{/if}
											<tr>

												<td class="DatablockLabel">Select Access Control Database</td>

												<td class="DatablockContent">

													<select {if $data.wlanSettings.wlanSettingTable.wlan0.accessControlMode eq '0'}disabled="disabled"{/if} name="accesscontroldb0" id="accesscontroldb0" onchange="setActiveContent();$('accessControlMode0').value=this.value; showRadiusAlert(this);">

														<option value="1" {if $data.wlanSettings.wlanSettingTable.wlan0.accessControlMode eq '1'}selected="selected"{/if}>Local MAC Address Database</option>

                                                                                                                {if !$config.WN604.status}

                                                                                                                    <option value="2" {if $data.wlanSettings.wlanSettingTable.wlan0.accessControlMode eq '2'}selected="selected"{/if}>Remote MAC Address Database</option>

                                                                                                                {/if}

													</select>

												</td>

											</tr>

											<tr>

{if $config.CENTRALIZED_VLAN.status}

                                                                                        <input type="hidden" name="vlanStatusvap0" id="vlanStatusvap0" value="{$data.vapSettings.vapSettingTable.wlan0.vap0.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap0" id="securityStatusvap0" value="{$data.vapSettings.vapSettingTable.wlan0.vap0.authenticationType}">

                                                                                        <input type="hidden" name="vlanStatusvap1" id="vlanStatusvap1" value="{$data.vapSettings.vapSettingTable.wlan0.vap1.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap1" id="securityStatusvap1" value="{$data.vapSettings.vapSettingTable.wlan0.vap1.authenticationType}">

                                                                                        <input type="hidden" name="vlanStatusvap2" id="vlanStatusvap2" value="{$data.vapSettings.vapSettingTable.wlan0.vap2.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap2" id="securityStatusvap2" value="{$data.vapSettings.vapSettingTable.wlan0.vap2.authenticationType}">

                                                                                        <input type="hidden" name="vlanStatusvap3" id="vlanStatusvap3" value="{$data.vapSettings.vapSettingTable.wlan0.vap3.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap3" id="securityStatusvap3" value="{$data.vapSettings.vapSettingTable.wlan0.vap3.authenticationType}">

                                                                                        <input type="hidden" name="vlanStatusvap4" id="vlanStatusvap4" value="{$data.vapSettings.vapSettingTable.wlan0.vap4.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap4" id="securityStatusvap4" value="{$data.vapSettings.vapSettingTable.wlan0.vap4.authenticationType}">

                                                                                        <input type="hidden" name="vlanStatusvap5" id="vlanStatusvap5" value="{$data.vapSettings.vapSettingTable.wlan0.vap5.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap5" id="securityStatusvap5" value="{$data.vapSettings.vapSettingTable.wlan0.vap5.authenticationType}">

                                                                                        <input type="hidden" name="vlanStatusvap6" id="vlanStatusvap6" value="{$data.vapSettings.vapSettingTable.wlan0.vap6.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap6" id="securityStatusvap6" value="{$data.vapSettings.vapSettingTable.wlan0.vap6.authenticationType}">

                                                                                        <input type="hidden" name="vlanStatusvap7" id="vlanStatusvap7" value="{$data.vapSettings.vapSettingTable.wlan0.vap7.vlanType}">

                                                                                        <input type="hidden" name="securityStatusvap7" id="securityStatusvap7" value="{$data.vapSettings.vapSettingTable.wlan0.vap7.authenticationType}">















{/if}

												<td colspan="2" style="padding: 0px; margin: 0px;">

													<table class="BlockContent Trans">

														<tr>

															<td style="vertical-align: top; width: 100%; padding: 0px;">

																{data_header label="Trusted Wireless Stations" backgroundColor="transparent" headerType="inline" actionButtons="<input type=\"image\" src=\"images/add_on.gif\" name=\"addmac\" id=\"addmac\" onclick=\"addTrustedStation(false,0, event);//openAddNewMacWin(event,0);\" value=\"Add\" style=\"margin-right: 2px;\"><input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('trustedTable0','0');setActiveContent(); return false;\" value=\"Delete\">"}

																<input id="rowCount0" name="rowCount0" value="4" type="hidden">

															</td>

															<td style="vertical-align: middle; width:1%; text-align: center;">

																&nbsp;

															</td>

															<td style="vertical-align: top; width: 100%; padding: 0px;">

																	{data_header label="Available Wireless Stations" backgroundColor="transparent" headerType="inline"}

															</td>

														</tr>

														<tr>

															{php}

															$size = count($this->_tpl_vars['data']['accessControlSettings']['wlanAccessControlLocalTable']['wlan0']);

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

																<div class="BlockContentTable" id="div_trustedTable0" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 300px;">

																	<table class="BlockContentTable Trans" id="trustedTable0">

																		<tr id="headRow">

																			{sortable_header_row sortable="false" tableid="trustedTable0" rowid="0" content="<input type=\"checkbox\" id=\"trustStation0_main\" onclick=\"toggleCheckBoxes('trustStation0', this);\">"}

																			{sortable_header_row sortable="true" tableid="trustedTable0" rowid="1" last="true" content="MAC Address"}

																		</tr>

																		<tr id="addMacRow0">

																			<th >&nbsp;</th>

																			<th ><input type="text" id="addNewMac0" class="input" value="" name="123" validate="MACAddress" label="New MAC Address"></th>

																		</tr>

																		{foreach name=trustedStations key=key item=value from=$data.accessControlSettings.wlanAccessControlLocalTable.wlan0}

																		<tr {if $smarty.foreach.trustedStations.iteration%2 eq 0}class="Alternate"{/if} id="{$key|upper}">

																				<td {if $smarty.foreach.trustedStations.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="trustStation0" onclick="if (!this.checked) $('trustStation0_main').checked=false;"></td>

																				<td {if $smarty.foreach.trustedStations.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper|replace:'-':':'}</td>

																		</tr>
																		{php}
																				$this->_tpl_vars['trustedList_0'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																		{/php}
																														

																		{/foreach}

																		<input id="rowCount0" name="rowCount0" value="{$smarty.foreach.trustedStations.total}" type="hidden">

																		<input id="addedStations0" name="system[accessControlSettings][wlanAccessControlLocalTable][wlan0]" value="" type="hidden">

																		<input id="deletedStations0" name="delete[accessControlSettings][wlanAccessControlLocalTable][wlan0]" value="" type="hidden">
																		
																		<input id="oldStations0" name="accessControlSettings[wlanAccessControlLocalTable][wlan0]" value="{$trustedList_0}" type="hidden">

																	</table>

																</div>

															</td>

															<td style="padding: 2px; vertical-align: middle; text-align: center;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addTrustedStation(true,'0',event);setActiveContent();return false;"></td>

															{php}

															$size = count($this->_tpl_vars['data']['monitor']['stationList']['newStaList']['wlan0']);

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

																<div class="BlockContentTable" id="div_avblStationList0" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 300px;">

																	<table class="BlockContentTable Trans" id="avblStationList0">

																		<thead>

																			<tr id="headRow">

																				{sortable_header_row sortable="false" tableid="avblStationList0" rowid="0" content="<input type=\"checkbox\" id=\"avblStation0_main\" onclick=\"toggleCheckBoxes('avblStation0', this);\">"}

																				{sortable_header_row sortable="true" tableid="avblStationList0" rowid="1" content="Station ID"}

																				{sortable_header_row sortable="true" tableid="avblStationList0" rowid="2" last="true" content="MAC Address"}

																			</tr>

																		</thead>

																		<tbody id="offTblBdy">

																			{foreach name=newStations key=key item=value from=$data.monitor.stationList.newStaList.wlan0}

																			<tr {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if} id="{$key|upper}">

																				<td {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="avblStation0" onclick="if (!this.checked) $('avblStation0_main').checked=false;"></td>

																				<td {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if}>{$smarty.foreach.newStations.iteration}</td>

																				<td {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper|replace:'-':':'}</td>

																			</tr>

																			{/foreach}

																		</tbody>

																	</table>

																</div>

															</td>

														</tr>

{if $config.MACACL_IMP_EXP.status}

							<tr>

								<td class="DatablockContent" style="text-align: left;">

									<input type="button" name="saveas" id="saveas" value="Save as..." onclick="doSave(0)">

								</td>

							</tr>

{/if}



													</table>

												</td>

											</tr>

										</table>

									</div>

{/if}

<!--@@@FIVEGHZSTART@@@-->

{if $config.FIVEGHZ.status}

									<div class="BlockContent" id="wlan2">

										<table class="BlockContent"  id="table_wlan2">

										{input_row label="Turn Access Control On" id="accessControlMode1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.accessControlMode type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan1.accessControlMode selectCondition="!=0" size="16" maxlength="15" dependent="accesscontroldb" }

                                        <input type="hidden" name="dummyAPMode1" id="ApMode1" value="{$data.wlanSettings.wlanSettingTable.wlan1.apMode}">
										{if $config.WNDAP360.status || $config.WNDAP660.status || $config.JWAP603.status}
										{input_row label="Import MAC Address List from a file" id="merge_mac_acl_list1" name="merge_mac_acl_list1" type="radio" options="1-Replace,2-Merge" selected="==1"}
											<input type="hidden" id="rogueApDetection" value="{$interface}">
											<tr>
													<td class="DatablockLabel">&nbsp;</td>
													<td class="DatablockContent"><input class="input" id="uploadFile1" name="macListFile1" type="file" oncontextmenu="return false" onkeydown="this.blur()" onpaste="return false" onchange="setActiveContent();"></td>
											</tr>
											{/if}
											<tr>

												<td class="DatablockLabel">Select Access Control Database</td>

												<td class="DatablockContent">

													<select {if $data.wlanSettings.wlanSettingTable.wlan1.accessControlMode eq '0'}disabled="disabled"{/if} name="accesscontroldb1" id="accesscontroldb1" onchange="setActiveContent();$('accessControlMode1').value=this.value; showRadiusAlert(this);">

														<option value="1" {if $data.wlanSettings.wlanSettingTable.wlan1.accessControlMode eq '1'}selected="selected"{/if}>Local MAC Address Database</option>

														<option value="2" {if $data.wlanSettings.wlanSettingTable.wlan1.accessControlMode eq '2'}selected="selected"{/if}>Remote MAC Address Database</option>

													</select>

												</td>

											</tr>

										<tr>

											<td colspan="2" style="padding: 0px; margin: 0px;">

												<table class="BlockContent Trans">

													<tr>

														<td style="vertical-align: top; width: 100%; padding: 0px;">

															{data_header label="Trusted Wireless Stations" backgroundColor="transparent" headerType="inline" actionButtons="<input type=\"image\" src=\"images/add_on.gif\" name=\"addmac\" id=\"addmac\" onclick=\"addTrustedStation(false,1, event);//openAddNewMacWin(event,1);\" value=\"Add\" style=\"margin-right: 2px;\"><input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('trustedTable1','1'); return false;\" value=\"Delete\">"}

															<input id="rowCount" name="rowCount" value="4" type="hidden">

														</td>

														<td style="vertical-align: middle; width:37px; text-align: center;">

															&nbsp;

														</td>

														<td style="vertical-align: top; width: 100%; padding: 0px;">

															{data_header label="Available Wireless Stations" backgroundColor="transparent" headerType="inline"}

														</td>

													</tr>

													<tr>

														{php}

														$size = count($this->_tpl_vars['data']['accessControlSettings']['wlanAccessControlLocalTable']['wlan1']);

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

															<div class="BlockContentTable" id="div_trustedTable1" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 300px;">

																<table class="BlockContentTable Trans" id="trustedTable1">

																	<tr id="headRow">

																		{sortable_header_row sortable="false" tableid="trustedTable1" rowid="0" content="<input type=\"checkbox\" id=\"trustStation1_main\" onclick=\"toggleCheckBoxes('trustStation1', this);\">"}

																		{sortable_header_row sortable="true" tableid="trustedTable1" rowid="1" last="true" content="MAC Address"}

																	</tr>

																	<tr id="addMacRow1">

																		<th >&nbsp;</th>

																		<th ><input type="text" id="addNewMac1" class="input" value="" name="123" validate="MACAddress" label="New MAC Address"></th>

																	</tr>

																	{foreach name=trustedStations key=key item=value from=$data.accessControlSettings.wlanAccessControlLocalTable.wlan1}

																	<tr {if $smarty.foreach.trustedStations.iteration%2 eq 0}class="Alternate"{/if} id="{$key|upper}">

																		<td {if $smarty.foreach.trustedStations.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="trustStation1" onclick="if (!this.checked) $('trustStation1_main').checked=false;"></td>

																		<td {if $smarty.foreach.trustedStations.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper|replace:'-':':'}</td>

																	</tr>
																	
																	{php}
																				$this->_tpl_vars['trustedList_1'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																		{/php}

																	{/foreach}

																	<input id="rowCount1" name="rowCount1" value="{$smarty.foreach.trustedStations.total}" type="hidden">

																	<input id="addedStations1" name="system[accessControlSettings][wlanAccessControlLocalTable][wlan1]" value="" type="hidden">

																	<input id="deletedStations1" name="delete[accessControlSettings][wlanAccessControlLocalTable][wlan1]" value="" type="hidden">
																																			
																	<input id="oldStations1" name="accessControlSettings[wlanAccessControlLocalTable][wlan1]" value="{$trustedList_1}" type="hidden">


																</table>

															</div>

														</td>

														<td style="padding: 2px; vertical-align: middle; text-align: left;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addTrustedStation(true,'1',event);setActiveContent(); return false;"></td>

														{php}

														$size = count($this->_tpl_vars['data']['monitor']['stationList']['newStaList']['wlan1']);

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

															<div class="BlockContentTable" id="div_avblStationList1" style="height:{$heightStr}; overflow-y: {$overflowStr}; width: 300px;">

																<table class="BlockContentTable Trans" id="avblStationList1">

																	<thead>

																		<tr id="headRow">

																			{sortable_header_row sortable="false" tableid="avblStationList1" rowid="0" content="<input type=\"checkbox\" onclick=\"toggleCheckBoxes('avblStation1', this);\">"}

																			{sortable_header_row sortable="true" tableid="avblStationList1" rowid="1" content="Station ID"}

																			{sortable_header_row sortable="true" tableid="avblStationList1" rowid="2" last="true" content="MAC Address"}

																		</tr>

																	</thead>

																	<tbody id="offTblBdy">

																		{foreach name=newStations key=key item=value from=$data.monitor.stationList.newStaList.wlan1}

																		<tr {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if} id="{$key|upper}">

																			<td {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if}><input type="checkbox" id="avblStation1" onclick="if (!this.checked) $('avblStation1_main').checked=false;"></td>

																			<td {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if}>{$smarty.foreach.newStations.iteration}</td>

																			<td {if $smarty.foreach.newStations.iteration%2 eq 0}class="Alternate"{/if}>{$key|upper|replace:'-':':'}</td>

																		</tr>

																		{/foreach}

																	</tbody>

																</table>

															</div>

														</td>

													</tr>

{if $config.MACACL_IMP_EXP.status}

							<tr>

								<td class="DatablockContent" style="text-align: left;">

									<input type="button" name="saveas2" id="saveas2" value="Save as..." onclick="doSave(1)">

								</td>

							</tr>

{/if}



												</table>

											</td>

										</tr>

									</table>

								</div>

{/if}

<!--@@@FIVEGHZEND@@@-->

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

</table>

<input type="hidden" id="radiusEnabled" value="{if $data.info802dot1x.authinfo.priRadIpAddr eq '0.0.0.0'}false{else}true{/if}">



<script language="javascript">

<!--

        var prevInterface = {$smarty.post.previousInterfaceNum|default:"''"};

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

			$('cb_accessControlMode0').observe('click',form.tab1.toggleFields.bindAsEventListener(form.tab1,'accessControlMode'));

		{/literal}

		{/if}

//<!--@@@FIVEGHZSTART@@@-->

		{if $config.FIVEGHZ.status}

		{literal}

			$('cb_accessControlMode1').observe('click',form.tab2.toggleFields.bindAsEventListener(form.tab2,'accessControlMode'));

		{/literal}

		{/if}

//<!--@@@FIVEGHZEND@@@-->

{literal}

        function doSave(interface)

        {

                doSave_MacAuth(interface);

		$('knownApListForm').submit();

		return false;





        }

{/literal}

-->

</script>



</form>

<form name="knownApListForm" id="knownApListForm" action="saveTable.php" method="post">

<input type="hidden" id = "ApList" name="ApList" value="">

</form>

