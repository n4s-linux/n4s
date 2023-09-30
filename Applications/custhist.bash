echo "# Seneste handlinger"
cd $tpath/.tags;
grep -v "Opened "$rp.diff"|tail -n50 |while read i
do
echo -e "\t$i"
done

echo

echo " # En tom sektion"
