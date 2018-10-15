<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
require("/etc/templates/upnpmsg.php");
$MMM="SOAP ACTION: ".$ServiceType."#".$ActionName."\n";
fwrite($UPNPMSG, $MMM);

fwrite ($ShellPath, "#!/bin/sh\n");

$ACTION_NAME	= query("/runtime/upnp/action_name");$SERVICE_TYPE	= query("/runtime/upnpdev/root:2/service:1/servicetype");

$SOAP_BODY="";
$errorCode=401;

if	(	$ACTION_NAME=="GetSSID"
	||	$ACTION_NAME=="GetBeaconType"
	||	$ACTION_NAME=="GetRadioMode"
	||	$ACTION_NAME=="GetChannelInfo"
	||	$ACTION_NAME=="GetDataTransmitRateInfo"
	||	$ACTION_NAME=="GetAutoRateFallBackMode"
	||	$ACTION_NAME=="GetPacketStatistics"
	||	$ACTION_NAME=="GetBasBeaconSecurityProperties"
	||	$ACTION_NAME=="GetWPABeaconSecurityProperties"	
	||	$ACTION_NAME=="GetTotalAssociations"
	)
{
	require($template_root."/upnpd/__ACTION.DO.".$ACTION_NAME.".php");
}

/* 200 OK */
if ($errorCode==200) { require($template_root."/upnpd/__ACTION_200.php"); exit; }

if	(	$ACTION_NAME=="SetSSID"
	||	$ACTION_NAME=="GetBSSID"
	||	$ACTION_NAME=="SetBeaconType"
	||	$ACTION_NAME=="SetRadioMode"
	||	$ACTION_NAME=="SetChannel"
	||	$ACTION_NAME=="SetDataTransmitRates"
	||	$ACTION_NAME=="SetAutoRateFallBackMode"
	||	$ACTION_NAME=="GetGenericAssociatedDeviceInfo"
	||	$ACTION_NAME=="GetSpecificAssociatedDeviceInfo"
	||	$ACTION_NAME=="SetAuthenticationServiceMode"
	||	$ACTION_NAME=="GetAuthenticationServiceMode"
	||	$ACTION_NAME=="SetSecurityKeys"
	||	$ACTION_NAME=="GetSecurityKeys"
	||	$ACTION_NAME=="SetDefaultWEPKeyIndex"
	||	$ACTION_NAME=="GetDefaultWEPKeyIndex"
	||	$ACTION_NAME=="SetBasBeaconSecurityProperties"
	||	$ACTION_NAME=="SetWPABeaconSecurityProperties"
	)
{
	$errorCode=501;
}
/* ERROR */
if		($errorCode==401) { $errorDescription="Invalid Action"; }
else if	($errorCode==402) { $errorDescription="Invalid Args"; }
else if	($errorCode==404) { $errorDescription="Invalid Var"; }
else if	($errorCode==501) { $errorDescription="Action Failed"; }
else if	($errorCode==605) { $errorDescription="AString Argument Too Long"; }
else if	($errorCode==704) { $errorDescription="ConnectionSetupFailed"; }
else if	($errorCode==705) { $errorDescription="ConnectionSetupInProgress"; }
else if	($errorCode==706) { $errorDescription="ConnectionNotConfigured"; }
else if	($errorCode==707) { $errorDescription="DisconnectInProgress"; }
else if	($errorCode==708) { $errorDescription="InvalidLayer2Address"; }
else if	($errorCode==709) { $errorDescription="InternetAccessDisabled"; }
else if	($errorCode==710) { $errorDescription="InvalidConnectionType"; }
else if	($errorCode==711) { $errorDescription="ConnectionAlreadyTerminated"; }
else if	($errorCode==713) { $errorDescription="InvalidIndex"; }
else if	($errorCode==714) { $errorDescription="NoSuchEntryInArray"; }
else if	($errorCode==715) { $errorDescription="WildCardNotPermittedInSrcIP"; }
else if	($errorCode==716) { $errorDescription="WildCardNotPermittedInExtPort"; }
else if	($errorCode==718) { $errorDescription="ConflictInMappingEntry"; }
else if	($errorCode==724) { $errorDescription="SamePortValuesRequired"; }
else if	($errorCode==725) { $errorDescription="OnlyPermanentLeasesSupported"; }
else if	($errorCode==726) { $errorDescription="RemoteHostOnlySupportsWildcard"; }
else if	($errorCode==727) { $errorDescription="ExternalPortOnlySupportsWildcard"; }
else if	($errorCode==728) { $errorDescription="InvalidChannel"; }
else if	($errorCode==729) { $errorDescription="InvalidMACAddress"; }
else if	($errorCode==730) { $errorDescription="InvalidDataTransmissionRates"; }
else if	($errorCode==731) { $errorDescription="InvalidWEPKey"; }
else if	($errorCode==732) { $errorDescription="NoWEPKeyIsSet"; }
else if	($errorCode==733) { $errorDescription="NoPSKKeyIsSet"; }
else if	($errorCode==734) { $errorDescription="NoEAPServer"; }
require($template_root."/upnpd/__ACTION_500.php");
?>