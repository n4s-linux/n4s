#/svn/svnroot/Applications/tmux/lastact.bash
#bash /svn/svnroot/Applications/tmux/savewindow.bash
#!/bin/bash

# Get the current tmux session and window
current_session=$(tmux display-message -p '#S')
current_window=$(tmux display-message -p '#I')

# List all tmux sessions except the current one
target_session=$(tmux list-sessions -F '#S' | grep -v "^$current_session$" | fzf --prompt="Move to")

# Check if a session was selected
if [ -z "$target_session" ]; then
    tmux display-message "No target session selected. Action canceled."
    exit 1
fi

# Move the active window to the selected session
tmux move-window -s "$current_session:$current_window" -t "$target_session:"


