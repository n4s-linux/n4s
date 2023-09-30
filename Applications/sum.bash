vi /tmp/sumtitle
title=$(cat /tmp/sumtitle)
vi /tmp/sum
cowsay "$(date +'%Y-%m-%d %H:%M') $title"
calc=$(echo "0";cat /tmp/sum|while read tal
	do
		tal=$(echo -n "$tal"|awk '{print $1}')
		if [ "$tal" != "" ]; then
			echo -n " + $tal "
		fi
	done
	)
calc=$(echo "$calc"|sed 's|+ -|-|g'|sed 's/^.\\+//g')
echo calc=$calc
fn=~/.data/sumz/$(echo -n $(cat /tmp/sumtitle|sed -e 's/[^A-Za-z0-9._-]/_/g'));
mkdir -p ~/.data/sumz

(
	echo "Input:"
	cat /tmp/sum|sed 's/^/  /'
	echo "Regnestykke: "
	echo $calc #|sed 's/^/  /'|sed "s/\+\ 0//g"|sed "s/0\ \+//g"
	echo
	echo Resultat:
	echo $calc|bc -l
) > "$fn";
cat "$fn"
echo "<meta charset=utf8><pre>$title" > /svn/svnroot/tmp/lastcow.html
cat "$fn"|cowsay -W80 >> /svn/svnroot/tmp/lastcow.html
echo "http://jodb.mikjaer.com/svnroot/tmp/lastcow.html" 
