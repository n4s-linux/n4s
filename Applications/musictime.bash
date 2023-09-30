hour=$(date +%H)
port=8888 #reverse shell fra desktop computer
playnow="$1"
if [ "$playnow" == "" ]; then
	playnow=$( ls /svn/svnroot/Audio/*.mp3|fzf)
fi
bn="$(basename "$playnow")" 
if [ -f "$playnow" ]; then
	rsync /svn/svnroot/Audio ssh://localhost:~/ -Pav -e "ssh localhost -p$port"
	ssh -p$port localhost "killall mpg123;mpg123 ~/.audio/'$bn' " < /dev/tty
fi
