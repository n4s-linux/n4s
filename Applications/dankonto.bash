# Dette program kan hente skattekonto med tastselv kode - angiv som argv1 og 2


rm /tmp/cookie
rm -rf skat.html
export url="https://pdcs.skat.dk/dcs-atn-gateway/login/tsklogin?targetUrl=aHR0cHM6Ly93d3cuc2thdC5kay9mcm9udC9hcHBtYW5hZ2VyL3NrYXQvbnRzZT9fbmZwYj10cnVlJl9wYWdlTGFiZWw9QjgwMDExNTA3MTM2NjE4NTY1Nzk5NCZfbmZscz1mYWxzZQ==&userType="
ua="User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36"
curl -O /dev/stdout "$url" 2&>~/dankonto.html
a=$(cat ~/dankonto.html|sed -n -e '/token/,$p'|head -n2|tail -n1|awk '{print $1}')
a=$(echo "${a::-4}")
logintoken=$(echo "${a:7}")
cvr="$1"
tastselvKode="$2"
url="https://pdcs.skat.dk/dcs-atn-gateway/login/dologin"
echo "Loggind in...<br>" > skat.html
curl -H "$ua" -X POST --data-urlencode "cvr=$cvr" --data-urlencode "tastselvKode=$tastselvKode" --data-urlencode "logintoken=$logintoken" "$url" -c /tmp/cookie >> skat.html
echo "<hr>">> skat.html
echo "Redirecting to login_redirect<br>" >> skat.html
url="https://pdcs.skat.dk/dcs-atn-gateway/flow/login_redirect.jsp"
curl -c /tmp/cookie -H "$ua" -b /tmp/cookie "$url" >> skat.html
echo "<hr>" >> skat.html
echo "Redirecting to ntse<br>">>skat.html
url="https://www.skat.dk/front/appmanager/skat/ntse"
curl -c /tmp/cookie -H "$ua" -b /tmp/cookie "$url" >> skat.html
echo "<hr>" >> skat.html
echo "Trying to get skattekonto not sure about url<br>">>skat.html
url="https://www.skat.dk/front/appmanager/skat/ntse?_nfpb=true&_nfpb=true&_pageLabel=P800615071366186523918&_nfls=false"
sleep 3
curl -c /tmp/cookie -H "$ua" -b /tmp/cookie "$url" >> skat.html
echo "<hr>">>skat.html
url=$(cat skat.html|grep -v 1617453|egrep -o 'https?://[^ ]+'|tail -n1)
echo $url
url=$(echo "${url::-10}")
echo $url
curl -c /tmp/cookie -H "$ua" -b /tmp/cookie "$url" >> skat.html
url="https://skattekontoen.skat.dk/dms-front-web/dms_internet_nodecor.portal?_nfls=false&_nfpb=true&_pageLabel=dms_uc1307_page_pub_company"
curl -c /tmp/cookie -H "$ua" -b /tmp/cookie "$url" > skat.html
php /svn/svnroot/Applications/dankonto.php skat.html
