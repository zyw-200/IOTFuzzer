<script language="javascript">
<!--
    var rad1Status = 1;
    var rad2Status = 1;
-->
</script>
<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Packet Capture','packetCapture')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<tr>
								<td class="DatablockContent" style="text-align: center;">
									<input type="button" name="start" id="start" value="Start" onclick="doPacketCapture('start')" {if $data.monitor.pktCaptureStatus eq '1'}disabled="disabled"{/if}>&nbsp;&nbsp;
									<input type="button" name="stop" id="stop" value="Stop" onclick="doPacketCapture('stop')" {if $data.monitor.pktCaptureStatus neq '1'}disabled="disabled"{/if}>&nbsp;&nbsp;
									<input type="button" name="saveas" id="saveas" value="Save as..." onclick="doSave()" {if $data.monitor.pktCaptureStatus neq '2'}disabled="disabled"{/if}>
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
    {if $config.TWOGHZ.status}
        {if $data.wlanSettings.wlanSettingTable.wlan0.radioStatus eq 0}
                rad1Status = 0;
        {/if}
    {else}
                rad1Status = 0;
    {/if}

    {if $config.FIVEGHZ.status}
        {if $data.wlanSettings.wlanSettingTable.wlan1.radioStatus eq '0'}
                rad2Status = 0;
        {/if}
    {else}
                rad2Status = 0;
    {/if}

    {literal}
    if(rad1Status == 0 && rad2Status == 0){
        document.getElementById('start').disabled=true;
        document.getElementById('stop').disabled=true;
        document.getElementById('saveas').disabled=true;
    }
    {/literal}

{literal}
        function doSave()
        {
            document.location.href='downloadFile.php?file=pcap&id='+Math.random(10000,99999);
            return false;

        }
{/literal}

{if ($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
	Form.disable(document.dataForm);
{/if}
	-->
	</script>
