<?php
if (!isset($argv[1])) die("usage: gocardless [getdata/new]\n");
$op = exec("whoami");
if ($op != "joo") die();
require_once("/svn/svnroot/Applications/fzf.php");
if (!file_exists("/home/$op/.gcl.keys")) {
	echo "Since this is the first run, please give me Your gocardless details - they will be stored in your home directory\n";
	echo "Secret ID: ";
	$secretid = trim(fgets(STDIN));
	echo "Secret Key: ";
	$secretkey = trim(fgets(STDIN));
	file_put_contents("/home/$op/.gcl.keys",json_encode(array('secretid'=>$secretid,"secretkey"=>$secretkey),JSON_PRETTY_PRINT));
}
$keydata = json_decode(file_get_contents("/home/$op/.gcl.keys"),true);
$secretid = $keydata["secretid"]; $secretkey=$keydata["secretkey"];
$payload = array("secret_id"=>$secretid,"secret_key"=>$secretkey);
//require_once("/home/joo/gocardless.keys.php");
if (!file_get_contents("/home/$op/tokens.json"))
	gettokens(); //- vigtigt husk at køre denne igen hvis jeg får fejl
else
	$tokens = json_decode(file_get_contents("/home/$op/tokens.json"),true);
$atoken = refreshtokens($tokens);
if ($argv[1] == "new") {
	$banks = call($atoken, "https://bankaccountdata.gocardless.com/api/v2/institutions/?country=dk");
	$bank = pickbank($banks);
	$agreement = call($atoken,"https://bankaccountdata.gocardless.com/api/v2/agreements/enduser/",array('institution_id'=>$bank,'max_historical_days'=>180,'access_valid_for_days'=>90,"access_scope"=>array('balances','details','transactions')));
	echo "Enter reference for this req: ";
	$ref = trim(fgets(STDIN));
	$req = call($atoken,"https://bankaccountdata.gocardless.com/api/v2/requisitions/",array("redirect"=>"https://olsensrevision.dk/tak-for-din-laeseadgang/","institution_id"=>$bank,"reference"=>date("c").$ref,"agreement"=>$agreement["id"],"user_language"=>"DA"));
	system("mkdir /home/$op/bankreq -p");
	file_put_contents("/home/$op/bankreq/$bank" . "_" .date("c"). $ref . ".json",json_encode($req,JSON_PRETTY_PRINT));
	echo "Link: " . $req["link"] . "\n";
}
else if ($argv[1] == "getdata") {
	ob_start();
	system("cd /home/$op/bankreq;ls -t");
	$reqs = ob_get_clean();
	$x = explode("\n",trim($reqs));
	foreach ($x as $valg) {
		$reqdata = json_decode(file_get_contents("/home/$op/bankreq/$valg"),true);
		if ($reqdata == null) continue;
		$id = $reqdata["id"];
		$list = call($atoken,"https://bankaccountdata.gocardless.com/api/v2/requisitions/$id/");
		foreach ($list["accounts"] as $curacc) {
			$data = call($atoken,"https://bankaccountdata.gocardless.com/api/v2/accounts/$curacc/transactions/");
			system("mkdir /home/$op/banktrans/$reqdata[reference] -p");
			if (!isset($data["transactions"])) continue;
			foreach ($data["transactions"]["booked"] as $curdata) {
				$curdata["AccountRef"] = $curacc;
				$curdata["ReqRef"] = $reqdata["reference"];
				file_put_contents("/home/$op/banktrans/$reqdata[reference]" . "/" .$curdata["AccountRef"] . "_"  .$curdata["internalTransactionId"] . ".json",json_encode($curdata,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			}
		}
	}
}

function pickbank($banks) {
	global $op;
	$fzf = "";
	foreach ($banks as $curbank) {
			$fzf .= $curbank["id"] . "\t" . $curbank["name"] . "\n";
	}
	$valg = fzf($fzf,"Pick bank","",true);
	$rv = explode(" ",$valg)[0];
	echo "Picked '$rv'\n";
	return $rv;
}
function call($atoken,$url,$params = null) {
	global $op;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$url");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers = [
	    'Accept: application/json',
	    'Content-Type: application/json',
	"Authorization: Bearer $atoken"
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	if ($params != null)
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($params) );

	$server_output = curl_exec($ch);
	if ($server_output == false) {

		echo curl_error($ch);
		die("couldnt call $url\n");
	}
	$output = json_decode($server_output,true);
	curl_close($ch);
	return $output;
}
function refreshtokens($tok) {
	global $op;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://bankaccountdata.gocardless.com/api/v2/token/refresh/");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$headers = [
	    'Accept: application/json',
	    'Content-Type: application/json'
	];
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode(array("refresh"=>$tok["refresh"])) );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$server_output = curl_exec($ch);
	if ($server_output == false) die("couldnt refresh token\n");
	$output = json_decode($server_output,true);
	curl_close($ch);
	$t = $output["access"];
	return $t;

}
function gettokens() {
global $op;
echo "getting tokens";
global $payload;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://bankaccountdata.gocardless.com/api/v2/token/new/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'Accept: application/json',
    'Content-Type: application/json'
];
curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload) );
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$server_output = curl_exec($ch);
if ($server_output == false) die("couldnt get token\n");
$output = json_decode($server_output,true);
curl_close($ch);
file_put_contents("/home/$op/tokens.json",json_encode($output,JSON_PRETTY_PRINT));
return $output;
}
?>
