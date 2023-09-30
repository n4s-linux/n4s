#!/bin/bash
#markdown $tpath/.tags/$1>~/tmp/md.html;elinks -dump -dump-color-mode 1 ~/tmp/md.html
diff  $tpath/.tags/."$1".cmp $tpath/.tags/"$1"
