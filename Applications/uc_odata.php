<?php
	require_once("/home/joo/uccred.php");
function getData($entityName,$filter = "",$url='https://odata.uniconta.com/api/Entities')
{
	global $firmaid;
	$hash = md5($firmaid.$entityName.$filter);
	$cf = "/var/www/uc/_$hash";
	if (file_exists($cf) && filemtime($cf) > strtotime("-1 minutes")) {
		echo "cached result $entityName\n";
		return json_decode(file_get_contents($cf),true);
	}
	global $username;global $password;
    $authHeader = base64_encode("$username:$password");

    $url = "$url/$firmaid/" . $entityName;
	if ($filter != "")
		$url .= "?\$filter=" . urlencode($filter) . "";
	$durl = urldecode($url);
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $authHeader",
        "Accept: application/json"
    ]);

    // Handling HTTPS requests
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'cURL error: ' . curl_error($ch);
        curl_close($ch);
        return curl_error($ch);
    }
    curl_close($ch);

    $data = json_decode($response, true);
	file_put_contents($cf,json_encode($data));
    return $data;
}
?>
