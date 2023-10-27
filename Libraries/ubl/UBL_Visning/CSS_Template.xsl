<?xml version="1.0" encoding="utf-8"?>
<!--
******************************************************************************************************************		
		OIOUBL Instance Documentation	
		
		Title 		= CSS_Template.xsl
		Publisher 	= "Digitaliseringsstyrelsen"
		Creator 	= "Erik Ringøen"
		Created 	= 2017-05-12
		Modified    = $Date: 2017-05-31 09:40:32 +0200 (on, 31 maj 2017) $
		$Revision: 34182 $

******************************************************************************************************************
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template name="CSSReference">
		<style type="text/css">
body, h2, h1, h3, td, th, tr {
    font-family: arial, serif;
    font-size: 10pt;
    background-color: white;
    color: #000000;
}

h2 {
    font-weight: bold;
    margin: 0;
    font-size: 12pt;
}

h3 {
    margin: 0;
    font-size: 20pt;
}

table {
    border: 0;
    margin: 0;
    width: 100%;
}

th, td {
    vertical-align: top;
    text-align: left;
}

td.AlignRightTd {
    text-align: right;
    padding-left: 20px;
}

td.AlignRightTdAmountType {
    vertical-align: top;
    text-align: right;
    width: 20px;
}

/* HEADER */
h3.DocumentType {
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}

td.DocumentHeader {
    width: 10%;
    padding-top: 5px;
    padding-bottom: 5px;
    margin-bottom: 11px;
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}

td.DocumentHeaderInfo {
    white-space: nowrap;
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}

td.UBLLine {
    margin: 0;
    font-size: 8pt;
}

td.UBLHeaderTd {
    margin: 0;
    font-size: 8pt;
    width: 15%
}

hr {
    border: none;
    border-top: solid 7px lightblue;
}

/* ITEM LINES */
table.ItemsTable {
    border-collapse: collapse;
}

.oddrow {
    background-color: #DFE0E1;
    -webkit-print-color-adjust: exact;
}

.evenrow {
    background-color: white;
    -webkit-print-color-adjust: exact;
}

th.ItemLineHeader {
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}

th.ItemLineHeaderAmount {
    text-align: center;
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}

/* Tax and total */
hr.HrTaxTotalBorder {
    border: none;
    border-top: solid 2px lightblue;
}

td.TaxAndTotalText {
    white-space: nowrap;
    vertical-align: top;
    font-weight: bold;
}

td.TaxAndTotalTextBc {
    white-space: nowrap;
    font-weight: bold;
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}

td.TaxAndTotalValueBc {
    text-align: right;
    vertical-align: top;
    font-weight: bold;
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}

td.TaxAndTotalValueTypeBc {
    vertical-align: top;
    font-weight: bold;
    background-color: lightblue;
    -webkit-print-color-adjust: exact;
}
        </style>
	</xsl:template>
</xsl:stylesheet>
