name="500 - "
tmux new-session -d -s "$name"
tmux switch-client -t "$name"
tmux new-window "bash /svn/svnroot/Applications/start.bash business" 
