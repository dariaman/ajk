<?php
	include_once ("../includes/fu6106.php");
	$func = $_REQUEST['fu'];

	switch ($func) {
		case 'member':
			$idpeserta = $_REQUEST['id'];			
			$query = "
			SELECT fu_ajk_peserta.id_polis,
							 type_data,
							 nama,
							 spaj,
							 gender,
							 tgl_lahir,
							 usia,
							 no_ktp,
							 pekerjaan,
							 kredit_tgl,
							 kredit_jumlah,
							 kredit_tenor,
							 kredit_akhir,
							 ratebank,
							 premi,
							 ext_premi,
							 cmp,
							 totalpremi,
							 status_medik,
							 status_bayar,
							 tgl_bayar,
							 status_aktif,
							 mppbln,
							 regional,
							 area,
							 cabang,
							 nopinjaman,
							 sumberdana,
							 tgl_laporan,
							 fu_ajk_peserta_as.id_polis_as,
							 fu_ajk_peserta_as.rateasuransi,
							 fu_ajk_peserta_as.id_asuransi,
							 fu_ajk_peserta_as.b_premi,
							 fu_ajk_peserta_as.b_extpremi,
							 fu_ajk_peserta_as.nettpremi	
				FROM fu_ajk_peserta 
				INNER JOIN fu_ajk_peserta_as on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
				WHERE fu_ajk_peserta.id_peserta= '".$idpeserta."'";
			
			$result = mysql_fetch_array(mysql_query($query));			
			$json[] = array('id_polis' => $result['id_polis'],
											'type_data' => $result['type_data'],
											'nama' => $result['nama'],
											'spaj' => $result['spaj'],
											 'gender' => $result['gender'],
											 'tgl_lahir' => $result['tgl_lahir'],
											 'usia' => $result['usia'],
											 'no_ktp'=> $result['no_ktp'],
											 'pekerjaan' => $result['pekerjaan'],
											 'kredit_tgl' => $result['kredit_tgl'],
											 'kredit_jumlah' => $result['kredit_jumlah'],
											 'kredit_tenor' => $result['kredit_tenor'],
											 'kredit_akhir' => $result['kredit_akhir'],
											 'ratebank' => $result['ratebank'],
											 'premi' => $result['premi'],
											 'ext_premi' => $result['ext_premi'],
											 'cmp' => $result['cmp'],
											 'totalpremi' => $result['totalpremi'],
											 'status_medik' => $result['status_medik'],
											 'status_bayar' => $result['status_bayar'],
											 'tgl_bayar' => $result['tgl_bayar'],
											 'status_aktif' => $result['status_aktif'],
											 'mppbln' => $result['mppbln'],
											 'regional' => $result['regional'],
											 'area' => $result['area'],
											 'cabang' => $result['cabang'],
											 'nopinjaman' => $result['nopinjaman'],
											 'sumberdana' => $result['sumberdana'],
											 'tgl_laporan' => $result['tgl_laporan'],
											 'id_polis_as' => $result['id_polis_as'],
											 'rateasuransi' => $result['rateasuransi'],
											 'id_asuransi' => $result['id_asuransi'],
											 'b_premi' => $result['b_premi'],
											 'b_extpremi' => $result['b_extpremi'],
											 'nettpremi' => $result['nettpremi']);
			echo json_encode($json);
		break;
		case 'updatemember':
			$idpeserta = $_REQUEST['id'];
			$bank = $_REQUEST['bank'];
			$tgl = $_REQUEST['tgl'];
			$query = "UPDATE fu_ajk_peserta 
								SET status_aktif = 'Pindah',
										transfer_to='".$bank."',
										transfer_date='".$tgl."' 
								WHERE id_peserta = '".$idpeserta."' and 
											del is null";
			mysql_query($query);
			// echo $query;
		break;

		default:
			
		break;
	}
?>