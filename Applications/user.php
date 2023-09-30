<?php 
require_once 'google-api-php-client/src/Google/autoload.php';
      $client = new Google_Client ();
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
$client->setScopes(array("https://www.googleapis.com/auth/calendar"));
/*
        "client_id" => '770710802407-llu2ccmulno2epprgaituh6sduv3cera.apps.googleusercontent.com',
        "client_secret" => 'sEciFs6J7FOCsY40ZngklDwh',
*/
$client->setRedirectUri('http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]);
$client->setClientSecret('sEciFs6J7FOCsY40ZngklDwh');
$client->setClientId('770710802407-llu2ccmulno2epprgaituh6sduv3cera.apps.googleusercontent.com');
$authUrl = $client->createAuthUrl();
echo "<a class='login' href='" . $authUrl . "'>Connect Me!</a>";
print_r($_POST);
print_r($_GET);
if (count($_GET)) {
	$client->authenticate($_GET['code']);
	print_r($_GET);
print_r($client);
}
?>
