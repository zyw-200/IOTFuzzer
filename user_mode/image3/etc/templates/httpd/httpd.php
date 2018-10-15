Umask 026
<?
require("/etc/templates/troot.php");
$SESSION_TIMEOUT=query("/sys/sessiontimeout");
$SESSION_NUM	=query("/proc/web/sessionum");
$HTTP_ALLOW	=query("/security/firewall/httpallow");
$REMOTE_PORT	=query("/security/firewall/httpRemotePort");
$WAN_IF		=query("/runtime/wan/inf:1/interface");
$LAN_IF		=query("/runtime/layout/lanif");
$IS_IPV6	=query("/inet/entry:1/ipv6/valid"); //add by log_luo 20100612
$UPNP_PORT	= 49152;

/* For UPNP */
$model	= query("/sys/modelname");
$ver	= query("/runtime/sys/info/firmwareversion");
set("/runtime/upnpdev/port",        $UPNP_PORT);
set("/runtime/upnpdev/server",      "Linux, UPnP/1.0, ".$model." Ver ".$ver);
set("/runtime/upnpdev/maxage",      1800);

/* generate this file for WEB server */
fwrite("/var/proc/web/sessiontimeout", $SESSION_TIMEOUT);
?>
Tuning {
	NumConnections 15
	BufSize 12288
	InputBufSize 4096
	ScriptBufSize 4096
	NumHeaders 100
	Timeout 60
	ScriptTimeout 60
	MaxUploadSize 15859712
}

PIDFile /var/run/httpd.pid
LogGMT On

Control {
	Types {
		text/html { html htm }
		text/xml { xml }
                text/plain { txt }
		image/gif { gif }
		image/jpeg { jpg }
		text/css { css }
		application/ocstream { * }
	}
	Specials {
		Dump { /dump }
		CGI { cgi }
		Imagemap { map }
		Redirect { url }
		Internal { _int }
	}
	External {
		/sbin/atp { php txt}
                /sbin/xgi { xgi bin dcf log}
                /sbin/sgi { sgi }
	}
        IndexNames { index.html }
}

Server {
<?
//$2590_ipv6 = fread("/etc/config/model_ver");
//if($2590_ipv6=="it_is_2590_ip_v6_version")
if($IS_IPV6==1)//add by log_luo 20100612
{
	echo "	Family inet6\n";
	echo "	Port 80	\n";	
}
?>
	Virtual {
		AnyHost
                Control {

                        SessionControl On
                        SessionIdleTime 600
                        SessionMax 8
                        SessionFilter { php xgi _int cgi }
                        ErrorFWUploadFile       /www/sys/wrongImg.htm
                        ErrorCfgUploadFile      /www/sys/wrongConf.htm
                        InfoFWRestartFile       /www/sys/restart.htm
                        InfoCfgRestartFile      /www/sys/restart2.htm
                        Alias /
                        Location /www
                        External {
                                /sbin/atp { xml }
                        }

                }
                Control {
                        Alias /var
                        Location /var/www
                }

<?
		/* The config for HTTP server on LAN side. */
		require($template_root."/httpd/httpd_server.php");
?>
		Control {
			Alias /HNAP1
			Location /www/HNAP1
			External {
				/usr/sbin/hnap { hnap }
			}
			IndexNames { index.hnap }
		}
	}
}

<?

/* The config section for UPnP support in HTTPD. */
if (query("/function/httpd_upnp")==1) { require($template_root."/httpd/httpd_upnp.php"); }

/* The config for HTTP server on WAN side. */
if ($WAN_IF!="" && $HTTP_ALLOW==1)
{
	echo "Server {\n";
	echo "	Interface ".$WAN_IF."\n";
	echo "	Port ".$REMOTE_PORT."\n";
	echo "	Virtual {\n";
	echo "		AnyHost\n";

	require($template_root."/httpd/httpd_server.php");

	echo "	}\n";
	echo "}\n";
}
?>
