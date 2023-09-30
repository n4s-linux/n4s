#!/bin/bash
if [ -z "$tpath" ]; then
	echo "arkiverbilag kræver tpath"
	return
fi
mkdir -p "$tpath/vouchers/used"
pushd . >/dev/null
cd "$tpath/vouchers" || { echo "fejl kan ikke gå i vouchers" ; exit 1; }
find . -maxdepth 1 -size +0k -type f|while read file
do
	echo mv "$file" used/
	mv "$file" used/
done
popd >/dev/null
