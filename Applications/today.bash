export mdoutput=markdown
if [ "$1" == "terminal" ]; then
	export mdoutput=terminal
fi
cd ~/regnskaber/stuff/.tags;grep $(date +%Y-%m-%d) * -l|grep -v .diff|while read file; do cat "$file"|php /svn/svnroot/Applications/markdowngrepexpand.php "$file"; done|grep $(date +%Y-%m-%d)
cd /data/regnskaber/transactions_crm/.tags;grep $(date +%Y-%m-%d) * -l|grep -v .diff|while read file; do cat "$file"|php /svn/svnroot/Applications/markdowngrepexpand.php "$file"; done|grep $(date +%Y-%m-%d)
