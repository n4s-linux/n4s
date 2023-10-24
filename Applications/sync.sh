echo -n Indtast oldserver ip: 
read oldserver

rsync -rzvpa $oldserver:/home /
rsync -rzvpa $oldserver:/svn /
rsync -rzvpa $oldserver:/data /
rsync -rzvpa $oldserver:/var/spool/cron /
