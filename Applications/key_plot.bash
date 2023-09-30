if [ "$measure" == "" ]; then
	measure=amount
fi
if [ "$title" == "" ]; then
	title="$1"
fi
flag="$2"
if [ "$flag" == "" ]; then
	flag=$(echo -e "yearly\nmonthly\nquarterly\nweekly\ndaily"|fzf )
fi
noend=1 php /svn/svnroot/Applications/key.php ledger -E -j reg $1 --$flag --plot-amount-format="%(format_date(date, '%Y-%m-%d')) %($measure)\n" > ~/tmp/ledgeroutput1.tmp
sed -i '$ d'  ~/tmp/ledgeroutput1.tmp
(cat <<EOF) | gnuplot
  set terminal dumb 175 55
$png
set autoscale
  #set xtics nomirror scale 0 center
  #set ytics add ('' 0) scale 0
  set border 3
  set grid ytics
  set title "$LEDGER_BEGIN $LEDGER_END $title"
  set ylabel "Time"
  set xlabel "Money"
     set yrange [] reverse

set timefmt "%Y-%m-%d"
set format x "%m"
     set boxwidth 0.9 relative
     set style fill solid 1.0
plot "~/tmp/ledgeroutput1.tmp" using 2:xticlabels(strftime('%Y', strptime('%Y-%m-%d', strcol(1)))) notitle linecolor rgb "blue" with boxes
EOF
