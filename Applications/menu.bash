#!/bin/bash
exit
export NOLOGON=1 
source /svn/svnroot/aliases
source /svn/svnroot/tmp/curenv_$(whoami)>/dev/null
php $SVNROOT/Applications/menu.php
