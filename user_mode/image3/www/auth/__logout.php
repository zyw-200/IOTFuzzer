<?
if(query("/runtime/web_debug")=="1"){echo "session id=".$SESSION_ID;}
if($SESSION_ID>0)
{
	unlink("/var/proc/web/session:".$SESSION_ID."/user/ac_auth");
	unlink("/var/proc/web/session:".$SESSION_ID."/mac");
	unlink("/var/proc/web/session:".$SESSION_ID."/ip");
	unlink("/var/proc/web/session:".$SESSION_ID."/port");
	unlink("/var/proc/web/session:".$SESSION_ID."/time");
	unlink("/var/proc/web/session:".$SESSION_ID."/uid");
}
if(query("/runtime/web_debug")=="1"){echo "ip=".fread("/var/proc/web/session:".$SESSION_ID."/ip");}
?>
