style=/svn/svnroot/Libraries/ubl/UBL_Visning/InvoiceHTML.xsl
input="$1"
xmlto -x "$style" html "$input" -o ~/tmp --skip-validation
