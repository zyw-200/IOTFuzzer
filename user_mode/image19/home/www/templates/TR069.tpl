<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('TR 069 Settings','tr069settings')</script></td>
					
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
					<table class="tableStyle">
							{assign var="tr069Status" value=$data.tr069CpeConfiguration.tr069Status}
							{input_row label="TR 069" id="tr069Status" name=$parentStr.tr069CpeConfiguration.tr069Status type="radio" options="1-Enable,0-Disable" selectCondition="==$tr069Status" onclick="checkSNMPStatus(this, true)"}

							<tr>
								<td class="spacerHeight21"></td>
							</tr>														
							{input_row label="ACS URL" id="acsurl" name=$parentStr.tr069CpeConfiguration.acsURL type="text" value=$data.tr069CpeConfiguration.acsURL disableCondition="0==$tr069Status" size="32" maxlength="128" validate="Presence^IpAddress, (( allowURL: true, allowZero: false, allowGeneric: false ))"}
							{input_row label="ACS User Name" id="acsusrname" name=$parentStr.tr069CpeConfiguration.acsUserName type="text" value=$data.tr069CpeConfiguration.acsUserName disableCondition="0==$tr069Status" size="32" maxlength="32" validate="Presence^AlphaNumericWithHU"}
							{input_row label="ACS Password" id="acspasswd" name=$parentStr.tr069CpeConfiguration.acsPassword type="password" value=$data.tr069CpeConfiguration.acsPassword disableCondition="0==$tr069Status" size="32" maxlength="32" validate="Presence, (( onlyIfChecked: 'dummy' ))^Ascii"}
							<tr>
								<td class="spacerHeight21"></td>
							</tr>
							{assign var="periodicInformEnable" value=$data.tr069CpeConfiguration.periodicInformEnable}
							{input_row label="Periodic Inform" id="prdinfostatus" name=$parentStr.tr069CpeConfiguration.periodicInformEnable disableCondition="0==$tr069Status" type="radio" options="1-Enable,0-Disable" selectCondition="==$periodicInformEnable"}
							
							{input_row label="Periodic Inform Interval" id="prdInfoInterval" name=$parentStr.tr069CpeConfiguration.periodicInformInterval type="text" class="input" value=$data.tr069CpeConfiguration.periodicInformInterval disableCondition="0==$tr069Status" size="10" maxlength="10" validate="Numericality, (( minimum:1, maximum: 4294967295, onlyInteger: true ))^Presence"}
							{input_row label="Periodic Inform Time" id="prdinfotime" name=$parentStr.tr069CpeConfiguration.periodicInformTime type="text" value=$data.tr069CpeConfiguration.periodicInformTime  size="32" maxlength="32" validate="Presence^Ascii" disableCondition="0==$tr069Status"}							
							
							<tr>
								<td class="spacerHeight21"></td>
							</tr>							
							{input_row label="Connection Request User Name" id="conReqsername" name=$parentStr.tr069CpeConfiguration.connectionRequestUserName type="text" value=$data.tr069CpeConfiguration.connectionRequestUserName  disableCondition="0==$tr069Status" size="32" maxlength="32" validate="Presence^AlphaNumericWithHU"}
							{input_row label="Connection Request User Password" id="conreqserpwd" name=$parentStr.tr069CpeConfiguration.connectionRequestUserPassword type="password" value=$data.tr069CpeConfiguration.connectionRequestUserPassword  disableCondition="0==$tr069Status" size="32" maxlength="32" validate="Presence, (( onlyIfChecked: 'dummy' ))^Ascii"}
	
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
		<td class="spacerHeight21"></td>
	</tr>
	
	<script language="javascript">
	<!--
	{if $config.SNMP.status}
					{if $data.remoteSettings.snmpStatus eq '1'}
									     var snmpOnStatus=true;
				{/if}
			
	{/if}		
	-->
	</script>