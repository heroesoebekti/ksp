<?php

class bunga{

	public function efektif($debet,$angsuran,$bunga){
	$i = 1;
	$html = '';
		//$bunga = $bunga/12;
	$pokok2 = $debet;
	$bungat = 0;
	$pinjam = array();
	while ($i <= $angsuran) {
		$pokok = $debet/$angsuran;

		$pinjam[$i] = array(
			'sisa'=>self::uang(round($pokok2)),
			'pokok'=>self::uang(round($pokok,2)),			
			'bunga'=>self::uang(round(($pokok2*($bunga/100)),2)),
			'total'=>self::uang(round(($pokok+($pokok2*($bunga/100))),2) ));
	    $bungat = $bungat+round(($pokok2*($bunga/100)),2);
	    $pokok2 = $pokok2-$pokok;

	    $i++; 
	}
	return $pinjam;
	}


	public function flat($debet,$angsuran,$bunga){
	$i = 1;
	$html = '';
	//$bunga = $bunga/12;
	$pokok2 = $debet;
	$bungat = 0;
	$pinjam = array();
	while ($i <= $angsuran) {
		$pokok = $debet/$angsuran;

		$pinjam[$i] = array(
			'sisa'=>self::uang(round($pokok2)),
			'pokok'=>self::uang(round($pokok,2)),			
			'bunga'=>self::uang(round(($debet*($bunga/100)),2)),
			'total'=>self::uang(round($pokok+($debet*($bunga/100)),2)));
	    $bungat = $bungat+round(($pokok*($bunga/100)),2);
	    $pokok2 = $pokok2-$pokok;

	    $i++; 
	}
	return $pinjam;
	}

	function uang($nilai){
		return number_format($nilai, 2, ',', '.');
	}
}


if(isset($_GET) && !empty($_GET) ){
	$bunga = $_GET['bunga'];
	$debet = $_GET['plafond'];
	$waktu = $_GET['jangka'];
	$type = $_GET['model'];
	$kalkulasi = new bunga();	
	if($type=='1'){
		$a = $kalkulasi->flat($debet,$waktu,$bunga);
	}elseif($type=='0'){
		$a = $kalkulasi->efektif($debet,$waktu,$bunga);
	}
echo json_encode($a);
}else{
	header('index.php');
}