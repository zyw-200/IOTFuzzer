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
							{assign var="wpsStatus" value=$data.wpsSettings.wpsDisable}
                                                        {assign var="onclickStr"  value="graysomething(this,false);"}
							{input_row label="WPS" id="chkWPS" name=$parentStr.wpsSettings.wpsDisable type="radio" options="0-Enable,1-Disable" selectCondition="==$wpsStatus" onclick="$onclickStr;setDisableRouter()"}
                                                        <tr>
								<td class="DatablockLabel">AP's PIN:</td>
								<td class="DatablockLabel" id="apPinLabel">&nbsp&nbsp {$data.monitor.wpsPin}</td>
							</tr>
                                                        <tr>
                                                        
                                                             <td align="left"><Input type="checkbox" id="disableRouterPinl" onclick="setActiveContent();toggleAPPINDisplay();$('disableRouterPin').disabled=true;" {if $data.monitor.wpsGetApPinStatus eq '01' OR  $data.monitor.wpsGetApPinStatus eq '00'}checked="checked"{/if}{if $wpsStatus eq '1' OR $data.monitor.wpsGetApPinStatus eq '00'}disabled="disabled"{/if}><td align="left" class="DatablockLabel">&nbsp&nbsp Disable AP's PIN</td>
                                                             <input type="hidden" name="disableRouterPin" id="disableRouterPin" value="{$data.monitor.wpsGetApPinStatus}" disabled="disabled">
                                                        </tr>
                                                        <tr>
                                                            <td align="left"><Input type="checkbox" onclick="setActiveContent();$('keepExisting').value=this.checked?'1':'0';" {if $wpsStatus eq '1'}disabled="disabled"{/if} {if $data.wpsSettings.wpsConfigured eq '1'}checked="checked"{/if}><td align="left" class="DatablockLabel">&nbsp&nbsp Keep Existing Wireless Settings
                                                            <input type="hidden" name="{$parentStr.wpsSettings.wpsConfigured}" id="keepExisting" value="{$data.wpsSettings.wpsConfigured}">
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
        <input type="hidden" name="bridgingStatus" id="bridgingStatus" value="{$data.wlanSettings.wlanSettingTable.wlan0.apMode}">
        <input type="hidden" id="secutityStatus" name="secutityStatus" value="{$data.vapSettings.vapSettingTable.wlan0.vap0.authenticationType}">
<script language="javascript">
	<!--
	window.top.frames['action'].$('standardButtons').show();
        {literal}
        if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Wireless Radio is turned off!')){
            Form.disable(document.dataForm);
        }
        if(($('bridgingStatus').value == '5')){
            Form.disable(document.dataForm);
        }
        {/literal}
        {if $data.wpsSettings.wpsDisable eq '1'}
                $('apPinLabel').disabled=true;
                $('apPinLabel').style.color="grey";
        {/if}
        {literal}
        if($('disableRouterPin').value == '00' || $('disableRouterPin').value == '01'){
            $('apPinLabel').disabled=true;
            $('apPinLabel').style.color="grey";
        }
        {/literal}
	-->
</script>	