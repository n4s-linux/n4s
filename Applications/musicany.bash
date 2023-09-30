hour=$(date +%H)
port=8888 #reverse shell fra desktop computer
playnow=/svn/svnroot/Audio/$hour.mp3 
if [ -f $playnow ]; then
	scp -P$port $playnow localhost:~/.audio/ 
	ssh -p$port localhost music123 ~/.audio/$hour.mp3 < /dev/tty
fi
