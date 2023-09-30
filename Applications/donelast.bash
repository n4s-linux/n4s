#!/bin/bash
cat "$1"|grep done > /tmp/done
cat "$1"|grep -v done > /tmp/notdone
cat /tmp/notdone /tmp/done > "$1"
rm /tmp/done /tmp/notdone 
