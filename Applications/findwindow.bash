id=$(tmux list-windows|grep "$1"|awk '{print $1}'|sed 's/://g')
if [ "$id" != "" ]; then
	tmux select-window -t "$id"
	echo "found"
else
	echo "notfound"
fi


