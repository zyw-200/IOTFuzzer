{*	<table align="left" class="datablockMain">
		<tr>
			<td>
				<table class="datablock" align="left">
					<tr>
						<td>
						{data_header label="Block Header"}
						</td>
					</tr>
					<tr>
						<td>
							<div  id="BlockContent">
								<table class="BlockContent">			
									<tr>
										<td class="DatablockLabel">Text Label</td>
										<td class="DatablockContent">Input field</td>
										<td class="DatablockText InstructionalText"><!--Instructional Text--></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>						
		<tr>
			<td class="separator"></td>
		</tr>						
	</table>*

<table class="detailsContainer">
	<tr>
		<td class="font15Bold">DHCP Server Configuration</td>
	</tr>
	<tr>
		<td class="spacerHeight9"><div id="errorMessageBlock" {if $errorString eq ''}style="display: none;"{/if}><b id="br_head"><img src="images/alert.gif" style="border: 0px; padding: 0px; margin: 0px;">{if $errorString eq ''}Please address the fields highlighted!{else}{$errorString}{/if}</b></div></td>
	</tr>
	<!-- till here included in data.tpl -->
	
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('DHCP Server Configuration','dhcpServerConfiguration')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot"></td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<tr>
								<td class="font10Bold padding4Top">Ping Packet Count</td>
								<td class="padding4Top"><input name="serverIP1" id="serverIP1" type="text" class="input" value="1" onKeyPress="setActiveContent()" validate="Presence"/>
								</td>
							</tr>
						</table>
					</td>
					<td class="subSectionBodyDotRight"></td>
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