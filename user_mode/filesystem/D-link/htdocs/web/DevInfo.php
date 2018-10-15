HTTP/1.1 200 OK

Firmware External Version: V<?echo cut(fread("", "/etc/config/buildver"), "0", "\n");?>
Firmware Internal Version: <?echo cut(fread("", "/etc/config/buildno"), "0", "\n");?>
Model Name: <?echo query("/runtime/device/modelname");?>
Hardware Version: <?echo query("/runtime/devdata/hwrev");?>
WLAN Domain: <?echo query("/runtime/devdata/countrycode");?>
Kernel: <?$ver = cut(fread("", "/proc/version"), "0", "("); echo cut($ver, "2", " ");?>
Language: <?$lang = query("/runtime/device/langcode"); if ($lang=="") echo "en"; else echo $lang;?>
Graphcal Authentication: <?if (query("/device/session/captcha")=="1") echo "Enable"; else echo "Disable";?>
LAN MAC: <?echo query("/runtime/devdata/lanmac");?>
WAN MAC: <?echo query("/runtime/devdata/wanmac");?>
WLAN MAC: <?echo query("/runtime/devdata/wlanmac");?>
