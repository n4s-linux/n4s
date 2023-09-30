current=$(tmux lsw -F '#{window_name}#{window_active}'|sed -n 's|^\(.*\)1$|\1|p')
new=${current::-1}
tmux rename-window "$new"
