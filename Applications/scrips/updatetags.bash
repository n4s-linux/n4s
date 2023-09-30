(egrep -o '#[^ ]+'.  $tpath/*.ledger $tpath/*.trans $tpath/.tags/* -h|uniq|awk '{print $1}'|sed 's/://g'|sed "s/'//g"|sed 's/#//g'|sed 's/"//g'|sed 's/,//g')|sed 's/[A-Z]/\L&/g'|while read tag; do if [ ! -f "./.tags/$tag" ]; then touch ./.tags/"$tag" 2>/dev/null; fi; done

#check todays transactions to see if they have has tags that we need to touch to keep updated
(find $tpath/ -maxdepth 1 -name \*.trans -mtime -1 |while read i
do
egrep -o '#[^ ]+'. "$i"
done|uniq|sed 's/#//g'
)|while read i
do
        #echo $i
	i=$(echo "$i"|awk '{print tolower($0)}')
        mt=$(stat -c %Y "$tpath/.tags/$i" 2>/dev/null)
        now=$(date +%s)
        diff=$(echo "(0$now-0$mt)/86400"|bc)
        #echo diff=$diff
        if [ "$diff" == "0" ]; then
                touch "$tpath/.tags/$i"
        fi
done

pushd . >/dev/null
cd "$tpath/.tags"
ls > .filelist
popd >/dev/null
