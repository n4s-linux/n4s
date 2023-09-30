echo $tpath
function l () 
{ 
    php /svn/svnroot/Applications/key.php ledger $@ 2>/dev/null
}
LEDGER_DEPTH=3
LEDGER_DEPTH=1 l b --balance-format="%(account)\n"|while read tla
do
	if [ "$tla" == "" ]; then
		continue;
	fi
	12m $(echo "$tla"|sed 's/\//\\\//g')
done
