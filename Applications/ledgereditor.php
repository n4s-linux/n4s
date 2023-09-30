<?php
$file = $_GET['f'];
    echo "<a href=pdfedit.php>Go back</a><br>";
	session_start();
        $filename = $_GET['f'];
	$data = file_get_contents($_GET['f']);
	echo "Data i $filename<br>";
	$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (isset($_POST['data'])) 
		$linez = $_POST['data'];
	else
		$linez = $data;
        echo "<form method=POST><textarea name=data rows=25 cols=90>$linez</textarea>";
?>
<br>
<input type=submit value=Opdater>
<?php if (isset($_POST['data'])) {
	file_put_contents($_GET['f'],$_POST['data']);
	$cwd = getcwd();
	//if (isset($_SESSION[$cwd . "/" . $file]))
	//$_SESSION[$file] = $_POST['data'];
	if (apc_exists($cwd . "/" . $file))
		apc_store($cwd . "/" . $file,$_POST['data']);
	echo "<font color=green>Opdateret</font>";
}
?>
</form>
