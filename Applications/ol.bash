#!/bin/bash
uriencode() {
  s="${1//'%'/%25}"
  s="${s//' '/%20}"
  s="${s//'"'/%22}"
  s="${s//'#'/%23}"
  s="${s//'$'/%24}"
  s="${s//'&'/%26}"
  s="${s//'+'/%2B}"
  s="${s//','/%2C}"
  s="${s//'/'/%2F}"
  s="${s//':'/%3A}"
  s="${s//';'/%3B}"
  s="${s//'='/%3D}"
  s="${s//'?'/%3F}"
  s="${s//'@'/%40}"
  s="${s//'['/%5B}"
  s="${s//']'/%5D}"
  printf %s "$s"
}
function translate() {
if [ "$1" == "7500 - Outsourcing" ]; then
	echo "7500 - Assistance"
	return
fi
echo "$1"
return
}

function po() { #// print offer
if [ "$2" == "" ];  then
opath=$(select_db)
else
opath="$2"
fi
#echo "opath=$opath id=$1"
#return
echo "<meta charset=utf8><p align=right><img src='https://olsensrevision.dk/wp-content/uploads/elementor/thumbs/olsens-onf38ivvwko6z6ujk7ubpjc3t0n8teweq5uwfuvuhs.png'></p><p align=center><h3>Status på din sag hos Olsens Revision ApS</h3></p>" > /tmp/currap.html
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">' >> /tmp/currap.html
#echo "<h1><p align=center>" >> /tmp/currap.html;
#cat ~/$opath/$1/5000" -"* >> /tmp/currap.html
#echo "</p></center></h1></u>" >> /tmp/currap.html
echo "<table class=table-striped valign=top><tr vlign=top><td valign=top>" >> /tmp/currap.html;
pushd . >/dev/null
cd ~/$opath/$1
y=0
ls|grep -v History|sort -n|while read i;do
	echo "i: '$i'" >> /tmp/debug.log
	if [ "$y" = "12" ]; then
		newrow="1"
		y=0;
	else
		newrow="0"
		y=$((y+1))
	fi
	if [[ ! -s "$i" ]]; then
		continue
	fi
	echo "<table class=table border=1 width=100%><tr valign=top>" >> /tmp/currap.html
	if [ "$i" == "6000 - Behovsliste" ] ; then
		yellow_begin="bgcolor=yellow "
		pre_yellow=" style='background-color:yellow' "
	else
		yellow_begin=""
		pre_yellow=""
	fi
	translated_i=$(translate "$i")
	(echo "<td valign=top width=350  $yellow_begin><div style='width: 350'>$translated_i</div></td><td valign=top style='width: 550px' with=550 $yellow_begin><div style='width: 550'><pre $pre_yellow>";cat "$i"|sed 's/^/  /'; echo " </pre></div></td>") >> /tmp/currap.html
	echo "</tr></table>" >> /tmp/currap.html;
	if [[ "$newrow" == "1" ]]; then
		echo "</td><td valign=top>" >> /tmp/currap.html
	fi
done
popd > /dev/null
echo "</td></tr></table>" >> /tmp/currap.html;
echo '<hr><center>Olsens Revision ApS, Fortunstræde 1, 2., 1065 København K, Tlf. 2586 4573</center><P style="page-break-before: always">' >> /tmp/currap.html
#echo "History:<br><pre>" >> /tmp/currap.html
#cat ~/$opath/$1/"100000 - History" >> /tmp/currap.html
w3m -dump /tmp/currap.html|less




}
export logfile_o="/var/log/olsen"


function logecho() {
        #echo $1 2>/dev/null
        echo $(date "+%Y-%m-%d %R") $(echo -n "$1 " )  >> "$logfile_o" 2>/dev/null
        if [ -f "$session_history" ]; then
                echo $(date "+%Y-%m-%d %R") $(echo -n "$1 " )  >> $session_history
        fi  

}



function so {
opath=$(select_db)
	if [ "$1" = "" ]; then
		echo "Kræver argument"
		return
	fi
	pushd . >/dev/null
	cd /data/ol/"$opath"/
a=$(
(
	grep -i "$1" . -R|grep -v History|grep -v template|grep -v completed
	grep -i "$1" . -R|grep History
)|fzf --no-mouse --cycle)
eo $(printf "%.0f\n" $(printf "%s" "$a"|sed  's/^[^0-9]*//;s/[^0-9].*$//')) "$opath"
	popd > /dev/null
}

function eo {
rm ~/tmp/hist.old -rf
rm ~/tmp/hist.new -rf
	opath="$2"
	if [ "$1" == "" ]  || [ "$opath" == "" ]; then
		echo "Kræver argument"
		return
	fi
	pushd . >/dev/null

while true
do
	cd /data/ol/"$opath"/$1
	if [ -f ".beforeedit" ]; then
		source .beforeedit
	fi
fn=$(echo -n $(basename $PWD)-;echo -n $((echo $PWD;cat 1000\ -\ Kunderef ) 2>/dev/null|md5sum|awk '{print $1}');echo .html)
	ls ../template|while read i; do if [ -f "$i" ]; then : else touch "$i"; fi; done
		title "b4inside"
		(echo -e "Exit\nBooking\nPush\nPrint\nCompleted\nTidsreg\nKundelink";ls|sort -h |while read i
		do 
			echo -ne "$i\t"; 
			echo -n $(head -n1 "$i"); 
			wc=$(wc -l "$i"|awk '{print $1}');
			if [ "$wc" -gt 1  ]; then 
				echo -n " (flere linier)";
			fi;
			echo;
		done|column -ts $'\t')	| fzf --no-mouse --cycle --reverse -i -e >~/tmp/valg
		x=$(cat ~/tmp/valg|awk '{print $1}')
		valg=$(echo -n $(cat ~/tmp/valg))
		re=$(echo -n $(cat /data/ol/"$opath/$1/1000 - Kunderef" 2>/dev/null) 2>/dev/null) >/dev/null
		taskname=$(echo -n $(cat /data/ol/$opath/$1/"5000 - "* 2>/dev/null) 2> /dev/null) >/dev/null
		logecho "eo $re - $taskname - $valg"
		#title "(done) eo $re - $taskname - $valg"

		if [ "$x" == "Exit" ]; then
			return;
		fi
		if [ "$x" == "Kundelink" ]; then
			re=$(echo -n $(cat /data/ol/"$opath/$1/1000 - Kunderef"))
			md=$(echo -n "$re"|md5sum |awk '{print $1}')
			echo "https://jodb.mikjaer.com/svnroot/Applications/olt.php?ref="$(uriencode "$re")"&md5="$(uriencode "$md")
			return  ; 
		fi
		if [ "$x" == "Tidsreg" ]; then
		fn=/data/ol/c/"$(echo -n $(cat /data/ol/$opath/$1/1000\ -\ Kunderef |awk '{print $1}'))/4500\ -\ CVR"
			if [ -f "$fn" ]; then
				cvr=$(echo -n $(cat "$fn"))
				echo "Kører uc på $cvr"
 	pushd . >/dev/null;cd /svn/svnroot/Applications/Uniconta;dotnet run $cvr < /dev/tty ;popd >/dev/null
			else
				echo "Kunde $1 har ikke noget CVR"
				echo "Tryk ENTER for at fortsætte og indsætte det"
				read < /dev/tty
				vi "$fn"
				cvr=$(echo -n $(cat "$fn"))
				pushd . >/dev/null;cd /svn/svnroot/Applications/Uniconta;dotnet run $cvr < /dev/tty;popd >/dev/null

			fi
			return	;
		fi
		if  [ "$x" == "Booking" ] ; then
			taskname=$(cat /data/ol/$opath/$1/"5000 - "*)
			company=$(cat /data/ol/$opath/$1/"1000 - "*)
			person=$(cat /data/ol/$opath/$1/"3000 - "*)
			histfile=$(ls /data/ol/$opath/$1/"100000 - History");
			echo -n "Indtast antal timer: "
			read timer < /dev/tty
			now=$(echo -n $(date +%Y-%m-%d))
			echo -n "Startdato ($now): "
			read start </dev/tty
			if [ -z "$start" ]; then
				start="$now"
			fi
			taskname=$(echo -n "$company - $person - $taskname";echo " - eo $1 t")
			php /svn/svnroot/Applications/bookit.php $timer $start "$taskname" "$histfile"
			#bookit.php [hours] [start] [Description] [HistFile]"
			return;
		fi
		if [ "$x" == "Completed" ]; then
			echo -n "Indtast complete besked: "
			read msg
			mkdir -p /data/ol/"$opath/completed"
			mv /data/ol/"$opath/$1/" /data/ol/"$opath/completed/"
			add=$(echo "$(date +%Y-%m-%d): Completed - $msg")
			sed -i "1s/^/$add /" /data/ol/"$opath/completed/$1/100000 - History"
			echo "$add" >> ~/.history
			#mv ~/"$opath/completed/$1.completed" ~/"$opath/completed/$1.completed"
			popd >/dev/null
			return
		fi
		if [ "$x" == "Push" ]; then
			echo "fn: $fn"
			exit
		fi
		if [ "$x" == "Print" ]; then
			# 1 = id, 2 = opath
			po "$1" "$opath"
			popd >/dev/null
			return
		fi
		fn="$(ls /data/ol/"$opath"/$1/"$x -"*|grep -vi modstridende|head -n1)"
		bn=$(basename "$fn")
		cp "$fn" ~/tmp/old
		title "$fn"
		vim "$fn"
		cp /data/ol/"$opath"/$1/"100000 - History" ~/tmp/hist.old
		pushd . 2>/dev/null >/dev/null
		cd /data/ol/"$opath/$1"
		if [ -f ".afteredit" ]; then
			source .afteredit.bash
		fi
		popd 2>/dev/null >/dev/null
		file1=$(md5sum "~/tmp/old"|awk '{print $1}')
		file2=$(md5sum "$fn"|awk '{print $1}')
		echo "md5: $file1 $file2" > ~/tmp/md5
		if [ "$file1" != "$file2" ]; then
			echo  $(date +%Y-%m-%d ) " - Ændret $bn:" > ~/tmp/hist.new
#			diff -u ~/tmp/old "$fn"|sed 's/^/  /' >> ~/tmp/hist.new
			diff -u ~/tmp/old "$fn"  >> ~/tmp/hist.new
			logecho "Edited $re - $taskname / $bn"

		fi
		cat ~/tmp/hist.new ~/tmp/hist.old > /data/ol/"$opath"/$1/"100000 - History" 2>/dev/null
		cat ~/tmp/hist.new >> ~/.history 2>/dev/null
		rm -f ~/tmp/old ~/tmp/hist.old 
		rm -f ~/tmp/hist.new
#		popd > /dev/null #this fucked me up
done
}



title() {
    # If the length of string stored in variable `PS1_BAK` is zero...
    # - See `man test` to know that `-z` means "the length of STRING is zero"
    if [[ -z "$PS1_BAK" ]]; then
        # Back up your current Bash Prompt String 1 (`PS1`) into a global backup variable `PS1_BAK`
        PS1_BAK=$PS1 
    fi  

    # Set the title escape sequence string with this format: `\[\e]2;new title\a\]`
    # - See: https://wiki.archlinux.org/index.php/Bash/Prompt_customization#Customizing_the_terminal_window_title

    TITLE="\[\e]2;$@\a\]"
        TITLE="\[\e]2;$STY\a\]"

    # Now append the escaped title string to the end of your original `PS1` string (`PS1_BAK`), and set your
    # new `PS1` string to this new value
    export PS1=${PS1_BAK}${TITLE}
}
function ol() {
	pushd . >/dev/null
	if [ "$1" == "" ]; then
    mkdir -p ~/tmp/
		chmod 777 ~/tmp/list
		opath=$(select_db)
		if [ -L /data/ol/"$opath" ] && [ -d /data/ol/"$opath" ]; then
			echo database $opath findes ikke
			return
		fi  
	else
	fn=/data/ol/"$1"
	if [ ! -d "$fn" ] && [ ! -L "$fn" ]; then
		echo database" '$1' " findes ikke
		return
	fi
	opath="$1"
	touch /data/ol/$opath/.viewed
	if [ ! -f /data/ol/"$opath/template/.headers" ]; then
		opath="$opath" olh 
	fi
	fi
	logecho "ol $opath"
	if [ "$2" == "" ]; then
		id=$((opath=$opath bash +x /svn/svnroot/Applications/listoffers.bash|fzf --cycle --reverse -e > ~/tmp/list);cat ~/tmp/list|awk -v FS="│" '{print $3}'| sed 's/[^0-9]*//g')
	else
		id=$((opath=$opath bash +x /svn/svnroot/Applications/listoffers.bash "$2"|fzf --cycle --reverse -e > ~/tmp/list);cat ~/tmp/list|awk -v FS="│" '{print $3}'| sed 's/[^0-9]*//g')
	fi
	title "eo $(echo -n $(cat /data/ol/$opath/$id/1000\ -*))"
	echo "eo $id $opath" >> ~/.last_ol
	chmod +x ~/.last_ol
	eo $id $opath
	popd >/dev/null
	#clear
}
