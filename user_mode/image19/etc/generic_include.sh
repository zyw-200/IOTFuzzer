#!/bin/sh

# Configurables.
# Abs path for which, so that other part is going to remain generic in most of the conditions.
WHICH=/usr/bin/which
umask 0007

#LED Colour codes specific to WG, all other LED drivers should adopt this method.
OFF=5
GREEN=3
AMBER=1
BLINKING_GREEN=4

#System Related

RFS_IMAGE=squashfs
ETC_FS=tmpfs
VAR_FS=jffs2

VAR_MTD_BLK=/dev/mtdblock4
MANUFAC_MTD_CHAR=/dev/mtd5

#Config related

DROPBEAR_DIR=/etc/dropbear
LOG_N_MANU_DIR=/var

NULL_DEVICE=/dev/null
DEFAULT_CONFIG=/etc/default-config

DEFAULT_REGINFO=0
MAC_OFFSET_TO_CHECK=0
DEFAULT_MAC=001122334400
MANU_BOARD_FILE=/etc/board

NO_OF_WDS=4
NO_OF_VAPS=8
NO_OF_RADIOS=1
WLAN_BUS="pci"
SET_MAC_WLAN="yes"
MAC_OFFSET_4_WLAN=0
WLAN_WITH_BASEMAC_N_COUNTRY="yes"
WLAN_MAX_CLIENT_SUPPORT="yes"
WLAN_DFS_SUPPORT="yes"
WLAN_MODULE_PATH=/lib/modules/wlan
SYS_USES_WLAN_GPIO="no"

ETH_MTU=1500
SET_MAC_ETH="yes"
ETH_INTERFACE=eth0
MAC_OFFSET_4_ETH=8
ETH_DRIVER=ag7100_mod.ko
ETH_MAC_CONGIURABLE="yes"

MULTIPLE_SKU="no" # single firmware for more than SKU
SKU_ONE=WG102
SKU_TWO=WG103
JP_TERRI="2"

DOWNGRADE_MIGRATION_SUPPORT="yes"

KLOGD_LOG_LEVEL=1 #only messg having log level les than this will be printed on console.

START_LED_DRIVER="yes"
LED_DRIVER=panel-led-driver.ko
LED_USR_SPACE=panel_led
LED_BOOTING_MODE=${BLINKING_GREEN}
LED_LOGIN_MODE=${GREEN}
LED_ERR_MODE=${AMBER}

START_WDT_DRIVER="yes"
WDT_DRIVER=sys-watchdog.ko
WDT_USR_SPACE=watchdog

START_RST_DRIVER="yes"
PANEL_RST_DRIVER=panel-reset-sw.ko
RST_USR_SPACE=reset_detect

CONFIG_CENTRALIZED_VLAN="yes"
BRIDGE_NF_DISABLED_BY_DEFAULT="yes"

CONFIG_CLOUD="yes"
CLIENT_MODE="yes"
CONFIG_ENC_DEC="yes"

TRANSLATORS="syslog password ssh snmp telnet dns bridge_and_vlan_translator hostapd_tr nmbd_tr http_redirect_tr ethtool_tr dhcp ntp timezone sc_radio pal_translator dump_config_logs_tr"
AP_MODE_TRANSLATORS="syslog password ssh snmp telnet dns client_bridge_tr nmbd_tr ethtool_tr dhcp ntp timezone sc_radio pal_translator dump_config_logs_tr"
TRANSLATORS_BIN_LOCATION=/usr/local/bin

# Cmds used in system initilization. [ Auto no need to change then from board to board.]
CP=`${WHICH} cp`
MOUNT=`${WHICH} mount`
RM=`${WHICH} rm`

INSMOD=`${WHICH} insmod`
RMMOD=`${WHICH} rmmod`

MD5SUM=`${WHICH} md5sum`
GREP=`${WHICH} grep`
MKDIR=`${WHICH} mkdir`

DROPBEAR_PGM=`${WHICH} dropbear`
DROPBEARKEY=`${WHICH} dropbearkey`

BDDATARD=`${WHICH} bddatard`
WR_MFG_DATA=`${WHICH} wr_mfg_data`
PRINTMD=`${WHICH} printmd`

IFCONFIG=`${WHICH} ifconfig`

KLOGD=`${WHICH} klogd`
SYSLOGD=`${WHICH} syslogd`

PANEL_LED=`${WHICH} ${LED_USR_SPACE}`
PANEL_RESET=`${WHICH} ${RST_USR_SPACE}`
SYS_WATCHDOG=`${WHICH} ${WDT_USR_SPACE}`

CONFIGD=`${WHICH} configd`

LIGHTTPD=`${WHICH} lighttpd`
LIGHTTPD_CONF=/etc/lighttpd.conf

LOG_FILE=/var/log/messages
LOG_SIZE=64
NO_OF_OLD_LOGS=2

RESTORE_CONFIG=/usr/local/bin/restore-configuration

PRODUCT_ID=`${PRINTMD} ${MANUFAC_MTD_CHAR} | ${GREP} -i productid | awk '{print $3}'`

WLANCONFIG=/usr/local/bin/wlanconfig # wlan related things which are not part of sdk they have absolute path.

WORKAROUNDS="yes"
FUSION_CODE_BASE="no"
# Include the colour script for echo.
. /etc/colour_output.sh
