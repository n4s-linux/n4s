pushd  .
# Description: Will open up a temporary dropbox wormhole to a dropbox folder - where the share will selfdestruct when the task is done
right now it has to be terminated manually, but how can we determine if the task is done ?
 function ctrl_c() {
if [ "$uid" != "" ]; then
	echo deleting folder - $uid - , thanks for trying me
	rm -r ~/Dropbox/unison/"$uid"
else
	echo caught ctrl-c
fi
popd
exit
}

file="$(find ~/Dropbox -type d|fzf --header='Vælg mappe')"
echo file=$file
uid=$(uuidgen|cut -c1-8)
bn="$(basename "$file")"
uid="$bn-$uid"
echo uid=$uid
mkdir -p ~/Dropbox/unison
cd ~/Dropbox/unison
mkdir "$uid"
trap ctrl_c INT
while true; do 
	echo unison -batch -times=true "$file" "$uid"
	unison -batch "$file" "$uid"
	sleep 60
done
popd
