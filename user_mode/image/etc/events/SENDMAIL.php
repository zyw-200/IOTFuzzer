<?
include "/htdocs/phplib/dumplog.php";
include "/htdocs/phplib/trace.php";

if ($ACTION == "LOGFULL")
{
	$logfile = "/var/run/logfull.log";
	$type = query("/runtime/logfull/type");
	$path = "/runtime/logfull/".$type;

	if($type == "sysact") $str = "System";
	else if($type == "attack") $str = "Firewall and Security";
	else if($type == "drop") $str = "Router Status";	
	
	fwrite("w", $logfile, "\n[".$str."]\n");
	fwrite("a", $logfile, "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n");
	DUMPLOG_append_to_file($logfile, $path);
	del($path);
	
	echo 'logger -p 192.1 "Log of ['.$str.'] is full."\n';
}
else
{
	/*write rg.log*/
	$logfile = "/var/run/rg.log";
	DUMPLOG_all_to_file($logfile);
}

$from         = query("/device/log/email/from");
$email_addr   = query("/device/log/email/to");
$mail_subject = get(s,"/device/log/email/subject");
$mail_server  = query("/device/log/email/smtp/server");
$mail_port    = "25";
$username  = query("/device/log/email/smtp/user");
$password  = query("/device/log/email/smtp/password");

if ($from == "" || $email_addr == "" || $mail_server == "")
{
	TRACE_error("sendmail: invalid args!!! from=[".$from."], to=[".$email_addr."], smtp server=[".$mail_server."]\n");
	return;
}
if ($mail_subject=="")$mail_subject = "RG log";
echo 'logger -p 192.1 "Sending the Log to '.$email_addr.'"\n';
/* static options */
echo 'sendmail'.
	 ' -s "'.$mail_subject.'"'.
	 ' -S '.$mail_server.
	 ' -P '.$mail_port.
	 ' -a '.$from.
	 ' -t '.$email_addr.
     ' -u '.$username.
     ' -p '.$password. 
	 ' -f '.$logfile.' &\n';
?>
