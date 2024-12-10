#!/bin/bash

# Get the list of sessions
sessions=($(tmux list-sessions -F "#{session_name}"))

# Get the current session name
current_session=$(tmux display-message -p '#{session_name}')

# Find the index of the current session
current_index=0
for i in "${!sessions[@]}"; do
    if [[ "${sessions[i]}" == "$current_session" ]]; then
        current_index=$i
        break
    fi
done

# Calculate the next session index (wrap around if at the last session)
next_index=$(( (current_index + 1) % ${#sessions[@]} ))

# Switch to the next session
tmux switch-client -t "${sessions[$next_index]}"
