#!/bin/bash
cache_dir=~/.cache_ol/"$opath"/"$1"
mkdir -p "$cache_dir"
cache_file="$cache_dir/cache_$2"
cache_searches=~/.cache_search
if [ "$1" == "cache" ]; then
	process_count=$(echo -n $(ps aux|grep -i listoffers.bash" cache"|grep -v grep|wc -l))
	if [ "$process_count" -gt 0 ]; then
		echo listoffers cache cant start, already running
	fi
	#rm -rf ~/.cache_search.bash - slet det her lort, bruger vi ikke - medmindre vi har PROBLEMER
	tail -n1000 "$cache_searches"|uniq|while read cmd
	do
		echo running $cmd
		eval "$cmd" >/dev/null
		sleep 0.1
	done
	exit
fi
if [ -f "$cache_file" ]; then
	since=$((echo -n $(date +%s)" - ";stat -c %Y "$cache_file" )|bc)
	if [ "$since" -lt 5000 ]; then
		cat "$cache_file"
		exit
	fi
fi
echo "opath=$opath bash /svn/svnroot/Applications/listoffers.bash \"$1\" \"$2\"" >> "$cache_searches"

function showheader() {
	pushd . > /dev/null
	cd /data/ol/"$opath/template/";
	echo "<tr>";
	cat /data/ol/"$opath/template/.headers"|while read i
	do
		fn="$(basename "$(echo -n $(ls "$i - "*))")"
		echo "<td nowrap>$fn</td>";
	done
	echo "</tr>";
}
tmp=~/tmp
mkdir -p $tmp
mds=$(echo -n $(echo -n "$2"|md5sum| head -n1 | awk '{print $1;}'))
pushd . > /dev/null
rm $tmp/currap_$opath_$mds.html 2>/dev/null
tf=$tmp/currap_$opath_$mds.html

cd /data/ol/$opath
args=""
#showheader >> "$tf"
(ls -d *|grep -v template|grep -v completed)|while read i
do
	if [ -f /data/ol/"$opath/template/.headers" ] ; then
		#opath="$opath" bash /svn/svnroot/Applications/ol_columns.bash $i|grep -i "$1"  > ~/.tmp.333
		opath="$opath" bash /svn/svnroot/Applications/ol_columns.bash $i|grep -i "$1" >> $tf #~/"$opath"/template/.columns $i  >> $tf
	elif [ "$opath" == "supplier_deals" ]; then
#'1000 - Leverandør'  '2000 - Månedsbudget'  '3000 - Service'  '4000 - Næste fornyelse'
	id=$i
	leverandor=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
	echo $opath
	budget=$(echo -n $(cat /data/ol/$opath/$i/"2000 - "*))
	service=$(echo -n $(cat /data/ol/$opath/$i/"3000 - "*))
	renewal=$(echo -n $(cat /data/ol/$opath/$i/"4000 - "*))

echo "<tr>$leverandor&nbsp;</td><td>$i</td><td>$budget</td><td>$service</td><td>$renewal</td></tr>" >> $tmp/currap_$opath_$mds.html


	elif [ "$opath" == "inv" ]; then
#'0900 - Fakturadato'  '0950 - Forfaldsdato'  '1000 - Kunderef'  '3000 - Vores ref'  '5600 - Fakturalinier'

	id=$i
	fakturadato=$(echo -n $(cat /data/ol/$opath/$i/"0900 - "*))
	forfaldsdato=$(echo -n $(cat /data/ol/$opath/$i/"0950 - "*))
	kunderef=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
	voresref=$(echo -n $(cat /data/ol/$opath/$i/"3000 - "*))
	total=$(echo -n $(cat /data/ol/$opath/$i/"9900 - "*))

echo "<tr>$total &nbsp;</td><td>$i</td><td>$fakturadato</td><td>$forfaldsdato</td><td>$kunderef</td><td>$voresref</td></tr>" >> $tmp/currap_$opath_$mds.html
	
	elif [ "$opath" == "pr" ]; then
#'1000 - Afdeling'  '2000 - Status'  '7100 - Arbejdspapir'
	id=$i
	afdeling=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
	status=$(echo -n $(cat /data/ol/$opath/$i/"2000 - "*))
	arbejdspapir=$(echo -n $(cat /data/ol/$opath/$i/"7100 - "*))
	overskrift=$(echo -n $(cat /data/ol/$opath/$i/"1500 - "*))
	timestamp=$(echo -n $(cat /data/ol/$opath/$i/"0900 - "*))

echo "<tr>$timestamp&nbsp;</td><td>$i</td><td>$afdeling</td><td>$overskrift</td><td>$status</td></tr>" >> $tmp/currap_$opath_$mds.html
	elif [ "$opath" == "m" ]; then

#'0900 - Reference'  '1000 - Kunderef'  '2000 - Modtaget'  '3000 - Indhold'  '3500 - Kommentar'  '4000 - Lokation'
		id=$i
		ref=$(echo -n $(cat /data/ol/$opath/$i/"0900 - "*))	
		kunde=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
		modtaget=$(echo -n $(cat /data/ol/$opath/$i/"2000 - "*))
		indhold=$(echo -n $(cat /data/ol/$opath/$i/"3000 - "*))
		kommentar=$(echo -n $(cat /data/ol/$opath/$i/"3500 - "*))
		lokation=$(echo -n $(cat /data/ol/$opath/$i/"4000 - "*))
echo "<tr><td width=100>$modtaget</td><td>$i</td>  <td>$ref</td> <td width=150>$kunde</td><td width=300>$indhold</td><td width=500>$kommentar</td><td>$lokation</td></tr>"  >> $tmp/currap_$opath_$mds.html
	elif [ "$opath" == "t" ]; then
		id=$i
		fno=/data/ol/$opath/$i/"7500 - Outsourcing"
		if [ -s "$fno" ]; then
		outsourc="OutSRC: "$(echo -n $(cat "$fno"))
		else
		outsourc="";
		fi
		ref=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
		dl=$(echo -n $(cat /data/ol/$opath/$i/"9000 - "*))
		opgave=$(echo -n $(cat /data/ol/$opath/$i/"5000 - "*|sed 's/$/<br>/'))
		status=$(echo $(cat /data/ol/$opath/$i/"7000 - "*|sed 's/$/<br>/'))
		estimat=$(echo $(cat /data/ol/$opath/$i/"8500 - "*|sed 's/$/<br>/'))
#		vorref=$(echo -n $(cat ~/$opath/$i/"3000 - "*|sed 's/$/<br>/'))
#		echo "<tr><td width=100>$dl</td><td>$i</td><td width=150>$ref</td><td width=175>$vorref</td><td width=300>$opgave</td><td width=500>$status</td></tr>"  >> $tmp/currap_$opath.html
		echo "<tr><td width=100>$dl</td><td>$i</td>  <td>$estimat</td> <td width=150>$ref</td><td width=300>$opgave</td><td width=500>$status</td><td>$outsourc</td></tr>"  >> $tmp/currap_$opath_$mds.html
		chmod 777 $tmp/currap_$opath_$mds.html

	elif [ "$opath" == "c" ]; then
		id=$i
		ref=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
		cvr=$(echo -n $(cat /data/ol/$opath/$i/"4500 - "*))
		email=$(echo -n $(cat /data/ol/$opath/$i/"5000 - "*))
		tlf=$(echo $(cat /data/ol/$opath/$i/"4000 - "*))
		echo "<tr><td width=300>$ref</td><td width=250>$i</td><td width=300>$cvr</td><td width=500>$email</td><td width=100>$tlf</td></tr>"  >> $tmp/currap_$opath_$mds.html
	elif [ "$opath" == "p" ]; then
		id=$i
		ref=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
		email=$(echo -n $(cat /data/ol/$opath/$i/"6000 - "*))
		tlf=$(echo $(cat /data/ol/$opath/$i/"5000 - "*))
		echo "<tr><td width=250>$ref</td><td width=250>$i</td><td width=500>$email</td><td width=100>$tlf</td></tr>"  >> $tmp/currap_$opath_$mds.html
	elif [ "$opath" == "st" ]; then
		id=$i
		ref=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
		dl=$(echo -n $(cat /data/ol/$opath/$i/"5000 - "*))
		opgave=$(echo -n $(cat /data/ol/$opath/$i/"2000 - "*|sed 's/$/<br>/'))
		status=$(echo $(cat /data/ol/$opath/$i/"6000 - "*|sed 's/$/<br>/'))
		estimat=$(echo -n $(cat /data/ol/$opath/$i/"7000 - "*|sed 's/$/<br>/'))
		echo "<tr><td width=200>$dl</td><td>$i</td><td width=250>$ref</td><td width=300>$opgave</td><td width=500>$status</td><td width=100>$estimat</td></tr>"  >> $tmp/currap_$opath_$mds.html

	elif [ "$opath" == "j" ]; then
#'1000 - Dato'  '2000 - Arbejde'  '3000 - Familie'  '4000 - Vennter'  '5000 - Damer'  '6000 - Medicin'  '7000 - Mad'
		id=$i
		dl=$(echo -n $(cat /data/ol/$opath/$i/"1000 - "*))
		arbejde=$(echo -n $(cat /data/ol/$opath/$i/"2000 - "*))
		familie=$(echo -n $(cat /data/ol/$opath/$i/"3000 - "*))
		venner=$(echo -n $(cat /data/ol/$opath/$i/"4000 - "*))
		aktiviteter=$(echo -n $(cat /data/ol/$opath/$i/"8000 - "*))
echo "<tr><td width=200>$dl</td><td>$i</td><td width=250>$arbejde</td><td width=300>$familie</td><td width=500>$venner</td><td width=100>$aktiviteter</td></tr>" >> $tmp/currap_$opath_$mds.html


	fi
done
#cat "$cache_file" |grep -i "$1" >> $tf
#cat $tmp/currap_$opath_$mds.html
echo "<table border=1>" > $tmp/currap2_$opath_$mds.html
#echo "<tr><td>Ny</td><td>Ny</td><td>Opret ny</td></tr>" >> $tmp/currap2_$opath_$mds.html;
#echo "<tr><td width=200>Deadline</td><td>id</td><td width=250>Ref</td><td width=300>Opgave</td><td width=500>Status</td><td>Estimat</td></tr>" >> $tmp/currap2_$opath.html
cat $tmp/currap_$opath_$mds.html|sort -h|grep -i "$2" >> $tmp/currap2_$opath_$mds.html
echo "</table>" >> "$tmp/currap2_$opath_$mds.html"
popd > /dev/null
w3m -dump "$tmp/currap2_$opath_$mds.html" > "$cache_file"
cat "$cache_file"
rm $tmp/currap2_$opath_$mds.html 2>/dev/null
rm $tmp/currap_$opath_$mds.html 2>/dev/null

