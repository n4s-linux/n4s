#!/bin/bash
for i in 1 2 3 4 5 6
do
(
echo 'j
p
c
t'|while read i; do opath=$i bash /svn/svnroot/Applications/listoffers.bash cache; done
)&
sleep 9

done
