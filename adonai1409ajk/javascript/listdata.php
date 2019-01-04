<?php
include_once ("../../includes/fu6106.php");
switch ($_REQUEST['list']) {
	case "listproduk":
$client=$_GET['client'];
$query='SELECT * FROM fu_ajk_polis WHERE id_cost="'.$client.'" AND del IS NULL ORDER BY nmproduk ASC';
$result=mysql_query($query);
echo ': <select name="produk" onchange="getAsuransi('.$client.',this.value)">
		<option>-- Pilih Produk --</option>';
while($row=mysql_fetch_array($result)) {
echo '	<option value="'.$row['id'].'">'.$row['nmproduk'].'</option>';
}
echo '</select>';
	;
	break;

	case "listasuransi":
$client=$_GET['client'];
$produk=$_GET['produk'];
$query='SELECT fu_ajk_polis_as.id,
			   fu_ajk_polis_as.id_cost,
			   fu_ajk_polis_as.id_as,
			   fu_ajk_polis_as.nmproduk,
			   fu_ajk_asuransi.name AS asuransi
			   FROM fu_ajk_polis_as
			   INNER JOIN fu_ajk_asuransi ON fu_ajk_polis_as.id_as = fu_ajk_asuransi.id
			   WHERE fu_ajk_polis_as.id_cost = "'.$client.'" AND fu_ajk_polis_as.nmproduk = "'.$produk.'" AND fu_ajk_polis_as.del IS NULL';
$result=mysql_query($query);
echo ': <select name="asuransi" onchange="getPolis('.$client.',this.value)">
		<option>-- Pilih Asuransi --</option>';
while($row=mysql_fetch_array($result)) {
	echo '	<option value="'.$produk.'-'.$row['id_as'].'">'.$row['asuransi'].'</option>';
}
echo '</select>';
	;
	break;

	case "listpolisasuransi":
$client=$_GET['client'];
$produk=$_GET['produk'];
$produkasuransi = explode("-", $produk);
$query='SELECT * FROM fu_ajk_polis_as WHERE id_cost="'.$client.'" AND nmproduk="'.$produkasuransi[0].'" AND id_as="'.$produkasuransi[1].'" AND del IS NULL ORDER BY nmproduk ASC';
$result=mysql_query($query);
echo ': <select name="polisasuransi"">
		<option>-- Pilih Polis Asuransi --</option>';
while($row=mysql_fetch_array($result)) {
	$cekRateAsuransi = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$row['id_cost'].'" AND id_polis="'.$row['nmproduk'].'" AND id_as="'.$row['id_as'].'" AND id_polis_as="'.$row['id'].'" AND status="baru" AND del IS NULL'));
	if ($cekRateAsuransi['id']) {
	echo '	<option value="'.$row['id'].'" disabled>'.$row['nopol'].'</option>';
	}else{
	echo '	<option value="'.$row['id'].'">'.$row['nopol'].'</option>';
	}
}
echo '</select>';
	;
	break;

	default:
		;
} // switch

?>