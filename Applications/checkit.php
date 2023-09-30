<?php
	ob_start();
	$op = system("whoami ");
	ob_end_clean();
	$date=date("Y-m-d H:m");
	;echo "✓ $op @ $date";
?>;
