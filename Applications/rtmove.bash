source ~/tmp/.rtcred
grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b" -h /data/regnskaber/transactions_crm/.tags/*|sort|uniq|grep -v olsenit@gmail.com|grep -v olsensrevision|grep -v 0lsen|grep -vi scope|while read mail
do
id=$(rt ls "status='new' and Owner = 'Nobody' and Requestor like '$mail'" -i)
rt edit $id set Queue=Revision
rt edit $id set Owner='Nobody'
done

