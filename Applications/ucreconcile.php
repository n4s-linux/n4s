<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['username'])) {
	if (isset($_POST["username"])) {
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["password"] = $_POST["password"];
	}
	else {
		echo "<form action=ucreconcile.php method=POST>User<br><input type=text name=username><br>Pass<br><input type=password name=password><br><input type=submit value=Login></form>";
		die();
	}
}
require_once("/svn/svnroot/Applications/uc_odata.php");
$username = $_SESSION["username"];
$password = $_SESSION["password"];
?>
<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/ouol0ye5m8ykepfo20seshwer5npng06hjzxt9by41oyimyl/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    menubar: true,
plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
window.onload = () => document.getElementById('scrollhere')?.scrollIntoView();
</script>
<?php
	$datafolder = "/var/www";
foreach ($_POST as $curpost => $curval) {                                                                                                     
	$path = $datafolder . "/" . $curpost;
	if (file_exists($path)) {
		$uid = filemtime($path);
		system ("cp $path $path.$uid");
	}
	if (file_put_contents($path, $curval) === false) 
		echo "Error: Could not write to file '$path'. Please check permissions and path.\n";
}
	$companies = getdata("CompanyClient");
	$ids = array();
	$inbox = array();
	foreach ($companies as $cc) {
		array_push($ids,$cc["PrimaryKeyId"]);
		$namez[$cc["PrimaryKeyId"]] = $cc["Name"];
		if (isset($cc["NInbox"])&&$cc["NInbox"] >10) $inbox[$cc["PrimaryKeyId"] . " - " . $cc["Name"]] = $cc["NInbox"];
	}
	echo "<h3>Bilag til kontering</h3>\n";
	echo "<table class=table>\n";
	foreach ($inbox as $curinbox => $curcount) {
		$x = explode(" - ",$curinbox);
		echo "<tr><td>$x[0]</td><td>$x[1]</td><td>$curcount</td></tr>\n";
	}
	echo "</table>\n";
	$r = array();
	foreach ($ids as $curid) {
		$firmaid = $curid;
		$d = getdata("GLAccountClient");
		foreach ($d as $curacc) {
			if ($curacc["AccountType"] == "Header") continue;
			if ($curacc["AccountType"] == "Sum") continue;
			if ($curacc["AccountType"] == "Total") continue;
			if ($curacc["AccountType"] == "Calculation expression") continue;
			if ($curacc["CurBalance"] == 0) continue;
			array_push($r,array("CurBalance"=>$curacc["CurBalance"],"Account"=>$curacc["Account"],"Type"=>$curacc["AccountType"],"Client"=>$curid,"Name"=>$curacc["Name"],"Reconciled"=>$curacc["Reconciled"]));
		}
	}
	usort($r,"sortbyreconcile");
$i = 0;
echo "<h3>Afstemningsliste</h3><table border=5 class='table table-striped'>";
	$edithash = $_GET["edit"];
	foreach ($r as $curr) {
		$hash = md5($curr["Client"] . $curr["Account"]);
		$hashdata = (file_exists($datafolder."/".$hash)) ? file_get_contents($datafolder."/".$hash) : "";
		$hash2 = md5($curr["Account"]);
		$hashdata2 = (file_exists($datafolder."/".$hash2)) ? file_get_contents($datafolder."/".$hash2) : "";
		if ($curr["Account"] <= 4999) continue;
		$t = relativeTime(strtotime($curr["Reconciled"]));	
		if (strtotime($curr["Reconciled"]) < strtotime("-1 year")) $t = "Never";
		$name = $namez[$curr["Client"]];
		if ($i == 0) echo '<tr><th>Customer</th><th>FinanceAcc</th><th>Amount</th><th>AccName</th><th>Type</th><th>LastReconciled</th><th>Noter</th></tr>'; 
		echo "<tr><td>$curr[Client] - $name</td><td>$curr[Account]</td><td>$curr[CurBalance]</td><td>$curr[Name]</td><td>$curr[Type]</td><td>$t</td>";
		if ($edithash == $hash) {
			echo "<td id=scrollhere><b><u><center>Kundespecifikke noter<br><form method=POST action=ucreconcile.php><textarea name=$hash>$hashdata</textarea><br><input type=submit><br>";
			echo "<br>";
			echo "<b><u>AccountNotez<br><textarea rows=5 name=$hash2>$hashdata2</textarea><br><input type=submit></form></b></u></center></td>";
		}
		else {
			echo "<td>";
			if (strlen($hashdata)) {
				box($hashdata,"#ebdb34","CustomerAccountNotez","ucreconcile.php?edit=$hash");
				echo "<br>";
			}
			if (strlen($hashdata2)) {
				box($hashdata2,"white","AccountNotez","ucreconcile.php?edit=$hash");
			}
			if (!strlen($hashdata) && !strlen($hashdata2))
				box("<center>Create notes</center>","#32a852","","ucreconcile.php?edit=$hash");
			echo "</td>";
		}
		$i++;
	}
	echo "</table>";
?>

<script>
  tinymce.init({
    selector: 'textarea', // Selects all <textarea> elements
    plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    toolbar_mode: 'floating',
    menubar: false, // Hide the menubar if you prefer
    height: 100, // Set height if you want to control the editor's size
	width: 200
  });
</script>
<?php
die();
	echo "<table class='table table-striped'>";
	foreach ($r as $curr) {
		if ($curr["Account"] >= 4999) continue;
		$t = relativeTime(strtotime($curr["Reconciled"]));	
		if (strtotime($curr["Reconciled"]) < strtotime("-1 year")) $t = "Never";
		$name = $namez[$curr["Client"]];
		echo "<tr><td>$curr[Client] - $name</td><td>$curr[Account]</td><td>$curr[CurBalance]</td><td>$curr[Name]</td><td>$curr[Type]</td><td>$t</td></tr>";
	}
	echo "</table>";

function sortbyreconcile($a,$b) {
	$s = strtotime($a["Reconciled"]);
	$s2 = strtotime($b["Reconciled"]);
	return $s > $s2;
}
function relativeTime($time) {

    $d[0] = array(1,"second");
    $d[1] = array(60,"minute");
    $d[2] = array(3600,"hour");
    $d[3] = array(86400,"day");
    $d[4] = array(604800,"week");
    $d[5] = array(2592000,"month");
    $d[6] = array(31104000,"year");

    $w = array();

    $return = "";
    $now = time();
    $diff = ($now-$time);
    $secondsLeft = $diff;

    for($i=6;$i>-1;$i--)
    {
         $w[$i] = intval($secondsLeft/$d[$i][0]);
         $secondsLeft -= ($w[$i]*$d[$i][0]);
         if($w[$i]!=0)
         {
            $return.= abs($w[$i]) . " " . $d[$i][1] . (($w[$i]>1)?'s':'') ." ";
         }

    }

    $return .= ($diff>0)?"#siden":"#indtil";
    return $return;
}







function updateGLAccountName($accountId, $newName) {
    try {
        // Construct the API endpoint for updating the GLAccountClient
        $url = "https://odata.uniconta.com/api/Entities/Update/GLAccountClient";

        // Fetch the existing account details (you can optimize this with a Get function if needed)
        $existingAccount = getGLAccount($accountId);

        if ($existingAccount) {
            // Update the 'Name' field
            $existingAccount['Name'] = $newName;

            // Send the updated account information via PUT request
            $response = sendRequest('PUT', $url, $existingAccount);

            if ($response['status'] === 200) {
                echo "Account name updated successfully!";
                echo $response['body']; // Response from API (updated object)
            } else {
                echo "Failed to update account: " . $response['body'];
            }
        } else {
            echo "Account not found!";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getGLAccount($accountId) {
    // This is a placeholder for fetching the existing account details based on accountId
    // You would call the Uniconta API's GET endpoint for GLAccountClient here
    global $firmaid;
    $url = "https://odata.uniconta.com/api/$firmaid/Entities/GLAccountClient?Account=$accountId";
    $response = sendRequest('GET', $url);
	print_r($response);die();
    if ($response['status'] === 200) {
        return json_decode($response['body'], true); // Return the account object
    }

    return null; // Return null if not found or error occurred
}

function sendRequest($method, $url, $data = null) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . setAuthorizationHeaderValue(),
        'Accept: application/json',
        'Content-Type: application/json',
    ]);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, getJsonSettings()));
    }

    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        'status' => $statusCode,
        'body' => $response
    ];
}

function setAuthorizationHeaderValue() {
	global $password;
	global $username;
    $rv = 'Basic ' . base64_encode("$username:$password");
	return $rv;
}

function getJsonSettings() {
    return JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
}

// Example usage
function box($str,$bg = "#fffbe6",$title,$link) {
?>
<a href=<?=$link?>>
<div class="p-3 rounded shadow" style="background-color: <?=$bg?>; border-right: 5px solid #4a751b; border-left: 5px solid #4a751b;">
    <h5 style="color: #333;"><center><?=$title?></center></h5>
    <p style="margin: 0;">
        <?php echo $str;?>
    </p>
</div>
</a>
<?php

}
?>
