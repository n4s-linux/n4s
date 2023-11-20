echo -n "Hvad vil du søge efter: "
read soeg
db=$(ls -1td ~/regnskaber/*/.tags|sed 's/\/\.tags//g'|while read i; do basename "$i"; done|fzf -1 --header="Vælg database")
tpath=~/regnskaber/$db
cd $tpath/.tags
searchtype=$(echo -e "Hashtags\nFullsearch"|fzf --header="Vælg søgetype")
if [ $searchtype == "Hashtags" ]; then
fn=$(find . -type f -not -path '*/.*' -exec awk 'BEGIN { OFS = "\t"; ORS = "\n" } !/✔/ && /#'"$soeg"'/ {print FILENAME,$0}' {} +|grep -v .diff|sed 's/^\.\///'|column -ts $'\t'|fzf -e|awk '{print $1}')
else
fn=$(find . -type f -not -path '*/.*' -exec awk 'BEGIN { OFS = "\t"; ORS = "\n" } !/✔/ && /'"$soeg"'/ {print FILENAME,$0}' {} +|grep -v .diff|sed 's/^\.\///'|column -ts $'\t'|fzf -e|awk '{print $1}')
fi
cd $tpath
tpath=$tpath bash $tpath/.menu.bash tags "$fn"
