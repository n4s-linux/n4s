pushd . 2>/dev/null
sec=$(head /data/regnskaber/.reviewed)
echo reviewed $sec
start=$(date +"%Y-%m-%d %H:%M" -d "UTC 1970-01-01 $sec secs")
echo start=$start
(
cd /svn/svnroot;git log  --since "$start" -p;cd /data/regnskaber
git log  --since "$start" -p
)|vi -

echo -n " Vil du godkende denne chunk er reg ok ? (jn): "
read jn
if [ "$jn" == "j" ]; then
	date +%s > /data/regnskaber/.reviewed
fi
popd 2>/dev/null

