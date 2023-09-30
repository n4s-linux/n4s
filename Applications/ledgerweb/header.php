<a href=balance.php>Balance</a>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
        <?php foreach (array("begin","end","beginytd","endytd") as $pp) {?>
    $( "#<?php echo $pp?>" ).datepicker({ dateFormat: 'yy-mm-dd' });
        <?php }?>
  } );
  </script>
</head>

<?php
foreach (array("begin","end","beginytd","endytd") as $pp) {
        if (isset($_GET[$pp])) {
                setcookie($pp,$_GET[$pp]);
                $_COOKIE[$pp] = $_GET[$pp];
        }
}
if (!isset($_COOKIE['begin'])) setcookie("begin",'1970-01-01');
        if (!isset($_COOKIE['end'])) setcookie("end",'2099-01-01');
if (!isset($_COOKIE['beginytd'])) setcookie("beginytd",'1970-01-01');
        if (!isset($_COOKIE['endytd'])) setcookie("endytd",'2099-01-01');


?>
<form action="" method=GET>
<p>Begin: <input autocomplete=off type="text" id="begin" name=begin value=<?php echo $_COOKIE['begin'];?>>
End: <input autocomplete=off type="text" id="end" name=end value=<?php echo $_COOKIE['end'];?>></p>
<p>BeginYTD: <input autocomplete=off type="text" id="beginytd" name=beginytd value=<?php echo $_COOKIE['beginytd'];?>>
EndYTD: <input autocomplete=off type="text" id="endytd" name=endytd value=<?php echo $_COOKIE['endytd'];?>></p>
<?php
	foreach ($_GET as $get => $val) {
		if (!in_array($get,array('begin','end','beginytd','endytd')))
			echo "<input type=hidden name=$get value=\"$val\">";
	}
?>
<input type=submit>
</form>
