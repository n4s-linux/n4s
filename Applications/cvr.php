<?php
function cvrapi($vat, $country)
{
  // Strip all other characters than numbers
//  $vat = preg_replace('/[^0-9]/', '', $vat);

  // Check whether VAT-number is invalid
 /* if(empty($vat) === true)
  {

    // Print error message
    return('Venligst angiv et CVR-nummer.');

  } else { */

    // Start cURL
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, 'http://cvrapi.dk/api?name=' . $vat . '&country=' . $country);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Olsens Revision ApS');

    // Parse result
    $result = curl_exec($ch);

    // Close connection when done
    curl_close($ch);

    // Return our decoded result
    return json_decode($result, 1);

  //}
}

// Test CVRAPI
$data = ( cvrapi($argv[1], 'dk') );
$cmd = "echo $data[vat] > '4500 - CVR'
echo '$data[email]' > '5000 - Email'
echo '$data[name]' > '1000 - Kundenavn'
echo '$data[address]' > '2000 - Adresse'
echo '$data[zipcode] $data[cityname]' > '3000 Postnr By'
echo 'Oprettet via CVR. Startdato: $data[startdate], Branche: $data[industrycode] $data[industrydesc] ' > '50000 - Noter'
";
echo $cmd;
print_r($data);
?>
