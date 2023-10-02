startdate=2023-10-01
months=12
function momsdato() {
	dato=$1
	m=$(date --date="$date" +%m)
	if [ "$m" -lt 7 ]; then
		seq 1 12|while read seq
		do
			d=$(date --date="$dato + $seq months" +%Y-%m-%d)
			m=$(date --date="$dato + $seq months" +%m)
			if [ "$m" == "09" ]; then
				echo $d
				return			
			fi
		done
	else
		seq 1 12|while read seq
		do
			d=$(date --date="$dato + $seq months" +%Y-%m-%d)
			m=$(date --date="$dato + $seq months" +%m)
			if [ "$m" == "03" ]; then
				echo $d
				return			
			fi
		done
	fi
}
function hvermåned() {
seq 0 $months|while read i; do 
	dato=$(date --date="$startdate +$i months" +%Y-%m-01)
	ts=$(date +%s --date="$dato")
	begints=$(date --date="$6" +%s)
	endts=$(date --date="$7" +%s)
	if [ "$ts" -lt "$begints" ] || [ "$ts" -gt "$endts" ]; then
		continue;	
	fi
	txt=$1
	moms=$3
	belob=$2
	konto=$4
	mk=$5
	if [ "$moms" != "1" ]; then
		echo -e "$dato ⏲ $txt\n\t$konto  $belob\n\t$mk\n\n";
	else
		belob=$(echo "0.8*$belob"|bc -l)
		moms=$(echo "-0.2*$belob"|bc -l)
		if [[ "$konto" == *"Udgift"* ]]; then
			momskonto="Købsmoms"
		else
			momskonto="Salgsmoms"
		fi
		echo -e "$dato ⏲ $txt\n\t$konto  $belob\n\tPassiver:Moms:$momskonto  $moms\n\t$mk\n\n";
		momsdato=$(momsdato "$dato")
		echo -e "$momsdato ⏲ $txt (momsbetaling $dato)\n\tAktiver:Likvider:RevolutDKK  $moms\n\tPassiver:Moms:Momsafregning\n\n";
	fi
done
}
hvermåned Husleje 11844 0 Udgifter:Lokaleomkostninger:Husleje Aktiver:Likvider:"RevolutDKK" 2023-07-01 2099-12-31
hvermåned Revi-Salg -75000 1 Indtægter:Revi-salg Aktiver:Likvider:"RevolutDKK" 2023-07-01 2099-12-31
hvermåned Administration 3000 1 Udgifter:Administration:Diverse Aktiver:Likvider:"RevolutDKK" 2023-07-01 2099-12-31
hvermåned Direkte\ omkostninger 1500 1 Udgifter:Direkte omkostninger Aktiver:Likvider:"RevolutDKK" 2023-07-01 2099-12-31
hvermåned Renter 2000 0 Udgifter:Renteudgifter:Budgetteret Aktiver:Likvider:"RevolutDKK" 2023-07-01 2099-12-31
hvermåned Løn 30000 0 Udgifter:Personaleomkostninger:Budgetteret Aktiver:Likvider:"RevolutDKK" 2023-07-01 2099-12-31
