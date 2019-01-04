<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
connect();

echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Outstanding</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
while($metcost_ = mysql_fetch_array($metcost)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
}
$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
echo '</select></td></tr>
		<tr><td align="right">Nama Produk</td>
		<td id="polis_rate">: <select name="id_polis" id="id_polis">
		<option value="">-- Pilih Produk --</option>
		</select></td></tr>

		<tr><td align="right">Kategori</td>
		<td id="polis_rate">: <select name="tipe_produk">
				<option value="All">-- All Kategori--</option>
				<option value="SPK"'._selected($_REQUEST['tipe_produk'], "SPK").'>Reguler</option>
				<option value="NON SPK"'._selected($_REQUEST['tipe_produk'], "NON SPK").'>Percepatan</option>
		</select></td></tr>
		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="all">---Semua Asuransi---</option>';
while($metcost_ = mysql_fetch_array($metcost1)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
}
echo '</select></td></tr>
		<tr><td align="right">Liability</td>
		<td id="polis_rate">: <select name="liability">
			<option value="ALL">-- All Klaim Liability--</option>
			<option value="LIABLE" '._selected($_REQUEST['liability'], "LIABLE").'>LIABLE</option>
			<option value="NONLIABLE" '._selected($_REQUEST['liability'], "NONLIABLE").'>NON LIABLE</option>
		</select></td></tr>
		<tr><td align="right">Status Bayar</td>
		<td id="polis_rate">: <select name="status_bayar">
		<option value="">-- Pilih Status bayar--</option>';
$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_pembayaran_status where `type`=\'2\' ORDER BY id DESC');
while($metcost_ = mysql_fetch_array($metcost1)) {
	echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['status_bayar'], $metcost_['id']).'>'.$metcost_['pembayaran_status'].'</option>';
}
echo '</select></td></tr>
		<tr><td align="right">Kelengakapan Dokumen</td>
		<td id="polis_rate">: <select name="status_klaim">
			<option value="">-- Pilih Status Klaim--</option>
			<option value="Dokumen Sudah Lengkap" '._selected($_REQUEST['status_klaim'], "Dokumen Sudah Lengkap").'>Dokumen Sudah Lengkap</option>
			<option value="Dokumen Belum Lengkap" '._selected($_REQUEST['status_klaim'], "Dokumen Belum Lengkap").'>Dokumen Belum Lengkap</option>
			<option value="Ditolak" '._selected($_REQUEST['status_klaim'], "Ditolak").'>Ditolak</option>
		</select></td></tr>
		<tr><td align="right">Kol</td>
		<td id="polis_rate">: <select name="kol">
				<option value="">-- Pilih Kol--</option>
				<option value="1"'._selected($_REQUEST['kol'], "1").'>1</option>
				<option value="2"'._selected($_REQUEST['kol'], "2").'>2</option>
				<option value="3"'._selected($_REQUEST['kol'], "3").'>3</option>
				<option value="4"'._selected($_REQUEST['kol'], "4").'>4</option>
				<option value="5"'._selected($_REQUEST['kol'], "5").'>5</option>
		</select></td></tr>

	<tr><td align="right">Tanggal Lapor</td>
	  <td> : <input type="text" id="fromakad1" name="tglcheck1" value="'.$_REQUEST['tglcheck1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromakad2" name="tglcheck2" value="'.$_REQUEST['tglcheck2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
	  <tr><td align="right">DOL</td>
	  <td> : <input type="text" id="fromakad3" name="tglcheck3" value="'.$_REQUEST['tglcheck3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad3);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad3);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromakad4" name="tglcheck4" value="'.$_REQUEST['tglcheck4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad4);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad4);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

if($_REQUEST['re']=='dataklaim'){
	if ($_REQUEST['id_cost']==""){
		$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
	}

	if ($error_1 or $error_2 or $error_3 or $error_4) {
		echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 ;
	} else {


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
			$status_klaim="  and if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
						'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."'";
		}

		if(empty($_REQUEST['status_bayar'])){
			$status_bayar="";
		}else{
			$status_bayar=" and fu_ajk_cn.status_bayar=".$_REQUEST['status_bayar'];
		}

		if(empty($_REQUEST['kol'])){
			$kol="";
		}else{
			$kol=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
		}

		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		$sqlKlaim = $database->doQuery("SELECT
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
						DATE(fu_ajk_cn.input_date) AS tgl_terima_laporan,
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
						if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
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
						fu_ajk_cn.id DESC


 						LIMIT ". $m ." , 25");

		$sqlKlaim1 = $database->doQuery("SELECT
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
						DATE(fu_ajk_cn.input_date) AS tgl_terima_laporan,
						DATEDIFF(fu_ajk_cn.input_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
						'' AS tgl_update_klaim,
						IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
						fu_ajk_cn.keterangan AS kelengkapan_dokumen,
						IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
						IF(fu_ajk_peserta.type_data='SPK',IF(fu_ajk_klaim.tgl_document_lengkap IS NOT NULL,fu_ajk_klaim.tgl_document_lengkap+28,''),IF(fu_ajk_klaim.tgl_document_lengkap IS NOT NULL,fu_ajk_klaim.tgl_document_lengkap+14,'')) AS due_date,
						IF(fu_ajk_klaim.tgl_kirim_dokumen='0000-00-00',NULL,fu_ajk_klaim.tgl_kirim_dokumen) AS tgl_kirim_dokumen,
						CURRENT_DATE() AS today,
						IF(fu_ajk_klaim.tgl_kirim_dokumen<>'0000-00-00' OR fu_ajk_klaim.tgl_kirim_dokumen IS NOT NULL,DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen),'') AS status_release,
						/*fu_ajk_spak.ext_premi*/'' AS EM,
						/*fu_ajk_spak.ket_ext*/'' AS keterangan_EM,
						IF(fu_ajk_klaim.tgl_investigasi='0000-00-00',NULL,fu_ajk_klaim.tgl_investigasi) AS tgl_investigasi,
						fu_ajk_klaim.diagnosa AS hasil_investigasi,
						fu_ajk_namapenyakit.namapenyakit AS penyebab_meinggal,
						IF(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 AND fu_ajk_polis.typeproduk='NON SPK','NOT LIABLE','LIABLE') AS polis_liability,
						fu_ajk_pembayaran_status.pembayaran_status,
						fu_ajk_klaim_status.status_klaim,
						if(`id_klaim_status`=6,'Ditolak',
						if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
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
						fu_ajk_cn.id DESC
							");



		$totalRows=mysql_num_rows($sqlKlaim1);
		$no=$m+1;
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		
			if($_REQUEST['liability']=='ALL'){
				$liability_report='
							<td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=summary_klaim_bank_liable_all&
							id_cost='.$_REQUEST['id_cost'].'&
							id_asuransi='.$_REQUEST['id_asuransi'].'&
							id_polis='.$_REQUEST['id_polis'].'&
							tipe_produk='.$_REQUEST['tipe_produk'].'&
							liability='.$_REQUEST['liability'].'&
							tglcheck1='.$_REQUEST['tglcheck1'].'&
							tglcheck2='.$_REQUEST['tglcheck2'].'&
							tglcheck3='.$_REQUEST['tglcheck3'].'&
							tglcheck4='.$_REQUEST['tglcheck4'].'&
							status_klaim='.$_REQUEST['status_klaim'].'&
							status_bayar='.$_REQUEST['status_bayar'].'&
							kol='.$_REQUEST['kol'].'&
							format_report='.$_REQUEST['format_report'].'"><img src="image/pdf.png" width="25" border="0"><br />Summary Klaim Liable</a></td>
							
							<td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=summary_klaim_bank_nonliable_all&
							id_cost='.$_REQUEST['id_cost'].'&
							id_asuransi='.$_REQUEST['id_asuransi'].'&
							id_polis='.$_REQUEST['id_polis'].'&
							tipe_produk='.$_REQUEST['tipe_produk'].'&
							liability='.$_REQUEST['liability'].'&
							tglcheck1='.$_REQUEST['tglcheck1'].'&
							tglcheck2='.$_REQUEST['tglcheck2'].'&
							tglcheck3='.$_REQUEST['tglcheck3'].'&
							tglcheck4='.$_REQUEST['tglcheck4'].'&
							status_klaim='.$_REQUEST['status_klaim'].'&
							status_bayar='.$_REQUEST['status_bayar'].'&
							kol='.$_REQUEST['kol'].'&
							format_report='.$_REQUEST['format_report'].'"><img src="image/pdf.png" width="25" border="0"><br />Summary Klaim Nonliable</a></td>';
									
			}else{
				$liability_report='
							<td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=summary_klaim_bank_'.strtolower($_REQUEST['liability']).'_all&
							id_cost='.$_REQUEST['id_cost'].'&
							id_asuransi='.$_REQUEST['id_asuransi'].'&
							id_polis='.$_REQUEST['id_polis'].'&
							tipe_produk='.$_REQUEST['tipe_produk'].'&
							liability='.$_REQUEST['liability'].'&
							tglcheck1='.$_REQUEST['tglcheck1'].'&
							tglcheck2='.$_REQUEST['tglcheck2'].'&
							tglcheck3='.$_REQUEST['tglcheck3'].'&
							tglcheck4='.$_REQUEST['tglcheck4'].'&
							status_klaim='.$_REQUEST['status_klaim'].'&
							status_bayar='.$_REQUEST['status_bayar'].'&
							kol='.$_REQUEST['kol'].'&
							format_report='.$_REQUEST['format_report'].'"><img src="image/pdf.png" width="25" border="0"><br />Summary Klaim Liable</a></td>
				';
			}
			echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
					<tr><td bgcolor="#FFF" colspan="2"><a href="e_report.php?er=klaim_outstanding&
							id_cost='.$_REQUEST['id_cost'].'&
							id_asuransi='.$_REQUEST['id_asuransi'].'&
							id_polis='.$_REQUEST['id_polis'].'&
							tipe_produk='.$_REQUEST['tipe_produk'].'&
							liability='.$_REQUEST['liability'].'&
							tglcheck1='.$_REQUEST['tglcheck1'].'&
							tglcheck2='.$_REQUEST['tglcheck2'].'&
							tglcheck3='.$_REQUEST['tglcheck3'].'&
							tglcheck4='.$_REQUEST['tglcheck4'].'&
							status_klaim='.$_REQUEST['status_klaim'].'&
							status_bayar='.$_REQUEST['status_bayar'].'&
							kol='.$_REQUEST['kol'].'&
							format_report=2"><img src="image/excel.png" width="25" border="0"><br />Print List data (*.xls)</a>
						</td><td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=klaim_outstanding&
							id_cost='.$_REQUEST['id_cost'].'&
							id_asuransi='.$_REQUEST['id_asuransi'].'&
							id_polis='.$_REQUEST['id_polis'].'&
							tipe_produk='.$_REQUEST['tipe_produk'].'&
							liability='.$_REQUEST['liability'].'&
							tglcheck1='.$_REQUEST['tglcheck1'].'&
							tglcheck2='.$_REQUEST['tglcheck2'].'&
							tglcheck3='.$_REQUEST['tglcheck3'].'&
							tglcheck4='.$_REQUEST['tglcheck4'].'&
							status_klaim='.$_REQUEST['status_klaim'].'&
							status_bayar='.$_REQUEST['status_bayar'].'&
							kol='.$_REQUEST['kol'].'&
							format_report=2"><img src="image/pdf.png" width="25" border="0"><br />Print List data (*.pdf)</a>
							</td>'.$liability_report.'
							<td colspan="36" bgcolor="#FFF"></td>
									</tr>
					<td>No</td>
					<td>Nomor Klaim</td>
					<td>Cabang</td>
					<td>Mitra</td>
					<td>Cover Asuransi</td>
					<td>Kategori</td>
					<td>Produk</td>
					<td>ID Peserta</td>
					<td>Nama Debitur</td>
					<td>Tgl Lahir</td>
					<td>Usia</td>
					<td>Plafond Kredit </td>
					<td>Tuntutan Klaim </td>
					<td>Tgl Akad</td>
					<td>J.Wkt (Th.)</td>
					<td>DOL</td>
					<td>Akad s/d DOL (hari)</td>
					<td>Tgl. Terima Laporan</td>
					<td>Lama Terima Laporan</td>
					<td>Tgl. Update Klaim</td>
					<td>Tgl. lapor Asuransi</td>
					<td>Kelengkapan Dokumen</td>
					<td>Tgl. Status Lengkap</td>
					<td>Due Date (PKS)</td>
					<td>Tgl. kirim Dok. Ke Asuransi</td>
					<td>Today</td>
					<td>Status Release Asuransi (hari)</td>
					<td>EM</td>
					<td>EM Keterangan</td>
					<td>Tgl. Investigasi</td>
					<td>Hasil investigasi</td>
					<td>Penyebab Kematian</td>
					<td>policy Liability</td>
					<td>Status Klaim</td>
					<td>Keterangan Asuransi</td>
					<td>Pengajuan Keuangan</td>
					<td>Bayar Ke Bank (Rp)</td>
					<td>Ref. Pemb ke bank</td>
					<td>Tgl Pembayaran ke Client</td>
					<td>Kol</td>
					</tr>';

			while($datanya_ = mysql_fetch_array($sqlKlaim)){
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
						<td width="1%" align="center">'.$no.'</td>
						<td>'.$datanya_['klaim_id'].'</td>
						<td>'.$datanya_['id_cabang'].'</td>
						<td>'.$datanya_['mitra'].'</td>
						<td>'.$datanya_['name'].'</td>
						<td>'.$datanya_['kategori'].'</td>
						<td>'.$datanya_['nmproduk'].'</td>
						<td>'.$datanya_['id_peserta'].'</td>
						<td>'.$datanya_['nama'].'</td>
						<td>'.$datanya_['tgl_lahir'].'</td>
						<td>'.$datanya_['usia'].'</td>
						<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
						<td>'.number_format($datanya_['tuntutan_klaim'],2).'</td>
						<td>'.$datanya_['kredit_tgl'].'</td>
						<td>'.$datanya_['tenor'].'</td>
						<td>'.$datanya_['dol'].'</td>
						<td>'.$datanya_['akad_dol'].'</td>
						<td>'.$datanya_['tgl_terima_laporan'].'</td>
						<td>'.$datanya_['lama_terima_laporan'].'</td>
						<td>'.$datanya_['tgl_update_klaim'].'</td>
						<td>'.$datanya_['tgl_lapor_asuransi'].'</td>
						<td>'.$datanya_['kelengkapan_dokumen'].'</td>
						<td>'.$datanya_['tgl_status_lengkap'].'</td>
						<td>'.$datanya_['due_date'].'</td>
						<td>'.$datanya_['tgl_kirim_dokumen'].'</td>
						<td>'.$datanya_['today'].'</td>
						<td>'.$datanya_['status_release'].'</td>
						<td>'.$datanya_['EM'].'</td>
						<td>'.$datanya_['keterangan_EM'].'</td>
						<td>'.$datanya_['tgl_investigasi'].'</td>
						<td>'.$datanya_['hasil_investigasi'].'</td>
						<td>'.$datanya_['penyebab_meinggal'].'</td>
						<td>'.$datanya_['polis_liability'].'</td>
						<td>'.$datanya_['status_klaim'].'</td>
						<td>'.$datanya_['keterangan_asuransi'].'</td>
						<td>'.number_format($datanya_['nilai_pengajuan_keuangan'],2).'</td>
						<td>'.number_format($datanya_['bayar_ke_bank'],2).'</td>
						<td>'.$datanya_['ref_pembayaran_ke_bank'].'</td>
						<td>'.$datanya_['tgl_bayar_ke_client'].'</td>
						<td>'.$datanya_['kol'].'</td>
						</tr>';
				$no++;
			}

			echo createPageNavigations($file = 'ajk_re_bankclaim.php?sh=klaimoutstanding&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&
							id_asuransi='.$_REQUEST['id_asuransi'].'&
							id_polis='.$_REQUEST['id_polis'].'&
							tipe_produk='.$_REQUEST['tipe_produk'].'&
							liability='.$_REQUEST['liability'].'&
							tglcheck1='.$_REQUEST['tglcheck1'].'&
							tglcheck2='.$_REQUEST['tglcheck2'].'&
							tglcheck3='.$_REQUEST['tglcheck3'].'&
							tglcheck4='.$_REQUEST['tglcheck4'].'&
							status_klaim='.$_REQUEST['status_klaim'].'&
							status_bayar='.$_REQUEST['status_bayar'].'&
							kol='.$_REQUEST['kol'].'&
							format_report='.$_REQUEST['format_report'].'
							', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';
	}

}

		
		
		
echo '<!--WILAYAH COMBOBOX-->
<!-- <script src="javascript/metcombo/prototype.js"></script>
<!-- <script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
/*
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
			"id_reg":		{url:\'javascript/metcombo/data.php?req=setpoliscostumerregional\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_reg"] ?>\'},
			"id_cab":		{url:\'javascript/metcombo/data.php?req=setpoliscostumercabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
*/
</script>';
echo"<script type='text/javascript' src='js/jquery/jquery.min-1.11.1.js'></script>
<script type='text/javascript'>//<![CDATA[
 	$(window).load(function(){
 		$(document).ready(function () {
 			(function ($) {
 				$('#cari').keyup(function () {
 					var rex = new RegExp($(this).val(), 'i');
 					$('.caritable tr').hide();
 					$('.caritable tr').filter(function () {
 						return rex.test($(this).text());
 					}).show();

 				})

 			}(jQuery));

			$('#id_cost').change(function(){
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumer',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_polis').html(returndata);
					}
				});

				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumerregional',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_reg').html(returndata);
					}
				});

			});
			$('#id_reg').change(function(){
			var noreg = document.getElementById('id_reg').value;
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
					cache:false,
					success:function(returndata) {
						$('#id_cab').html(returndata);
					}
				});

			});

 		});
 			var idcost = document.getElementById('id_cost').value;
			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumer',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_polis').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumerregional',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_reg').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
				cache:false,
				success:function(returndata) {
					$('#id_cab').html(returndata);
				}
			});
 	});


</script>";
?>