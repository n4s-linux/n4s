<?php
	require_once("/svn/svnroot/Applications/ps/ps_order.php");
	function ps_post_order($id) {
		ps_mktrans(ps_getorder($id));
	}
	function ps_mktrans($order_data) {
		$t = ($order_data);
		global $data;
		// global data !!!
		$t['update'] = 1;
		$t['Dato'] = $order_data['date'];
		$t['Beløb'] = $order_data['total_paid']*-1;
		$t['Konto'] = 1010;
		$t['F1'] = 'U25';
		$t['F2'] = '';
		$t['Tekst'] = "Ordre $order_data[id]";
		$t['Modkonto'] = getpayacc($order_data['payment']);
		$t['Bilagsnr'] = 8888;
		unset($t['id']);
		array_push($data,$t);
		$t['Modkonto'] = 1012;
		$t['Beløb'] = $order_data['shipping'];
		$t['Tekst'] .= " porto reg";
		array_push($data,$t);
		$t = $order_data;
		$t['update'] = 1;
		$t['Beløb'] = $order_data['costs'];
		$t['Konto'] = 1330;
		$t['Modkonto'] = 5520;
		$t['Dato'] = $order_data['date'];
		$t['Tekst'] = "Vareforbrug ordre $order_data[id]";
		$t['F1'] = '';
		$t['F2'] = '';
		$t['Bilagsnr'] = 8888;
		unset($t['id']);
		array_push($data,$t);
		validate();
		save(true);
	}
	function getpayacc($ref) {
		$refs = array('Bankoverførsel'=>5850,'dankort'=>5816,'other'=>5822);
		if (isset($refs[$ref]))
			return ($refs[$ref]);
		else
			return ($refs[$other]);
	}
?>
