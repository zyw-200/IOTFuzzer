echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
rgdb -A /etc/templates/apc_auto_clone.php > /var/run/apc_auto_clone.sh
sh /var/run/apc_auto_clone.sh
