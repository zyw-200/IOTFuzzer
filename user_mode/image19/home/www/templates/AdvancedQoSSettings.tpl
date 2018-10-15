	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Qos Settings','modifyQoSQueueParameters')</script></td>
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
										<div id="wlan1">
											<div class="BlockContentTable" style="border-bottom: 0px;" id="table_wlan1">
												{data_header label="AP EDCA parameters" headerType="inline"}
												<table class="BlockContentTable">
                                                    <input type="hidden" name="dummyAPMode0" id="ApMode0" value="{$data.wlanSettings.wlanSettingTable.wlan0.apMode}">
                                                    <input type="hidden" name="dummyWMMSupport0" id="WMMSupport0" value="{$data.wlanSettings.wlanSettingTable.wlan0.wmmSupport}">

													<tr>
														<th>Queue</th>
														<th>AIFS</th>
														<th>cwMin</th>
														<th>cwMax</th>
														<th class="Last">Max. Burst</th>
													</tr>
													<tr>
														<th>Data 0 (Best Effort)</th>
														<td>{ip_field label="Data 0 (Best Effort) AIFS" size="3" maxlength="3" id="01_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMin" id="wmmApEdcaCwMin0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMax" id="wmmApEdcaCwMax0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaCwMax}</td>
														<td>{ip_field label="Data 0 (Best Effort) Max. Burst" size="5" maxlength="5" id="01_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.1.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 1 (Background)</th>
														<td class="Alternate">{ip_field label="Data 1 (Background) AIFS" size="3" maxlength="3" id="02_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMin" id="wmmApEdcaCwMin0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMax" id="wmmApEdcaCwMax0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 1 (Background) Max. Burst" size="5" maxlength="5" id="02_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.2.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr>
														<th>Data 2 (Video)</th>
														<td>{ip_field label="Data 2 (Video) AIFS" size="3" maxlength="3" id="03_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMin" id="wmmApEdcaCwMin0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMax" id="wmmApEdcaCwMax0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaCwMax}</td>
														<td>{ip_field label="Data 2 (Video) Max. Burst" size="5" maxlength="5" id="03_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.3.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 3 (Voice)</th>
														<td class="Alternate">{ip_field label="Data 3 (voice) AIFS" size="3" maxlength="3" id="04_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMin" id="wmmApEdcaCwMin0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMax" id="wmmApEdcaCwMax0" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 3 (voice) Max. Burst" size="5" maxlength="5" id="04_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan0.4.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
												</table>
												{data_header label="Station EDCA parameters" headerType="inline"}
												<table class="BlockContentTable">
													<tr>
														<th>Queue</th>
														<th>AIFS</th>
														<th>cwMin</th>
														<th>cwMax</th>
														<th class="Last">TXOP Limit</th>
													</tr>
													<tr>
														<th>Data 0 (Best Effort)</th>
														<td>{ip_field label="Data 0 (Best Effort) AIFS" size="3" maxlength="3" id="01_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMin" id="wmmStaEdcaCwMin0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMax" id="wmmStaEdcaCwMax0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaCwMax}</td>
														<td>{ip_field label="Data 0 (Best Effort) TXOP Limit" size="5" maxlength="5" id="01_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.1.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 1 (Background)</th>
														<td class="Alternate">{ip_field label="Data 1 (Background) AIFS" size="3" maxlength="3" id="02_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMin" id="wmmStaEdcaCwMin0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMax" id="wmmStaEdcaCwMax0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 1 (Background) TXOP Limit" size="5" maxlength="5" id="02_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.2.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr>
														<th>Data 2 (Video)</th>
														<td>{ip_field label="Data 2 (Video) AIFS" size="3" maxlength="3" id="03_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMin" id="wmmStaEdcaCwMin0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMax" id="wmmStaEdcaCwMax0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaCwMax}</td>
														<td>{ip_field label="Data 2 (Video) TXOP Limit" size="5" maxlength="5" id="03_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.3.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 3 (Voice)</th>
														<td class="Alternate">{ip_field label="Data 3 (voice) AIFS" size="3" maxlength="3" id="04_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMin" id="wmmStaEdcaCwMin0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMax" id="wmmStaEdcaCwMax0" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 3 (voice) TXOP Limit" size="5" maxlength="5" id="04_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan0.4.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
												</table>
											</div>
										</div>
{/if}
<!--@@@FIVEGHZSTART@@@-->
{if $config.FIVEGHZ.status}
										<div id="wlan2" {if $config.TWOGHZ.status AND not $config.DUAL_CONCURRENT.status}style="display:none;"{/if}>
											<div class="BlockContentTable" style="border-bottom: 0px;" id="table_wlan2">
												{data_header label="AP EDCA parameters" headerType="inline"}
												<table class="BlockContentTable">
                                                    <input type="hidden" name="dummyAPMode1" id="ApMode1" value="{$data.wlanSettings.wlanSettingTable.wlan1.apMode}">
                                                    <input type="hidden" name="dummyWMMSupport1" id="WMMSupport1" value="{$data.wlanSettings.wlanSettingTable.wlan1.wmmSupport}">

													<tr>
														<th>Queue</th>
														<th>AIFS</th>
														<th>cwMin</th>
														<th>cwMax</th>
														<th class="Last">Max. Burst</th>
													</tr>
													<tr>
														<th>Data 0 (Best Effort)</th>
														<td>{ip_field label="Data 0 (Best Effort) AIFS" size="3" maxlength="3" id="11_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMin" id="wmmApEdcaCwMin1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMax" id="wmmApEdcaCwMax1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaCwMax}</td>
														<td>{ip_field label="Data 0 (Best Effort) Max. Burst" size="5" maxlength="5" id="11_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.1.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 1 (Background)</th>
														<td class="Alternate">{ip_field label="Data 1 (Background) AIFS" size="3" maxlength="3" id="12_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMin" id="wmmApEdcaCwMin1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMax" id="wmmApEdcaCwMax1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 1 (Background) Max. Burst" size="5" maxlength="5" id="12_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.2.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr>
														<th>Data 2 (Video)</th>
														<td>{ip_field label="Data 2 (Video) AIFS" size="3" maxlength="3" id="13_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMin" id="wmmApEdcaCwMin1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMax" id="wmmApEdcaCwMax1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaCwMax}</td>
														<td>{ip_field label="Data 2 (Video) Max. Burst" size="5" maxlength="5" id="13_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.3.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 3 (Voice)</th>
														<td class="Alternate">{ip_field label="Data 3 (voice) AIFS" size="3" maxlength="3" id="14_wmmApEdcaAifs" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaAifs value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMin" id="wmmApEdcaCwMin1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMax" id="wmmApEdcaCwMax1" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 3 (voice) Max. Burst" size="5" maxlength="5" id="14_wmmApEdcaMaxBurst" name=$parentStr.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaMaxBurst value=$data.wmmSettings.wmmApEdcaSettingTable.wlan1.4.wmmApEdcaMaxBurst type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
												</table>
												{data_header label="Station EDCA parameters" headerType="inline"}
												<table class="BlockContentTable">
													<tr>
														<th>Queue</th>
														<th>AIFS</th>
														<th>cwMin</th>
														<th>cwMax</th>
														<th class="Last">TXOP Limit</th>
													</tr>
													<tr>
														<th>Data 0 (Best Effort)</th>
														<td>{ip_field label="Data 0 (Best Effort) AIFS" size="3" maxlength="3" id="11_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMin" id="wmmStaEdcaCwMin1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 0 (Best Effort) cwMax" id="wmmStaEdcaCwMax1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaCwMax}</td>
														<td>{ip_field label="Data 0 (Best Effort) TXOP Limit" size="5" maxlength="5" id="11_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.1.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 1 (Background)</th>
														<td class="Alternate">{ip_field label="Data 1 (Background) AIFS" size="3" maxlength="3" id="12_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMin" id="wmmStaEdcaCwMin1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 1 (Background) cwMax" id="wmmStaEdcaCwMax1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 1 (Background) TXOP Limit" size="5" maxlength="5" id="12_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.2.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr>
														<th>Data 2 (Video)</th>
														<td>{ip_field label="Data 2 (Video) AIFS" size="3" maxlength="3" id="13_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMin" id="wmmStaEdcaCwMin1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaCwMin}</td>
														<td>{ip_field type="select" label="Data 2 (Video) cwMax" id="wmmStaEdcaCwMax1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaCwMax}</td>
														<td>{ip_field label="Data 2 (Video) TXOP Limit" size="5" maxlength="5" id="13_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.3.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
													<tr class="Alternate">
														<th>Data 3 (Voice)</th>
														<td class="Alternate">{ip_field label="Data 3 (voice) AIFS" size="3" maxlength="3" id="14_wmmStaEdcaAifs" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaAifs value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaAifs type="text" validate="Numericality, (( minimum:0, maximum: 8, onlyInteger: true ))^Presence"}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMin" id="wmmStaEdcaCwMin1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaCwMin type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaCwMin}</td>
														<td class="Alternate">{ip_field type="select" label="Data 3 (voice) cwMax" id="wmmStaEdcaCwMax1" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaCwMax type="select" options=$apEdcaCwList selected=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaCwMax}</td>
														<td class="Alternate">{ip_field label="Data 3 (voice) TXOP Limit" size="5" maxlength="5" id="14_wmmStaEdcaTxopLimit" name=$parentStr.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaTxopLimit value=$data.wmmSettings.wmmStaEdcaSettingTable.wlan1.4.wmmStaEdcaTxopLimit type="text" validate="Numericality, (( minimum:0, maximum: 8192, onlyInteger: true ))^Presence"}</td>
													</tr>
												</table>
											</div>
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
	{ip_field label="&nbsp;" id="enableQoS" name="enableQoS" type="hidden" enableForm="true"}
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
