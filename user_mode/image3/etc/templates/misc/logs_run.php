  <?
  /* vi: set sw=4 ts=4: */
  require("/etc/templates/troot.php");
  
  $klog_pid="/var/run/klogd.pid";
  $lanif=query("/runtime/layout/lanif");
  $wanif=query("/runtime/wan/inf:1/interface");
  if ($wanif=="") { $wanif=query("/runtime/layout/wanif"); }
  /*syslogd_2007_01_23 , Jordan start */
  $loglevel=query("/security/log/loglevel");
  $first_init=query("/runtime/log/first_init");
  $First_init_RemoLog=query("/runtime/log/Remote_log_init");
  $fw_ver=query("/runtime/sys/info/firmwareversion");
  /*syslogd_2007_01_23 , Jordan end*/
  $remoteInfo=query("/security/log/remoteInfo");/*RemoteLog_2007_02_15, Jordan */
  $smtpInfo=query("/security/log/smtpInfo");/*smtp_2007_02_16, Jordan*/
  if ($klogd_only != 1)
  {
  	$syslog_pid="/var/run/syslogd.pid";
  
  	echo "if [ -f ".$syslog_pid." ]; then\n";
  	echo "	PID=`cat ".$syslog_pid."`\n";
  	echo "	if [ $PID != 0 ]; then\n";
  	echo "		kill $PID > /dev/console 2>&1\n";
  	echo "	fi\n";
  	echo "	rm -f ".$syslog_pid."\n";
  	echo "fi\n";
  
  /*schedule_smtp.2008_03_26.allen*/
  	$timer_email_pid="/var/run/timer_email.pid";
  	
  	echo "if [ -f ".$timer_email_pid." ]; then\n";
  	echo "	PID=`cat ".$timer_email_pid."`\n";
  	echo "	if [ $PID != 0 ]; then\n";
  	echo "		kill $PID > /dev/console 2>&1\n";
  	echo "	fi\n";
  	echo "	rm -f ".$timer_email_pid."\n";
  	echo "fi\n";
  	
	$smtpsindex=query("/sys/log/smtpindex");
	$smtps=query("/sys/log/smtp/index:".$smtpsindex."/mailserver");
	$fromemail=query("/sys/log/smtp/index:".$smtpsindex."/fromemail");
	$toemail=query("/sys/log/smtp/index:".$smtpsindex."/toemail");
  	$hostname=query("/sys/hostname");
  	$logserver=query("/sys/log/logserver");/*syslogd_2007_01_23 , Jordan */
  	$eulogserver=query("/sys/log/eulogserver");/*syslogd_2014_11_12 , Ken*/
  	$opts="";
  /*schedule_smtp.2008_03_26.allen*/
  	$timer_opts="";
  	$timer=query("/sys/log/timemail");
  /* auth ESMTP_2008_04_29,allen */
  //	$port = "25" ;
  	$password = query("/sys/log/smtp/index:".$smtpsindex."/pass1");
  	$username = query("/sys/log/smtp/index:".$smtpsindex."/username");
  	
  	
  	anchor("/security/log");
  	if (query("systeminfo")==1)		{ $opts=$opts." -F sysact"; }
  	if (query("debuginfo")==1)		{ $opts=$opts." -F debug"; }
  	if (query("attackinfo")==1)		{ $opts=$opts." -F attack"; }
  	if (query("droppacketinfo")==1)	{ $opts=$opts." -F drop"; }
  	if (query("noticeinfo")==1)		{ $opts=$opts." -F notice"; }
  /*syslogd_2007_02_02 , Jordan start */
  	if (query("WirelessInfo")==1)		{ $opts=$opts." -F wireless"; }
  	if (query("apsystemInfo")==1)		{ $opts=$opts." -F apsysact"; }
  	if (query("apnoticeInfo")==1)		{ $opts=$opts." -F apnotice"; }
          if ($loglevel != "") { $opts=$opts." -l ".$loglevel; }	/*klogd_2007_01_30 , Jordan */
  /*syslogd_2007_02_02 , Jordan end*/    
  /*syslogd_2007_01_23 , Jordan start */
  	if ($remoteInfo == 1)/*RemoteLog_2007_02_15, Jordan */
  	{
  		if ($first_init != 1)/* syslog_2007_04_09 , Jordan */
  		/*if ($withremotelog == 1)*//*syslogd_2007_02_09*//* syslog_2007_04_09 , Jordan*/
  		{
  			if ($logserver != "") 
  			{ 
  				$opts=$opts."  -R ".$logserver; 
        				if($logport != "")
        		 		{
          		 		$opts=$opts.":".$logport;
        			 	}
      			}
				if ($eulogserver != "")  /*EU directive server log*//*syslogd_2014_11_12 , Ken*/
				{
 					$opts=$opts."  -F eulog";
					$opts=$opts."  -E ".$eulogserver;
				}
  		}
  	}
  /*syslogd_2007_01_23 , Jordan end*/
  	if ($smtpInfo == 1)/*smtp_2007_02_16, Jordan*/
  	{
  		/*syslogd_2008_03_03.allen*/
  		if($toemail == "")
  		{
  			$toemail=$fromemail;
  		}
  		if ($smtps != "" && $fromemail != "")
  	{
		//$opts=$opts." -t /var/log/messages".
		//		" -m -S \"".$smtps."\" -a \"".$fromemail."\" -r \"".$toemail."\" -H \"".$hostname."\"";
		$opts=$opts." -t /var/log/messages".
				" -m -S \"".$smtps."\" -a \"".$fromemail."\" -r \"".$toemail."\" -H \"".$hostname."\" -P \"".$password."\" -u \"".$username."\"";
		
		/*schedule_smtp.2008_03_26.allen*/
		$timer_opts=" -t /var/log/smtpmsg".
				" -S \"".$smtps."\" -a \"".$fromemail."\" -r \"".$toemail."\" -H \"".$hostname.
				"\" -T \"".$timer."\" -P \"".$password."\" -u \"".$username."\"";
	}
  	}
  	if ($first_init == 1) { $opts=$opts." -b ".$fw_ver; }	/*syslog_2007_02_16 , Jordan*/
  	if ($First_init_RemoLog == 1) { $opts=$opts." -i"; }
  	if ($opts != "")
  	{
  		echo "syslogd ".$opts." &\n";
  		echo "echo $! > ".$syslog_pid."\n";
  	}
  	/*schedule_smtp.2008_03_26.allen*/
  	if ($timer_opts != "")
  	{
  		echo "schedule_smtp".$timer_opts." &\n";
  		echo "echo $! > ".$timer_email_pid."\n";	
  	}
  	
  }
  
  echo "if [ -f ".$klog_pid." ]; then\n";
  echo "	PID=`cat ".$klog_pid."`\n";
  echo "	if [ $PID != 0 ]; then\n";
  echo "		kill $PID > /dev/console 2>&1\n";
  echo "	fi\n";
  echo "	rm -f ".$klog_pid."\n";
  echo "fi\n";
  /*klogd_2007_01_30 , Jordan start */
  $opts="";
  /*syslog_2007_02_16 , Jordan marked*/
  /*if ($first_init == 1) { $opts=$opts." -b ".$fw_ver; }*//*ap71_syslogd_20061125*//*Added by Erick*/
  
  if ($loglevel != "")
  {echo "klogd -c ".$loglevel." ".$opts." &\n";}
  else
  {echo "klogd -c 5 ".$opts." &\n";}
  echo "echo $! > ".$klog_pid."\n";
  echo "rgdb -s /security/log/first_init 0\n";
  /*
  echo "klogd -l ".$lanif." -w ".$wanif." &\n";
  echo "echo $! > ".$klog_pid."\n";
  */
  /*klogd_2007_01_30 , Jordan end*/
  ?>
