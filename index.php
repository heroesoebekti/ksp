<?php
class bunga{
	public function efektif($debet,$angsuran,$bunga){
	$i = 1;
	$html = '';
	$pokok2 = $debet;
	$bungat = 0;
	$pinjam = array();
	while ($i <= $angsuran) {
		$pokok = $debet/$angsuran;
		$pinjam['angsuran'][$i] = array(
			'sisa'=>round($pokok2),
			'pokok'=>round($pokok,2),			
			'bunga'=>round(($pokok2*($bunga/100)),2),
			'total'=>round(($pokok+($pokok2*($bunga/100))),2) );
	    $bungat = $bungat+round(($pokok2*($bunga/100)),2);
	    $pokok2 = $pokok2-$pokok;

	    $i++; 
	}
	$pinjam['rekap'] = array(
		'debet'=>$debet,

		'angsur'=>$angsuran,
		'total_bunga'=>$bungat,
		'total'=>$debet+$bungat);
	return $pinjam;
	}

	public function flat($debet,$angsuran,$bunga){
	$i = 1;
	$html = '';
	$pokok2 = $debet;
	$bungat = 0;
	$pinjam = array();
	while ($i <= $angsuran) {
		$pokok = $debet/$angsuran;

		$pinjam['angsuran'][$i] = array(
			'sisa'=>round($pokok2),
			'pokok'=>round($pokok,2),			
			'bunga'=>round(($debet*($bunga/100)),2),
			'total'=>round($pokok+($debet*($bunga/100)),2));
	    $bungat = $bungat+round(($pokok*($bunga/100)),2);
	    $pokok2 = $pokok2-$pokok;

	    $i++; 
	}
	$pinjam['rekap'] = array(
		'debet'=>$debet,
		'angsur'=>$angsuran,
		'total_bunga'=>$bungat,
		'total'=>$debet+$bungat);
	return $pinjam;
	}
}

function uang($nilai){
	return number_format($nilai, 2, ',', '.');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
<style type="text/css">
	table, tr, td {
		border: 1px solid;
		padding: 5px;
	}
	.content{
		text-align: right;
	}
</style>
<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>
<h1 id="aa">SIMULASI UTANG</h1>
<form method="POST">
	<input type="text" name="plafond" placeholder="PLAFOND" id="plafond" onkeyup="autocomplet()" ><br/>
	<input type="text" name="bunga" placeholder="BUNGA" id="bunga" onkeyup="autocomplet()"><br/>
	<input type="text" name="jangka" placeholder="JANGKA" id="jangka" onkeyup="autocomplet()"><br/>
	<select name="type" id="model" onchange="autocomplet()">
		<option value="0" selected>EFEKTIF</option>
		<option value="1">FLAT</option>
	</select><br/>
	<input type="submit" name="submit" value="HITUNG"><br/><hr/>
</form>

              <table collpadding="3" style="padding:8px !important;">
            <thead><tr><td> SISA </td><td> POKOK </td><td> BUNGA </td><td> TOTAL </td></tr></thead>
            	<tbody id="content" style="padding:8px !important;"></tbody>
            </table>  	

<?php
if(isset($_POST['submit'])){
	$bunga = $_POST['bunga'];
	$debet = $_POST['plafond'];
	$waktu = $_POST['jangka'];
	$type = $_POST['type'];
	$kalkulasi = new bunga();	
	if($type=='1'){
	$a = $kalkulasi->flat($debet,$waktu,$bunga);
}elseif($type=='0'){
	$a = $kalkulasi->efektif($debet,$waktu,$bunga);
}
	echo '<table>';
	echo '<td>Angsuran Ke </td><td>Sisa Pinjaman</td>'.'<td>Pokok Pinjaman</td>'.'<td>Bunga Pinjaman</td>'.'<td>Total Bayar</td>';
	foreach ($a['angsuran'] as $key => $value) {
	echo '<tr class="content"><td>'.$key.'</td>';
	echo '<td style="color:red;">'.uang($value['sisa']).'</td>'.'<td>'.uang($value['pokok']).'</td>'.'<td>'.uang($value['bunga']).'</td>'.'<td>'.uang($value['total']).'</td>';
	echo '</td></tr>';
	}
	echo '<td colspan="2">Total</td><td>'.uang($a['rekap']['debet']).'</td>'.'<td>'.uang($a['rekap']['total_bunga']).'</td>'.'<td>'.uang($a['rekap']['total']).'</td>';
	echo '</table>';
}
?> 
<script type="text/javascript">
function autocomplet() {
    var min_length = 1;     
    var plafond = $('#plafond').val();
    var bunga = $('#bunga').val();
    var jangka = $('#jangka').val();
    var model = $('#model').val();


    if (plafond.length >= min_length) {
		$.getJSON('//<?php echo $_SERVER['SERVER_NAME'].'/'.$_SERVER['REQUEST_URI'];?>/preview.php?plafond='+plafond+'&bunga='+bunga+'&jangka='+jangka+'&model='+model, function(data) { 
				$("#content > tr").remove();
	    		$.each(data, function(index, element) {	   		
	    		$("#content").append('<tr class="ref"><td>'+element.sisa+'</td><td>'+element.pokok+'</td><td>'+element.bunga+'</td><td>'+element.total+'</td></tr>');
                $('td').fadeIn(50000);
	    		});
		});
		$('#content').fadeIn(500);
    } else {
        $('#content').fadeOut(500);
    }
}
</script>
</body>
</html>

