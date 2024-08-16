#!/bin/bash
pushd . >/dev/null
cd $tpath/.newvouchers
fil=$(ls *.*|fzf --header="Pick document")
popd >/dev/null
if [ "$fil" == "" ]; then
	echo nothing selected
	exit
fi
cp "$tpath/.newvouchers/$fil" ~/tmp/preview.pdf
