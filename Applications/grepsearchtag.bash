function searchtag(){
        search="$1"
        fn="$2"
#       cat "$fn"|sed -n -e "/$search/,\$p"|sed -e '/^#/,$d'
grep "#$search" "$fn"|grep -v ✔|while read i ; do echo -n "$i ";done|cut -c 1-80
}

function grepsearchtag() {
        source $tpath/.tags.bash
        search="$(gettags)"
	bash /svn/svnroot/Applications/newsearchgrep.bash "$search" < /dev/tty
	return

	# below is not being run anymore, this is a sorted list, it is better, and a full list - not just one entry per file



        tag=$(grep -i "#$search" .tags/*|grep -v \.diff|grep -v "✔"|grep -v ✔|sed 's/.tags\///g'|cut -d: -f1|uniq|sort|while read i
        do
                data=$(searchtag "$search" $tpath/.tags/"$i")
                echo "$i $data"
        done|fzf -e|awk '{print $1}')
        if [ "$tag" == "" ]; then
                echo "Du valgte ikke et søgeord- tryk ENTER.."|cowsay
                read
                return

        else
                $tpath/.menu.bash tags "$tag"
        fi
        if [ -f "$tpath/.tags/$search" ]; then
                touch $tpath/.tags/"$search"
        fi




}

