#!/bin/sh
#

ENC_FILE="/tmp/enc_dec.txt"
PRODUCT=`printmd | grep -i ProductId | cut -d ' ' -f 3`

if [ ${PRODUCT} == "WNDAP350" ] || [ ${PRODUCT} == "wndap350" ]; then

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:basicSettings:adminPasswd > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:basicSettings:adminPasswd ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt		
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
        /etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Key > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Key ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Secret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Secret ${ENC_FILE}
	rm -f ${ENC_FILE}

elif [ ${PRODUCT} == "WNDAP360" ] || [ ${PRODUCT} == "wndap360" ]; then

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:basicSettings:adminPasswd > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:basicSettings:adminPasswd ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Key > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Key ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Secret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Secret ${ENC_FILE}
	rm -f ${ENC_FILE}

elif [ ${PRODUCT} == "WNDAP660" ] || [ ${PRODUCT} == "wndap660" ]; then

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:basicSettings:adminPasswd > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:basicSettings:adminPasswd ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

 	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user1pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user1pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user2pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user2pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user3pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user3pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user4pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user4pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:idsIpsMailSettings:srcMailPassword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:idsIpsMailSettings:srcMailPassword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Key > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Key ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Secret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Secret ${ENC_FILE}
	rm -f ${ENC_FILE}

elif [ ${PRODUCT} == "WNDAP620" ] || [ ${PRODUCT} == "wndap620" ]; then

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:basicSettings:adminPasswd > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:basicSettings:adminPasswd ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

 	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan1:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan1:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:Ipv6:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:Ipv6:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user1pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user1pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user2pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user2pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user3pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user3pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:userSettings:user4pword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:userSettings:user4pword ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:idsIpsMailSettings:srcMailPassword > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:idsIpsMailSettings:srcMailPassword ${ENC_FILE}
	rm -f ${ENC_FILE}

elif [ ${PRODUCT} == "WNAP320" ] || [ ${PRODUCT} == "wnap320" ]; then

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:basicSettings:adminPasswd > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:basicSettings:adminPasswd ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Key > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Key ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Secret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Secret ${ENC_FILE}
	rm -f ${ENC_FILE}

elif [ ${PRODUCT} == "WNAP210V2" ] || [ ${PRODUCT} == "WNAP210v2" ] || [ ${PRODUCT} == "WNAP210" ] || [ ${PRODUCT} == "wnap210V2" ] || [ ${PRODUCT} == "wnap210v2" ] || [ ${PRODUCT} == "wnap210" ]; then

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:basicSettings:adminPasswd > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:basicSettings:adminPasswd ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap1:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap2:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap3:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap4:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap5:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap6:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap7:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Key > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Key ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:dumpApConfigLogSettings:s3Secret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:dumpApConfigLogSettings:s3Secret ${ENC_FILE}
	rm -f ${ENC_FILE}

elif [ ${PRODUCT} == "WN604" ] || [ ${PRODUCT} == "wn604" ] || [ ${PRODUCT} == "WN604-1KMUKS" ] || [ ${PRODUCT} == "wn604-1kmuks" ]; then

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:basicSettings:adminPasswd > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:basicSettings:adminPasswd ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:staSettings:staSettingTable:wlan0:sta0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey1 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey2 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey3 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepKey4 ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wpaPsk > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:vapSettings:vapSettingTable:wlan0:vap0:wpaPsk ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds0:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds1:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds2:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey> ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsPresharedkey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepKey ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:wdsSettings:wdsSettingTable:wlan0:wds3:wdsWepPassPhrase ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:priRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:priRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:authinfo:sndRadSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:authinfo:sndRadSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:priAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:priAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

	/etc/translator_scripts/get_file_space_rm_newline /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret > ${ENC_FILE}
	/usr/local/bin/db_enc decrypt
	/etc/translator_scripts/set_file_space_from_file /var/config system:info802dot1x:accntinfo:sndAcntSharedSecret ${ENC_FILE}
	rm -f ${ENC_FILE}

fi

