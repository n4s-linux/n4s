fn=/tmp/banklist_$(whoami)
fnt=$fn.tmp
ls /data/regnskaber/ |while read regnskab;
	do
		dato=$(LEDGER_BEGIN=1970/01/01 LEDGER_END=$(date +%Y-%m-%d) LEDGER_DEPTH=5 ledger --date-format="%Y-%m-%d" -f "$regnskab/curl" r bank 2>/dev/null|tail -n1|awk '{print $1}')
		if [ "$dato" == "" ]; then
			dato=1970-01-01
		fi
	       	echo -e "$dato\t$regnskab<br>";done > $fnt
		sort $fnt > $fn
mutt -F /svn/svnroot/Libraries/muttrc -e "set content_type=text/html" olsenit@gmail.com -s "/svn/svnroot/Applications/key_banks.bash" < $fn 2>/dev/null

