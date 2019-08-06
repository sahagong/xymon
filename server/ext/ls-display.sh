#/bin/sh

## total list
$XYMONHOME/bin/xymongen \
--pageset=ls --template=ls \
--page-title=total --critical-reds-only $XYMONHOME/www/ls
