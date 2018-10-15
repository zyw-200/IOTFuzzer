<?
include "/htdocs/phplib/trace.php";

fwrite("w", $START, "#!/bin/sh\n");
fwrite("a", $START, "exit 0\n");

fwrite("w", $STOP, "#!/bin/sh\n");
fwrite("a", $STOP, "exit 0\n");

/* prepare the passwd file: /var/passwd */
$file = "/var/passwd";
fwrite("w", $file, "");

$cnt = query("/device/account/count");
$i = 0;
while ($i < $cnt)
{
	$i++;
	$name	= get("s", "/device/account/entry:".$i."/name");
	$pwd	= get("s", "/device/account/entry:".$i."/password");
	$grp	= get("s", "/device/account/entry:".$i."/group");
	TRACE_debug('SERVICE: DEVICE.ACCOUNT: Account['.$i.']: "'.$name.'" "'.$pwd.'" "'.$grp.'"');
	fwrite("a", $file, '"'.$name.'" "'.$pwd.'" "'.$grp.'"\n');
	fwrite("w", "/var/etc/hnapasswd", $name.":".$pwd."\n");// Generate the password for HNAP Joseph_Chao
}

/* prepare the session config file: /var/session/sesscfg */
$file = "/var/session/sesscfg";
anchor("/device/session");
fwrite("w", $file,
			"\"".query("timeout")       ."\" ".
			"\"".query("maxsession")    ."\" ".
			"\"".query("maxauthorized") ."\" ".
			"\"".query("captcha")       ."\"");
?>
