	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Wireless On-Off','WLONOFF')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">	
						<tr><td>
							{assign var="scheduledWirelessStatus" value=$data.basicSettings.scheduledWirelessStatus} 
                            {assign var="onclickStr"  value="graysomething(this,true);"}                                                
							{if !$config.DUAL_CONCURRENT.status}
							{input_row label="Wireless On-Off" id="schWirelessonoff" name=$parentStr.basicSettings.scheduledWirelessStatus type="radio" options="1-On,0-Off" selectCondition="==$scheduledWirelessStatus" onclick="$onclickStr;disableSchduleControls(this);" onchange="$('hidden_scstatus').value=this.value"}							
								{if $activeMode eq '2'}
								{ip_field id="hidden_scstatus" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.schedularStatus type="hidden" value=$scheduledWirelessStatus}
								{if $config.WNDAP620.status}
								{ip_field id="hidden" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.schedularStatus type="hidden" value='0'}
								{/if}
								{elseif $activeMode eq '4'}
								{ip_field id="hidden_scstatus" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.schedularStatus type="hidden" value=$scheduledWirelessStatus}
								{if $config.WNDAP620.status}
								{ip_field id="hidden" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.schedularStatus type="hidden" value='0'}
								{/if}
								{else}
								{ip_field id="hidden_scstatus" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.schedularStatus type="hidden" value=$scheduledWirelessStatus}
								{if $config.WNDAP620.status}
								{ip_field id="hidden" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.schedularStatus type="hidden" value='0'}		
								{/if}
								{/if}
							{else}
							{input_row label="Wireless On-Off" id="schWirelessonoff" name=$parentStr.basicSettings.scheduledWirelessStatus type="radio" options="1-On,0-Off" selectCondition="==$scheduledWirelessStatus" onclick="$onclickStr;disableSchduleControls(this);"}							
							{/if}			
							</td>
						<tr>                            
								<td class="DatablockLabel">Radio off schedule</td>
								<td class="DatablockContent">
									<table width="80%">
										<tr align="center"><td "width=10%">M</td><td "width=10%">T</td><td "width=10%">W</td><td "width=10%">T</td><td "width=10%">F</td><td "width=10%">S</td><td "width=10%">S</td></tr>
										<tr align="center">
                                        <td><Input type="checkbox" id="schRad_0" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>
										<input type="hidden" name="{$parentStr.basicSettings.scheduledWirelessWeeklyStatus}" id="scheduledWirelessWeeklyStatus" value="{$data.basicSettings.scheduledWirelessWeeklyStatus}">
										<td><Input type="checkbox" id="schRad_1" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>
										<td><Input type="checkbox" id="schRad_2" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>
										<td><Input type="checkbox" id="schRad_3" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>                                        
                                        <td><Input type="checkbox" id="schRad_4" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>
										<td><Input type="checkbox" id="schRad_5" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>
										<td><Input type="checkbox" id="schRad_6" onclick="setActiveContent();convertWeekScheduleToString();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>
										</tr>                                                                                
									</table>
								</td>
							</tr>
						</tr>	
							<tr>
								<td class="DatablockLabel">Radio ON Time</td>
								<td class="DatablockContent">&nbsp&nbsp<input class="input" size="2" maxlength="2" id="radioOnHour" label="Radio ON Hour" value="" type="text" onblur="convert2TimeString()" validate="Numericality, {literal}{ minimum:0, maximum: 23, onlyInteger: true }^Presence"{/literal} onkeydown="setActiveContent();" {if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>&nbsp:
									<input class="input" size="2" maxlength="2" id="radioOnMin" label="Radio ON Minutes" value="" type="text"  onblur="convert2TimeString()" validate="Numericality, {literal}{ minimum:0, maximum: 59, onlyInteger: true }^Presence"{/literal} onkeydown="setActiveContent();"{if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>&nbsp hrs
									<input type="hidden" name="{$parentStr.basicSettings.radioOnTime}" id="radioOnTime" value="{$data.basicSettings.radioOnTime}">
								</td>
							</tr>								
							<tr>
								<td class="DatablockLabel">Radio OFF Time</td>
								<td class="DatablockContent">
									&nbsp&nbsp<input class="input" size="2" maxlength="2" id="radioOffHour" label="Radio OFF Hour" value="" type="text" onblur="convert2TimeString()" validate="Numericality, {literal}{ minimum:0, maximum: 23, onlyInteger: true }^Presence"{/literal} onkeydown="setActiveContent();" {if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>&nbsp:
									<input class="input" size="2" maxlength="2" id="radioOffMin" label="Radio OFF Minutes" value="" type="text" onblur="convert2TimeString()" validate="Numericality, {literal}{ minimum:0, maximum: 59, onlyInteger: true }^Presence"{/literal} onkeydown="setActiveContent();" {if $scheduledWirelessStatus eq '0'}disabled="disabled"{/if}>&nbsp hrs
									<input type="hidden" name="{$parentStr.basicSettings.radioOffTime}" id="radioOffTime" value="{$data.basicSettings.radioOffTime}">
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
<script language="javascript">
	<!--
	{literal}
	convertRadioOnOffTime();
	convertScheduleWeekStatus();
	{/literal}
	-->
</script>	
