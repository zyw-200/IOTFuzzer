#!/bin/sh
echo [$0] ... > /dev/console
<?
/* insmod driver modules & common settings */
	$INSMOD="insmod /lib/modules/";
	echo $INSMOD."adf.ko\n";
	echo $INSMOD."asf.ko\n";
	echo $INSMOD."ath_hal.ko\n";
	echo $INSMOD."ath_rate_atheros.ko\n";
	echo $INSMOD."ath_dfs.ko\n";
	echo $INSMOD."ath_dev.ko\n";
	$countrycode= query("/runtime/nvram/countrycode");
        if ($countrycode!=""){//ELBOX_PROGS_PRIV_WLAN_OVRD_CTRL_PWR
                echo $INSMOD."umac.ko CountryCode=".$countrycode."\n";
        }else
        {
            echo $INSMOD."umac.ko CountryCode=840\n";
        }
	//echo $INSMOD."umac.ko\n";
//	echo "sleep 2\n";
	echo $INSMOD."ath_pktlog.ko\n";
?>
