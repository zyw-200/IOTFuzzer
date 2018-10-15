	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Qos Settings','qOSSettings')</script></td>
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
                                                <input type="hidden" name="dummyAPMode0" id="ApMode0" value="{$data.wlanSettings.wlanSettingTable.wlan0.apMode}">
												{assign var="wmmSupport" value=$data.wlanSettings.wlanSettingTable.wlan0.wmmSupport}
												{input_row label="Enable Wi-Fi Multimedia (WMM)" id="idwmmSupport0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.wmmSupport type="radio" options="1-Enable,0-Disable" selectCondition="==$wmmSupport" onclick="disableWMMPS(this)"}
												{if $config.WMM.WMM_PS.status}
												{assign var="uapsdStatus" value=$data.wlanSettings.wlanSettingTable.wlan0.uapsdStatus}
												{input_row label="WMM Powersave" id="idwmmPowersave0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.uapsdStatus type="radio" options="1-Enable,0-Disable" selectCondition="==$uapsdStatus" disableCondition="1!=$wmmSupport"}
												{/if}
											</table>
										</div>
										{/if}
<!--@@@FIVEGHZSTART@@@-->
										{if $config.FIVEGHZ.status}
										<div class="BlockContent" id="wlan2">
											<table class="BlockContent" id="table_wlan2">
                                                <input type="hidden" name="dummyAPMode1" id="ApMode1" value="{$data.wlanSettings.wlanSettingTable.wlan1.apMode}">
												{assign var="wmmSupport" value=$data.wlanSettings.wlanSettingTable.wlan1.wmmSupport}
												{input_row label="Enable Wi-Fi Multimedia (WMM)" id="idwmmSupport1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.wmmSupport type="radio" options="1-Enable,0-Disable" selectCondition="==$wmmSupport" onclick="disableWMMPS1(this)"}
												{if $config.WMM.WMM_PS.status}
												{assign var="uapsdStatus" value=$data.wlanSettings.wlanSettingTable.wlan1.uapsdStatus}
												{input_row label="WMM Powersave" id="idwmmPowersave1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.uapsdStatus type="radio" options="1-Enable,0-Disable" selectCondition="==$uapsdStatus" disableCondition="1!=$wmmSupport"}
												{/if}
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
