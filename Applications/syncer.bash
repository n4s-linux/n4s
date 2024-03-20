if [  "$1" == "" ] || [  "$2" == ""  ]; then
	echo uage: syncer.bash username host
	exit
fi
user="$1"
host="$2"
mkdir -p ~/.unison
ssh -q -o "BatchMode=yes" $user@$host exit
if [ "$?" != 0 ]; then
	ssh-copy-id $user@$host
fi
while true
do
unison -contactquietly -prefer newer -ignorenot "Name .*" -times=true -confirmbigdel=false -batch ~/unison/ ssh://$user@$host/unison 2>/dev/stdout|
grep -v ^"Looking for"|
grep -v "Reconciling changes"|
grep -v ^"Nothing to do:"|
grep -v "Waiting for changes from server"

sleep 2
echo -n .
done
