function pdfpextr()
{
    # this function uses 3 arguments:
    #     $1 is the first page of the range to extract
    #     $2 is the last page of the range to extract
    #     $3 is the input file
    #     output file will be named "inputfile_pXX-pYY.pdf"
    gs -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dSAFER \
       -dFirstPage=${1} \
       -dLastPage=${2} \
       -sOutputFile=${3%.pdf}_p${1}-p${2}.pdf \
       ${3}
}
echo "fil $1"
rm -rf /svn/svnroot/tmp/pdf/
mkdir /svn/svnroot/tmp/pdf/
cp "$1" /svn/svnroot/tmp/pdf/input.pdf
cd /svn/svnroot/tmp/pdf
pdfpextr 1 1 input.pdf
rm input.pdf
echo "<title>Olsen-dokumentviser</title><body><h1>Bilagsnr. $2</h1><br>" > /svn/svnroot/tmp/pdf/output.html
ls *.pdf|while read i
do
	convert -interlace none -density 300 -quality 100 -colorspace RGB "$i" "$i.jpg"
	echo "<a href=$i.jpg><img height=100% width=100% border=0 src=$i.jpg></a><br>" >> output.html
done
echo "</body>" >> output.html
rm *.pdf
#convert -colorspace RGB -interlace none -density 300x300 -quality 100 $1 /svn/svnroot/tmp//file.jpg
