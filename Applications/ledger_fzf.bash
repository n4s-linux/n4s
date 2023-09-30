c=""
a=$(LEDGER_DEPTH=1 php key.php ledger bal -S amount --no-total --balance-format="%(account)|%(total)\n"|column -t -s "|" |fzf|awk '{print $1}')
b=$(LEDGER_DEPTH=2 php key.php ledger bal -S amount --no-total "$a:" --balance-format="%(account)|%(total)\n"|column -t -s "|"|fzf|awk '{print $1}')
if [ "$b" = "$a" ]; then
echo
else
if [ "$b" != "" ]; then
c=$(LEDGER_DEPTH=3 php key.php ledger bal -S amount --no-total "$b:" --balance-format="%(account)|%(total)\n"|column -t -s "|"|fzf|awk '{print $1}')
fi
fi
if [ "$c" = "" ]; then 
	c="$b"
fi
LEDGER_DEPTH=999 php key.php ledger register "$c"|vi -
