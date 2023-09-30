#!/bin/bash
title=$(tmux display-message -p '#W')
while true; do
	inotifywait "$tpath" 2>/dev/null > /dev/null
	tmux rename-window "... $title"	
	sleep 2
	tmux rename-window "$title"
 done
