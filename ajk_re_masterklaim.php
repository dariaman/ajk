<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
connect();
switch ($_REQUEST['sh']) {
	case "liable":

echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Liable</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>

		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td  width="40%" align="right">Klaim Liability  <font color="red">*</font></td><td> : <select name="klaim_liable">
				<option value="">--- Pilih Klaim Liability ---</option>
				<option value="liable" '._selected($_REQUEST['klaim_liable'],'liable').'>Klaim Liable</option>
				<option value="nonliable" '._selected($_REQUEST['klaim_liable'],'nonliable').'>Klaim Non Liable</option>
				</select></td>
		</tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}
			if ($_REQUEST['id_asuransi']==""){
				$error_2 = '<div align="center"><font color="red"><blink>Silahkan pilih asuransi<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}

			if ($_REQUEST['klaim_liable']==""){
				$error_5 = '<div align="center"><font color="red"><blink>Silahkan pilih jenis klaim liability<br /></div></font></blink>';
			}

			if ($error_1 or $error_2 or $error_3 or $error_4 OR $error_5) {
				echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 . ''. $error_5;
			} else {
				if($_REQUEST['klaim_liable']=="liable"){
					$s='klaim_liable';
				}elseif($_REQUEST['klaim_liable']=="nonliable"){
					$s='klaim_nonliable';
				}
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er='.$s.'&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th>No</th>
					<th>Bukopin Cabang</th>
					<th>Cover Asuransi</th>
					<th>Kategori</th>
					<th>Nama Debitur</th>
					<th>Tgl Lahir</th>
					<th>Usia</th>
					<th>Plafond Kredit </th>
					<th>Tuntutan Klaim </th>
					<th>Tgl Akad</th>
					<th>J.Wkt (Th.)</th>
					<th>DOL</th>
					<th>Akad s/d DOL (hari)</th>
					<th>Tgl. Terima Laporan</th>
					<th>Kelengkapan Dokumen Klaim</th>
					<th>Tanggal Status Lengkap</th>
					<th>Status Klaim</th>
					<th>Kol</th>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
				if($_REQUEST['klaim_liable']=="liable"){
				$sqlKlaim = $database->doQuery("select
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								ROUND(aa.kredit_tenor/12) as kredit_tenor,   /* fu_ajk_peserta.data jika 'SPK' maka tenor tahun selain itu 'BULAN'*/
								aa.dol,
								DATEDIFF(aa.dol,aa.kredit_tgl) as akad_dol,
								CONCAT('KURANG : ',GROUP_CONCAT(IF(fu_ajk_klaim_doc.dokumen is null,CONCAT('Dokumen ',fu_ajk_dokumenklaim.nama_dok),NULL))) as dokumen,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.keterangan,
								aa.status_klaim,
								aa.kol
								from (SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								fu_ajk_dokumenklaim_bank.id_dok as dok,
								fu_ajk_cn.keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document,
								fu_ajk_klaim.tgl_document_lengkap,
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*/
								/*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_peserta.id_polis = fu_ajk_dokumenklaim_bank.id_produk
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id) aa 
								LEFT JOIN fu_ajk_klaim_doc on aa.klaim_id=fu_ajk_klaim_doc.id_klaim and fu_ajk_klaim_doc.dokumen=aa.dok
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=aa.dok
								group BY
								aa.klaim_id,
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								aa.kredit_tenor,
								aa.dol,
								aa.keterangan,
								aa.status_klaim,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.kol LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("select
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								ROUND(aa.kredit_tenor/12) as kredit_tenor,   /* fu_ajk_peserta.data jika 'SPK' maka tenor tahun selain itu 'BULAN'*/
								aa.dol,
								DATEDIFF(aa.dol,aa.kredit_tgl) as akad_dol,
								CONCAT('KURANG : ',GROUP_CONCAT(IF(fu_ajk_klaim_doc.dokumen is null,CONCAT('Dokumen ',fu_ajk_dokumenklaim.nama_dok),NULL))) as dokumen,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.keterangan,
								aa.status_klaim,
								aa.kol
								from (SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								fu_ajk_dokumenklaim_bank.id_dok as dok,
								fu_ajk_cn.keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_klaim.tgl_document,
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*/
								/*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_peserta.id_polis = fu_ajk_dokumenklaim_bank.id_produk
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)>365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id) aa 
								LEFT JOIN fu_ajk_klaim_doc on aa.klaim_id=fu_ajk_klaim_doc.id_klaim and fu_ajk_klaim_doc.dokumen=aa.dok
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=aa.dok
								group BY
								aa.klaim_id,
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								aa.kredit_tenor,
								aa.dol,
								aa.keterangan,
								aa.status_klaim,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.kol");
				}elseif($_REQUEST['klaim_liable']=="nonliable"){

					$sqlKlaim = $database->doQuery("select
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								ROUND(aa.kredit_tenor/12) as kredit_tenor,   /* fu_ajk_peserta.data jika 'SPK' maka tenor tahun selain itu 'BULAN'*/
								aa.dol,
								DATEDIFF(aa.dol,aa.kredit_tgl) as akad_dol,
								CONCAT('KURANG : ',GROUP_CONCAT(IF(fu_ajk_klaim_doc.dokumen is null,CONCAT('Dokumen ',fu_ajk_dokumenklaim.nama_dok),NULL))) as dokumen,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.keterangan,
								aa.status_klaim,
								aa.kol
								from (SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								fu_ajk_dokumenklaim_bank.id_dok as dok,
								fu_ajk_cn.keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document,
								fu_ajk_klaim.tgl_document_lengkap,
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
					
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_peserta.id_polis = fu_ajk_dokumenklaim_bank.id_produk
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
					
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death'
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
					
								order by
								fu_ajk_peserta.id) aa
								LEFT JOIN fu_ajk_klaim_doc on aa.klaim_id=fu_ajk_klaim_doc.id_klaim and fu_ajk_klaim_doc.dokumen=aa.dok
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=aa.dok
								group BY
								aa.klaim_id,
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								aa.kredit_tenor,
								aa.dol,
								aa.keterangan,
								aa.status_klaim,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.kol LIMIT ". $m ." , 25");
					
					$sqlKlaim1 = $database->doQuery("select
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								ROUND(aa.kredit_tenor/12) as kredit_tenor,   /* fu_ajk_peserta.data jika 'SPK' maka tenor tahun selain itu 'BULAN'*/
								aa.dol,
								DATEDIFF(aa.dol,aa.kredit_tgl) as akad_dol,
								CONCAT('KURANG : ',GROUP_CONCAT(IF(fu_ajk_klaim_doc.dokumen is null,CONCAT('Dokumen ',fu_ajk_dokumenklaim.nama_dok),NULL))) as dokumen,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.keterangan,
								aa.status_klaim,
								aa.kol
								from (SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								fu_ajk_dokumenklaim_bank.id_dok as dok,
								fu_ajk_cn.keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_klaim.tgl_document,
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
					
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_peserta.id_polis = fu_ajk_dokumenklaim_bank.id_produk
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
					
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death'
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
					
								order by
								fu_ajk_peserta.id) aa
								LEFT JOIN fu_ajk_klaim_doc on aa.klaim_id=fu_ajk_klaim_doc.id_klaim and fu_ajk_klaim_doc.dokumen=aa.dok
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=aa.dok
								group BY
								aa.klaim_id,
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								aa.kredit_tenor,
								aa.dol,
								aa.keterangan,
								aa.status_klaim,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.kol");
				}
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['id_cabang'].'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['nama'].'</td>
					<td>'.$datanya_['tgl_lahir'].'</td>
					<td>'.$datanya_['usia'].'</td>
					<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
					<td>'.number_format($datanya_['total_claim'],2).'</td>
					<td>'.$datanya_['kredit_tgl'].'</td>
					<td>'.$datanya_['kredit_tenor'].'</td>
					<td>'.$datanya_['dol'].'</td>
					<td>'.$datanya_['akad_dol'].'</td>
					<td>'.$datanya_['tgl_document'].'</td>
					<td></td>
					<td>'.$datanya_['tgl_document_lengkap'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['kol'].'</td>
					</tr>';
					$no++;
					
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=liable&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&klaim_liable='.$_REQUEST['klaim_liable'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}


		;
		break;

	case "nonliable":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim NonLiable</font></th></tr></table>';

		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		echo '</select></td></tr>

		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}
			if ($_REQUEST['id_asuransi']==""){
				$error_2 = '<div align="center"><font color="red"><blink>Silahkan pilih asuransi<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or $error_2 or $error_3 or $error_4) {
				echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_nonliable&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Bukopin Cabang</td>
					<td>Cover Asuransi</td>
					<td>Kategori</td>
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
					<td>Kelengkapan Dokumen Klaim</td>
					<td>Tanggal Status Lengkap</td>
					<td>Status Klaim</td>
					<td>Kol</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlKlaim = $database->doQuery("select
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								ROUND(aa.kredit_tenor/12) as kredit_tenor,   /* fu_ajk_peserta.data jika 'SPK' maka tenor tahun selain itu 'BULAN'*/
								aa.dol,
								DATEDIFF(aa.dol,aa.kredit_tgl) as akad_dol,
								CONCAT('KURANG : ',GROUP_CONCAT(IF(fu_ajk_klaim_doc.dokumen is null,CONCAT('Dokumen ',fu_ajk_dokumenklaim.nama_dok),NULL))) as dokumen,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.keterangan,
								aa.status_klaim,
								aa.kol
								from (SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								fu_ajk_dokumenklaim_bank.id_dok as dok,
								fu_ajk_cn.keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document,
								fu_ajk_klaim.tgl_document_lengkap,
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_peserta.id_polis = fu_ajk_dokumenklaim_bank.id_produk
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id) aa 
								LEFT JOIN fu_ajk_klaim_doc on aa.klaim_id=fu_ajk_klaim_doc.id_klaim and fu_ajk_klaim_doc.dokumen=aa.dok
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=aa.dok
								group BY
								aa.klaim_id,
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								aa.kredit_tenor,
								aa.dol,
								aa.keterangan,
								aa.status_klaim,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.kol LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("select
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								ROUND(aa.kredit_tenor/12) as kredit_tenor,   /* fu_ajk_peserta.data jika 'SPK' maka tenor tahun selain itu 'BULAN'*/
								aa.dol,
								DATEDIFF(aa.dol,aa.kredit_tgl) as akad_dol,
								CONCAT('KURANG : ',GROUP_CONCAT(IF(fu_ajk_klaim_doc.dokumen is null,CONCAT('Dokumen ',fu_ajk_dokumenklaim.nama_dok),NULL))) as dokumen,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.keterangan,
								aa.status_klaim,
								aa.kol
								from (SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								fu_ajk_dokumenklaim_bank.id_dok as dok,
								fu_ajk_cn.keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_klaim.tgl_document,
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								LEFT JOIN fu_ajk_dokumenklaim_bank ON fu_ajk_peserta.id_polis = fu_ajk_dokumenklaim_bank.id_produk
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id) aa 
								LEFT JOIN fu_ajk_klaim_doc on aa.klaim_id=fu_ajk_klaim_doc.id_klaim and fu_ajk_klaim_doc.dokumen=aa.dok
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=aa.dok
								group BY
								aa.klaim_id,
								aa.id_cabang,
								aa.`name`,
								aa.nmproduk,
								aa.nama,
								aa.tgl_lahir,
								aa.usia,
								aa.kredit_jumlah,
								aa.total_claim,
								aa.kredit_tgl,
								aa.kredit_tenor,
								aa.dol,
								aa.keterangan,
								aa.status_klaim,
								aa.tgl_document,
								aa.tgl_document_lengkap,
								aa.kol");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['id_cabang'].'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['nama'].'</td>
					<td>'.$datanya_['tgl_lahir'].'</td>
					<td>'.$datanya_['usia'].'</td>
					<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
					<td>'.number_format($datanya_['total_claim'],2).'</td>
					<td>'.$datanya_['kredit_tgl'].'</td>
					<td>'.$datanya_['kredit_tenor'].'</td>
					<td>'.$datanya_['dol'].'</td>
					<td>'.$datanya_['akad_dol'].'</td>
					<td>'.$datanya_['tgl_document'].'</td>
					<td></td>
					<td>'.$datanya_['tgl_document_lengkap'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['kol'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=nonliable&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "tiering":
	echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Tiering</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		echo '</select></td></tr>
			<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or  $error_3 or $error_4) {
				echo $error_1 . '' . $error_3 . '' . $error_4 ;
			} else {
				$q1='';
				if($_REQUEST['id_asuransi']!=""){
					$q1=" and fu_ajk_dn.`id_as`=".$_REQUEST['id_asuransi'];
				}
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_tiering&id_cost='.$_REQUEST['id_cost'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Status</td>
					<td>Debitur</td>
					<td>Plafond</td>
					<td>Nilai Klaim</td>
					<td>Nilai Tiering</td>
					<td>Asuransi bayar</td>
					<td>Portofolio</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlKlaim = $database->doQuery("SELECT 
								status_klaim,
								nama_bank,
								count(id) as jml_debitur,
								sum(kredit_jumlah) as plafond,
								sum(total_claim) as total_klaim,
								sum(nilai_tiering) as nilai_tiering,
								sum(asuransi_bayar) as asuransi_bayar,
								sum(total_claim)*50/100 as portofolio from (
								SELECT
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_costumer.`name` as nama_bank,
								fu_ajk_peserta.id,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar
								
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$q1."
								
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								) aa GROUP BY
								
								status_klaim,
								nama_bank
				 				LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT 
								status_klaim,
								nama_bank,
								count(id) as jml_debitur,
								sum(kredit_jumlah) as plafond,
								sum(total_claim) as total_klaim,
								sum(nilai_tiering) as nilai_tiering,
								sum(asuransi_bayar) as asuransi_bayar,
								sum(total_claim)*50/100 as portofolio from (
								SELECT
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_costumer.`name` as nama_bank,
								fu_ajk_peserta.id,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar
								
								FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$q1."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								) aa GROUP BY
								
								status_klaim,
								nama_bank");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['jml_debitur'].'</td>
					<td>'.$datanya_['plafond'].'</td>
					<td>'.number_format($datanya_['total_klaim'],2).'</td>
					<td>'.number_format($datanya_['nilai_tiering'],2).'</td>
					<td>'.number_format($datanya_['asuransi_bayar'],2).'</td>
					<td>'.number_format($datanya_['portofolio'],2).'</td>
					</tr>';
					$no++;
				}

				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=tiering&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "tiering_as":
		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Tiering</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_asuransi" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>

				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_asuransi']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih asuransi<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or  $error_3 or $error_4) {
				echo $error_1 . '' . $error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_tiering_as&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Status</td>
					<td>Debitur</td>
					<td>Plafond</td>
					<td>Nilai Klaim</td>
					<td>Nilai Tiering</td>
					<td>Asuransi bayar</td>
					<td>Portofolio</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlKlaim = $database->doQuery("SELECT 
					status_klaim,
					`name` as nama_asuransi,
					count(id) as jml_debitur,
					sum(kredit_jumlah) as plafond,
					sum(total_claim) as total_klaim,
					sum(nilai_tiering) as nilai_tiering,
					sum(asuransi_bayar) as asuransi_bayar,
					sum(total_claim)*50/100 as portofolio from (
					SELECT
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_asuransi.`name`,
					fu_ajk_peserta.id,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.total_claim,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
					fu_ajk_cn.total_bayar_asuransi as asuransi_bayar
					
					FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
					/*fu_ajk_peserta
					INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
					inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
					*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
					where fu_ajk_cn.type_claim='Death' 
					and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
					and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
					and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
					) aa GROUP BY
					
					status_klaim,
					`name`
				 	LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT 
					status_klaim,
					`name` as nama_asuransi,
					count(id) as jml_debitur,
					sum(kredit_jumlah) as plafond,
					sum(total_claim) as total_klaim,
					sum(nilai_tiering) as nilai_tiering,
					sum(asuransi_bayar) as asuransi_bayar,
					sum(total_claim)*50/100 as portofolio from (
					SELECT
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_asuransi.`name`,
					fu_ajk_peserta.id,
					fu_ajk_peserta.kredit_jumlah,
					fu_ajk_cn.total_claim,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
					if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
					fu_ajk_cn.total_bayar_asuransi as asuransi_bayar
					
					FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
					/*fu_ajk_peserta
					INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
					inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
					*/
					/*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
					where fu_ajk_cn.type_claim='Death' 
					and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
					and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'
					and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
					) aa GROUP BY
					
					status_klaim,
					`name`");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['jml_debitur'].'</td>
					<td>'.number_format($datanya_['plafond'],2).'</td>
					<td>'.number_format($datanya_['total_klaim'],2).'</td>
					<td>'.number_format($datanya_['nilai_tiering'],2).'</td>
					<td>'.number_format($datanya_['asuransi_bayar'],2).'</td>
					<td>'.number_format($datanya_['portofolio'],2).'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=tiering_as&re=dataklaim&id_asuransi='.$_REQUEST['id_asuransi'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "tieringasuransi":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Tiering Asuransi</font></th></tr></table>';

		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
     <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr><tr><td width="40%" align="right">Kol<font color="red">*</font></td><td> : 
				<select name="kol">
				<option value="">---Pilih Kolekbilitas---</option>
				<option value="1" '._selected("1", $_REQUEST['kol']).'>1</option>
				<option value="2" '._selected("2", $_REQUEST['kol']).'>2</option>
				<option value="3" '._selected("3", $_REQUEST['kol']).'>3</option>
				<option value="4" '._selected("4", $_REQUEST['kol']).'>4</option>
				<option value="5" '._selected("5", $_REQUEST['kol']).'>5</option>
				</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih perusahaan<br /></div></font></blink>';
			}
			
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or $error_3 or $error_4) {
				echo $error_1 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_tiering_asuransi&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Bukopin Cabang</td>
					<td>Cover Asuransi</td>
					<td>Kategori</td>
					<td>Nama Debitur</td>
					<td>Tgl Lahir</td>
					<td>Usia</td>
					<td>Plafond Kredit </td>
					<td>Tuntutan Klaim </td>
					<td>Tanggal Status Lengkap</td>
					<td>Status Klaim</td>
					<td>Asuransi Bayar (Rp.)</td>
					<td>Tgl. Asuransi Bayar</td>
					<td>Persentase Tiering</td>
					<td>Nilai Tiering</td>
					<td>Selisih</td>
					<td>Kol</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
				
				$q1='';
				$q2='';
				if($_REQUEST['id_asuransi']!=""){
					$q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
				}
				
				if($_REQUEST['kol']!=""){
					$q2=" and IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5'))))=".$_REQUEST['kol']."";
				}
				
				
				$sqlKlaim = $database->doQuery("SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_cn.keterangan,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
								fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
								
								/*
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								*/
								
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*/
								/*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2."
								
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and (fu_ajk_polis.id=11 or fu_ajk_polis.id=12) and fu_ajk_klaim.sebab_meninggal<>7)
								
								order by 
								fu_ajk_peserta.id
 								LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_cn.keterangan,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
								fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
								
								/*
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								*/
								
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2."
								
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12 and fu_ajk_klaim.sebab_meninggal<>7)
								
								order by 
								fu_ajk_peserta.id
							");
								$totalRows=mysql_num_rows($sqlKlaim1);
								$no=$m+1;
								$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
								while($datanya_ = mysql_fetch_array($sqlKlaim)){
									if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
									echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
									<td width="1%" align="center">'.$no.'</td>
									<td>'.$datanya_['id_cabang'].'</td>
									<td>'.$datanya_['name'].'</td>
									<td>'.$datanya_['nmproduk'].'</td>
									<td>'.$datanya_['nama'].'</td>
									<td>'.$datanya_['tgl_lahir'].'</td>
									<td>'.$datanya_['usia'].'</td>
									<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
									<td>'.number_format($datanya_['total_claim'],2).'</td>
									<td>'.$datanya_['tgl_document_lengkap'].'</td>
									<td>'.$datanya_['status_klaim'].'</td>
									<td>'.number_format($datanya_['asuransi_bayar'],2).'</td>
									<td>'.$datanya_['tgl_asuransi_bayar'].'</td>
									<td>'.$datanya_['persentase_tiering'].'</td>
									<td>'.number_format($datanya_['nilai_tiering'],2).'</td>
									<td>'.number_format($datanya_['selisih'],2).'</td>
									<td>'.$datanya_['kol'].'</td>
									</tr>';
									$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=tieringasuransi&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&kol='.$_REQUEST['kol'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "tieringbank":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Tiering Bank</font></th></tr></table>';

		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
     
		<tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){

			if ($_REQUEST['id_cost']==""){
				$error_2 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_2 or $error_3 or $error_4) {
				echo $error_2 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_tiering_bank&id_cost='.$_REQUEST['id_cost'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Bukopin Cabang</td>
					<td>Cover Asuransi</td>
					<td>Kategori</td>
					<td>Nama Debitur</td>
					<td>Tgl Lahir</td>
					<td>Usia</td>
					<td>Plafond Kredit </td>
					<td>Tuntutan Klaim </td>
					<td>Tanggal Status Lengkap</td>
					<td>Status Klaim</td>
					<td>Asuransi Bayar (Rp.)</td>
					<td>Tgl. Asuransi Bayar</td>
					<td>Persentase Tiering</td>
					<td>Nilai Tiering</td>
					<td>Selisih</td>
					<td>Kol</td>
					</tr>';
				
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlKlaim = $database->doQuery("SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_cn.keterangan,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
								fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
								
								/*
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								*/
								
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*/
								/*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id
 								LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_cn.keterangan,
								fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
								fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
								
								/*
								fu_ajk_peserta.kredit_tgl,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								*/
								
								/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
								
								/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
								INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
								*/
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id
							");
								$totalRows=mysql_num_rows($sqlKlaim1);
								$no=$m+1;
								$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
								while($datanya_ = mysql_fetch_array($sqlKlaim)){
									if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
									echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
									<td width="1%" align="center">'.$no.'</td>
									<td>'.$datanya_['id_cabang'].'</td>
									<td>'.$datanya_['name'].'</td>
									<td>'.$datanya_['nmproduk'].'</td>
									<td>'.$datanya_['nama'].'</td>
									<td>'.$datanya_['tgl_lahir'].'</td>
									<td>'.$datanya_['usia'].'</td>
									<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
									<td>'.number_format($datanya_['total_claim'],2).'</td>
									<td>'.$datanya_['tgl_document_lengkap'].'</td>
									<td>'.$datanya_['status_klaim'].'</td>
									<td>'.number_format($datanya_['asuransi_bayar'],2).'</td>
									<td>'.$datanya_['tgl_asuransi_bayar'].'</td>
									<td>'.$datanya_['persentase_tiering'].'</td>
									<td>'.number_format($datanya_['nilai_tiering'],2).'</td>
									<td>'.number_format($datanya_['selisih'],2).'</td>
									<td>'.$datanya_['kol'].'</td>
									</tr>';
									$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=tieringbank&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "tieringkol":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Tiering Kol</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		echo '</select></td></tr>

		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="all">---Semua Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}

			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or $error_2 or $error_3 or $error_4) {
				echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_tieringkol&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Cabang</td>
					<td>Cover Asuransi</td>
					<td>Nama Debitur</td>
					<td>Tgl Lahir</td>
					<td>Usia</td>
					<td>Plafond Kredit </td>
					<td>Tuntutan Klaim </td>
					<td>Tgl Akad</td>
					<td>J.Wkt (Th.)</td>
					<td>DOL</td>
					<td>Akad s/d DOL (hari)</td>
					<td>Tgl. Laporan Asuransi</td>
					<td>Tgl. Status</td>
					<td>Lama Pengajuan (hari)</td>
					<td>Status Klaim</td>
					<td>Kategori</td>
					<td>Tiering</td>
					<td>Nilai Klaim by Tiering</td>
					<td>Kol</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				if($_REQUEST['id_asuransi']=="all"){
					$asuransi="";
				}else{
					$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
				}
				$sqlKlaim = $database->doQuery("SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								ROUND(if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) as tenor,
								fu_ajk_klaim.tgl_klaim,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
								fu_ajk_klaim.tgl_lapor_klaim,
								'' as tgl_status_klaim,
								'' as lama_pengajuan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_polis.nmproduk,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
								
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id
 								LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT
								fu_ajk_klaim.id as klaim_id,
								fu_ajk_cn.id_cabang,
								fu_ajk_asuransi.`name`,
								fu_ajk_peserta.nama,
								fu_ajk_peserta.tgl_lahir,
								fu_ajk_peserta.usia,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								ROUND(if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) as tenor,
								fu_ajk_klaim.tgl_klaim,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
								fu_ajk_klaim.tgl_lapor_klaim,
								'' as tgl_status_klaim,
								'' as lama_pengajuan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_polis.nmproduk,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
								
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
								if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
								
								IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12)
								
								order by 
								fu_ajk_peserta.id
								");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['id_cabang'].'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['nama'].'</td>
					<td>'.$datanya_['tgl_lahir'].'</td>
					<td>'.$datanya_['usia'].'</td>
					<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
					<td>'.number_format($datanya_['total_claim'],2).'</td>
					<td>'.$datanya_['kredit_tgl'].'</td>
					<td>'.$datanya_['kredit_tenor'].'</td>
					<td>'.$datanya_['dol'].'</td>
					<td>'.$datanya_['akad_dol'].'</td>
					<td>'.$datanya_['tgl_lapor_klaim'].'</td>
					<td></td>
					<td>'.$datanya_['lama_pengajuan'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['persentase_tiering'].'</td>
					<td>'.number_format($datanya_['nilai_tiering'],2).'</td>
					<td>'.$datanya_['kol'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=tieringkol&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}


		;
		break;

	case "klaimmaster":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Master</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		echo '</select></td></tr>

		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="all">---Semua Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}

			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or $error_2 or $error_3 or $error_4) {
				echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="44"><a href="e_report.php?er=klaim_master&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Nomor Klaim</td>
					<td>Cabang</td>
					<td>Mitra</td>
					<td>Cover Asuransi</td>
					<td>Kategori</td>
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
					<td>Asuransi Bayar</td>
					<td>Ref. Pemb Dari Asuransi</td>
					<td>Tgl Bayar dari Asuransi</td>
					<td>Pengajuan Keuangan</td>
					<td>Bayar Ke Bank (Rp)</td>
					<td>Ref. Pemb ke bank</td>
					<td>Tgl Pembayaran ke Client</td>
					<td>Selisih</td>
					<td>Kol</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				if($_REQUEST['id_asuransi']=="all"){
					$asuransi="";
				}else{
					$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
				}
				$sqlKlaim = $database->doQuery("SELECT

						CONCAT(DATE_FORMAT(fu_ajk_cn.tgl_claim,'%y/%m'),'/',fu_ajk_klaim.id) as klaim_id,
						fu_ajk_cn.id_cabang,
						fu_ajk_grupproduk.nmproduk as mitra,
						fu_ajk_asuransi.`name`,
						fu_ajk_polis.nmproduk,
						fu_ajk_peserta.id_peserta,
						fu_ajk_peserta.nama,
						fu_ajk_peserta.tgl_lahir,
						fu_ajk_peserta.usia,
						fu_ajk_peserta.kredit_jumlah,
						fu_ajk_cn.total_claim,
						fu_ajk_peserta.kredit_tgl,
						ROUND(if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) as tenor,
						fu_ajk_klaim.tgl_klaim as dol,
						DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
						fu_ajk_klaim.tgl_document as tgl_terima_laporan,
						DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_klaim.tgl_document) as lama_terima_laporan,
						'' as tgl_update_klaim,
						fu_ajk_klaim.tgl_lapor_klaim as tgl_lapor_asuransi,
						fu_ajk_cn.keterangan as kelengkapan_dokumen,
						fu_ajk_klaim.tgl_document_lengkap as tgl_status_lengkap,
						if(fu_ajk_peserta.type_data='SPK',fu_ajk_klaim.tgl_document_lengkap+28,fu_ajk_klaim.tgl_document_lengkap+14) as due_date,
						fu_ajk_klaim.tgl_kirim_dokumen,
						CURRENT_DATE() as today,
						DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,CURRENT_DATE()) as status_release,
						fu_ajk_spak.ext_premi as EM,
						fu_ajk_spak.ket_ext as keterangan_EM,
						fu_ajk_klaim.tgl_investigasi,
						fu_ajk_klaim.diagnosa as hasil_investigasi,
						fu_ajk_namapenyakit.namapenyakit as penyebab_meinggal,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12,'NOT LIABLE','LIABLE') as polis_liability,
						fu_ajk_klaim_status.status_klaim,
						'' as keterangan_asuransi,
						fu_ajk_cn.total_bayar_asuransi,
						'' as ref_bayar_asuransi,
						fu_ajk_cn.tgl_bayar_asuransi,
						'' as nilai_pengajuan_keuangan,
						fu_ajk_cn.total_claim  as bayar_ke_bank,
						'' as ref_pembayaran_ke_bank,
						fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,
						fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,
						
						
						/*fu_ajk_klaim.tgl_document_lengkap,
						fu_ajk_cn.keterangan,
						fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
						fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
						
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
						
						fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
						*/
						/*
						fu_ajk_peserta.kredit_tgl,
						if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
						fu_ajk_klaim.tgl_klaim as dol,
						*/
						
						/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
						IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
						if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
						if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
						if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
						,
						if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
						if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
						if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
						FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
						/*fu_ajk_peserta
						INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
						inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
						*/
						INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
						INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
						LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
						LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
						LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
						LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak
						LEFT JOIN fu_ajk_namapenyakit on fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
						/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
						
						/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
						INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
						*/
						where fu_ajk_cn.type_claim='Death' 
						and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi."
						and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
						order by 
						fu_ajk_peserta.id


 								LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT

							CONCAT(DATE_FORMAT(fu_ajk_cn.tgl_claim,'%y/%m'),'/',fu_ajk_klaim.id) as klaim_id,
							fu_ajk_cn.id_cabang,
							fu_ajk_grupproduk.nmproduk as mitra,
							fu_ajk_asuransi.`name`,
							fu_ajk_polis.nmproduk,
							fu_ajk_peserta.id_peserta,
							fu_ajk_peserta.nama,
							fu_ajk_peserta.tgl_lahir,
							fu_ajk_peserta.usia,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							fu_ajk_peserta.kredit_tgl,
							ROUND(if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) as tenor,
							fu_ajk_klaim.tgl_klaim as dol,
							DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
							fu_ajk_klaim.tgl_document as tgl_terima_laporan,
							DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_klaim.tgl_document) as lama_terima_laporan,
							'' as tgl_update_klaim,
							fu_ajk_klaim.tgl_lapor_klaim as tgl_lapor_asuransi,
							fu_ajk_cn.keterangan as kelengkapan_dokumen,
							fu_ajk_klaim.tgl_document_lengkap as tgl_status_lengkap,
							if(fu_ajk_peserta.type_data='SPK',fu_ajk_klaim.tgl_document_lengkap+28,fu_ajk_klaim.tgl_document_lengkap+14) as due_date,
							fu_ajk_klaim.tgl_kirim_dokumen,
							CURRENT_DATE() as today,
							DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,CURRENT_DATE()) as status_release,
							fu_ajk_spak.ext_premi as EM,
							fu_ajk_spak.ket_ext as keterangan_EM,
							fu_ajk_klaim.tgl_investigasi,
							fu_ajk_klaim.diagnosa as hasil_investigasi,
							fu_ajk_namapenyakit.namapenyakit as penyebab_meinggal,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12,'NOT LIABLE','LIABLE') as polis_liability,
							fu_ajk_klaim_status.status_klaim,
							'' as keterangan_asuransi,
							fu_ajk_cn.total_bayar_asuransi,
							'' as ref_bayar_asuransi,
							fu_ajk_cn.tgl_bayar_asuransi,
							'' as nilai_pengajuan_keuangan,
							fu_ajk_cn.total_claim  as bayar_ke_bank,
							'' as ref_pembayaran_ke_bank,
							fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,
							fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,
							
							
							/*fu_ajk_klaim.tgl_document_lengkap,
							fu_ajk_cn.keterangan,
							fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
							fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
							
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
							
							fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
							*/
							/*
							fu_ajk_peserta.kredit_tgl,
							if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
							fu_ajk_klaim.tgl_klaim as dol,
							*/
							
							/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
							IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
							if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
							if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
							if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
							,
							if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
							if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
							if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
							FROM
							fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
							/*fu_ajk_peserta
							INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
							inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
							*/
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
							LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
							LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak
							LEFT JOIN fu_ajk_namapenyakit on fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
							/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
							
							/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
							INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
							*/
							where fu_ajk_cn.type_claim='Death' 
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi."
							and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
							order by 
							fu_ajk_peserta.id
							");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['klaim_id'].'</td>
					<td>'.$datanya_['id_cabang'].'</td>
					<td>'.$datanya_['mitra'].'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['id_peserta'].'</td>
					<td>'.$datanya_['nama'].'</td>
					<td>'.$datanya_['tgl_lahir'].'</td>
					<td>'.$datanya_['usia'].'</td>
					<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
					<td>'.number_format($datanya_['total_claim'],2).'</td>
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
					<td>'.number_format($datanya_['total_bayar_asuransi'],2).'</td>
					<td>'.$datanya_['ref_bayar_asuransi'].'</td>
					<td>'.$datanya_['tgl_bayar_asuransi'].'</td>
					<td>'.number_format($datanya_['nilai_pengajuan_keuangan'],2).'</td>
					<td>'.$datanya_['bayar_ke_bank'].'</td>
					<td>'.$datanya_['ref_pembayaran_ke_bank'].'</td>
					<td>'.$datanya_['tgl_bayar_ke_client'].'</td>
					<td>'.number_format($datanya_['selisih'],2).'</td>
					<td>'.$datanya_['kol'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=klaimmaster&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}


		;
		break;

	case "klaimoutstanding":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Outstanding</font></th></tr></table>';
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		echo '</select></td></tr>

		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="all">---Semua Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}

			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or $error_2 or $error_3 or $error_4) {
				echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="44"><a href="e_report.php?er=klaim_outstanding&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Nomor Klaim</td>
					<td>Cabang</td>
					<td>Mitra</td>
					<td>Cover Asuransi</td>
					<td>Kategori</td>
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
					<td>Asuransi Bayar</td>
					<td>Ref. Pemb Dari Asuransi</td>
					<td>Tgl Bayar dari Asuransi</td>
					<td>Pengajuan Keuangan</td>
					<td>Bayar Ke Bank (Rp)</td>
					<td>Ref. Pemb ke bank</td>
					<td>Tgl Pembayaran ke Client</td>
					<td>Selisih</td>
					<td>Kol</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				if($_REQUEST['id_asuransi']=="all"){
					$asuransi="";
				}else{
					$asuransi="and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
				}
				$sqlKlaim = $database->doQuery("SELECT

						CONCAT(DATE_FORMAT(fu_ajk_cn.tgl_claim,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
						fu_ajk_cn.id_cabang,
						fu_ajk_grupproduk.nmproduk as mitra,
						fu_ajk_asuransi.`name`,
						fu_ajk_polis.nmproduk,
						fu_ajk_peserta.id_peserta,
						fu_ajk_peserta.nama,
						fu_ajk_peserta.tgl_lahir,
						fu_ajk_peserta.usia,
						fu_ajk_peserta.kredit_jumlah,
						fu_ajk_cn.total_claim,
						fu_ajk_peserta.kredit_tgl,
						ROUND(if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) as tenor,
						fu_ajk_klaim.tgl_klaim as dol,
						DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
						fu_ajk_klaim.tgl_document as tgl_terima_laporan,
						DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_klaim.tgl_document) as lama_terima_laporan,
						'' as tgl_update_klaim,
						fu_ajk_klaim.tgl_lapor_klaim as tgl_lapor_asuransi,
						fu_ajk_cn.keterangan as kelengkapan_dokumen,
						fu_ajk_klaim.tgl_document_lengkap as tgl_status_lengkap,
						if(fu_ajk_peserta.type_data='SPK',fu_ajk_klaim.tgl_document_lengkap+28,fu_ajk_klaim.tgl_document_lengkap+14) as due_date,
						fu_ajk_klaim.tgl_kirim_dokumen,
						CURRENT_DATE() as today,
						DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,CURRENT_DATE()) as status_release,
						fu_ajk_spak.ext_premi as EM,
						fu_ajk_spak.ket_ext as keterangan_EM,
						fu_ajk_klaim.tgl_investigasi,
						fu_ajk_klaim.diagnosa as hasil_investigasi,
						fu_ajk_namapenyakit.namapenyakit as penyebab_meinggal,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12,'NOT LIABLE','LIABLE') as polis_liability,
						fu_ajk_klaim_status.status_klaim,
						'' as keterangan_asuransi,
						fu_ajk_cn.total_bayar_asuransi,
						'' as ref_bayar_asuransi,
						fu_ajk_cn.tgl_bayar_asuransi,
						'' as nilai_pengajuan_keuangan,
						fu_ajk_cn.total_claim  as bayar_ke_bank,
						'' as ref_pembayaran_ke_bank,
						fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,
						fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,
						
						
						/*fu_ajk_klaim.tgl_document_lengkap,
						fu_ajk_cn.keterangan,
						fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
						fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
						
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
						
						fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
						*/
						/*
						fu_ajk_peserta.kredit_tgl,
						if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
						fu_ajk_klaim.tgl_klaim as dol,
						*/
						
						/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
						IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
						if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
						if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
						if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
						,
						if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
						if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
						if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
						FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
						/*fu_ajk_peserta
						INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
						inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
						*/
						/*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
						INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
						INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
						LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
						LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
						LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
						LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak
						LEFT JOIN fu_ajk_namapenyakit on fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
						/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
						
						/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
						INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
						*/
						where fu_ajk_cn.type_claim='Death'  and fu_ajk_cn.confirm_claim<>'Approve(paid)'
						and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi."
						and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
						order by 
						fu_ajk_peserta.id


 								LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT

							
						CONCAT(DATE_FORMAT(fu_ajk_cn.tgl_claim,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
							fu_ajk_cn.id_cabang,
							fu_ajk_grupproduk.nmproduk as mitra,
							fu_ajk_asuransi.`name`,
							fu_ajk_polis.nmproduk,
							fu_ajk_peserta.id_peserta,
							fu_ajk_peserta.nama,
							fu_ajk_peserta.tgl_lahir,
							fu_ajk_peserta.usia,
							fu_ajk_peserta.kredit_jumlah,
							fu_ajk_cn.total_claim,
							fu_ajk_peserta.kredit_tgl,
							ROUND(if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) as tenor,
							fu_ajk_klaim.tgl_klaim as dol,
							DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
							fu_ajk_klaim.tgl_document as tgl_terima_laporan,
							DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_klaim.tgl_document) as lama_terima_laporan,
							'' as tgl_update_klaim,
							fu_ajk_klaim.tgl_lapor_klaim as tgl_lapor_asuransi,
							fu_ajk_cn.keterangan as kelengkapan_dokumen,
							fu_ajk_klaim.tgl_document_lengkap as tgl_status_lengkap,
							if(fu_ajk_peserta.type_data='SPK',fu_ajk_klaim.tgl_document_lengkap+28,fu_ajk_klaim.tgl_document_lengkap+14) as due_date,
							fu_ajk_klaim.tgl_kirim_dokumen,
							CURRENT_DATE() as today,
							DATEDIFF(fu_ajk_klaim.tgl_kirim_dokumen,CURRENT_DATE()) as status_release,
							fu_ajk_spak.ext_premi as EM,
							fu_ajk_spak.ket_ext as keterangan_EM,
							fu_ajk_klaim.tgl_investigasi,
							fu_ajk_klaim.diagnosa as hasil_investigasi,
							fu_ajk_namapenyakit.namapenyakit as penyebab_meinggal,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.id<>11 and fu_ajk_polis.id<>12,'NOT LIABLE','LIABLE') as polis_liability,
							fu_ajk_klaim_status.status_klaim,
							'' as keterangan_asuransi,
							fu_ajk_cn.total_bayar_asuransi,
							'' as ref_bayar_asuransi,
							fu_ajk_cn.tgl_bayar_asuransi,
							'' as nilai_pengajuan_keuangan,
							fu_ajk_cn.total_claim  as bayar_ke_bank,
							'' as ref_pembayaran_ke_bank,
							fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,
							fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,
							
							
							/*fu_ajk_klaim.tgl_document_lengkap,
							fu_ajk_cn.keterangan,
							fu_ajk_cn.total_bayar_asuransi as asuransi_bayar,
							fu_ajk_cn.tgl_bayar_asuransi as tgl_asuransi_bayar,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80))))))) as persentase_tiering,
							
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as nilai_tiering,
							
							fu_ajk_cn.total_bayar_asuransi-if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<31,10,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<61,20,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<91,30,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<121,40,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<151,50,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<181,60,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<271,70,80)))))))/100*fu_ajk_cn.total_claim as selisih,
							*/
							/*
							fu_ajk_peserta.kredit_tgl,
							if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
							fu_ajk_klaim.tgl_klaim as dol,
							*/
							
							/*CONVERT(GROUP_CONCAT(fu_ajk_dokumenklaim_bank.id_dok) USING utf8) as dok,*/
							IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
							if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
							if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
							if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
							,
							if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
							if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
							if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
							FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
							/*fu_ajk_peserta
							INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
							inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
							*/
							/*inner join fu_ajk_cn on  fu_ajk_peserta.id_klaim=fu_ajk_cn.id*/
							INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
							INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
							LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
							LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
							LEFT JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
							LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak
							LEFT JOIN fu_ajk_namapenyakit on fu_ajk_namapenyakit.id=fu_ajk_klaim.sebab_meninggal
							/*left JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim_bank.id_dok = fu_ajk_dokumenklaim.id*/
							
							/*LEFT JOIN fu_ajk_klaim_doc ON fu_ajk_klaim_doc.id_klaim=fu_ajk_klaim.id
							INNER JOIN fu_ajk_dokumenklaim ON fu_ajk_dokumenklaim.id=fu_ajk_klaim_doc.dokumen
							*/
							where fu_ajk_cn.type_claim='Death' and fu_ajk_cn.confirm_claim<>'Approve(paid)'
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$asuransi."
							and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
							order by 
							fu_ajk_peserta.id
							");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['klaim_id'].'</td>
					<td>'.$datanya_['id_cabang'].'</td>
					<td>'.$datanya_['mitra'].'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['id_peserta'].'</td>
					<td>'.$datanya_['nama'].'</td>
					<td>'.$datanya_['tgl_lahir'].'</td>
					<td>'.$datanya_['usia'].'</td>
					<td>'.number_format($datanya_['kredit_jumlah'],2).'</td>
					<td>'.number_format($datanya_['total_claim'],2).'</td>
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
					<td>'.number_format($datanya_['total_bayar_asuransi'],2).'</td>
					<td>'.$datanya_['ref_bayar_asuransi'].'</td>
					<td>'.$datanya_['tgl_bayar_asuransi'].'</td>
					<td>'.number_format($datanya_['nilai_pengajuan_keuangan'],2).'</td>
					<td>'.number_format($datanya_['bayar_ke_bank'],2).'</td>
					<td>'.$datanya_['ref_pembayaran_ke_bank'].'</td>
					<td>'.$datanya_['tgl_bayar_ke_client'].'</td>
					<td>'.number_format($datanya_['selisih'],2).'</td>
					<td>'.$datanya_['kol'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=klaimoutstanding&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "sumklaim":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Summary Klaim Berdasarkan Pengajuan Klaim (Bank)</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td width="40%" align="right">Kol<font color="red">*</font></td><td> : 
				<select name="kol">
				<option value="">---Pilih Kolekbilitas---</option>
				<option value="1" '._selected("1", $_REQUEST['kol']).'>1</option>
				<option value="2" '._selected("2", $_REQUEST['kol']).'>2</option>
				<option value="3" '._selected("3", $_REQUEST['kol']).'>3</option>
				<option value="4" '._selected("4", $_REQUEST['kol']).'>4</option>
				<option value="5" '._selected("5", $_REQUEST['kol']).'>5</option>
				</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or  $error_3 or $error_4) {
				echo $error_1 . '' . $error_3 . '' . $error_4 ;
			} else {
				
				$q1='';
				$q2='';
				if($_REQUEST['id_asuransi']!=""){
					$q1=" and fu_ajk_dn.id_as='".$_REQUEST['id_asuransi']."'";
				}
				
				if($_REQUEST['kol']!=""){
					$q2=" WHERE kol='".$_REQUEST['kol']."'";
				}
				
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=summary_klaim&id_cost='.$_REQUEST['id_cost'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Perusahaan</td>
					<td>Kategori</td>
					<td>Kol</td>
					<td>Status Klaim</td>
					<td>Jumlah</td>
					<td>Plafond</td>
					<td>tuntukan Klaim</td>
					<td>Penerimaan Asuransi</td>
					<td>Pembayaran Ke Bank</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlKlaim = $database->doQuery("SELECT
					status_klaim,
					`name`,
					nmproduk,
					kol,
					COUNT(jml) AS jml,
					SUM(plafond) AS plafond,
					SUM(klaim) AS klaim,
					SUM(terima_asuransi) AS terima_asuransi,
					SUM(bayar_bank) AS bayar_bank
					FROM (
					SELECT
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_costumer.`name`,
					fu_ajk_polis.nmproduk,
					fu_ajk_peserta.id AS jml,
					fu_ajk_peserta.kredit_jumlah AS plafond,
					fu_ajk_cn.total_claim AS klaim,
					fu_ajk_cn.total_bayar_asuransi AS terima_asuransi,
					IF(fu_ajk_cn.confirm_claim='Approve(paid)', fu_ajk_cn.total_claim,0) AS bayar_bank,
					IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
					,
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) AS kol
					FROM
					fu_ajk_cn
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
					
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
					WHERE fu_ajk_cn.type_claim='Death' 
					AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']." ".$q1."
					AND fu_ajk_cn.tgl_claim BETWEEN '".$_REQUEST ['tglcheck1']."' AND '".$_REQUEST ['tglcheck2']."'
					ORDER BY 
					fu_ajk_costumer.`name`,
					fu_ajk_polis.nmproduk,
					fu_ajk_klaim_status.status_klaim) aa
					".$q2."
					GROUP BY
					aa.status_klaim,
					aa.`name`,
					aa.nmproduk,
					aa.kol
				 				LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT
					status_klaim,
					`name`,
					nmproduk,
					kol,
					COUNT(jml) AS jml,
					SUM(plafond) AS plafond,
					SUM(klaim) AS klaim,
					SUM(terima_asuransi) AS terima_asuransi,
					SUM(bayar_bank) AS bayar_bank
					FROM (
					SELECT
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_costumer.`name`,
					fu_ajk_polis.nmproduk,
					fu_ajk_peserta.id AS jml,
					fu_ajk_peserta.kredit_jumlah AS plafond,
					fu_ajk_cn.total_claim AS klaim,
					fu_ajk_cn.total_bayar_asuransi AS terima_asuransi,
					IF(fu_ajk_cn.confirm_claim='Approve(paid)', fu_ajk_cn.total_claim,0) AS bayar_bank,
					IF(fu_ajk_cn.confirm_claim<>'Approve(paid)',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
					,
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) AS kol
					FROM
					fu_ajk_cn
					INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
					INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
					
					INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
					WHERE fu_ajk_cn.type_claim='Death' 
					AND fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."  ".$q1."
					AND fu_ajk_cn.tgl_claim BETWEEN '".$_REQUEST ['tglcheck1']."' AND '".$_REQUEST ['tglcheck2']."'
					ORDER BY 
					fu_ajk_costumer.`name`,
					fu_ajk_polis.nmproduk,
					fu_ajk_klaim_status.status_klaim) aa
					".$q2."
					GROUP BY
					aa.status_klaim,
					aa.`name`,
					aa.nmproduk,
					aa.kol");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['kol'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['jml'].'</td>
					<td>'.number_format($datanya_['plafond'],2).'</td>
					<td>'.number_format($datanya_['klaim'],2).'</td>
					<td>'.number_format($datanya_['terima_asuransi'],2).'</td>
					<td>'.number_format($datanya_['bayar_bank'],2).'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=sumklaim&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "sumklaim_kol":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Summary Klaim Berdasarkan Kolekbitas (Bank)</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>

				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or  $error_3 or $error_4) {
				echo $error_1 . '' . $error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=summary_klaim_kol&id_cost='.$_REQUEST['id_cost'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Perusahaan</td>
					<td>Kategori</td>
					<td>Kol</td>
					<td>Status Klaim</td>
					<td>Debitur</td>
					<td>Nilai Klaim</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlKlaim = $database->doQuery("select 
								aa.status_klaim,
								aa.`name`,
								aa.nmproduk,
								aa.kol,
								count(aa.id) as jml,
								sum(aa.total_claim) as nilai_klaim
								
								from (
								SELECT
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_costumer.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.id,
								fu_ajk_cn.total_claim,
								IF(fu_ajk_cn.confirm_claim='Approve(paid)',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*/
								/*INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_klaim = fu_ajk_cn.id*/
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'
								) aa GROUP BY
								aa.status_klaim,
								aa.`name`,
								aa.nmproduk,
								aa.kol
								order by 
								aa.`name`,
								aa.nmproduk,
								aa.kol,
								aa.status_klaim
				 				LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("select 
								aa.status_klaim,
								aa.`name`,
								aa.nmproduk,
								aa.kol,
								count(aa.id) as jml,
								sum(aa.total_claim) as nilai_klaim
								
								from (
								SELECT
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_costumer.`name`,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.id,
								fu_ajk_cn.total_claim,
								IF(fu_ajk_cn.tgl_byr_claim is null,
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<121,'4','5')))
								,
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<91,'2',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)>90,'3',
								if(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<121,'4','5')))) as kol
								
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
								/*fu_ajk_peserta
								INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
								inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
								*//*INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_klaim = fu_ajk_cn.id*/
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death' 
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'
								) aa GROUP BY
								aa.status_klaim,
								aa.`name`,
								aa.nmproduk,
								aa.kol
								order by 
								aa.`name`,
								aa.nmproduk,
								aa.kol,
								aa.status_klaim");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['kol'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['jml'].'</td>
					<td>'.number_format($datanya_['nilai_klaim'],2).'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=sumklaim_kol&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;

	case "sumklaim_as":
		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Summary Klaim Berdasarkan Asuransi</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>

		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Tanggal Klaim</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}
			if ($_REQUEST['id_asuransi']==""){
				$error_2 = '<div align="center"><font color="red"><blink>Silahkan pilih asuransi<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck1'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_4 = '<div align="center"><font color="red"><blink>Tanggal klaim tidak boleh kosong</div></font></blink>';
			}


			if ($error_1 or $error_2 or $error_3 or $error_4) {
				echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=klaim_summary_asuransi&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<td>No</td>
					<td>Perusahaan</td>
					<td>Asuransi</td>
					<td>Status Klaim</td>
					<td>Jumlah</td>
					<td>Plafond</td>
					<td>Tuntun Klaim</td>
					<td>Penerimaan Asuransi</td>
					<td>Pembayaran ke Bank</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlKlaim = $database->doQuery("SELECT
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_costumer.`name`,
					fu_ajk_asuransi.`name` as asuransi,
					count(fu_ajk_peserta.id) as jml,
					sum(fu_ajk_peserta.kredit_jumlah) as plafond,
					sum(fu_ajk_cn.total_claim) as klaim,
					sum(fu_ajk_cn.total_bayar_asuransi) as terima_asuransi,
					sum(if(fu_ajk_cn.confirm_claim='Approve(paid)', fu_ajk_cn.total_claim,0)) as bayar_bank
					
					FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
					/*fu_ajk_peserta
					INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
					inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
					*//*INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_klaim = fu_ajk_cn.id*/
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
					where fu_ajk_cn.type_claim='Death' 
					and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
					and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
					and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
					group BY
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_costumer.`name`,
					fu_ajk_asuransi.`name` 
					order by 
					fu_ajk_costumer.`name`,
					fu_ajk_asuransi.`name`, 
					fu_ajk_klaim_status.status_klaim
					LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_costumer.`name`,
					fu_ajk_asuransi.`name` as asuransi,
					count(fu_ajk_peserta.id) as jml,
					sum(fu_ajk_peserta.kredit_jumlah) as plafond,
					sum(fu_ajk_cn.total_claim) as klaim,
					sum(fu_ajk_cn.total_bayar_asuransi) as terima_asuransi,
					sum(if(fu_ajk_cn.confirm_claim='Approve(paid)', fu_ajk_cn.total_claim,0)) as bayar_bank
					
					FROM
						fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						
					/*fu_ajk_peserta
					INNER JOIN fu_ajk_dn ON fu_ajk_peserta.id_dn = fu_ajk_dn.id
					inner join fu_ajk_cn on  fu_ajk_dn.id=fu_ajk_cn.id_dn
					*//*INNER JOIN fu_ajk_cn ON fu_ajk_peserta.id_klaim = fu_ajk_cn.id*/		
					INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
					INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
					INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
					LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
					LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
					where fu_ajk_cn.type_claim='Death' 
					and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
					and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi']."
					and fu_ajk_cn.tgl_claim between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."' 
					group BY
					fu_ajk_klaim_status.status_klaim,
					fu_ajk_costumer.`name`,
					fu_ajk_asuransi.`name` ");
				$totalRows=mysql_num_rows($sqlKlaim1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlKlaim)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['name'].'</td>
					<td>'.$datanya_['asuransi'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['jml'].'</td>
					<td>'.number_format($datanya_['plafond'],2).'</td>
					<td>'.number_format($datanya_['klaim'],2).'</td>
					<td>'.number_format($datanya_['terima_asuransi'],2).'</td>
					<td>'.number_format($datanya_['bayar_bank'],2).'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=sumklaim_as&id_asuransi='.$_REQUEST['id_asuransi'].'&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}

		;
		break;
	case "klaim_share" :
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_polis ORDER BY nmproduk DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Produk <font color="red">*</font></td><td> : <select name="prod" id="grupprod">
			  	<option value="all">---All Produk---</option>';
			  	while($metcost_ = mysql_fetch_array($metcost1)) {
					echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_produk'], $metcost_['id']).'>'.$metcost_['nmproduk'].'</option>';
				}
				echo '</select></td></tr>
			  
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim_share"><input type="submit" name="ere" id="btncari" value="Show"></td></tr>
			  </table>
			  </form> ';
			if($_REQUEST['re']=="dataklaim_share"){
				header("location:e_report_klaim.php?er=klaim_share&id_cost=".$_REQUEST['id_cost']."&prod=".$_REQUEST['prod']);
			}
		break;
	default:
		;
} // switch

echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_reg":		{url:\'javascript/metcombo/data.php?req=setpoliscostumerregional\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_reg"] ?>\'},
			"id_cab":		{url:\'javascript/metcombo/data.php?req=setpoliscostumercabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},

		},
		loadingImage:\'../loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
?>