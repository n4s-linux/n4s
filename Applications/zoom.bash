konto="$1"
konto="$(php /svn/svnroot/Applications/key.php ledger accounts|fzf)"
while true
do

noend=1 LEDGER_BEGIN=1970/1/1 php /svn/svnroot/Applications/key.php ledger csv "^$konto" |php /svn/svnroot/Applications/zoom.php
done
