#!/bin/bash

# File to write the active session name
output_file=~/tmp/.activesession

# Ensure the temporary directory exists
mkdir -p ~/tmp

# Check if tmux is installed
if ! command -v tmux &>/dev/null; then
    echo "Error: tmux is not installed or not in PATH." >&2
    exit 1
fi

# Get the currently active tmux session name and extract only the portion before a colon using sed
active_session=$(tmux display-message -p '#S' 2>/dev/null | sed 's/:.*//')

# Check if the session name was retrieved
if [ -z "$active_session" ]; then
    echo "Error: Unable to retrieve the active tmux session name." >&2
    exit 1
fi

# Write the active session name to the file
echo "$active_session" > "$output_file"

