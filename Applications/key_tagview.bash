#!/bin/bash
tpath=~/regnskaber/transactions_stuff
cd $tpath
echo "Loading..."
clear
git blame .tags/$1|tail|fzf --tac
exit
watch -t -n0.2 "git blame .tags/$1|tail"
