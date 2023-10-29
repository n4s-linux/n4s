<?php
// Create a Complete OIOUBL Invoice XML with InvoiceLine and Prefixes

// Define your invoice data
$invoiceNumber = '12345';
$invoiceDate = '2023-10-28';
$amount = 100.00;
$currency = 'DKK';

// Create the XML document
$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;

// Create the root element
$invoice = $xml->createElement('cbc:Invoice');
$invoice->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
$invoice->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
$xml->appendChild($invoice);

// Invoice Header
$invoiceHeader = $xml->createElement('cac:InvoiceHeader');
$invoice->appendChild($invoiceHeader);

// Invoice Number
$invoiceNumberElement = $xml->createElement('cbc:ID', $invoiceNumber);
$invoiceHeader->appendChild($invoiceNumberElement);

// Invoice Issue Date
$issueDateElement = $xml->createElement('cbc:IssueDate', $invoiceDate);
$invoiceHeader->appendChild($issueDateElement);

// Supplier Information
$supplier = $xml->createElement('cac:AccountingSupplierParty');
$invoiceHeader->appendChild($supplier);

$supplierParty = $xml->createElement('cac:Party');
$supplier->appendChild($supplierParty);

$supplierName = $xml->createElement('cbc:Name', 'Your Supplier Name');
$supplierParty->appendChild($supplierName);

// Buyer Information
$buyer = $xml->createElement('cac:AccountingCustomerParty');
$invoiceHeader->appendChild($buyer);

$buyerParty = $xml->createElement('cac:Party');
$buyer->appendChild($buyerParty);

$buyerName = $xml->createElement('cbc:Name', 'Your Buyer Name');
$buyerParty->appendChild($buyerName);

// Invoice Amount
$invoiceAmountElement = $xml->createElement('cac:LegalMonetaryTotal');
$invoiceHeader->appendChild($invoiceAmountElement);

$lineAmount = $xml->createElement('cbc:PayableAmount', $amount);
$lineAmount->setAttribute('currencyID', $currency);
$invoiceAmountElement->appendChild($lineAmount);

// Invoice Line
$invoiceLine = $xml->createElement('cac:InvoiceLine');
$invoice->appendChild($invoiceLine);

$invoiceLineID = $xml->createElement('cbc:ID', '1');
$invoiceLine->appendChild($invoiceLineID);

$invoiceLineAmount = $xml->createElement('cbc:LineExtensionAmount', $amount);
$invoiceLineAmount->setAttribute('currencyID', $currency);
$invoiceLine->appendChild($invoiceLineAmount);

// Output the XML
echo $xml->saveXML();
