<?php
	$op = system("whoami");
	//system("ledger -f /data/regnskaber/transactions_igangv/curl accounts Igangværende > ~/tmp/igvacc"); 
	//system("cd /data/regnskaber/transactions_crm/.tags/;ls *|grep -v .diff|fzf  > ~/tmp/igvacc"); 
	system("cd /data/regnskaber/transactions_crm/.tags/;grep \"#cvr\" *|grep -v .diff|cut -d: -f1|uniq|fzf > ~/tmp/igvacc"); $kunde = trim(file_get_contents("/home/$op/tmp/igvacc"));
	$kundedata = file_get_contents("/data/regnskaber/transactions_crm/.tags/$kunde");
	$uid = uniqid();
	$output = "/home/$op/tmp/materiale_$uid.html";
	require_once("fzf.php");
	$materiale = fzf("Indtægtsbilag som PDF\nFysiske indtægtsbilag\nFysiske udgiftsbilag\nIndtægtsbilag @ Dropbox\nUdgiftsbilag som PDF\nUdgiftsbilag @ Dropbox\nUdgiftsbilag @ E-conomic\nBank CSV fil","Vælg materiale","--multi");
	if ($materiale == "") die("ikke valgt materiale\n");
	$periode = getperiod();
	if ($periode== "") die("ikke valgt periode\n");
	$deadline = explode(" ",fzf("7 dage\n10 dage\n5 dage\n14dage\n","Vælg deadline","--multi"))[0];
	if ($deadline== "") die("ikke valgt deadline\n");
	$date = date("Y-m-d");
	$deadline =date("d-m-Y",strtotime("$date + $deadline days"));

	file_put_contents($output,mheader());
	$o = "Vedrørende <i>$kunde</i><br>";
	$o .= "Vi indkalder materiale for $periode<br>";
	$o .= behovsliste($materiale);
	$o .= "<br><b>Indleveringsfrist</b><br>Vi ønsker materialet indleveret inden d. $deadline - hvis dette ikke er muligt - kontakt os venligst hurtigst muligt herom...<br><br>";
	file_put_contents($output,$o,FILE_APPEND);
	file_put_contents($output,footer(),FILE_APPEND);
	require_once("proc_open.php");
	exec_app("links $output");
	$yesno = trim(fzf("Ja\nNej","Vil du sende mailen ?"));
	if  ($yesno == "Ja") 
		email("Olsens Revision ApS - Materialeindkaldelse - $kunde - $periode",askmail(),file_get_contents($output));
	else
		die("Ikke sendt\n");
	function behovsliste($materiale) {
		$o = "<br><b>Vi har behov for følgende materiale:</b><br><ul>";
		$materiale = explode("\n",$materiale);
		foreach ($materiale as $mat) $o .= "<li>$mat</li>";
		$o .= "</ul>";
		return $o;
	}
	function mheader() {
		return '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"><meta charset=utf8>';
	}
	function footer() {
		return "Med venlig hilsen<br><img src=https://olsensrevision.dk/wp-content/uploads/elementor/thumbs/olsens-onf38ivvwko6z6ujk7ubpjc3t0n8teweq5uwfuvuhs.png>";
	}
	function getperiod() {
		require_once("nicem.php");
		$fzf = "";
	        for ($år = date("Y") -5; $år <= date("Y");$år++) { // HVERT ÅR i $år 
                for ($i = 1;$i<13;$i++) {
                        $fzf .= "$år" . "-" . nicem($i) . "\n";;                    
                }   
                for ($i=1;$i<5;$i++) {
                        $fzf .= "$år" . "Q$i\n";            
                }   
                for ($i=1;$i<3;$i++) {
                        $fzf .= "$år" . "H$i\n";            
                }   
                $fzf .= "$år\n";
		$fzf .= "resten af $år\n";
        }  
		return fzf($fzf,"Vælg periode","--tac");
	}

	function email($emne = "Materialeindkaldelse",$modtager="olsenit@gmail.com",$body) {
	$to = $modtager;
	$subject = $emne;
	$from = "mail@olsensrevision.dk";
	 
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	 
	// Create email headers
	$headers .= 'From: '.$from."\r\n".
	    'Reply-To: '.$from."\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	 
	// Compose a simple HTML email message
	$message = '<html><body>';
	$message .= $body;
	$message .= '</body></html>';
	 
	// Sending email
	if(mail($to, $subject, $message, $headers)){
	    echo 'Your mail has been sent successfully.';
	} else{
	    echo 'Unable to send email. Please try again.';
	}
}
function askmail() {
	echo "Indtast email: ";
	$fd = fopen("PHP://stdin","r");
	$retval = trim(fgets($fd));
	fclose($fd);
	return $retval;
}
?>
