of=~/tmp/sog$(date +%s)
cd ~/regnskaber/
echo -n Søgefrase:
read mail
(echo Resultat
grep "$mail" */.tags/*)|grep -v ✔|grep -v .diff|grep -v ^Resultat|sed 's/\/.tags//g'|sed 's/transactions_//g'|fzf --multi > "$of"
fn=$(cat "$of"|awk '{print $1}')
tag=$(cat "$of"|awk '{print $2}')
value=$(cat "$of"|awk '{print $3}')
other=$(cat "$of"|awk '{print $4}')
echo -e "$fn\n$tag\n$value\n$other"|fzf --tac 
read
