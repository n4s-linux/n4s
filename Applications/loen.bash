#!/bin/bash
exec < $SVNROOT/Documents/Kunder/Lønliste
while read i
do
rt create -t ticket set subject="Månedsløn $(LC_ALL=da_DK date +"%B %Y")"
done
