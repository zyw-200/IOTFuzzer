<?
if ($ACTION=="STARTTODOWNLOADFILE")
{
	mov("/device/account","/runtime/device");
}
else if ($ACTION=="ENDTODOWNLOADFILE")
{
	mov("/runtime/device/account","/device");
}
?>
