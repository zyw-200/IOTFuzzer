{php}
if(!empty($_REQUEST['wdsLinkMode'])) {
echo "<input type='hidden' id='wdsLinkMode' value ='".$_REQUEST['wdsLinkMode']."'>";
{/php}
	<script>
	<!--
		//$('br_head').innerHTML = "TFTP failed to get the file!";
		//$('errorMessageBlock').show();
//alert('hit');
                //alert($('wdsLinkMode').value);
	-->
	</script>
{php}
}
{/php}
<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Profile Definition','profileDefinition')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<tr >
                                                                <td class="DatablockLabel">Profile Name</td>
								<td class="DatablockContent">
									<input class="input" size="20" maxlength="32" id="wdsProfileName" name="{$parentStr.wdsProfileName}" value="{$data.wdsProfileName}" type="text" label="Profile Name"  validate="Presence, {literal}{ allowQuotes: true,allowSpace: true}{/literal}" onkeydown="setActiveContent();">
								</td>
							</tr>


							{input_row label="Remote MAC Address" id="remoteMacAddress" name=$parentStr.remoteMacAddress type="text" masked="true" onblur="this.setAttribute('masked',(this.value != '')?false:true)" value=$data.remoteMacAddress|replace:'-':':'|replace:'00:00:00:00:00:00':'' validate="MACAddress, (( checkSameMac: '$macValue'))"}
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
	<tr>
		<td class="spacerHeight21">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Authentication Settings','authenticationSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{input_row label="Network Authentication" id="authenticationType" name=$parentStr.wdsAuthenticationtype type="select" options=$authenticationTypeList selected=$data.wdsAuthenticationtype onchange="wdsDisplaySettings(this.value);"}

							{input_row label="Data Encryption" id="key_size_11g" name="encryptionType" type="select" options=$encryptionTypeList selected=$encryptionSel onchange="if ($('authenticationType').value=='0') wdsDisplaySettings('1',this.value,1); setEncryption(this.value,$('authenticationType').value);"}

							{ip_field id="encryption" name=$parentStr.wdsEncryption type="hidden" value=$data.wdsEncryption}

							{if NOT ($data.wdsAuthenticationtype eq 1 OR ($data.wdsAuthenticationtype eq 0 AND $data.wdsEncryption neq 0))}
								{ip_field id="wepKeyType" name=$parentStr.wdsWepKeyType type="hidden" value=$data.wdsWepKeyType disabled="true"}
							{else}
								{ip_field id="wepKeyType" name=$parentStr.wdsWepKeyType type="hidden" value=$data.wdsWepKeyType}
							{/if}

							{if NOT ($data.wdsAuthenticationtype eq 1 OR ($data.wdsAuthenticationtype eq 0 AND $data.wdsEncryption neq 0))}
								{assign var="hideWepRow" value="style=\"display: none;\" disabled='true'"}
								{assign var="disableWepRow" value="disabled='true'"}
							{/if}

							{if NOT ($data.wdsAuthenticationtype eq 2 OR $data.wdsAuthenticationtype eq 4)}
								{assign var="hideWPARow" value="style=\"display: none;\" disabled='true'"}
							{/if}
							<tr mode="1" {$hideWepRow}>
								<td class="DatablockLabel">Passphrase</td>
								<td class="DatablockContent">
									<input class="input" size="20" maxlength="39" id="wepPassPhrase" name="{$parentStr.wdsWepPassPhrase}" value="{$data.wdsWepPassPhrase|regex_replace:"/(.)/":'*'}" type="text" label="Passphrase" validate="Presence, {literal}{ isMasked: 'wepPassPhrase', allowQuotes: true, allowSpace: true, allowTrimmed: false }{/literal}" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);">&nbsp;
									<input name="szPassphrase_button" style="text-align: center;" value="Generate Key" onclick="gen_11g_keys()" type="button">
									<input type="hidden" id="wepPassPhrase_hidden" value="{$data.wdsWepPassPhrase}">
								</td>
							</tr>
							<tr mode="1" {$hideWepRow}>
								<td class="DatablockLabel">WEP Key</td>
								<td class="DatablockContent">
									<input class="input" size="20" id="wepKey" name="{$parentStr.wdsWepKey}" value="{$data.wdsWepKey|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key" validate="{literal}Presence^HexaDecimal,{ isMasked: 'wepKey' }^Length,{ isWep: true}{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value!='')?false:true);" {$disableWepRow} onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">&nbsp;
								</td>
							</tr>
							<tr mode="4" {$hideWPARow}>
								<td class="DatablockLabel">WPA Passphrase (Network Key)</td>
								<td class="DatablockContent">
									<input id="wpa_psk" class="input" size="28" maxlength="63" name="{$parentStr.wdsPresharedkey}" value="{$data.wdsPresharedkey|regex_replace:"/(.)/":'*'}" type="text" label="WPA Passphrase (Network Key)" validate="Presence, {literal}{ allowQuotes: true, allowSpace: true,  allowTrimmed: false }{/literal}^Length,{literal}{minimum: 8}{/literal}"  onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);">
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
		<tr>
		<td class="spacerHeight21"></td>
	</tr>
	</tr>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Link Test','linkTest')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
                                                        {input_row label="IP Address" id="linkIpAddr" name="linkIpAddr" type="ipfield" value="" validate="OnlyIpAddress"}
                                                        <tr><td></td>
                                                            <td class="DatablockContent" style="text-align: left;">
                                                                    <input type="button" name="linktest" id="linktest" value="Link Test" onclick="startLinkTest()" >
                                                            </td>
                                                        </tr>
                                                        <tr><td class="DatablockLabel">Link Test Process Status</td>
                                                                <td class="DatablockContent" style="text-align: left;">
                                                                        <div id="showbarValue" style="font-size:10pt;padding:2px;black 1px;text-align: left">Uninitialized</div>
                                                                </td>
                                                        </tr>
                                                        <tr><td></td>
                                                                <td class="DatablockContent" style="text-align: left;">
                                                                        <div id="linktestonoffimage"><img src="images/pushButton_On.gif"</div><div id="showbar" style="font-size:8pt;padding:2px;"></div>
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

	{if $config.DHCP_SNOOPING.status  AND $config2.WG102.status}}
		<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('DHCP Snooping','dhcpSnooping')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{assign var="dhcpTrustedInterface" value=$data.dhcpTrustedInterface}
							{input_row label="DHCP Trusted Interface" id="dhcpTrustedInterface" name=$parentStr.dhcpTrustedInterface type="radio" options="1-Yes,0-No" selectCondition="==$dhcpTrustedInterface"}
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
	{/if}
	
	
    <input type="hidden" name="previousInterfaceNum" id="previousInterfaceNum" value="{$smarty.post.previousInterfaceNum}">
	<script language="javascript">
	<!--
		var buttons = new buttonObject();
		buttons.getStaticButtons(['back']);
		{literal}
		function doBack()
		{
			$('mode7').value='';
			doSubmit('cancel');
		}
		{/literal}
	-->
	</script>
