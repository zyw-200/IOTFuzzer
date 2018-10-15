<? /* vi: set sw=4 ts=4: */
echo $G_TAG_SCRIPT_START."\n";
?>
var d = new Date();
var date = (d.getMonth()+1)+"/"+d.getDate()+"/"+d.getFullYear();
var time = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
var str="<?=$NEXT_LINK?>.xgi?";
str+="setPath=/runtime/time/";
str+="&date="+date+"&time="+time;
str+="&endSetPath=1";
str+="&exeshell=submit TIME";
self.location.href=str;
<?
echo $G_TAG_SCRIPT_END."\n";
?>
