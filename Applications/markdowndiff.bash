#!/bin/bash

# Define the paths to the original and edited markdown files
original_file="$1"
edited_file="$2"

# Use the 'diff' command to compare the two files and store the output
diff_output=$(diff -u "$original_file" "$edited_file")

# Initialize a variable to store the current section
current_section=""

# Process the diff output
while IFS= read -r line; do
    case $line in
        ' '*) # Unchanged line
            if [ -n "$current_section" ]; then
                echo "$current_section$line"
            fi
            ;;
        '-'*) # Removed line
            current_section="- ${line#*-} "
            ;;
        '+'*) # Added line
            current_section="+ ${line#*+} "
            ;;
    esac
done <<< "$diff_output"

echo $diff_output
