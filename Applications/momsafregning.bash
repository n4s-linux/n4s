#!/bin/bash
function f2i() {
  LC_ALL=C awk -- '
    BEGIN {
      for (i = 1; i < ARGC; i++)
        printf "%.0f\n", ARGV[i]
    }' "$@"
}
end=$(date +%Y-%m-%d --date="$LEDGER_END -1 day")
output=$tpath/.moms/$end-$(date +%Y-%m-%d)
bn=$(basename "$output")
mkdir -p $tpath/.moms
echo "Momskørsel $(date +%Y-%m-%d) af $(whoami)" >> $output
noend=1 php /svn/svnroot/Applications/key.php ledger bal --flat ^Passiver:Moms|while read line
do
	echo -e "\t$line" >> $output
done
echo "$end (.moms/$bn) Momsafregning $end" >> $tpath/.Momsafregning-$end.ledger
echo 'Passiver:Moms:Købsmoms
Passiver:Moms:Salgsmoms
Passiver:Moms:Moms af varekøb udland
Passiver:Moms:Moms af ydelser udland'|while read momskonto
do
	echo "$momskonto" >> $output
	balance=$(noend=1 php /svn/svnroot/Applications/key.php ledger bal ^$(echo "$momskonto"|sed 's/ /\./g' ) --depth=3|awk '{print $1}')
	if [ "$balance" == "" ]; then continue; fi
	konto=$(echo "$momskonto"|sed 's/ /./g')
	rev=$(echo $balance*-1|bc )
	rev=$(f2i "$rev")
	echo -n "Indtast beløb $momskonto ($rev):"
	read belob < /dev/tty
	if [ "$belob" == "" ]; then
		belob="$rev"
	fi
	echo -e "\t$momskonto  $belob" >> $tpath/.Momsafregning-$end.ledger
done
	echo -e "\tPassiver:Moms:Momsafregning:$end" >> $tpath/.Momsafregning-$end.ledger
	cat $tpath/.Momsafregning-$end.ledger >> $tpath/Momsafregning-$end.ledger
	echo -e "; Spec findes i .moms/$bn\n" >> $tpath/Momsafregning-$end.ledger
	rm $tpath/.Momsafregning-$end.ledger
