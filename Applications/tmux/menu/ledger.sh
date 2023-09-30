#!/usr/bin/env bash
#
#   Copyright (c) 2021: Jacob.Lundqvist@gmail.com
#   License: MIT
#
#   Part of https://github.com/jaclu/tmux-menus
#
#   Version: 1.1 2021-11-11
#
#   Main menu, the one popping up when you hit the trigger
#
#   There are three types of menu item lines:
#   1) An item leading to an action
#       "Description" "in menu shortcut key" " action taken when it is triggered"
#   2) Just a line of text
#       "Some text to display" "" ""
#   3) Separator line
#       ""
#   All but the last line in the menu, needs to end with a continuation \
#   Whitespace after thhis \ will fail the menu!
#

CURRENT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

SCRIPT_DIR="$(dirname "$CURRENT_DIR")/scripts"

echo "$SCRIPT_DIR/utils.sh"

source "/svn/svnroot/Applications/tmux/menu/scripts/utils.sh"


tmux display-menu \
     -T "#[align=centre] n4s menu " \
     -x $menu_location_x -y 0 \
     \
     "    Menu regnskab"    m  "splitw -bf -l 9 bash /svn/svnroot/Applications/tmux/fzfmenu.bash"     \
     "    Journal"  n  "new-window bash /svn/svnroot/Applications/start.bash business"   \
     "    Åbn regnskab"  å  "new-window bash /svn/svnroot/Applications/start.bash regnskab"   
