function gettags() {
        fn=$((ls -ltr $tpath/.tags/|grep -v .scrabble$|grep -v ".regn$"|grep -v ".diff$";echo NEW)|fzf --no-sort -e --tac|awk '{print $NF}')
	#cat /tmp/tagz.tmp
        #cp /tmp/tagz.tmp /tmp/tagz
	if [ "$fn" == "NEW" ] ; then
		stty sane
		echo -n "New journal name: " > /dev/tty
		read fn	 </dev/tty
		touch "$tpath/.tags/$fn"
	fi
        echo "$fn"

}

