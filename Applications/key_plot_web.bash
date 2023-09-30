rm /tmp/ledgeroutput1.tmp 2> /dev/null
LEDGER_DEPTH=999 ledger -B -f "$1" -j reg "$2" -M --collapse --plot-amount-format="%(format_date(date, '%Y-%m-%d')) %(amount)\n" > /tmp/ledgeroutput1.tmp
echo >> /tmp/ledgeroutput1.tmp
(cat <<EOF) | gnuplot
set terminal png font arial 14 size 900,350 background rgb 'gray'
set autoscale
set multiplot
  set xtics nomirror scale 0 center
  set ytics add ('' 0) scale 0
  set border 1
  set grid ytics
set yrange []
plot "/tmp/ledgeroutput1.tmp" using 2:xticlabels(strftime('%b', strptime('%Y-%m-%d', strcol(1)))) notitle linecolor rgb "light-turquoise", '' using 0:2:2 with lines font "Courier,8" offset 0,0.5 textcolor linestyle 0 notitle 
EOF

