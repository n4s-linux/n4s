<?php
include("/svn/svnroot/Applications/common/SimpleTpl.php");
include("ledger_token.php");
session_start();


class Auth
{
	public function __construct()
	{
		$this->stpl = new \Mikjaer\SimpleTpl\SimpleTpl();
		$this->token = new LedgerToken();
	}

	public function loginError($message)
	{
		$this->stpl->assign("errorMessage", $message);

		return false;
	}

	public function loginPerform()
	{


		$dir = "/data/regnskaber/".$_REQUEST["regnskab"];
		if (!preg_match("/[a-z0-9]+/", $_REQUEST["regnskab"]))
            return $this->loginError("Ugyldige regnskab"); //Invalid accounts
		elseif (!is_dir($dir))
            return $this->loginError("Ugyldigt regnskab '" .$_REQUEST['regnskab']."'");  //Invalid accounts
        elseif (!$this->checkIfWritable($dir)) {
            return $this->loginError("Utilstrækkelige tilladelser");  //Insufficient permissions
        }
        elseif(isset($_REQUEST["token"]) && $this->token->verifyToken($_REQUEST["token"])) {
            _log(' login perfform token true ');
            return true;
 	}
	elseif (file_exists("/data/regnskaber/".$_REQUEST["regnskab"]."/credentials.json"))
		{
			$pwdb = json_decode(file_get_contents(
				"/data/regnskaber/".$_REQUEST["regnskab"]."/credentials.json"),true);

			if (!isset($pwdb[$_REQUEST["username"]])) // username not found
				return $this->loginError("Forkert brugernavn eller password");

			if (!password_verify( $_REQUEST["password"], $pwdb[$_REQUEST["username"]]["password"]))
				return $this->loginError("Forkert- brugernavn eller password");

			$_SESSION["authed"]=array("username"=>$_REQUEST["username"],
						"regnskab"=>$_REQUEST["regnskab"]);
			return true; // SUCCESS
		}
		elseif (file_exists("/data/regnskaber/".$_REQUEST["regnskab"]."/password"))
		{
			$pwd = file_get_contents("/data/regnskaber/".$_REQUEST["regnskab"]."/password");

			if (($_REQUEST["password"] == $pwd) and ($_REQUEST["username"] == ""))
			{
				$_SESSION["authed"]=array("username"=>"nobody",
							"regnskab"=>$_REQUEST["regnskab"]);
				return true; // SUCCESS
			}
		}
		else {
			return false;	// FAILURE
		}
	}

	public function isAuthed() { if (isset($_SESSION["authed"])) return true; }
	public function regnskab() { if ($this->isAuthed()) return $_SESSION["authed"]["regnskab"]; }
	public function username() { if ($this->isAuthed()) return $_SESSION["authed"]["username"]; }

	public function login()
	{

		if (isset($_REQUEST["posted"]))
		{

			$this->stpl->assign("input", $_REQUEST);
			if ($this->loginPerform())
			{
				$_SESSION["tpath"]="/data/regnskaber/".$this->regnskab;
				$_SESSION["regnskab"]=$this->regnskab;
				$_SESSION["username"]=$this->username;
                $_SESSION["after_login"]=true;
				header("location: key_html.php");
			}
		}

		print $this->stpl->fetch(__DIR__.'/ledger_login.tpl');
	}

	public function loginWithToken(){

	        _log('loginWithToken');

	        $token = $this->token->readToken($_REQUEST["token"]);
	        if(!$token){
	            $this->logout();
            }

            $_REQUEST["regnskab"] = $token->data->session->authed->regnskab;

	        if ($this->loginPerform())
            {
                $_SESSION["readonly"] = true;
                $_SESSION["authed"]=array("username"=>$token->data->session->authed->username,
                    "regnskab"=>$token->data->session->authed->regnskab);

                $_SESSION['begin'] =  $token->data->session->begin;
                $_SESSION['end'] =  $token->data->session->end;
                $_SESSION['beginytd'] =  $token->data->session->beginytd;
                $_SESSION['endytd'] =  $token->data->session->endytd;
                $_SESSION["tokenUserSettings"] = $token->data->userSettings;

                if(isset($token->data->accountSettings)){
                    $_SESSION['page'] = $token->data->accountSettings->page;
                    $_SESSION['acc'] = $token->data->accountSettings->acc;
                } else {
                    unset($_SESSION['page']);
                    unset($_SESSION['acc']);
                }

                header("location: key_html.php?readonly");
            }
            return $token;
    }

	public function logout() { session_destroy(); unset($_SESSION); }


    public function checkIfWritable($dir){
	    //TODO
        return true;
    }

}


$auth = new Auth();
