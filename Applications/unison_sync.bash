#!/bin/bash
bn=$(basename "$tpath")
if [ "$tpath" == "" ]; then
	exit
fi
lastsync=~/tmp/lastsync_$bn
if [ "justrun" == "" ]; then
	if [ -f $lastsync ]; then
		if [ -n "$(find $lastsync -mmin -1 2>/dev/null)" ]; then
			exit
		else
		  echo "File has not been modified in the last 1 minutes."
		fi
	fi
fi

UNISON_SERVER=localhost
if [ "$tpath" == "" ]; then
	exit
fi
bn=$(basename "$tpath")
pushd . >/dev/null
cd "$tpath"
echo -n "🔄🔄🔄 Synkroniserer... "
unison $tpath ssh://rsync@localhost/"$bn" -silent -auto -batch -prefer newer >/dev/null
touch $lastsync
echo "...";
popd >/dev/null
