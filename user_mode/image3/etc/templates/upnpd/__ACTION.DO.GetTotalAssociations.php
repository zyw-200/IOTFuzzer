<NewTotalAssociations><?
	$count=0;
	for ("/runtime/stats/wireless/client") { $count++; }
	echo $count;
?></NewTotalAssociation>