cd /data/regnskaber
inotifywait -m -r *|while read line
do
		line=$(echo "$line"|sed 's/ .*//')
		tpath="/data/regnskaber/$line"
		echo $tpath
		cd $tpath
		CURTIME=$(date +%s)
		FILETIME=$(stat curl -c %Y)
		TIMEDIFF=$(expr $CURTIME - $FILETIME)
		echo timediff=$TIMEDIFF
		if [ "$TIMEDIFF" -gt 5 ]; then
		echo tpath="$tpath" php /svn/svnroot/Applications/key.php l b 
		tpath="$tpath" php /svn/svnroot/Applications/key.php l b 
		fi
done
