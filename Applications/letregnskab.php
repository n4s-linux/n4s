<?php
$op = exec("whoami");
$datepicktext = "(Vælg aktuelt regnskabsår)"; $quiet=1; include("/svn/svnroot/Applications/datepick.php");
$dpfn = "/home/$op/tmp/.datepick_$bn";
$period_now = file_get_contents($dpfn);
$datepicktext = "(Vælg foregående regnskabsår)"; $quiet=1; include("/svn/svnroot/Applications/datepick.php");
$dpfn = "/home/$op/tmp/.datepick_$bn";
$period_then = file_get_contents($dpfn);


ob_start();
system("$period_now color=none php /svn/svnroot/Applications/newl.php csv");
$data = explode("\n",trim(ob_get_clean()));
$balances = array();
foreach ($data as $curline) {
	$cols = str_getcsv($curline);
	$amount = $cols[5];
	$account = $cols[3];
	$ecoaccount = ecomap($account);
	if (!isset($balances[$ecoaccount."|||".$account]["now"])) $balances[$ecoaccount."|||".$account]["now"] = 0;
	$balances[$ecoaccount."|||".$account]["now"] += $amount;
}
ob_start();
system("$period_then color=none php /svn/svnroot/Applications/newl.php csv");
$data = explode("\n",trim(ob_get_clean()));
foreach ($data as $curline) {
	$cols = str_getcsv($curline);
	$amount = $cols[5];
	$account = $cols[3];
	$ecoaccount = ecomap($account);
	if (!isset($balances[$ecoaccount."|||".$account]["then"])) $balances[$ecoaccount."|||".$account]["then"] = 0;
	$balances[$ecoaccount."|||".$account]["then"] += $amount;
}
$data = "Kontonr\tKontonavn\tIÅR\nSidsteÅR\n";
foreach ($balances as $key => $curbalance) {
	$x = explode("|||",$key);
	$kontonr = $x[0];
	$kontonavn = $x[1];
	if (!isset($curbalance["now"])) $curbalance["now"] = "";
	if (!isset($curbalance["then"])) $curbalance["then"] = "";
	$data .= "$kontonr\t$kontonavn\t$curbalance[now]\t$curbalance[then]\n";
	
}
echo "Saving /home/$op/tmp/Letregnskab_$bn.csv\n";
file_put_contents("/home/$op/tmp/Letregnskab_$bn.csv",$data);
function ecomap($konto) {
	$md = cleanFileName($konto);
	if (file_exists("/data/regnskaber/ecomap/$md")) return trim(file_get_contents("/data/regnskaber/ecomap/$md"));
	else {
		echo "Indtast e-conomic kontonummer for $konto: ";
		$nr = trim(fgets(STDIN));
		file_put_contents("/data/regnskaber/ecomap/$md",$nr);
		return $nr;
	}
}
function cleanFileName($file_name){ 
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION); 
    $file_name_str = pathinfo($file_name, PATHINFO_FILENAME); 
     
    // Replaces all spaces with hyphens. 
    $file_name_str = str_replace(' ', '-', $file_name_str); 
    // Removes special chars. 
    $file_name_str = preg_replace('/[^A-Za-z0-9\-\_]/', '', $file_name_str); 
    // Replaces multiple hyphens with single one. 
    $file_name_str = preg_replace('/-+/', '-', $file_name_str); 
     
    $clean_file_name = $file_name_str.'.'.$file_ext; 
     
    return $clean_file_name; 
}
?>
