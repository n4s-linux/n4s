<?php
// Create the supplier company party
$supplierCompany = [
    'name' => 'Supplier Company',
    'address' => [
        'streetName' => 'Supplier Street',
        'buildingNumber' => '1',
        'cityName' => 'Supplier City',
        'postalZone' => '12345',
        'country' => [
            'identificationCode' => 'DK'
        ]
    ],
    'partyIdentification' => [
        'companyID' => '12345678'
    ],
    'contact' => [
        'telephone' => '12345678',
        'electronicMail' => 'supplier@example.com'
    ]
];

// Create the client company party
$clientCompany = [
    'name' => 'Client Company',
    'address' => [
        'streetName' => 'Client Street',
        'buildingNumber' => '2',
        'cityName' => 'Client City',
        'postalZone' => '67890',
        'country' => [
            'identificationCode' => 'DK'
        ]
    ],
    'partyIdentification' => [
        'companyID' => '87654321'
    ],
    'contact' => [
        'telephone' => '98765432',
        'electronicMail' => 'client@example.com'
    ]
];

// Create the tax sub-total
$taxSubTotal = [
    'taxableAmount' => 100.00,
    'taxAmount' => 25.00,
    'taxCategory' => [
        'id' => 'Standard',
        'name' => 'Standard rate',
        'percent' => 0.25,
        'taxScheme' => [
            'id' => 'VAT',
            'name' => 'Value Added Tax'
        ]
    ]
];

// Create the invoice line item
$invoiceLine = [
    'id' => 1,
    'item' => [
        'name' => 'Product',
        'description' => 'Product description',
        'sellersItemIdentification' => '123',
        'standardItemIdentification' => '456'
    ],
    'price' => [
        'priceAmount' => 100.00
    ],
    'invoicedQuantity' => 1,
    'taxTotal' => [
        'taxSubTotal' => [$taxSubTotal]
    ]
];

// Create the legal monetary total
$legalMonetaryTotal = [
    'lineExtensionAmount' => 100.00,
    'taxExclusiveAmount' => 100.00,
    'taxInclusiveAmount' => 125.00,
    'payableAmount' => 125.00
];

// Create the invoice object
$invoice = [
    'id' => '1234',
    'issueDate' => (new DateTime())->format('Y-m-d'),
    'accountingSupplierParty' => $supplierCompany,
    'accountingCustomerParty' => $clientCompany,
    'invoiceLine' => [$invoiceLine],
    'taxTotal' => [
        'taxSubTotal' => [$taxSubTotal]
    ],
    'legalMonetaryTotal' => $legalMonetaryTotal
];
// Convert the invoice array to XML using SimpleXMLElement
// Convert the invoice array to XML using SimpleXMLElement
$xml = new SimpleXMLElement('<Invoice/>');
array_walk_recursive($invoice, array($xml, 'addChild'));

// Output the generated XML to the console
echo $xml->asXML();

// Use DOMDocument to format the XML
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());

// Save the generated XML to a file
$dom->save("/home/joo/tmp/foobar.xml");
?>