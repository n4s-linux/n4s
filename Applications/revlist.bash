cd /svn/svnroot/
revlist=$(git rev-list -n 200 HEAD)
(
  for rev in $revlist
  do
    lines=$(git log -1 --pretty="format:%b" $rev|head -n1|sed 's/#//g') 
    files=$(git log -1 --pretty="format:" --name-status $rev|head -n10)
    echo "# $(git log -1 --pretty="👾%s" $rev)"
    IFS=$'\n'
    for line in $lines
    do
      echo "> $line "|sed 's/```//g'
    done
    unset IFS
	git --no-pager show "$rev" |head -n50|while read i; do echo -e "\t$i";done
    echo 'Filer ændret:'
    while read change file; do 
      if [ ${#file} -gt 0 ]
      then
        echo "* ($change) $file "; 
      fi
    done <<< "$files"
  done
)
