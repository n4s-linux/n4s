echo -n "Indtast navn: "
read name
tmux new-session -d -s "$name"
tmux switch-client -t "$name"
