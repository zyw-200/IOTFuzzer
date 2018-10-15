	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Ethernet','ethernet')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
										{assign var="configType" value=$data.ethernetSettings.configType}
										{input_row label="Configuration Type" id="configType" name=$parentStr.ethernetSettings.configType type="radio" options="0-Auto,1-Manual" selectCondition="==$configType" onclick="$('speed').disabled=(this.value==1?false:true);"}
										
										{input_row label="Speed" id="speed" name=$parentStr.ethernetSettings.speed type="select" options=$speedList selected=$data.ethernetSettings.speed disableCondition="1!=$configType"}
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