<?php
$begin = 0;
$end = 0;
$data_fi = array();
require_once("/svn/svnroot/Applications/proc_open.php");
require_once("/svn/svnroot/Applications/jl/jl.php");
require_once("/svn/svnroot/Applications/jl/help.php");
require_once("/svn/svnroot/Libraries/companies.php");
require_once("/svn/svnroot/Applications/lookup_customer.php");
$kundenr = getenv("k");
$k = "$kundenr";
while (!is_numeric($k)) {
	if (!strlen($kundenr))
		$k = lookup_customer(console_input("Indtast kundenavn"));
	else
		$k = lookup_customer($kundenr);
}
$kundenr = $k;
($GLOBALS["___mysqli_ston"] = mysqli_connect($config['dbhost'], $config['dbuser'], $config['dbpasswd'])) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
((bool)mysqli_set_charset($GLOBALS["___mysqli_ston"], "utf8"));
mysqli_select_db($GLOBALS["___mysqli_ston"], "jl") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
//$filnavn = getenv("KDDIR") . "/Ledger.json";
//$filnavn = getenv("HOME") . "/books/" . basename(getenv("KDDIR")) . ".json";
$filnavn_short = basename(getenv("KDDIR")) . ".json";
$bookspath = getenv("HOME") . "/books/";
$entrydata = array("Dato"=>'date',"Bilagsnr"=>'numeric_optional',"Tekst"=>'text',"Konto"=>'account',"F1"=>'text',"Beløb"=>'numeric',"Modkonto"=>'account_optional',"F2"=>'text',"Dimension"=>'text');
	$nytregnskab = 0;
	/* pull()
	if (file_exists($filnavn)) {
		echo "Indlæser datafil '$filnavn'\n";
		$lastchanged = (filemtime($filnavn) - time()) / 60 / 60 * -1;
		$enhed = "timer";
		if ($lastchanged > 24) {
			$lastchanged = $lastchanged / 24 ;
			$enhed = "dage";
		}
		$lastchanged = intval($lastchanged);
		echo("Ledgeren blev sidst ændret $lastchanged $enhed siden\n");
		$data =(array)json_decode(file_get_contents($filnavn),true);
	}
	else {
		echo "Path: $bookspath\n";
		$data = (array)json_decode(file_get_contents($bookspath."Kontoplan.json"),true);
		$nytregnskab = 1;
	} 	
	if (isset($data['afstemning']))
		$afstemning = $data['afstemning'];
	else
		$data['afstemning'] = array();
	if (isset($data['indstillinger']))
		$indstillinger = $data['indstillinger'];
	else
		$data['indstillinger'] = array();
	$kontoplan=(array)$data['kontoplan'];
	*/
	$kontoplan_id = array();
	$sumfra_id = array(); /*
	if (isset($data['data']))
		$data = $data['data'];
	else
		$data = array();
	if ($nytregnskab)
		commit();
	*/
$data = load_transactions();
$kontoplan = load_kontoplan();
validate();
getperiod();
$filter = array('beløb'=>"",'konto'=>"",'bilag'=>"",'dimension'=>"",'tekst'=>"");
$history = array();
$run = 0;
	while (1) {
		if (isset($argv[2])) {
			$cmd = $argv[2];
		}
		else
			$cmd = trim(console_input("jl ($begin - $end)"));
		if ($run == 1 && isset($argv[2])) {die();}
		$run++;
		if (!strlen($cmd)) continue;
		if ($cmd != ".") {
			if (!in_array($cmd,$history))
				array_unshift($history,$cmd);
		}
		else {
			print_r(array_slice($history,0,10));
			$cmd = $history[intval(console_input("Indtast nummer"))];
		}
		$data_pri= array();
		$data_fi= array_filter($data,"filter_period");
		$split = explode(" ",$cmd);
		$cmd = $split[0];
		$parameters = $split;
		unset($parameters[0]);
			switch ($cmd) {
			case "ps":
				require_once("/svn/svnroot/Applications/jl/extensions/ps.php");
				if (count($parameters))
					ps_post_order($parameters[1]);
				else
					ps_post_order(console_input("Indtast prestashop ordrenummer"));
				break;
			case "sync":
				sync();
				break;
			case "h":
				http_edit();
				break;
			case "a":
				add_reconciliation();
				break;
			case "v":
				validate();
				break;
			case "g":
				validate();
				save();
				/*if (console_input("Ønskes commit også (j/n)") == "j")
					commit() ;*/
				break;
			case "log":
				glog();
				break;
			case "?":
			case "help":
			case "hjælp":
				print_r($functions);
				break;
			case "kp": 
				print_r(($kontoplan['kontoplan']));
				break;
			case "sum_ny": 
				$fra = intval(console_input("Fra konto"));
				$til = intval(console_input("Til konto"));
				$sumnr = $til +1;
				if ($fra >= $til) {
					echo "Fra skal være < til\n";break;
				}
				else if(isset($kp[$sumnr])) {
					echo "Kontonummer findes allerede i kontoplan\n";
					break;
				}
				$kontoplan['Sumfra'][$sumnr] = $fra;
				$kontoplan['kontoplan'][$sumnr] = console_input("Konto navn");
				validate();save();
				break;
			case "csv":
				listcsv();
				break;
			case "s":
				foreach ($parameters as &$param) {
					if ($param == "a") $param = "ask";
					else if ($param == "j") $param = 1;
					else $param = intval($param);
				}
				for ($i = 1;$i<4;$i++) {
					if (!isset($parameters[$i]))
						$parameters[$i] = "ask";
				}
				search_edit($parameters[1],$parameters[2],$parameters[3]);
				break;
			case "fr":
				$filter = array();
				echo "Filter nulstillet\n";
				break;
			case "filter":
				$filter_count = 0;
				$i = 1;
				foreach (array('konto','bilag','tekst','beløb','dimension') as $filter_mulighed) {
					if (isset($parameters[$i])) {
						if ($parameters[$i] == "NA")
							$filter[$filter_mulighed] = "";
						else if ($parameters[$i] == "a") {
							$filter[$filter_mulighed] = console_input("Filter for $filter_mulighed");
							$filter_count++;
						}
						else {
							$filter[$filter_mulighed] = $parameters[$i];
							$filter_count++;
						}
					}
					else {
						$filter[$filter_mulighed] = console_input("Filter for $filter_mulighed");
						if (strlen($filter[$filter_mulighed]))
							$filter_count++;
					}	
					$i++;
				}
				if ($filter_count) {
					print_r($filter);
					echo "Filtre er nu indstillet - vis/rediger resultaterne med 's'\n";
				}
				else
					echo "Filtre nulstillet OK\n";
				break;
			case "e":
				addentry_frominput();
				break;
			case "p":
				setp:
				if (!count($parameters))
					setperiod(false,false,true);
				else
					setperiod($parameters[1],$parameters[2]);
				$data_pri= array();
				$data_fi= array_filter($data,"filter_period");
				break;
		/*	case "k":
				if ($begin == 0) {
					echo "Indstil først periode...\n";
					goto setp;
				}
				if (count($parameters))
					balance("kk",$parameters[1]);
				else
					balance("kk",console_input("Indtast kontonummer"));
				break;
*/
			case "kb": 
				balance("kkk");
				break;
			case "dimension":
			if (!count($parameters))
				balance("dimension",console_input("Indtast kontonummer"));
			else
				balance("dimension",$parameters[1]);
				break;
			case "b":
				if ($begin == 0) {
					echo "Indstil først periode...\n";
					goto setp;
				}
				balance_form();
				break;
			default: 
				echo "Kører '$cmd' i skal\n";
				exec_app("bash -c '$cmd'");
				break;
		}
		$oldcmd = $cmd;
}

?>
