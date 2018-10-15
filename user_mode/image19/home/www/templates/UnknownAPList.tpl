<script language="javascript">
<!--
    var twoGHzEmpty = 0;
    var fiveGHzEmpty = 0;
-->
</script>

{if $config.TWOGHZ.status AND $data.radioStatus0 eq '1'}
	<tr id="wlan1">
		<td>
			<table class="tableStyle">
				<tr>
					<td>
						<table class="tableStyle">
							<tr>
								<td colspan="3">
									<table class='tableStyle'>
										<tr>
											<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Unknown AP List (802.11{$wlan0ModeString})</td>
											<td class='subSectionTabTopRight spacer40Percent'>
												<a href='javascript: void(0);' onclick="showHelp('Unknown AP List','unknownAPList');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>
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
												<div  id="BlockContentTable">
													<table class="BlockContentTable" id="unknownList">
														<thead>
															<tr>
																{sortable_header_row sortable="false" tableid="unknownList" rowid="0" content="#"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="1" content="MAC Address"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="2" content="SSID"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="3" content="Privacy"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="4" content="Channel"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="5" content="Rate"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="6" content="Beacon Int."}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="7" content="# of Beacons"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="8" last="true" content="Last Beacon"}
															</tr>
														</thead>
														<tbody>
														{foreach name=unknownAP key=key item=value from=$data.monitor.apList.unknownApTable.wlan0}
															<tr {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$smarty.foreach.unknownAP.iteration}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$key|replace:'-':':'}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApSsid}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApPrivacy}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApChannel}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApRate}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApBeaconInterval}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApNumBeacons}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApLastBeacon}</td>
															</tr>
														{php}
															$this->_tpl_vars['unknownApList'].= str_replace('-',':',$this->_tpl_vars['key']) . ',';
														{/php}
														{foreachelse}
															<script language="javascript">
															<!--
                                                                    {literal}
                                                                        twoGHzEmpty = 1;
                                                                    {/literal}
																{if $interface eq 'wlan1' AND not $config.DUAL_CONCURRENT.status}
																	window.top.frames['action'].$('save').disabled=true;
																	window.top.frames['action'].$('save').src = 'images/save_off.gif';
																{/if}
															-->
															</script>
														{/foreach}
														</tbody>
													</table>
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
			</table>
		</td>
	</tr>
	<tr>
		<td class="spacerHeight21"></td>
	</tr>
{else}
    <script language="javascript">
        <!--
        {literal}
           twoGHzEmpty = 1;
        {/literal}
        -->
    </script>
{/if}

{if $config.FIVEGHZ.status AND $data.radioStatus1 eq '1'}
	<tr id="wlan2">
		<td>
			<table class="tableStyle">
				<tr>
					<td>
						<table class="tableStyle">
							<tr>
								<td colspan="3">
									<table class='tableStyle'>
										<tr>
											<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Unknown AP List (802.11{$wlan1ModeString})</td>
											<td class='subSectionTabTopRight spacer40Percent'>
												<a href='javascript: void(0);' onclick="showHelp('Unknown AP List','unknownAPList');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>
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
												<div  id="BlockContentTable">
													<table class="BlockContentTable" id="unknownList">
														<thead>
															<tr>
																{sortable_header_row sortable="false" tableid="unknownList" rowid="0" content="#"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="1" content="MAC Address"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="2" content="SSID"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="3" content="Privacy"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="4" content="Channel"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="5" content="Rate"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="6" content="Beacon Int."}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="7" content="# of Beacons"}
																{sortable_header_row sortable="true" tableid="unknownList" rowid="8" last="true" content="Last Beacon"}
															</tr>
														</thead>
														<tbody>
														{foreach name=unknownAP key=key item=value from=$data.monitor.apList.unknownApTable.wlan1}
															<tr {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$smarty.foreach.unknownAP.iteration}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$key|replace:'-':':'}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApSsid}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApPrivacy}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApChannel}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApRate}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApBeaconInterval}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApNumBeacons}</td>
																<td {if $smarty.foreach.unknownAP.iteration%2 eq 0}class="Alternate"{/if}>{$value.unknownApLastBeacon}</td>
															</tr>
														{php}
															$this->_tpl_vars['unknownApList'].= str_replace('-',':',$this->_tpl_vars['key']) . ',';
														{/php}
														{foreachelse}
															<script language="javascript">
															<!--
                                                                    {literal}
                                                                        fiveGHzEmpty = 1;
                                                                    {/literal}
																{if $interface eq 'wlan2' AND not $config.DUAL_CONCURRENT.status}
																	window.top.frames['action'].$('save').disabled=true;
																	window.top.frames['action'].$('save').src = 'images/save_off.gif';
																{/if}
															-->
															</script>
														{/foreach}
														</tbody>
													</table>
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
			</table>
		</td>
	</tr>
{else}
    <script language="javascript">
        <!--
        {literal}
           fiveGHzEmpty = 1;
        {/literal}
        -->
    </script>
{/if}

<script language="javascript">
<!--
{literal}
function doSave()
{
	if(window.top.frames['action'].$('save').src.indexOf('save_on')!== -1)
	{
		$('unknownApListForm').submit();
		return false;
	}
	else
	{
		window.top.frames['action'].$('save').disabled=true;
	}
	{/literal}

	{if (not $config.DUAL_CONCURRENT.status) AND ($config.TWOGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan0.radioStatus eq '0') AND ($config.FIVEGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan1.radioStatus eq '0')}
			window.top.frames['action'].$('refresh').disabled=true;
			window.top.frames['action'].$('refresh').src="images/refresh_off.gif";
	{/if}
	{literal}
}
{/literal}
{if $config.CLIENT.status}
	{if ($config.TWOGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5) OR ($config.FIVEGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan1.apMode eq 5)}
		Form.disable(document.dataForm);
		window.top.frames['action'].$('refresh').disabled=true;
		window.top.frames['action'].$('refresh').src="images/refresh_off.gif";
		window.top.frames['action'].$('save').disabled=true;
		window.top.frames['action'].$('save').src="images/save_off.gif";
	{/if}
{/if}

	{if  $config.DUAL_CONCURRENT.status}
        {literal}
            if(twoGHzEmpty == 1 && fiveGHzEmpty == 1){
                window.top.frames['action'].$('save').disabled=true;
                window.top.frames['action'].$('save').src="images/save_off.gif";
            }
        {/literal}
    {/if}

-->
</script>
</form>
<form name="unknownApListForm" id="unknownApListForm" action="saveTable.php" method="post">
<input type="hidden" name="ApList" value="{$unknownApList}">
</form>
