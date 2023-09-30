<?php
	$xml = simplexml_load_file($argv[1]);
	echo ($xml->accounts->account->{'account-total'}->amount->quantity);
?>
