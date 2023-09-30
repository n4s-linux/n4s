tpath=~/regnskaber/transactions_stuff
destfn=$tpath/.tags/stuff_add
tag=$((ls $tpath/.tags|grep -v .diff;echo NY)|fzf)
if [ "$tag" == "NY" ]; then
	echo -n "Indtast tag: "
	read tag
fi
data=$tag
echo "Sidste transaktioner fundet: "
grep "$data" $tpath/.tags/*|grep -v .diff|tail -n15|while read i
do
	echo -e "\t$i"
done
dato=$(date +"%Y-%m-%dT%H:%M")
echo -n "Indtast kroner: "
read kroner
if [ "$kroner" != "" ]; then
	kroner="#"$kroner"kroner"
fi
echo -n "Indtast min: "
read min 
if [ "$min" != "" ]; then
	min="#"$min"min"
fi
echo -n "Evt. kommentar: "
read kommentar
echo -e "$dato\t#$data $kommentar $kroner $min" >> $destfn
tail -n 1 $destfn|cowsay -W80
echo exiting in 15...
sleep 15
