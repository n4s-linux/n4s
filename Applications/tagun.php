<?php
	$silent = getenv("silent");
	require_once("/svn/svnroot/Applications/proc_open.php");
	$op = exec("whoami");
	$tpath = getenv("tpath"); if ($tpath == "") die("tagun requires tpath\n");
	$key = trim(file_get_contents("/data/regnskaber/keys/tagun.key"));
	$polygonkey=trim(file_get_contents("/data/regnskaber/keys/polygon.key"));
	if (!isset($argv[1])||$argv[1] == "") die("requires argument in form of a filename\n");
	$dn = dirname($argv[1]);
	$bn = basename($argv[1]);
	$bilag = explode("_____",basename($argv[1]))[0];
	$ofn = $tpath . "/.newvouchers/.". $bn . ".json";
	$ofncurrency = $tpath . "/.newvouchers/.". $bn . ".currency.json";
	$ofnh = "/home/$op/tmp/curhtml.html";
	$ofnsuggestion = $ofn . ".sugg.trans";
	$finaldestination = $tpath. "/" . exec("date +%Y-%m-%dT%H%M") . $bn. "_sugg.trans";
	$ofntg= $ofn.".tg";
	if (file_exists($ofntg)) {
		fprintf(STDERR,"Cached OCR for $argv[1] - $ofntg\n");
		$j = json_decode(file_get_contents($ofntg),true);
	}
	else {
		fprintf(STDERR,"Getting OCR for $argv[1]\n");
		ob_start();
		system("python /svn/svnroot/Applications/tagun.py $key \"$argv[1]\" \"$argv[1]\"");
		$j = ob_get_clean();
		$j = json_decode($j,true);
		file_put_contents("$ofntg",json_encode($j,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	}
	if (!is_array($j)) {
		print_r($j);
		echo "Not array, Quitting\n";
		die();
	}
	foreach ($j as &$curj) {
		if (isset($curj["regions"])) {
			unset($curj["regions"]);
		}
		if (!is_array($curj))continue;
		foreach ($curj as &$curjj) {
			if (is_array($curjj) && isset($curjj["regions"])) {
				unset($curjj["regions"]);
			}
		}
	}
	error_reporting(0);
	$useless = array("city","continent","country","location","elapsed","merchantState");
	foreach ($useless as $curuseless) unset($j[$curuseless]);
	file_put_contents("$ofnh",jsonToDebug(json_encode($j)));
	if (!isset($j["totalAmount"])) { fprintf(STDERR,"no amount\n");die();}
	$data["totalAmount"] = $j["totalAmount"]["data"];
	$data["currency"] = $j["totalAmount"]["currencyCode"];
	$currency = $data["currency"];
	$data["date"] = $j["date"]["data"];
	$data["dueDate"] = $j["dueDate"]["data"];
	$data["taxAmount"] = $j["taxAmount"]["data"];
	$data["merchantName"] = $j["merchantName"]["text"];
	$data["merchantAddress"] = $j["merchantAddress"]["text"];
	$date = date("Y-m-d",strtotime($data["date"]));
	$data["DKKAmount"] = $data["totalAmount"];
	$amount = $data["totalAmount"];
	if ($currency != "DKK" && $currency != "") {
		$url = "https://api.polygon.io/v2/aggs/ticker/C:$currency" . "DKK/range/1/day/$date/$date?adjusted=true&sort=asc&apiKey=$polygonkey";
		if (!file_exists($ofncurrency)) {
			$currencydata= json_decode(file_get_contents("$url"),true);
			file_put_contents($ofncurrency,json_encode($currencydata,JSON_PRETTY_PRINT));
		}
		else
			$currencydata = json_decode(file_get_contents($ofncurrency),true);
		$rate = $currencydata["results"][0]["c"];
		$data["DKKAmount"] = $rate * $amount;
	}

	$trans = array();
	$trans["Create"] = false;
	$trans["Date"] = date("Y-m-d",strtotime($data["date"]));
	$trans["Description"] = str_replace("  "," ",$data["merchantName"]);
	$trans["Reference"] = $bilag;
	$trans["Comment"] = "";

	error_reporting(E_ALL);
	$trans["Filename"] = basename("$finaldestination");
	$trans["Transactions"][0]["Amount"] = $data["DKKAmount"];
	$trans["Transactions"][0]["Func"] = "";
	$trans["Transactions"][0]["Currency"] = $currency;
	$trans["Transactions"][0]["AmountCurrency"] = $currency;
	$trans["Transactions"][0]["Account"] = $trans["Description"];
	$trans["Transactions"][1]["Amount"] = -$data["DKKAmount"];
	$trans["Transactions"][1]["Currency"] = $currency;
	$trans["Transactions"][1]["AmountCurrency"] = -$amount;
	$trans["Transactions"][1]["Func"] = "";
	$trans["Transactions"][1]["Account"] = "Fejlkonto:Uhåndterede bilag";
	$trans["History"][] = array("op"=>exec("whoami"),"desc"=>"Created from ocr","date"=>date("Y-m-d H:i"));
	file_put_contents("$ofnh",jsonToDebug(json_encode($data)));
	file_put_contents("$ofnh",jsonToDebug(json_encode($j)),FILE_APPEND);
	file_put_contents("$ofn",json_encode($j,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	file_put_contents("$ofnsuggestion",json_encode($trans,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); 
	if ($silent != "1") {
		exec_app("vi \"$ofnsuggestion\" \"$ofnh.txt\" \"$ofn\"");
		$readback = json_decode(file_get_contents("$ofnsuggestion"),true);
		if ($readback["Create"] != false) {
			system(" echo creating|toilet |lolcat;sleep 0.5");
			unset($readback["Create"]);
			file_put_contents($finaldestination,json_encode($readback,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		}
	}
function jsonToDebug($jsonText = '')
{
    $arr = json_decode($jsonText, true);
    $html = "";
    if ($arr && is_array($arr)) {
        $html .= _arrayToHtmlTableRecursive($arr);
    }
    return $html;
}

function _arrayToHtmlTableRecursive($arr) {
    $str = "<table border=1><tbody>";
    foreach ($arr as $key => $val) {
        $str .= "<tr>";
        $str .= "<td>$key</td>";
        $str .= "<td>";
        if (is_array($val)) {
            if (!empty($val)) {
                $str .= _arrayToHtmlTableRecursive($val);
            }
        } else {
            $str .= "<strong>$val</strong>";
        }
        $str .= "</td></tr>";
    }
    $str .= "</tbody></table>";

    return $str;
}
	?>
