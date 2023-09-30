# Kør udfra genvej

mkdir -p /data/regnskaber/.genveje
tpath=/data/regnskaber/.genveje/$1
cd $tpath&& (LEDGER_DEPTH=999 missing_vouchers=1 LEDGER_END=2099/12/31 LEDGER_BEGIN=1970/1/1 php /svn/svnroot/Applications/key.php ledger csv Udgifter|php /svn/svnroot/Applications/csvtomd.php;exit)
