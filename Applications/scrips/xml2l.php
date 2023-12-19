<?php
$fn = $argv[1];
$data = file_get_contents($fn);
$data = XMLtoArray($data);
$type = "inv";
if (!isset($data['Invoice'])) {
	$data['Invoice'] = $data['CreditNote'];
	$data['Invoice']['cac:InvoiceLine'] = $data['Invoice']['cac:CreditNoteLine'];
	$type = "cn";
}
$invoicedate = $data['Invoice']['cbc:IssueDate'];
//['Invoice']['cac:AccountingCustomerParty']['cac:PartyName'] + ['Invoice']['cbc:ID']
$faktura = $data['Invoice']['cbc:ID'];
//$debitor = $data['Invoice']['cac:AccountingCustomerParty']['cac:PartyName'];
$debitor = $data['Invoice']['cac:AccountingCustomerParty']['cac:Party']['cac:PartyName']['cbc:Name'];
$debitor = clean($debitor);
//$lines = $data['Invoice']['cac:InvoiceLine'];
//['cac:PaymentTerms']['cbc:Amount']['_value'] + ['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxAmount']['_value']
if ($type == "inv")
$brutto = $data['Invoice']['cac:PaymentTerms']['cbc:Amount']['_value'];
else
$brutto = $data['Invoice']['cac:LegalMonetaryTotal']['cbc:TaxInclusiveAmount']['_value'];
$moms = $data['Invoice']['cac:TaxTotal']['cac:TaxSubtotal']['cbc:TaxAmount']['_value'] *-1;
if ($type == "cn") {
	$brutto = $brutto *-1;
	$moms = $moms *-1;
}
$date = $data['Invoice']['cbc:IssueDate'];
$netto = $brutto - $moms;
if (strtotime($date) < strtotime('2021-01-01')) die("; too old invoice $fn\n");
echo "$date $debitor - f.  $faktura\n\tAktiver:Omsætningsaktiver:Debitorer:$debitor:$faktura  $brutto\n\tPassiver:Moms:Salgsmoms  $moms\n\tAktiver:Omsætningsaktiver:Igangværende arbejder\n\n";
echo "$date $debitor - f.  $faktura\n\tIndtægter:Igangværende arbejder  $netto\n\tIndtægter:Revi-Salg\n\n";
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function XMLtoArray($xml) {
    $previous_value = libxml_use_internal_errors(true);
    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false; 
    $dom->loadXml($xml);
    libxml_use_internal_errors($previous_value);
    if (libxml_get_errors()) {
        return [];
    }
    return DOMtoArray($dom);
}

function DOMtoArray($root) {
    $result = array();

    if ($root->hasAttributes()) {
        $attrs = $root->attributes;
        foreach ($attrs as $attr) {
            $result['@attributes'][$attr->name] = $attr->value;
        }
    }

    if ($root->hasChildNodes()) {
        $children = $root->childNodes;
        if ($children->length == 1) {
            $child = $children->item(0);
            if (in_array($child->nodeType,[XML_TEXT_NODE,XML_CDATA_SECTION_NODE])) {
                $result['_value'] = $child->nodeValue;
                return count($result) == 1
                    ? $result['_value']
                    : $result;
            }

        }
        $groups = array();
        foreach ($children as $child) {
            if (!isset($result[$child->nodeName])) {
                $result[$child->nodeName] = DOMtoArray($child);
            } else {
                if (!isset($groups[$child->nodeName])) {
                    $result[$child->nodeName] = array($result[$child->nodeName]);
                    $groups[$child->nodeName] = 1;
                }
                $result[$child->nodeName][] = DOMtoArray($child);
            }
        }
    }
    return $result;
}
