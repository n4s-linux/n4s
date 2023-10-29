if [ "$orgtpath" != "" ]; then
	echo du er allerede i en faktura
	exit
fi

mkdir -p $tpath/.inv

function getlast {
	ls $tpath/.inv|sort -n|tail -n1
}
function getnext {
	last=$(getlast)
	echo $last+1|bc
}

valg="Ny faktura
Rediger faktura"

valg=$(echo "$valg"|fzf)
if [ "$valg" == "Rediger faktura" ]; then
	export orgtpath=$tpath
	next=$(ls $tpath/.inv|fzf --header="Vælg faktura")
elif [ "$valg" == "Ny faktura" ]; then
	export orgtpath=$tpath
	next=$(getnext)
fi

export ip=$tpath/.inv/$next
export ipc=$ip/.custdata
export ipf=$ip/.fdata
mkdir $ip
mkdir $ipc
mkdir $ipf
vi $ipc/Navn $ipc/Adresse $ipc/PostnrBy $ipc/CVR
date +%Y-%m-%d > $ipf/Fakturadato
tpath=$ip
touch $ip/curl
LEDGER_BEGIN=1970/1/1
LEDGER_END=2099/12/31
export -p >~/tmp/fork.bash
echo "alias e='php /svn/svnroot/Applications/createinvoiceline.php'" >> ~/tmp/fork.bash
echo "declare -x PROMPT_COMMAND=" >>  ~/tmp/fork.bash
echo "cd $tpath" >>~/tmp/fork.bash
echo "source /svn/svnroot/Libraries/aliases" >> ~/tmp/fork.bash
echo "alias mkinv='bash /svn/svnroot/Applications/simpleinv.bash'" >> ~/tmp/fork.bash
echo "skriv mkinv for at lave faktura"
bash --rcfile ~/tmp/fork.bash
