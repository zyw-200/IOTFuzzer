<?php /* Smarty version 2.6.18, created on 2012-03-31 17:09:41
         compiled from Statistics.tpl */ ?>
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

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['lan']['lanRecvPacket']; ?>
</td>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['lan']['lanTransPacket']; ?>
</td>

											</tr>

											<tr class="Alternate">

												<th>Bytes</th>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['lan']['lanRecvBytes']; ?>
</td>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['lan']['lanTransBytes']; ?>
</td>

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

<?php if ($this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['radioStatus0'] == '1'): ?>

	<tr id="wlan1">

		<td>

			<table class="tableStyle" style="width: 432px;">

				<tr>

					<td colspan="3">

						<table class='tableStyle'>

							<tr>

								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Wireless 802.11<?php echo $this->_tpl_vars['wlan0ModeString']; ?>
</td>

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

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanRecvUnicastPacket']; ?>
</td>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanTransUnicastPacket']; ?>
</td>

											</tr>

											<tr class="Alternate">

												<th>Broadcast Packets</th>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanRecvBroadcastPacket']; ?>
</td>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanTransBroadcastPacket']; ?>
</td>

											</tr>

											<tr>

												<th>Multicast Packets</th>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanRecvMulticastPacket']; ?>
</td>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanTransMulticastPacket']; ?>
</td>

											</tr>

											<tr class="Alternate">

												<th>Total Packets</th>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanRecvPacket']; ?>
</td>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanTransPacket']; ?>
</td>

											</tr>

											<tr>

												<th>Total Bytes</th>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanRecvBytes']; ?>
</td>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan0']['wlanTransBytes']; ?>
</td>

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

<?php endif; ?>

<!--@@@FIVEGHZSTART@@@-->

<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status'] && $this->_tpl_vars['data']['radioStatus1'] == '1'): ?>

	<tr id="wlan2">

		<td>

			<table class="tableStyle" style="width: 432px;">

				<tr>

					<td colspan="3">

						<table class='tableStyle'>

							<tr>

								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Wireless 802.11<?php echo $this->_tpl_vars['wlan1ModeString']; ?>
</td>

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

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanRecvUnicastPacket']; ?>
</td>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanTransUnicastPacket']; ?>
</td>

											</tr>

											<tr class="Alternate">

												<th>Broadcast Packets</th>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanRecvBroadcastPacket']; ?>
</td>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanTransBroadcastPacket']; ?>
</td>

											</tr>

											<tr>

												<th>Multicast Packets</th>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanRecvMulticastPacket']; ?>
</td>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanTransMulticastPacket']; ?>
</td>

											</tr>

											<tr class="Alternate">

												<th>Total Packets</th>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanRecvPacket']; ?>
</td>

												<td class="Alternate"><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanTransPacket']; ?>
</td>

											</tr>

											<tr>

												<th>Total Bytes</th>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanRecvBytes']; ?>
</td>

												<td><?php echo $this->_tpl_vars['data']['monitor']['stats']['wlan1']['wlanTransBytes']; ?>
</td>

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

<?php endif; ?>

<!--@@@FIVEGHZEND@@@-->

<?php if (! ( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 5 )): ?>

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

<?php if ($this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['radioStatus0'] == '1'): ?>

											<tr>

												<th>802.11<?php echo $this->_tpl_vars['wlan0ModeString']; ?>
 Radio</th>

												<td>
												<?php echo $this->_tpl_vars['data']['monitor']['radioApStatus']['wlan0']['numberOfStations']; ?>
												
												</td>

											</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status'] && $this->_tpl_vars['data']['radioStatus1'] == '1'): ?>

											<tr class="Alternate">

												<th>802.11<?php echo $this->_tpl_vars['wlan1ModeString']; ?> Radio</th>

												<td class="Alternate">
												<?php echo $this->_tpl_vars['data']['monitor']['radioApStatus']['wlan1']['numberOfStations']; ?>
												
												</td>

    											</tr>

<?php endif; ?>

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

<?php endif; ?>

	<tr>

		<td class="spacerHeight21">&nbsp;</td>

	</tr>

<script language="javascript">

<!--



<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>

	<?php if (( $this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 5 ) || ( $this->_tpl_vars['config']['FIVEGHZ']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == 5 )): ?>

		//Form.disable(document.dataForm);

		//window.top.frames['action'].$('refresh').disabled=true;

		//window.top.frames['action'].$('refresh').src="images/refresh_off.gif";

	<?php endif; ?>

<?php endif; ?>

-->

</script>
