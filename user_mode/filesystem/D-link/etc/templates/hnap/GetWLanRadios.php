HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<? 
echo "<"."?";?>xml version="1.0" encoding="utf-8"<? echo "?".">";
include "/htdocs/phplib/xnode.php"; 
$path_phyinf_wlan1 = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);
$path_phyinf_wlan2 = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-2", 0);
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanRadiosResponse xmlns="http://purenetworks.com/HNAP1/">
		<GetWLanRadiosResult>OK</GetWLanRadiosResult>
		<RadioInfos>
			<RadioInfo>
			<RadioID>2.4GHZ</RadioID>
			<Frequency>2</Frequency>
			<SupportedModes>
				<string>802.11n</string>
				<string>802.11bgn</string>
				<string>802.11gb</string>
			</SupportedModes>
			<Channels>
			<?
			$inx = 1;
			while( $inx <= 11 )
			{	echo "<int>".$inx."</int>\n"; $inx++; }
			?>
			</Channels>
			<WideChannels>
			<?
			$bandwidth = query($path_phyinf_wlan1."/media/dot11n/bandwidth");     			
			if ($bandWidth != "20")
			{
				$startChannel = 3;
				while( $startChannel <= 9 )
				{
					echo "<WideChannel>\n";
					echo "	<Channel>".$startChannel."</Channel>\n";
					echo "	<SecondaryChannels>\n";
					$secondaryChnl = $startChannel - 2;
					echo "		<int>".$secondaryChnl."</int>\n";	
					$secondaryChnl = $startChannel + 2;
					echo "		<int>".$secondaryChnl."</int>\n";
					echo "	</SecondaryChannels>\n";
					echo "</WideChannel>\n";	
					$startChannel++;	
				}
			}
			?>
			</WideChannels>
			<SupportedSecurity>
				<SecurityInfo>
					<SecurityType>WEP-OPEN</SecurityType>
					<Encryptions>
						<string>WEP-64</string>
						<string>WEP-128</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WEP-SHARED</SecurityType>
					<Encryptions>
						<string>WEP-64</string>
						<string>WEP-128</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA2-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA2-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPAORWPA2-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPAORWPA2-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
			</SupportedSecurity>
			</RadioInfo>
			<RadioInfo>
			<RadioID>5GHZ</RadioID>
			<Frequency>5</Frequency>
			<SupportedModes>
				<string>802.11a</string>
				<string>802.11n</string>
				<string>802.11an</string>
			</SupportedModes>
			<Channels>
			<?
			$inx = 36;
			while( $inx <= 64 )
			{	echo "<int>".$inx."</int>\n"; $inx=$inx+4; }
			$inx = 149;
			while( $inx <= 165 )
			{	echo "<int>".$inx."</int>\n"; $inx=$inx+4; }
			?>
			</Channels>
			<WideChannels>
			<?
			$bandwidth = query($path_phyinf_wlan2."/media/dot11n/bandwidth");     			
			if ($bandWidth != "20")
			{
				$startChannel = 44;
				while( $startChannel <= 56 )
				{
					echo "<WideChannel>\n";
					echo "	<Channel>".$startChannel."</Channel>\n";
					echo "	<SecondaryChannels>\n";
					$secondaryChnl = $startChannel - 8;
					echo "		<int>".$secondaryChnl."</int>\n";	
					$secondaryChnl = $startChannel + 8;
					echo "		<int>".$secondaryChnl."</int>\n";
					echo "	</SecondaryChannels>\n";
					echo "</WideChannel>\n";	
					$startChannel=$startChannel+4;	
				}
				echo "<WideChannel>\n";
		    	echo "	<Channel>157</Channel>\n";
				echo "	<SecondaryChannels>\n";
				echo "		<int>149</int>\n";	
				echo "		<int>165</int>\n";
				echo "	</SecondaryChannels>\n";
				echo "</WideChannel>\n";
			}
			?>
			</WideChannels>
			<SupportedSecurity>
				<SecurityInfo>
					<SecurityType>WEP-OPEN</SecurityType>
					<Encryptions>
						<string>WEP-64</string>
						<string>WEP-128</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WEP-SHARED</SecurityType>
					<Encryptions>
						<string>WEP-64</string>
						<string>WEP-128</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA2-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPA2-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPAORWPA2-PSK</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
				<SecurityInfo>
					<SecurityType>WPAORWPA2-RADIUS</SecurityType>
					<Encryptions>
						<string>TKIP</string>
						<string>AES</string>
						<string>TKIPORAES</string>
					</Encryptions>
				</SecurityInfo>
			</SupportedSecurity>
			</RadioInfo>
		</RadioInfos>
    </GetWLanRadiosResponse>
  </soap:Body>
</soap:Envelope>
