#!/bin/bash
exec > /dev/null 2>&1
cw=$(cat ~/tmp/.cw)
php /svn/svnroot/Applications/logvimtime.php "$cw" 2>/dev/null
