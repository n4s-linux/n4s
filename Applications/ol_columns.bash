y=0
echo -n "<tr>"
cat /data/ol/$opath/template/.headers|while read i; do
	if [ "$y" == 1 ]; then
		echo -n "<td>$1</td>" 2>/dev/null
	fi
(
	echo -n "<td>" 
	echo -n $(cat /data/ol/$opath/$1/"$i -"*) 2>/dev/null
	echo -n "&nbsp;</td>"
) 2>/dev/null
	y=$((y+1))
done
echo "</tr>"
