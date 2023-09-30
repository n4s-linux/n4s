#!/bin/bash
if [ ! -f "$1" ]; then

#!/bin/bash
# Bash Menu Script Example

PS3='Ny PDF fil kilde: '
options=("Screenshot" "Uden bilag")
select opt in "${options[@]}"
do
    case $opt in
        "Screenshot")
        	scrot -s /tmp/ss.png
		convert /tmp/ss.png /tmp/ss.pdf
		mv /tmp/ss.pdf "$1"
		rm /tmp/ss.png
		break;
            ;;
        "Uden bilag")
		op=$(whoami)
		date=$(date +"%Y-%m-%d")
            echo "<b>Mangler bilag til '$1' konteret af $op d. $d</b>"|html2ps|ps2pdf /dev/stdin "$1"
		break;
            ;;
        "Option 3")
            echo "you chose choice 3"
            ;;
        "Quit")
            break
            ;;
        *) echo invalid option;;
    esac
done



fi
input=$1
uid=""
base=$(basename "$1")
tf=~/.cache_acc
mkdir -p "$tf"
chmod 777 "$tf"
tmp="$tf/T-$base$uid"
mkdir "$tmp/"
chmod 777 "$tmp"
rm "$tmp/Ledger.orig" 
=======
uid=$(uuidgen)
tmp="/tmp/$1_$uid"
rm -rf "$tmp"
mkdir -p "$tmp"
rm "$tmp/Ledger.orig" 2>/dev/null
mds=$(md5sum "$1")
if [ "$2" = "cat" ]; then
	if [ -f "$tmp/$1-$mds" ]; then
		if [ -z "nocache" ]; then
			echo ";Fil hentet via PDF CAT (cache), filnavn: $1"
			cat "$tmp/$1-$mds"
			exit
		fi
	fi
fi
#echo pdftk "\"$1\"" unpack_files output "\"$tmp/\""
pdftk "$1" unpack_files output "$tmp"
#echo pdftk "'$1'" unpack_files output "'$tmp'"
if [ "$2" = "cat" ]; then
	if [ ! -f "$tmp/Ledger" ]; then
		echo "; Ingen kontering i $1"
		(echo "; Ingen kontering i $1") > "$tmp/$1-$mds"
	else
		echo "; Fil hentet via PDF CAT, filnavn : $1"
		cat "$tmp/Ledger"
		(cat "$tmp/Ledger") > "$tmp/$1-$mds"
	fi
	exit
elif [ "$2" = "unaccounted" ]; then
	if [ ! -f "$tmp/Ledger" ]; then
		/svn/svnroot/Applications/editpdfmeta.bash "$1"
	fi
	exit
fi

xpdf "$1" 2>/dev/null&
pid=$!
echo $pid > "$tmp/pid"
original="$tmp/original"
if [ ! -f "$original" ]; then
	cp "$1" "$original"
fi
#ls "$original" -l
cp "$tmp/Ledger" "$tmp/Ledger.orig"
if [ ! -f "$tmp/Ledger" ]; then
	#(echo -n $(date +"%Y-%m-%d") "$1"
	#) > "$tmp/Ledger"
	php /svn/svnroot/Applications/similar.php "$1" > "$tmp/Ledger"
fi
EDITOR="gvim --nofork"
if [ ! -f /var/www/webinput ]; then
	$EDITOR "$tmp/Ledger"
else
	cp /var/www/webinput "$tmp/Ledger"
	unlink /var/www/webinput
fi
cat "$tmp/Ledger"|md5sum > "$tmp/md5"
	echo -n $(date "+%Y-%m-%d %H:%M") ": " > "$tmp/history1"
	echo $(whoami) "@" $(hostname) >> "$tmp/history1"
	diff "$tmp/Ledger.orig" "$tmp/Ledger" >> "$tmp/history1"
	echo "<hr>" >> "$tmp/history1"
	cat "$tmp/history" >> "$tmp/history1"
	cp "$tmp/history1" "$tmp/history"
#cat "$tmp/history"
rm ~/archive.tmp
cp "$original" "$original.bak"
pdftk "$original.bak" attach_files "$original" "$tmp/Ledger" "$tmp/md5" "$tmp/history" output ~/archive.tmp
#rm "$1"
cp ~/archive.tmp "$1"
kill $pid 2>/dev/null
echo we went here
