<?php
// Include the necessary class files directly or
// vendor/autoload.php if you used composer to install the package.
require('http.php');
require('oauth_client.php');

$client = new oauth_client_class;
$client->server = 'Google';

$client->client_id = 'your application id here';
$client->client_secret = 'your application secret here';

$client->scope = 'https://www.googleapis.com/auth/contacts.readonly';
if(($success = $client->Initialize()))
{
    if(($success = $client->Process()))
    {
        if(strlen($client->authorization_error))
        {
            $client->error = $client->authorization_error;
            $success = false;
        }
        elseif(strlen($client->access_token))
        {
            $success = $client->CallAPI(
                'https://people.googleapis.com/v1/people/me/connections'.
                '?fields=connections(emailAddresses%2Cnames)',
                'GET', array(), array('FailOnAccessError'=>true), $contacts);
        }
    }
    $success = $client->Finalize($success);
}
if($client->exit)
    exit;
if($success)
{
        echo '<pre>';
        foreach($contacts->connections as $contact)
        {
            echo htmlspecialchars($contact->names[0]->displayName), "\n";
        }
        echo '</pre>';
}
else
{
  echo 'Error: ', HtmlSpecialChars($client->error);
}
?>
