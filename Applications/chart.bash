#!/bin/sh
konto="$1"

php /svn/svnroot/Applications/key.php ledger -j reg "$konto" -M --collapse --plot-amount-format="%(format_date(date, "%Y-%m-%d")) %(abs(quantity(scrub(display_amount))))\n" > ~/tmp/ledgeroutput1.tmp
cat ~/tmp/ledgeroutput1.tmp
exit
(cat <<EOF) | gnuplot
set terminal png 
  set style data histogram
  set style histogram clustered gap 1
  set style fill transparent solid 0.4 noborder
  set xtics nomirror scale 0 center
  set ytics add ('' 0) scale 0
  set border 1
  set grid ytics
  set title "Monthly $@"
  set ylabel "Amount"
  plot "~/tmp/ledgeroutput1.tmp" using 2:xticlabels(strftime('%b', strptime('%Y-%m-%d', strcol(1)))) notitle linecolor rgb "light-turquoise", '' using 0:2:2 with labels font "Courier,8" offset 0,0.5 textcolor linestyle 0 notitle
EOF

#rm ~/tmp/ledgeroutput*.tmp
