cat ~/regnskaber/config.bash|fzf --tac --multi|while read multi
do
	echo -n "Indtast ny værdi for "$multi": " < /dev/tty
	varname=$(echo "$multi"|cut -f1 -d"=")
	read ny < /dev/tty
	tmux select-pane -D
	tmux send-keys "$varname=$ny "
	tmux select-pane -U
done
echo -n "Indtast navn for parametersæt til hurtig finding ved historik (ctrl-R): "
read kommentar
tmux select-pane -D
tmux send-keys " # $kommentar"
tmux select-pane -U
