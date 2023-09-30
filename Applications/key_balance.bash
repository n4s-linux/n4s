LEDGER_DEPTH=999
#php /svn/svnroot/Applications/key.php ledger b --flat --balance-format="%(account)|||||%(display_total)\n" --no-total > ~/tmp/key_balance.tmp
LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger csv > ~/tmp/key_balance.tmp &&php /svn/svnroot/Applications/key_balancecsv.php
rm ~/tmp/key_balance.tmp
