mkdir -p ~/tmp/vitouch
cd ~/tmp/vitouch
if [ "$1" == "" ]; then
bash $(find * -maxdepth 1 -type f -mtime -1 -printf "%TH:%TM %p\n"|sort -r|grep -v ".tags"|fzf |awk '{print $2}')
else
find * -maxdepth 1 -type f -mtime -1 -printf "%TH:%TM %p\n"|sort 
fi
