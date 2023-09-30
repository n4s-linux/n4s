#!/bin/bash
tail -f ~/.olsen.log&
php /svn/svnroot/Applications/tidsreg_add.php tail&
tail -f ~/Dropbox/"Olsens Revision ApS/".olsen*.log

