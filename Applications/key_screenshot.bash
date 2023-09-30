#!/bin/bash
gnome-screenshot -a -f /tmp/ss.png;cat /tmp/ss.png|base64|xclip -select clipboard
