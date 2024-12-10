file="$1"
bash /svn/svnroot/Applications/mkd.bash "$1" sections|while read section
do
bash /svn/svnroot/Applications/mkd.bash "$1" getsection "$section"
done
