	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('General','basicSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{input_row label="Access Point Name" id="apName" name=$parentStr.basicSettings.apName type="text" class="input" value=$data.basicSettings.apName size="16" maxlength="15" validate="Presence^AlphaNumericWithH^NotAllNums^NotLastH"}
							{input_row label="Country / Region" id="sysCountryRegion" name=$parentStr.basicSettings.sysCountryRegion type="select" class="select" options=$countryList selected=$data.basicSettings.sysCountryRegion}
							{ip_field label="&nbsp;" id="sysCountry" name="sysCountry" type="hidden" value=$data.basicSettings.sysCountryRegion}
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
