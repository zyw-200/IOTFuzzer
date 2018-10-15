<? /* vi: set sw=4 ts=4: */
anchor("/wlan/inf:2");
/* aclmode 0:disable, 1:allow all of the list, 2:deny all of the list */
echo "iwpriv ".$wlanif_a." maccmd 3\n";   // flush the ACL database.
$aclmode=query("acl/mode");
if      ($aclmode == 1)     { echo "iwpriv ".$wlanif_a." maccmd 1\n"; }
else if ($aclmode == 2)     { echo "iwpriv ".$wlanif_a." maccmd 2\n"; }
else                        { echo "iwpriv ".$wlanif_a." maccmd 0\n"; }
if ($aclmode == 1 || $aclmode == 2)
{
	for("/wlan/inf:2/acl/mac")
	{
		$mac=query("/wlan/inf:2/acl/mac:".$@);
		//echo "iwpriv ".$wlanif." acladdmac ".$mac."\n"; //wtpmacenable 2007-09-13 dennis
		echo "iwpriv ".$wlanif_a." addmac ".$mac."\n"; //wtpmacenable 2007-09-13 dennis	
	}
}
														   
?>
