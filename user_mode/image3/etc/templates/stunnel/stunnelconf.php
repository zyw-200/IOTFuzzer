; Sample stunnel configuration file by Michal Trojnara 2002-2006
; Some options used here may not be adequate for your particular configuration

; Certificate/key is needed in server mode and optional in client mode
; The default certificate is provided only for testing and should not
; be used in a production environment
pid=/var/run/stunnel.pid

<?
anchor("/sys/stunnel");
$single_cert = query("single_cert");
$path        = query("path");
$certname    = query("certname");
$keyname     = query("keyname");

if($single_cert == 1)
{
	echo ";Single Certificat.\n";
	echo "cert = ".$path."/".$certname."\n";
}
else
{
	echo ";Certificate and Private Key.\n";
	echo "cert = ".$path."/".$certname."\n";
	echo "key = ".$path."/".$keyname."\n";
}
?>

; Some performance tunings
socket = l:TCP_NODELAY=1
socket = r:TCP_NODELAY=1

; Workaround for Eudora bug
;options = DONT_INSERT_EMPTY_FRAGMENTS

;setuid = root
;setgid = root

; Authentication stuff
;verify = 2
; Don't forget to c_rehash CApath
;CApath = certs
; It's often easier to use CAfile
;CAfile = certs.pem
; Don't forget to c_rehash CRLpath
;CRLpath = crls
; Alternatively you can use CRLfile
;CRLfile = crls.pem

; Some debugging stuff useful for troubleshooting
;debug = 7
debug = syslog.5
;output = /var/log/stunnel.log
;output = stderr
;output = /www/stunnel.log

; Use it for client mode
client = no

session = 300

; Service-level configuration

;[pop3s]
;accept  = 995
;connect = 110

;[imaps]
;accept  = 993
;connect = 143

;[ssmtp]
;accept  = 465
;connect = 25

;anchor("/wan/rg/inf:1/static");
[https]
accept  = 0.0.0.0:443
connect = 0.0.0.0:80
;TIMEOUTclose = 0

; vim:ft=dosini
