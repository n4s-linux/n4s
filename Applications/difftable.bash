date="$1"
grep $date ~/regnskaber/stuff/.tags/*.diff /data/regnskaber/transactions_crm/.tags/*.diff|sort -t : -k 2,2 |grep -v "\*\*\*"|php /svn/svnroot/Applications/difftable.php
