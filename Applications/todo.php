<?php
		require_once("/svn/svnroot/Applications/class.olsensCalendar.php");
		$cal = new OlsensCalendar;
		$ledigtid = array();
		$ledigtid = $cal->bookAccountingAct(system("whoami"),$str,$start);
			require_once("showtime.php");
			echo showtime($ledigtid);
			//function showtime($time,$Tekst = "Fundet", $delimiter = "\n")
			if (!isset($ledigtid[0]['start']))
				$ledigtid = $ledigtid;
			$count = count($ledigtid) -1;
			echo "Ledig tid fundet (se ovenfor) - book i kalender? (j/n): ";
			$str = explode("\n",fgets($filefd))[0];
			fclose($filefd);
		foreach ($ledigtid as $lt) {
			$sluttid = $lt['start']->format("Y-m-d H:i");
			echo "NOW...\n";
			echo "$kundenr,'$tekst','open','$sluttid','$operator',$minutes);)";
		}
