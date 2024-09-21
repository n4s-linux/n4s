echo -ne "\e[38;5;208m🔍 \e[1mWhat to search for: \e[0m"
read soeg
db=$(ls -1td ~/regnskaber/*/.tags|sed 's/\/\.tags//g'|while read i; do basename "$i"; done|fzf -1 --header="Vælg database")
tpath=~/regnskaber/$db
cd $tpath/.tags
searchtype=$(echo -e "Hashtags\nFullsearch"|fzf --header="Vælg søgetype")
if [ $searchtype == "Hashtags" ]; then
fn=$(find . -type f -not -path '*/.*' -exec awk 'BEGIN { OFS = "\t"; ORS = "\n" } !/✕/ && !/✔/ && /#'"$soeg"'/ {print FILENAME,$0}' {} +|grep -v .diff|sed 's/^\.\///'|column -ts $'\t'|sort -k2 -r |fzf -e|awk '{print $1}')
else
fn=$(find . -type f -not -path '*/.*' -exec awk 'BEGIN { OFS = "\t"; ORS = "\n" } !/✕/ && !/✔/ && /'"$soeg"'/ {print FILENAME,$0}' {} +|grep -v .diff|sed 's/^\.\///'|column -ts $'\t'|fzf -e|sort -k2 -r|awk '{print $1}')
fi
if [ "$fn" == "" ]; then
	exit
fi
cd $tpath
tpath=$tpath bash $tpath/.menu.bash tags "$fn"
