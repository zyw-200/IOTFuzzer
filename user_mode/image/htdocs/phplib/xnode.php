<? /* vi: set sw=4 ts=4: */

/* XNODE_getvaluebynode() */
function XNODE_getvaluebynode($base, $node, $target, $idx)
{
	return query($base."/".$node.":".$idx."/".$target);
}

/* XNODE_getpathbytarget() */
function XNODE_getpathbytarget($base, $node, $target, $value, $create)
{
	foreach($base."/".$node)
	{
		if (query($target) == $value)
			return $base."/".$node.":".$InDeX;
	}
	
	if ($create > 0)
	{
		$i = query($base."/".$node."#")+1;
		$path = $base."/".$node.":".$i;
		set($path."/".$target, $value);
		return $path;
	}

	return "";
}

function XNODE_getschedule($base)
{
	$sch = query($base."/schedule");
	if ($sch != "")
	{
		$sptr = XNODE_getpathbytarget("/schedule", "entry", "uid", $sch, 0);
		if ($sptr != "") return $sptr;
	}
	return "";
}

function XNODE_getscheduledays($sch)
{
	$days = "";
	$comm = "";
	if (query($sch."/sun")=="1") { $ret=$ret.$comm."Sun"; $comm=","; }
	if (query($sch."/mon")=="1") { $ret=$ret.$comm."Mon"; $comm=","; }
	if (query($sch."/tue")=="1") { $ret=$ret.$comm."Tue"; $comm=","; }
	if (query($sch."/wed")=="1") { $ret=$ret.$comm."Wed"; $comm=","; }
	if (query($sch."/thu")=="1") { $ret=$ret.$comm."Thu"; $comm=","; }
	if (query($sch."/fri")=="1") { $ret=$ret.$comm."Fri"; $comm=","; }
	if (query($sch."/sat")=="1") { $ret=$ret.$comm."Sat"; $comm=","; }
	return $ret;
}

function XNODE_get_var($name)
{
	$path = XNODE_getpathbytarget("/runtime/services/globals", "var", "name", $name, 1);
	return query($path."/value");
}

function XNODE_set_var($name, $value)
{
	$path = XNODE_getpathbytarget("/runtime/services/globals", "var", "name", $name, 1);
	set($path."/value", $value);
}

function XNODE_del_var($name)
{
	$path = XNODE_getpathbytarget("/runtime/services/globals", "var", "name", $name, 1);
	$value = query($path."/value");
	del($path);
	return $value;
}

function XNODE_del_children($path, $child)
{
	$cnt = query($path."/".$child."#");
	while ($cnt > 0) { del($path."/".$child); $cnt--; }
}

function XNODE_add_entry($base, $uid_prefix)
{
	$seqno = query($base."/seqno");
	$count = query($base."/count");
	$max   = query($base."/max");
	if ($seqno == "" && $count == "")
	{
		$seqno = 1; 
		$count = 0;
	}
	if ($max != "" && $count >= $max) return "";

	$uid = $uid_prefix."-".$seqno;
	$seqno++;
	$count++;
	set($base."/seqno", $seqno);
	set($base."/count", $count);
	set($base."/entry:".$count."/uid", $uid);
	return $base."/entry:".$count;
}
function XNODE_del_entry($base, $index)
{
	$count = query($base."/count");
	$count--;
	del($base."/entry:".$index);
	set($base."/count",$count);
}
?>
