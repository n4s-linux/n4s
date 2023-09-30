uid=$(date +%s)
source /svn/svnroot/aliases
mkdir -p ~/tmp/
tmux paste-buffe|awk '{$1=$1};1'> ~/tmp/pastebuffer$uid

echo BUFFER START
cat ~/tmp/pastebuffer$uid
echo BUFFER_SLUT
echo -n "Indtast dit 🕊 (blip) : "
read bf
echo -n "Ønsker du at inkludere tmux klppebord (j/n): "
read tmuxinclude
db="$(ls -1td ~/regnskaber/*/.tags|sed 's/\/\.tags//g'|while read i; do basename "$i"; done|fzf -1 --header="Vælg database")"
sr "$db"
cd $tpath/.tags
tag="$(ls|grep -v .diff|fzf)"
echo -n "# $(date) 🕊 " > ~/tmp/blip.currenttag
echo -e "$bf\n\t\n" >> ~/tmp/blip.currenttag
cat "$tag" >> ~/tmp/blip.currenttag
if [ "$tmuxinclude" == "j" ]; then
	cat ~/tmp/pastebuffer$uid > ~/tmp/blip.currenttag
fi
cp ~/tmp/blip.currenttag "$tag"
echo -e "$date\tblip.bash 🕊: +$bf" >> $tag.diff
