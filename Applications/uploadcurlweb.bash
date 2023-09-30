echo -n "Indtast port: "
read port
webserver=70.34.205.132
bn=$(basename "$tpath")
mkdir -p ~/tmp/publicledger
php /svn/svnroot/Applications/key.php ledger print > ~/tmp/publicledger/$bn.ledger
scp ~/tmp/publicledger/$bn.ledger root@$webserver:~/$bn.ledger
ssh root@$webserver "ufw allow $port"
ssh root@$webserver "hledger-web --host=$webserver -f ~/$bn.ledger --port=$port"
