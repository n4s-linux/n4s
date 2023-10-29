#!/bin/bash

function requiredata() {
	context=$3
	tag=$1
	field=$2
	echo "<$tag>requiredata $tag $field $context</$tag>"
}

# Define the supplier company party
function supplierCompany() {
  echo "<cac:AccountingSupplierParty>
  <cac:Party>
    <cac:PartyName>
	$(requiredata "cac:Name" Supplier)
    </cac:PartyName>
    <cac:PostalAddress>
	$(requiredata "cbc:Streetname" Supplier)
	$(requiredata "cbc:BuildingNumber" Supplier)
	$(requiredata "cbc:CityName" Supplier)
	$(requiredata "cbc:PostalZone" Supplier)
	$(requiredata "cbc:Country" Supplier)
      <cac:Country>
        <cbc:IdentificationCode>DK</cbc:IdentificationCode>
      </cac:Country>
    </cac:PostalAddress>
    <cac:PartyIdentification>
	$(requiredata "cbc:ID" Supplier "PartyIdenfification")
    </cac:PartyIdentification>
    <cac:Contact>
	$(requiredata "cbc:Telephone" Supplier "Contact")
	$(requiredata "cbc:ElectronicMail" Supplier "Contact")
    </cac:Contact>
  </cac:Party>
</cac:AccountingSupplierParty>"
}

# Define the client company party
function clientCompany() {
  echo "<cac:AccountingCustomerParty>
  <cac:Party>
    <cac:PartyName>
      <cbc:Name>Client Company</cbc:Name>
	$(requiredata "cbc:Name" Customer)
    </cac:PartyName>
    <cac:PostalAddress>
	$(requiredata "cbc:StreetName" Customer)
	$(requiredata "cbc:BuildingNumber" Customer)
	$(requiredata "cbc:CityName" Customer)
	$(requiredata "cbc:PostalZone" Customer)
      <cac:Country>
        <cbc:IdentificationCode>DK</cbc:IdentificationCode>
      </cac:Country>
    </cac:PostalAddress>
    <cac:PartyIdentification>
	$(requiredata "cbc:ID" Customer)
    </cac:PartyIdentification>
    <cac:Contact>
	$(requiredata "cbc:Telephone" Customer)
	$(requiredata "cbc:ElectronicMail" Customer)
    </cac:Contact>
  </cac:Party>
</cac:AccountingCustomerParty>"
}

# Define the tax sub-total
function taxSubTotal() {
  echo "<cac:TaxSubtotal>
  <cbc:TaxableAmount currencyID=\"DKK\">100.00</cbc:TaxableAmount>
  <cbc:TaxAmount currencyID=\"DKK\">25.00</cbc:TaxAmount>
  <cac:TaxCategory>
    <cbc:ID>Standard</cbc:ID>
    <cbc:Name>Standard rate</cbc:Name>
    <cbc:Percent>0.25</cbc:Percent>
    <cac:TaxScheme>
      <cbc:ID>VAT</cbc:ID>
      <cbc:Name>Value Added Tax</cbc:Name>
    </cac:TaxScheme>
  </cac:TaxCategory>
</cac:TaxSubtotal>"
}

# Define the invoice line item
function invoiceLine() {
  echo "<cac:InvoiceLine>
  <cbc:ID>1</cbc:ID>
  <cac:Item>
    <cbc:Name>Product</cbc:Name>
    <cbc:Description>Product description</cbc:Description>
    <cac:SellersItemIdentification>
      <cbc:ID>123</cbc:ID>
    </cac:SellersItemIdentification>
    <cac:StandardItemIdentification>
      <cbc:ID>456</cbc:ID>
    </cac:StandardItemIdentification>
  </cac:Item>
  <cac:Price>
    <cbc:PriceAmount currencyID=\"DKK\">100.00</cbc:PriceAmount>
  </cac:Price>
  <cbc:InvoicedQuantity unitCode=\"C62\">1</cbc:InvoicedQuantity>
  <cac:TaxTotal>
    $(taxSubTotal)
  </cac:TaxTotal>
</cac:InvoiceLine>"
}

# Define the legal monetary total
function legalMonetaryTotal() {
  echo "<cac:LegalMonetaryTotal>
  <cbc:LineExtensionAmount currencyID=\"DKK\">100.00</cbc:LineExtensionAmount>
  <cbc:TaxExclusiveAmount currencyID=\"DKK\">100.00</cbc:TaxExclusiveAmount>
  <cbc:TaxInclusiveAmount currencyID=\"DKK\">125.00</cbc:TaxInclusiveAmount>
  <cbc:PayableAmount currencyID=\"DKK\">125.00</cbc:PayableAmount>
</cac:LegalMonetaryTotal>"
}

# Define the invoice XML
function invoice() {
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<Invoice xmlns=\"urn:oasis:names:specification:ubl:schema:xsd:Invoice-2\" xmlns:cac=\"urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2\" xmlns:cbc=\"urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2\">
  <cbc:UBLVersionID>2.2</cbc:UBLVersionID>
  <cbc:CustomizationID>urn:oioubl:schema:electronic:invoice:2.0</cbc:CustomizationID>
  <cbc:ProfileID>urn:oioubl:id:profileid:1.2</cbc:ProfileID>
  <cbc:ID>1234</cbc:ID>
  <cbc:IssueDate>$(date +%Y-%m-%d)</cbc:IssueDate>
  $(supplierCompany)
  $(clientCompany)
  $(invoiceLine)
  <cac:TaxTotal>
    $(taxSubTotal)
  </cac:TaxTotal>
  $(legalMonetaryTotal)
</Invoice>"
}

# Save the invoice XML to a file
echo "$(invoice)"|less
