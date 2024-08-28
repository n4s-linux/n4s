<?php
function expand($darray){
$simple = "";
$simple = getenv("simple");
$newdarray = array();
$darray_add = array();
foreach ($darray as $dataarray) {
		$newtrans = array();
	if (!isset($dataarray['Transactions'])) continue;
	$id = 0;
	$dataarray = datemorphology($dataarray);
	foreach ($dataarray['Transactions'] as $trans) {
		$sourcetrans = $trans;
		$trans['id'] = $id++;
		if (!isset($trans["Amount"]))
			die($dataarray['Filename'] . " has missing amount inside it\n");
		$trans["OrigAmount"] = $trans["Amount"];
		if (!isset($trans["Func"]))
			$trans["Func"] = "";
		if ($trans["Func"] != "" && ( stristr($trans['Account'] ,"Egenkapital")||stristr($trans['Account'],'Likvider') || stristr($trans['Account'],'Kreditorer') || stristr($trans['Account'],'debitorer'))) {
			echo("fejl - der er skrevet funktion ind på en konto der indeholder Likvider/Kreditorer/Debitorer - afbryder !\n");
			echo $dataarray['Filename'] . "\n";
			$cmd = ("vim '$dataarray[Filename]' -c \"let g:winid = popup_create('Venligst fjern momskode fra likvid/kreditor/debitorkonto', #{mindwidth:60, minheight: 1,line: 1,col:8})\"");
			die();
		}


		if ($trans['Func'] == "iy-abr") {
			$ot = $trans;
			$trans["SourceFunc"] = "iy-abr";
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af ydelseskøb Udland";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:Ydelseskøb Udland";
			array_push($newtrans,$trans);
			$trans = $ot;

		}
		if ($trans['Func'] == "iv-abr") {
			$ot = $trans;
			$trans["SourceFunc"] = "iv-abr";
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af varekøb Udland";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:Varekøb Udland";
			array_push($newtrans,$trans);
			$trans = $ot;

		}



		if ($trans['Func'] == "iy-eu") {
			$ot = $trans;
			$trans["SourceFunc"] = "iy-eu";
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af ydelseskøb EU";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:Ydelseskøb EU";
			array_push($newtrans,$trans);
			$trans = $ot;

		}
		if ($trans['Func'] == "iv-eu") {
			$ot = $trans;
			$trans["SourceFunc"] = "iv-eu";
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af varekøb EU";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:Varekøb EU";
			array_push($newtrans,$trans);
			$trans = $ot;

		}
		if ($trans['Func'] == "iv" || $trans['Func'] == "iv25") {
			$ot = $trans;
			$trans["SourceFunc"] = "iv";
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af varekøb udland";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:IV";
			array_push($newtrans,$trans);
			$trans = $ot;

		}
		if ($trans['Func'] == "iy" || $trans['Func'] == "iy25") {
			$ot = $trans;
			$trans["SourceFunc"] = "iy";
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af ydelser udland";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:IY";
			array_push($newtrans,$trans);
			$trans = $ot;

		}

		if ($trans['Func'] == "u" || $trans['Func'] == "U" || $trans["Func"] == "u25") {
			$trans['Amount'] = $trans['Amount'] * 0.8;
			array_push($newtrans,$trans);
			$sourcetrans = $trans; // very important if we need source trans for periodization
			$trans['Amount'] = $trans['Amount'] /4;
			$trans['Account'] = "Passiver:Moms:Salgsmoms";
			$trans['id'] = "virt";
			$trans["SourceFunc"] = "u";
			array_push($newtrans,$trans);
		}
			else	if ($trans['Func'] == "i" || $trans['Func'] == "I") {
			$trans['Amount'] = $trans['Amount'] * 0.8;
			array_push($newtrans,$trans);
			$sourcetrans = $trans; // very important if we need source trans for periodization

			$trans['Amount'] = $trans['Amount'] /4;
			$trans['Account'] = "Passiver:Moms:Købsmoms";
			$trans['id'] = "virt";
			$trans["SourceFunc"] = "i";
			array_push($newtrans,$trans);
		}

			else	if ($trans['Func'] == "rep" || $trans['Func'] == "Rep") {
			$vatamount = $trans["Amount"] * 0.05;
			$trans['Amount'] = $trans['Amount'] * 0.95;
			array_push($newtrans,$trans);
			$sourcetrans = $trans; // very important if we need source trans for periodization

			$trans['Amount'] = $vatamount;
			$trans['Account'] = "Passiver:Moms:Købsmoms";
			$trans['id'] = "virt";
			$trans["SourceFunc"] = "rep";
			array_push($newtrans,$trans);
		}


		else {
			//always push original trans;
			array_push($newtrans,$trans);

		}
		if (isset($trans['Levetid'])) {
			$mk = explode(":",$trans['Account']);
			$mk = $mk[count($mk)-1];
			$start = $orgtrans['Date'];
			$levetid = $trans['Levetid'];
			$belob = number_format($trans['Amount'] *-1 / $levetid,2,".","");
			$konto = $trans['Account'];
			$remainder = 0;
			$rest = $trans['Amount'];
			for ($i = 0;$i < $levetid;$i++) {
				$rest += $belob;
				$ptrans = $dataarray;
				unset($ptrans['History']);
				$ptrans['Description'] .= " ( $i / $levetid måneder rest $rest)";
				$ptrans['Date'] = date("Y-m-d",strtotime("$ptrans[Date] + $i months"));
				$ptrans['Transactions'] = array(
					array('Amount'=>$belob,'Account'=>$konto,'id'=>"virt"),
					array('Amount'=>-$belob,'Account'=>"Udgifter:Afskrivninger:$mk",'id'=>"virt")
					);
				array_push($newdarray,$ptrans);
				$remainder += $belob;
			}
			$remainder += $trans['Amount'];
			$remainder = -number_format($remainder,2,".","");
			if ($remainder != 0) {
				$ptrans = $dataarray;
				unset($ptrans['History']);
				$ptrans['Date'] = date("Y-m-d",strtotime("$ptrans[Date] + $i months"));
				$ptrans['Transactions'] = array(
					array('Amount'=>$remainder,'Account'=>$konto,'id'=>"virt"),
					array('Amount'=>-$remainder,'Account'=>"Udgifter:Afskrivninger:$mk",'id'=>"virt")
					);
				array_push($newdarray,$ptrans);
			}
	
		}
		if ($simple != "1" && isset($trans['P-Start']) && isset($trans['P-End']) && $trans["P-Start"] != $trans["P-End"]) {
			$start = new DateTime($trans['P-Start']);
			$end = new DateTime($trans['P-End']);
			$inc = DateInterval::createFromDateString('first day of next month');
			$end->modify('+1 day');
			$p = new DatePeriod($start,$inc,$end);
						$count = 0;
			$max = null;
			foreach ($p as $d) { $count++; $max = $d ;}
			$ptrans = $dataarray;
			$orgtrans = $dataarray;
			$ptrans['Transactions'] = array();
			$ptrans['id'] = "virt";
			if (strtotime($orgtrans['Date']) < strtotime($trans['P-End'])) {
				if (isset($sourcetrans['P-Account']) && strlen($sourcetrans['P-Account']))
					$contra = $sourcetrans['P-Account'];
				else if ($sourcetrans['Amount'] > 0)
					$contra = "Aktiver:Periodeafgrænsningsposter:$orgtrans[Description]";
				else
					$contra = "Passiver:Periodeafgrænsningsposter:$orgtrans[Description]";
			}
			else {
				if (isset($sourcetrans['P-Account']) && strlen($sourcetrans['P-Account']))
					$contra = $sourcetrans['P-Account'];
				else if ($sourcetrans['Amount'] < 0)
					$contra = "Passiver:Periodeafgrænsningsposter:$orgtrans[Description]";
				else
					$contra = "Aktiver:Periodeafgrænsningsposter:$orgtrans[Description]";
			}

			$reversetrans = $sourcetrans;
			$reversetrans["OrigAmount"] = $reversetrans["Amount"];
			$reversetrans['Amount'] = $reversetrans['Amount'] * -1;
			//print_r($p);die();
			array_push($ptrans['Transactions'],$reversetrans);
			$reversetrans['Account'] = $contra;
			$reversetrans['Amount'] = $reversetrans['Amount'] * -1;
			array_push($ptrans['Transactions'],$reversetrans);
			$ptrans['Description'] .= " (P)";
			array_push($darray_add,$ptrans);
			if ($count == 0)
				$amountperiod = round($reversetrans["Amount"],2);
			else
				$amountperperiod = round($reversetrans['Amount'] ,2)/ $count;
			$remainder = $reversetrans['Amount'];
			$leftover = $amountperperiod*$count - floor($amountperperiod * $count);
			$source = $orgtrans;
			$source['Transactions'] = array();
			$i= 0;
			$pcount = -1;
			foreach ($p as $d) $pcount++;
			$balz = 0;
			foreach ($p as $d) {
				$remainder -= round($amountperperiod,2);
				$ptrans = $sourcetrans;
				$source = $orgtrans;
				$source['Transactions'] = array();
				$source['Date'] = $d->format("Y-m-d");
				$ptrans['Amount'] = round($amountperperiod,2);
				$ptrans['id'] = "virt";
				if ($i == $pcount)
					$ptrans['Amount'] += $remainder;
				array_push($source['Transactions'] ,$ptrans);
				$ptrans['Amount'] = $ptrans['Amount'] * -1;
				$ptrans['Account'] = $contra;
				array_push($source['Transactions'],$ptrans);
				$source['Description'] .= " (P)";
				array_push($darray_add,$source);
				$i++;
			}
		}



	}
	$dataarray['Transactions'] = $newtrans;
	array_push($newdarray,$dataarray);

}
	$newdarray = array_merge($newdarray, $darray_add);
//	$newdarray = array_merge($newdarray, $darray_add_ek);
	return $newdarray;

}
?>
