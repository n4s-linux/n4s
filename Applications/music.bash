port=$1
freq=$2
duration=$3
echo ssh -n localhost -p$port ffplay -f lavfi -i "sine=frequency=$2:duration=$3" -autoexit -nodisp
ssh -n localhost -p$port ffplay -f lavfi -i "sine=frequency=$2:duration=$3" -autoexit -nodisp
#rsync -p /svn/svnroot/Audio localhost:
