uid=$(date +%s)
flameshot gui -r > ~/Dropbox\ \(OlsensRevision\ ApS\)/Screenshots/ss_$uid.png
cd ~/Dropbox\ \(OlsensRevision\ ApS\)/Screenshots/
fn="$(ls -t|head -n1)"
link2=$(dropbox sharelink "ss_$uid.png")
link=$(echo "$link2"|sed 's/dl=0/dl=1/g')
echo link : $link2 kopieret til klippebord
echo "$link"|xclip -selection clipboard

