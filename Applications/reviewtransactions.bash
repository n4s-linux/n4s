find /data/regnskaber/*/ -name \*.trans|while read filename
do
	php /svn/svnroot/Applications/reviewfile.php "$filename"
done
