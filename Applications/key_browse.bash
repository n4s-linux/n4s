(LEDGER_DEPTH=999 noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger r "$1" --register-format="%(date)\t%(display_amount)\t%(display_total)\t%(payee)\t%(tag('Filename'))\t%(tag('TransID'))\n")> /tmp/data
chmod 777 /tmp/data
php key_browse.php
