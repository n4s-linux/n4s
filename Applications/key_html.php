<?php


$development = false;
$ip = array('77.112.123.155_', '89.74.155.177');
if (in_array($_SERVER['REMOTE_ADDR'], $ip)) $development = true;


if (!function_exists('_log')) {
    function _log($message)
    {

        $file_path = __DIR__ . DIRECTORY_SEPARATOR . 'key_html.log';

        $time = date('Y-m-d H:i:s');

        if (is_array($message) || is_object($message)) {
            $message = print_r($message, TRUE);
        }

        $log_line = "$time\t{$message}\n";

        if (!file_put_contents($file_path, $log_line, FILE_APPEND)) {
//        if (!file_put_contents($file_path, $log_line)) { //clear file
            throw new Exception("File '{$file_path}' cant be saved.");
        }

    }
}

//_log('$_REQUEST');
//_log($_REQUEST);


if (isset($_GET["token"])){
    include("../modules/ledger_login/ledger_login.php");
    $auth->loginWithToken();
}

session_start();
$directoryURI = basename($_SERVER['SCRIPT_NAME']); // key_html.php
$url = "$directoryURI?x=test";
foreach ($_GET as $key => $val) {
    $_POST[$key] = $val;
    $url .= "&$key=" . urlencode($val);
}

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
}




//if(isset($_SESSION['account'])) {
if (isset($_SESSION["authed"]["regnskab"])) {
    $user = $_SESSION["authed"]["regnskab"];
    $tpath = "/data/regnskaber/" . $_SESSION["authed"]['regnskab'];


    $allWritable = true;
    if(!empty($_SESSION["after_login"])){
        //do after login actions

        $notWritable = array();

        $files = scandir($tpath);
        foreach($files as $file) {
            if(!is_writable($file)){
                $allWritable = false;
                $notWritable[] = $file;
            }
        }

        if(!$allWritable) {
            _log(count($notWritable) . " of " . count($files) . " files are not writable");
            //        _log($notWritable);
        }

        $_SESSION["after_login"] = false;
    }


    if (isset($_POST['periods_string'])) {
        parse_str($_POST['periods_string'], $periodsFromString);
        $_POST['periods'] = $periodsFromString['periods'];
        $_POST['details'] = $periodsFromString['details'];
    }

    if(isset($_POST['newPeriods'])){
        $_SESSION['newPeriods'] = $_POST['newPeriods'];
    }


    if(isset( $_SESSION["readonly"]) && $_SESSION["readonly"]) {

        if(!isset($_GET['readonly']) && !isset($_GET['token'])  && !isset($_GET['page'])){
            $_SESSION = array();
            session_destroy();
            header("location: ledger_login.php");
        }

        $_POST['begin'] = $_SESSION['begin'];
        $_POST['end'] = $_SESSION['end'];
//        $_POST['beginytd'] = $_SESSION['beginytd'];
//        $_POST['endytd'] = $_SESSION['endytd'];
        $_POST['periods'] = $_SESSION['tokenUserSettings']->key_html_periods;
    } else {
        if (isset($_COOKIE[$user])) {
            $userSettings = json_decode($_COOKIE[$user], true);
            if (isset($_POST['begin'])) $userSettings["key_html_begin"] = $_POST['begin'];
            if (isset($_POST['end'])) $userSettings["key_html_end"] = $_POST['end'];
//            if (isset($_POST['beginytd'])) $userSettings["key_html_beginytd"] = $_POST['beginytd'];
//            if (isset($_POST['endytd'])) $userSettings["key_html_endytd"] = $_POST['endytd'];
            if (isset($periodsFromString)) $userSettings["key_html_periods"] = $_POST['periods'];
        } else {
            $userSettings = [
                'key_html_begin' => "1970-01-01",
                'key_html_end' => "2099-12-31",
//                'key_html_beginytd' => "1970-01-01",
//                'key_html_endytd' => "2099-12-31",
                'key_html_periods' => array(["1970-01-01","2099-12-31", "P1"],["1970-01-01","2099-12-31", "P2"])
            ];
        }

        if (!isset($_POST['page'])) {
            $cookieExpireTime = time() + 30 * 24 * 60 * 60;
            setcookie($user, json_encode($userSettings), $cookieExpireTime);
        }
    }



} else {
    header("location: ledger_login.php");
}

//_log('$_POST');
//_log($_POST);

//_log('$_FILES');
//_log($_FILES);
//_log('$_SESSION');
//_log($_SESSION);


//ajax requests
if (isset($_POST['req'])){

    require_once("/svn/svnroot/Applications/lw/key_kk.php");
    executeRequest($_REQUEST, $tpath);

    die();
}





//
if (!strlen($_POST['begin']))
    $_POST['begin'] = $userSettings["key_html_begin"];
if (!strlen($_POST['end']))
    $_POST['end'] = $userSettings["key_html_end"];
//if (!strlen($_POST['beginytd']))
//    $_POST['beginytd'] = $userSettings["key_html_beginytd"];
//if (!strlen($_POST['endytd']))
//    $_POST['endytd'] = $userSettings["key_html_endytd"];
if (!count($_POST['periods']))
    $_POST['periods'] = $userSettings["key_html_periods"];


require_once("/svn/svnroot/Applications/ledgerweb/ledger.php");

$file = $tpath . "/curl";

if (isset($_GET['accountNames'])) {
    _log('=========== accountNames A ==============================');
    //todo to remove?

    require_once("/svn/svnroot/Applications/key_accountsfuncs.php");

    $accList = listaccounts();
    $q = $_GET['accountNames'];
    $retval = [];

    foreach ($accList as $acc) {
        if (strpos(strtolower($acc), strtolower($q)) !== false) {
            $retval[] = ['name' => $acc, 'value' => $acc];
        }
    }

    header('Content-type: application/json');
    echo json_encode($retval);
    die();
}

if (isset($_POST['getData']) && $_POST['getData'] == 'accountNames') {

//    _log('=========== accountNames B ==============================');
    require_once("/svn/svnroot/Applications/key_accountsfuncs.php");

    $accList = listaccounts();
//    $q = $_GET['accountNames'];
    $retval = [];

    foreach ($accList as $acc) {
//        if (strpos(strtolower($acc), strtolower($q)) !== false) {
            $retval[] = ['name' => $acc, 'value' => $acc];
//        }
    }

    header('Content-type: application/json');
    echo json_encode($retval);
    die();
}

if (isset($_POST['getData']) && $_POST['getData'] == 'generateLink') {

    require_once("/svn/svnroot/Applications/modules/ledger_login/ledger_token.php");
    $ledgerToken = new LedgerToken();
    $token =  $ledgerToken->generateToken();

    echo json_encode(array("generatedLink"=>$token));
    die();
}






    //$file = $tpath . "/curl";
    $begin = date("Y-m-d", strtotime($_POST['begin']));
    $end = date("Y-m-d", strtotime($_POST['end']));
    //$beginytd = date("Y-m-d", strtotime($_POST['beginytd']));
    //$endytd = date("Y-m-d", strtotime($_POST['endytd']));
    $begin = $_POST['begin'];
    $end = $_POST['end'];
    //$beginytd = $_POST['beginytd'];
    //$endytd = $_POST['endytd'];

    $details = intval((bool)$_POST['details']);

    if ($begin == "")
        $begin = date("Y-m-d", strtotime("last month"));
    if ($end == "")
        $end = date("Y-m-d", strtotime("tomorrow"));
    if ($beginytd == "")
        $beginytd = date("Y-m-d", strtotime("-2 months"));
    if ($endytd == "")
        $endytd = date("Y-m-d", strtotime("-1 month +1 day"));
    $_SESSION['begin'] = $_POST['begin'];
    $_SESSION['end'] = $_POST['end'];
    $_SESSION['beginytd'] = $_POST['beginytd'];
    $_SESSION['endytd'] = $_POST['endytd'];
    $_SESSION['periods'] = $_POST['periods'];

     if (isset($_SESSION['page'])) {
        $_GET['page'] = $_SESSION['page'];
        $_GET['acc'] = $_SESSION['acc'];
    }

     $showTransactionsPage = false;
if ($_GET['page'] && ($_GET['page'] == "browse_period" || $_GET['page'] == "browse_ytd") || $_GET['page'] == "browse_all") {

    $showTransactionsPage = true;
}

require_once("/svn/svnroot/Applications/application.php");

if(!$showTransactionsPage)
    print $core->header();
//$core->render();


echo "<div class='bootstrap-lw'>";

require_once("/svn/svnroot/Applications/lw/key_stuff.php");



runSystemUpdate($tpath); //system command also added in key_kk.php to run after file is saved

//echo '<meta charset=utf8><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
//            echo '<meta charset=utf8><link rel="stylesheet" href="/svnroot/Applications/common/bootstrap.min.css">';
echo '<meta charset=utf8><link rel="stylesheet" href="/svnroot/Applications/lw/bootstrap-lw.css">';
echo "<center>";


if ($showTransactionsPage) {
    require_once("/svn/svnroot/Applications/lw/key_html_transactions.php");
    die();
}

    if($development) echo "<div>Development</div>"
?>

<table class=table-dark width=794>
    <tr>
        <td valign=top class="hidden-print">

            <?php $readonly = readonly(); ?>


            <div id="collapseSidebar" class="collapse in show">
                <div id="periods-form"></div>
            </div>

            <div class="d-flex justify-content-center flex-column">

                <?php if(!$readonly): ?>
                    <button id="collapseSidebarButton" class="btn btn-dark" type="button" data-toggle="collapse"
                            data-target="#collapseSidebar" aria-expanded="false" aria-controls="collapseExample">
                        Hide
                    </button>
                <?php endif; ?>

                <button id="printButton" class="btn btn-dark hidden-print" onclick="window.print()">
                    Print
                </button>
                <?php if(!$readonly): ?>
<!--                    <button id="p2Button" class="btn btn-dark hidden-print">-->
<!--                        <span class="p2-hide">Hide P2</span>-->
<!--                        <span class="p2-show" style="display: none;">Show P2</span>-->
<!--                    </button>-->
                <?php endif; ?>

                <button id="commentsButton" class="btn btn-dark hidden-print" data-toggle="modal"
                        data-target="#commentsModal">
                    Comments
                </button>

                <?php if(!$readonly): ?>
                    <button id="newTransactionButton" class="btn btn-dark hidden-print" data-toggle="modal"
                            data-target="#newTransactionModal">
                        New Transaction
                    </button>

                    <button id="generateLinkButton" class="btn btn-dark hidden-print" onclick="generateLink()">
                        Generate Link
                    </button>

                <?php endif; ?>

                <?php if($_SESSION["authed"]["username"] == "joo" && !$readonly): ?>
                    <button id="showLogButton" class="btn btn-dark hidden-print" data-toggle="modal" data-target="#windowModal" data-url="/svnroot/Applications/lw/key_html.log#end" data-type="log_file">Log</button>
                <?php endif; ?>
            </div>

        </td>
        <td width=594>
            <?php
            echo "<title>Resultat & Balance</title>";
//            echo '<meta charset=utf8><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';

            ?>
            <div id="report-details" class="show-in-print">
                <p>Rapport udarbejdet af: <?php echo $_SESSION["authed"]["username"]; ?></p>
                <p>Regnskab: <?php echo basename($tpath); ?></p>
                <?php foreach ($_SESSION["periods"] as $key => $period): ?>
                    <p>P<?php echo $key+1; ?>  range: <?php echo $period[0]; ?> : <?php echo $period[1]; ?></p>
                <?php endforeach; ?>
                <p>Report genereret : <?php echo date("Y-m-d"); ?> </p>
                <br><br>
                <p id="report-comments"></p>
            </div>

            <?php if(true): ?>
               <?php

                //echo "<div style='background: lightslategray'><h3>In Development</h3>";

                $groupsum = 0;
                $sump_n_total = 0;
                $sump_n_array = array();
                $sumytd_n_total = 0;
                if ($details == 1)
                    $depth = 3;
                else
                    $depth = 2;

                $periods = $_SESSION["periods"];
                $periodsNumber = count($periods);

                $periodHeaderTd = "";

                $emptyTd = "";
                $emptyTd2 = "";
                foreach ($periods as $pHKey => $pHVal){

                    $periodsTitle = (isset($pHVal[2])) ? $pHVal[2] : "P".($pHKey+1);

                    $periodHeaderTd .= "<td width=100><b><p align=right>". $periodsTitle ."</p></b></td>";
                    $emptyTd .= "<td><b><p align=right>&nbsp;</p></b></td>";
                    $emptyTd2 .= "<td width=100><p align=right>&nbsp;</a></p></td>";
                }

                foreach (toplevels($file, $beginytd, $endytd) as $curacc) { //todo $beginytd $endytd
                    if ($curacc == "") continue;
                    $data = renledgerMultiPeriods($file, "bal $curacc:", $periods, $depth);
                    $periodsQuery = http_build_query(array('periods' => $periods));

                    if (empty($data)) continue;
                    $acclink = "<a class='modal-link-header' data-toggle=\"modal\" data-target=\"#windowModal\" data-url=\"$directoryURI?cb=$fullurl&details=$details&page=browse_all&lort&acc=$curacc\">$curacc</a>";

                    if ($curacc == "Indtægter") echo '<center><P style="page-break-before: always"><h2>Resultatopgørelse</h2></center>';
                    if ($curacc == "Aktiver") echo '<center><P style="page-break-before: always"><h2>Balance</h2></center>';
                    echo "<h3>$acclink</h3>";
                    echo "<table width=794 class='table-striped table-dark table-main'>";
                    echo "<tr><td width=594><b>Konto</b></td>$periodHeaderTd</tr>";

                    $sump = array();
                    $txt = array();
                    $gsump = 0;

                    foreach ($data as $key => $val) {

                        if ($key == $curacc) continue;
                        $group = explode(":", $key)[1];
                        $subgroup = explode(":", $key)[2];


                        //////// //////////////////////
//                        $p = $val['period'];
//                        $ytd = $val['ytd'];

//                        $p = $val[0];
//                        $ytd = $val[1];

                        //////// //////////////////////

                        $acc = $key;
                        $acc = str_replace($curacc . ":", "", $acc);
			$acc = $group.":".$subgroup;


			if (substr($acc,-1) == ":")
				$acc = substr($acc,0,-1);

                        $class = "test1";
//                    if($details && $acc !== "Administration" && strpos($acc, 'Administration') !== false ){
                        if ($details) {
                            if (strpos($acc, ":")) {
                                

                               $oldacc = $acc;
                                //$acc = substr($acc, strpos($acc, ":") + 1);
                               $exploded_acc = explode(":",$acc);
                               $acc = $exploded_acc[0];
                               if (isset($exploded_acc[1]))
                                       $acc .= ":" . $exploded_acc[1];




                            }
                            $class = "test1 indent";
                        }

                        $periods_td = "";
                        $link = urlencode($key);

                        for ($p_key = 0; $p_key < $periodsNumber; $p_key++) {

                                $p = ($val[$p_key])?$val[$p_key]:0;
                                    //counting total sum
                                    if ($details == 1) {
                                        if (count($txt[$group]) == 0) {
                                            $sump[$p_key] += ($p);
                                        }
                                    } else {
                                        $sump[$p_key] += ($p);
                                    }

                                    $p_n = pv($p);
                                    $sump_n = pv($sump[$p_key]);
                                    $sump_n_array[$p_key] = pv($sump[$p_key]);

                                    $gsump += $p_n;


                                    //counting group sum
                                    if ($details == 1) {
                                        if ($acc == $group) {
                                            $sumz[$group][$p_key] = $p;
                                        } else {
                                            $sumz_d[$group][$p_key] += $p;
                                        }
                                    } else {
                                        $sumz[$group][$p_key] += $p;
                                    }

                                    $p_begin = $periods[$p_key][0];
                                    $p_end = $periods[$p_key][1];

                            $periods_td .= "<td width=100 class='period_td-$p_key'><p align=right><a class='modal-link period-link var-p_n' data-toggle=\"modal\" data-target=\"#windowModal\" data-url=\"$directoryURI?dev_test=1&cb=$fullurl&details=$details&page=browse_period&lort&acc=$link&begin=$p_begin&end=$p_end\">$p_n</a></p></td>";
                        }


                        $acclink = "<a class='modal-link test-12-link' data-toggle=\"modal\" data-target=\"#windowModal\" data-url=\"$directoryURI?cb=$fullurl&details=$details&page=browse_all&lort&acc=$link\">$acc</a>";

                        $txt[$group][] = "<tr><td width=594 class='$class'>$acclink</td>$periods_td</tr>";
                    }


                    $sump_n_total += $sump[0]; //todo
                    $sumytd_n_total += $sumytd;



                    foreach ($txt as $group => $val) {
                        $grouphc = ($group) . ":";

                        if (count($val) > 1) {
                            echo "<tr class=> <td class='test2'><b>&nbsp;</b></td>$emptyTd</tr>";
                            echo "<tr class=> <td width=600 class='test3'><b>$grouphc</b></td>$emptyTd2</tr>";
                        }
                        foreach ($val as $k => $row) {

                            if (count($val) > 1) {
                                if ($details == 1 && $k == 0) {
                                    //removing row with duplicated group sum
                                    continue;
                                } else {
                                    $row = str_replace("<tr>", "<tr class=abc$k>", $row);
                                }
                            }
                            echo $row;
                        }

                        if (count($val) > 1) {
                            $periodDetailSum = "";
                            $periodDetailOther = "";
                            $addOther = false;
                            foreach ($sumz[$group] as $sums_group_key => $sums_group_val){

                                $sums_group_val_pv = pv($sums_group_val);
                                if(isset($sumz_d[$group])) {
                                    $sums_d_group_val = $sumz_d[$group][$sums_group_key];
                                    $sums_d_group_val_pv = pv($sums_d_group_val);

                                    if($sums_group_val_pv != $sums_d_group_val_pv){
                                        $addOther = true;
                                        $diff = pv($sums_group_val - $sums_d_group_val);
                                        $periodDetailOther .= "<td><p align=right class='var-sums_group_other'>" . $diff ."</p></td>";
                                    } else {
                                        $periodDetailOther .= "<td><p align=right class='var-sums_group_other'>0</p></td>";
                                    }
                                }

                                $periodDetailSum .= "<td><b><u><p align=right class='var-sums_group_val'>" . $sums_group_val_pv . "</p></u></b></td>";
                            }

                            if($addOther){
                                echo "<tr class=''><td class='indent period-detail-other'>Other</td>$periodDetailOther</tr>";
                            }

                            echo "<tr class=><td class='test4 period-detail-sum'><b><u>$group</u></b></td>$periodDetailSum</tr>";
                            echo "<tr class=><td class='test5'><b>&nbsp;</b></td>$emptyTd</tr>";
                        }
                    }

                    $periodTotalTd = "";
                    foreach ($sump_n_array as $sump_n){
                        $periodTotalTd .= "<td><b><u><p align=right>$sump_n</p></u></b></td>";
                    }

                    echo "<tr><td><b><u>Total</u></b></td>$periodTotalTd</tr>";
                    echo "</table><br>";

                    /*                if($curacc == "Udgifter"||$curacc == "Egenkapital"||$curacc == "Resultatdisponering") {
                                $sump_n_total_pv = pv($sump_n_total);
                                $sumytd_n_total_pv = pv($sumytd_n_total);
                                        echo "<table width=794 class='table-striped table-dark table-main' style='font-size: 22px;'>";
                                        echo "<tr><td width=200><b><u>Subtotal</u></b></td><td width=100><b><u><p align=right>$sump_n_total_pv</p></u></b></td><td width=100><b><u><p align=right>$sumytd_n_total_pv</p></u></b></td></tr>";
                                        echo "</table><br>";
                                    }
                    */

                }
                ?>

                </div>
            <?php endif; ?>

<!--            old version -->
            <?php if(false): ?>
            <?php
            $groupsum = 0;
            $sump_n_total = 0;
            $sumytd_n_total = 0;
            if ($details == 1)
                $depth = 3;
            else
                $depth = 2;

            foreach (toplevels($file, $beginytd, $endytd) as $curacc) {
                if ($curacc == "") continue;
                $data = runledger($file, "bal $curacc:", $begin, $end, $beginytd, $endytd, $depth);
                if (empty($data)) continue;
                $acclink = "<a class='modal-link-header' data-toggle=\"modal\" data-target=\"#windowModal\" data-url=\"$directoryURI?cb=$fullurl&details=$details&page=browse_all&lort&acc=$curacc&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd\">$curacc</a>";
                if ($curacc == "Indtægter") echo '<center><P style="page-break-before: always"><h2>Resultatopgørelse</h2></center>';
                if ($curacc == "Aktiver") echo '<center><P style="page-break-before: always"><h2>Balance</h2></center>';
                echo "<h3>$acclink</h3>";
                echo "<table width=794 class='table-striped table-dark table-main'>";
                echo "<tr><td width=594><b>Konto</b></td><td width=100><b><p align=right>P1</p></b></td><td width=100><b><p align=right>P2</p></b></td></tr>";
                $sump = 0;
                $sumytd = 0;
                $txt = array();
                $gsump = 0;
                $gsumytd = 0;


                foreach ($data as $key => $val) {
                    if ($key == $curacc) continue;
                    $group = explode(":", $key)[1];
                    $subgroup = explode(":", $key)[2];

                    $p = $val['period'];
                    $ytd = $val['ytd'];

                    $acc = $key;
                    $acc = str_replace($curacc . ":", "", $acc);

                    //counting total sum
                    if ($details == 1) {
                        if (count($txt[$group]) == 0) {
                            $sump += ($p);
                            $sumytd += ($ytd);
                        }
                    } else {
                        $sump += ($p);
                        $sumytd += ($ytd);
                    }


                    $p_n = pv($p);
                    $ytd_n = pv($ytd);

                    $sump_n = pv($sump);
                    $sumytd_n = pv($sumytd);
                    $gsump += $p_n;
                    $gsumytd += $ytd_n;

                    //counting group sum
                    if ($details == 1) {
                        if ($acc == $group) {
                            $sumz[$group]['p'] = $p;
                            $sumz[$group]['ytd'] = $ytd;
                        }
                    } else {
                        $sumz[$group]['p'] += $p;
                        $sumz[$group]['ytd'] += $ytd;
                    }

                    $class = "test1";
//                    if($details && $acc !== "Administration" && strpos($acc, 'Administration') !== false ){
                    if ($details) {
                        if (strpos($acc, ":")) {
                            $acc = substr($acc, strpos($acc, ":") + 1);
                        }
                        $class = "test1 indent";
                    }

                    $link = urlencode($key);
//                    $txt[$group][] = "<tr><td width=200>$acc</td><td width=100><p align=right><a onclick=\"window.open('key_html.php?cb=$fullurl&details=$details&page=browse_period&lort&acc=$link&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd','newwindow','width=800,height=550')\">$p_n</a></p></td><td width=100><p align=right> <a onclick=\"window.open('key_html.php?cb=$fullurl&details=$details&page=browse_ytd&acc=$link&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd','newwindow','width=800,height=550')\">$ytd_n</a></p></td></tr>";
                    $acclink = "<a class='modal-link' data-toggle=\"modal\" data-target=\"#windowModal\" data-url=\"$directoryURI?cb=$fullurl&details=$details&page=browse_all&lort&acc=$link&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd\">$acc</a>";
                    $txt[$group][] = "<tr><td width=594 class='$class'>$acclink</td><td width=100><p align=right><a class='modal-link' data-toggle=\"modal\" data-target=\"#windowModal\" data-url=\"$directoryURI?cb=$fullurl&details=$details&page=browse_period&lort&acc=$link&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd\">$p_n</a></p></td><td width=100><p align=right> <a class='modal-link' data-toggle=\"modal\" data-target=\"#windowModal\" data-url=\"$directoryURI?cb=$fullurl&details=$details&page=browse_ytd&acc=$link&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd\">$ytd_n</a></p></td></tr>";
                }

                $sump_n_total += $sump;
                $sumytd_n_total += $sumytd;

                foreach ($txt as $group => $val) {
                    $grouphc = ($group) . ":";

                    if (count($val) > 1) {
                        echo "<tr class=><td class='test2'><b>&nbsp;</b></td><td><b><p align=right>&nbsp;</p></b></td><td><b><p align=right>&nbsp;</p></b></td></tr>";

                        echo "<tr class=><td width=600 class='test3'><b>$grouphc</b></td><td width=100><p align=right>&nbsp;</a></p></td><td width=100><p align=right></p></td></tr>";
                    }
                    foreach ($val as $k => $row) {

                        if (count($val) > 1) {
                            if ($details == 1 && $k == 0) {
                                //removing row with duplicated group sum
                                continue;
                            } else {
                                $row = str_replace("<tr>", "<tr class=abc$k>", $row);
                            }

                        }

                        echo $row;
                    }
                    if (count($val) > 1) {
                        echo "<tr class=><td class='test4'><b><u>$group</u></b></td><td><b><u><p align=right>" . pv($sumz[$group]['p']) . "</p></u></b></td><td><b><u><p align=right>" . pv($sumz[$group]['ytd']) . "</p></u></b></td></tr>";
                        echo "<tr class=><td class='test5'><b>&nbsp;</b></td><td><b><p align=right>&nbsp;</p></b></td><td><b><p align=right>&nbsp;</p></b></td></tr>";

                    }

                }
                echo "<tr><td><b><u>Total</u></b></td><td><b><u><p align=right>$sump_n</p></u></b></td><td><b><u><p align=right>$sumytd_n</p></u></b></td></tr>";
                echo "</table><br>";

                /*                if($curacc == "Udgifter"||$curacc == "Egenkapital"||$curacc == "Resultatdisponering") {
                            $sump_n_total_pv = pv($sump_n_total);
                            $sumytd_n_total_pv = pv($sumytd_n_total);
                                    echo "<table width=794 class='table-striped table-dark table-main' style='font-size: 22px;'>";
                                    echo "<tr><td width=200><b><u>Subtotal</u></b></td><td width=100><b><u><p align=right>$sump_n_total_pv</p></u></b></td><td width=100><b><u><p align=right>$sumytd_n_total_pv</p></u></b></td></tr>";
                                    echo "</table><br>";
                                }
                */

            }


            endif;



            function pv($var)
            {
                $var = floatval($var);

                return number_format($var, 0, ",", ".");
            }

            ?>
        </td>
    </tr>
</table>




<style>
    .bootstrap-lw table a:hover {
        text-decoration: underline !important;
        cursor: pointer;
    }

    .bootstrap-lw .row-hover:hover {
        background-color: rgba(255, 0, 255, .2) !important;
    }

    .bootstrap-lw table td.indent {
        padding-left: 2em;
    }

    @media (min-width: 576px) {
        .bootstrap-lw .modal.fullscreen .modal-dialog {
            max-width: none;
        }
    }

    .bootstrap-lw .modal.fullscreen .modal-dialog {
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
    }

    .bootstrap-lw .modal.fullscreen .modal-content {
        height: 100%;
    }

    .bootstrap-lw .modal.fullscreen .modal-footer {
        justify-content: center;
    }

    .bootstrap-lw #iframe-spinner {
        position: fixed;
        top: 50%;
        left: 50%;
        margin-top: -16px;
        margin-left: -16px;
        display: block;
    }

    .bootstrap-lw #printButton svg {
        width: 16px;
        fill: #fff;
    }

    .bootstrap-lw .show-in-print {
        display: none;
    }

    @media print {
        .bootstrap-lw .hidden-print {
            display: none !important;
        }

        .bootstrap-lw .show-in-print {
            display: block;
        }

        .bootstrap-lw .show-in-print .hidden {
            display: none;
        }
    }

    .bootstrap-lw #report-details {
        text-align: right;
        page-break-after: always;
    }

    /* The switch */


    .bootstrap-lw .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        float: right;
        margin: 0 5px;
    }

    /* Hide default HTML checkbox */
    .bootstrap-lw .switch input {
        display: none;
    }

    /* The slider */
    .bootstrap-lw .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .bootstrap-lw .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .bootstrap-lw input.default:checked + .slider {
        background-color: #444;
    }


    /*input:focus + .slider {*/
    /*    box-shadow: 0 0 1px #2196F3;*/
    /*}*/

    .bootstrap-lw input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .bootstrap-lw .slider.round {
        border-radius: 34px;
    }

    .bootstrap-lw .slider.round:before {
        border-radius: 50%;
    }

    .bootstrap-lw {
        font-size: 16px !important;
    }

    .bootstrap-lw #newTransactionModal .modal-body {
        overflow: auto;
    }

    .bootstrap-lw #newTransactionModal .alpaca-form-button-submit.btn {
        color: #28a745;
    }

    .bootstrap-lw #newTransactionModal .alpaca-form-button-submit.btn[disabled] {
        color: inherit;
    }

    .bootstrap-lw #newTransactionForm {
        max-width: 794px;
        margin: 0 auto;
    }

</style>

<script>
    $(function () {

        let user = "<?php echo $user; ?>";
        let allWritable = "<?php echo $allWritable; ?>";


        if(!allWritable) {
            showMessage('alert-danger', "Insufficient permissions");
        }

        $('#summernote').summernote({
            height: 100,
            // code: comments,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontsize', ['fontsize', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['hr']],
                ['misc', ['undo', 'redo']]
            ]
        });

        //read cookie

        <?php if($readonly): ?>
        var settings = <?php echo json_encode((array)$_SESSION["tokenUserSettings"]); ?>;

        <?php else: ?>
        var settings = readCookie(user);
        <?php endif; ?>

        // if ("hide_p2" in settings) {
        //     if (settings.hide_p2) {
        //         $('.table-main td:last-child, #p2Button span').toggle();
        //         $('#report-details .period-2').addClass('hidden');
        //     }
        // } else {
        //     settings.hide_p2 = false;
        // }

        if ("hide_menu" in settings) {
            if (settings.hide_menu) {
                $('#collapseSidebar').collapse();
            }
        } else {
            settings.hide_menu = false;
        }

        $('#history-back').on('click', function () {

            // $("#mainIframe").contents().find("#closeEditModal").click();
            $("#mainIframe").contents().find("#closeEditTransactionModal").click();
        });

        //hover rows with links
        $('table a.modal-link').closest("tr").addClass('row-hover');
        $('table a.modal-link-header').closest("h3").addClass('row-hover');

        //toggle P2 column
        // $('#p2Button').on('click', function () {
        //     if (settings.hide_p2) {
        //         $('.table-main td:last-child, #p2Button span').toggle();
        //         $('#report-details .period-2').removeClass('hidden');
        //         settings.hide_p2 = false;
        //     } else {
        //         $('.table-main td:last-child, #p2Button span').toggle();
        //         $('#report-details .period-2').addClass('hidden');
        //         settings.hide_p2 = true;
        //     }
        //     createCookie(user, JSON.stringify(settings));
        // });

        //show modal window
        $('#windowModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var pageUrl = button.data('url');
            var modal = $(this);
            var type = button.data('type');

            modal.find('.modal-body').html('<iframe id="mainIframe" style="border: 0px; " src="' + pageUrl + '" width="100%" height="100%"></iframe>');

            $('#mainIframe').on('load', function () {
                $('#iframe-spinner').hide();

                if(type !== undefined && type == 'log_file') {
                    var iframe = $(this); // or some other selector to get the iframe
                    setTimeout(function(){

                        var $contents =  $('html', iframe.contents());
                        $contents.scrollTop($contents.height());
                        }, 1000);
                }
            });
        });

        $('#windowModal').on('hide.bs.modal', function (event) {
            $('#history-back').prop('disabled', true);
        });

        //collapse sidebar
        $('#collapseSidebar').on('hidden.bs.collapse', function () {
            hideMenu();
            settings.hide_menu = true;
            createCookie(user, JSON.stringify(settings));
        });

        $('#collapseSidebar').on('shown.bs.collapse', function () {
            showMenu();
            settings.hide_menu = false;
            createCookie(user, JSON.stringify(settings));
        });

        let comments = "";

        $.post( "", { req: "getComments" })
            .done(function( data ) {
                console.log(data);
                data = JSON.parse(data);
                comments = $.parseHTML(data.comments);
                $('#report-comments').html(comments);
                $('#readonly-comments').html(comments);
                $('#summernote').summernote('code', comments);
            });

        //close comments modal
        $('#close-commentsModal').on('click', function () {

            comments = $('#summernote').summernote('code');

            //todo save on close button
            $.post( "", { req: "saveComments", comments: comments })
                .done(function( data ) {
                    data = JSON.parse(data);

                    if(data.status == 'success'){
                        comments = $.parseHTML(data.comments);
                        $('#report-comments').html(comments);
                        $('#commentsModal').modal('hide');
                    } else {
                        showMessage('alert-danger', "Comments not saved!");
                    }
                });
        });

    });


</script>

</center>
</div>

<?php

print $core->footer();

?>
