#!/bin/bash
noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=$(echo -n $(date +%Y-%m-%d -d "tomorrow")) php /svn/svnroot/Applications/key.php ledger csv ^Aktiver ^Passiver > ~/tmp/interest.out
php /svn/svnroot/Applications/interest.php
