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

echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Data Klaim Liability</font></th></tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		$h_nama_perusahaan='Semua Perusahaan';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'" '._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';

			if($_REQUEST['id_cost']==$metcost_['id']){
				$h_nama_perusahaan=$metcost_['name'];
			}
		}
		echo '</select></td></tr>
		<tr><td width="40%" align="right">Nama Asuransi <font color="red"></font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		$h_nama_asuransi='Semua Asuransi';
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_asuransi ORDER BY name DESC');
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'" '._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';

			if($_REQUEST['id_asuransi']==$metcost_['id']){
				$h_nama_asuransi=$metcost_['name'];

			}
		}
		echo '</select></td></tr>
		<tr><td  width="40%" align="right">Klaim Liability  <font color="red">*</font></td><td> : <select name="klaim_liable">
				<option value="">--- Pilih Klaim Liability ---</option>
				<option value="liable" '._selected($_REQUEST['klaim_liable'],'liable').'>Klaim Liable</option>
				<option value="nonliable" '._selected($_REQUEST['klaim_liable'],'nonliable').'>Klaim Non Liable</option>
				</select></td>
		</tr>
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
	  				</tr>
				<tr><td align="right">DOL</td>
	  <td> : <input type="text" id="fromakad3" name="tglcheck3" value="'.$_REQUEST['tglcheck3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad3);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromakad4" name="tglcheck4" value="'.$_REQUEST['tglcheck4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad4);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
	  				</tr>
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';

		if($_REQUEST['re']=='dataklaim'){
			if ($_REQUEST['id_cost']==""){
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih bank<br /></div></font></blink>';
			}


			if ($_REQUEST['klaim_liable']==""){
				$error_5 = '<div align="center"><font color="red"><blink>Silahkan pilih jenis klaim liability<br /></div></font></blink>';
			}

			if ($error_1  or $error_3 or $error_4 OR $error_5) {
				echo $error_1 . '' . $error_2 . '' .$error_3 . '' . $error_4 . ''. $error_5;
			} else {
				if($_REQUEST['klaim_liable']=="liable"){
					$s='klaim_liable';
					$e='summary_klaim_liable_all';
				}elseif($_REQUEST['klaim_liable']=="nonliable"){
					$s='klaim_nonliable';
					$e='summary_klaim_nonliable_all';
				}

				echo '<table border="0" width="50%" cellpadding="1" cellspacing="1">
							<tr>
								<td bgcolor="#FFF" colspan="2">Perusahaan</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$h_nama_perusahaan.'</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Asuransi</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$h_nama_asuransi.'</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Klaim Liable/ Non liable</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$_REQUEST['klaim_liable'].'</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Kelengkapan Dokumen</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">';
								if(empty($_REQUEST['status_klaim'])) echo 'Semua Status Kelengkapan Dokumen'; else echo $_REQUEST['status_klaim'];
							echo '</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Kol</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">';
								if(empty($_REQUEST['kol'])) echo 'Semua Kol'; else echo $_REQUEST['kol'];
							echo '</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Tanggal Lapor</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$_REQUEST['tglcheck1'].' s.d '.$_REQUEST['tglcheck2'].'</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Dol</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$_REQUEST['tglcheck3'].' s.d '.$_REQUEST['tglcheck4'].'</td>
							</tr>
							</tr></table><br>';
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="orange">
					<tr><td bgcolor="#FFF" colspan="2"><a href="e_report.php?er='.$s.'&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&status_klaim='.$_REQUEST['status_klaim'].'&kol='.$_REQUEST['kol'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a>
							</td>
							<td bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er='.$s.'&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&status_klaim='.$_REQUEST['status_klaim'].'&kol='.$_REQUEST['kol'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '"><img src="image/pdf.png" width="25" border="0"><br />Print Klaim '.$_REQUEST['klaim_liable'].' '.$_REQUEST['status_klaim'].'</a>
							</td><td bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er='.$e.'&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&status_klaim='.$_REQUEST['status_klaim'].'&kol='.$_REQUEST['kol'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '"><img src="image/pdf.png" width="25" border="0"><br />Print Summary Klaim '.$_REQUEST['klaim_liable'].' '.$_REQUEST['status_klaim'].'</a>
							</td><td bgcolor="#FFF" colspan="19"></td>
							</tr><tr>
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
					<th>Tgl. Laporan Asuransi</th>
					<th>Kelengkapan Dokumen Klaim</th>
					<th>Tanggal Status Lengkap</th>
					<th>Status Klaim</th>
					<th>Asuransi Bayar (Rp.)</th>
					<th>Tgl Bayar Dari Asuransi</th>
					<th>Bayar Ke Bank (Rp.)</th>
					<th>Tgl Bayar Ke Client</th>
					<th>Selisih Bayar (Rp.)</th>
					<th>Kol</th>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$q_status="";
				if(!empty($_REQUEST['status_klaim'])){
					$q_status=" and if(`id_klaim_status`=6,'Ditolak',
					if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
					'Dokumen Belum Lengkap'))='".$_REQUEST['status_klaim']."' ";
				}
				$q_asuransi='';

				if($_REQUEST['id_asuransi']!=""){
					$q_asuransi=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
				}

				$q_tglklaim='';
				if(!empty($_REQUEST['tglcheck1'])){
					$q_tglklaim="and fu_ajk_cn.approve_date between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'";
				}


				$q_dol='';
				if(!empty($_REQUEST['tglcheck3'])){
					$q_dol="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tglcheck3']."' and '".$_REQUEST ['tglcheck4']."'";
				}



				$q_kol='';
				if(!empty($_REQUEST['kol'])){
					$q_kol="and
					IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
						,
						IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol'];
				}
			if($_REQUEST['klaim_liable']=="liable"){



				$sqlKlaim = $database->doQuery("SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
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
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
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
								'Dokumen Belum Lengkap')) AS keterangan,
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
								where  fu_ajk_cn.del is null  and fu_ajk_cn.type_claim='Death' and confirm_claim !='Pending'
								".$q_tglklaim."
								".$q_dol."
								".$q_status."
								".$q_kol."
								".$q_asuransi."
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_cn.confirm_claim !='Pending'
								and fu_ajk_cn.policy_liability='LIABLE'
								order by
								fu_ajk_peserta.id LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
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
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
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
								'Dokumen Belum Lengkap')) AS keterangan,
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
								where  fu_ajk_cn.del is null and fu_ajk_cn.type_claim='Death' and confirm_claim !='Pending'
								".$q_tglklaim."
								".$q_dol."
								".$q_status."
								".$q_kol."
								".$q_asuransi."
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								and fu_ajk_cn.confirm_claim !='Pending'
								and fu_ajk_cn.policy_liability='LIABLE'
								order by
								fu_ajk_peserta.id");
				}elseif($_REQUEST['klaim_liable']=="nonliable"){

					$sqlKlaim = $database->doQuery("SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
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
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
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
								'Dokumen Belum Lengkap')) AS keterangan,
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
								where  fu_ajk_cn.del is null  and fu_ajk_cn.type_claim='Death'
								and fu_ajk_cn.confirm_claim !='Pending'

								".$q_tglklaim."
								".$q_dol."
								".$q_status."
								".$q_kol."
								".$q_asuransi."
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
								/*and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')*/

								and fu_ajk_cn.policy_liability='NONLIABLE'
								order by
								fu_ajk_peserta.id LIMIT ". $m ." , 25");

					$sqlKlaim1 = $database->doQuery("SELECT
								CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
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
								ROUND(IF(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor)/12) AS kredit_tenor,
								fu_ajk_klaim.tgl_klaim AS dol,
								DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) AS akad_dol,
								IF(fu_ajk_klaim.tgl_document='0000-00-00',NULL,fu_ajk_klaim.tgl_document) AS tgl_document,
								DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) AS lama_terima_laporan,
								'' AS tgl_update_klaim,
								IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_klaim,
								fu_ajk_cn.keterangan AS kelengkapan_dokumen,
								IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_document_lengkap,
								IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
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
								'Dokumen Belum Lengkap')) AS keterangan,
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
							where  fu_ajk_cn.del is null  and fu_ajk_cn.type_claim='Death'
							and fu_ajk_cn.confirm_claim !='Pending'
							".$q_tglklaim."
							".$q_dol."
							".$q_status."
							".$q_kol."
							".$q_asuransi."
							and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."
							and fu_ajk_cn.policy_liability='NONLIABLE'
							/*and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')*/

							order by
							fu_ajk_peserta.id");
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
					<td>'.number_format($datanya_['tuntutan_klaim'],2).'</td>
					<td>'.$datanya_['kredit_tgl'].'</td>
					<td>'.$datanya_['kredit_tenor'].'</td>
					<td>'.$datanya_['dol'].'</td>
					<td>'.$datanya_['akad_dol'].'</td>
					<td>'.$datanya_['tgl_lapor_klaim'].'</td>
					<td>'.$datanya_['keterangan'].'</td>
					<td>'.$datanya_['tgl_document_lengkap'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.number_format($datanya_['total_bayar_asuransi'],2).'</td>
					<td>'.$datanya_['tgl_bayar_asuransi'].'</td>
					<td>'.number_format($datanya_['bayar_ke_bank'],2).'</td>
					<td>'.$datanya_['tgl_bayar_ke_client'].'</td>
					<td>'.number_format($datanya_['selisih'],2).'</td>
					<td>'.$datanya_['kol'].'</td>
					</tr>';
					$no++;

				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=liable&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&klaim_liable='.$_REQUEST['klaim_liable'].'&status_klaim='.$_REQUEST['status_klaim'].'&kol='.$_REQUEST['kol'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
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
								IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'DOKUMEN SUDAH LENGKAP',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'DITOLAK',
								'DOKUMEN BELUM LENGKAP')) AS keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document,
								fu_ajk_klaim.tgl_document_lengkap,

IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')

								order by
								fu_ajk_peserta.id) aa LIMIT ". $m ." , 25");

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
								IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'DOKUMEN SUDAH LENGKAP',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'DITOLAK',
								'DOKUMEN BELUM LENGKAP')) AS keterangan,
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_klaim.tgl_document_lengkap,
								fu_ajk_klaim.tgl_document,

IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')

								order by
								fu_ajk_peserta.id) aa");
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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')
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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')
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
					and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')
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
					and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')
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
			if(_selected($_REQUEST['id_cost'], $metcost_['id'])=='selected'){
				$h_nama_perusahaan=$metcost_['name'];
			}else{
				$h_nama_perusahaan='Semua Perusahaan';
			}
		}
		echo '</select></td></tr>
		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="">---Pilih Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
			if(_selected($_REQUEST['id_asuransi'], $metcost_['id'])=='selected'){
				$h_nama_asuransi=$metcost_['name'];
			}else{
				$h_nama_asuransi='Semua Asuransi';
			}
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
	  				</tr>
				<tr><td align="right">DOL</td>
	  <td> : <input type="text" id="fromakad3" name="tglcheck3" value="'.$_REQUEST['tglcheck3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad3);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromakad4" name="tglcheck4" value="'.$_REQUEST['tglcheck4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad4);return false;"/>
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

			if ($error_1 or $error_3 or $error_4) {
				echo $error_1 . '' .$error_3 . '' . $error_4 ;
			} else {

				echo '<table border="0" width="50%" cellpadding="1" cellspacing="1">
							<tr>
								<td bgcolor="#FFF" colspan="2">Perusahaan</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$h_nama_perusahaan.'</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Asuransi</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$h_nama_asuransi.'</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Kelengkapan Dokumen</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">';
							if(empty($_REQUEST['status_klaim'])) echo 'Semua Status Kelengkapan Dokumen'; else echo $_REQUEST['status_klaim'];
							echo '</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Kol</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">';
							if(empty($_REQUEST['kol'])) echo 'Semua Kol'; else echo $_REQUEST['kol'];
							echo '</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Tanggal Lapor</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$_REQUEST['tglcheck1'].' s.d '.$_REQUEST['tglcheck2'].'</td>
							</tr>
							<tr>
								<td bgcolor="#FFF" colspan="2">Dol</td><td bgcolor="#FFF" colspan="2">:</td><td bgcolor="#FFF" colspan="2">'.$_REQUEST['tglcheck3'].' s.d '.$_REQUEST['tglcheck4'].'</td>
							</tr>
							</tr></table><br>';

				echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
					<tr><td bgcolor="#FFF" colspan="2">
							<a href="e_report.php?er=klaim_tiering_asuransi&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&kol='.$_REQUEST['kol'].'&status_klaim='.$_REQUEST['status_klaim'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '"><img src="image/excel.png" width="25" border="0"><br />List Data klaim Tiering (Excel)</a>
							<a href="e_report.php?er=klaim_tiering_asuransi_all&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&kol='.$_REQUEST['kol'].'&status_klaim='.$_REQUEST['status_klaim'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '"><img src="image/excel.png" width="25" border="0"><br />List Data klaim Tiering All Kolom (Excel)</a>
							</td>
							<td bgcolor="#FFF"><a target="_blank" href="e_report_klaim.php?er=tiering_klaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&kol='.$_REQUEST['kol'].'&status_klaim='.$_REQUEST['status_klaim'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '"><img src="image/dninvoice1.jpg" width="25" border="0"><br />List Data Klaim Tiering</a></td>
							<td bgcolor="#FFF" colspan="22"><a target="_blank" href="e_report_klaim.php?er=summary_klaim_tiering_all&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&kol='.$_REQUEST['kol'].'&status_klaim='.$_REQUEST['status_klaim'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '"><img src="image/dninvoice.png" width="25" border="0"><br />Summary Klaim Tiering</a></td>

							</td></tr>
						<tr>
						<th>No</th>
						<th>Bukopin Cabang</th>
						<th>Cover Asuransi</th>
						<th>Kategori</th>
						<th>Nama Debitur</th>
						<th>Tgl Lahir</th>
						<th>Usia</th>
						<th> Plafond Kredit </th>
						<th> Tuntutan Klaim </th>
						<th>Presentase Tiering</th>
						<th> Nilai Tiering </th>
						<th>Tgl Akad</th>
						<th>J.Wkt (Th.)</th>
						<th>DOL</th>
						<th>Akad s/d DOL (hari)</th>
						<th>Tgl. Lapor Asuransi </th>
						<th>Kelengkapan Dokumen Klaim</th>
						<th>Tanggal Status Lengkap</th>
						<th>Status Klaim</th>
						<th> Asuransi Bayar (Rp.) </th>
						<th>Tanggal Pembayaran dari Asuransi </th>
						<th> Bayar ke Bank (Rp.) </th>
						<th>Tanggal Pembayaran ke Bank</th>
						<th> Selisih Bayar (Rp.) </th>
						<th>Kol</th>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$q1='';
				$q2='';
				$q3='';
				$q4='';
				$q5='';
				$q6='';

				if($_REQUEST['id_asuransi']!=""){
					$q1=" and fu_ajk_asuransi.id=".$_REQUEST['id_asuransi'];
				}

				if($_REQUEST['kol']!=""){
					$q2=" and
						IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
					IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
					,
					IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
					IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
					IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5'))))=".$_REQUEST['kol']."";
				}


				$q3="";
				if(!empty($_REQUEST['status_klaim'])){
					$q3="  and if(`id_klaim_status`=6,'Ditolak',
					if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
					'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."' ";
				}

				$q4='';
				if(!empty($_REQUEST['tglcheck1'])){
					$q4="and fu_ajk_cn.approve_date between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'";
				}


				$q5='';
				if(!empty($_REQUEST['tglcheck3'])){
					$q5="and fu_ajk_klaim.tgl_klaim between '".$_REQUEST ['tglcheck3']."' and '".$_REQUEST ['tglcheck4']."'";
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
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								fu_ajk_klaim.tgl_document_lengkap,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap'))  AS keterangan,
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

								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,

								if(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00','',fu_ajk_klaim.tgl_lapor_klaim) as tgl_lapor_klaim,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor,fu_ajk_peserta.kredit_tenor/12) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								datediff(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl) as akad_dol,
								if(fu_ajk_cn.tgl_byr_claim<>'',fu_ajk_cn.total_claim,'')  as bayar_ke_bank,
								fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,


								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
								,
								IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
								IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death'
								and fu_ajk_cn.policy_liability='NONLIABLE'
								and fu_ajk_cn.confirm_claim !='Pending'
								and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."

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
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_cn.total_claim,
								fu_ajk_peserta.kredit_tgl,
								fu_ajk_klaim.tgl_document_lengkap,
								if(`id_klaim_status`=6,'Ditolak',
								if(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'Dokumen Sudah Lengkap',
								'Dokumen Belum Lengkap'))  AS keterangan,
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

								fu_ajk_cn.total_bayar_asuransi-fu_ajk_cn.total_claim as selisih,

								if(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00','',fu_ajk_klaim.tgl_lapor_klaim) as tgl_lapor_klaim,
								if(fu_ajk_peserta.type_data='SPK',fu_ajk_peserta.kredit_tenor*12,fu_ajk_peserta.kredit_tenor) as kredit_tenor,
								fu_ajk_klaim.tgl_klaim as dol,
								datediff(fu_ajk_peserta.kredit_tgl,fu_ajk_klaim.tgl_klaim) as akad_dol,
								if(fu_ajk_cn.tgl_byr_claim<>'',fu_ajk_cn.total_claim,'')  as bayar_ke_bank,
								fu_ajk_cn.tgl_byr_claim as tgl_bayar_ke_client,

								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
								FROM
								fu_ajk_cn
								INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
								INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta

								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT join fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death'
								and fu_ajk_cn.policy_liability='NONLIABLE'
								and fu_ajk_cn.confirm_claim !='Pending'
								and fu_ajk_cn.id_cost=".$_REQUEST['id_cost']." ".$q1." ".$q2." ".$q3." ".$q4." ".$q5."

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
									<td>'.number_format($datanya_['tuntutan_klaim'],2).'</td>
									<td>'.$datanya_['persentase_tiering'].'</td>
									<td>'.number_format($datanya_['nilai_tiering'],2).'</td>
									<td>'.$datanya_['kredit_tgl'].'</td>
									<td>'.number_format($datanya_['kredit_tenor']).'</td>
									<td>'.$datanya_['dol'].'</td>
									<td>'.$datanya_['akad_dol'].'</td>
									<td>'.$datanya_['tgl_lapor_klaim'].'</td>
									<td>'.$datanya_['keterangan'].'</td>
									<td>'.$datanya_['tgl_document_lengkap'].'</td>
									<td>'.$datanya_['status_klaim'].'</td>
									<td>'.number_format($datanya_['asuransi_bayar'],2).'</td>
									<td>'.$datanya_['tgl_asuransi_bayar'].'</td>
									<td>'.number_format($datanya_['bayar_ke_bank'],2).'</td>
									<td>'.$datanya_['tgl_bayar_ke_client'].'</td>
									<td>'.number_format($datanya_['selisih'],2).'</td>
									<td>'.$datanya_['kol'].'</td>
									</tr>';
									$no++;
				}
				echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=tieringasuransi&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&kol='.$_REQUEST['kol'].'&status_klaim='.$_REQUEST['status_klaim'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&tgl3=' . $_REQUEST ['tglcheck3'] . '&tgl4=' . $_REQUEST ['tglcheck4'] . '&tgl5=' . $_REQUEST ['tglcheck5'] . '&tgl6=' . $_REQUEST ['tglcheck6'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
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
								IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'DOKUMEN SUDAH LENGKAP',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'DITOLAK',
								'DOKUMEN BELUM LENGKAP')) AS keterangan,
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

								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')

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
								IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'DOKUMEN SUDAH LENGKAP',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'DITOLAK',
								'DOKUMEN BELUM LENGKAP')) AS keterangan,
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

								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')

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


								IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol

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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')

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


IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol

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
								and (DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK')

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
						/*fu_ajk_klaim.tgl_document as tgl_terima_laporan,*/
						DATE(fu_ajk_cn.approve_date) AS tgl_terima_laporan,
						DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) as lama_terima_laporan,
						'' as tgl_update_klaim,
						fu_ajk_klaim.tgl_lapor_klaim as tgl_lapor_asuransi,
						fu_ajk_cn.keterangan as kelengkapan_dokumen,
						fu_ajk_klaim.tgl_document_lengkap as tgl_status_lengkap,
						if(fu_ajk_peserta.type_data='SPK',fu_ajk_klaim.tgl_document_lengkap+28,fu_ajk_klaim.tgl_document_lengkap+14) as due_date,
						fu_ajk_klaim.tgl_kirim_dokumen,
						CURRENT_DATE() as today,
						DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen) as status_release,
						/*fu_ajk_spak.ext_premi*/ '' as EM,
						/*fu_ajk_spak.ket_ext*/ '' as keterangan_EM,
						fu_ajk_klaim.tgl_investigasi,
						fu_ajk_klaim.diagnosa as hasil_investigasi,
						fu_ajk_namapenyakit.namapenyakit as penyebab_meinggal,
						if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK','NOT LIABLE','LIABLE') as polis_liability,
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
						IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'DOKUMEN SUDAH LENGKAP',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'DITOLAK',
								'DOKUMEN BELUM LENGKAP')) AS keterangan,
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

IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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
						/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
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
							DATE(fu_ajk_cn.approve_date) as tgl_terima_laporan,
							DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_document) as lama_terima_laporan,
							'' as tgl_update_klaim,
							fu_ajk_klaim.tgl_lapor_klaim as tgl_lapor_asuransi,
							fu_ajk_cn.keterangan as kelengkapan_dokumen,
							fu_ajk_klaim.tgl_document_lengkap as tgl_status_lengkap,
							if(fu_ajk_peserta.type_data='SPK',fu_ajk_klaim.tgl_document_lengkap+28,fu_ajk_klaim.tgl_document_lengkap+14) as due_date,
							fu_ajk_klaim.tgl_kirim_dokumen,
							CURRENT_DATE() as today,
							DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_kirim_dokumen) as status_release,
							/*fu_ajk_spak.ext_premi*/ '' as EM,
							/*fu_ajk_spak.ket_ext*/ '' as keterangan_EM,
							fu_ajk_klaim.tgl_investigasi,
							fu_ajk_klaim.diagnosa as hasil_investigasi,
							fu_ajk_namapenyakit.namapenyakit as penyebab_meinggal,
							if(DATEDIFF(fu_ajk_klaim.tgl_klaim,fu_ajk_peserta.kredit_tgl)<365 and fu_ajk_polis.typeproduk='NON SPK','NOT LIABLE','LIABLE') as polis_liability,
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
							IF(
								IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NOT NULL,'DOKUMEN SUDAH LENGKAP',
								IF(IF( fu_ajk_klaim.tgl_document_lengkap =  '0000-00-00', NULL , fu_ajk_klaim.tgl_document_lengkap) IS NULL AND `id_klaim_status`=6,'DITOLAK',
								'DOKUMEN BELUM LENGKAP')) AS keterangan,
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

IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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
							/*LEFT JOIN fu_ajk_spak ON fu_ajk_peserta.spaj=fu_ajk_spak.spak*/
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
	  <tr><td align="right">Format Report</td>
		<td id="polis_rate">:
	  	<select name="format_report">
	  		<option value="1"'._selected($_REQUEST['format_report'], "1").'>Internal Report</option>
	  		<option value="2"'._selected($_REQUEST['format_report'], "2").'>Bank Report</option>
	  		<option value="3"'._selected($_REQUEST['format_report'], "3").'>Asuransi Report</option>
	  	</select></td></tr>
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
					$tgl_lapor=' and fu_ajk_cn.approve_date between "'.$_REQUEST['tglcheck1'].'" and "'.$_REQUEST['tglcheck2'].'"';
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
					'Dokumen Belum Lengkap')) ='".$_REQUEST['status_klaim']."'";
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
						CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
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
						DATE(fu_ajk_cn.approve_date) AS tgl_terima_laporan,
						DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim) AS lama_terima_laporan,
						current_date AS tgl_update_klaim,
						IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
						fu_ajk_cn.keterangan AS kelengkapan_dokumen,
						IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
						IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
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
						'Dokumen Belum Lengkap'))  AS status_dokumen,
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
						IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 AND DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol,
						(SELECT count(*)
						FROM fu_ajk_peserta pes
						INNER JOIN fu_ajk_cn cn
						ON cn.id = pes.id_klaim
						WHERE cn.type_refund = 'Topup' and
									pes.nama = fu_ajk_peserta.nama and
									pes.tgl_lahir = fu_ajk_peserta.tgl_lahir and
									pes.cabang = fu_ajk_peserta.cabang)AS topup
						FROM
						fu_ajk_cn
						INNER JOIN fu_ajk_dn ON fu_ajk_cn.id_cost = fu_ajk_dn.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_dn.id_nopol AND fu_ajk_cn.id_dn = fu_ajk_dn.id
						INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_cost = fu_ajk_peserta.id_cost AND fu_ajk_cn.id_nopol = fu_ajk_peserta.id_polis AND fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
						INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
						INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
						INNER JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
						LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
						INNER JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
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
						CONCAT(DATE_FORMAT(fu_ajk_cn.approve_date,'%Y%m'),'/',fu_ajk_klaim.id) AS klaim_id,
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
						DATE(fu_ajk_cn.approve_date) AS tgl_terima_laporan,
						DATEDIFF(fu_ajk_cn.approve_date,fu_ajk_klaim.tgl_klaim) AS lama_terima_laporan,
						'' AS tgl_update_klaim,
						IF(fu_ajk_klaim.tgl_lapor_klaim='0000-00-00',NULL,fu_ajk_klaim.tgl_lapor_klaim) AS tgl_lapor_asuransi,
						fu_ajk_cn.keterangan AS kelengkapan_dokumen,
						IF(fu_ajk_klaim.tgl_document_lengkap='0000-00-00',NULL,fu_ajk_klaim.tgl_document_lengkap) AS tgl_status_lengkap,
						IF(fu_ajk_polis.`typeproduk`='NON SPK',DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 28 DAY),DATE_ADD(fu_ajk_klaim.tgl_document_lengkap,INTERVAL 14 DAY)) as due_date,
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
						'Dokumen Belum Lengkap'))  AS status_dokumen,
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
						INNER JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn=fu_ajk_cn.id
						LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id=fu_ajk_klaim.id_klaim_status
						INNER JOIN fu_ajk_grupproduk ON fu_ajk_grupproduk.id=fu_ajk_peserta.nama_mitra
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
				if($_REQUEST['format_report']=='1'){

				echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
					<tr><td bgcolor="#FFF" colspan="2"><a href="e_report_masterklaim.php?er=klaim_outstanding&
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
							format_report='.$_REQUEST['format_report'].'"><img src="image/excel.png" width="25" border="0"><br />Print List data (*.xls)</a>
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
							format_report='.$_REQUEST['format_report'].'"><img src="image/pdf.png" width="25" border="0"><br />Print List data (*.pdf)</a></td>
							<td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=summary_status_pengajuan&
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
							format_report='.$_REQUEST['format_report'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Summary Status Klaim Berdasarkan Pengajuan Klaim(*.pdf)</a>
							</td>
							<td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=summary_status_kol&
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
							format_report='.$_REQUEST['format_report'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Summary Status Klaim Berdasarkan Kol(*.pdf)</a>
							</td>
							<td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=summary_status_asuransi&
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
							format_report='.$_REQUEST['format_report'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Summary Status Klaim Berdasarkan Asuransi(*.pdf)</a>
							</td>
							<td colspan="2" bgcolor="#FFF">
							<a target="_blank" href="e_report_klaim.php?er=summary_status_liability&
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
							format_report='.$_REQUEST['format_report'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Summary Status Klaim Berdasarkan Policy Liability(*.pdf)</a>
							</td>
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
					echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=klaimoutstanding&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&
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
				}elseif ($_REQUEST['format_report']=='2'){

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
							format_report='.$_REQUEST['format_report'].'"><img src="image/excel.png" width="25" border="0"><br />Print List data (*.xls)</a>
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
							format_report='.$_REQUEST['format_report'].'
									"><img src="image/pdf.png" width="25" border="0"><br />Print List data (*.pdf)</a>
							</td><td colspan="40" bgcolor="#FFF"></td></tr>
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

					echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=klaimoutstanding&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&
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
				}elseif($_REQUEST['format_report']=='3'){

					echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
					<tr><td bgcolor="#FFF" colspan="2" ><a href="e_report.php?er=klaim_outstanding&
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
							format_report='.$_REQUEST['format_report'].'"><img src="image/excel.png" width="25" border="0"><br />Print List data (*.xls)</a>
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
							format_report='.$_REQUEST['format_report'].'
									"><img src="image/pdf.png" width="25" border="0"><br />Print List data (*.pdf)</a>
							</td><td colspan="40" bgcolor="#FFF"></td></tr>
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
					<td>Asuransi Bayar</td>
					<td>Ref. Pemb Dari Asuransi</td>
					<td>Tgl Bayar dari Asuransi</td>
					<td>Pengajuan Keuangan</td>
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
						<td>'.number_format($datanya_['total_bayar_asuransi'],2).'</td>
						<td>'.$datanya_['ref_bayar_asuransi'].'</td>
						<td>'.$datanya_['tgl_bayar_asuransi'].'</td>
						<td>'.number_format($datanya_['nilai_pengajuan_keuangan'],2).'</td>
						<td>'.$datanya_['kol'].'</td>
						</tr>';
						$no++;
					}

					echo createPageNavigations($file = 'ajk_re_masterklaim.php?sh=klaimoutstanding&re=dataklaim&id_cost='.$_REQUEST['id_cost'].'&
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

IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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

IF(DATE(fu_ajk_cn.tgl_byr_claim) IS NULL or fu_ajk_cn.tgl_byr_claim='0000-00-00',

			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(CURRENT_DATE(),fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))
			,
			IF(DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=90,'2',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>90 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=120,'3',
			IF(DATEDIFF(DATE(fu_ajk_cn.tgl_byr_claim),fu_ajk_klaim.tgl_klaim)>120 and DATEDIFF(fu_ajk_cn.tgl_byr_claim,fu_ajk_klaim.tgl_klaim)<=180 ,'4','5')))) AS kol
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
		<tr><td width="40%" align="right">Nama Asuransi <font color="red">*</font></td><td> : <select name="id_asuransi">
		  	<option value="all">---Semua Asuransi---</option>';
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_asuransi'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td align="right">Status Klaim</td>
		<td id="polis_rate">: <select name="status_klaim">
		<option value="">-- Pilih Status Klaim--</option>';
		$metcost1 = $database->doQuery('SELECT * FROM fu_ajk_klaim_status ORDER BY order_list DESC');
		while($metcost_ = mysql_fetch_array($metcost1)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['status_klaim'], $metcost_['id']).'>'.$metcost_['status_klaim'].'</option>';
		}

		echo '</select></td></tr>
		<tr><td align="right">Kol</td>
		<td id="polis_rate">: <select name="kol">
				<option value="">-- Pilih Kol--</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
		</select></td></tr>

				<tr><td align="right">Tanggal Create CN</td>
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
					<tr><td bgcolor="#FFF"colspan="24"><a target="_blank" href="e_report_klaim.php?er=summary_klaim_kol&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&status_klaim='.$_REQUEST['status_klaim'].'&kol='.$_REQUEST['kol'].'&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/pdf.png" width="25" border="0"><br />PDF</a></td></tr>
					<td>No</td>
					<td>Perusahaan</td>
					<td>Asuransi</td>
					<td>Kategori</td>
					<td>Kol</td>
					<td>Status Klaim</td>
					<td>Debitur</td>
					<td>Plafond</td>
					<td>Tuntutan Klaim</td>
					<td>Penerimaan Asuransi</td>
					<td>Bayar Ke Bank</td>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

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


				if(empty($_REQUEST['status_klaim'])){
					$status_klaim="";
				}else{
					$status_klaim=" and fu_ajk_klaim_status.id=".$_REQUEST['status_klaim'];
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

				$sqlKlaim = $database->doQuery("select
								aa.status_klaim,
								aa.`name`,
								aa.nama_asuransi,
								aa.nmproduk,
								aa.kol,
								count(aa.id) as jml,
								sum(aa.tuntutan_klaim) as tuntutan_klaim,
								sum(aa.kredit_jumlah) as plafond,
								sum(aa.total_bayar_asuransi) as asuransi_bayar,
								sum(aa.total_claim) as nilai_klaim
								from (
								SELECT
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_costumer.`name`,
								fu_ajk_asuransi.name as nama_asuransi,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.id,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_bayar_asuransi,
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
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death'  and fu_ajk_cn.del is null
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."  ".$asuransi." ".$polis." ".$status_klaim." ".$kol."
								and fu_ajk_cn.tgl_createcn between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'
								) aa GROUP BY
								aa.status_klaim,
								aa.`name`,
								aa.nama_asuransi,
								aa.nmproduk,
								aa.kol
								order by
								aa.nama_asuransi,
								aa.`name`,
								aa.nmproduk,
								aa.kol,
								aa.status_klaim
				 				LIMIT ". $m ." , 25");

				$sqlKlaim1 = $database->doQuery("select
								aa.status_klaim,
								aa.`name`,
								aa.nama_asuransi,
								aa.nmproduk,
								aa.kol,
								count(aa.id) as jml,

								sum(aa.tuntutan_klaim) as tuntutan_klaim,
								sum(aa.kredit_jumlah) as plafond,
								sum(aa.total_bayar_asuransi) as asuransi_bayar,
								sum(aa.total_claim) as nilai_klaim


								from (
								SELECT
								fu_ajk_klaim_status.status_klaim,
								fu_ajk_costumer.`name`,
								fu_ajk_asuransi.name as nama_asuransi,
								fu_ajk_polis.nmproduk,
								fu_ajk_peserta.id,
								fu_ajk_cn.tuntutan_klaim,
								fu_ajk_peserta.kredit_jumlah,
								fu_ajk_cn.total_bayar_asuransi,
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
								INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
								INNER JOIN fu_ajk_costumer ON fu_ajk_peserta.id_cost = fu_ajk_costumer.id
								INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
								LEFT JOIN fu_ajk_klaim ON fu_ajk_klaim.id_cn = fu_ajk_cn.id
								LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim_status.id = fu_ajk_klaim.id_klaim_status
								where fu_ajk_cn.type_claim='Death' and fu_ajk_cn.del is null
								and fu_ajk_peserta.id_cost=".$_REQUEST['id_cost']."  ".$asuransi." ".$polis." ".$status_klaim." ".$kol."
								and fu_ajk_cn.tgl_createcn between '".$_REQUEST ['tglcheck1']."' and '".$_REQUEST ['tglcheck2']."'
								) aa GROUP BY
								aa.status_klaim,
								aa.`name`,
								aa.nama_asuransi,
								aa.nmproduk,
								aa.kol
								order by
								aa.nama_asuransi,
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
					<td>'.$datanya_['nama_asuransi'].'</td>
					<td>'.$datanya_['nmproduk'].'</td>
					<td>'.$datanya_['kol'].'</td>
					<td>'.$datanya_['status_klaim'].'</td>
					<td>'.$datanya_['jml'].'</td>
					<td>'.duit($datanya_['plafond']).'</td>
					<td>'.duit($datanya_['tuntutan_klaim']).'</td>
					<td>'.duit($datanya_['asuransi_bayar']).'</td>
					<td>'.duit($datanya_['nilai_klaim']).'</td>
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

case "klaim_share1" :
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
			  <tr><td width="40%" align="right">Tahun DOL<font color="red">*</font></td><td> : <select name="y_uw" id="y_uw">
				<option value="">---Pilih tahun DOL---</option>';
					for($x=2012;$x<=date('Y');$x++){
						echo  '<option value="'.$x.'"'._selected($_REQUEST['y_uw'], $x).'>'.$x.' s.d '.($x+2).'</option>';
					}

					echo '</select></td></tr>
				<tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataklaim_share1"><input type="submit" name="ere" id="btncari" value="Show"></td></tr>
			  </table>
			  </form> ';
			if($_REQUEST['re']=="dataklaim_share1"){
				header("location:e_report_klaim.php?er=klaim_share1&id_cost=".$_REQUEST['id_cost']."&prod=".$_REQUEST['prod']."&y_uw=".$_REQUEST['y_uw']);
			}
			break;
	default:
		;
} // switch

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
