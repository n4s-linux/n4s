#!/bin/bash
search="$(((cat /data/ol/companies/*/1000" - "*|uniq| sed '/^$/d'|sort);cat ~/tmp/lastsearch)|uniq|fzf --tac)"
echo "$search" > ~/tmp/lastsearch
source /svn/svnroot/Applications/ol.bash
db=$(echo 'companies
p
t
m'|fzf)
ol "$db" "$search"
