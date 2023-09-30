pushd . >/dev/null
cd "$tpath"
fn=~/tmp/.book_$(basename "$tpath")
dato=$(date)
op=$(whoami)
uid=$(date +%s)
echo "; Start poster bogført $dato ($op, $uid)" > "$fn"
noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger print >> "$fn"
echo -e "; Slut poster bogført $dato ($op, $uid)\n\n" >> "$fn"

less "$fn"
echo -n "Er du sikker på at du vil bogføre de viste poster og slette disse poster? (j/n): "
read jn
if [ "$jn" == "j" ]; then
	cat "$fn" >> mainbook.ledger
	cat "$fn"|less
	uid=$(date +%s)
	mkdir -p .booked/$uid
	mv mainbook.ledger .mainbook.ledger
	mv $tpath/*.ledger .booked/$uid
	mv .mainbook.ledger mainbook.ledger
	mv $tpath/*.trans .booked/$uid
else
	echo not booked
fi
popd >/dev/null
