pushd . 2&>/dev/null
mkdir -p ~/.otimers
cd ~/.otimers
ls|while read i
do
	mt=$(stat "$i" -c %Y)
	now=$(date +%s)
	diff=$(echo "($now-$mt)/60"|bc)
	echo -n "⌛$diff "
done



popd 2&>/dev/null
