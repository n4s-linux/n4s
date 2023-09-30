<?php
$nosync = array("log","",".","..","...","./curl","./chart_of_account");
function forceFilePutContents (string $fullPathWithFileName, string $fileContents,$mt)
    {
        $exploded = explode(DIRECTORY_SEPARATOR,$fullPathWithFileName);

        array_pop($exploded);

        $directoryPathOnly = implode(DIRECTORY_SEPARATOR,$exploded);

        if (!file_exists($directoryPathOnly)) 
        {
            mkdir($directoryPathOnly,0775,true);
        }
        file_put_contents($fullPathWithFileName, $fileContents);    
	touch($fullPathWithFileName,strtotime($mt));
    }

function getlatest($filename) {
	global $rows;
	global $tpath;
	$retval = false;
	foreach ($rows as $row) {
		if ($row['filename'] == $filename) {
			forceFilePutContents("$tpath/$filename",$row['contents'], $row['updated']);
		}
	} 
	assert($retval);
}
function getupdated($filename,$mt) {
	global $rows;
	global $nosync;
	if (in_array($filename,$nosync))
		return "nosync";
	else
		$retval = "put";
	foreach ($rows as $row) {
		if ($row['filename'] != $filename)
			continue;
		if (strtotime($row['updated']) > strtotime($mt)) {
			$retval = "get";
			break;
		}
		else if (strtotime($row['updated']) == strtotime($mt)) {
			$retval = "insync";
			break;
		}
		else if (strtotime($row['updated']) < strtotime($mt)) {
			$retval = "put";
			break;
		}
	}
	//echo "retval ($filename):$retval\n";
	
	return $retval;
}
$path = basename(getenv("tpath"));
require_once("key_sync.dbsettings.php");
$tpath=getenv("tpath");
$mysqli = new mysqli("$host","$user","$password","$db",$port);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}


$q = "select * from $path order by updated asc";
echo "$q\n";
$res = $mysqli->query($q);
if (mysqli_num_rows($res) && $rows = $res->fetch_all(MYSQLI_ASSOC)) {
	echo "loaded rows...\n";
}
else {
	$rows = array();
	echo "new table...\n";
}
	


$q = "create table if not exists $path (id int auto_increment primary key, filename varchar(255),contents mediumblob,updated datetime)";



// Perform query
if ($result = $mysqli -> query($q)) {
	echo "table ok...\n";
}

$cmd = "cd $tpath;find . -type f > .ls;";
//require_once("/svn/svnroot/Applications/proc_open.php");
//exec_app("cd \"$tpath\";vi $tpath/.ls");
system($cmd);
$ls = file_get_contents("$tpath/.ls");
system ("unlink $tpath/.ls 2>/dev/null");
$files = explode("\n",$ls) ;
chdir("$tpath");
foreach ($files as $curfile) {
	$curfile = str_replace("./.",".",$curfile);
	//echo "curfile: '$curfile'\n";
	if (file_exists("$curfile"))
		$mt = date('Y-m-d H:i:s',filemtime("$curfile"));
	/*else {
		echo("(dangerous skip maybe) $curfile findes ikke !\n");
	}*/

	// check here who has the newest file
	$db_update = getupdated(str_replace("./.",".",$curfile),$mt);
	//echo "$curfile ($db_update)\n";	
	//perform the required actions	
	if ($db_update == "put"){ 
		$cfesc = mysqli_real_escape_string($mysqli,$curfile);
		$cfesc = str_replace("./.",".",$cfesc);
		$contents = mysqli_real_escape_string($mysqli,file_get_contents("$curfile"));
		$q = "insert into $path (filename,updated,contents) values ('$cfesc','$mt','$contents');";
		echo "insert into $path (filename,updated,contents) values ('$cfesc','$mt','contents');\n";
		if (!$result = $mysqli->query($q))
			die($mysqli->error);
	}
	else if ($db_update == "get") {
		echo "getting $curfile from db which has a later version - yay\n";
		getlatest($curfile);
	}
}
?>
