konto=$(php /svn/svnroot/Applications/key.php ledger accounts|fzf --header="Vælg konto"|sed 's/\//./g')
sort=$(echo -e "date\npayee\namount\ncode"|fzf --header="vælg sortering")

bn=$(basename "$tpath")
php /svn/svnroot/Applications/key.php ledger -S $sort r "$konto" --register-format="%(date)|||%(code)|||%(payee)|||%(display_amount)|||%(display_total)||%(tag('Filename'))\n"|column -t -s\|\|\||fzf --exact --header="Vælg transaktioner til åbning eller afbryd med CTRL-C" --tac --multi|awk '{print $NF}'|while read fn
do
	tmux new-window -n "$bn/$fn" "tpath=$tpath LEDGER_BEGIN=$LEDGER_BEGIN LEDGER_END=$LEDGER_END php /svn/svnroot/Applications/key.php search 1970-01-01 2099-12-31 $fn"
done
