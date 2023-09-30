<?php

function getJsonToEdit($tpath,$fn) {

    if(!$GLOBALS['development']){
        $fn = explode("Filename: ", $fn)[1];
        $fn = explode("\\\n",$fn)[0];
        $fn = explode("\n",$fn)[0];
    } else {

//        dev file
//        $fn = "20201016_dev_n4dxbnan6.trans";
//        $fn = "20201016_dev_i3d6spvey.trans";
        $fn = "00_DEVELOPMENT.trans";

    }
//    $data = file_get_contents("$tpath/".$fn);
    $data = json_decode(file_get_contents("$tpath/".$fn),true);

    if(isset($data["Filereferences"])){
        foreach ($data["Filereferences"] as $key => $filereference) {
            $data["Filereferences"][$key]['filepath'] = basename($filereference['filepath']);
        }
    }

    $readonly = false;
    if(isset($_SESSION["readonly"]) && $_SESSION["readonly"]){
        $readonly = true;
    }

    $data = json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

    $response_array['fileData'] = $data;
    $response_array['readonly'] = $readonly;

//    _log($response_array);

//    echo json_encode($response_array);
    return $response_array;
}

function getThumbnail($fn, $tpath){

    $src = "";

    //check for img_tmp directory
    if (!file_exists("$tpath/img_tmp/")) {
        mkdir("$tpath/img_tmp/", 0777);
    }

    $thumb_file = "$tpath/img_tmp/".$fn;

    if (!file_exists($thumb_file)) {

        $file_original = "$tpath/img/".$fn;

        $mime = mime_content_type( $file_original );

        if($mime == "application/pdf"){
            $info = pathinfo($file_original);
            $output = "$tpath/img_tmp/". $info['filename'] . '.' . "jpg";

            if( !file_exists($output)){
                exec("gs -dSAFER -dBATCH -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -r300 -sOutputFile=$output $file_original");
            }
            $thumb_file = $output;
        } else {
            $thumb_file = $file_original;
        }
    }

    $imageData = base64_encode(file_get_contents($thumb_file));
    $src = 'data: '.mime_content_type($thumb_file).';base64,'.$imageData;

    return $src;
}

function kk($tpath,$fn){

//    todo to remove, used in old transaction edit

    if(!$GLOBALS['development']){
        $fn = explode("Filename: ", $fn)[1];
        $fn = explode("\\\n",$fn)[0];
        $fn = explode("\n",$fn)[0];
//	echo "$tpath/".$fn;
        $data = json_decode(file_get_contents("$tpath/".$fn),true);
    } else {
        //dev file
        echo "<br>";
        $file = "00_DEVELOPMENT.trans";
        echo "Dev version " . $file ;
        $data = json_decode(file_get_contents($file),true);
    }


	//show file content
//	echo prettytrans($data);



	//show and edit
	echo prettytransWithEdit($data);return;


die();
	$acc = array($acc);
	echo "kk $tpath $acc<br>";
	$dl = "|\t|";
	system("tpath=\"$tpath\" LEDGER_SORT=date LEDGER_DEPTH=9999999999999999999 php /svn/svnroot/Applications/key.php ledger r $curacc --register-format=\"%(account)$dl%(code)$dl%(payee)$dl%(display_amount)$dl%(date)$dl" . "%(tag('Filename'))\n\" > /tmp/kkout.csv");
	$data = explode("\n",file_get_contents("/tmp/kkout.csv"));
	$kk = array();
	foreach ($data as $line) {
		$ld = explode($dl,$line);
		$account = explode($curacc.":",$ld[0])[1];
		$code = $ld[1];
		$payee = $ld[2];
		$amount = round($ld[3],2);
		$date = $ld[4];
		$fn = $ld[5];
		if (!isset($kk[$account]))
			$kk[$account] = array();
		array_push($kk[$account],array('Account'=>$account,'Code'=>$code,'Payee'=>$payee,'Amount'=>$amount,'Date'=>$date,'Filename'=>$fn));

	}
	$bal = 0;
print_r($kk);
die();
	foreach ($acc as $trans) {
		if ($trans['Account'] == "") continue;
		foreach ($trans as $key => &$val) {
			if (strlen($val)) $val == "&nbsp";
		}
		if ($bal == 0) {
			echo "<h3>$trans[Account]</h3>";
			echo "<table class='table-striped' width=100% >";
			echo "<tr><td width=15%>Dato</td><td width=15%>Beskrivelse</td><td width=15%>Code</td><td width=10%>Beløb</td><td width=10%>Balance</td>";
		}	
		$bal += $trans['Amount'];
		$link = "";
		$linkend = "";
		$back = "";
		$backend = "";
		$backlink = "";
		if (isset($trans['Filename']) && strlen($trans['Filename']) > 0) {
			$link = "<a href=#$trans[Filename]>";
			$linkend = "</a>";
			$back = "<a name=back_$trans[Filename]>";
			$backend = "</a>";
			$backlink = "back_$trans[Filename]";
			
		}
		$af = number_format($trans['Amount'],2,",",".");
		$bf = number_format($bal,2,",",".");
		echo "$back<tr><td width=50>$link $trans[Date] $linkend</td><td width=50>$link $trans[Payee] $linkend</td><td width=30>$trans[Code]</td><td width=50><p align=right>$af</p></td><td width=50><p align=right>$bf</p></td></tr>$backend\n";
		if (strlen($trans['Filename']) > 0)
			$fns .= "<h3><a name=$trans[Filename]>$trans[Filename]</a>:</h3>" . prettytrans(json_decode(file_get_contents("$tpath/".$trans["Filename"]),true),$backlink) . "<br><br>";

	}
	echo "</table><br>";

unlink("/tmp/kkout.csv");
echo "<br><br><br>";
}
//echo "$fns";

function prettytrans($trans,$backlink = "google.dk") {
	$r .= "<table class='table-striped table-dark'>";
	foreach ($trans as $elem=>$key) {
		if (!is_array($key)) {
            if($elem == 'filepath') {
                $filename = basename($key);
                $r .= "<tr><td>$elem</td><td><a href='#' class='show-pdf-modal' data-file='$key'>$filename</a><span class='close delete-file' data-file='$key' data-href='#' data-toggle='modal' data-target='#confirm-delete-file'>x</span></td></tr>";
            } elseif ($key != "")
                $r .= "<tr><td>$elem</td><td>$key</td></tr>";
		}
		else {
			$r .= "<tr><td>$elem</td><td>" . prettytrans($key,null) . "</td></tr>";
		}
	}
	return $r . "</table>";
}

function prettytransWithEdit($trans,$backlink = "google.dk", $name = "fileEdit") {

	$r .= "<table class='table-striped table-dark table-edit'>";
	foreach ($trans as $elem=>$key) {
		$fieldName = $name . "[$elem]";
		if (!is_array($key)) {
			if ($key != "") {
				if(!in_array($elem, ["Filename"])){
				    if($elem == 'Amount') $key = pv2($key);
					$r .= "<tr><td>$elem</td><td><input class=\"form-control\" style=\"width: 100%;\" type=\"text\" name=\"$fieldName\" value=\"$key\"></td></tr>";
				} else {
					$r .= "<tr><td>$elem</td><td>$key</td></tr>";
				}
			}
		}
		else {
            if($elem !== 'Filereferences' && $elem !== 'History'){
                $r .= "<tr><td>$elem</td><td>" . prettytransWithEdit($key,null, $fieldName) . "</td></tr>";
            } else {
                $r .= "<tr><td>$elem</td><td>" . prettytrans($key,null) . "</td></tr>";
            }
		}
	}
	return $r . "</table>";
}

function createTransactionFile($tpath, $postData){

    if(isset($_SESSION['readonly']) && $_SESSION['readonly'] ){
        return;
    }

    _log('createTransactionFile');

    $newData = $postData['fileEdit'];

    $fileName = $newData['Filename'];

    $fullPath = "$tpath/".$fileName ;


    if(isset($postData['Filereferences'])) {
        $fileAdded = false;

        foreach ($postData['Filereferences'] as $fileRef){
            $fileRef['upload date'] = date('Y-m-d');
            $fileRef['upload by'] = $_SESSION["authed"]["regnskab"];

            $newData['Filereferences'][] = $fileRef;
            $fileAdded = true;
        }
    }

        $newHistory = ['Date' => date('Y-m-d') , 'Desc' => 'Created by ' .  $_SESSION["authed"]["regnskab"] ];
        $newData['History'] = array();
        array_push( $newData['History'], $newHistory);

        $str = json_encode($newData,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        _log('createTransactionFile');

    try {
        _log('createTransactionFile 1');

        if(file_put_contents($fullPath, $str) ) {

            _log('createTransactionFile 1 . 1');
            _log($fullPath);
            $response_array['status'] = 'success';
            //command from key_html.php to run after file is saved
            system("tpath=\"$tpath\" php /svn/svnroot/Applications/key.php ledger bal  >/tmp/bal.foobar");
            $_SESSION['system_command'] = time();
        } else {
            _log('createTransactionFile 1 . 2');
            $response_array['status'] = 'failure';
        }
    } catch (Exception $e) {
        _log('--EX--');
        _log($e->getMessage());
//        echo "<div class=\"alert alert-danger\" role=\"alert\">Caught exception: $e->getMessage()</div>";
    }

    _log('createTransactionFile 2');

    $response_array['fileName'] = $fileName;
    $response_array['fullPath'] = $fullPath;

//    echo json_encode($response_array);
    return $response_array;

}

function updateFileNew($tpath,$fn, $postData) {

    if(isset($_SESSION['readonly']) && $_SESSION['readonly'] ){
        return;
    }

    _log('updateFileNew');

    $development = false;
    $ip = array('77.112.123.155', '89.74.155.177');
    if (in_array($_SERVER['REMOTE_ADDR'], $ip)) $development = true;

    if(!$development){
        $fn = $postData['Filename'];

    } else {
        $fn = '00_DEVELOPMENT.trans';
    }
    $fullPath = "$tpath/".$fn ;
    _log($fullPath);
    _log($postData);
    $data = json_decode(file_get_contents($fullPath),true);

    $fileRemoved = false;
    if(!empty($_POST['deleteFile']))
    {
        $fileToRemove = "$tpath/img/".$_POST['deleteFile'];
        $updatedData = removeFile($data, $fileToRemove);
        if ($updatedData) {
            $response_array['removedFile'] = $_POST['deleteFile'];
            $fileRemoved = true;
        }

    } else {
        $updatedData = updateData($data, $postData);
    }

    $changes = $updatedData[1];
    $newData = $updatedData[0];

    if(isset($postData['Filereferences'])) {
        $fileAdded = false;

        $response_array['newFiles'] = [];

        foreach ($postData['Filereferences'] as $key => $fileRef){
            $newData['Filereferences'][] = $fileRef;
            $fileAdded = true;
        }
        unset($postData['Filereferences']);
    }

    $newData =  array_merge($newData, $postData);

    if(count($newData) > $updatedData[0]) {
            $changes .= "new value ";
    }

    if(!$changes && !$fileAdded&& !$fileRemoved) {
        $response_array['fileName'] = $fn;
        $response_array['fullPath'] = $fullPath;
        return $response_array;
    }

    if($changes) {
        $newHistory = ['Date' => date('Y-m-d') , 'Desc' => 'Changed by ' .  $_SESSION["authed"]["regnskab"] ];

        if(!isset($newData['History'])) $newData['History'] = array();

        array_push( $newData['History'], $newHistory);
    }

    $str = json_encode($newData,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

    if (file_exists($fullPath)) {
        try {
            if(file_put_contents($fullPath, $str) ) {

                _log('FILE SAVED');
                _log($fullPath);
                _log($str);

                if($fileRemoved){
                   // echo "<div class=\"alert alert-success\" role=\"alert\">File Removed</div>";
                } else {
                    $response_array['status'] = 'success';
                }
                //command from key_html.php to run after file is saved
                system("tpath=\"$tpath\" php /svn/svnroot/Applications/key.php ledger bal  >/tmp/bal.foobar");
                $_SESSION['system_command'] = time();

                if(isset($_POST['save_and_hide'])) {
                    ?>
                    <script>
                        window.parent.$('#windowModal').modal('hide');
                    </script>
                    <?php
                }
            } else {
                _log("File: $fullPath Not Saved");
               // echo "<div class=\"alert alert-danger\" role=\"alert\">File: $fullPath Not Saved</div>";
            }
        } catch (Exception $e) {
            _log("Caught exception: $e->getMessage()");
           // echo "<div class=\"alert alert-danger\" role=\"alert\">Caught exception: $e->getMessage()</div>";
        }
    } else {
        //echo "The file $fullPath does not exist";
    }


    if(isset($newData["Filereferences"])){
        foreach ($newData["Filereferences"] as $key => $filereference) {
            $newData["Filereferences"][$key]['filepath'] = basename($filereference['filepath']);
        }
    }

    $response_array['fileName'] = $fn;
    $response_array['fullPath'] = $fullPath;
    $response_array['newData'] = $newData;

//    echo json_encode($response_array);
    return $response_array;

}

function updateFile($tpath,$fn, $postData) {



    if(isset($_SESSION['readonly']) && $_SESSION['readonly'] ){
        return;
    }
    _log('updateFile');
//    todo to remeve

    $ipAddress = $_SERVER['REMOTE_ADDR'] ;
    $development = false;
    $ip = array('77.112.123.155', '89.74.155.177');
    if (in_array($_SERVER['REMOTE_ADDR'], $ip)) $development = true;
//    $development = false;

    if(!$development){
        $fn = explode("Filename: ", $fn)[1];
        $fn = explode("\\\n",$fn)[0];
        $fn = explode("\n",$fn)[0];
        $fullPath = "$tpath/".$fn ;
        $data = json_decode(file_get_contents($fullPath),true);
    } else {
        //dev
	$fileName = "00_DEVELOPMENT.trans";
	$data = json_decode(file_get_contents($fileName),true);
	$fullPath = dirname(__FILE__) . '/' . $fileName;
    }

    _log('-- file save -- full path -- ');
    _log($fullPath);
    _log($postData);


    $fileRemoved = false;
    if(!empty($_POST['deleteFile']))
    {

        $updatedData = removeFile($data, $_POST['deleteFile']);
        if ($updatedData) {

            $fileRemoved = true;
        }

    } else {
        $updatedData = updateData($data, $postData);
    }

    $changes = $updatedData[1];
    $newData = $updatedData[0];

    if(isset($postData['Filereferences'])) {
        $fileAdded = false;

        foreach ($postData['Filereferences'] as $fileRef){
            $fileRef['upload date'] = date('Y-m-d');
            $fileRef['upload by'] = $_SESSION["authed"]["regnskab"];

            $newData['Filereferences'][] = $fileRef;
            $fileAdded = true;
        }
    }

    if(!$changes && !$fileAdded&& !$fileRemoved) {
        if(isset($_POST['save_and_hide'])) {
            ?>
            <script>
                window.parent.$('#windowEditModal').modal('hide');
            </script>
            <?php
        }
        return;
    }

    if($changes) {
        $newHistory = ['Date' => date('Y-m-d') , 'Desc' => 'Changed by ' .  $_SESSION["authed"]["regnskab"] ];

        if(!isset($newData['History'])) $newData['History'] = array();

        array_push( $newData['History'], $newHistory);
    }

	$str = json_encode($newData,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

    if (file_exists($fullPath)) {
        try {
            if(file_put_contents($fullPath, $str) ) {
                if($fileRemoved){
                    echo "<div class=\"alert alert-success\" role=\"alert\">File Removed</div>";
                } else {
                    echo "<div class=\"alert alert-success\" role=\"alert\">File Saved</div>";
                }
                //command from key_html.php to run after file is saved
                system("tpath=\"$tpath\" php /svn/svnroot/Applications/key.php ledger bal  >/tmp/bal.foobar");
                $_SESSION['system_command'] = time();

                if(isset($_POST['save_and_hide'])) {
                    ?>
                    <script>
                        window.parent.$('#windowModal').modal('hide');
                    </script>
                    <?php
                }
            } else {
                echo "<div class=\"alert alert-danger\" role=\"alert\">File: $fullPath Not Saved</div>";
            }
        } catch (Exception $e) {
            echo "<div class=\"alert alert-danger\" role=\"alert\">Caught exception: $e->getMessage()</div>";
        }
    } else {
        echo "The file $fullPath does not exist";
    }
}

function updateData(&$data, $postData, &$changes = ''){
    foreach ($data as $elem=>$key) {
        if (!is_array($data[$elem])) {

//            if($elem == 'Amount') {
//                $postData[$elem] =  pv2_reset($postData[$elem]);
//            }

            if( (isset($postData[$elem]) || empty($postData[$elem]) ) && $data[$elem] != $postData[$elem]){
                if($elem !== 'SpiirID') {
                    if (!empty($postData[$elem]) && is_numeric($postData[$elem])) $postData[$elem] = (float) $postData[$elem];
                }
                $data[$elem] = $postData[$elem];
                $changes .= "$elem ";
            }
        } else if($elem !== 'History' && $elem !== 'Filereferences'){
            foreach ($data[$elem] as $i => $val) {
//				$changes .= "$i: ";
                updateData($data[$elem][$i], $postData[$elem][$i], $changes);
            }

        }
    }

	return [$data, $changes];
}

function removeFile(&$data, $file){

    foreach ($data['Filereferences'] as $elem=>$key){
        if ($key['filepath'] == $file) {
            if (!unlink($file)) {
                echo ("$file cannot be deleted due to an error");
                return false;
            }
            else {
                //file has been deleted
                array_splice($data['Filereferences'], $elem, 1);
                return [$data];
            }

        }
    }


}

function saveUploadedFiles($tpath, $filesToUpload) {

    if(isset($_SESSION['readonly']) && $_SESSION['readonly'] ){
        return;
    }

    $target_dir = $tpath . "/img/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir);
    }

    $git_command = "cd $tpath; git lfs track \"img/**\"";
    exec($git_command);

    for ($i = 0; $i < count($filesToUpload["filesToUpload"]["name"]); $i++) {

        $files = glob($target_dir . "*.pdf");

        $numbers = [];
        if ($files) {
            foreach ($files as $file) {
                $numbers[] = basename("$file", ".pdf");
            }
            $filecount = max($numbers) + 1;
        } else {
            $filecount = 1;
        }

        $target_file_name = $filecount . ".pdf";

        $target_file = $target_dir . $target_file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo(basename($filesToUpload["filesToUpload"]["name"][$i]), PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["file_edit_submit"])) {
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg"
                || $imageFileType == "gif") {
                $check = getimagesize($filesToUpload["filesToUpload"]["tmp_name"][$i]);
                if ($check !== false) {
                    //    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                 //   echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            //echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
//            if ($filesToUpload["filesToUpload"]["size"][$i] > 500000) {
//                echo "Sorry, your file is too large.";
//                $uploadOk = 0;
//            }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "pdf") {
           // echo "Sorry, only JPG, JPEG, PNG, GIF & PDF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
           // echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            $sourceFile = $filesToUpload["filesToUpload"]["tmp_name"][$i];

            if ($imageFileType == 'pdf') {
                move_uploaded_file($sourceFile, $target_file);
            } else {
                $cmd = "$sourceFile $target_file";
                exec("convert $cmd ");
            }

            if (file_exists($target_file)) {

                _log('### file saved ###');
                chmod($target_file, 0777);

                $_POST['fileEdit']['Filereferences'][] = [
//                        'filepath' => $target_file,
                        'filepath' => "img/" . $target_file_name,
                        'upload_date' => date('Y-m-d'),
                        'upload_by' => $_SESSION["authed"]["regnskab"]

                ];
                //   echo "The file ". basename( $filesToUpload["filesToUpload"]["name"][$i]). " has been uploaded.";
            } else {
                _log('Sorry, there was an error uploading your file.');
            }
        }
    }

}

function saveComments($tpath, $comments){

    if(isset($_SESSION['readonly']) && $_SESSION['readonly'] ){
        return;
    }

    $fullPath = "$tpath/comments" ;

    try {
        if(file_put_contents($fullPath, $comments) ) {
            $response_array['status'] = 'success';
            $response_array['comments'] = $comments;
        } else {
            $response_array['status'] = 'failure';
        }
    } catch (Exception $e) {
        _log('--EX--');
        _log($e->getMessage());
    }

    return $response_array;
}

function getComments($tpath) {
    $fullPath = "$tpath/comments" ;

    $comments = "";
    if (file_exists($fullPath)) {
        $comments = file_get_contents($fullPath);
    }

    $response_array['comments'] = $comments;

    return $response_array;
}

function pv2($var) {
    $var = floatval($var);

    return number_format($var,2,",",".");
}
function pv2_reset($var) {

    $var = floatval(str_replace(',', '.', str_replace('.', '', $var)));

    return $var;
}

function executeRequest($request, $tpath){


    $response_array = [];

    switch ($request['req']) {
        case 'fileForm-edit-new':

            if (isset($_FILES)) {
                saveUploadedFiles($tpath, $_FILES);
            }

            unset($_POST['req']);
            $response_array = updateFileNew($tpath, $_GET['fn'], $_POST['fileEdit']);
            break;

        case 'fileForm-create':

            if (isset($_FILES)) {
                saveUploadedFiles($tpath, $_FILES);
            }

            unset($_POST['req']);

            $response_array = createTransactionFile($tpath, $_POST);
            break;

        case 'getJsonToEdit':

            $response_array = getJsonToEdit($tpath, $request['fn']);
            break;

        case 'saveComments':

            $response_array = saveComments($tpath, $_POST['comments']);
            break;

        case 'getComments':

            $response_array = getComments($tpath);
            break;

        case 'getThumbnail':

//            $response_array = array('thumb' => getThumbnail($request['imagePath']));
            $response_array = getThumbnail($request['imagePath'], $tpath);
            break;
    }
//    header('Content-Type: application/json');
    echo json_encode($response_array);

}


