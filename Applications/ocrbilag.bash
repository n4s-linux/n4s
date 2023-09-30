#!/bin/bash
if [ -f "./.ocr/$1.txt" ]; then 
cp "./.ocr/$1.txt" /svn/svnroot/tmp/ocr.tmp
else
abbyyocr9 -if "$1" -f Text -of "./.ocr/$1.txt" -of /svn/svnroot/tmp/ocr.tmp
fi

#input til specifikke OCR applets: Tekst-fil med fuld ocr af hele dokumentet til grepning ($1), Original PDF-fil ($2)
ls $SVNROOT/Applications/learnedocr/*.bash|while read i
do
echo "Prøver at godkende med script: $i"
bash $i /svn/svnroot/tmp/ocr.tmp "$1"
done
