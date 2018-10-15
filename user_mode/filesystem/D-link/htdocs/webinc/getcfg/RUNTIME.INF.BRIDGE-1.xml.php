<module>
	<service><?=$GETCFG_SVC?></service>
	<runtime>
<?
include "/htdocs/phplib/xnode.php";
$inf = cut($GETCFG_SVC,2,".");
$path = XNODE_getpathbytarget("/runtime", "inf", "uid", $inf, 0);
if ($path!="")
{
	echo "\t\t<inf>\n";
	echo dump(3, $path);
	echo "\t\t</inf>\n";
}
?>	</runtime>
	<SETCFG>ignore</SETCFG>
	<ACTIVATE>ignore</ACTIVATE>
</module>
