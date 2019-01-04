<?php

	include_once ("ui.php");
	include_once ("../includes/functions.php");
	connect();
	$today = date("Y-m-d G:i:s");

	if (isset($_SESSION['nm_user'])) {
    $q = mysql_fetch_array($database->doQuery("SELECT * FROM pengguna WHERE nm_user= '". $_SESSION['nm_user'] ."' "));    
	}

	//cek cabang
	$cabang = mysql_fetch_array(mysql_query("select * from fu_ajk_cabang where name = '".$q['cabang']."' and del is NULL and id_cost = '".$q['id_cost']."'"));
	//cek central
	$cabangcentral = mysql_query("select * from fu_ajk_cabang where centralcbg = '".$cabang['id']."'");

	if(mysql_num_rows($cabangcentral) > 0){
		$qcabang = " (fu_ajk_peserta.cabang in (select name from fu_ajk_cabang where centralcbg = '".$cabang[id]."') or fu_ajk_peserta.cabang = '".$q['cabang']."')";
	}else{
		$qcabang = " fu_ajk_peserta.cabang = '".$q['cabang']."'";
	}


	$q_outstanding = $database->doQuery("SELECT fu_ajk_peserta.id_peserta,
																							fu_ajk_dn.dn_kode,
																							fu_ajk_peserta.nama,
																							fu_ajk_peserta.cabang,
																							fu_ajk_peserta.kredit_jumlah,
																					 		DATE_FORMAT(fu_ajk_peserta.kredit_tgl,'%d-%m-%Y') as kredit_tgl,
																					 		DATE_FORMAT(fu_ajk_peserta.kredit_akhir,'%d-%m-%Y') as kredit_akhir,
																							fu_ajk_peserta.totalpremi,
																							ifnull(fu_ajk_peserta.nilai_bayar,0)as nilai_bayar,
																							ifnull(fu_ajk_peserta.totalpremi,0) - ifnull(fu_ajk_peserta.nilai_bayar,0) as selisih
																				FROM fu_ajk_peserta
																						 INNER JOIN fu_ajk_dn
																						 ON fu_ajk_dn.id = fu_ajk_peserta.id_dn
																				WHERE fu_ajk_peserta.status_bayar != 1 and 
																							fu_ajk_peserta.del is NULL and 
																							fu_ajk_peserta.status_aktif = 'Inforce' and
																							".$qcabang);
																								
	echo 		'<br>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<th width="95%" align="left">Outstanding Premi</font></th>
							</tr>
							<tr>						
								<td><a href="aajk_report.php?er=outstandingpremi&cb='.$q['nm_user'].'"><img src="image/dninvoice1.jpg" width="22" border="0"><br>PDF</a></td>
							</tr>
						</table>';		
	echo		'<br>
					<table border="1" cellpadding="5" cellspacing="0" width="100%" >
						<tr>
							<td align="center" width="2%" bgcolor="#bde0e6">No</td>
							<td align="center" width="7%" bgcolor="#bde0e6">ID Peserta</td>
							<td align="center" width="5%" bgcolor="#bde0e6">No. DN</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Nama Peserta</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Cabang</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Plafond</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Tgl Akad</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Tgl Akhir</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Total Premi</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Nilai Bayar</td>
							<td align="center" width="10%" bgcolor="#bde0e6">Nilai Outstanding</td>
						</tr>';
						$no = 1;
						$sumpremi = 0;
						while($q_outstanding_r = mysql_fetch_array($q_outstanding)){
			echo '<tr>
			 			 		<td align="center">'.$no.'</td>
			 			 		<td align="center">'.$q_outstanding_r['id_peserta'].'</td>
			 			 		<td align="center">'.$q_outstanding_r['dn_kode'].'</td>
			 			 		<td align="center">'.$q_outstanding_r['nama'].'</td>
			 			 		<td align="center">'.$q_outstanding_r['cabang'].'</td>
			 			 		<td align="center">'.duit($q_outstanding_r['kredit_jumlah']).'</td>
			 			 		<td align="center">'.$q_outstanding_r['kredit_tgl'].'</td>
			 			 		<td align="center">'.$q_outstanding_r['kredit_akhir'].'</td>
			 			 		<td align="center">'.duit($q_outstanding_r['totalpremi']).'</td>
			 			 		<td align="center">'.duit($q_outstanding_r['nilai_bayar']).'</td>
			 			 		<td align="center">'.duit($q_outstanding_r['selisih']).'</td>
			 			</tr>';
			 			 $sumpremi = $sumpremi + $q_outstanding_r['totalpremi'];
			 			 $sumbayar = $sumbayar + $q_outstanding_r['nilai_bayar'];
			 			 $sumselisih = $sumselisih + $q_outstanding_r['selisih'];
			 			 $no++;
						}
			echo '<tr>
							<td colspan="8"><b>Total</td> 
							<td align="center"><b>'.duit($sumpremi).'</td> 
							<td align="center"><b>'.duit($sumbayar).'</td> 
							<td align="center"><b>'.duit($sumselisih).'</td> 
						</tr>
					</table>';
?>