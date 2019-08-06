#!/bin/bash

/usr/bin/find /home/xymon/server/tmp/ -mtime +7 -exec rm -f {} \;
