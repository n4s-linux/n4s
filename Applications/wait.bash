path=/data/regnskaber
while true
do
	curpath=$(inotifywait "$path/" --format '%w' -r -q)
done
