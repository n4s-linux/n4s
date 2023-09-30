<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">



<?php
	function transtabel($t) {
		echo "<table class=table border=1>";
		foreach ($t as $curt) {
			if (stristr($curt['Account'],"fejl"))continue;
			echo "<tr>";
			echo "<td>$curt[Account]</td><td>$curt[Amount]</td><td>$curt[Func]</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	function fejlrapport($fejler) {
		if (isset($fejler['fejlkonto'])) {
			$bannedfields = array("UID","Filename");
			echo " # Poster på fejlkonto ";
			foreach ($fejler['fejlkonto'] as $curfil) {
				$fn = $curfil['Filename'];
				echo "<table class='table-sm' border=2 width=450 height=150><tr><td>";
				foreach ($curfil as $field => $val) {
					if (!is_array($val)) {
						if (in_array($field,$bannedfields)) continue;
					}
				
				}echo "</td><td>";
				transtabel($curfil['Transactions']);
				echo "</table></table>";
			}
		}
	}
?>
