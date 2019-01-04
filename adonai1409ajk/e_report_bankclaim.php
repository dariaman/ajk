<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
error_reporting(0);
require('fpdf.php');
include_once "../includes/Spreadsheet/Excel/Writer.php";
require_once('includes/metPHPXLS/Worksheet.php');
require_once('includes/metPHPXLS/Workbook.php');
$futoday  = date("d m Y");
$futgl = date("Y-m-d H:i:s");
$nmBulan = explode(" ", $futoday);
if ($nmBulan=="01") {	$_nmBulan = "Januari";	}
elseif ($nmBulan[1]=="02") {	$_nmBulan = "Februari";	}
elseif ($nmBulan[1]=="03") {	$_nmBulan = "Maret";	}
elseif ($nmBulan[1]=="04") {	$_nmBulan = "April";	}
elseif ($nmBulan[1]=="05") {	$_nmBulan = "Mei";	}
elseif ($nmBulan[1]=="06") {	$_nmBulan = "Juni";	}
elseif ($nmBulan[1]=="07") {	$_nmBulan = "Juli";	}
elseif ($nmBulan[1]=="08") {	$_nmBulan = "Agustus";	}
elseif ($nmBulan[1]=="09") {	$_nmBulan = "September";	}
elseif ($nmBulan[1]=="10") {	$_nmBulan = "Oktober";	}
elseif ($nmBulan[1]=="11") {	$_nmBulan = "November";	}
else	{	$_nmBulan = "Desember";	}
$Today_ =$nmBulan[0].' '.$_nmBulan.' '.$nmBulan[2];
include "../includes/fu6106.php";
$metpath_file = "../ajk_file/_spak/";
function KonDecRomawi($angka){
	$hsl = "";
	if($angka<1||$angka>3999){
		$hsl = "Batas Angka 1 s/d 3999";
	}else{
		while($angka>=1000){
			$hsl .= "M";
			$angka -= 1000;
		}
		if($angka>=500){
			if($angka>500){
				if($angka>=900){
					$hsl .= "CM";
					$angka-=900;
				}else{
					$hsl .= "D";
					$angka-=500;
				}
			}
		}
		while($angka>=100){
			if($angka>=400){
				$hsl .= "CD";
				$angka-=400;
			}else{
				$angka-=100;
			}
		}
		if($angka>=50){
			if($angka>=90){
				$hsl .= "XC";
				$angka-=90;
			}else{
				$hsl .= "L";
				$angka-=50;
			}
		}
		while($angka>=10){
			if($angka>=40){
				$hsl .= "XL";
				$angka-=40;
			}else{
				$hsl .= "X";
				$angka-=10;
			}
		}
		if($angka>=5){
			if($angka==9){
				$hsl .= "IX";
				$angka-=9;
			}else{
				$hsl .= "V";
				$angka-=5;
			}
		}
		while($angka>=1){
			if($angka==4){
				$hsl .= "IV";
				$angka-=4;
			}else{
				$hsl .= "I";
				$angka-=1;
			}
		}
	}
	return ($hsl);
}
function bulan($bln){
	$bulan = $bln;
Switch ($bulan){
	case 1 : $bulan="Januari";
		Break;
	case 2 : $bulan="Februari";
		Break;
	case 3 : $bulan="Maret";
		Break;
	case 4 : $bulan="April";
		Break;
	case 5 : $bulan="Mei";
		Break;
	case 6 : $bulan="Juni";
		Break;
	case 7 : $bulan="Juli";
		Break;
	case 8 : $bulan="Agustus";
		Break;
	case 9 : $bulan="September";
		Break;
	case 10 : $bulan="Oktober";
		Break;
	case 11 : $bulan="November";
		Break;
	case 12 : $bulan="Desember";
		Break;
}
	return $bulan;
}


function HeaderingExcel($filename) {
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=$filename" );
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
}
HeaderingExcel('DATA KLAIM  BERDASARKAN PERUSAHAAN.xls');
$workbook = new Workbook("");
$worksheet1 =& $workbook->add_worksheet('DATA KLAIM');
$format =& $workbook->add_format();
$format->set_align('vcenter');	$format->set_align('center');	$format->set_color('white');	$format->set_bold();	$format->set_pattern();	$format->set_fg_color('green');
$fjudul =& $workbook->add_format();	$fjudul->set_align('vcenter');	$fjudul->set_align('center');	$fjudul->set_bold();

$worksheet1->merge_cells(0, 0, 0, 7);	$worksheet1->write_string(0, 0, "DATA KLAIM PERUSAHAAN", $fjudul, 1);

$worksheet1->set_row(4, 15);
$worksheet1->set_column(9, 0, 5);	$worksheet1->write_string(9, 0, "No", $format);
$worksheet1->set_column(9, 1, 5);	$worksheet1->write_string(9, 1, "Nomor Klaim", $format);
$worksheet1->set_column(9, 2, 5);	$worksheet1->write_string(9, 2, "Cabang", $format);
$worksheet1->set_column(9, 3, 5);	$worksheet1->write_string(9, 3, "Mitra", $format);
$worksheet1->set_column(9, 4, 5);	$worksheet1->write_string(9, 4, "Cover Asuransi", $format);
$worksheet1->set_column(9, 5, 5);	$worksheet1->write_string(9, 5, "Kategori", $format);
$worksheet1->set_column(9, 6, 5);	$worksheet1->write_string(9, 6, "Produk", $format);
$worksheet1->set_column(9, 7, 5);	$worksheet1->write_string(9, 7, "ID Peserta", $format);
$worksheet1->set_column(9, 8, 5);	$worksheet1->write_string(9, 8, "Nama Debitur", $format);
$worksheet1->set_column(9, 9, 5);	$worksheet1->write_string(9, 9, "Tgl Lahir", $format);
$worksheet1->set_column(9, 10, 5);	$worksheet1->write_string(9, 10, "Usia", $format);
$worksheet1->set_column(9, 11, 5);	$worksheet1->write_string(9, 11, "Plafond Kredit", $format);
$worksheet1->set_column(9, 12, 5);	$worksheet1->write_string(9, 12, "Tuntutan Klaim", $format);
$worksheet1->set_column(9, 13, 5);	$worksheet1->write_string(9, 13, "Tgl Akad", $format);
$worksheet1->set_column(9, 14, 5);	$worksheet1->write_string(9, 14, "J.Wkt (Th.)", $format);
$worksheet1->set_column(9, 15, 5);	$worksheet1->write_string(9, 15, "DOL", $format);
$worksheet1->set_column(9, 16, 5);	$worksheet1->write_string(9, 16, "Akad s/d DOL (hari)", $format);
$worksheet1->set_column(9, 17, 5);	$worksheet1->write_string(9, 17, "Tgl. Terima Laporan", $format);
$worksheet1->set_column(9, 18, 5);	$worksheet1->write_string(9, 18, "Lama Terima Laporan", $format);
$worksheet1->set_column(9, 19, 5);	$worksheet1->write_string(9, 19, "Tgl. Update Klaim", $format);
$worksheet1->set_column(9, 20, 5);	$worksheet1->write_string(9, 20, "Tgl. lapor Asuransi", $format);
$worksheet1->set_column(9, 21, 5);	$worksheet1->write_string(9, 21, "Kelengkapan Dokumen", $format);
$worksheet1->set_column(9, 22, 5);	$worksheet1->write_string(9, 22, "Tgl. Status Lengkap", $format);
$worksheet1->set_column(9, 23, 5);	$worksheet1->write_string(9, 23, "Due Date (PKS)", $format);
$worksheet1->set_column(9, 24, 5);	$worksheet1->write_string(9, 24, "Tgl. kirim Dok. Ke Asuransi", $format);
$worksheet1->set_column(9, 25, 5);	$worksheet1->write_string(9, 25, "Today", $format);
$worksheet1->set_column(9, 26, 5);	$worksheet1->write_string(9, 26, "Status Release Asuransi (hari)", $format);
$worksheet1->set_column(9, 27, 5);	$worksheet1->write_string(9, 27, "EM", $format);
$worksheet1->set_column(9, 28, 5);	$worksheet1->write_string(9, 28, "EM Keterangan", $format);
$worksheet1->set_column(9, 29, 5);	$worksheet1->write_string(9, 29, "Tgl. Investigasi", $format);
$worksheet1->set_column(9, 30, 5);	$worksheet1->write_string(9, 30, "Hasil investigasi", $format);
$worksheet1->set_column(9, 31, 5);	$worksheet1->write_string(9, 31, "Penyebab Kematian", $format);
$worksheet1->set_column(9, 32, 5);	$worksheet1->write_string(9, 32, "policy Liability", $format);
$worksheet1->set_column(9, 33, 5);	$worksheet1->write_string(9, 33, "Status Klaim", $format);
$worksheet1->set_column(9, 34, 5);	$worksheet1->write_string(9, 34, "Keterangan Asuransi", $format);
$worksheet1->set_column(9, 35, 5);	$worksheet1->write_string(9, 35, "Pengajuan Keuangan", $format);
$worksheet1->set_column(9, 36, 5);	$worksheet1->write_string(9, 36, "Bayar Ke Bank (Rp)", $format);
$worksheet1->set_column(9, 37, 5);	$worksheet1->write_string(9, 37, "Ref. Pemb ke bank", $format);
$worksheet1->set_column(9, 38, 5);	$worksheet1->write_string(9, 38, "Tgl Pembayaran ke Client", $format);
$worksheet1->set_column(9, 39, 5);	$worksheet1->write_string(9, 39, "Kol", $format);
$baris = 10;
$sql="";


if($_REQUEST['id_asuransi']=="all"){
	$asuransi="";
}else{
	$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
}

if(empty($_REQUEST['id_polis'])){
	$polis="";
}else{
	$polis=" and fu_ajk_polis.id=".$_REQUEST['id_polis'];
}

if($_REQUEST['liability']=='ALL'){
	$liability='';
}else{
	$liability=' and fu_ajk_cn.policy_liability="'.$_REQUEST['liability'].'"';
}
$tgl_lapor='';
if($_REQUEST['tglcheck1']!==""){
	$tgl_lapor=' and fu_ajk_cn.input_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
}

$tipe_produk='';
if($_REQUEST['tipe_produk']!=="All"){
	$tipe_produk=' and fu_ajk_polis.typeproduk="'.$_REQUEST['tipe_produk'].'"';
}

$tgl_dol='';
if($_REQUEST['tglcheck3']!==""){
	$tgl_lapor=' and fu_ajk_cn.tgl_claim between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
}


if(empty($_REQUEST['status_klaim'])){
	$status_klaim="";
}else{
	$status_klaim="  and IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
								'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."'";
}

if(empty($_REQUEST['status_bayar'])){
	$status_bayar="";
}else{
	$status_bayar=" and and if(fu_ajk_cn.tgl_bayar_asuransi is null or fu_ajk_cn.tgl_bayar_asuransi='0000-00-00','UNPAID' ,'PAID')=".$_REQUEST['status_bayar'];
}

if(empty($_REQUEST['kol'])){
	$kol="";
}else{
	$kol=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL,

						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
}
					$kucing = mysql_query("SELECT
						CONCAT(DATE_FORMAT(fu_ajk_cn.input_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
						fu_ajk_cn.id_cabang,
						fu_ajk_grupproduk.nmproduk AS mitra,
						fu_ajk_asuransi.`name`,
						fu_ajk_polis.nmproduk,
						IF(fu_ajk_polis.`typeproduk`='NON SPK','Percepatan','Reguler') AS kategori,
						fu_ajk_peserta.id_peserta,
						fu_ajk_peserta.nama,
						fu_ajk_peserta.tgl_lahir,
						fu_ajk_peserta.usia,
						fu_ajk_peserta.kredit_jumlah,
						fu_ajk_cn.total_claim,
						fu_ajk_cn.tuntutan_klaim,
						fu_ajk_peserta.kredit_tgl,
						ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS tenor,
						fu_ajk_klaim.tgl_klaim AS dol,
						DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
						IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_terima_laporan,
						DATEDIFF(fu_ajk_cn.input_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
						'' AS tgl_update_klaim,
						IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
						fu_ajk_cn.keterangan AS kelengkapan_dokumen,
						IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
						IF(fu_ajk_peserta.type_data='SPK',IF(fu_ajk_klaim.tgl_document_lengkap IS NOT NULL,fu_ajk_klaim.tgl_document_lengkap+28,''),IF(fu_ajk_klaim.tgl_document_lengkap IS NOT NULL,fu_ajk_klaim.tgl_document_lengkap+14,'')) AS due_date,
						IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
						CURRENT_DATE() AS today,
						IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
						/*fu_ajk_spak.ext_premi*/ '' AS EM,
						/*fu_ajk_spak.ket_ext*/ '' AS keterangan_EM,
						IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
						fu_ajk_klaim.diagnosa AS hasil_investigasi,
						fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
						fu_ajk_cn.policy_liability AS polis_liability,
						fu_ajk_pembayaran_status.pembayaran_status,
						fu_ajk_klaim_status.status_klaim,
						IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'Ditolak',
						'Dokumen Belum Lengkap')) AS status_dokumen,
						'' AS keterangan_asuransi,
						fu_ajk_cn.total_bayar_asuransi,
						'' AS ref_bayar_asuransi,
						fu_ajk_cn.tgl_bayar_asuransi,
						'' AS nilai_pengajuan_keuangan,
						fu_ajk_cn.total_claim  AS bayar_ke_bank,
						'' AS ref_pembayaran_ke_bank,
						fu_ajk_cn.tgl_byr_claim AS tgl_bayar_ke_client,
						fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim AS selisih,
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
						FROM
						fu_ajk_cn
						INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
						INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
						INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
						LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
						LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
						LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
						/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
						LEFT JOIN fu_ajk_namapenyakit ON fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
						LEFT JOIN fu_ajk_pembayaran_status ON fu_ajk_pembayaran_status.id=fu_ajk_cn.status_bayar
						WHERE fu_ajk_cn.type_claim='Death' AND fu_ajk_cn.del IS NULL AND `fu_ajk_cn`.`confirm_claim` <> 'Pending'
						AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi." ".$tipe_produk." ".$polis." ".$status_bayar." ".$status_klaim." ".$kol." ".$tgl_dol." ".$tgl_lapor." ".$liability."
						ORDER BY fu_ajk_asuransi.id,fu_ajk_polis.`typeproduk`,
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))),
						fu_ajk_cn.id DESC");

		$no=1;
		while ($datanya_ = mysql_fetch_array($kucing)) {
			$worksheet1->write_string($baris, 0,$no);
			$worksheet1->write_string($baris, 1,$datanya_['klaim_id']);
			$worksheet1->write_string($baris, 2,$datanya_['id_cabang']);
			$worksheet1->write_string($baris, 3,$datanya_['mitra']);
			$worksheet1->write_string($baris, 4,$datanya_['name']);
			$worksheet1->write_string($baris, 5,$datanya_['kategori']);
			$worksheet1->write_string($baris, 6,$datanya_['nmproduk']);
			$worksheet1->write_string($baris, 7,$datanya_['id_peserta']);
			$worksheet1->write_string($baris, 8,$datanya_['nama']);
			$worksheet1->write_string($baris, 9,$datanya_['tgl_lahir']);
			$worksheet1->write_string($baris, 10,$datanya_['usia']);
			$worksheet1->write_number($baris, 11,$datanya_['kredit_jumlah']);
			$worksheet1->write_number($baris, 12,$datanya_['total_claim']);
			$worksheet1->write_string($baris, 13,$datanya_['kredit_tgl']);
			$worksheet1->write_number($baris, 14,$datanya_['tenor']);
			$worksheet1->write_string($baris, 15,$datanya_['dol']);
			$worksheet1->write_number($baris, 16,$datanya_['akad_dol']);
			$worksheet1->write_string($baris, 17,$datanya_['tgl_terima_laporan']);
			$worksheet1->write_string($baris, 18,$datanya_['lama_terima_laporan']);
			$worksheet1->write_string($baris, 19,$datanya_['tgl_update_klaim']);
			$worksheet1->write_string($baris, 20,$datanya_['tgl_lapor_asuransi']);
			$worksheet1->write_string($baris, 21,$datanya_['kelengkapan_dokumen']);
			$worksheet1->write_string($baris, 22,$datanya_['tgl_status_lengkap']);
			$worksheet1->write_string($baris, 23,$datanya_['due_date']);
			$worksheet1->write_string($baris, 24,$datanya_['tgl_kirim_dokumen']);
			$worksheet1->write_string($baris, 25,$datanya_['today']);
			$worksheet1->write_string($baris, 26,$datanya_['status_release']);
			$worksheet1->write_string($baris, 27,$datanya_['EM']);
			$worksheet1->write_string($baris, 28,$datanya_['keterangan_EM']);
			$worksheet1->write_string($baris, 29,$datanya_['tgl_investigasi']);
			$worksheet1->write_string($baris, 30,$datanya_['hasil_investigasi']);
			$worksheet1->write_string($baris, 31,$datanya_['penyebab_meinggal']);
			$worksheet1->write_string($baris, 32,$datanya_['polis_liability']);
			$worksheet1->write_string($baris, 33,$datanya_['status_klaim']);
			$worksheet1->write_string($baris, 34,$datanya_['keterangan_asuransi']);
			$worksheet1->write_number($baris, 35,$datanya_['nilai_pengajuan_keuangan']);
			$worksheet1->write_number($baris, 36,$datanya_['bayar_ke_bank']);
			$worksheet1->write_string($baris, 37,$datanya_['ref_pembayaran_ke_bank']);
			$worksheet1->write_string($baris, 38,$datanya_['tgl_bayar_ke_client']);
			$worksheet1->write_string($baris, 40,$datanya_['kol']);
		
			$baris++;
			$no++;
}

$workbook->close();
	
?>
