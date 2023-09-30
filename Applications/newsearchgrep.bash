if [ "$1" == "" ]; then
	echo kræver argumenter
	exit
fi
#tpath=/data/regnskaber/transactions_crm
cd $tpath/.tags
valg=$(
grep "#$1" * -l|grep -v .diff|while read file
do
	cat "$file"|php /svn/svnroot/Applications/markdowngrepexpand.php "$file"|grep "#$1" |grep -v  "✔"|grep -v php
done|column -ts $'\t'|sort|fzf -e  --header="vælg sag"|grep -Po '(?<=(💾)).*(?=💾)')
cd $tpath
tpath=$tpath bash $tpath/.menu.bash tags "$valg"
