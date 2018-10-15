<? /* vi: set sw=4 ts=4: */
anchor("/wlan/inf:1");
/* aclmode 0:disable, 1:allow all of the list, 2:deny all of the list */
echo $IWPRIV." maccmd 3\n";   // flush the ACL database.
$aclmode=query("acl/mode");
if      ($aclmode == 1)     { echo $IWPRIV." maccmd 1\n"; }
else if ($aclmode == 2)     { echo $IWPRIV." maccmd 2\n"; }
else                        { echo $IWPRIV." maccmd 0\n"; }
if ($aclmode == 1 || $aclmode == 2)
{
	for("/wlan/inf:1/acl/mac")
	{
		$mac=query("/wlan/inf:1/acl/mac:".$@);
		echo $IWPRIV." addmac ".$mac."\n"; //wtpmacenable 2007-09-13 dennis	
	}
}
														   
?>
