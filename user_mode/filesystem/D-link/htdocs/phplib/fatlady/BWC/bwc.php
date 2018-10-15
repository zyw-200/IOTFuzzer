<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/inf.php";
include "/htdocs/phplib/inet.php";


function set_result($result, $node, $message)
{
    $_GLOBALS["FATLADY_result"] = $result;
    $_GLOBALS["FATLADY_node"]   = $node;
    $_GLOBALS["FATLADY_message"]= $message;
}

function check_bwcqd($bwcqd, $total_bd)
{

	$bd = query($bwcqd."/bandwidth");
	if( $bd == "") return "OK";
	if( isdigit($bd) == 0 )
	{
		set_result("FAILED", $bwcqd."/bandwidth", i18n("Invalid Bandwidth!."));
		return "FAILED";
	}
	if( $bd > $total_bd || $bd < 0 )
	{
		set_result("FAILED", $bwcqd."/bandwidth", i18n("Invalid Bandwidth!!."));
		return "FAILED";
	}
	
	return "OK";

}

function check_bwcf($bwcf)
{

    $lanStr = "LAN-1";
    $lanIp = INF_getcfgipaddr($lanStr);
    $lanMask = INF_getcfgmask($lanStr);

    $startip = query($bwcf."/ipv4/start");
    $endip = query($bwcf."/ipv4/end");
    $path = $bwcf."/entry:".$InDex;

    if($startip == "" || $startip == $lanIp)
    {
        set_result("FAILED", $path."/start", i18n("Invalid start IP."));
        return "FAILED";
    }
    if(INET_validv4addr($startip) == 0)
    {
        set_result("FAILED", $path."/start", i18n("Invalid start IP."));
        return "FAILED";
    }
    if(INET_validv4host($startip, $lanMask) == 0)
    {
        set_result("FAILED", $path."/start", i18n("Invalid start IP."));
        return "FAILED";
    }
    if(INET_validv4network($startip, $lanIp, $lanMask) == 0)
    {
        set_result("FAILED", $path."/start", i18n("Invalid start IP."));
        return "FAILED";
    }
    if($endip == "" || $endip == $lanIp)
    {
        set_result("FAILED", $path."/end", i18n("Invalid end IP."));
        return "FAILED";
    }
    if(INET_validv4addr($endip) == 0)
    {
        set_result("FAILED", $path."/end", i18n("Invalid end IP."));
        return "FAILED";
    }
    if(INET_validv4host($endip, $lanMask) == 0)
    {
        set_result("FAILED", $path."/end", i18n("Invalid end IP."));
        return "FAILED";
    }
    if(INET_validv4network($endip, $lanIp, $lanMask) == 0)
    {
        set_result("FAILED", $path."/end", i18n("Invalid end IP."));
        return "FAILED";
    }


    return "OK";
}

function check_bwc($prefix)
{
	foreach($prefix."/entry")
	{
		$bwc_index = $InDeX;

		$auto_bd = query("autobandwidth");
        TRACE_debug("FATLADY: BWC: ".$prefix."/entry:".$bwc_index."/autobandwidth=".$auto_bd);
        if( $auto_bd!="" && $auto_bd!="0" && $auto_bd!="1")
        {
             set_result("FAILED", $prefix."/entry:".$bwc_index."/autobandwidth", i18n("The input autobandwidth is not valid."));
             return "FAILED";
        }
        $total_bd = query("bandwidth");
        TRACE_debug("FATLADY: BWC: ".$prefix."/entry:".$bwc_index."/bandwidth=".$total_bd);
        if( isdigit($total_bd) == "0")
        {
             set_result("FAILED", $prefix."/entry:".$bwc_index."/bandwidth", i18n("The input bandwidth is not digital."));
             return "FAILED";
        }
        $flag = query("flag");
        TRACE_debug("FATLADY: BWC: ".$prefix."/entry:".$bwc_index."/flag=".$flag);
        if( $flag!="BC" && $flag!="TC" && $flag!="AQC")
		{
             set_result("FAILED", $prefix."/entry:".$bwc_index."/flag", i18n("The input flag is not valid."));
             return "FAILED";
        }
        $bwc_en = query("enable");
        TRACE_debug("FATLADY: BWC: ".$prefix."/entry:".$bwc_index."/enable=".$bwc_en);
        if( $bwc_en!="" && $bwc_en!="0" && $bwc_en!="1")
        {
             set_result("FAILED", $prefix."/entry:".$bwc_index."/enable", i18n("The input bwc enable is not valid."));
             return "FAILED";
        }

        foreach($prefix."/entry:".$bwc_index."/rules/entry")
        {
			$en = query("enable");
            if( $en!="" && $en!="0" && $en!="1")
            {
	             set_result("FAILED", $prefix."/entry:".$bwc_index."/rules/entry:".$InDeX."/enable", i18n("The input rules enable is not digital."));
    	         return "FAILED";
			}

            $bwcf_uid = query("bwcf");
            $bwcf = XNODE_getpathbytarget($prefix."/bwc/bwcf", "entry", "uid", $bwcf_uid, 0);
            if($bwcf == "")
            {
                set_result("FAILED", $prefix."/bwc/bwcf", "BWCF UID ".$bwcf_uid." mismatch");
                return "FAILED";
            }
            if( check_bwcf($bwcf) == "FAILED" )
			{
				return "FAILED";
			}

            $bwcqd_uid = query("bwcqd");
            $bwcqd = XNODE_getpathbytarget($prefix."/bwc/bwcqd", "entry", "uid", $bwcqd_uid, 0);
            if($bwcqd == "")
            {
                set_result("FAILED", $prefix."/bwc/bwcqd", "BWCQD UID ".$bwcqd_uid." mismatch");
                return "FAILED";
            }

            if( check_bwcqd($bwcqd, $total_bd) == "FAILED" )
			{
				return "FAILED";
			}
        }
		$InDeX = $bwc_index;
	}
 
    return "OK";

}

function fatlady_bwc($prefix)
{
    TRACE_debug("FATLADY: BWC: FATLADY_prefix=".$prefix);

    if(check_bwc($prefix) == "FAILED")
    {
         return;
    }

    set($prefix."/valid", "1");
    set_result("OK", "", "");
    return;
}

?>
