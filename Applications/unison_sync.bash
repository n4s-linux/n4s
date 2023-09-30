#!/bin/bash
exit
lastsync="$tpath"/.lastsync_$(whoami)
UNISON_SERVER=139.177.183.125
if [ "$tpath" == "" ]; then
	exit
fi
if [ ! -L "$tpath" ] && [ ! -d "$tpath"] ; then
	exit
fi
#unison stuff_test/ ssh://unison@$UNISON_SERVER/stuff_test -batch
bn=$(basename "$tpath")
pushd . >/dev/null
cd "$tpath"
echo -n "🔄🔄🔄 Synkroniserer... "
unison . ssh://unison@unison/"$bn" -silent -batch && date +%s > $lastsync && echo ✔✔✔ || echo cant sync
popd >/dev/null
