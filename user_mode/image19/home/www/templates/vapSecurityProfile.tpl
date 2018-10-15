
<script language="javascript">

<!--

    var twoGHzEmpty = 0;

-->

</script>



	{if ($config.TWOGHZ.status AND !$config.FIVEGHZ.status)}

            {*php}

			var_dump($this->_tpl_vars['data']['wdsMode']);

			{/php*}

        {if ($data.wdsMode eq '1' OR $data.wdsMode eq '5' OR $data.wdsMode eq '4')}

            <script language="javascript">

            <!--

                twoGHzEmpty = 1;

            -->

            </script>

        {/if}

	{/if}



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

{if $config.MBSSID.status}

							<tr >

                                                                <td class="DatablockLabel">Profile Name</td>

								<td class="DatablockContent">

									<input class="input" size="20" maxlength="32" id="vapProfileName" name="{$parentStr.vapProfileName}" value="{$data.vapProfileName}" type="text" label="Profile Name"  validate="Presence, {literal}{ allowQuotes: true,allowSpace: true}{/literal}" onkeydown="setActiveContent();">

								</td>

							</tr>



{/if}

{php}

//$this->_tpl_vars['data']['ssid'] = urlencode($this->_tpl_vars['data']['ssid']);

{/php}

							{input_row label="Wireless Network Name (SSID)" id="ssid" name=$parentStr.ssid type="text" size="20" maxlength="32" validate="Presence, ((allowQuotes: true,allowSpace: true, allowTrimmed: false))^Length, (( minimum: 2 ))" value=$data.ssid}



							{assign var="hideNetworkName" value=$data.hideNetworkName}

							{input_row label="Broadcast Wireless Network Name (SSID)" id="broadcastSSID" name=$parentStr.hideNetworkName type="radio" options="0-Yes,1-No" selectCondition="==$hideNetworkName"}

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

							{input_row label="Network Authentication" id="authenticationType" name=$parentStr.authenticationType type="select" options=$authenticationTypeList selected=$data.authenticationType onchange="DisplaySettings(this.value);"}



							{input_row label="Data Encryption" id="key_size_11g" name="encryptionType" type="select" options=$encryptionTypeList selected=$encryptionSel onchange="if ($('authenticationType').value=='0') DisplaySettings('1',this.value,1); setEncryption(this.value,$('authenticationType').value);"}



							{ip_field id="encryption" name=$parentStr.encryption type="hidden" value=$data.encryption}

{if $config.CENTRALIZED_VLAN.status}

                                                        {ip_field id="vapMacACLStatus" name="vapMacACLStatus" type="hidden" value=$data.macACLStatus}

{/if}

							{if NOT ($data.authenticationType eq 1 OR ($data.authenticationType eq 0 AND $data.encryption neq 0))}

								{ip_field id="wepKeyType" name=$parentStr.wepKeyType type="hidden" value=$data.wepKeyType disabled="true"}

							{else}

								{ip_field id="wepKeyType" name=$parentStr.wepKeyType type="hidden" value=$data.wepKeyType}

							{/if}

							{if NOT ($data.authenticationType eq 1 OR ($data.authenticationType eq 0 AND $data.encryption neq 0))}

								{assign var="hideWepRow" value="style=\"display: none;\" disabled='true'"}

								{assign var="disableWepRow" value="disabled='true'"}

							{/if}



							{if NOT($data.authenticationType eq 16 OR $data.authenticationType eq 32 OR $data.authenticationType eq 48)}

								{assign var="hideWPARow" value="style=\"display: none;\" disabled='true'"}

							{/if}

							<tr mode="1" {$hideWepRow}>

								<td class="DatablockLabel">Passphrase</td>

								<td class="DatablockContent">

									<input class="input" size="20" maxlength="39" id="wepPassPhrase" name="{$parentStr.wepPassPhrase}" value="{$data.wepPassPhrase|regex_replace:"/(.)/":'*'}" type="text" label="Passphrase"  onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();" validate="Presence, {literal}{ isMasked: 'wepPassPhrase', allowQuotes: true, allowSpace: true, allowTrimmed:false }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true); $('wepPassPhrase_hidden').value='';">&nbsp;

									<input name="szPassphrase_button" style="text-align: center;" value="Generate Keys" onclick="gen_11g_keys()" type="button">

									<input type="hidden" id="wepPassPhrase_hidden" value="{$data.wepPassPhrase}">

								</td>

							</tr>

							<tr mode="1" {$hideWepRow}>

								<td class="DatablockLabel">Key 1&nbsp;<input id="keyno_11g1" name="{$parentStr.wepKeyNo}" value="1" {if $data.wepKeyNo eq '1'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>

								<td class="DatablockContent">

									<input class="input" size="20" id="wepKey1" name="system['vapSettings']['vapSettingTable']['wlan{$navigation.8}']['vap{$navigation.7}']['wepKey1']" value="{$data.wepKey1|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 1" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g1' }^HexaDecimal,{ isMasked: 'wepKey1' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

							<tr mode="1" {$hideWepRow}>

								<td class="DatablockLabel">Key 2&nbsp;<input id="keyno_11g2" name="{$parentStr.wepKeyNo}" value="2" {if $data.wepKeyNo eq '2'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>

								<td class="DatablockContent">

									<input class="input" size="20" id="wepKey2" name="system['vapSettings']['vapSettingTable']['wlan{$navigation.8}']['vap{$navigation.7}']['wepKey2']" value="{$data.wepKey2|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 2" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g2' }^HexaDecimal,{ isMasked: 'wepKey2' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

							<tr mode="1" {$hideWepRow}>

								<td class="DatablockLabel">Key 3&nbsp;<input id="keyno_11g3" name="{$parentStr.wepKeyNo}" value="3" {if $data.wepKeyNo eq '3'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>

								<td class="DatablockContent">

									<input class="input" size="20" id="wepKey3" name="system['vapSettings']['vapSettingTable']['wlan{$navigation.8}']['vap{$navigation.7}']['wepKey3']" value="{$data.wepKey3|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 3" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g3' }^HexaDecimal,{ isMasked: 'wepKey3' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

							<tr mode="1" {$hideWepRow}>

								<td class="DatablockLabel">Key 4&nbsp;<input id="keyno_11g4" name="{$parentStr.wepKeyNo}" value="4" {if $data.wepKeyNo eq '4'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>

								<td class="DatablockContent">

									<input class="input" size="20" id="wepKey4" name="system['vapSettings']['vapSettingTable']['wlan{$navigation.8}']['vap{$navigation.7}']['wepKey4']" value="{$data.wepKey4|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 4" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g4' }^HexaDecimal,{ isMasked: 'wepKey4' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

{if $config.WN604.status}

							<tr mode="16" {$hideWPARow}>

								<td class="DatablockLabel">WPA Type</td>

								<td class="DatablockContent">

                                                                    {assign var="wpaMethod" value=$data.wpaPresharedKeyType }

                                                                    {ip_field id="wpaPresharedKeyType" name=$parentStr.wpaPresharedKeyType type="radio" options="1-PSK,0-Passphrase" selectCondition="==$wpaMethod" value=$wpaMethod onclick="toggleWPAMethods(this.value);"}</td>

								</td>

							</tr>

{/if}



{if $config.PASSPHRASE_CHAR.status}

							<tr mode="1" {$hideWepRow}>

								<td class="DatablockLabel">Show Passphrase in Clear Text</td>

								<td class="DatablockContent">

                                                                    {ip_field id="showPassphrase1" name="showPassphrase1" type="radio" options="0-No,1-Yes" value="0" selectCondition="==0" onclick="showPassPhrase(this.value,this.id);"}

								</td>

							</tr>

{/if}

							<tr mode="16" {$hideWPARow}>

								<td class="DatablockLabel">WPA Passphrase (Network Key)</td>

								<td class="DatablockContent">

                                                                <input type="hidden" id="wpa_psk_hidden" value="{$data.presharedKey}">

{if $config.WN604.status}

									<input id="wpa_psk" class="input" size="28" maxlength="63" name="{$parentStr.presharedKey}" value="{if $wpsDisable eq '1'}{$data.presharedKey|regex_replace:"/(.)/":'*'}{else}{$data.presharedKey}{/if}" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA Passphrase (Network Key)" validate="" onblur="setPassPhraseTouched();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);$('wpa_psk_hidden').value='';" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">

{else}

    {if $config.WPS.status}

                                                                        <input id="wpa_psk" class="input" size="28" maxlength="63" name="{$parentStr.presharedKey}" value="{if $wpsDisable eq '1'}{$data.presharedKey|regex_replace:"/(.)/":'*'}{else}{$data.presharedKey}{/if}" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA Passphrase (Network Key)" validate="Presence,{literal} {allowQuotes: true, allowSpace: true, allowTrimmed: false }{/literal}^Length,{literal}{minimum: 8}{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);$('wpa_psk_hidden').value='';" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">

    {else}

                                                                        <input id="wpa_psk" class="input" size="28" maxlength="63" name="{$parentStr.presharedKey}" value="{$data.presharedKey|regex_replace:"/(.)/":'*'}" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA Passphrase (Network Key)" validate="Presence,{literal} {allowQuotes: true, allowSpace: true, allowTrimmed: false }{/literal}^Length,{literal}{minimum: 8}{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);$('wpa_psk_hidden').value='';" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">

    {/if}

{/if}								</td>

							</tr>

{if $config.WN604.status}

                                                        <tr mode="16" {$hideWPARow}>

								<td class="DatablockLabel">WPA PSK</td>

								<td class="DatablockContent">

                                                                        <input id="wpa_psk_2" class="input" size="28" maxlength="64" name="{$parentStr.wpaPsk}" value="{if $wpsDisable eq '1'}{$data.wpaPsk|regex_replace:"/(.)/":'*'}{else}{$data.wpaPsk}{/if}" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA PSK" validate="" onblur="setPSKTouched();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();" disabled="disabled">

								</td>

							</tr>



                                                        {if ($data.authenticationType eq 16 OR $data.authenticationType eq 32 OR $data.authenticationType eq 48)}

                                                            <script language="javascript">

                                                            <!--

                                                                {literal}

                                                                toggleWPAMethods();

                                                                {/literal}

                                                            -->

                                                            </script>

							{/if}

{/if}

{if $config.PASSPHRASE_CHAR.status}

							<tr mode="16" {$hideWPARow}>

								<td class="DatablockLabel">Show Passphrase in Clear Text</td>

								<td class="DatablockContent">

                                                                    {ip_field id="showPassphrase2" name="showPassphrase2" type="radio" options="0-No,1-Yes" value="0" selectCondition="==0" onclick="showPassPhrase(this.value,this.id);"}

								</td>

							</tr>

{/if}

							{assign var="clientSeparation" value=$data.clientSeparation}

							{input_row label="Wireless Client Security Separation" id="clientSeparation" name=$parentStr.clientSeparation type="select" options=$clientSeparationList selected=$clientSeparation}

{if $config.MBSSID.status}

    {if $config.CENTRALIZED_VLAN.status}

                                                        {assign var="vlanType" value=$data.vlanType}

                                                        {assign var="vlanAccessControl" value=$data.vlanAccessControl}

                                                        {assign var="vlanAccessControlPolicy" value=$data.vlanAccessControlPolicy}

                                                        {input_row label="Dynamic VLAN" id="vlanType" name=$parentStr.vlanType type="select" options=$VLANTypeList selected=$vlanType onchange="updateDynVLANControls()"}

							{input_row label="VLAN ID" id="vlan_id" name=$parentStr.vlanID type="text" value=$data.vlanID size="4" maxlength="4" validate="Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence" disableCondition="0!=$vlanType"}

                                                        {input_row label="Access Control" id="vlanAccessControl" name=$parentStr.vlanAccessControl type="radio" options="0-Disable,1-Enable" selectCondition="==$vlanAccessControl" disableCondition="0!=$vlanType"}

                                                        {input_row label="Access Control Policy" id="vlanAccessControlPolicy" name=$parentStr.vlanAccessControlPolicy type="radio" options="0-Disable,1-Enable" selectCondition="==$vlanAccessControlPolicy" disableCondition="0!=$vlanType"}



    {else}

                                                        {input_row label="VLAN ID" id="vlan_id" name=$parentStr.vlanID type="text" value=$data.vlanID size="4" maxlength="4" validate="Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence"}

    {/if}



{/if}

{if $config.PRIORITY8021P.status}

							{input_row label="802.1P Priority" id="priority_8021P" name=$parentStr.Priority8021P type="text" value=$data.Priority8021P size="4" maxlength="1" validate="Numericality, (( minimum:0, maximum: 7, onlyInteger: true ))^Presence"}

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

		<tr>

		<td class="spacerHeight21"></td>

	</tr>

	</tr>

	

{if $config.DHCP_SNOOPING.status AND $config2.WG102.status}

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
	<!--@@@ARADA_QOSSTART@@@-->
	{if $config.ARADA_QOS.status}	
	<tr>
	<td><table class="tableStyle">
	    <tr>
	      <td colspan="3"><script>tbhdr('QoS Policies ','qospolicies')</script></td>
	    </tr>
	    <tr style="display:none">
	      <td class="subSectionBodyDot">&nbsp;</td>
	      <td >QoS Policies</td>
	      <td class="subSectionBodyDotRight">&nbsp;</td>
	    </tr>
	    <tr>
	      <td class="subSectionBodyDot">&nbsp;</td>
	      <td ><table  class="tableStyle">
	          <tr >
	            <td class="DatablockLabel"></td>
	            <td class="DatablockLabel" >Incoming</td>
	            <td class="DatablockLabel">Outgoing</td>
	          </tr>
			  	<script type='text/javascript'>
				<!--
				var curringressval="{$data.ingress}";
				var curregressval="{$data.egress}";
				{literal}
				function updateClassification(currmode,selpolicy,currrules)
				{
					var flag=true;
					if(currrules == "ingress")
					{
						if(curringressval != 'x' && curringressval != '8' && curringressval != selpolicy && selpolicy != '8' )
						{
						flag = false;	
						}
					}
					else
					{
						if(curregressval != 'x' && curregressval != '8' && curregressval != selpolicy && selpolicy != '8' )
						flag = false;
					}
					if(flag == true)
					{
						hideMessage();
						var currpolicy="vap"+selpolicy;				
						setActiveContent();
						new Ajax.Request('QoSClassifications.php',
						  {
						  method:'get',
						  parameters: {opmode: currmode,policy: currpolicy,id: Math.floor(Math.random()*10005) },    
						  onSuccess: function(QosPolicyName){
						  var response = QosPolicyName.responseText;
						  var tmpstr=response.toString().split("@");
						  $(("show_classiffication")).innerHTML="<select size='10' style='width:270px; height:60px;font-size:11px' class='smallfix2' id='Classific_policy_"+currmode+"'>"+tmpstr[0]+"</select>";
							}
						});
					}
					else
					{
						$(("show_classiffication")).innerHTML="";
						showMessage('Delete the QoS policy from the VAP before applying it');
						setActiveContent(false);
						activeCancel();
					}
				}
				
				{/literal}
				-->
				</script>
	          <tr >
	            <td class="DatablockLabel">Apply Policy</td>
	            <td class="DatablockContent">
				<input type="hidden" value="wlan{$navigation.8}" id="curropmode"/>
				<input type="hidden" value="vap{$navigation.7}" id="currpolicy"/>
				{ip_field id="egress" name=$parentStr.egress type="hidden" value=$data.egress}
				{ip_field id="ingress" name=$parentStr.ingress type="hidden" value=$data.ingress}
				{assign var="policyName" value="&"|explode:$vapQoSPolicyIdx}
				{assign var="wlan0" value=","|explode:$policyName[0]}
				{assign var="wlan1" value=","|explode:$policyName[1]}		
				<select name="policy2input0" style="width:100px"  defaultSelectedIndex="0" onchange="updateClassification('wlan{$navigation.8}',this.value,'ingress');$('ingress').value=this.value">
				<option value="8">None</option>
				{if $navigation.8=='0'}
				<script type='text/javascript'>
					<!--
					var QoSPolicies="{$vapQoSPolicyIdx}";
					{literal}
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode1=opmode1.toString().split(",");
					for(i=0;i<opmode1.length;i++)
					{
						var tmp=opmode1[i].toString().split('+')
						if(tmp[0]==1)
						{
						var str="<option value='"+i+"'"
							if(($('ingress').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					{/literal}
					-->
				</script>	
				{/if}
				{if $navigation.8=='1'}
				<script type='text/javascript'>
					<!--
					var QoSPolicies="{$vapQoSPolicyIdx}";
					{literal}
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode2=opmode2.toString().split(",");
					for(i=0;i<opmode2.length;i++)
					{
						var tmp=opmode2[i].toString().split('+')
						if(tmp[0]==1)
						{
						var str="<option value='"+i+"'"
							if(($('ingress').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					{/literal}
					-->
				</script>	
				</select>
				{/if}
				  </td>
	            <td class="DatablockContent">
				<select name="policy2input0" style="width:100px"  defaultSelectedIndex="0" onchange="updateClassification('wlan{$navigation.8}',this.value,'egress');$('egress').value=this.value">
				<option value="8">None</option>
				{if $navigation.8=='0'}
				<script type='text/javascript'>
					<!--
					var QoSPolicies="{$vapQoSPolicyIdx}";
					{literal}
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode1=opmode1.toString().split(",");
					for(i=0;i<opmode1.length;i++)
					{
						var tmp=opmode1[i].toString().split('+')
						if(tmp[0]==1)
						{
						var str="<option value='"+i+"'"
							if(($('egress').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					{/literal}
					-->
				</script>	
				{/if}
				{if $navigation.8=='1'}
				<script type='text/javascript'>
					<!--
					var QoSPolicies="{$vapQoSPolicyIdx}";
					{literal}
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode2=opmode2.toString().split(",");
					for(i=0;i<opmode2.length;i++)
					{
						var tmp=opmode2[i].toString().split('+')
						if(tmp[0]==1)
						{
						var str="<option value='"+i+"'"
							if(($('egress').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					{/literal}
					-->
				</script>	
				</select>
				{/if}
				</td>
	          </tr>
	          <tr >
	            <td class="DatablockLabel" style="height:60px" valign="top">Policy Details</td>
	            <td class="DatablockContent" colspan="2" >
				<div style="width:270px" id="show_classiffication" >
				
				</div>
				</td>
	          </tr>
	        </table></td>
	      <td class="subSectionBodyDotRight">&nbsp;</td>
	    </tr>
	    <tr>
	      <td colspan="3" class="subSectionBottom"></td>
	    </tr>
	  </table></td>
	</tr>
	{/if}
	<!--@@@ARADA_QOSEND@@@-->	

	<input type="hidden" id="radiusEnabled" value="{if $data.priRadIpAddr eq '0.0.0.0'}false{else}true{/if}">
	<!--@@@IPV6START@@@-->
	<input type="hidden" id="radiusv6Enabled" value="{if $data.priRadIpv6Addr eq '--0'}false{else}true{/if}">
	<!--@@@IPV6END@@@-->
	
    <input type="hidden" name="previousInterfaceNum" id="previousInterfaceNum" value="{$smarty.post.previousInterfaceNum}">

{if $config.WPS.status}

        <input type="hidden" id="wpsDisableStatus" name="wpsDisableStatus" value="{$wpsDisable}">

{/if}

{if $config.WN604.status}

        <input type="hidden" name="pskTouched" id="pskTouched">

        <input type="hidden" name="passPhraseTouched" id="passPhraseTouched">

{/if}

	<script language="javascript">

	<!--

        {literal}

        if(($('errorMessageBlock').style.display != 'none') && ($('br_head').innerHTML == 'Wireless Radio is turned off!')){

            Form.disable(document.dataForm);

        }

        {/literal}

		var buttons = new buttonObject();

		buttons.getStaticButtons(['back']);

        if(!(config.MBSSID.status))

            top.action.$('back').style.display = 'none';

		{literal}

		function doBack()

		{

			$('mode7').value='';

			doSubmit('cancel');

		}

		{/literal}



	{if  $config.TWOGHZ.status AND !$config.FIVEGHZ.status}

        {literal}

            if(twoGHzEmpty == 1){

                Form.disable(document.dataForm);

            }

        {/literal}

    {/if}

	-->

	</script>
