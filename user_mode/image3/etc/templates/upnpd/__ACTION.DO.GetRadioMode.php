<NewRadioEnabled><?
	$RadioEnabled=0;
	if(query("/wireless/enable"))$RadioEnabled=1;
	echo $RadioEnabled;
?></NewRadioEnabled>