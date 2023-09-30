<?php
    echo "<a href=pdfedit.php>Go back</a><br>";
	session_start();
        $filename = $_GET['f'];
	//echo "unset : " . getcwd() . "/" . $_GET['f'] . "<br>";
	system("/svn/svnroot/Applications/editpdfmeta.bash \"$filename\" cat > /tmp/loskat");
	$data = file_get_contents("/tmp/loskat");
	unlink("/tmp/loskat");
	//echo "Data i $filename<br>";
	$lines = explode("\n",$data);
	$initialcomment = 1;
	$linez = "";
	foreach ($lines as $line) {
		if (substr($line,0,1) == ";" && $initialcomment) {
			echo $line . "<br>";
		}
		else {
			$linez .= $line . "\n";
			$initialcommit = 0;
		}
	}
	$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if (isset($_POST['data'])) {
		$linez = $_POST['data'];
}
        echo "<form action=\"$link\" method=POST><textarea name=data rows=25 cols=90>$linez</textarea>";
?>
<br>
<input type=submit value=Opdater>
<?php if (isset($_POST['data'])) {
	file_put_contents("/var/www/webinput",$_POST['data']);
	system("/svn/svnroot/Applications/editpdfmeta.bash \"$_GET[f]\"");
	echo "<font color=green>Opdateret</font>";
	//unset($_SESSION[$_GET['f']]);
	apc_delete($_GET['f']);
	apc_store("require_update",1);
}
?>
</form>
<img src="getfile.php?fn=<?php echo $filename;?>&embedded=true">
<script>
var textareas = document.getElementsByTagName('textarea');
var count = textareas.length;
for(var i=0;i<count;i++){
    textareas[i].onkeydown = function(e){
        if(e.keyCode==9 || e.which==9){
            e.preventDefault();
            var s = this.selectionStart;
            this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
            this.selectionEnd = s+1; 
        }
    }
}</script>
