source ~/tmp/.rtcred
date=$(date +%Y-%m-%d)
rt ls "LastUpdatedBy = 'joo' and Resolved = '$date'" -f id,Subject|while read i
do
echo -n $(date +%Y-%m-%d)
echo -ne "\t"
echo $i
done
