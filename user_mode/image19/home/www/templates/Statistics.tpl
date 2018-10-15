	<tr>

		<td>

			<table class="tableStyle">

				<tr>

					<td colspan="3">

						<table class='tableStyle'>

							<tr>

								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Wired Ethernet</td>

								<td class='subSectionTabTopRight spacer40Percent'>

									<a href='javascript: void(0);' onclick="showHelp('Wired Ethernet','wiredEthernet');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>

							</tr>

							<tr>

								<td colspan='3' class='subSectionTabTopShadow'></td>

							</tr>

						</table>

					</td>

				</tr>

				<tr>

					<td class="subSectionBodyDot">&nbsp;</td>

					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">

						<table class="tableStyle">

							<tr>

								<td>

									<div  id="BlockContentTable">

										<table class="BlockContentTable">

											<tr>

												<th>&nbsp;</th>

												<th>Received</th>

												<th class="Last">Transmitted</th>

											</tr>

											<tr>

												<th>Packets</th>

												<td>{$data.monitor.stats.lan.lanRecvPacket}</td>

												<td>{$data.monitor.stats.lan.lanTransPacket}</td>

											</tr>

											<tr class="Alternate">

												<th>Bytes</th>

												<td class="Alternate">{$data.monitor.stats.lan.lanRecvBytes}</td>

												<td class="Alternate">{$data.monitor.stats.lan.lanTransBytes}</td>

											</tr>

										</table>

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

	<tr>

		<td class="spacerHeight21">&nbsp;</td>

	</tr>

{if $config.TWOGHZ.status AND $data.radioStatus0 eq '1'}

	<tr id="wlan1">

		<td>

			<table class="tableStyle" style="width: 432px;">

				<tr>

					<td colspan="3">

						<table class='tableStyle'>

							<tr>

								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Wireless 802.11{$wlan0ModeString}</td>

								<td class='subSectionTabTopRight spacer40Percent'>

									<a href='javascript: void(0);' onclick="showHelp('Wireless Statistics','wirelessStatistics');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>

							</tr>

							<tr>

								<td colspan='3' class='subSectionTabTopShadow'></td>

							</tr>

						</table>

					</td>

				</tr>

				<tr>

					<td class="subSectionBodyDot">&nbsp;</td>

					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">

						<table class="tableStyle">

							<tr>

								<td>

									<div  id="BlockContentTable">

										<table class="BlockContentTable">

											<tr>

												<th>&nbsp;</th>

												<th>Received</th>

												<th class="Last">Transmitted</th>

											</tr>

											<tr>

												<th>Unicast Packets</th>

												<td>{$data.monitor.stats.wlan0.wlanRecvUnicastPacket}</td>

												<td>{$data.monitor.stats.wlan0.wlanTransUnicastPacket}</td>

											</tr>

											<tr class="Alternate">

												<th>Broadcast Packets</th>

												<td class="Alternate">{$data.monitor.stats.wlan0.wlanRecvBroadcastPacket}</td>

												<td class="Alternate">{$data.monitor.stats.wlan0.wlanTransBroadcastPacket}</td>

											</tr>

											<tr>

												<th>Multicast Packets</th>

												<td>{$data.monitor.stats.wlan0.wlanRecvMulticastPacket}</td>

												<td>{$data.monitor.stats.wlan0.wlanTransMulticastPacket}</td>

											</tr>

											<tr class="Alternate">

												<th>Total Packets</th>

												<td class="Alternate">{$data.monitor.stats.wlan0.wlanRecvPacket}</td>

												<td class="Alternate">{$data.monitor.stats.wlan0.wlanTransPacket}</td>

											</tr>

											<tr>

												<th>Total Bytes</th>

												<td>{$data.monitor.stats.wlan0.wlanRecvBytes}</td>

												<td>{$data.monitor.stats.wlan0.wlanTransBytes }</td>

											</tr>

										</table>

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

	<tr>

		<td class="spacerHeight21"></td>

	</tr>

{/if}

<!--@@@FIVEGHZSTART@@@-->

{if $config.FIVEGHZ.status AND $data.radioStatus1 eq '1'}

	<tr id="wlan2">

		<td>

			<table class="tableStyle" style="width: 432px;">

				<tr>

					<td colspan="3">

						<table class='tableStyle'>

							<tr>

								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Wireless 802.11{$wlan1ModeString}</td>

								<td class='subSectionTabTopRight spacer40Percent'>

									<a href='javascript: void(0);' onclick="showHelp('Wireless Statistics','wirelessStatistics');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>

							</tr>

							<tr>

								<td colspan='3' class='subSectionTabTopShadow'></td>

							</tr>

						</table>

					</td>

				</tr>

				<tr>

					<td class="subSectionBodyDot">&nbsp;</td>

					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">

						<table class="tableStyle">

							<tr>

								<td>

									<div  id="BlockContentTable">

										<table class="BlockContentTable">

											<tr>

												<th>&nbsp;</th>

												<th>Received</th>

												<th class="Last">Transmitted</th>

											</tr>

											<tr>

												<th>Unicast Packets</th>

												<td>{$data.monitor.stats.wlan1.wlanRecvUnicastPacket}</td>

												<td>{$data.monitor.stats.wlan1.wlanTransUnicastPacket}</td>

											</tr>

											<tr class="Alternate">

												<th>Broadcast Packets</th>

												<td class="Alternate">{$data.monitor.stats.wlan1.wlanRecvBroadcastPacket}</td>

												<td class="Alternate">{$data.monitor.stats.wlan1.wlanTransBroadcastPacket}</td>

											</tr>

											<tr>

												<th>Multicast Packets</th>

												<td>{$data.monitor.stats.wlan1.wlanRecvMulticastPacket}</td>

												<td>{$data.monitor.stats.wlan1.wlanTransMulticastPacket}</td>

											</tr>

											<tr class="Alternate">

												<th>Total Packets</th>

												<td class="Alternate">{$data.monitor.stats.wlan1.wlanRecvPacket}</td>

												<td class="Alternate">{$data.monitor.stats.wlan1.wlanTransPacket}</td>

											</tr>

											<tr>

												<th>Total Bytes</th>

												<td>{$data.monitor.stats.wlan1.wlanRecvBytes}</td>

												<td>{$data.monitor.stats.wlan1.wlanTransBytes }</td>

											</tr>

										</table>

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

	<tr>

		<td class="spacerHeight21"></td>

	</tr>

{/if}

<!--@@@FIVEGHZEND@@@-->

{if !($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}

<tr>

		<td>

			<table class="tableStyle">

				<tr>

					<td colspan="3">

						<table class='tableStyle'>

							<tr>

								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Client Association</td>

								<td class='subSectionTabTopRight spacer40Percent'>

									<a href='javascript: void(0);' onclick="showHelp('Client Association','clientAssociation');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>

							</tr>

							<tr>

								<td colspan='3' class='subSectionTabTopShadow'></td>

							</tr>

						</table>

					</td>

				</tr>

				<tr>

					<td class="subSectionBodyDot">&nbsp;</td>

					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">

						<table class="tableStyle">

							<tr>

								<td>

									<div  id="BlockContentTable">

										<table class="BlockContentTable">

											<tr>

												<th>&nbsp;</th>

												<th>Number of Associated Clients</th>

											</tr>

{if $config.TWOGHZ.status AND $data.radioStatus0 eq '1'}

											<tr>

												<th>802.11{$wlan0ModeString} Radio</th>

												<td>
												{$data.monitor.radioApStatus.wlan0.numberOfStations}												
												</td>

											</tr>

{/if}

{if $config.FIVEGHZ.status AND $data.radioStatus1 eq '1'}

											<tr class="Alternate">

												<th>802.11{$wlan1ModeString} Radio</th>

												<td class="Alternate">
												{$data.monitor.radioApStatus.wlan1.numberOfStations}												
												</td>

    											</tr>

{/if}

										</table>

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

{/if}

	<tr>

		<td class="spacerHeight21">&nbsp;</td>

	</tr>

<script language="javascript">

<!--



{if $config.CLIENT.status}

	{if ($config.TWOGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5) OR ($config.FIVEGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan1.apMode eq 5)}

		//Form.disable(document.dataForm);

		//window.top.frames['action'].$('refresh').disabled=true;

		//window.top.frames['action'].$('refresh').src="images/refresh_off.gif";

	{/if}

{/if}

-->

</script>

