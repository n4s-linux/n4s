#!/bin/bash
LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 noend=1 php /svn/svnroot/Applications/key.php ledger accounts> ~/tmp/accounts
mkdir -p $tpath/.calc
activebudget=$(ls -t $tpath/.*.dat|head -n 1|cut -d '.' -f 2) php /svn/svnroot/Applications/budget.php
rm ~/tmp/accounts
php /svn/svnroot/Applications/key.php ledger b> /dev/null
