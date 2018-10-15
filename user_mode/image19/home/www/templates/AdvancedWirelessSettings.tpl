	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Wireless Settings','wirelessLANParameters')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
						<table class="tableStyle">
					<tr>
						<td>
							{include file="bandStrip.tpl"}
                            {if $config.TWOGHZ.status}
                                {if $data.radioStatus0 eq '0'}
                                    {php}
                                    $this->_tpl_vars['data']['activeMode0'] = 2;
                                    $this->_tpl_vars['data']['activeMode'] = 2;
                                    {/php}
                                {/if}
                            {/if}
<!--@@@FIVEGHZSTART@@@-->
                            {if $config.FIVEGHZ.status}
                                {if $data.radioStatus1 eq '0'}
                                    {php}
                                    $this->_tpl_vars['data']['activeMode1'] = 4;
                                    $this->_tpl_vars['data']['activeMode'] = 4;
                                    {/php}
                                {/if}
                            {/if}
<!--@@@FIVEGHZEND@@@-->
							<div id="IncludeTabBlock">
{if $config.TWOGHZ.status}
								{assign var="apMode" value=$data.wlanSettings.wlanSettingTable.wlan0.apMode}
								<div  class="BlockContent" id="wlan1">
									<table class="BlockContent Trans" id="table_wlan1">
<!--@@@SUPERGSTART@@@-->
	{if $config.MODE11G.status AND $config.MODE11G.SUPERG.status}
											{assign var="supergMode" value=$data.wlanSettings.wlanSettingTable.wlan0.supergMode}
											{assign var="operateMode" value=$data.wlanSettings.wlanSettingTable.wlan0.operateMode}
											{input_row label="Super-G Mode" id="idsupergMode0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.supergMode type="radio" options="1-Enable,0-Disable" value=$supergMode selectCondition="==$supergMode" disableCondition="1!=$operateMode"}
	{/if}
<!--@@@SUPERGEND@@@-->
											{input_row label="RTS Threshold (0-2347)" id="iRTSThreshold0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.rtsThreshold type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.rtsThreshold size="5" maxlength="4" validate="Numericality, (( minimum: 0, maximum: 2347, onlyInteger: true ))^Presence"}
											{input_row label="Fragmentation Length (256-2346)" id="fragmentThreshold0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.fragLength type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.fragLength size="5" maxlength="4" validate="Numericality, (( minimum: 256, maximum: 2346, onlyInteger: true ))^Presence"}

                                            {if $config.WN604.status}
											{input_row label="Beacon Interval (20-100)" id="beaconInterval0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.beaconInterval type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.beaconInterval size="5" maxlength="4" validate="Numericality, (( minimum: 20, maximum: 100, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
											{else}
											{input_row label="Beacon Interval (100-1000)" id="beaconInterval0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.beaconInterval type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.beaconInterval size="5" maxlength="4" validate="Numericality, (( minimum: 100, maximum: 1000, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
											{/if}
											{if  $config.MODE11N.status AND ($data.activeMode eq '2' OR $data.activeMode0 eq '2')}
											{input_row label="Aggregation Length (1024-65535)" id="aggregationLength0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.ampduAggrLength type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.ampduAggrLength size="6" maxlength="5" validate="Numericality, (( minimum: 1024, maximum: 65535, onlyInteger: true ))^Presence"}
											{assign var="ampdu" value=$data.wlanSettings.wlanSettingTable.wlan0.ampdu}
											{input_row label="AMPDU" id="idampdu0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.ampdu type="radio" options="1-Enable,0-Disable" value=$ampdu selectCondition="==$ampdu"}
											{assign var="rifsTransmission" value=$data.wlanSettings.wlanSettingTable.wlan0.rifsTransmission}
											{input_row label="RIFS Transmission" id="idrfis0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.rifsTransmission type="radio" options="1-Enable,0-Disable" value=$rifsTransmission selectCondition="==$rifsTransmission" disableCondition="5==$apMode"}
											{/if}
											{input_row label="DTIM Interval (1-255)" id="dtimInterval0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.dtimInterval type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.dtimInterval size="5" maxlength="3" validate="Numericality, (( minimum:1, maximum: 255, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
											{assign var="preambleType" value=$data.wlanSettings.wlanSettingTable.wlan0.preambleType}
                                            {input_row label="Preamble Type" id="idpreamble0"  name=$parentStr.wlanSettings.wlanSettingTable.wlan0.preambleType type="radio" options="0-Auto,1-Long" value=$preambleType selectCondition="==$preambleType" disableCondition="5==$apMode"}
<!--@@@ANTENNA_SELECTIONSTART@@@-->
    {if $config.ANTENNA_SELECTION.status}
											{assign var="antenna" value=$data.wlanSettings.wlanSettingTable.wlan0.antenna}
											{input_row label="Antenna" id="idantenna0"  name=$parentStr.wlanSettings.wlanSettingTable.wlan0.antenna type="radio" options="0-Internal,1-External" value=$antenna selectCondition="==$antenna" }
    {/if}
<!--@@@ANTENNA_SELECTIONEND@@@-->
                                            {assign var="elevenDSupport" value=$data.wlanSettings.wlanSettingTable.wlan0.11dSupport}
                                            {input_row label="802.11d" id="eDSupport" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.11dSupport type="checkbox" value="$elevenDSupport" selectCondition="==1" disableCondition="5==$apMode"}
<!--@@@CLIENT_SEPARATIONSTART@@@-->
	{if $config.CLIENT_SEPARATION.status}
											{input_row label="Client Isolation" id="clientSeparation0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.clientSeparation type="select" options=$clientSeparationList selected=$data.wlanSettings.wlanSettingTable.wlan0.clientSeparation disableCondition="5==$apMode"}
	{/if}
<!--@@@CLIENT_SEPARATIONEND@@@-->
	{if $config.MAXSTATION_FEATURE.status}
											{assign var="maxStationCount" value=$config.MAXSTATION.count}
											{input_row label="Max. Wireless Clients" id="maxWirelessClients0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.maxWirelessClients type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.maxWirelessClients size="4" maxlength="3" validate="Numericality, (( minimum:1, maximum: $maxStationCount, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
											{ip_field label="&nbsp;" id="dbMaxWirelessClients0" name="dbMaxWirelessClients0" type="hidden" value=$data.wlanSettings.wlanSettingTable.wlan0.maxWirelessClients}
											{ip_field label="&nbsp;" id="dbApMode0" name="dbApMode0" type="hidden" value=$data.wlanSettings.wlanSettingTable.wlan0.apMode}											
	{/if}
										{if $config.BAND_STEERING.status}
											{if ($data.radioStatus0 eq 1) && ($data.radioStatus1 eq 1)}	
											{assign var="BSstatus" value=$data.wlanSettings.wlanSettingTable.wlan0.bandSteeringStatus}
											{input_row label="Band Steering to 5GHz" id="BandSteering" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.bandSteeringStatus type="radio" options="1-Enable,0-Disable" value=$BSstatus onclick="toggleBandSteering(this.value);" selectCondition="==$BSstatus"}
											{input_row label="Rssi Threshold 2.4GHz" id="RssiThreshold24GHz" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.rssi24GHz type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.rssi24GHz size="4" maxlength="3" validate="Numericality, (( minimum:0, maximum: 255, onlyInteger: true ))" disableCondition="1!=$BSstatus"}
											{input_row label="Rssi Threshold 5GHz" id="RssiThreshold5GHz" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.rssi50GHz disableCondition="0!=$BSstatus"  type="text" value=$data.wlanSettings.wlanSettingTable.wlan0.rssi50GHz size="4" maxlength="3" validate="Numericality, (( minimum:0, maximum: 255, onlyInteger: true ))" }
											{/if}
											{/if}
									</table>
								</div>
{/if}
<!--@@@FIVEGHZSTART@@@-->
{if $config.FIVEGHZ.status}
								<div  class="BlockContent" id="wlan2">
									<table class="BlockContent Trans" id="table_wlan2">
											{input_row label="RTS Threshold (0-2347)" id="iRTSThreshold1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.rtsThreshold type="text" value=$data.wlanSettings.wlanSettingTable.wlan1.rtsThreshold size="5" maxlength="4" validate="Numericality, (( minimum: 0, maximum: 2347, onlyInteger: true ))^Presence"}
											{input_row label="Fragmentation Length (256-2346)" id="fragmentThreshold1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.fragLength type="text" value=$data.wlanSettings.wlanSettingTable.wlan1.fragLength size="5" maxlength="4" validate="Numericality, (( minimum: 256, maximum: 2346, onlyInteger: true ))^Presence"}
                                            {if $config.WN604.status}
											{input_row label="Beacon Interval (20-100)" id="beaconInterval1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.beaconInterval type="text" value=$data.wlanSettings.wlanSettingTable.wlan1.beaconInterval size="5" maxlength="4" validate="Numericality, (( minimum: 20, maximum: 100, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
                                           	{else}
											{input_row label="Beacon Interval (100-1000)" id="beaconInterval1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.beaconInterval type="text" value=$data.wlanSettings.wlanSettingTable.wlan1.beaconInterval size="5" maxlength="4" validate="Numericality, (( minimum: 100, maximum: 1000, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
											{/if}
											{if $config.MODE11N.status AND ($data.activeMode eq '4' OR $data.activeMode1 eq '4')}
											{input_row label="Aggregation Length (1024-65535)" id="aggregationLength1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.ampduAggrLength type="text" value=$data.wlanSettings.wlanSettingTable.wlan1.ampduAggrLength size="6" maxlength="5" validate="Numericality, (( minimum: 1024, maximum: 65535, onlyInteger: true ))^Presence"}
											{assign var="ampdu" value=$data.wlanSettings.wlanSettingTable.wlan1.ampdu}
											{input_row label="AMPDU" id="idampdu1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.ampdu type="radio" options="1-Enable,0-Disable" value=$ampdu selectCondition="==$ampdu"}
											{assign var="rifsTransmission" value=$data.wlanSettings.wlanSettingTable.wlan1.rifsTransmission}
											{input_row label="RIFS Transmission" id="idrfis1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.rifsTransmission type="radio" options="1-Enable,0-Disable" value=$rifsTransmission selectCondition="==$rifsTransmission" disableCondition="5==$apMode"}
	{/if}
											{input_row label="DTIM Interval (1-255)" id="dtimInterval1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.dtimInterval type="text" value=$data.wlanSettings.wlanSettingTable.wlan1.dtimInterval size="5" maxlength="3" validate="Numericality, (( minimum:1, maximum: 255, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
	<!--@@@CLIENT_SEPARATIONSTART@@@-->
    {if $config.CLIENT_SEPARATION.status}
											{input_row label="Client Isolation" id="clientSeparation1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.clientSeparation type="select" options=$clientSeparationList selected=$data.wlanSettings.wlanSettingTable.wlan1.clientSeparation disableCondition="5==$apMode"}
	{/if}
<!--@@@CLIENT_SEPARATIONEND@@@-->
	{if $config.MAXSTATION_FEATURE.status}
											{assign var="maxStationCount" value=$config.MAXSTATION.count}
											{input_row label="Max. Wireless Clients" id="maxWirelessClients1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.maxWirelessClients type="text" value=$data.wlanSettings.wlanSettingTable.wlan1.maxWirelessClients size="4" maxlength="3" validate="Numericality, (( minimum:1, maximum: $maxStationCount, onlyInteger: true ))^Presence" disableCondition="5==$apMode"}
											{ip_field label="&nbsp;" id="dbMaxWirelessClients1" name="dbMaxWirelessClients1" type="hidden" value=$data.wlanSettings.wlanSettingTable.wlan1.maxWirelessClients}
	{/if}
									</table>
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

		{*disabling Band Steering*}
		{if (($BSstatus) == 0 ) }
		{literal}		
			$('RssiThreshold24GHz').disabled=true;		
			$('RssiThreshold5GHz').disabled=true;				
		$('inlineTab1').observe('click', disableRssi);
		function disableRssi()
		{
			$('RssiThreshold24GHz').disabled=true;		
			$('RssiThreshold5GHz').disabled=true;				
		}
		{/literal}

		{/if}

-->
</script>