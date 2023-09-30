<script>
function copyTextToClipboard(text) {
  var textArea = document.createElement("textarea");

  //
  // *** This styling is an extra step which is likely not required. ***
  //
  // Why is it here? To ensure:
  // 1. the element is able to have focus and selection.
  // 2. if element was to flash render it has minimal visual impact.
  // 3. less flakyness with selection and copying which **might** occur if
  //    the textarea element is not visible.
  //
  // The likelihood is the element won't even render, not even a flash,
  // so some of these are just precautions. However in IE the element
  // is visible whilst the popup box asking the user for permission for
  // the web page to copy to the clipboard.
  //

  // Place in top-left corner of screen regardless of scroll position.
  textArea.style.position = 'fixed';
  textArea.style.top = 0;
  textArea.style.left = 0;

  // Ensure it has a small width and height. Setting to 1px / 1em
  // doesn't work as this gives a negative w/h on some browsers.
  textArea.style.width = '2em';
  textArea.style.height = '2em';

  // We don't need padding, reducing the size if it does flash render.
  textArea.style.padding = 0;

  // Clean up any borders.
  textArea.style.border = 'none';
  textArea.style.outline = 'none';
  textArea.style.boxShadow = 'none';

  // Avoid flash of white box if rendered for any reason.
  textArea.style.background = 'transparent';


  textArea.value = text;

  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Copying text command was ' + msg);
  } catch (err) {
    console.log('Oops, unable to copy');
  }

  document.body.removeChild(textArea);
}


var copyBobBtn = document.querySelector('.js-copy-bob-btn'),
  copyJaneBtn = document.querySelector('.js-copy-jane-btn');

copyBobBtn.addEventListener('click', function(event) {
  copyTextToClipboard('Bob');
});


copyJaneBtn.addEventListener('click', function(event) {
  copyTextToClipboard('Jane');
});
</script><?php
$lines = system("ledger -f /tmp/curledger.ledger --depth 3 bal");
show_account_tree($lines,"test",$lines);
function show_account_tree($lines,$cmd,$ytd) {
if (stristr($cmd,"bal") === FALSE) {
	echo $lines;
	return;
}
$bal = array();
echo "<pre>";
$lines = explode("\n",$lines);
foreach ($lines as $line) {
	$line = trim($line);
	$number = explode(" ",$line)[0];
	$pos = strpos($line," ");
	$acc = trim(substr($line,$pos +1));
	$bal[$acc] = $number;

}
$linez = explode("\n",$ytd);
$ytd= array();
foreach ($linez as $line) {
        $line = trim($line);
        $number = explode(" ",$line)[0];
        $pos = strpos($line," ");
        $acc = trim(substr($line,$pos +1));
        $ytd[$acc] = $number;
}
echo "</pre><table border=1>";
$last = "";
$y = 0;
foreach ($bal as $acc => $saldo) {
	$y++;
	echo "<tr>";
	echo "<td>$ytd[$acc]</td><td>$saldo</td>";
	unset($ytd[$acc]);
	$level = count(explode(":",$acc));
	$cur = explode(":",$acc);
//echo "svar: $new<br>";
	for ($i = 0;$i<$level;$i++) {
		$a = $acc; // full account name
		$b = explode(":", $a);
			$t = $cur[$i];
		$ct = trim(htmlentities($acc));
			
		echo "<td><a id=$y href=\"#\" onclick='copyTextToClipboard(\"$ct\");'>$t</a></td>";
	}
	$accname = explode(":",$acc);
	$accname = end($accname);
	echo "</tr>";
}
foreach ($ytd as $acc => $saldo) {
        $y++;
	if ($saldo == 0)
		continue;
        echo "<tr>";
        echo "<td>$ytd[$acc]</td><td>$saldo</td>";
        unset($ytd[$acc]);
        $level = count(explode(":",$acc));
        $cur = explode(":",$acc);
//echo "svar: $new<br>";
        for ($i = 0;$i<$level;$i++) {
                $a = $acc; // full account name
                $b = explode(":", $a);
                        $t = $cur[$i];
                $ct = trim(htmlentities($acc));
    
                echo "<td><a id=$y href=\"#\" onclick='copyTextToClipboard(\"$ct\");'>$t</a></td>";
        }   
        $accname = explode(":",$acc);
        $accname = end($accname);
        echo "</tr>";
}
echo "</table>";
}
?>
