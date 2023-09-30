<?php
session_start();
include(__DIR__."/core_grid.class.php");
class menuItem
{
    private $label, $url, $module, $method, $active;
    private $items = array();

    public function addSubMenuItem(menuItem $item) { $this->items[] = $item; return $this; }

    public function setLabel($label) { $this->label = $label; return $this; }
    public function setUrl($url) { $this->url = $url; return $this; }
    public function setModule($module) { $this->module = $module; return $this; }
    public function setMethod($method) { $this->method = $method; return $this; }
    public function setActive() { $this->active = true; return $this; }


    public function getLabel() { return $this->label; }
    public function getActive() { return $this->active; }
    public function getItems() { return $this->items; }

    public function getUrl() 
    { 
        if ($this->module)
        {
            $url = "?m=".$this->module;

            if ($this->method)
                $url.="&s=".$this->method;

            return $url; 
        }
        return $this->url; 
    }
}

class module
{
    protected $core, $stpl;

    public function __construct($core,$stpl)
    {
        $this->core = $core;
        $this->stpl = $stpl;
        if (method_exists($this, "init"))
            $this->init();
    }
}

class core
{
    private $stpl;
    private $menu = array();
    public function __construct($stpl)
    {
        $this->stpl = $stpl;

        if ($_REQUEST["logout"] == "true")
        {
            $this->logout();
            header("location: /");
            die();
        }

        if (!$this->isAuthed())
            if (!$this->auth())
                die();
    }

    public function addMenuItem($item) { $this->menu[] = $item; return $this; }


    public function isAuthed() { if (isset($_SESSION["authed"])) return true; }
    public function regnskab() { if ($this->isAuthed()) return $_SESSION["authed"]["regnskab"]; }
    public function username() { if ($this->isAuthed()) return $_SESSION["authed"]["username"]; }



    public function auth()
    {
 	if (isset($_REQUEST["posted"]))
	{
		$this->stpl->assign("input", $_REQUEST);
		if ($this->loginPerform())
		{
			$_SESSION["tpath"]="/home/joo/regnskaber/transactions_".$this->regnskab;
			$_SESSION["regnskab"]=$this->regnskab;
			$_SESSION["username"]=$this->username;
		
                        return true;
                }	
	}

        $this->stpl->assign("header", $this->header());
        $this->stpl->assign("content", $this->stpl->fetch(__DIR__.'/auth_login.tpl'));
        $this->stpl->assign("footer", $this->footer());
        print $this->stpl->fetch(__DIR__.'/main.tpl');
    
        return false;
    }

    public function logout() { session_destroy(); unset($_SESSION); }


    public function loginError($message)
    {
            $this->stpl->assign("errorMessage", $message);

            return false;
    }

    public function loginPerform()
    {
            if (!preg_match("/[a-z0-9]+/", $_REQUEST["regnskab"]))
                    return $this->loginError("Ugyldige regnskab");
            elseif (!is_dir("/home/joo/regnskaber/transactions_".$_REQUEST["regnskab"]) && ! is_link("/home/joo/regnskaber/transactions_".$_REQUEST["regnskab"]))
                    return $this->loginError("Ugyldigt regnskab");
            elseif (file_exists("/home/joo/regnskaber/transactions_".$_REQUEST["regnskab"]."/credentials.json"))
            {
                    $pwdb = json_decode(file_get_contents(
                            "/home/joo/regnskaber/transactions_".$_REQUEST["regnskab"]."/credentials.json"),true);

                    if (!isset($pwdb[$_REQUEST["username"]])) // username not found
                            return $this->loginError("Forkert brugernavn eller password");

                    if (!password_verify( $_REQUEST["password"], $pwdb[$_REQUEST["username"]]["password"]))
                            return $this->loginError("Forkert- brugernavn eller password");

                    $_SESSION["authed"]=array("username"=>$_REQUEST["username"],
                                            "regnskab"=>$_REQUEST["regnskab"]);
                    return true; // SUCCESS
            }
            elseif (file_exists("/home/joo/regnskaber/transactions_".$_REQUEST["regnskab"]."/password"))
            {
                    $pwd = trim(file_get_contents("/home/joo/regnskaber/transactions_".$_REQUEST["regnskab"]."/password"));
                    if (($_REQUEST["password"] == $pwd) and ($_REQUEST["username"] != ""))
                    {
                        $_SESSION["authed"]=array("username"=>"nobody", "regnskab"=>$_REQUEST["regnskab"]);
                        return true; // SUCCESS
                    }
            }
            else
                    return false;	// FAILURE
    }




   public function renderMenu()
    {   
        $menu[] = array();

        foreach ($this->menu as $item)
        {
            if (count($subitems = $item->getItems()) > 0)
            {
                $i = array();
                foreach ($subitems as $subitem)
                {
                    $i[] = array(
                            "label" => $subitem->getLabel(),
                            "url" => $subitem->getUrl()
                            );
                }
                $menu[] = array(
                        "label" => $item->getLabel(),
                        "active" => $item->getActive(),
                        "items" => $i
                        );
            }
            else
            {
                $menu[] = array(
                        "label" => $item->getLabel(),
                        "active" => $item->getActive(),
                        "url" => $item->getUrl()
                        );
            }
        }

        return $menu;
    }

    public function setTitle($title) { $this->title = $title; $this->stpl->assign("pagetitle",$title); return $true; }

    public function render()
    {

        if ($module=$_REQUEST["m"])
        {
            if (preg_match("/^[a-z]+$/", $module))
                if (file_exists($file = (getcwd()."/modules/$module/$module.class.php")))
                {
                    include($file);
                    $module = new $module($this, $this->stpl);

                    if (($method=$_REQUEST["s"])
                            and (preg_match("/^[a-z]+$/", $method))
                            and (method_exists($module, $method)))
                        $module->$method();
                    else
                        $module->main();

                }
        }

#        $this->setTitle("test");
        $this->stpl->assign("header", $this->header());
        $this->stpl->assign("footer", $this->footer());
        print $this->stpl->fetch(__DIR__.'/main.tpl');
    }

    public function header()
    {
        $this->stpl->assign("menu", $this->renderMenu());
        return $this->stpl->fetch(__DIR__.'/header.tpl');
    }

    public function footer()
    {
        return $this->stpl->fetch(__DIR__.'/footer.tpl');
    }



}
