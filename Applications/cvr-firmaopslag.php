<?php
/**
 * Simpel kodeeksempel på hvordan du kan hente firmainformation ved hjælp af API fra cvrapi.dk
 * (Virker både med CVR-nummer, firmanavnet og p-nummer)
 * Lavet af Thomas B. Sørensen // www.ThomasSoerensen.dk
 */
        // Start curl
function cvr_lookup($CVR) {
        $ch = curl_init();
        // Sætte curl indstillinger
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://cvrapi.dk/' . $CVR);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Olsens Revision ApS');
        // Parse resultat
        $result = curl_exec($ch);
        // Lukke forbindelsen
        curl_close($ch);
        // Decode resultat
        $cvr_info = json_decode($result);
	return $cvr_info;
}
function getbusinessdesc($custno) {
	$data = (array)cvr_lookup($cvr);
	if (!isset($data['virkformtekst']))
		return "Privatkunde";
	else
		return $data['virkformtekst'] . " indenfor " . explode(":",$data['branchetekst'])[0] . " i " . $data['by'];
}
?>
