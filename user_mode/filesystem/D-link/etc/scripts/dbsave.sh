#!/bin/sh
# dump the DOM tree to a file.
xmldbc -d /var/config.xml
gzip /var/config.xml
devconf put -f /var/config.xml.gz
rm -f /var/config.xml.gz
