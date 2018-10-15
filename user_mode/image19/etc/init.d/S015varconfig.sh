#!/bin/sh
#
# Checking the database.
#
if [ ${MULTIPLE_SKU} = "yes" ]; then
	ncecho 'Multiple SKU supported.     '
	cecho green '[DONE]'
	ncecho 'Current SKU is              '
	if [ -e ${DEFAULT_CONFIG}-${PRODUCT_ID} ]; then
		${CP} -f ${DEFAULT_CONFIG}-${PRODUCT_ID} ${DEFAULT_CONFIG}
		cecho yblink "[${PRODUCT_ID}]"
	else
		cecho rblink '[NO-CONFIG]'
	fi
fi

if [ -e ${RESTORE_CONFIG} ]; then
if [ -e ${DEFAULT_CONFIG} ]; then

ncecho 'Checking database.          '

if [ ! -e /var/config ]; then
	cecho yellow '[COPYING DEFAULT]'
	ncecho '                            '
        ${RESTORE_CONFIG} ${DEFAULT_CONFIG} no-reboot
else
        VAR_CONFIG="/var/config"
	DEFVER=`grep configVersion ${DEFAULT_CONFIG} | awk '{print $2}'`
        CURVER=`grep configVersion ${VAR_CONFIG} | awk '{print $2}'`
        CURCOUNTRY=`grep sysCountryRegion ${VAR_CONFIG} | awk '{print $2}'`
        REGINFO=`${PRINTMD} | ${GREP} -i reginfo | awk '{print $3}'`
        DIFF_FILE="diff_"$CURVER"_"$DEFVER".txt"

        if [ $DEFVER != $CURVER ]; then
		ncecho '[VERSION DIFFERENCE]'
		ncecho '                            '
		/usr/local/bin/validate-config-version.sh ${CURVER} ${DEFVER}
		RESULT=$?
		if [ ${RESULT} -eq 1 ] && [ -e /etc/diffs/${DIFF_FILE} ]; then
			sed -i "s,configVersion ${CURVER},configVersion ${DEFVER},g" ${VAR_CONFIG}
			/usr/local/bin/upmigration.sh /etc/diffs/${DIFF_FILE} ${VAR_CONFIG}
			md5sum ${VAR_CONFIG} > /var/config.md5
			ncecho '[MIGRATION DONE]'
			ncecho '                            '

		elif [ ${RESULT} -eq 2 ] && [ ${CONFIG_ENC_DEC} = "yes" ]; then
			ncecho 'Please wait for ~ 5 min, Encryption is in progress... '
			/usr/local/bin/exr.sh
			sed -i "s,configVersion ${CURVER},configVersion ${DEFVER},g" ${VAR_CONFIG}
			md5sum ${VAR_CONFIG} > /var/config.md5
			ncecho '[ENCRYPTION DONE]'
			ncecho '                            '

		elif [ ${RESULT} -eq 3 ] && [ ${DOWNGRADE_MIGRATION_SUPPORT} = "yes" ]; then
			/usr/local/bin/migration.sh
			cat /tmp/config1 > ${VAR_CONFIG}
			md5sum ${VAR_CONFIG} > /var/config.md5
			ncecho '[Auto MIGRATION DONE]'
			ncecho '                            '

		else
			cecho yblink '[MIGRATION FAILED - RESTORE]'
			ncecho '                            '
			${RESTORE_CONFIG} ${DEFAULT_CONFIG} no-reboot

                fi
        fi
        if [ $REGINFO = $JP_TERRI ]; then
                ncecho '[REGINFO JAPAN]'
                sed -i s/"system:basicSettings:sysCountryRegion ${CURCOUNTRY}"/"system:basicSettings:sysCountryRegion 392"/ ${VAR_CONFIG}
                md5sum ${VAR_CONFIG} > /var/config.md5
        fi
fi

if [ ${MULTIPLE_SKU} = "yes" ]; then
	DEF_PRODUCT_ID=`${PRINTMD} ${MANUFAC_MTD_CHAR} | ${GREP} -i productid | awk '{print $3}'`
	VAR_PRODUCT_ID=`${GREP} -i productid /var/config | awk '{print $2}'`

        if [ ${DEF_PRODUCT_ID} != ${VAR_PRODUCT_ID} ]; then
                cecho yblink '[PRODUCT DIFFERENCE]'
		ncecho '                            '
                ${RESTORE_CONFIG} ${DEFAULT_CONFIG} no-reboot
        fi
fi

cecho green '[DONE]'

ncecho 'Verifing checksum.          '

if [ ! -e /var/config.md5 ]; then
        cecho yellow '[NO CHECKSUM]'
	ncecho '                            '
        ${RESTORE_CONFIG} ${DEFAULT_CONFIG} no-reboot
else
        ${MD5SUM} /var/config.md5 -c > ${NULL_DEVICE}
        if [ $? -ne 0  ]; then
		cecho yblink '[WRONG CHECKSUM]'
		ncecho '                            '
                ${RESTORE_CONFIG} ${DEFAULT_CONFIG} no-reboot
        fi
fi
cecho green '[DONE]'

fi
fi

if [ ! -e /var/pal.cfg ]; then
	ncecho 'Making a fresh copy of pal.cfg...	'
	cp -rf /etc/pal.cfg /var/pal.cfg
	cecho green '[DONE]'
fi
