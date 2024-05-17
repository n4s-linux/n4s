<?php
require_once("/svn/svnroot/Applications/shortacc.php");
$tpath = getenv("tpath");
$op = exec("whoami");
if ($tpath == "") die("register requires tpath\n");
require_once("/svn/svnroot/Applications/readonly.php");
$currenttransactions = array();
require_once("/svn/svnroot/Applications/proc_open.php");
require_once("/svn/svnroot/Applications/fzf.php");
$t = getdata();
$matches = getmatches($t,$argv);
$accounts = getaccounts($matches);
if (empty($accounts)) die("Empty search result\n");
$selected = explode("\n",showmatches($accounts,$matches,$t));
$action = getaction($selected);
if ($action == "") die("No action selected\n");
if ($action == "Edit") edit($selected);
else if ($action == "Reverse") copytrans($selected,"Reverse");
else if ($action == "Copy") copytrans($selected,"Copy");
function copytrans($selected,$mode="Copy") {
	global $currenttransactions;
	global $op;
	global $tpath;
	$torev = array();
	foreach ($selected as $curselect) {
		$x = explode(" ",$curselect);
		$x = trim($x[0]);
		if ($x != "") {
			array_push($torev,$currenttransactions[$x]);
		}
	}
	$vim = "";$results = "";
	foreach ($torev as $currev) {
		$tags = tagz($currev[7]);
		if (!isset($tags["Filename"])) {
			print_r($currev);
			echo "Cannot reverse because not knowing its source...\n";
			die();
		}
		else {
			echo "Reversing $tpath/$tags[Filename]\n";
			$j = json_decode(file_get_contents("$tpath/$tags[Filename]"),true);
			$j["SourceTrans"] = $tags["Filename"];
			if ($mode == "Reverse") {
				foreach ($j["Transactions"] as & $ct) {
					$ct["Amount"] = $ct["Amount"] * -1;
				}
			}
			unset($j["History"]);
			$j["History"][] = array("Date"=>date("Y-m-g H:m"),"op"=>$op,"Description"=>"$mode transaction");
			$nfn = date("Y-m-d") . "_$mode" . uniqid() . ".trans";
			$j["Filename"] = $nfn;
			if ($mode == "Reverse")
				$j["Description"] = "⏪[Reversal] $j[Description]";
			else
				$j["Description"] = "📋[Copy] $j[Description]";
			unset($j["Status"]);
			file_put_contents("$tpath/$nfn",json_encode($j,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			$results .= "\"$nfn\" ";
			$vim .= ":e $tpath/$nfn\n";
			$cmd = "cp \"$tpath/$nfn\" \"$tpath/.$nfn.old\"";		system($cmd);
		}
	}
	if (count($torev)) {
		file_put_contents("/home/$op/tmp/register_reverse",$vim);
		exec_app("vim -s /home/$op/tmp/register_reverse");
		exec_app("php /svn/svnroot/Applications/key_arraydiff.php $results");
		system("rm \"$tpath\"/.*.old");
	}
	
}
function edit($selected) {
	global $currenttransactions;
	global $tpath;
	global $op;
	$toedit = array();
	foreach ($selected as $curselect) {
		$x = explode(" ",$curselect);
		$x = trim($x[0]);
		if ($x != "" && is_numeric($x)) {
			array_push($toedit,$currenttransactions[$x]);
		}
	}
	$vim = "";
	$results = "";
	foreach ($toedit as $curedit) {
		$tags = tagz($curedit[7]);
		if (!isset($tags["Filename"])) die("File $x has no Filename\n");
		if (!file_exists($tpath."/".$tags["Filename"])) continue;
		$vim .= (readonly($tags["Filename"])) ? ":view $tpath/$tags[Filename]\n" : ":e $tpath/$tags[Filename]\n";
		if (isset($tags["TransID"])) {
			$vim .= "/Transactions\n/Account\n";
			for ($i = 0; $i<$tags["TransID"];$i++) {
				$vim .= "n";
			}
			$vim .= "www";
		}
		$results .= "\"$tags[Filename]\" ";
		$cmd = "cp \"$tpath/$tags[Filename]\" \"$tpath/.$tags[Filename].old\"";		system($cmd);
	}
	if (count($toedit)) {
		file_put_contents("/home/$op/tmp/register_edit",$vim);
		exec_app("vim -s /home/$op/tmp/register_edit");
		exec_app("php /svn/svnroot/Applications/key_arraydiff.php $results");
		system("rm \"$tpath\"/.*.old");
	}
}
function getaction($selection){
	$valg = fzf("Edit\nCopy\nReverse","Chose action for transactions");
	if ($valg == "") die("Didnt select action, aborting...\n");
	return $valg;
}
function getdata() {
	ob_start();
	$lh = ledgerhack();
	$cmd = ("$lh color=none LEDGER_SORT=date,account php /svn/svnroot/Applications/newl.php --no-pager --no-color csv");
	system($cmd);
	$data = explode("\n",trim(ob_get_clean()));
	$t = array();
	foreach ($data as $curline) {
		$d = str_getcsv($curline);
		array_push($t,$d);
	}
		return $t;
}
function getmatches($t,$argv) {
$args = $argv;
unset($args[0]);
$matches = array();
foreach ($t as $ct) {
	$match = true;
	foreach ($args as $curarg) {
		if (!stristr($ct[3],$curarg)) { $match = false; continue 2;}
	}
	if ($match) {
		array_push($matches,$ct);
	}
}
return $matches;
}
function bydate($a,$b) {
	return strtotime($a[0]) > strtotime($b[0]);
}
function showmatches($accounts,$matches,$t) {
	global $currenttransactions;
	$fzf = "";
	foreach ($accounts as $ca) $fzf .= "$ca\n";
	$fzf = trim($fzf);
	$acc = fzf($fzf,"Select account that has matches - SPACE to select all","--multi --bind space:select-all");
	if ($acc == "") die("No account(s) selected\n");
	$accs = explode("\n",$acc);
	$show = array();
	foreach ($accs as $ca) {
		foreach ($t as $ct) {
			if ($ct[3] == $ca) {
				array_push($show,$ct);
			}
		}
	}
	$fzf = "";
	$bal = 0;
	$currenttransactions= array();
	$i = 0;
	usort($show,"bydate");
	foreach ($show as $currow) {
		$currenttransactions[$i] = $currow;
		$date = $currow[0];
		$acc = $currow[3];
		$text = $currow[2];
		$amount = str_pad($currow[5],15," ",STR_PAD_LEFT);
		$bal += $currow[5];
		$pamount= str_pad(number_format($amount,2,".",","),15," ",STR_PAD_LEFT);
		$pbal = str_pad(number_format($bal,2,".",","),15," ",STR_PAD_LEFT);
		$tags = tagz($currow[7]);
		if (isset($tags["Filename"])) {
			$contra = shortacc(getcontra($tags["Filename"],$acc));
		}
		else
			$contra = "NA";
		if (isset($tags["SourceFunc"])) $moms = "[" .$tags["SourceFunc"]."]"; else $moms = "";
		if (isset($tags["Filename"]) && !readonly($tags["Filename"]))
			$fzf .= "\033[38;5;16;48;5;226m$i\t$date\t$acc\t$moms\t$contra\t$text\tp$pamount\t$pbal\t\033[0m\n";
		else
			$fzf .= "\033[38;5;96;48;5;247m$i\t$date\t$acc\t$moms\t$contra\t$text\t$pamount\t$pbal\t$\033[0m\n";
	
		$i++;
	}
	$fzf = trim($fzf);
	$rv = fzf($fzf,"Browse entries - SPACE to select all","--tac --ansi --multi --bind space:select-all",true);
	if (trim($rv) == "") die("No matches selected\n");
	return $rv;
}
function getaccounts($t) {
	$accs = array();
	foreach ($t as $ct) if (!in_array($ct[3],$accs)) array_push($accs,$ct[3]);
	return $accs;
}
function ledgerhack() {
        // ledger csv problem workaround unset ledger-depth, then set it again - irc no response 2023-11-01
        return "unset LEDGER_DEPTH;LEDGER_DEPTH=999";
}
function tagz($str) {
	$rv = array();
	$x = explode("||||",$str);
	foreach ($x as $ct) {
		$ct = trim($ct);
		$pos = strpos($ct,": ");
		$sub = trim(substr($ct,$pos +1));
		$base = trim(substr($ct,0,$pos));
		$rv[$base] = $sub;
	}
	if (!isset($rv["Filename"])) $rv["Status"] = "Locked";
	return $rv;
}
function getcontra($fn,$acc) {
	global $tpath;
	if ($fn == "") return "N/A";
	if (!file_exists("$tpath/$fn")) return "N/A";
	$j = json_decode(file_get_contents("$tpath/$fn"),true);
	$t = $j["Transactions"];
	if (count($t) > 2) return "𝤒";
	foreach ($t as $curt) { if ($curt["Account"] != $acc) return $curt["Account"];}
	return "N/A";
}
?>
