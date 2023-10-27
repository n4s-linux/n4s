style=InvoiceHTML.xsl
input=Examples/OIOUBL_Invoice.xml
output=~/tmp/out.html
    xmlto -x $style html "$input" --skip-validation -o ~/tmp


