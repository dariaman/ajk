<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
require('fpdf.php');
include_once "../includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');

include "../includes/fu6106.php";
$futgl = date("d M Y");
$futglojk = date("d-m-Y");
$futglreas = date("ymd");
switch ($_REQUEST['r']) {
	case "pesertamedical":
function echocsv( $fields )
{
	$separator = '';
	foreach ( $fields as $field )
	{
		if ( preg_match( '/\\r|\\n|;|"/', $field ) )
		{
			$field = str_replace( '"', '""', $field );
		}
		echo $separator . $field;
		$separator = ';';
	}
	echo "\r\n";
}
		$tgl1 = explode("/", $_REQUEST['tanggal1']);	$tglawal = $tgl1[2].'-'.$tgl1[1].'-'.$tgl1[0];
		$tgl2 = explode("/", $_REQUEST['tanggal2']);	$tglakhir = $tgl2[2].'-'.$tgl2[1].'-'.$tgl2[0];
if ($_REQUEST['cat'])			{	$satu = 'AND fu_ajk_peserta.id_cost = "' . $_REQUEST['cat'] . '"';		}
if ($_REQUEST['subcat'])		{	$dua = 'AND fu_ajk_peserta.id_polis = "' . $_REQUEST['subcat'] . '"';	}
$query = sprintf('SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nopol,
fu_ajk_peserta.id_peserta,
fu_ajk_peserta.spaj,
fu_ajk_peserta.nama,
fu_ajk_peserta.gender,
fu_ajk_peserta.tgl_lahir,
fu_ajk_peserta.usia,
fu_ajk_peserta.kredit_tgl,
fu_ajk_peserta.kredit_tenor,
fu_ajk_peserta.kredit_akhir,
fu_ajk_peserta.kredit_jumlah,
fu_ajk_peserta.premi,
fu_ajk_peserta.disc_premi,
fu_ajk_peserta.bunga,
fu_ajk_peserta.biaya_adm,
fu_ajk_peserta.biaya_refund,
fu_ajk_peserta.ext_premi,
fu_ajk_peserta.totalpremi,
fu_ajk_peserta.badant,
fu_ajk_peserta.badanb,
fu_ajk_peserta.status_medik,
fu_ajk_peserta.status_aktif,
fu_ajk_peserta.status_peserta,
fu_ajk_peserta.regional,
fu_ajk_peserta.area,
fu_ajk_peserta.cabang
FROM
fu_ajk_peserta
INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON fu_ajk_peserta.id_polis = fu_ajk_polis.id
WHERE fu_ajk_peserta.id!="" AND fu_ajk_peserta.status_aktif="pending" '.$satu.' '.$dua.' AND fu_ajk_peserta.del IS NULL');
$result = mysql_query( $query, $conn ) or die( mysql_error( $conn ) );
header( "Content-Type: text/csv" );
header( "Content-Disposition: attachment;filename=Data-Medical.csv" );

$row = mysql_fetch_assoc( $result );
if ( $row )
{	echocsv( array_keys( $row ) );	}

while ( $row )
{	echocsv( $row );
	$row = mysql_fetch_assoc( $result );
}
		exit;
		;
		break;
	case "":
		;
		break;
	default:
		;
} // switch

?>