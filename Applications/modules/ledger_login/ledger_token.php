<?php


//TODO
//Generete at token and store it
//token should allow access in read only mode to the current client
//It should have the same settings as the current one for the client (dates, details, show/hide p2), comments
//All the dates should be in the link and only these dates in the created link should be allowed
//All should be readonly, except for details that should be able to change via the link, but not rememberred.
//read only mode means that the customer should not be able to change transactions, so the transactions edit form should be locked and no save button
//key_html.php?cb=&details=0&page=browse_period&lort&acc=Udgifter%3AB%C3%B8der&begin=1970-01-01&end=2099-12-31&beginytd=1970-01-01&endytd=2099-12-31
require_once('/svn/svnroot/Applications/vendor/autoload.php');

use \Firebase\JWT\JWT;

class LedgerToken {

    private $key = "asdff2323asdf23";

    public function __construct()
    {

//        $this->JWT = JWT;

    }


    public function generateToken(){

        _log('---------generateToken---------------');

        $user = $_SESSION["authed"]["regnskab"];

        if (isset($_COOKIE[$user])) {
            $userSettings = json_decode($_COOKIE[$user], true);
        }

        $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );

      //  $escaped_url = str_replace("Applications/key_html.php", "Applications/lw/key_html.php", $escaped_url);

        $key = $this->key;

        $expDays = 7;
        if(isset($_REQUEST['token-exp-days'])){
            $expDays = $_REQUEST['token-exp-days'];
        }

        $expTime =  strtotime("+$expDays days midnight");

        $token = array(
//            "iat" => '$issued_at',
            "exp" => $expTime,
//            "iss" => '$issuer',
            "data" => array(
                "session" => $_SESSION,
                "userSettings" => $userSettings,
                "lastname" => '$user->lastname',
                "email" => '$user->email'
            )
        );

        if(isset($_POST['pageUrl'])){

            $parts = parse_url($_POST['pageUrl']);
            parse_str($parts['query'], $query);
            $token['data']['accountSettings'] = array(
                'page' => $query['page'],
                'acc' => $query['acc']
            );

        }

        $jwt =  JWT::encode($token, $key);


        $params = array();

        if($_SESSION['begin']){
            $params[] = "begin=". $_SESSION['begin'];
        }
        if($_SESSION['end']){
            $params[] = "end=". $_SESSION['end'];
        }
        if($_SESSION['beginytd']){
            $params[] = "beginytd=". $_SESSION['beginytd'];
        }
        if($_SESSION['endytd']){
            $params[] = "endytd=". $_SESSION['endytd'];
        }
        if($_SESSION['periods']){
            $params[] = "periods=". $_SESSION['periods'];
        }

        $params[] = "token=". $jwt;

//        begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd

//
//        $fp = fopen('tokens.json', 'w');
////        fwrite($fp, json_encode($jwt));
//        fwrite($fp, $jwt);
//        fclose($fp);

        $link = "https:".$escaped_url . "?" . implode('&', $params);

        return $link;
    }

    public function readToken($token){

        try {
            $tokenData = JWT::decode($token, $this->key, ['HS256']);
        } catch (Exception $e) {
            _log('readToken exception');
            _log($e->getMessage());
            $tokenData = false;
        }

//       _log((array) $tokenData);

        return $tokenData;
    }

    public function verifyToken($token){
        $verified = false;
        try {
            $tokenData = JWT::decode($token, $this->key, ['HS256']);

            if(isset($tokenData->data)){
                $verified = true;
            }
        } catch (Exception $e) {
            _log('verifyToken exception');
            _log($e->getMessage());
        }

        return $verified;

    }


}










