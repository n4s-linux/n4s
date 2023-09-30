rev=
if [[ "$1" == "Indtægter"* ]]; then
        rev=reverse
fi
if [[ "$1" == "Egenkapita"* ]]; then
        rev=reverse
fi

echo >> /tmp/ledgeroutput1.tmp
(cat <<EOF) | gnuplot
  set terminal dumb 575 75
$png
  set title "'$1'"
  set ylabel "Amount"
  set xlabel "Tid"
      set xzeroaxis linetype 3 linewidth 2.5
set yrange [] $rev

plot "/tmp/ledgeroutput1.tmp"  using 2:xticlabels(strftime('%m-%d', strptime('%Y-%m-%d', strcol(1)))) notitle with lines 
EOF

