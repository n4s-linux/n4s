#!/bin/bash

# Get the current window title
full_title="$1"
max_width=8

# Truncate the title if it exceeds the max width
if [ ${#full_title} -gt $max_width ]; then
    truncated_title="${full_title:0:max_width}..."
else
    truncated_title="$full_title"
fi

# Output the truncated title
echo "#$truncated_title"
