function tagcount() {
	tag="$1"
	cd ~/regnskaber/crm/.tags
	grep "#$tag" *|grep -v .diff|grep -v ✔|wc -l
}
(
echo "# Vi er ansvarlige for"
echo -n "* Virksomhedskunder: "; tagcount cvr
echo -n "* Momser: "; tagcount moms
echo -n "* Årsregnskaber: "; tagcount moms
echo -n "* Lønninger: "; tagcount månedsløn
echo -n "* Lønsumsafgifter: "; tagcount lønsum
echo -n "* Igangværende sager i alt: ";grep -Ril \#cvr *|grep -v diff|xargs cat|grep ^#|grep -v Logins|grep -v Pligter|grep -v Mapper|grep -v Historik|grep -v Stamdata|grep -v ✔|wc -l

echo -n "* L1 opgaver: "; tagcount L1
echo -n "* L2 opgaver: "; tagcount L2 
echo -n "* L3 opgaver: "; tagcount L3
echo -n "* Øvrige opgaver: "; tagcount opgave

echo -e "\n# Løste subtasks"
seq 0 6 |while read seq
do
fm=$(date --date="-$seq month" +%Y-%m)
echo -n "* Løst $fm: "; grep "✔ @" *|grep -v $fm|grep ">"|grep .diff|wc -l
done
)|glow --pager /dev/stdin
