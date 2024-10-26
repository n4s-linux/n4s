source /opt/rt5/var/.rtcred




cat /opt/rt5/var/vouchermails|sort|uniq|grep -v olsenit@gmail.com|grep -v olsensrevision|grep -v 0lsen|while read mail
do
#id=$(rt ls "Queue != 'Vouchers' and Queue != 'JO' and Queue != 'Revision' and status='new' and Owner = 'Nobody' and Requestor like '$mail'" -i)

id=$(rt ls "(Queue = 'General' or Queue = 'Spam') and Requestor like '$mail'" -i)
if [ "$id" != "" ]; then
rt edit $id set Queue=Vouchers
fi
done





cat /opt/rt5/var/notimails|sort|uniq|grep -v olsenit@gmail.com|grep -v olsensrevision|while read mail
do
#id=$(rt ls "Queue != 'Notifications' and Queue != 'Vouchers' and Queue != 'JO' and Queue != 'Revision' and status='new' and Owner = 'Nobody' and Requestor like '$mail'" -i)


id=$(rt ls "(Queue = 'General' or Queue = 'Spam') and Requestor like '$mail'" -i)
if [ "$id" != "" ]; then
rt edit $id set Queue=Notifications
fi
done



grep -E -o "\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}\b" -h /data/regnskaber/transactions_crm/.tags/*|sort|uniq|grep -v olsenit@gmail.com|grep -v olsensrevision|grep -v 0lsen|while read mail
do
#id=$(rt ls "Queue != 'Revision' and status='new' and Owner = 'Nobody' and Requestor like '$mail'" -i)
id=$(rt ls "(Queue = 'General' or Queue = 'Spam') and Requestor like '$mail'" -i)
if [ "$id" != "" ]; then
rt edit $id set Queue=Revision

fi
done

cat /opt/rt5/var/privmails|sort|uniq|grep -v olsensrevision|while read mail
do
id=$(rt ls "(Queue = 'General' or Queue = 'Spam') and Requestor like '$mail'" -i)
if [ "$id" != "" ]; then
rt edit $id set Queue=JO
fi
done
