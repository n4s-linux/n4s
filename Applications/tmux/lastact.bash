#!/bin/bash

# Log file to track the last action
log_file=~/tmp/.cw
log_files=~/tmp/.cws

# Get the current session and window
session=$(tmux display-message -p '#S')
window=$(tmux display-message -p '#W')

# Log the session and window
echo "$window" > "$log_file"
echo "$session" > "$log_files"
