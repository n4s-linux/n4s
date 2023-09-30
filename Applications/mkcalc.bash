ny=$((ls $tpath/.calculations 2>/dev/null;echo NY)|fzf --tac)
if [ "$ny" != "NY" ]; then
	vi $tpath/.calculations/"$ny"
fi
function sanitize_file_name {
	    echo -n $1 | perl -pe 's/[\?\[\]\/\\=<>:;,''"&\$#*()|~`!{}%+]//g;' -pe 's/[\r\n\t -]+/-/g;'
    }
if [ "$tpath" == "" ]; then
	echo kræver du er på en kunde
	exit
fi

mkdir -p "$tpath/.calculations"
fn=$(date +%s)
echo python /svn/svnroot/Applications/calc.py $tpath/.calculations/$fn
python /svn/svnroot/Applications/calc.py $tpath/.calculations/$fn

echo -n "Indtast navn for beregning: "
read navn
orgnavn="$navn"
navn=$(sanitize_file_name "$navn")
echo "$orgnavn" > $tpath/.calculations/$navn
cat $tpath/.calculations/$fn >> "$tpath/.calculations/$navn"
rm $tpath/.calculations/$fn
vi "$tpath/.calculations/$navn"
