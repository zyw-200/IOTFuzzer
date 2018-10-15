<module>
	<service><?=$GETCFG_SVC?></service>
	<wifi>
<?		echo dump(2, "/wifi");
?>	</wifi>
<?
	foreach("/phyinf")
	{
		if (query("type")== "wifi")
		{
			echo '\t<phyinf>\n';
			echo dump(2, "/phyinf:".$InDeX);
			echo '\t</phyinf>\n';
		}
	}
?></module>
