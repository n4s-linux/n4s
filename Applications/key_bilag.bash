#!/bin/bash
mkdir -p ~/.curbilag
if [ "$dbpath" == "" ]; then
	echo "Kræver dbpath sat - kør srb"
	exit
	#dbpath="$(find ~/Dropbox/*/Bilag/Incoming/ -type d|fzf --header "FIND KUNDEMAPPE" )"
fi
(pushd . >/dev/null;cd "$dbpath";(find . -name \*.pdf -printf '%TY-%Tm-%Td\t%p\n')|sort --reverse|fzf --bind "f12:execute(echo hej;rm ~/.dbpath;abort)" --header="dbpath: $dbpath (skift ved at skrive srb) " --multi|while read i; do echo "$i"|cut -f2; done;popd >/dev/null) > ~/.filez
wc=$(echo -n $(ls ~/.curbilag/|wc -l|tail -n1))
#if [ ! -z ~"/.curbilag/" ]; then
if [ ! "$wc" == "0" ]; then
	echo Der ligger allerede filer som ikke er brugt...
	ls ~/.curbilag
	echo -n "Skal de flytes tilbage ? (j/n): "
	read jn
	if [ "$jn" == "j" ]; then
		mv ~/.curbilag/* "$dbpath"
		echo "ok er flyttet, prøv igen nu..."
	fi
else
	cat ~/.filez	|while read i
	do
		mv "$dbpath/$i" ~/.curbilag
	done
fi
