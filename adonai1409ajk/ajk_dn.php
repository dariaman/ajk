<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once("ui.php");
include_once("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {
    $q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
}
switch ($_REQUEST['r']) {
    case "createdn":
        $cek = $database->doQuery('SELECT id_cost, id_polis, type_data, nama_mitra, status_aktif, status_medik, Sum(totalpremi) AS tpremi, namafile, input_time, regional, area, cabang,usia
									FROM fu_ajk_peserta WHERE id_cost="'.$_REQUEST['id_cost'].'" AND id_polis="'.$_REQUEST['id_polis'].'" AND input_by="'.$_REQUEST['idu'].'" AND input_time="'.$_REQUEST['idt'].'" AND id_dn="" AND status_aktif="Approve" AND del IS NULL
								    GROUP BY nama_mitra, regional, cabang, namafile, input_time');

        while ($cek2 = mysql_fetch_array($cek)) {
            $fakcek = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn ORDER BY id DESC'));
            $fakcekdn =$fakcek['id_dn'] + 1;
            $tglnya = explode("/", $futgldn);
            $thnnya = substr($tglnya[2], 2);
            $idkode = 10000000000 + $fakcekdn;
            $idkode2 = substr($idkode, 1);	// ID PESERTA //
            $kode = 'ADN'.$thnnya.''.$tglnya[1].''.$idkode2;

            $cekpolis_as = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis_as WHERE id_cost="'.$cek2['id_cost'].'" AND id_as="'.$_REQUEST['ids'].'" AND nmproduk="'.$cek2['id_polis'].'" '));

            $Rdn = $database->doQuery('INSERT INTO fu_ajk_dn SET id_cost="'.$cek2['id_cost'].'",
														   id_nopol="'.$cek2['id_polis'].'",
														   id_polis_as="'.$cekpolis_as['id'].'",
														   id_as="'.$_REQUEST['ids'].'",
														   id_regional="'.$cek2['regional'].'",
														   id_area="'.$cek2['area'].'",
														   id_cabang="'.$cek2['cabang'].'",
														   id_dn="'.$fakcekdn.'",
														   type_data="'.$cek2['type_data'].'",
														   dn_kode="'.$kode.'",
														   totalpremi="'.$cek2['tpremi'].'",
														   tgl_createdn="'.$datelog.'",
														   tgltransaksi="'.$datelog.'",
														   dn_status="unpaid",
														   validasi_uw="ya",
														   namafile="'.$cek2['namafile'].'",
														   input_by="'.$_SESSION['nm_user'].'",
														   input_time="'.$futgl.'"');
            
            $Rdn__ = mysql_fetch_array($database->doQuery('SELECT id,dn_kode FROM fu_ajk_dn ORDER BY id DESC'));


            $rpremi = $database->doQuery('INSERT INTO fu_ajk_note_as SET id_dn="' . $Rdn__['id'] . '",
																	 note_type="CNP",
			 														 note_date="' . $datelog . '",
			 														 note_desc="Pembayaran DN Premi nomor '.$kode.' dengan Nilai premi '.$cek2['tpremi'].'",
		                											 note_curr="IDR",
		                											 note_subtotal="'.$cek2['tpremi'].'",
		                											 note_other_fee="0",
		                											 note_total="'.$cek2['tpremi'].'",
																	 entry_by="' . $_SESSION['nm_user'] . '",
																	 entry_time="' . $futgl . '" ');

            $cnpremi_ = mysql_fetch_array($database->doQuery('SELECT id FROM fu_ajk_note_as ORDER BY id DESC'));
            
            $metDNpeserta = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="'.$Rdn__['id'].'", status_aktif="Inforce" ,tgl_laporan="'.$datelog.'" WHERE id_dn="" AND namafile="'.$cek2['namafile'].'" AND id_cost="'.$cek2['id_cost'].'" AND id_polis="'.$cek2['id_polis'].'" AND cabang="'.$cek2['cabang'].'" AND status_aktif="Approve" AND input_by="'.$_REQUEST['idu'].'" AND input_time="'.$_REQUEST['idt'].'" AND regional="'.$cek2['regional'].'" AND cabang="'.$cek2['cabang'].'" AND nama_mitra="'.$cek2['nama_mitra'].'" AND del IS NULL ');

            $metasuransi = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$cek2['id_cost'].'" AND id_polis="'.$cek2['id_polis'].'" AND id_dn="'.$Rdn__['id'].'" AND namafile="'.$cek2['namafile'].'" AND status_aktif="Inforce"');
            $xcounter=1;
            $xpremi_asuransi=0;
            while ($metasuransi_ = mysql_fetch_array($metasuransi)) {
                //CEK RATE MPP START DAN AKHIR
                $cekpolisproduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$metasuransi_['id_cost'].'" AND id="'.$metasuransi_['id_polis'].'"'));
                //CEK RATE MPP START DAN AKHIR

                //20170719 DELETE PESERTA TEMPF
                $database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE spaj="'.$metasuransi_['spaj'].'" AND status_aktif="Upload"');
                $metUpdateSPK = $database->doQuery('UPDATE fu_ajk_spak SET status="Realisasi" WHERE spak="'.$metasuransi_['spaj'].'" AND status="Aktif" AND del IS NULL');

                if ($cekpolisproduk['typeproduk']=="SPK") {
                    
                    if ($cekpolisproduk['mpptype']=="Y") {
                        if ($metasuransi_['spaj'] != "") {
                           
                            if ($metasuransi_['danatalangan']=="1") {
                                if ($metasuransi_['kredit_tenor'] <= 12) {
                                    $met_asuransi_tenor = 1;
                                } elseif ($metasuransi_['kredit_tenor'] >= 25) {
                                    $met_asuransi_tenor = 3;
                                } else {
                                    $met_asuransi_tenor = 2;
                                }
                            } else {
                                $met_asuransi_tenor = $metasuransi_['kredit_tenor'];
                            }
                        } else {
                            $met_asuransi_tenor = $metasuransi_['kredit_tenor'];
                        }                        
                        $query = 'SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$metasuransi_['id_cost'].'" AND id_polis="'.$metasuransi_['id_polis'].'" AND id_as="'.$_REQUEST['ids'].'" AND id_polis_as="'.$cekpolis_as['id'].'" AND tenor="'.$met_asuransi_tenor.'" AND '.$metasuransi_['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL';
                        $bermasalah = 'SPK MPP '.$metasuransi_['id_peserta'];
                        $met_rate_asuransi = mysql_fetch_array($database->doQuery($query));		// RATE PREMI
                    } else {
                        $query = 'SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$cek2['id_cost'].'" AND id_polis="'.$cek2['id_polis'].'" AND id_as="'.$_REQUEST['ids'].'" AND id_polis_as="'.$cekpolis_as['id'].'" AND usia="'.$metasuransi_['usia'].'" AND tenor="'.$metasuransi_['kredit_tenor'].'" AND status="baru" AND del IS NULL';
                        $bermasalah = 'SPK NOT MPP '.$metasuransi_['id_peserta'];
                        $met_rate_asuransi = mysql_fetch_array($database->doQuery($query));
                    }
                } else {
                    if ($cekpolisproduk['mpptype']=="Y") {
                        if ($metasuransi_['spaj'] != "") {
                           
                            if ($metasuransi_['danatalangan']=="1") {
                                if ($metasuransi_['kredit_tenor'] <= 12) {
                                    $met_asuransi_tenor = 1;
                                } elseif ($metasuransi_['kredit_tenor'] >= 25) {
                                    $met_asuransi_tenor = 3;
                                } else {
                                    $met_asuransi_tenor = 2;
                                }
                            } else {
                                $met_asuransi_tenor = $metasuransi_['kredit_tenor'] / 12;
                            }
                        } else {
                            $met_asuransi_tenor = $metasuransi_['kredit_tenor'] / 12;
                        }
                       
                        $query = 'SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$cek2['id_cost'].'" AND id_polis="'.$cek2['id_polis'].'" AND id_as="'.$_REQUEST['ids'].'" AND id_polis_as="'.$cekpolis_as['id'].'" AND tenor="'.$met_asuransi_tenor.'" AND '.$metasuransi_['mppbln'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL ';
                        $bermasalah = 'SPK MPP '.$metasuransi_['id_peserta'];
                        $met_rate_asuransi = mysql_fetch_array($database->doQuery($query));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
                    } else {
                        if ($cek2['id_polis'] == 19) {
                            $met_rate_asuransi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$cek2['id_cost'].'" AND id_polis="'.$cek2['id_polis'].'" AND id_as="'.$_REQUEST['ids'].'" AND id_polis_as="'.$cekpolis_as['id'].'" AND tenor="'.$metasuransi_['kredit_tenor'].'" and usia = "'.$cek2['usia'].'" AND status="baru" AND del IS NULL'));
                        } else {
                            $met_rate_asuransi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$cek2['id_cost'].'" AND id_polis="'.$cek2['id_polis'].'" AND id_as="'.$_REQUEST['ids'].'" AND id_polis_as="'.$cekpolis_as['id'].'" AND tenor="'.$metasuransi_['kredit_tenor'].'" AND status="baru" AND del IS NULL'));
                        }
                        $bermasalah = 'PERCEPATAN NOT MPP '.$metasuransi_['id_peserta'];
                    }
                }
                
                $met_premi_as = ROUND($metasuransi_['kredit_jumlah'] * $met_rate_asuransi['rate'] / 1000);	//PREMI ASURANSI
                $asuransi_disc = $met_premi_as * ($cekpolis_as['discount'] / 100);					//DISCOUNT POLIS ASURANSI
                $asuransi_ppn  = $met_premi_as * ($cekpolis_as['ppn'] / 100);						//PPN POLIS ASURANSI
                $asuransi_pph  = $met_premi_as * ($cekpolis_as['pph23'] / 100);						//PPH23 POLIS ASURANSI

                if ($metasuransi_['ext_premi']=="") {
                    $premiAsEM_ = '0';
                } else {
                    $premiAsEM = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$metasuransi_['id_cost'].'" AND id_polis="'.$metasuransi_['id_polis'].'" AND spak="'.$metasuransi_['spaj'].'" AND status="Realisasi"'));
                    $premiAsEM_ = $met_premi_as * ($premiAsEM['ext_premi'] / 100);
                }
                $asuransi_nettpremi  = $met_premi_as - $asuransi_disc - $asuransi_ppn + $asuransi_pph + $premiAsEM_;		//NETT PREMI ASURANSI
                $__metasuransi = $database->doQuery('INSERT INTO fu_ajk_peserta_as SET id_bank="'.$metasuransi_['id_cost'].'",
																			   id_polis="'.$metasuransi_['id_polis'].'",
																			   id_asuransi="'.$cekpolis_as['id_as'].'",
																			   id_polis_as="'.$cekpolis_as['id'].'",
																			   id_dn="'.$Rdn__['id'].'",
																			   id_peserta="'.$metasuransi_['id_peserta'].'",
																			   rateasuransi="'.$met_rate_asuransi['rate'].'",
																			   b_premi="'.$met_premi_as.'",
																			   b_admin="'.$cekpolis_as['adminfee'].'",
																			   b_disc="'.$asuransi_disc.'",
																			   b_extpremi="'.$premiAsEM_.'",
																			   b_ppn="'.$asuransi_ppn.'",
																			   b_pph="'.$asuransi_pph.'",
																			   nettpremi="'.$asuransi_nettpremi.'",
																			   input_by="'.$_SESSION['nm_user'].'",
																			   input_date="'.$futgl.'"');

                $rpremi_detail = $database->doQuery('INSERT INTO fu_ajk_note_as_detail SET id_note="' . $cnpremi_['id'] . '",
																	 id_peserta="'. $metasuransi_['id_peserta'] .'",
																	 note_type="CNP",
			 														 note_date="' . $datelog . '",
			 														 note_desc="Pembayaran DN Premi a/n '.$metasuransi_['nama'].' dengan Nilai premi '.$met_premi_as.'",
		                											 note_curr="IDR",
		                											 note_subtotal="'.$asuransi_nettpremi.'",
		                											 note_other_fee="0",
		                											 note_total="'.$asuransi_nettpremi.'",
																	 entry_by="' . $_SESSION['nm_user'] . '",
																	 entry_time="' . $futgl . '" ');

                $qtransactiondn = mysql_fetch_array($database->doQuery("SELECT 'AR-01' as TransactionCode,
																																		fu_ajk_dn.tgl_createdn,
																																		'A' as Status,
																																		fu_ajk_dn.dn_kode,
																																		fu_ajk_grupproduk.nmproduk,
																																		fu_ajk_grupproduk.nm_mitra,
																																		fu_ajk_asuransi.code,
																																		fu_ajk_asuransi.name,
																																		fu_ajk_polis.nmproduk as Produk_nm,
																																		CONCAT(fu_ajk_peserta.status_aktif,' ',IFNULL(fu_ajk_peserta.status_peserta,''))as StatusPeserta,
																																		DATE_FORMAT(NOW(),'%Y-%m-%d') as DateStatus,
																																		'PRM' as CoreCode,
																																		'PRM' as BMaterialCode,
																																		fu_ajk_peserta.id_peserta,
																																		fu_ajk_peserta.nama,
																																		fu_ajk_peserta.cabang,
																																		fu_ajk_peserta.totalpremi,
																																		fu_ajk_peserta.tgl_lahir,
																																		fu_ajk_peserta.tgl_laporan,
																																		fu_ajk_peserta.kredit_tenor,
																																		fu_ajk_peserta.kredit_jumlah,
																																		'C' as Return_Status,
																																		'".$_SESSION['nm_user']."' as input_by,
																																		now() as input_date
																																FROM fu_ajk_peserta
																																		 INNER JOIN fu_ajk_peserta_as
																																		 on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
																																		 INNER JOIN fu_ajk_dn
																																		 on fu_ajk_dn.id = fu_ajk_peserta.id_dn
																																		 INNER JOIN fu_ajk_asuransi
																																		 on fu_ajk_asuransi.id = fu_ajk_peserta_as.id_asuransi
																																		 INNER JOIN fu_ajk_grupproduk
																																		 on fu_ajk_grupproduk.id = (CASE WHEN IFNULL(fu_ajk_peserta.nama_mitra,'') = '' or fu_ajk_peserta.nama_mitra = 0 THEN 1 ELSE fu_ajk_peserta.nama_mitra END)
																																		 INNER JOIN fu_ajk_polis
																																		 on fu_ajk_polis.id = fu_ajk_peserta.id_polis
																																WHERE fu_ajk_peserta.del is NULL and
																																			fu_ajk_peserta_as.del is NULL and
																																			fu_ajk_dn.del is null and
																																			fu_ajk_asuransi.del is NULL AND
																																			fu_ajk_grupproduk.del is NULL and
																																			fu_ajk_polis.del is NULL and
																																			fu_ajk_peserta.id_peserta = '".$metasuransi_['id_peserta']."'"));

                $qtransactioncn = mysql_fetch_array($database->doQuery("SELECT 'AP-01' as TransactionCode,
																																		fu_ajk_dn.tgl_createdn,
																																		'A' as Status,
																																		CONCAT('ACNA',MID(fu_ajk_dn.dn_kode,4,20)) as cn_kode,
																																		fu_ajk_grupproduk.nmproduk,
																																		fu_ajk_grupproduk.nm_mitra,
																																		fu_ajk_asuransi.code,
																																		fu_ajk_asuransi.name,
																																		fu_ajk_polis.nmproduk as Produk_nm,
																																		CONCAT(fu_ajk_peserta.status_aktif,' ',IFNULL(fu_ajk_peserta.status_peserta,''))as StatusPeserta,
																																		DATE_FORMAT(NOW(),'%Y-%m-%d') as DateStatus,
																																		'PRM' as CoreCode,
																																		'PRM-AS' as BMaterialCode,
																																		fu_ajk_peserta.id_peserta,
																																		fu_ajk_peserta.nama,
																																		fu_ajk_peserta.cabang,
																																		fu_ajk_peserta_as.nettpremi,
																																		fu_ajk_peserta.tgl_lahir,
																																		fu_ajk_peserta.tgl_laporan,
																																		fu_ajk_peserta.kredit_tenor,
																																		fu_ajk_peserta.kredit_jumlah,
																																		'C' as Return_Status,
																																		'".$_SESSION['nm_user']."' as input_by,
																																		now() as input_date
																															FROM fu_ajk_peserta
																																	 INNER JOIN fu_ajk_peserta_as
																																	 on fu_ajk_peserta_as.id_peserta = fu_ajk_peserta.id_peserta
																																	 INNER JOIN fu_ajk_dn
																																	 on fu_ajk_dn.id = fu_ajk_peserta.id_dn
																																	 INNER JOIN fu_ajk_asuransi
																																	 on fu_ajk_asuransi.id = fu_ajk_peserta_as.id_asuransi
																																	 INNER JOIN fu_ajk_grupproduk
																																	 on fu_ajk_grupproduk.id = (CASE WHEN IFNULL(fu_ajk_peserta.nama_mitra,'') = '' or fu_ajk_peserta.nama_mitra = 0 THEN 1 ELSE fu_ajk_peserta.nama_mitra END)
																																	 INNER JOIN fu_ajk_polis
																																	 on fu_ajk_polis.id = fu_ajk_peserta.id_polis
																															WHERE fu_ajk_peserta.del is NULL and
																																		fu_ajk_peserta_as.del is NULL and
																																		fu_ajk_dn.del is null and
																																		fu_ajk_asuransi.del is NULL AND
																																		fu_ajk_grupproduk.del is NULL and
																																		fu_ajk_polis.del is NULL and
																																		fu_ajk_peserta.id_peserta = '".$metasuransi_['id_peserta']."'"));

                $namadn = str_replace("'", "\\'", $qtransactiondn['nama']);

                $arap_transaksi_dn = $database->doQuery("INSERT INTO CMS_ArAp_Transaction
																						 SET 	fArAp_TransactionCode = '".$qtransactiondn['TransactionCode']."',
																									fArAp_TransactionDate = '".$qtransactiondn['tgl_createdn']."',
																									fArAp_Status = '".$qtransactiondn['Status']."',
																									fArAp_No = '".$qtransactiondn['dn_kode']."',
																									fArAp_Customer_Id = '".$qtransactiondn['nmproduk']."',
																									fArAp_Customer_Nm = '".$qtransactiondn['nm_mitra']."',
																									fArAp_Asuransi_Id = '".$qtransactiondn['code']."',
																									fArAp_Asuransi_Nm = '".$qtransactiondn['name']."',
																									fArAp_Produk_Nm = '".$qtransactiondn['Produk_nm']."',
																									fArAp_StatusPeserta = '".$qtransactiondn['StatusPeserta']."',
																									fArAp_DateStatus = '".$qtransactiondn['DateStatus']."',
																									fArAp_CoreCode = '".$qtransactiondn['CoreCode']."',
																									fArAp_BMaterialCode = '".$qtransactiondn['BMaterialCode']."',
																									fArAp_RefMemberID = '".$qtransactiondn['id_peserta']."',
																									fArAp_RefMemberNm = '".$namadn."',
																									fArAp_RefCabang = '".$qtransactiondn['cabang']."',
																									fArAp_RefAmount = '".$qtransactiondn['totalpremi']."',
																									fArAp_RefDOB = '".$qtransactiondn['tgl_lahir']."',
																									fArAp_AssDate = '".$qtransactiondn['tgl_laporan']."',
																									fArAp_RefTenor = '".$qtransactiondn['kredit_tenor']."',
																									fArAp_RefPlafond = '".$qtransactiondn['kredit_jumlah']."',
																									fArAp_Return_Status = '".$qtransactiondn['Return_Status']."',
																									fArAp_SourceDB = 'AJK',
																									input_by = '".$qtransactiondn['input_by']."',
																									input_date = '".$qtransactiondn['input_date']."'");

                $namacn = str_replace("'", "\\'", $qtransactioncn['nama']);
                $arap_transaksi_cn = $database->doQuery("INSERT INTO CMS_ArAp_Transaction
																						 SET 	fArAp_TransactionCode = '".$qtransactioncn['TransactionCode']."',
																									fArAp_TransactionDate = '".$qtransactioncn['tgl_createdn']."',
																									fArAp_Status = '".$qtransactioncn['Status']."',
																									fArAp_No = '".$qtransactioncn['cn_kode']."',
																									fArAp_Customer_Id = '".$qtransactioncn['nmproduk']."',
																									fArAp_Customer_Nm = '".$qtransactioncn['nm_mitra']."',
																									fArAp_Asuransi_Id = '".$qtransactioncn['code']."',
																									fArAp_Asuransi_Nm = '".$qtransactioncn['name']."',
																									fArAp_Produk_Nm = '".$qtransactioncn['Produk_nm']."',
																									fArAp_StatusPeserta = '".$qtransactioncn['StatusPeserta']."',
																									fArAp_DateStatus = '".$qtransactioncn['DateStatus']."',
																									fArAp_CoreCode = '".$qtransactioncn['CoreCode']."',
																									fArAp_BMaterialCode = '".$qtransactioncn['BMaterialCode']."',
																									fArAp_RefMemberID = '".$qtransactioncn['id_peserta']."',
																									fArAp_RefMemberNm = '".$namacn."',
																									fArAp_RefCabang = '".$qtransactioncn['cabang']."',
																									fArAp_RefAmount = '".$qtransactioncn['nettpremi']."',
																									fArAp_RefDOB = '".$qtransactioncn['tgl_lahir']."',
																									fArAp_AssDate = '".$qtransactioncn['tgl_laporan']."',
																									fArAp_RefTenor = '".$qtransactioncn['kredit_tenor']."',
																									fArAp_RefPlafond = '".$qtransactioncn['kredit_jumlah']."',
																									fArAp_Return_Status = '".$qtransactioncn['Return_Status']."',
																									fArAp_SourceDB = 'AJK',
																									input_by = '".$qtransactioncn['input_by']."',
																									input_date = '".$qtransactioncn['input_date']."'");

                $xpremi_asuransi+=$asuransi_nettpremi;
                $xcounter++;
            }

            $arap_produksi_cn = $database->doQuery('update CMS_ArAp_Master set
												fArAp_AmmountTotal="'.$xpremi_asuransi.'"
												where fArAp_No="'.str_replace('DN', 'CNA', $Rdn__['dn_kode']).'"');



            $arap_produksi__detail_cn = $database->doQuery('update CMS_ArAp_Detail set
												fArAp_Amount="'.$xpremi_asuransi.'"
												where fArAp_No="'.str_replace('DN', 'CNA', $Rdn__['dn_kode']).'"');


            //echo '<br /><br />';
            $sendmaildn = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$kode.'"');
            while ($_sendmaildn = mysql_fetch_array($sendmaildn)) {
                $cekdnpesertamail = mysql_fetch_array($database->doQuery('SELECT id_dn, id_cost, input_by FROM fu_ajk_peserta WHERE id_dn="'.$_sendmaildn['id'].'"'));
                $emailinput = $cekdnpesertamail['input_by'];
                $cekemailpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$emailinput.'"'));
                $cekdnpeserta = $database->doQuery('SELECT id_dn, id_cost, id_polis, id_peserta, spaj, nama, usia, tgl_lahir, kredit_tgl, kredit_tenor, kredit_akhir,kredit_jumlah, totalpremi FROM fu_ajk_peserta WHERE id_dn="'.$_sendmaildn['id'].'" AND id_cost="'.$_sendmaildn['id_cost'].'"');
                $message .= '<html><head><title>DN CREATE</title></head><body>
        				<table border="0" width="50%" cellpadding="1" cellspacing="3">
        				<tr><td colspan="3">To '.$cekemailpeserta['nm_user'].'</td></tr>
        				<tr><td colspan="3"><br />Telah di buat Data DN oleh : <b>'.$q['nm_lengkap'].' </b> pada tanggal <b>'.$futgldn.'</b></td></tr>
        				<tr><td width="20%">Nomor DN</td><td>: '.$_sendmaildn['dn_kode'].'</td></tr>
        				<tr><td>Regional</td><td>: '.$_sendmaildn['id_regional'].'</td></tr>
        				<tr><td>Cabang</td><td>: '.$_sendmaildn['id_cabang'].'</td></tr>
        			    </table>
        			    <table border="0" width="100%" cellpadding="1" cellspacing="3">
        			    <tr><td bgcolor="#DEDEDE" align="center" width="20%"><b>ID Peserta</b></td>
        			    	<td bgcolor="#DEDEDE" align="center"><b>Nama</b></td>
        			    	<td bgcolor="#DEDEDE" align="center" width="10%"><b>DOB</b></td>
        			    	<td bgcolor="#DEDEDE" align="center" width="3%"><b>Usia</b></td>
        			    	<td bgcolor="#DEDEDE" align="center" width="10%"><b>Kredit Awal</b></td>
        			    	<td bgcolor="#DEDEDE" align="center" width="3%"><b>Tenor</b></td>
        			    	<td bgcolor="#DEDEDE" align="center" width="10%"><b>Kredit Akhir</b></td>
        			    	<td bgcolor="#DEDEDE" align="center" width="10%"><b>U P</b></td>
        			    	<td bgcolor="#DEDEDE" align="center" width="10%"><b>Total Premi</b></td>
        			    </tr>';
                while ($_cekdnpeserta = mysql_fetch_array($cekdnpeserta)) {
                    $message .= '<tr><td align="center">'.$_cekdnpeserta['id_peserta'].'</td>
        						 <td>'.$_cekdnpeserta['nama'].'</td>
        						 <td align="center">'.$_cekdnpeserta['tgl_lahir'].'</td>
        						 <td align="center">'.$_cekdnpeserta['usia'].'</td>
        						 <td align="center">'._convertDate($_cekdnpeserta['kredit_tgl']).'</td>
        						 <td align="center">'.$_cekdnpeserta['kredit_tenor'].'</td>
        						 <td align="center">'._convertDate($_cekdnpeserta['kredit_akhir']).'</td>
        						 <td align="right">'.duit($_cekdnpeserta['kredit_jumlah']).'</td>
        						 <td align="right">'.duit($_cekdnpeserta['totalpremi']).'</td>
        					 </tr>';
                }
                $message .='</table></body></html>';

                $mail	= new PHPMailer; // call the class
                $mail->IsSMTP();
                $mail->Host = SMTP_HOST; //Hostname of the mail server
                $mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
                $mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
                $mail->Password = SMTP_PWORD; //Password for SMTP authentication
                $mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
                $mail->debug = 1;
                $mail->SMTPSecure = "ssl";
                $mail->IsHTML(true);

                $mail->SetFrom($q['email'], $q['nm_lengkap']); //From address of the mail
                //$mail->Subject = "AJKOnline - DN BARU DARI U/W (".$q['nm_lengkap'].")"; //Subject od your mail
                // $mail->Subject = "AJKOnline - DN BARU telah dibuat oleh (".$q['nm_lengkap'].")"; //Subject od your mail
                $mail->Subject = "AJKOnline - )"; //Subject od your mail

                /*
                $mailsupervisorajk = $database->doQuery('SELECT * FROM pengguna WHERE id_cost="" AND id_polis="" AND status="UNDERWRITING" AND aktif="Y"');
                    while ($_mailsupervisorajk = mysql_fetch_array($mailsupervisorajk)) {
                        $mail->AddAddress($_mailsupervisorajk['email'], $_mailsupervisorajk['nm_lengkap']); //To address who will receive this email
                    }
                    $mail->AddAddress($cekemailpeserta['email'], $cekemailpeserta['nm_lengkap']); //To address who will receive this email
                */
                $mail->AddAddress('hansen@adonai.co.id','Hansen');
                // $mail->AddAddress($cekemailpeserta['email'], $cekemailpeserta['nm_lengkap']); //MAIL STAFF

                //EMAIL PENERIMA  KANTOR U/W
                // $mailOffice = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Produksi" AND status="Aktif"');
                // while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
                //     $mail->AddCC($mailOffice_['emailto'], $mailOffice_['emailnama']); //To address who will receive this email
                // }
                //EMAIL PENERIMA  KANTOR U/W

                //$mail->AddCC("IT@adonai.co.id");
                //$mail->AddBCC("rahmad@adonaits.co.id");
                // $mail->MsgHTML($message); //Put your body of the message you can place html code here
                $mail->MsgHTML('<p>'.$query.' '.$bermasalah.'<p>');
                
                if($met_rate_asuransi['rate'] != ""){

                }else{
                  $send = $mail->Send(); //Send the mails  
                }
                
                echo $message;            
            }
        }
        echo '<div align="center">Data DN telah selesai di buat oleh '.$q['nm_lengkap'].' pada tanggal '.$futgldn.'.</div><meta http-equiv="refresh" content="2; url=ajk_dn.php?r=viewdn">';
            ;
    break;

    case "validasidnuw":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Debit Note (DN) - Validasi DN di ARM</font></th><th width="5%"><a href="ajk_dn.php?r=valbatch">Batch</a></th></tr></table>';

        echo '<form method="post" action="ajk_dn.php?r=validasi_dn_ok">
			  <table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			  <tr><th rowspan="2" width="3%">No</th>
			  	  <th colspan="5">Debit Note (DN)</th>
			  	  <th colspan="5">Credit Note (CN)</th>
			  	  <th rowspan="2" width="5%">Net DN-CN</th>
			  	  <th rowspan="2" width="5%">Tgl Input</th>
			  	  <th rowspan="2" width="1%"><input type="checkbox" id="selectall"/>ALL</th>
			  </tr>
			  <tr><th width="11%">Nomor</th>
				  <th width="5%">Total Premi</th>
				  <th width="5%">Jumlah Peserta</th>
				  <th width="10%">Cabang</th>
				  <th width="5%">Tipe</th>
			  	  <th width="11%">Nomor</th>
				  <th width="5%">Total Klaim</th>
				  <th width="5%">Jumlah Peserta</th>
				  <th width="10%">Cabang</th>
				  <th width="5%">Tipe</th>
			  </tr>';
        if ($_REQUEST['x']) {
            $m = ($_REQUEST['x']-1) * 250;
        } else {
            $m = 0;
        }
        $met = $database->doQuery('SELECT * FROM fu_ajk_dn WHERE id!="" AND validasi_uw="ya" AND validasi_arm="tdk" AND validasi_batch="" AND del IS NULL ORDER BY tgl_createdn DESC, id_dn DESC LIMIT ' . $m . ' , 250');
        $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" AND validasi_uw="ya" AND validasi_arm="tdk" AND validasi_batch="" AND del IS NULL '));
        $totalRows = $totalRows[0];
        $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
        while ($metdn = mysql_fetch_array($met)) {
            $metpeserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$metdn['dn_kode'].'" AND del IS NULL');
            $fupeserta = mysql_num_rows($metpeserta);
            while ($idcek = mysql_fetch_array($metpeserta)) {
                if ($idcek['id_peserta']=="") {
                    $x = 100000000 + $idcek['id'];
                    $xx = substr($x, 1);
                    $metx = $database->doQuery('UPDATE fu_ajk_peserta SET id_peserta="'.$xx.'" WHERE id_peserta="" AND id="'.$idcek['id'].'"');
                }
                if ($idcek['status_peserta']=="") {
                    $tipedn = "Inforce";
                } else {
                    $tipedn = $idcek['status_peserta'];
                }
            }
            $valdncn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn WHERE id_dn="'.$metdn['dn_kode'].'" AND (type_claim !="Refund" OR type_claim !="Deeath") '));
            if ($valdncn['id_dn']==$metdn['dn_kode']) {
                $metValcn = $valdncn['id_cn'];
                $metValcnclaim = duit($valdncn['total_claim']);
                $metValcnpeserta = '<b>1</b> Peserta';
                $metValcbg = $valdncn['id_cabang'];
                $metValtype = $valdncn['type_claim'];
            } else {
                $metValcn = '-';
                $metValcnclaim = '-';
                $metValcnpeserta = '-';
                $metValcbg = '-';
                $metValtype = '-';
            }

            $valnetnya = $metdn['totalpremi'] - $valdncn['total_claim'];
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			    <td align="center">'.(++$no + ($pageNow-1) * 250).'</td>
			    <td align="center">'.$metdn['dn_kode'].'</td>
			    <td align="right"><b>'.duit($metdn['totalpremi']).'</b></td>
			    <td align="center"><b>'.$fupeserta.'</b> Peserta</td>
			    <td>'.$metdn['id_cabang'].'</td>
			    <td align="center">'.$tipedn.'</td>
				<td align="center">'.$metValcn.'</td>
			    <td align="center"><b>'.$metValcnclaim.'</b></td>
			    <td align="center">'.$metValcnpeserta.'</td>
			    <td>'.$metValcbg.'</td>
			    <td>'.$metValtype.'</td>
			    <td align="right"><b>'.duit($valnetnya).'<b></td>
			    <td align="right">'.$metdn['tgl_createdn'].'</td>
				<td align="center"><input type="checkbox" class="case" name="valdata[]" value="'.$metdn['id'].'" id="cbx"></td>';
        }
        echo '<tr bgcolor="#FFF"><td colspan="13">&nbsp;</td>
				<td align="center"><a href="#" onClick="if(confirm(\'Validasi nomor DN ke ARM ?\')){return true;}{return false;}"><input type="submit" name="ApproveValidasiUW" Value="OK"></a></td></tr>
			  <tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_dn.php?r=validasidnuw&', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 250);
        //echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['rdns'].'&dne='.$_REQUEST['rdne'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        echo '<b>Total Data Validasi : <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table></form>';
            ;
    break;

    case "valbatch":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Debit Note - BATCH</font></th><th width="5%"><a href="ajk_dn.php?r=validasidnuw"><img src="image/back.gif"></a></th></tr></table>';
        echo '	  <table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			  <tr><th width="1%">No</th>
			  	  <th>No Batch</th>
			  	  <th width="10%">Jumlah DN</th>
			  	  <th width="10%">Jumlah CN</th>
			  	  <th width="10%">User</th>
			  	  <th width="10%">Tanggal Batch</th>
			  	  <th width="1%">Print</th>
			  </tr>';
        if ($_REQUEST['x']) {
            $m = ($_REQUEST['x']-1) * 25;
        } else {
            $m = 0;
        }
        $met = $database->doQuery('SELECT * FROM fu_ajk_batch ORDER BY id DESC LIMIT ' . $m . ' , 25');
        $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_batch WHERE id != ""'));
        $totalRows = $totalRows[0];
        $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
        while ($metbatch = mysql_fetch_array($met)) {
            $jumdn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_dn WHERE validasi_batch="'.$metbatch['idb'].'"'));
            $jumcn = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_cn WHERE validasi_batchcn="'.$metbatch['idb'].'"'));

            $tglbatch = explode(" ", $metbatch['input_time']);
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		    <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			<td>'.$metbatch['no_batch'].'</td>
			<td align="center">'.$jumdn.'</td>
			<td align="center">'.$jumcn.'</td>
			<td align="center">'.$metbatch['input_by'].'</td>
			<td align="center">'._convertDate($tglbatch[0]).'</td>
			<td align="center"><a href="ajk_report_fu.php?fu=printbatch&userna='.$q['nm_lengkap'].'&idb='.$metbatch['idb'].'" target="_blank"><img src="image/print1.png" width="25"></a></td>
		  </tr>';
        }
        echo '</table>';
                ;
    break;

    case "validasi_dn_ok":
        if ($_REQUEST['valdata']=="") {
            echo '<center>Silahkan ceklist nomor DN yang akan do konfirmasi ke bagian ARM. !<br /><a href="ajk_dn.php?r=validasidnuw">Back</a></center>';
        } else {
            $metbatch = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_batch ORDER BY id DESC'));
            $autobatch = $metbatch['idb'] + 1;
            $idbatchnya = 100000000 + $autobatch;
            $idbatch = substr($idbatchnya, 1);
            $batchtgl = explode("-", $futgldnIng);
            $batchthn = substr($batchtgl[0], 2);
            $kodebatch = 'AJKPRM-'.$batchthn.'-'.$batchtgl[1].'-'.$idbatch;

            $mbatch = $database->doQuery('INSERT INTO fu_ajk_batch SET idb="'.$autobatch.'", no_batch="'.$kodebatch.'", input_by="'.$_SESSION['nm_user'].'", input_time="'.$futgldnIng.'"');
            foreach ($_REQUEST['valdata'] as $k => $val) {
                //PENOMORAN BATCH DN
                $met = $database->doQuery('UPDATE fu_ajk_dn SET validasi_uw="ya", validasi_batch="'.$autobatch.'" WHERE id="'.$val.'"');

                //PENOMORAN BATCH CN
                $metcekcnval = mysql_fetch_array($database->doQuery('SELECT id,dn_kode FROM fu_ajk_dn WHERE id = "'.$val.'"'));
                $metcn = $database->doQuery('UPDATE fu_ajk_cn SET validasi_cn_uw="ya", validasi_batchcn="'.$autobatch.'" WHERE id_dn="'.$metcekcnval['dn_kode'].'"');
            }
            echo '<meta http-equiv="refresh" content="2;URL=ajk_dn.php?r=valbatch"><center><b>Penomoran batch telah di buat.</b></center>';
        }
            ;
    break;

    case "viewdn":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Invoice Debit Note (DN) - Download Debit Note (DN) </font></th>
			  <tr><td>
			  <tr><td colspan="2" align="center">
		<fieldset style="padding: 2">
		<legend>Searching</legend>
		<table border="0" width="100%" cellpadding="2" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td>Nama Persusahaan</td>
			<td>: <select id="id_cost" name="id_cost">
			  	<option value="">-----Perusahaan-----</option>';
            $metreg = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
            while ($metreg_ = mysql_fetch_array($metreg)) {
                echo '<option value="'.$metreg_['id'].'">'.$metreg_['name'].'</option>';
            }
            echo '</select></td>
			<td>Nama Produk</td>
			<td>: <select name="id_polis" id="id_polis"><option value="">--- Produk ---</option></select></td>
			<td>Asuransi</td>
			<td>: <select name="id_asuransi" id="id_asuransi"><option value="">--- Asuransi ---</option></select></td>
		</tr>
		<tr><td>Regional</td>
			<td>: <select id="rreg" name="rreg">
			<option value="">--- Pilih ---</option>';
                $rreg=$database->doQuery('SELECT * FROM fu_ajk_regional ORDER BY name ASC');
        while ($freg = mysql_fetch_array($rreg)) {
            echo  '<option value="'.$freg['name'].'"'._selected($_REQUEST['rreg'], $freg['name']).'>'.$freg['name'].'</option>';
        }
        echo '</select></td>
			<td width="10%">DN Date </td>
			<td>: ';print initCalendar();	print calendarBox('rdns', 'triger', $_REQUEST['rdns']);echo ' s/d ';
                print initCalendar();	print calendarBox('rdne', 'triger1', $_REQUEST['rdne']); echo '</td>
			<td width="11%">Nomor DN</td><td>: <input type="text" name="dns" value="'.$_REQUEST['dns'].'"> s/d <input type="text" name="dne" value="'.$_REQUEST['dne'].'">';
        echo '</td>
		</tr>
		<tr><td>Cabang</td>
			<td>: <select id="rcabang" name="rcabang">
				<option value="">--- Pilih ---</option>';
                $rcabang=$database->doQuery('SELECT * FROM fu_ajk_cabang ORDER BY name ASC');
        while ($farea = mysql_fetch_array($rcabang)) {
            echo  '<option value="'.$farea['name'].'"'._selected($_REQUEST['rcabang'], $farea['name']).'>'.$farea['name'].'</option>';
        }
        echo '</select></td><td>Payment Date</td>
				<td>: ';print initCalendar();	print calendarBox('rpays', 'triger2', $_REQUEST['rpays']); echo ' s/d ';
        print initCalendar();	print calendarBox('rpaye', 'triger3', $_REQUEST['rpaye']); echo '</td>
			<td>Status Pembayaran</td>
			<td>: <select id="rstat" name="rstat">
					<option value="">--- Pilih Status ---</option>
					<option value="paid">Paid</option>
					<option value="unpaid">Unpaid</option>
			</select></td>
			</tr>
		<tr><td colspan="6" align="center"><input type="submit" name="button" name="carieuy" value="Cari" class="button"></td></tr>
		</form>
		</table>
		</fieldset><br />';
        echo '<table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
			  <tr><th width="3%">No</th>
			  	  <th width="25%">Perusahaan</th>
			  	  <th width="25%">Asuransi</th>
			  	  <th width="10%">Produk</th>
			  	  <th>Debitnote</th>
			  	  <th width="5%">Peserta</th>
			  	  <th>Premi Bank</th>
			  	  <th>Tanggal Debitnote</th>
			  	  <th>Tanggal WPC</th>
			  	  <th width="1%">Status</th>
			  	  <th width="5%">Paid Date</th>
			  	  <th width="12%">Creditnote</th>
			  	  <th width="5%">Premi CN</th>
			  	  <th width="5%">Status CN</th>
			  	  <th width="5%">NettPremi Bank</th>
			  	  <th width="5%">NettPremi Asuransi</th>
			  	  <th width="10%">Cabang</th>
			  	  <th width="9%">Regional</th>
			  	  <th width="10%">Option</th>
			  	  <!--<th width="1%">Hapus DN</th>-->
			  </tr>';

        if ($_REQUEST['id_cost']) {
            $satu = 'AND fu_ajk_dn.id_cost = "'.$_REQUEST['id_cost'].'"';
        }
        if ($_REQUEST['id_polis']) {
            $dua = 'AND fu_ajk_dn.id_nopol	 = "'.$_REQUEST['id_polis'].'"';
        }
        if ($_REQUEST['id_asuransi']) {
            $tiga = 'AND fu_ajk_dn.id_as = "'.$_REQUEST['id_asuransi'].'"';
        }
        if ($_REQUEST['rreg']) {
            $empat = 'AND fu_ajk_dn.id_regional LIKE "' .  $_REQUEST['rreg'] . '"';
        }
        if ($_REQUEST['rcabang']) {
            $lima = 'AND fu_ajk_dn.id_cabang LIKE "' . $_REQUEST['rcabang'] . '"';
        }

        if ($_REQUEST['rdns']!='' and $_REQUEST['rdne']!='') {
            $enam= 'AND fu_ajk_dn.tgl_createdn BETWEEN \''.$_REQUEST['rdns'].'\' AND \''.$_REQUEST['rdne'].'\'';
        }
        if ($_REQUEST['rpays']!='' and $_REQUEST['rpaye']!='') {
            $tujuh= 'AND fu_ajk_dn.tgl_dn_paid BETWEEN \''.$_REQUEST['rpays'].'\' AND \''.$_REQUEST['rpaye'].'\'';
        }

        if ($_REQUEST['rstat']) {
            $delapan = 'AND fu_ajk_dn.dn_status = "'.$_REQUEST['rstat'].'"';
        }
        if ($_REQUEST['rdnno']) {
            $sembilan = 'AND fu_ajk_dn.dn_kode LIKE "%' . $_REQUEST['rdnno'] . '%"';
        }
        if ($_REQUEST['dns']!='' and $_REQUEST['dne']!='') {
            $sepuluh = 'AND fu_ajk_dn.dn_kode BETWEEN \''.$_REQUEST['dns'].'\' AND \''.$_REQUEST['dne'].'\'';
        }

        if ($_REQUEST['x']) {
            $m = ($_REQUEST['x']-1) * 25;
        } else {
            $m = 0;
        }

        $met = $database->doQuery('SELECT fu_ajk_costumer.name AS perusahaan,
										  fu_ajk_polis.nmproduk,
										  fu_ajk_dn.id,
										  fu_ajk_dn.id_cost,
										  fu_ajk_dn.id_nopol,
										  fu_ajk_dn.id_as,
										  fu_ajk_dn.id_polis_as,
										  fu_ajk_dn.id_regional,
										  fu_ajk_dn.id_cabang,
										  fu_ajk_dn.totalpremi,
										  fu_ajk_dn.tgl_createdn,
										  (fu_ajk_dn.tgl_createdn + INTERVAL fu_ajk_polis.jtempo DAY) AS tglWPC,
										  fu_ajk_dn.tgl_dn_paid,
										  fu_ajk_dn.dn_status,
										  fu_ajk_dn.dn_kode
								   FROM fu_ajk_dn
								   INNER JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
								   INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
								   WHERE fu_ajk_dn.id !="" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL
				   				   GROUP BY fu_ajk_dn.dn_kode
				   				   ORDER BY fu_ajk_dn.tgl_createdn DESC, fu_ajk_dn.id DESC LIMIT ' . $m . ' , 25');
        $totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_dn WHERE id != "" '.$satu.' '.$dua.' '.$tiga.' '.$empat.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' '.$sembilan.' '.$sepuluh.' AND fu_ajk_dn.del IS NULL '));
        $totalRows = $totalRows[0];
        $_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
        while ($metdn = mysql_fetch_array($met)) {
            $met_asuransi_ = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$metdn['id_as'].'"'));
            $met_peserta_ = mysql_fetch_array($database->doQuery('SELECT COUNT(nama) AS jData FROM fu_ajk_peserta WHERE id_cost="'.$metdn['id_cost'].'" AND id_polis="'.$metdn['id_nopol'].'" AND id_dn="'.$metdn['id'].'"  AND (status_peserta IS NULL OR status_peserta !="Batal") AND del IS NULL GROUP BY id_dn'));
            $met_pesertaAS_ = mysql_fetch_array($database->doQuery('SELECT status_peserta,
																	   IF(status_peserta="Batal","",SUM(nettpremi)) AS netpremi
																FROM fu_ajk_peserta_as
																WHERE id_bank="'.$metdn['id_cost'].'" AND
																	  id_polis="'.$metdn['id_nopol'].'" AND
																	  id_asuransi="'.$metdn['id_as'].'" AND
																	  id_polis_as="'.$metdn['id_polis_as'].'" AND
																	  id_dn="'.$metdn['id'].'"
																GROUP BY id_dn'));
            $met_creditnote_ = mysql_fetch_array($database->doQuery('SELECT id, id_cn, type_claim, SUM(total_claim) AS tCLaim FROM fu_ajk_cn WHERE id_cost="'.$metdn['id_cost'].'" AND id_nopol="'.$metdn['id_nopol'].'" AND id_dn="'.$metdn['id'].'" GROUP BY id_dn'));


            if ($met_creditnote_['id_cn']) {
                $_nomorcn = '<a href="../aajk_report.php?er=_eBatal&idC='.$met_creditnote_['id'].'" target="_blank">'.$met_creditnote_['id_cn'].'</a>';
                $_typecn = $met_creditnote_['type_claim'];
                $_nilaicn = duit($met_creditnote_['tCLaim']);
            } else {
                $_nomorcn = '';
                $_nilaicn = '';
                $_typecn = '';
            }

            if ($met_pesertaAS_['status_peserta']=="Batal") {
                $_nilaiAsuransi = '';
            } else {
                $_nilaiAsuransi = $met_pesertaAS_['netpremi'];
            }
            $netpremiBank = $metdn['totalpremi'] - $met_creditnote_['tCLaim'];
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			    <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			    <td>'.$metdn['perusahaan'].'</td>
			    <td>'.$met_asuransi_['name'].'</td>
			    <td>'.$metdn['nmproduk'].'</td>
			    <td align="right"><a href="ajk_dn.php?r=viewmember&id='.$metdn['id'].'" target="_blank">'.$metdn['dn_kode'].'</a></td>
			    <td align="right">'.duit($met_peserta_['jData']).' Data</td>
			    <td align="center">'.duit($metdn['totalpremi']).'</td>
			    <td align="center">'._convertDate($metdn['tgl_createdn']).'</td>
			    <td align="center">'._convertDate($metdn['tglWPC']).'</td>
			    <td align="center">'.strtoupper($metdn['dn_status']).'</td>
			    <td align="center">'.$metdn['tgl_dn_paid'].'</td>
			    <td>'.$_nomorcn.'</td>
			    <td>'.$_nilaicn.'</td>
			    <td align="right"><b>'.$_typecn.'</b></td>
			    <td align="right"><b>'.duit($netpremiBank).'</b></td>
			    <td align="right"><b>'.duit($_nilaiAsuransi).'</b></td>
			    <td>'.$metdn['id_cabang'].'</td>
			    <td>'.$metdn['id_regional'].'</td>
			    <td align="center">
			    	<!--<a href="ajk_dn.php?r=ket&id='.$metdn['id'].'" title="input text"><img src="image/edit.png" width="25"></a> &nbsp;
			    	<a href="ajk_report_fu.php?fu=ajkpdfinvdn&id='.$metdn['id'].'" title="DN Pdf '.$metdn['dn_kode'].'" target="_blank"><img src="image/dninvoice.png" width="21"></a> &nbsp;-->
					<a href="../aajk_report.php?er=_kwitansi&idn='.$metdn['id'].'&ats=adm" target="_blank"><img src="../image/dninvoice1.jpg" width="15"></a> &nbsp;
					<a href="../aajk_report.php?er=_kwipeserta&idn='.$metdn['id'].'" target="_blank"><img src="../image/new.png" width="15"></a>

				</td>
				<!--<td align="center">'.$statusbaru.'</td>-->';
        }
        echo '<tr><td colspan="22">';
        echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&id_asuransi='.$_REQUEST['id_asuransi'].'&dns='.$_REQUEST['dns'].'&dne='.$_REQUEST['dne'].'&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&', $total = $totalRows, $psDeh = 10, $anchor = '', $perPage = 25);
        //echo createPageNavigations($file = 'ajk_dn.php?r=viewdn&rpays='.$_REQUEST['rpays'].'&rpaye='.$_REQUEST['rpaye'].'&rreg='.$_REQUEST['rreg'].'&rcabang='.$_REQUEST['rcabang'].'&rdnno='.$_REQUEST['rdnno'].'&rstat='.$_REQUEST['rstat'].'&dns='.$_REQUEST['rdns'].'&dne='.$_REQUEST['rdne'].'&', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
        echo '<b>Total Data Debit Note (DN): <u>' . $totalRows . '</u></b></td></tr>';
        echo '</table>';
        /*
        $printdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn'));
        if ($printdn['id_prm']!="") {	$metprint ='<a title="print data DN" href="ajk_report_fu.php?fu=ajkpdf&id='.$_REQUEST['id'].'" target="_blank"><img src="image/dninvoice.jpg" width="30"></a>';
                                        $metprintmembers ='<a title="print data member" href="ajk_report_fu.php?fu=ajkpdfm&id='.$_REQUEST['id'].'" target="_blank"><img src="image/dnmember.jpg" width="30"></a>';
        }
        else	{	$metprint ='<img src="image/dninvoice.jpg" width="29">';
                    $metprintmembers ='<img src="image/dnmember.jpg" width="29">';
        }
        echo '<table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr><td valign="top" width="12%" align="center">
                '.$metprint.' &nbsp; '.$metprintmembers.' &nbsp;
                <a href="ajk_report_fu.php?fu=exl&id='.$_REQUEST['id'].'"><img src="image/excel.png" width="29"></a> &nbsp;
                <a href="ajk_report_fu.php?fu=pri&id='.$_REQUEST['id'].'"><img src="image/print.png" width="29"></a>
                <table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6"><tr><th> Nomor DN</th></tr>';
        $dn = $database->doQuery('SELECT * FROM fu_ajk_dn ORDER BY dn_kode ASC');
        while ($rdn = mysql_fetch_array($dn)) {
        if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
              <td><a href="ajk_dn.php?r=viewdn&id='.$rdn['id'].'">'.$rdn['dn_kode'].'</a></td></tr>';
        }
        echo '</table>
              </td>';
        if ($_REQUEST['e']="viewdn") {
        $d = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'" '));
        $cbg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$d['id_cabang'].'" '));
        $area = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_area WHERE id="'.$cbg['id_area'].'" '));
        $regional = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$area['id_reg'].'" '));
        //$cbg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE '));
        echo '<td valign="top">Regional : <b>'.$regional['name'].', '.$area['name'].'</b><br /> Kantor Cabang : <b>'.$cbg['name'].'</b><br />
              <table border="0" width="100%" cellpadding="5" cellspacing="1" bgcolor="#bde0e6">
              <tr><th width="7%">No.SPAJ</th>
                  <th width="8%">No.Peserta</th>
                  <th width="1%">P/W</th>
                  <th width="15%">Identitas</th>
                  <th>Nama</th>
                  <th width="1%">Usia</th>
                  <th width="9%">Tgl Kredit</th>
                  <th width="10%">Jumlah.Kredit</th>
                  <th width="3%">Tenor</th>
                  <th width="5%">Premi</th>
                  <th width="5%">Adm</th>
                  <th width="5%">Refund</th>
                  <th width="5%">Ex.Premi</th>
                  <th width="5%">Total.Premi</th>
              </tr>';
        $peserta = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$d['dn_kode'].'" AND id_dn!=""');
        while ($fupeserta = mysql_fetch_array($peserta)) {
        if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
            $x = 1000000000 + $fupeserta['id'];
            $xx = substr($x, 1);
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
              <td>'.$fupeserta['spaj'].'</td>
                  <td>'.$xx.'</td>
                  <td>'.$fupeserta['gender'].'</td>
                  <td>'.$fupeserta['kartu_type'].' '.$fupeserta['kartu_no'].'</td>
                  <td>'.$fupeserta['nama'].'</td>
                  <td>'.$fupeserta['usia'].'</td>
                  <td>'.$fupeserta['kredit_tgl'].'</td>
                  <td align="right">'.duit($fupeserta['kredit_jumlah']).'</td>
                  <td align="center">'.$fupeserta['kredit_tenor'].'</td>
                  <td align="right">'.duit($fupeserta['premi']).'</td>
                  <td align="right">'.duit($fupeserta['biaya_adm']).'</td>
                  <td align="right">'.duit($fupeserta['biaya_refund']).'</td>
                  <td align="right">'.duit($fupeserta['ext_premi']).'</td>
                  <td align="right">'.duit($fupeserta['totalpremi']).'</td>
                  </tr>';
        $futotal +=$fupeserta['totalpremi'];
        }
        $upd_dn = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi="'.$futotal.'" WHERE id="'.$_REQUEST['id'].'" ');
        echo '<tr bgcolor="#DEDEDE"><td colspan="7" align="center"><b>T O T A L</b></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align="right"><b>'.duit($futotal).'</b></td></tr>';
        }
        echo '</table>
            </td></tr>';
        echo '</table>';
        */

        echo '<!--WILAYAH COMBOBOX-->
		<script src="javascript/metcombo/prototype.js"></script>
		<script src="javascript/metcombo/dynamicombo.js"></script>
		<!--WILAYAH COMBOBOX-->
		<script>
		document.observe("dom:loaded",function(){
			new DynamiCombo( "id_cost" , {
				elements:{
					"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
					"id_asuransi":	{url:\'javascript/metcombo/data.php?req=setpolisasuransi\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_asuransi"] ?>\'},
				},
				loadingImage:\'loader1.gif\',
				loadingText:\'Loading...\',
				debug:0
			} )
		});
		</script>';
                ;
    break;

    case "as_edit":
        echo '<table border="1" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="95%" align="left" colspan="2">Modul DN Members - Edit Asuransi</font></th><th><a href="ajk_dn.php?r=viewmember&id='.$_REQUEST['id'].'"><img src="image/back.png" width="20"></a></th></tr>
			  </table>';
        $fusdn = mysql_fetch_array($database->doQuery('SELECT
		fu_ajk_costumer.`name` AS perusahaan,
		fu_ajk_polis.nmproduk AS produk,
		fu_ajk_asuransi.`name` AS asuransi,
		fu_ajk_polis_as.nopol AS polisasuransi,
		fu_ajk_dn.id,
		fu_ajk_dn.id_cost,
		fu_ajk_dn.id_nopol,
		fu_ajk_dn.id_as,
		fu_ajk_dn.id_polis_as,
		fu_ajk_dn.id_regional,
		fu_ajk_dn.id_cabang,
		fu_ajk_dn.dn_kode
		FROM
		fu_ajk_dn
		INNER JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
		INNER JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
		INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		INNER JOIN fu_ajk_polis_as ON fu_ajk_dn.id_polis_as = fu_ajk_polis_as.id
		WHERE
		fu_ajk_dn.id = "'.$_REQUEST['id'].'"'));
        $metAsPolis = $database->doQuery('SELECT * FROM fu_ajk_polis_as WHERE id_cost="'.$fusdn['id_cost'].'" AND nmproduk="'.$fusdn['id_nopol'].'"  ORDER BY id_as ASC');
        echo '<form method="post" action="">
			  <table border="0" width="100%" cellpadding="3" cellspacing="0">
			  <tr><td colspan="2" width="10%">Perusahaan </td><td colspan="5">: '.strtoupper($fusdn['perusahaan']).'</td></tr>
			  <tr><td colspan="2">Produk </td><td colspan="5">: '.strtoupper($fusdn['produk']).'</td></tr>
			  <tr><td colspan="2">Regional </td><td colspan="5">: '.$fusdn['id_regional'].'</td></tr>
			  <tr><td colspan="2">Cabang  </td><td colspan="5">: '.$fusdn['id_cabang'].'</td></tr>
			  <tr><td colspan="2">Debit Note</td><td colspan="5">: '.$fusdn['dn_kode'].'</td></tr>
			  <tr><td colspan="2">Asuransi  </td><td colspan="5">: '.$fusdn['asuransi'].'</td></tr>
			  <tr><td colspan="2">Nomor Polis  </td><td colspan="5">: '.$fusdn['polisasuransi'].'</td></tr>
			  <tr><td colspan="2">Nomor Polis Baru </td><td colspan="5">:
		<select name="id_polisas" id="id_polisas">
			<option value="">---Pilih Polis Asuransi---</option>';
        while ($metAsPolis_ = mysql_fetch_array($metAsPolis)) {
            $metAs = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_asuransi WHERE id="'.$metAsPolis_['id_as'].'"'));
            if ($metAsPolis_['id']==$fusdn['id_polis_as']) {
                echo  '<option value="'.$metAsPolis_['id'].'" disabled>'.$metAs['name'].' - '.$metAsPolis_['nopol'].'</option>';
            } else {
                echo  '<option value="'.$metAsPolis_['id'].'"'._selected($_REQUEST['id_polisas'], $fusdn['id_polis_as']).'>'.$metAs['name'].' - '.$metAsPolis_['nopol'].'</option>';
            }
        }
        echo '</select></td></tr>
				<tr><td align="center"colspan="2"><input type="hidden" name="re" value="pesertapolisbaru"><input type="submit" name="ere" value="Simpan" onClick="if(confirm(\'Apakah anda yakin akan mengganti Asuransi dari nomor DN '.$fusdn['dn_kode'].' ini ?\')){return true;}{return false;}"></td></tr>
			  </table></form>';
        if ($_REQUEST['re']=="pesertapolisbaru") {
            if ($_REQUEST['id_polisas']=="") {
                echo '<center><font color="red">Silahkan pilih Polis Asuransi untuk mengedit data premi Asuransi</font></center><meta http-equiv="refresh" content="2;URL=ajk_dn.php?r=viewmember&id='.$_REQUEST['id'].'">';
            } else {
                echo '<table border="0" width="100%" cellpadding="3" cellspacing="0"><tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">SPAJ</th>
			<th width="5%" rowspan="2">No. Reg</th>
			<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
			<th width="1%" rowspan="2">P/W</th>
			<th rowspan="2" width="8%">Tgl Lahir</th>
			<th rowspan="2" width="1%">Usia</th>
			<th colspan="4" width="10%">Status Kredit</th>
			<th width="1%" rowspan="2">Premi</th>
			<th colspan="2" width="10%">Biaya</th>
			<th width="1%" rowspan="2">Total Premi</th>
			<th rowspan="2" width="8%">Medical</th>
		</tr>
		<tr><th width="8%">Kredit Awal</th>
			<th width="1%">Tenor</th>
			<th width="8%">Kredit Akhir</th>
			<th>Jumlah</th>
			<th>Adm</th>
			<th>Ext. Premi</th>
		</tr>';
                //echo 'ID ASURANSI BARU '.$_REQUEST['id_polisas'].'<br />';
                //echo 'ID DN '.$_REQUEST['id'].'<br />';
                //echo 'ID DN '.$fusdn['id_nopol'].'<br />';
                $metPolisAs = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis_as WHERE id="'.$_REQUEST['id_polisas'].'"'));


                $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" AND id_dn="'.$fusdn['id'].'" AND id_cost="'.$fusdn['id_cost'].'" AND id_polis="'.$fusdn['id_nopol'].'" AND del IS NULL ORDER BY cabang ASC');
                while ($fudata = mysql_fetch_array($data)) {
                    $metPolis = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, singlerate FROM fu_ajk_polis WHERE id="'.$fusdn['id_nopol'].'"'));
                    //jika rate dengan usia//
                    //echo 'Nama Produknya '.$metPolis['singlerate'].' - '.$metPolis['nmproduk'].'<br />';
                    if ($metPolis['singlerate']=="Y") {
                        $mametTenor = $fudata['kredit_tenor'] / 12;
                        $metPolisAsBaru = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$fusdn['id_cost'].'" AND id_polis="'.$fusdn['id_nopol'].'" AND id_as="'.$metPolisAs['id_as'].'" AND id_polis_as="'.$metPolisAs['id'].'" AND tenor="'.$mametTenor.'" AND usia="'.$fudata['usia'].'" AND status="baru" AND del is null'));
                        $premibarunya = $fudata['kredit_jumlah'] * $metPolisAsBaru['rate'] / 1000;
                        $totalpremibarunya = $premibarunya + $fudata['ext_premi'];
                    } else {
                        $metPolisAsBaru = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi_as WHERE id_cost="'.$fusdn['id_cost'].'" AND id_polis="'.$fusdn['id_nopol'].'" AND id_as="'.$metPolisAs['id_as'].'" AND id_polis_as="'.$metPolisAs['id'].'" AND tenor="'.$fudata['kredit_tenor'].'" AND status="baru"  AND del is null'));
                        $premibarunya = $fudata['kredit_jumlah'] * $metPolisAsBaru['rate'] / 1000;
                        $totalpremibarunya = $premibarunya + $fudata['ext_premi'];
                    }
                    $metUpdatePstAs = $database->doQuery('UPDATE fu_ajk_peserta_as SET id_asuransi="'.$metPolisAs['id_as'].'",
																		   id_polis_as="'.$metPolisAs['id'].'",
																		   b_premi="'.$premibarunya.'",
																		   b_extpremi="'.$fudata['ext_premi'].'",
																		   nettpremi="'.$totalpremibarunya.'",
																		   update_by="'.$q['nm_lengkap'].'",
																		   update_date="'.$futoday.'"
											  WHERE id_bank="'.$fusdn['id_cost'].'" AND
											  		id_polis="'.$fusdn['id_nopol'].'" AND
											  		id_dn="'.$fudata['id_dn'].'" AND
											  		id_peserta="'.$fudata['id_peserta'].'"');

                    if (($no % 2) == 1) {
                        $objlass = 'tbl-odd';
                    } else {
                        $objlass = 'tbl-even';
                    }
                    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.++$no.'</td>
					<td align="center">'.$fudata['spaj'].'</td>
					<td>'.$fudata['id_peserta'].'</td>
					<td>'.$fudata['nama'].'</td>
					<td align="center">'.$fudata['gender'].'</td>
					<td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
					<td align="center">'.$fudata['usia'].'</td>
					  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
					  <td align="center">'.$fudata['kredit_tenor'].'</td>
					  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
					  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
					<td align="right">'.duit($premibarunya).'</td>
					<td align="right">'.duit($fudata['biaya_adm']).'</td>
					<!--<td align="right">'.duit($em).'</td>-->
					<td align="right">'.duit($fudata['ext_premi']).'</td>
					<td align="right">'.duit($totalpremibarunya).'</td>
					<td align="center">'.$fudata['status_medik'].'</td>
					<!--<td align="center">'.$hapusdata.'</td>-->
					</tr>';
                    $jkredit +=$fudata['kredit_jumlah'];
                    $jpremi +=$premibarunya;
                    $exjpremi +=$fudata['ext_premi'];
                    $jtpremi +=$totalpremibarunya;
                }
                echo '<tr bgcolor="orange"><td colspan="10" align="center"><b>Total</td>
			  	  <td align="right"><b>'.duit($jkredit).'</td>
			  	  <td align="right"><b>'.duit($jpremi).'</td><td>&nbsp;</td>
				  <td align="right"><b>'.duit($exjpremi).'</td>
			  	  <td align="right"><b>'.duit($jtpremi).'</td><td>&nbsp;</td>
			  </tr></table>';
                $metUpdateDNAsuransi = $database->doQuery('UPDATE fu_ajk_dn SET id_polis_as="'.$metPolisAs['id'].'", id_as="'.$metPolisAs['id_as'].'", totalpremi_as="'.$jtpremi.'" WHERE id="'.$fusdn['id'].'"');
                echo '<center><b>Data peserta asuransi telah dirubah oleh '.$q['nm_lengkap'].' pada tanggal '.$futoday.'.<br /><meta http-equiv="refresh" content="2;URL=ajk_dn.php?r=viewmember&id='.$_REQUEST['id'].'"></b></center>';
            }
        } else {
            echo '<table border="0" width="100%" cellpadding="3" cellspacing="0"><tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">SPAJ</th>
			<th width="5%" rowspan="2">No. Reg</th>
			<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
			<th width="1%" rowspan="2">P/W</th>
			<th rowspan="2" width="8%">Tgl Lahir</th>
			<th rowspan="2" width="1%">Usia</th>
			<th colspan="4" width="10%">Status Kredit</th>
			<th width="1%" rowspan="2">Premi</th>
			<th colspan="2" width="10%">Biaya</th>
			<th width="1%" rowspan="2">Total Premi</th>
			<th rowspan="2" width="8%">Medical</th>
		</tr>
		<tr><th width="8%">Kredit Awal</th>
			<th width="1%">Tenor</th>
			<th width="8%">Kredit Akhir</th>
			<th>Jumlah</th>
			<th>Adm</th>
			<th>Ext. Premi</th>
		</tr>';
            $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id!="" AND id_dn="'.$fusdn['id'].'" AND id_cost="'.$fusdn['id_cost'].'" AND id_polis="'.$fusdn['id_nopol'].'" AND del IS NULL ORDER BY cabang ASC');
            $jumdata = mysql_num_rows($data);
            while ($fudata = mysql_fetch_array($data)) {
                if ($jumdata == 1) {
                    // DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddataDN&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].') dan Penghapusan Nomor DN ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
                    $hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
                } else {
                    // DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddata&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].')?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
                    $hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
                }


                $met_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND spak="'.$fudata['spaj'].'"'));
                //$metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);
                $em = $fudata['premi'] * $met_spk['ext_premi'] / 100;
                $totalpreminya = $fudata['premi'] + $em;
                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td align="center">'.++$no.'</td>
					<td align="center">'.$fudata['spaj'].'</td>
					<td>'.$fudata['id_peserta'].'</td>
					<td>'.$fudata['nama'].'</td>
					<td align="center">'.$fudata['gender'].'</td>
					<td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
					<td align="center">'.$fudata['usia'].'</td>
					  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
					  <td align="center">'.$fudata['kredit_tenor'].'</td>
					  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
					  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
					<td align="right">'.duit($fudata['premi']).'</td>
					<td align="right">'.duit($fudata['biaya_adm']).'</td>
					<!--<td align="right">'.duit($em).'</td>-->
					<td align="right">'.duit($fudata['ext_premi']).'</td>
					<td align="right">'.duit($fudata['totalpremi']).'</td>
					<td align="center">'.$fudata['status_medik'].'</td>
					<!--<td align="center">'.$hapusdata.'</td>-->
					</tr>';
                $jkredit +=$fudata['kredit_jumlah'];
                $jpremi +=$fudata['premi'];
                $exjpremi +=$em;
                $jtpremi +=$fudata['totalpremi'];
            }
            echo '<tr><th colspan="10">Total</th>
			  	  <th>'.duit($jkredit).'</th>
			  	  <th>'.duit($jpremi).'</th><th>&nbsp;</th>
				  <th>'.duit($exjpremi).'</th>
			  	  <th>'.duit($jtpremi).'</th><th>&nbsp;</th>
			  </tr></table>';
        }
            ;
    break;

    case "viewmember":
        echo '<table border="1" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="95%" align="left" colspan="2">Modul DN Members </font></th><th><a href="ajk_dn.php?r=viewdn"><img src="image/back.png" width="20"></a></th></tr>
			  </table>
			  <form method="post" action="">';
        $fusdn = mysql_fetch_array($database->doQuery('SELECT
		fu_ajk_costumer.`name` AS perusahaan,
		fu_ajk_polis.nmproduk AS produk,
		fu_ajk_asuransi.`name` AS asuransi,
		fu_ajk_polis_as.nopol AS polisasuransi,
		fu_ajk_dn.id,
		fu_ajk_dn.id_cost,
		fu_ajk_dn.id_nopol,
		fu_ajk_dn.id_regional,
		fu_ajk_dn.id_cabang,
		fu_ajk_dn.dn_kode
		FROM
		fu_ajk_dn
		LEFT JOIN fu_ajk_costumer ON fu_ajk_dn.id_cost = fu_ajk_costumer.id
		LEFT JOIN fu_ajk_polis ON fu_ajk_dn.id_nopol = fu_ajk_polis.id
		LEFT JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
		LEFT JOIN fu_ajk_polis_as ON fu_ajk_dn.id_polis_as = fu_ajk_polis_as.id
		WHERE
		fu_ajk_dn.id = "'.$_REQUEST['id'].'"'));

        echo '<form method="post" action="#" onload ="onbeforeunload">
			  <table border="0" width="100%" cellpadding="3" cellspacing="1">
			  <tr><td colspan="2">Perusahaan </td><td colspan="5">: '.strtoupper($fusdn['perusahaan']).'</td></tr>
			  <tr><td colspan="2">Produk </td><td colspan="5">: '.strtoupper($fusdn['produk']).'</td></tr>
			  <tr><td colspan="2">Regional </td><td colspan="5">: '.$fusdn['id_regional'].'</td></tr>
			  <tr><td colspan="2">Cabang  </td><td colspan="5">: '.$fusdn['id_cabang'].'</td></tr>
			  <tr><td colspan="2">Asuransi  </td><td colspan="5">: '.$fusdn['asuransi'].'</td></tr>
			  <tr><td colspan="2">Nomor Polis </td><td colspan="5">: '.$fusdn['polisasuransi'].'</td></tr>
			  <tr><td colspan="2">Debit Note</td><td colspan="5">: '.$fusdn['dn_kode'].'</td>
			  	  <td colspan="14" align="right"><a href="ajk_dn.php?r=as_edit&id='.$_REQUEST['id'].'" title="edit asuransi"><img src="image/edit.png" width="25"></a> &nbsp; </td>
			  </tr>
		<tr><th width="1%" rowspan="2">No</th>
			<th width="5%" rowspan="2">S P K</th>
			<th width="5%" rowspan="2">IDPeserta</th>
			<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
			<th width="1%" rowspan="2">P/W</th>
			<th rowspan="2" width="10%">Tgl Lahir</th>
			<th rowspan="2" width="1%">Usia</th>
			<th colspan="4" width="10%">Status Kredit</th>
			<th width="1%" rowspan="2">Premi</th>
			<th colspan="2" width="10%">Biaya</th>
			<th width="1%" rowspan="2">Total Premi</th>
			<th rowspan="2" width="1%">Medical</th>
			<th rowspan="2" width="1%">Status</th>
		</tr>
		<tr><th width="8%">Kredit Awal</th>
			<th width="1%">Tenor</th>
			<th width="8%">Kredit Akhir</th>
			<th>Jumlah</th>
			<th>Adm</th>
			<th>Ext. Premi</th>
		</tr>';

        $data = $database->doQuery('SELECT *,ROUND(premi, 2) AS premi, ROUND(totalpremi, 2) AS totalpremi FROM fu_ajk_peserta WHERE id!="" AND id_dn="'.$fusdn['id'].'" AND id_cost="'.$fusdn['id_cost'].'" AND id_polis="'.$fusdn['id_nopol'].'" AND del IS NULL ORDER BY cabang ASC');
        $jumdata = mysql_num_rows($data);
        while ($fudata = mysql_fetch_array($data)) {
            if ($jumdata == 1) {
                // DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddataDN&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].') dan Penghapusan Nomor DN ?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
                $hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
            } else {
                // DISABLED (081013) $hapusdata = '<a href="ajk_dn.php?r=delUpddata&iddn='.$_REQUEST['id'].'&idP='.$fudata['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data peserta a/n '.addslashes($fudata['nama']).' (Reg.ID : '.$fudata['id_peserta'].')?\')){return true;}{return false;}"><img src="image/deleted.png" width="20"></a>';	}
                $hapusdata = '<a href="#" title="penghapusan data tidak di ijinkan."><img src="image/deleted.png" width="20"></a>';
            }


            $met_spk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id_cost="'.$fudata['id_cost'].'" AND id_polis="'.$fudata['id_polis'].'" AND spak="'.$fudata['spaj'].'"'));
            //$metTrate = $mametrate['rate'] * (1 - $met_spk['ext_premi'] / 100);
            $em = $fudata['premi'] * $met_spk['ext_premi'] / 100;
            $totalpreminya = $fudata['premi'] + $em;
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
				<td align="center">'.++$no.'</td>
				<td align="center">'.$fudata['spaj'].'</td>
				<td>'.$fudata['id_peserta'].'</td>
				<td>'.$fudata['nama'].'</td>
				<td align="center">'.$fudata['gender'].'</td>
				<td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
				<td align="center">'.$fudata['usia'].'</td>
				  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
				  <td align="center">'.$fudata['kredit_tenor'].'</td>
				  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
				  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
				<td align="right">'.duit($fudata['premi']).'</td>
				<td align="right">'.duit($fudata['biaya_adm']).'</td>
				<!--<td align="right">'.duit($fudata['biaya_refund']).'</td>
				<td align="right">'.duit($em).'</td>-->
				<td align="right">'.duit($fudata['ext_premi']).'</td>
				<td align="right">'.duit($fudata['totalpremi']).'</td>
				<td align="center">'.$fudata['status_medik'].'</td>
				<td align="center">'.$fudata['status_peserta'].'</td>
				<!--<td align="center">'.$hapusdata.'</td>-->
				</tr>';
            $jkredit +=$fudata['kredit_jumlah'];
            $jpremi +=$fudata['premi'];
            $exjpremi +=$em;
            $jtpremi +=$fudata['totalpremi'];
        }
        echo '<tr><th colspan="10">Total</th>
			  	  <th>'.duit($jkredit).'</th>
			  	  <th>'.duit($jpremi).'</th><th>&nbsp;</th>
				  <th>'.duit($exjpremi).'</th>
			  	  <th>'.duit($jtpremi).'</th><th>&nbsp;</th><th>&nbsp;</th>
			  </tr></table>
			  </form>';
        //$upd = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi="'.$jtpremi.'" WHERE id="'.$_REQUEST['id'].'"');
                ;
    break;

    case "delUpddata":
        $delpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idP'].'"'));		//CARI ID DATA BATAL UNTUK PENGURANGAN PREMI TABEL DN
        $delpesertaupdate = $database->doQuery('UPDATE fu_ajk_peserta SET update_by="'.$q['nm_user'].'", update_time="'.$futgl.'", del="1", status_aktif="Batal" WHERE id="'.$_REQUEST['idP'].'"');	//UPDATE DATA BATAL


        $cekdelDN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE dn_kode="'.$delpeserta['id_dn'].'"'));
        $hitungpremidel = $cekdelDN['totalpremi'] - $delpeserta['totalpremi'];
        $cekdelDNupdate = $database->doQuery('UPDATE fu_ajk_dn SET totalpremi="'.$hitungpremidel.'", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE dn_kode="'.$delpeserta['id_dn'].'"');

        /* PENDING DATA BATAL PESERTA UNTUK DATA CNNYA
        //$mamet = $database->doQuery('UPDATE fu_ajk_peserta SET update_by="'.$q['nm_user'].'", update_time="'.$futgl.'", status_aktif="batal", del ="1" WHERE id="'.$_REQUEST['idP'].'"');
        //$mametz = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idP'].'"'));
        */
        $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
        $metidcn = explode(" ", $cn['input_date']);	$metidthncn = explode("-", $metidcn[0]);
        if ($metidthncn[0] < $futgliddn) {
            $metautocn = 1;
        } else {
            $metautocn = $cn['idC'] + 1;
        }
        $idcnnya = 10000000000 + $metautocn;	$idcn = substr($idcnnya, 1);
        $cntgl = explode("-", $futgldnIng); $cnthn = substr($cntgl[0], 2);	$cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;

        $Rcn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$delpeserta['id_cost'].'",
														   idC="'.$metautocn.'",
														   id_cn="'.$cn_kode.'",
														   id_dn="'.$delpeserta['id_dn'].'",
														   id_nopol="'.$delpeserta['id_polis'].'",
														   id_peserta="'.$delpeserta['id_peserta'].'",
														   id_regional="'.$delpeserta['regional'].'",
														   id_cabang="'.$delpeserta['cabang'].'",
														   premi="'.$delpeserta['premi'].'",
														   total_claim="'.$delpeserta['totalpremi'].'",
														   tgl_claim="'.$futgldn.'",
														   type_claim="Batal",
														   tgl_createcn="'.$futgldnIng.'",
														   input_by="'.$_SESSION['nm_user'].'",
														   input_date="'.$futgl.'"');
        $rCN = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'" WHERE id="'.$delpeserta['id'].'"');

        echo '<center><b>Data peserta telah di batalkan.<br /><meta http-equiv="refresh" content="2;URL=ajk_dn.php?r=viewmember&id='.$_REQUEST['iddn'].'"></b></center>';
                ;
    break;

    case "delUpddataDN":
        $delpeserta = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['idP'].'"'));		//CARI ID DATA BATAL UNTUK MENGHILANGKAN NOMOR DN
        $delpesertaupdate = $database->doQuery('UPDATE fu_ajk_peserta SET update_by="'.$q['nm_user'].'", update_time="'.$futgl.'", del="1" WHERE id="'.$_REQUEST['idP'].'"');	//UPDATE DATA BATAL
        $cekdelDNupdate = $database->doQuery('UPDATE fu_ajk_dn SET ket="Ada pembatalan peserta", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'", del="1" WHERE dn_kode="'.$delpeserta['id_dn'].'"');
        echo '<center><b>Data peserta beserta nomor DN telah di batalkan.<br /><meta http-equiv="refresh" content="2;URL=ajk_dn.php?r=viewdn"></b></center>';
                ;
    break;

    case "dnclaim":
        echo '<form method="post" action="ajk_dn.php?r=createdn">
			  <table border="0" cellpadding="1" cellspacing="1">
			<tr><td align="center"><a href="ajk_dn.php?r=createdn" onClick="if(confirm(\'Nomor DN Peserta akan dibuat berdasarkan per Cabang ?\')){return true;}{return false;}"><img src="image/createDN.png" border="0" width="25"><br />Create DN</a></td>
			</tr>
		    </table>
			<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="1%" rowspan="2">No</th>
				<th width="5%" rowspan="2">SPAJ</th>
				<th width="5%" rowspan="2">No. Reg</th>
				<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
				<th width="1%" rowspan="2">P/W</th>
				<th colspan="2">Kartu Identitas</th>
				<th rowspan="2">Tgl Lahir</th>
				<th rowspan="2">Usia</th>
				<th colspan="4">Status Kredit</th>
				<th width="1%" rowspan="2">Bunga<br>%</th>
				<th width="1%" rowspan="2">Premi</th>
				<th colspan="3">Biaya</th>
				<th width="1%" rowspan="2">Total Premi</th>
				<th rowspan="2">Medical</th>
				<th rowspan="2">Type</th>
				<th rowspan="2">Cabang</th>
				<th rowspan="2">Area</th>
				<th rowspan="2">Regional</th>
			</tr>
			<tr><th width="5%">Type</th>
				<th width="5%">No</th>
				<th>Kredit Awal</th>
				<th>Jumlah</th>
				<th>Tenor</th>
				<th>Akhir Kredit</th>
				<th>Adm</th>
				<th>Refund</th>
				<th>Ext. Premi</th>
			</tr>';
        $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="" AND status_aktif="aktif" AND status_medik="NM" AND status_peserta!="" '.$satu.' '.$dua.' '.$tiga.' ORDER BY input_time DESC');
        while ($fudata = mysql_fetch_array($data)) {
            echo '<tr class="'.rowClass(++$i).'">
				  <td align="center">'.++$no.'</td>
				  <td>'.$fudata['spaj'].'</td>
				  <td>'.$fudata['id_peserta'].'</td>
				  <td>'.$fudata['nama'].'</td>
				  <td align="center">'.$fudata['gender'].'</td>
				  <td width="1%" align="center">'.$fudata['kartu_type'].'</td>
				  <td>'.$fudata['kartu_no'].'</td>
				  <td align="center">'.$fudata['tgl_lahir'].'</td>
				  <td align="center">'.$fudata['usia'].'</td>
				  <td align="center">'.$fudata['kredit_tgl'].'</td>
				  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
				  <td align="center">'.$fudata['kredit_tenor'].'</td>
				  <td align="center">'.$fudata['kredit_akhir'].'</td>
				  <td align="center">'.$fudata['bunga'].'</td>
				  <td align="right">'.duit($fudata['premi']).'</td>
				  <td align="right">'.duit($fudata['biaya_adm']).'</td>
				  <td align="right">'.duit($fudata['biaya_refund']).'</td>
				  <td align="right">'.duit($fudata['ext_premi']).'</td>
				  <td align="right">'.duit($fudata['totalpremi']).'</td>
				  <td align="center">'.$fudata['status_medik'].'</td>
				  <td align="center">'.$fudata['status_peserta'].'</td>
				  <td align="center">'.$fudata['cabang'].'</td>
				  <td align="center">'.$fudata['area'].'</td>
				  <td align="center">'.$fudata['regional'].'</td>
				  </tr>';
        }
            echo '</table></form>';
            ;
    break;

    case "views":
        echo '<table border="0" cellpadding="5" cellspacing="1" width="100%">
		<tr><th width="100%" align="left">Modul View Data</font></th><th><a href="ajk_dn.php"><img src="image/Backward-64.png" width="20"></a></th></tr>
		</table>';
        if ($_REQUEST['del']=="hapus") {
            $d=$database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Batal", update_by="'.$_SESSION['nm_user'].'", update_time="'.$futgl.'", del="1" WHERE id="'.$_REQUEST['id'].'"');
            $e=$database->doQuery('DELETE FROM fu_ajk_peserta_tempf WHERE spaj="'.$_REQUEST['idt'].'" AND status_aktif="Upload"');
            header("location:ajk_dn.php?r=views&idCost=".$_REQUEST['idCost']."&idPolis=".$_REQUEST['idPolis']."");
        }
        echo '<form method="post" action="ajk_dn.php?r=createdn">
		    <input type="hidden" name="id_cost" value="'.$_REQUEST['idCost'].'">
		    <input type="hidden" name="id_polis" value="'.$_REQUEST['idPolis'].'">
			<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="1%" rowspan="2">Del</th>
				<th width="1%" rowspan="2">No</th>
				<th width="5%" rowspan="2">SPAJ/SPAK/MITRA</th>
				<th width="5%" rowspan="2">No. Reg</th>
				<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
				<th width="1%" rowspan="2">P/W</th>
				<th rowspan="2">Tgl Lahir</th>
				<th colspan="4">Status Kredit</th>
				<th width="1%" rowspan="2">Premi</th>
				<th colspan="3">Biaya</th>
				<th width="1%" rowspan="2">Total Premi</th>
				<th rowspan="2">Medical</th>
				<th rowspan="2">Regional</th>
				<th rowspan="2">Area</th>
				<th rowspan="2">Cabang</th>
			</tr>
			<tr><th>Kredit Awal</th>
				<th>Jumlah</th>
				<th>Tenor</th>
				<th>Kredit Akhir</th>
				<th>Adm</th>
				<th>Discount</th>
				<th>Ext. Premi</th>
			</tr>';
        $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="" AND
												  id_cost="'.$_REQUEST['idCost'].'" AND
												  status_aktif="Approve" AND
												  id_polis="'.$_REQUEST['idPolis'].'" AND
												  status_medik=("SPD" OR "FCL" OR "SPK") AND
												  input_by="'.$_REQUEST['nmUser'].'" AND
												  input_time="'.$_REQUEST['tglUser'].'" AND
												  del IS NULL ORDER BY no_urut ASC');
        while ($fudata = mysql_fetch_array($data)) {
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center"><a href="ajk_dn.php?r=views&del=hapus&idCost='.$_REQUEST['idCost'].'&idPolis='.$_REQUEST['idPolis'].'&id='.$fudata['id'].'&idt='.$fudata['spaj'].'" onClick="if(confirm(\'Anda yakin akan menghapus data ?\')){return true;}{return false;}"><img src="image/delete.gif"></a></td>
			  <td align="center">'.++$no.'</td>
			  <td>'.$fudata['spaj'].'</td>
			  <td>'.$fudata['id_peserta'].'</td>
			  <td>'.$fudata['nama'].'</td>
			  <td align="center">'.$fudata['gender'].'</td>
			  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
			  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
			  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
			  <td align="center">'.$fudata['kredit_tenor'].'</td>
			  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
			  <td align="right">'.duit($fudata['premi']).'</td>
			  <td align="right">'.duit($fudata['biaya_adm']).'</td>
			  <td align="right">'.duit($fudata['disc_premi']).'</td>
			  <td align="right">'.duit($fudata['ext_premi']).'</td>
			  <td align="right">'.duit($fudata['totalpremi']).'</td>
			  <td align="center">'.$fudata['status_medik'].'</td>
			  <td align="center">'.$fudata['regional'].'</td>
			  <td align="center">'.$fudata['area'].'</td>
			  <td align="center">'.$fudata['cabang'].'</td>
			  </tr>';
        }
                echo '</table></form>';
                    ;
    break;

    case "ket":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Debit Note - Keterangan</font></th></tr></table>';
        if ($_REQUEST['op']=="Simpan") {
            $metdnnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
            $met = $database->doQuery('UPDATE fu_ajk_dn SET ket="'.$_REQUEST['kontenel'].'", update_by="'.$q['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
            echo '<center>Keterangan data '.$metdnnya['dn_kode'].' telah di buat oleh '.$q['nm_user'].' pada tanggal '.$futgldn.'</center>';
            echo '<meta http-equiv="refresh" content="2; url=ajk_dn.php?r=viewdn">';
        }
        $metdn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['id'].'"'));
        echo '<table border="0" cellpadding="3" cellspacing="0" width="50%" align="center">
			  <form method="post" action="">
			  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
			  <tr><td width="15%">Nomor DN :</td><td><b>'.$metdn['dn_kode'].'</b></td></tr>
			  <tr><td valign="top">Keterangan :</td><td><textarea name="kontenel" cols="80" rows="8">'.$metdn['ket'].'</textarea></td></tr>
			  <tr><td colspan="2"><input type="Submit" name="op" value="Simpan"></td></tr>
			  </form>
			  </table>';
                ;
    break;

    /* FORMAT DATA BATAL VERSI AJK RELIFE
    case "revisidn":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
              <tr><th width="80%" align="left" colspan="2">Modul Debit Note (DN) - Revisi / Pembatalan</font></th>
                    <th width="3%"><a href="ajk_dn.php?r=datadnbatal" title="Buat DN untuk data yang telah di Batalkan atau di Revisi."><img src="../image/pernyataan.png"></a></th>
                    <th width="3%"><a href="ajk_dn.php?r=viewdn"><img src="../image/back.gif"></a></th>
              </tr></table>';
        if ($_REQUEST['dnbatal']=="OK") {
            //script pembentukan nomor cn//
            $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
            $metidcn = explode(" ",$cn['input_date']);	$metidthncn = explode("-",$metidcn[0]);
            if ($metidthncn[0] < $futgliddn) {	$metautocn = 1;	}	else	{	$metautocn = $cn['idC'] + 1;	}		// untuk menentukan tahun baru penomoran cn menjadi 1 lagi

            $idcnnya = 100000000 + $metautocn;	$idcn = substr($idcnnya,1);
            $cntgl = explode("-", $futgldnIng); $cnthn = substr($cntgl[0],2);	$cn_kode = 'AJKCN-'.$cnthn.'-'.$cntgl[1].'-'.$idcn;
            //script pembentukan nomor cn//

        foreach($_REQUEST['validasidn'] as $k => $val){
            $persertacn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$val.'"'));

            //masukan peserta batal atau revisi ke dalam tablebatal history
            $mameto = $database->doQuery('INSERT INTO fu_ajk_peserta_batal SET no_urut="'.$persertacn['no_urut'].'",
                                                                  id_dn="",
                                                                  id_cost="'.$persertacn['id_cost'].'",
                                                                  id_polis="'.$persertacn['id_polis'].'",
                                                                  namafile="'.$persertacn['namafile'].'",
                                                                  spaj="'.$persertacn['spaj'].'",
                                                                  nama="'.$persertacn['nama'].'",
                                                                  gender="'.$persertacn['gender'].'",
                                                                  kartu_type="'.$persertacn['kartu_type'].'",
                                                                  kartu_no="'.$persertacn['kartu_no'].'",
                                                                  kartu_period="'.$persertacn['kartu_period'].'",
                                                                  tgl_lahir="'.$persertacn['tgl_lahir'].'",
                                                                  usia="'.$persertacn['usia'].'",
                                                                  kredit_tgl="'.$persertacn['kredit_tgl'].'",
                                                                  vkredit_tgl="'.$persertacn['vkredit_tgl'].'",
                                                                  thn="'.$persertacn['thn'].'",
                                                                  bln="'.$persertacn['bln'].'",
                                                                  kredit_jumlah="'.$persertacn['kredit_jumlah'].'",
                                                                  kredit_tenor="'.$persertacn['kredit_tenor'].'",
                                                                  kredit_akhir="'.$persertacn['kredit_akhir'].'",
                                                                  premi="'.$persertacn['premi'].'",
                                                                  bunga="'.$persertacn['bunga'].'",
                                                                  disc_premi="'.$persertacn['disc_premi'].'",
                                                                  biaya_adm="'.$persertacn['biaya_adm'].'",
                                                                  biaya_refund="'.$persertacn['biaya_refund'].'",
                                                                  ext_premi="'.$persertacn['ext_premi'].'",
                                                                  totalpremi="'.$persertacn['totalpremi'].'",
                                                                  badant="'.$persertacn['badant'].'",
                                                                  badanb="'.$persertacn['badanb'].'",
                                                                  statement1="'.$persertacn['statement1'].'",
                                                                  p1_ket="'.$persertacn['p1_ket'].'",
                                                                  statement2="'.$persertacn['statement2'].'",
                                                                  p2_ket="'.$persertacn['p2_ket'].'",
                                                                  statement3="'.$persertacn['statement3'].'",
                                                                  p3_ket="'.$persertacn['p3_ket'].'",
                                                                  statement4="'.$persertacn['statement4'].'",
                                                                  p4_ket="'.$persertacn['p4_ket'].'",
                                                                  ket="'.$persertacn['ket'].'",
                                                                  status_medik="'.$persertacn['status_medik'].'",
                                                                  status_bayar="'.$persertacn['status_bayar'].'",
                                                                  status_aktif="Batal",
                                                                  status_peserta="'.$persertacn['status_peserta'].'",
                                                                  regional ="'.$persertacn['regional'].'",
                                                                  area ="'.$persertacn['area'].'",
                                                                  cabang ="'.$persertacn['cabang'].'",
                                                                  input_by ="'.$persertacn['input_by'].'",
                                                                  input_time ="'.$persertacn['input_time'].'"');
            //masukan peserta batal atau revisi ke dalam table batal history

            //INSERT KE TABEL CN
            $metcn = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cost="'.$persertacn['id_cost'].'",
                                                           idC="'.$metautocn.'",
                                                           id_cn="'.$cn_kode.'",
                                                           id_dn="'.$persertacn['id_dn'].'",
                                                           id_nopol="'.$persertacn['id_polis'].'",
                                                           id_peserta="'.$persertacn['id_peserta'].'",
                                                           id_regional="'.$persertacn['regional'].'",
                                                           id_cabang="'.$persertacn['cabang'].'",
                                                           premi="'.$persertacn['premi'].'",
                                                           total_claim="'.$persertacn['totalpremi'].'",
                                                           tgl_claim="'.$futgldnIng.'",
                                                           type_claim="Batal",
                                                           tgl_createcn="'.$futgldnIng.'",
                                                           tgl_byr_claim="",
                                                           confirm_claim="Approve(unpaid)",
                                                           input_by="'.$_SESSION['nm_user'].'",
                                                           input_date="'.$futgl.'"');
            //INSERT KE TABEL CN

            $met = $database->doQuery('UPDATE fu_ajk_peserta SET status_aktif="Batal", id_klaim="'.$cn_kode.'", update_by="'.$_SESSION['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$val.'"');
        }
            echo '<center>Data telah di Batalkan pada tanggal '.$futgldn.', tunggu proses selanjutnya untuk ke Modul DN baru.<br /><img src="image/loading.gif" width="30"></center>';
            echo '<meta http-equiv="refresh" content="5; url=ajk_dn.php?r=creatednrevisi">';
        }
        if ($_REQUEST['iddn']=="") {
        echo '<center>Data DN pembatalan tidak ada</center>';
        }else{
        $dnbatal = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, id_cost FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'" AND id_cost="'.$_REQUEST['id_cost'].'"'));
        echo '<form method="post" action="">
            <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
            <tr><th width="5%" rowspan="2"><input type="checkbox" id="selectall"/></th>
                <th width="1%" rowspan="2">Status</th>
                <th width="1%" rowspan="2">No</th>
                <th width="10%" rowspan="2">DN Awal</th>
                <th width="5%" rowspan="2">SPAJ</th>
                <th width="5%" rowspan="2">No. Reg</th>
                <th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
                <th width="1%" rowspan="2">P/W</th>
                <th colspan="2">Kartu Identitas</th>
                <th rowspan="2">Tgl Lahir</th>
                <th colspan="4">Status Kredit</th>
                <th width="1%" rowspan="2">Bunga<br>%</th>
                <th width="1%" rowspan="2">Premi</th>
                <th colspan="3">Biaya</th>
                <th width="1%" rowspan="2">Total Premi</th>
                <th width="1%" rowspan="2">Tinggi/ Berat Badan</th>
                <th rowspan="2">Medical</th>
                <th rowspan="2">Cabang</th>
                <th rowspan="2">Area</th>
                <th rowspan="2" width="5%">Regional</th>
            </tr>
            <tr><th width="5%">Type</th>
                <th width="5%">No</th>
                <th>Kredit Awal</th>
                <th>Jumlah</th>
                <th>Tenor</th>
                <th>Kredit Akhir</th>
                <th>Adm</th>
                <th>Refund</th>
                <th>Ext. Premi</th>
            </tr>';
        $pesertabatal = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="'.$dnbatal['dn_kode'].'" AND id_cost="'.$dnbatal['id_cost'].'"');
        while ($pebtl = mysql_fetch_array($pesertabatal)) {
            if ($pebtl['status_aktif']=="batal") {	$mametcek = '';
            }else{	$mametcek = '<input type="checkbox" class="case" name="validasidn[]" value="'.$pebtl['id'].'">';
            }
        if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
        echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
              <td align="center">'.$mametcek.'</td>
              <td align="center">'.$pebtl['status_aktif'].'</td>
              <td align="center">'.++$no.'</td>
              <td>'.$pebtl['id_dn'].'</td>
              <td>'.$pebtl['spaj'].'</td>
              <td>'.$pebtl['id_peserta'].'</td>
              <td>'.$pebtl['nama'].'</td>
              <td align="center">'.$pebtl['gender'].'</td>
              <td width="1%" align="center">'.$pebtl['kartu_type'].'</td>
              <td>'.$pebtl['kartu_no'].'</td>
              <td align="center">'.$pebtl['tgl_lahir'].'</td>
              <td align="center">'.$pebtl['kredit_tgl'].'</td>
              <td align="right">'.duit($pebtl['kredit_jumlah']).'</td>
              <td align="center">'.$pebtl['kredit_tenor'].'</td>
              <td align="center">'.$pebtl['kredit_akhir'].'</td>
              <td align="center">'.$pebtl['bunga'].'</td>
              <td align="right">'.duit($pebtl['premi']).'</td>
              <td align="right">'.duit($pebtl['biaya_adm']).'</td>
              <td align="right">'.duit($pebtl['biaya_refund']).'</td>
              <td align="right">'.duit($pebtl['ext_premi']).'</td>
              <td align="right">'.duit($pebtl['totalpremi']).'</td>
              <td align="center">'.$pebtl['badant'].'/'.$fudata['badanb'].'</td>
              <td align="center">'.$pebtl['status_medik'].'</td>
              <td align="center">'.$pebtl['cabang'].'</td>
              <td align="center">'.$pebtl['area'].'</td>
              <td align="center">'.$pebtl['regional'].'</td>
              </tr>';
        }
        echo '<tr><td><a href="" onClick="if(confirm(\'Proses Semua Data Pembatalan atau Revisi ?\')){return true;}{return false;}"><input type="submit" name="dnbatal" Value="OK"></a><br /></td></tr>
              <tr><td colspan="50" bgcolor="white">Note : Silahkan ceklist semua data untuk pembatalan nomor DN. kemudian klik OK pada tombol di atas.</td></tr>
              </table></form>';
        }
                ;
    break;
    */

    case "revisidn":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Debit Note (DN) - Revisi / Pembatalan</font></th>
			  	  <th width="3%"><a href="ajk_dn.php?r=datadnbatal" title="Buat DN untuk data yang telah di Batalkan atau di Revisi."><img src="../image/pernyataan.png"></a></th>
			  	  <th width="3%"><a href="ajk_dn.php?r=viewdn"><img src="../image/back.gif"></a></th>
			  </tr></table>';
        $dn_batal_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'" AND id_cost="'.$_REQUEST['id_cost'].'"'));
        $dn_batal = $database->doQuery('UPDATE fu_ajk_dn SET dn_status="Batal", update_by="'.$_SESSION['nm_user'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['iddn'].'" AND id_cost="'.$_REQUEST['id_cost'].'"');

        $cn = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cn ORDER BY id DESC'));
        $metidcn = explode(" ", $cn['input_date']);	$metidthncn = explode("-", $metidcn[0]);
        if ($metidthncn[0] < $futgliddn) {
            $metautocn = 1;
        } else {
            $metautocn = $cn['idC'] + 1;
        }
        $idcnnya = 10000000000 + $metautocn;	$idcn = substr($idcnnya, 1);
        $cntgl = explode("-", $futgl); $cnthn = substr($cntgl[0], 2);	$cn_kode = 'ACN'.$cnthn.''.$cntgl[1].''.$idcn;

        $peserta_batal = $database->doQuery('UPDATE fu_ajk_peserta SET id_klaim="'.$cn_kode.'", status_aktif="Lapse", status_peserta="Batal", update_by="'.$_SESSION['nm_user'].'", update_time="'.$futgl.'" WHERE id_dn="'.$dn_batal_['id'].'" AND id_cost="'.$_REQUEST['id_cost'].'"');

        $cn_batal = $database->doQuery('INSERT INTO fu_ajk_cn SET id_cn="'.$cn_kode.'",
																  idC="'.$metautocn.'",
																  id_dn="'.$dn_batal_['id'].'",
																  id_cost="'.$dn_batal_['id_cost'].'",
																  id_nopol="'.$dn_batal_['id_nopol'].'",
																  id_peserta="'.$peserta_batal_['id_peserta'].'",
																  id_regional="'.$dn_batal_['id_regional'].'",
																  id_cabang="'.$dn_batal_['id_cabang'].'",
																  tgl_createcn="'.$datelog.'",
																  type_claim="Batal",
																  premi="'.$dn_batal_['totalpremi'].'",
																  total_claim="'.$dn_batal_['totalpremi'].'",
																  confirm_claim="Approve(unpaid)",
																  input_by="'.$_SESSION['nm_user'].'",
																  input_date="'.$futgl.'"');
        echo '<center>Nomor DN <font color="red"><b>'.$dn_batal_['dn_kode'].'</b></font> telah dibatalkan oleh '.$_SESSION['nm_user'].' pada tanggal '.$futgldn.'.<br />
			  Apakah anda ingin melanjutkan ke pembuatan nomor DN baru dengan beberapa peserta yang telah di batalkan?<br />
			  <a href="ajk_dn.php?r=creatednrevisi&id_cost='.$_REQUEST['id_cost'].'&iddn='.$_REQUEST['iddn'].'"><img src="image/save.png" border="0" width="35"> &nbsp;
			  <a href="ajk_dn.php?r=viewdn"><img src="image/deleted.png" border="0" width="35">
			  </center>';
            ;
    break;

    case "creatednrevisi":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Create Debit Note (DN) - Revisi / Pembatalan</font></th>
			  	  <th width="3%"><a href="ajk_dn.php?r=creatednrevisi&x=euy"><img src="../image/pernyataan.png"></a></th>
			  </tr></table>';
        echo '<form method="post" action="ajk_dn.php?r=creatednbatal">
			  <input type="hidden" name="iddn" value='.$_REQUEST['iddn'].'>
			  <input type="hidden" name="id_cost" value='.$_REQUEST['id_cost'].'>
			<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
				<th rowspan="2"><input type="checkbox" id="selectall"/></th></th>
				<th width="1%" rowspan="2">No</th>
				<th width="5%" rowspan="2">SPAJ</th>
				<th width="5%" rowspan="2">No. Reg</th>
				<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
				<th colspan="2">Kartu Identitas</th>
				<th rowspan="2">Tgl Lahir</th>
				<th colspan="4">Status Kredit</th>
				<th width="1%" rowspan="2">Premi</th>
				<th width="1%" rowspan="2">Ext.Premi</th>
				<th width="1%" rowspan="2">Total Premi</th>
				<th rowspan="2">Type</th>
				<th rowspan="2">Regional</th>
				<th rowspan="2">Area</th>
				<th rowspan="2">Cabang</th>
			</tr>
			<tr><th width="5%">Type</th>
				<th width="5%">No</th>
				<th>Kredit Awal</th>
				<th>Jumlah</th>
				<th>Tenor</th>
				<th>Kredit Akhir</th>
			</tr>';
        $met_revisiDN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'" AND id_cost="'.$_REQUEST['id_cost'].'"'));
        $met_revisi = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost="'.$met_revisiDN['id_cost'].'" AND id_dn="'.$met_revisiDN['id'].'" AND status_aktif="Lapse" AND status_peserta="Batal"');
        while ($met_revisi_ = mysql_fetch_array($met_revisi)) {
            if (($no % 2) == 1) {
                $objlass = 'tbl-odd';
            } else {
                $objlass = 'tbl-even';
            }
            echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center"><input type="checkbox" class="case" name="valdata[]" value="'.$met_revisi_['id'].'"></td>
			  <td align="center">'.(++$no).'</td>
			  <td align="center">'.$met_revisi_['nama_mitra'].'</td>
			  <td align="center">'.$met_revisi_['id_peserta'].'</td>
			  <td>'.$met_revisi_['nama'].'</td>
			  <td align="center">'.$met_revisi_['nip'].'</td>
			  <td align="center">'.$met_revisi_['no_ktp'].'</td>
			  <td align="center">'._convertDate($met_revisi_['tgl_lahir']).'</td>
			  <td align="center">'._convertDate($met_revisi_['kredit_tgl']).'</td>
			  <td align="right">'.duit($met_revisi_['kredit_jumlah']).'</td>
			  <td align="center">'.$met_revisi_['kredit_tenor'].'</td>
			  <td align="center">'._convertDate($met_revisi_['kredit_akhir']).'</td>
			  <td align="right">'.duit($met_revisi_['premi']).'</td>
			  <td align="right">'.duit($met_revisi_['ext_premi']).'</td>
			  <td align="right">'.duit($met_revisi_['totalpremi']).'</td>
			  <td align="center">'.$met_revisi_['status_aktif'].'</td>
			  <td align="center">'.$met_revisi_['regional'].'</td>
			  <td align="center">'.$met_revisi_['area'].'</td>
			  <td align="center">'.$met_revisi_['cabang'].'</td>
			  </tr>';
        }
        echo '<tr><td align="center" colspan="19"><a href="#" onClick="if(confirm(\'Buat DN Baru ?\')){return true;}{return false;}"><input type="hidden" name="creatdnbatal" Value="OK"><input type="submit" name="creatdnbatal" Value="DN Baru"></a></td></tr>
			  </table></form>';
                ;
    break;

    case "creatednbatal":
        if (!$_REQUEST['valdata']) {
            echo '<center><blink><font color="red">Silahkan pilih Data DN baru.!!!</font></blink><a href="ajk_dn.php?r=creatednrevisi&id_cost='.$_REQUEST['id_cost'].'&iddn='.$_REQUEST['iddn'].'"> [back]</a></center>';
        } else {
            foreach ($_REQUEST['valdata'] as $k => $val) {
                $mamet_newDN = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$val.'"'));
                $mameto = $database->doQuery('INSERT INTO fu_ajk_peserta SET id_cost="'.$mamet_newDN['id_cost'].'",
																	 id_polis="'.$mamet_newDN['id_polis'].'",
																	 namafile="'.$mamet_newDN['namafile'].'",
																	 no_urut="'.$mamet_newDN['no_urut'].'",
																	 spaj="'.$mamet_newDN['spaj'].'",
																	 type_data="'.$mamet_newDN['type_data'].'",
																	 id_peserta="'.$mamet_newDN['id_peserta'].'",
																	 nama_mitra="'.$mamet_newDN['nama_mitra'].'",
																	 nama="'.$mamet_newDN['nama'].'",
																	 gender="'.$mamet_newDN['gender'].'",
																	 tgl_lahir="'.$mamet_newDN['tgl_lahir'].'",
																	 usia="'.$mamet_newDN['usia'].'",
																	 kredit_tgl="'.$mamet_newDN['kredit_tgl'].'",
																	 kredit_jumlah="'.$mamet_newDN['kredit_jumlah'].'",
																	 kredit_tenor="'.$mamet_newDN['kredit_tenor'].'",
																	 kredit_akhir="'.$mamet_newDN['kredit_akhir'].'",
																	 premi="'.$mamet_newDN['premi'].'",
																	 disc_premi="'.$mamet_newDN['disc_premi'].'",
																	 biaya_adm="'.$mamet_newDN['biaya_adm'].'",
																	 ext_premi="'.$mamet_newDN['ext_premi'].'",
																	 totalpremi="'.$mamet_newDN['totalpremi'].'",
																	 badant="'.$mamet_newDN['badant'].'",
																	 badanb="'.$mamet_newDN['badanb'].'",
																	 status_medik="'.$mamet_newDN['status_medik'].'",
																	 status_bayar="'.$mamet_newDN['status_bayar'].'",
																	 status_aktif="Inforce",
																	 regional="'.$mamet_newDN['regional'].'",
																	 area="'.$mamet_newDN['area'].'",
																	 cabang="'.$mamet_newDN['cabang'].'",
																	 input_by ="'.$mamet_newDN['input_by'].'",
																	 input_time ="'.$mamet_newDN['input_time'].'"');
                $sumDNrevisi += $mamet_newDN['totalpremi'];
            }
            echo '<br /><br />';
            $metDNRevisi_ = mysql_fetch_array($database->doQuery('SELECT id, id_dn FROM fu_ajk_dn ORDER BY id DESC'));
            $nomordn = $metDNRevisi_['id_dn'] + 1;
            $tglnya = explode("/", $futgldn);
            $thnnya = substr($tglnya[2], 2);
            $idkode = 10000000000 + $nomordn;
            $idkode2 = substr($idkode, 1);	// ID PESERTA //
            $kode = 'ADN'.$thnnya.''.$tglnya[1].''.$idkode2;				// NOMOR DN
        $metDNRevisi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_dn WHERE id="'.$_REQUEST['iddn'].'"'));
            $Rdn = $database->doQuery('INSERT INTO fu_ajk_dn SET id_cost="'.$metDNRevisi['id_cost'].'",
														   id_nopol="'.$metDNRevisi['id_nopol'].'",
														   id_polis_as="'.$metDNRevisi['id_polis_as'].'",
														   id_as="'.$metDNRevisi['id_as'].'",
														   id_dn="'.$nomordn.'",
														   dn_kode="'.$kode.'",
														   totalpremi="'.$sumDNrevisi.'",
														   id_regional="'.$metDNRevisi['id_regional'].'",
														   id_area="'.$metDNRevisi['id_area'].'",
														   id_cabang="'.$metDNRevisi['id_cabang'].'",
														   tgl_createdn="'.$datelog.'",
														   dn_status="unpaid",
														   validasi_uw="ya",
														   namafile="'.$metDNRevisi['namafile'].'",
														   input_by="'.$_SESSION['nm_user'].'",
														   input_time="'.$futgl.'"');

            $metDNRevisi_2 = mysql_fetch_array($database->doQuery('SELECT id, id_dn FROM fu_ajk_dn ORDER BY id DESC'));
            foreach ($_REQUEST['valdata'] as $k => $val) {
                $xNew = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$val.'"'));
                $udpateDNrevisi = $database->doQuery('UPDATE fu_ajk_peserta SET id_dn="'.$metDNRevisi_2['id'].'" WHERE id_cost="'.$xNew['id_cost'].'" AND id_polis="'.$xNew['id_polis'].'" AND id_klaim ="" AND id_peserta="'.$xNew['id_peserta'].'"');
            }
            echo '<center>Telah di buat DN baru dari data Batal oleh '.$_SESSION['nm_user'].' pada tanggal '.$futgldn.'.<br /><img src="image/loading.gif" width="30"></center>';
            echo '<meta http-equiv="refresh" content="2; url=ajk_dn.php?r=viewdn">';
        }
            ;
    break;

    case "editdatarevisi":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Edit Data Revisi / Pembatalan</font></th>
			  	  <th width="5%"><a href="ajk_dn.php?r=creatednrevisi"><img src="../image/back.gif"></a></th>
			  </tr></table>';
        $metrevisi = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta_batal WHERE id="'.$_REQUEST['id'].'"'));
        $r = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metrevisi['id_cost'].'"'));
        $p = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$r['id'].'"');
        echo '<form method="post" action="">
			  <table border="0" cellpadding="3" cellspacing="0" width="40%" align="center">
			  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
			  <tr><td>Nama Perusahaan</td><td>: <input type="hidden" name="id_cost" value="'.$r['id'].'" size="1"><b>'.$r['name'].'</b></td></tr>
			  <tr><td>Nomor Polis</td><td>: ';
                echo '<select name="rpolis"><option value="">---Pilih Polis---</option>';
                while ($pp = mysql_fetch_array($p)) {
                    $pp2=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$metrevisi['id_cost'].'" AND id="'.$metrevisi['id_polis'].'"'));
                    echo  '<option value="'.$pp['id'].'"'._selected($pp['nopol'], $pp2['nopol']).'>'.$pp['nopol'].'</option>';
                }
              echo '</select></td></tr>
			  <tr><td>Type Produk</td><td>: <select size="1" name="rproduk">
				<option value="">--- Type Produk ---</option>
				<option value="Restruktur"'._selected($metrevisi['status_peserta'], "Restruktur").'>Restruktur</option>
				<option value="Baloon"'._selected($metrevisi['status_peserta'], "Baloon").'>Baloon Payment</option>
				<option value="Top Up"'._selected($metrevisi['status_peserta'], "Top Up").'>Top Up</option>
				</select></td></tr>
			  <tr><td>Nama</td><td>: <input type="text" name="rnama" value="'.$metrevisi['nama'].'" size="50"></td></tr>
			  <tr><td>Tanggal Lahir</td><td>: <input type="text" name="rdob" class="tanggal" value="'.$metrevisi['tgl_lahir'].'" size="8"></td></tr>
			  <tr><td>Tanggal Mulai Kredit</td><td>: <input type="text" name="rtanggal1" class="tanggal" value="'.$metrevisi['kredit_tgl'].'" size="8"></td></tr>
			  <tr><td>Jumlah Tenor <i>(perBulan)</i></td><td>: <input type="text" name="rtenor" value="'.$metrevisi['kredit_tenor'].'" size="1"></td></tr>
			  <tr><td>Tanggal Akhir Kredit</td><td>: <input type="text" name="rtanggal2" class="tanggal" value="'.$metrevisi['kredit_akhir'].'" size="8"></td></tr>
			  <tr><td>Jumlah UP</td><td>: <input type="text" name="rjumup" value="'.$metrevisi['kredit_jumlah'].'" size="10"></td></tr>
			  <tr><td>Premi</td><td>: <input type="text" name="rpremi" value="'.$metrevisi['premi'].'" size="10"></td></tr>
			  <tr><td>Discount</td><td>: <input type="text" name="rdisc" value="'.$metrevisi['disc_premi'].'" size="10"></td></tr>
			  <tr><td>Biaya Administrasi</td><td>: <input type="text" name="radm" value="'.$metrevisi['biaya_adm'].'" size="10"></td></tr>
			  <tr><td>Biaya Refund</td><td>: <input type="text" name="rrefund" value="'.$metrevisi['biaya_refund'].'" size="10"></td></tr>
			  <tr><td>Ext. Premi</td><td>: <input type="text" name="rpremiex" value="'.$metrevisi['ext_premi'].'" size="10"></td></tr>
			  <tr><td>Regional</td><td>: <input type="text" name="rreg" value="'.$metrevisi['regional'].'"></td></tr>
			  <tr><td>Area</td><td>: <input type="text" name="rarea" value="'.$metrevisi['area'].'"></td></tr>
			  <tr><td>Cabang</td><td>: <input type="text" name="rcab" value="'.$metrevisi['cabang'].'"></td></tr>
			  <tr><td colspan="2"><input type="hidden" name="r" value="previewdatabatal"><input type="Submit" name="err" value="Preview"></td></tr>
			  </table></form>';
            ;
    break;

    case "previewdatabatal":
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Edit Data Revisi / Pembatalan</font></th></tr></table>';
        if ($_REQUEST['er']=="Ok") {
            $vkredit = explode('/', $_REQUEST['rtanggal1']);
            $thnIng = $vkredit[2].'-'.$vkredit[1].'-'.$vkredit[0];
            $mets=$database->doQuery('UPDATE fu_ajk_peserta_batal SET nama="'.$_REQUEST['rnama'].'",
																  id_polis="'.$_REQUEST['rpolis'].'",
																  tgl_lahir="'.$_REQUEST['rdob'].'",
																  kredit_tgl="'.$_REQUEST['rtanggal1'].'",
																  vkredit_tgl="'.$thnIng.'",
																  bln="'.$vkredit[1].'",
																  thn="'.$vkredit[2].'",
																  kredit_jumlah="'.$_REQUEST['rjumup'].'",
																  kredit_tenor="'.$_REQUEST['rtenor'].'",
																  kredit_akhir="'.$_REQUEST['rtanggal2'].'",
																  premi="'.$_REQUEST['rpremi'].'",
																  disc_premi="'.$_REQUEST['rdisc'].'",
																  biaya_adm="'.$_REQUEST['radm'].'",
																  biaya_refund="'.$_REQUEST['rrefund'].'",
																  ext_premi="'.$_REQUEST['rpremiex'].'",
																  totalpremi="'.$_REQUEST['tpremi'].'",
																  status_peserta="'.$_REQUEST['rproduk'].'"
																  WHERE id="'.$_REQUEST['id'].'"');
            echo '<center>Data batal telah di rubah pada tanggal '.$futgldn.', tunggu proses selanjutnya untuk ke Modul DN baru.<br /><img src="image/loading.gif" width="30"></center>';
            echo '<meta http-equiv="refresh" content="5; url=ajk_dn.php?r=creatednrevisi">';
        }
        $totPremi = $_REQUEST['rpremi'] + $_REQUEST['radm'] + $_REQUEST['rrefund'] + $_REQUEST['rpremiex'];
        $metclient = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
        $metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE  id="'.$_REQUEST['rpolis'].'"'));
        echo '<table border="0" cellpadding="3" cellspacing="1" width="50%" align="center">
			  <form method="post" action="ajk_dn.php?r=previewdatabatal&id='.$_REQUEST['id'].'&er=Ok">
			  <tr><th colspan="2">Preview Data Batal / Revisi yang telah di edit.</th></tr>
			  <tr><td><input type="hidden" name="idbatal" value="'.$_REQUEST['id'].'"></td></tr>
			  <tr><td bgcolor="#DEDEDE" width="25%">Nama Perusahaan</td><td><input type="hidden" name="id_cost" value="'.$_REQUEST['id_cost'].'"><b>'.$metclient['name'].'</b></td></tr>
			  <tr><td bgcolor="#DEDEDE">Nomor Polis</td><td><input type="hidden" name="rpolis" value="'.$_REQUEST['rpolis'].'"><b>'.$metpolis['nopol'].'</b></td></tr>
			  <tr><td bgcolor="#DEDEDE">Type Produk<td><input type="hidden" name="rproduk" value="'.$_REQUEST['rproduk'].'">'.$_REQUEST['rproduk'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Nama</td><td><input type="hidden" name="rnama" value="'.$_REQUEST['rnama'].'">'.$_REQUEST['rnama'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Tanggal Lahir</td><td><input type="hidden" name="rdob" value="'.$_REQUEST['rdob'].'">'.$_REQUEST['rdob'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Tanggal Kredit</td><td><input type="hidden" name="rtanggal1" value="'.$_REQUEST['rtanggal1'].'">'.$_REQUEST['rtanggal1'].' s/d <input type="hidden" name="rtanggal2" value="'.$_REQUEST['rtanggal2'].'">'.$_REQUEST['rtanggal2'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Tenor</td><td><input type="hidden" name="rtenor" value="'.$_REQUEST['rtenor'].'">'.$_REQUEST['rtenor'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">U P</td><td><input type="hidden" name="rjumup" value="'.$_REQUEST['rjumup'].'">'.duit($_REQUEST['rjumup']).'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Premi</td><td><input type="hidden" name="rpremi" value="'.$_REQUEST['rpremi'].'">'.duit($_REQUEST['rpremi']).'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Discount</td><td><input type="hidden" name="rdisc" value="'.$_REQUEST['rdisc'].'">'.$_REQUEST['rdisc'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Biaya Administrasi</td><td><input type="hidden" name="radm" value="'.$_REQUEST['radm'].'">'.duit($_REQUEST['radm']).'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Biaya Refund</td><td><input type="hidden" name="rrefund" value="'.$_REQUEST['rrefund'].'">'.duit($_REQUEST['rrefund']).'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Ext. Premi</td><td><input type="hidden" name="rpremiex" value="'.$_REQUEST['rpremiex'].'">'.duit($_REQUEST['rpremiex']).'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Total Premi</td><td><input type="hidden" name="tpremi" value="'.$totPremi.'"><b>'.duit($totPremi).'</b></td></tr>
			  <tr><td bgcolor="#DEDEDE">Regional</td><td><input type="hidden" name="rreg" value="'.$_REQUEST['rreg'].'">'.$_REQUEST['rreg'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Area</td><td><input type="hidden" name="rarea" value="'.$_REQUEST['rarea'].'">'.$_REQUEST['rarea'].'</td></tr>
			  <tr><td bgcolor="#DEDEDE">Cabang</td><td><input type="hidden" name="rcab" value="'.$_REQUEST['rcab'].'">'.$_REQUEST['rcab'].'</td></tr>
			  <tr><td colspan="2" align="center"><input type="Submit" name="er" value="Ok"><a href="ajk_dn.php?r=editdatarevisi&id='.$_REQUEST['id'].'"> &nbsp; [cancel]</a></td></tr>
			  </table></form>';
            ;
    break;

    case "datadnbatal":
        $updatestatusbatal = $database->doQuery('UPDATE fu_ajk_peserta_batal SET status_aktif="Batal" WHERE status_aktif !="Batal" ');
        echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr><th width="80%" align="left" colspan="2">Modul Create Debit Note (DN) - Revisi / Pembatalan</font></th>
			  	  <th width="3%"><a href="ajk_dn.php?r=creatednrevisi&x=euy"><img src="../image/pernyataan.png"></a></th>
			  </tr></table>';
        echo '<form method="post" action="ajk_dn.php?r=creatednbatal">
			<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="5%" rowspan="2" align="">ALL<input type="checkbox" id="selectall"/></th>
				<th width="1%" rowspan="2">No</th>
				<th width="5%" rowspan="2">SPAJ</th>
				<th width="5%" rowspan="2">Polis</th>
				<th width="5%" rowspan="2">No. Reg</th>
				<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
				<th width="1%" rowspan="2">P/W</th>
				<th colspan="2">Kartu Identitas</th>
				<th rowspan="2">Tgl Lahir</th>
				<th colspan="4">Status Kredit</th>
				<th width="1%" rowspan="2">Bunga<br>%</th>
				<th width="1%" rowspan="2">Premi</th>
				<th colspan="3">Biaya</th>
				<th width="1%" rowspan="2">Total Premi</th>
				<th width="1%" rowspan="2">Tinggi/ Berat Badan</th>
				<th rowspan="2">Medical</th>
				<th rowspan="2">Type</th>
				<th rowspan="2">Cabang</th>
				<th rowspan="2">Area</th>
				<th rowspan="2">Regional</th>
			</tr>
			<tr><th width="5%">Type</th>
				<th width="5%">No</th>
				<th>Kredit Awal</th>
				<th>Jumlah</th>
				<th>Tenor</th>
				<th>Kredit Akhir</th>
				<th>Adm</th>
				<th>Refund</th>
				<th>Ext. Premi</th>
			</tr>';

        $data = $database->doQuery('SELECT * FROM fu_ajk_peserta_batal WHERE id_dn="" AND status_aktif="Batal"  AND del IS NULL ORDER BY input_time DESC');
        while ($fudata = mysql_fetch_array($data)) {
            $riweuh = explode("/", $fudata['tgl_lahir']);
            $cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
            $riweuh2 = explode("/", $fudata['kredit_tgl']);
            $cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];						// FORMULA USIA
            $umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya)) / (60*60*24*365.2425)));

            $riweuhkredit = explode("/", $fudata['kredit_tgl']);
            $cektglkredit = $riweuhkredit[0].'-'.$riweuhkredit[1].'-'.$riweuhkredit[2];		//KREDIT AKHIR
            $endkredit2=date('d/m/Y', strtotime($cektglkredit."+".$fudata['kredit_tenor']." Month"));
            $met = $database->doQuery('UPDATE fu_ajk_peserta SET usia="'.$umur.'", kredit_akhir="'.$endkredit2.'" WHERE id="'.$fudata['id'].'"');

            $metpolis = mysql_fetch_array($database->doQuery('SELECT id, nopol FROM fu_ajk_polis WHERE id="'.$fudata['id_polis'].'"'));
            if ($fudata['status_peserta']=="Batal") {
                $moving ='';
            } else {
                $moving = '<a href="ajk_dn.php?r=datadnbatal&move=movedata&bid='.$fudata['id'].'">'.$fudata['status_peserta'].'</a>';
            }
            echo '<tr class="'.rowClass(++$i).'">
			  <td align="center"><a href="ajk_dn.php?r=editdatarevisi&id='.$fudata['id'].'"><img src="image/edit3.png" width="15"></a>
								 <input type="checkbox" class="case" name="cetakdnbatal[]" value="'.$fudata['id'].'">
			  </td>
			  <td align="center">'.++$no.'</td>
			  <td>'.$fudata['spaj'].'</td>
			  <td>'.$metpolis['nopol'].'</td>
			  <td>'.$fudata['id_peserta'].'</td>
			  <td>'.$fudata['nama'].'</td>
			  <td align="center">'.$fudata['gender'].'</td>
			  <td width="1%" align="center">'.$fudata['kartu_type'].'</td>
			  <td>'.$fudata['kartu_no'].'</td>
			  <td align="center">'.$fudata['tgl_lahir'].'</td>
			  <td align="center">'.$fudata['kredit_tgl'].'</td>
			  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
			  <td align="center">'.$fudata['kredit_tenor'].'</td>
			  <td align="center">'.$endkredit2.'</td>
			  <td align="center">'.$fudata['bunga'].'</td>
			  <td align="right">'.duit($fudata['premi']).'</td>
			  <td align="right">'.duit($fudata['biaya_adm']).'</td>
			  <td align="right">'.duit($fudata['biaya_refund']).'</td>
			  <td align="right">'.duit($fudata['ext_premi']).'</td>
			  <td align="right">'.duit($fudata['totalpremi']).'</td>
			  <td align="center">'.$fudata['badant'].'/'.$fudata['badanb'].'</td>
			  <td align="center">'.$fudata['status_medik'].'</td>
			  <td align="center">'.$moving.'</td>
			  <td align="center">'.$fudata['cabang'].'</td>
			  <td align="center">'.$fudata['area'].'</td>
			  <td align="center">'.$fudata['regional'].'</td>
			  </tr>';
        }
        echo '<tr><td align="center"><a href="#" onClick="if(confirm(\'Buat DN Baru ?\')){return true;}{return false;}"><input type="hidden" name="creatdnbatal" Value="OK"><input type="submit" name="creatdnbatal" Value="DN Baru"></a></td></tr>
					  </table></form>';
        if ($_REQUEST['move']=="movedata") {
            //SET STATUS MENJADI AKTIF
            $bidmove = $database->doQuery('UPDATE fu_ajk_peserta_batal SET status_aktif="aktif" WHERE id="'.$_REQUEST['bid'].'"');

            //PINDAH DATA KE TABEL AKTIF
            $cekbatalaktif = $database->doQuery('INSERT INTO fu_ajk_peserta(id_dn, id_cost, id_polis, namafile, id_klaim, id_peserta, no_urut, spaj, nama, gender, kartu_type, kartu_no, kartu_period, tgl_lahir, usia, kredit_tgl, vkredit_tgl, bln, thn, kredit_jumlah, sum_insured, kredit_tenor, kredit_akhir, premi, disc_premi, bunga, biaya_adm, biaya_refund, ext_premi, totalpremi, badant, badanb, statement1, p1_ket, statement2, p2_ket, statement3, p3_ket, statement4, p4_ket, file_p, ket, status_medik, status_bayar, status_aktif, status_peserta, regional, regional_lama, area, area_lama, cabang, cabang_lama, input_by, input_time )
																 SELECT id_dn, id_cost, id_polis, namafile, id_klaim, id_peserta, no_urut, spaj, nama, gender, kartu_type, kartu_no, kartu_period, tgl_lahir, usia, kredit_tgl, vkredit_tgl, bln, thn, kredit_jumlah, sum_insured, kredit_tenor, kredit_akhir, premi, disc_premi, bunga, biaya_adm, biaya_refund, ext_premi, totalpremi, badant, badanb, statement1, p1_ket, statement2, p2_ket, statement3, p3_ket, statement4, p4_ket, file_p, ket, status_medik, status_bayar, status_aktif, status_peserta, regional, regional_lama, area, area_lama, cabang, cabang_lama, input_by, input_time
																 FROM fu_ajk_peserta_batal WHERE id="'.$_REQUEST['bid'].'"');

            //HAPUS DATA PADA TABEL PESERTA BATAL
            $deldatabatal = $database->doQuery('DELETE FROM fu_ajk_peserta_batal WHERE id="'.$_REQUEST['bid'].'"');
            header("location:ajk_dn.php?r=datadnbatal");
        }
                ;
    break;

    default:
        //FORM UNTUK MENGHITUNG RATE LAMA YANG TELAH DIBATASI SAMPAI TANGGAL 1 DES 2014
        $tglratedebitnote1=date('Y-m-d');
        $tglratedebitnote2=date('2014-12-01');

        $jumharibatasrate = daysBetween($tglratedebitnote1, $tglratedebitnote2);
        if ($jumharibatasrate >= 0) {
            $ratespecial = '<tr><td>Type Rate ('.$jumharibatasrate.' hari lagi)</td>
								<td><select id="type_ratenya" name="type_ratenya">
				  					<option value="">-----Type Rate-----</option>
				  					<option value="lama">LAMA</option>
				  					<option value="baru">BARU</option>
									</select>
								</td>
							</tr>';
        } else {
            $ratespecial ='';
        }
        //FORM UNTUK MENGHITUNG RATE LAMA YANG TELAH DIBATASI SAMPAI TANGGAL 1 DES 2014

        echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">Modul Create Debit Note</font></th></tr></table>
		<table border="0" width="40%" cellpadding="2" cellspacing="1" align="center">
			<form method="post" action="">
			<tr><td width="40%">Nama Persusahaan <font color="red">*</font></td>
				<td><select id="id_cost" name="id_cost">
				  	<option value="">-----Perusahaan-----</option>';
                $metreg = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
                while ($metreg_ = mysql_fetch_array($metreg)) {
                    echo '<option value="'.$metreg_['id'].'">'.$metreg_['name'].'</option>';
                }
                echo '</select></td>
			</tr>
			<tr><td>Nama Produk <font color="red">*</font></td>
				<td><select name="id_polis" id="id_polis"><option value="">--- Produk ---</option></select></td>
			</tr>
			<tr><td>Nama Asuransi <font color="red">*</font></td>
				<td><select name="id_asuransi" id="id_asuransi"><option value="">--- Asuransi ---</option></select></td>
			</tr>
			<tr><td>User Upload <font color="red">*</font></td>
				<td><select name="user_upload" id="user_upload"><option value="">--- User Upload ---</option></select></td>
			</tr>
			<tr><td>Tanggal Upload <font color="red">*</font></td>
				<td><select name="tgl_upload" id="tgl_upload"><option value="">--- Tanggal Upload ---</option></select></td>
			</tr>
			'.$ratespecial.'
			<tr><td colspan="2" align="center"><input type="submit" name="r" value="Searching" class="button"></td></tr>
			</form></table>';

        if ($_REQUEST['r']=="Searching") {
            if ($_REQUEST['id_cost']=="") {
                $error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';
            }
            if ($_REQUEST['id_polis']=="") {
                $error_2 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Produk...!!<br /></div></font></blink>';
            }
            if ($_REQUEST['id_asuransi']=="") {
                $error_3 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Asuransi...!!<br /></div></font></blink>';
            }
            if ($_REQUEST['user_upload']=="") {
                $error_4 = '<div align="center"><font color="red"><blink>Silahkan pilih user upload...!!<br /></div></font></blink>';
            }
            if ($_REQUEST['tgl_upload']=="") {
                $error_5 = '<div align="center"><font color="red"><blink>Silahkan pilih tanggal upload...!!<br /></div></font></blink>';
            }
            if ($error_1 or $error_2 or $error_3 or $error_4 or $error_5) {
                echo $error_1 .''.$error_2.''.$error_3.''.$error_4.''.$error_5;
            } else {
                echo '<form method="post" action="ajk_dn.php?r=createdn">
			  <table border="0" cellpadding="1" cellspacing="1">';
                $met_costumer = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$_REQUEST['id_cost'].'"'));
                $met_produk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_polis'].'"'));
                $met_asuransi = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_asuransi WHERE id="'.$_REQUEST['id_asuransi'].'"'));
                $cek_user = mysql_fetch_array($database->doQuery('SELECT id, input_by FROM fu_ajk_peserta WHERE id="'.$_REQUEST['user_upload'].'"'));
                $cek_tglupload = mysql_fetch_array($database->doQuery('SELECT id, input_time FROM fu_ajk_peserta WHERE id="'.$_REQUEST['tgl_upload'].'"'));

                if ($q['status']=="UNDERWRITING" or $q['level']==1 or $q['status']=="") {
                    echo '<tr><td>Nama Perushaan</td><td>: '.$met_costumer['name'].'</td></tr>
				  <tr><td>Nama Produk</td><td>: '.$met_produk['nmproduk'].'</td></tr>
				  <tr><td>Nama Asuransi</td><td>: '.$met_asuransi['name'].'</td></tr>
				  <tr><td>Type Rate</td><td>: RATE '.strtoupper($_REQUEST['type_ratenya']).'</td></tr>
				  <tr><td>User Upload</td><td>: '.$cek_user['input_by'].'</td></tr>
				  <tr><td>Tanggal Upload</td><td>:'.$cek_tglupload['input_time'].'</td></tr>
				  <tr><td align="center">';
                    if ($_REQUEST['id_asuransi']=="undefined") {
                        echo '';
                    } else {
                        echo '<a href="ajk_dn.php?r=createdn&id_cost='.$_REQUEST['id_cost'].'&id_polis='.$_REQUEST['id_polis'].'&ids='.$_REQUEST['id_asuransi'].'&idu='.$cek_user['input_by'].'&idt='.$cek_tglupload['input_time'].'" onClick="if(confirm(\'Nomor DN Peserta akan dibuat berdasarkan per Cabang ?\')){return true;}{return false;}"><img src="image/createDN.png" border="0" width="25"><br />Create DN</a></td></tr>';
                    }
                }
                if ($_REQUEST['type_ratenya']!="") {
                    $data_mamet = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="" AND status_aktif="Approve" AND id_cost="'.$_REQUEST['id_cost'].'" AND id_polis="'.$_REQUEST['id_polis'].'" AND status_medik=("SPD" OR "FCL" OR "SPK") AND del IS NULL ORDER BY input_time DESC');
                    while ($fudata_mamet = mysql_fetch_array($data_mamet)) {
                        $admpolis_mamet = mysql_fetch_array($database->doQuery('SELECT id_cost, adminfee, day_kredit, discount, singlerate FROM fu_ajk_polis WHERE id_cost="'.$fudata_mamet['id_cost'].'" AND id="'.$fudata_mamet['id_polis'].'"'));
                        $cekrate_mamet = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$fudata_mamet['id_cost'].'" AND id_polis="'.$fudata_mamet['id_polis'].'" AND tenor="'.$fudata_mamet['kredit_tenor'].'" AND status="'.$_REQUEST['type_ratenya'].'"'));		// RATE PREMI PILIHAN
                        $premi_mamet = $fudata_mamet['kredit_jumlah'] * $cekrate_mamet['rate'] / 1000;
                        $diskonpremi_mamet = $premi_mamet * $admpolis_mamet['discount'] /100;			//diskon premi
            $tpremi_mamet = $premi_mamet - $diskonpremi_mamet;							//totalpremi
            $data_mamet_update = $database->doQuery('UPDATE fu_ajk_peserta SET premi = '.$tpremi_mamet.', disc_premi='.$diskonpremi_mamet.', totalpremi='.$tpremi_mamet.' WHERE id="'.$fudata_mamet['id'].'" AND id_dn="" AND status_aktif="Approve" AND id_cost="'.$_REQUEST['id_cost'].'" AND id_polis="'.$_REQUEST['id_polis'].'" AND status_medik=("SPD" OR "FCL") AND del IS NULL ORDER BY input_time DESC');
                    }
                } else {
                }
                if ($_REQUEST['id_polis'] == "19") {
                    $pasangan = '<th rowspan="2">Pasangan</th>';
                }
                echo '</table>
		    <input type="hidden" name="id_cost" value="'.$_REQUEST['id_cost'].'">
		    <input type="hidden" name="id_polis" value="'.$_REQUEST['id_polis'].'">
		    <input type="hidden" name="id_asuransi" value="'.$_REQUEST['id_asuransi'].'">
			<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="1%" rowspan="2">No</th>
				<th rowspan="2" width="10%">Nama Mitra</th>
				<th width="1%" rowspan="2">SPAK</th>
				<th width="1%" rowspan="2">ID Peserta</th>
				<th rowspan="2">Nama Debitur</th>'.$pasangan.'
				<th rowspan="2" width="1%">Tgl Lahir</th>
				<th rowspan="2" width="1%">Usia</th>
				<th colspan="4" width="1%">Status Kredit</th>
				<th width="1%" rowspan="2">Premi</th>
				<th colspan="3" width="1%">Biaya</th>
				<th width="1%" rowspan="2">Total Premi</th>
				<th rowspan="2" width="1%">Medical</th>
				<th rowspan="2" width="1%">Cabang</th>
				<th colspan="2" width="1%">Photo</th>
			</tr>
			<tr><th>Kredit Awal</th>
				<th>Jumlah</th>
				<th>Tenor</th>
				<th>Kredit Akhir</th>
				<th>Adm</th>
				<th>Discount</th>
				<th>Ext. Premi</th>
				<th>Debitur</th>
				<th>KTP</th>
			</tr>';
                $data = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_dn="" AND status_aktif="Approve" AND id_cost="'.$_REQUEST['id_cost'].'" AND id_polis="'.$_REQUEST['id_polis'].'" AND input_by="'.$cek_user['input_by'].'" AND status_medik=("SPD" OR "FCL" OR "SPK") AND input_time="'.$cek_tglupload['input_time'].'" AND del IS NULL ORDER BY input_time DESC');
                while ($fudata = mysql_fetch_array($data)) {
                    if ($fudata['spaj']!="") {
                        $datapasangan = mysql_fetch_array($database->doQuery("select * from fu_ajk_spak where id = (select nolink from fu_ajk_spak_form where idspk = (select id from fu_ajk_spak where spak = '".$fudata['spaj']."'))"));
                    }
                    if ($_REQUEST['id_polis']=="19") {
                        $namapasangan = '<td>'.$datapasangan['spak'].'</td>';
                    }
                    $met_mitra = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id="'.$fudata['nama_mitra'].'" AND del IS NULL'));
                    $met_photo = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_photo WHERE id_cost="'.$fudata['id_cost'].'" AND id_peserta="'.$fudata['id_peserta'].'"'));
                    if (!$met_photo) {
                        if ($fudata['spaj'] == null or $fudata['spaj']=="") {
                            $photonya1 = '<img src="../image/non-user.png" width="50">';
                            $photonya2 = '<img src="../image/non-user.png" width="50">';
                        } else {
                            $_cekSPK = substr($fudata['spaj'], 0, 1);
                            if ($_cekSPK =="M" or $_cekSPK =="P") {
                                $metPhotonya = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.filefotodebitursatu,
																					fu_ajk_spak_form.filefotoktp
																			FROM fu_ajk_spak
																			INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																			WHERE fu_ajk_spak.spak = "'.$fudata['spaj'].'"'));
                                //<a href="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" width="50">';
                                $photonya1 = '<a href="../../ajkmobilescript/'.$metPhotonya['filefotodebitursatu'].'" rel="lightbox" target="_blank" ><img src="../../ajkmobilescript/'.$metPhotonya['filefotodebitursatu'].'" width="50"></a>';
                                $photonya2 = '<a href="../../ajkmobilescript/'.$metPhotonya['filefotoktp'].'" rel="lightbox" target="_blank" ><img src="../../ajkmobilescript/'.$metPhotonya['filefotoktp'].'" width="50"></a>';
                            } else {
                                $metPhotonya = mysql_fetch_array($database->doQuery('SELECT photo_spk
																			FROM fu_ajk_spak
																			WHERE spak = "'.$fudata['spaj'].'"'));
                                $photonya1 = '<a href="'.$metpath_file.''.$metPhotonya['photo_spk'].'" rel="lightbox" target="_blank" ><img src="'.$metpath_file.'/'.$metPhotonya['photo_spk'].'" width="50"></a>';
                                $photonya2 = '<a href="'.$metpath_file.''.$met_photo['photo_dekl_2'].'" rel="lightbox" target="_blank" ><img src="'.$metpath_file.'/'.$met_photo['photo_dekl_2'].'" width="50"></a>';
                            }
                        }
                    } else {
                        $_cekSPK = substr($fudata['spaj'], 0, 1);
                        if ($_cekSPK =="M" or $_cekSPK =="P") {
                            $metPhotonya = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form.filefotodebitursatu,
																					fu_ajk_spak_form.filefotoktp
																			FROM fu_ajk_spak
																			INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																			WHERE fu_ajk_spak.spak = "'.$fudata['spaj'].'"'));
                            //<a href="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" rel="lightbox"><img src="../../ajkmobilescript/'.$met_['filefotodebitursatu'].'" width="50">';
                            $photonya1 = '<a href="../../ajkmobilescript/'.$metPhotonya['filefotodebitursatu'].'" rel="lightbox" target="_blank" ><img src="../../ajkmobilescript/'.$metPhotonya['filefotodebitursatu'].'" width="50"></a>';
                            $photonya2 = '<a href="../../ajkmobilescript/'.$metPhotonya['filefotoktp'].'" rel="lightbox" target="_blank" ><img src="../../ajkmobilescript/'.$metPhotonya['filefotoktp'].'" width="50"></a>';
                        } else {
                            $info1 = pathinfo($met_photo['photo_dekl_1']);
                            if ($info1['extension']=="pdf") {
                                $imginfo1 = '<img src="../image/ajk_doc.png" width="25">';
                                $photonya1 = '<a href="'.$metpath_file.''.$met_photo['photo_dekl_1'].'" target="_blank" >'.$imginfo1.'</a>';
                            } else {
                                $photonya1 = '<a href="'.$metpath_file.''.$met_photo['photo_dekl_1'].'" rel="lightbox" target="_blank" ><img src="'.$metpath_file.'/'.$met_photo['photo_dekl_1'].'" width="50"></a>';
                            }

                            $info2 = pathinfo($met_photo['photo_dekl_2']);
                            if ($info1['extension']=="pdf") {
                                $imginfo2 = '<img src="../image/ajk_doc.png" width="25">';
                                $photonya2 = '<a href="'.$metpath_file.''.$met_photo['photo_dekl_2'].'" target="_blank" >'.$imginfo2.'</a>';
                            } else {
                                $photonya2 = '<a href="'.$metpath_file.''.$met_photo['photo_dekl_2'].'" rel="lightbox" target="_blank" ><img src="'.$metpath_file.'/'.$met_photo['photo_dekl_2'].'" width="50"></a>';
                            }
                        }
                    }
                    //DOKUMEN MEMOUSIA DAN DOKUMEN MEDICAL
                    if ($fudata['memousia']=="") {
                        $memoUsia_ = $fudata['usia'];
                    } else {
                        $memoUsia_ = '<a href="'.$metpath_file.''.$fudata['memousia'].'" target="_blank">'.$fudata['usia'].'</a>';
                    }

                    if ($fudata['medicalfile']=="") {
                        $debMedical_ = $fudata['nama'];
                    } else {
                        $debMedical_ = '<a href="'.$metpath_file.''.$fudata['medicalfile'].'" target="_blank">'.$fudata['nama'].'</a>';
                    }
                    //DOKUMEN MEMOUSIA DAN DOKUMEN MEDICAL

                    if (($no % 2) == 1) {
                        $objlass = 'tbl-odd';
                    } else {
                        $objlass = 'tbl-even';
                    }
                    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.++$no.'</td>
			  <td align="center">'.$met_mitra['nmproduk'].'</td>
			  <td align="center">'.$fudata['spaj'].''.$info1['extension'].'</td>
			  <td align="center">'.$fudata['id_peserta'].'</td>
			  <td>'.$debMedical_.'</td>'.$namapasangan.'
			  <td align="center">'._convertDate($fudata['tgl_lahir']).'</td>
			  <td align="center">'.$memoUsia_.'</td>
			  <td align="center">'._convertDate($fudata['kredit_tgl']).'</td>
			  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
			  <td align="center">'.$fudata['kredit_tenor'].'</td>
			  <td align="center">'._convertDate($fudata['kredit_akhir']).'</td>
			  <td align="right">'.duit($fudata['premi']).'</td>
			  <td align="right">'.duit($fudata['biaya_adm']).'</td>
			  <td align="right">'.duit($fudata['disc_premi']).'</td>
			  <td align="right">'.duit($fudata['ext_premi']).'</td>
			  <td align="right">'.duit($fudata['totalpremi']).'</td>
			  <td align="center">'.$fudata['status_medik'].'</td>
			  <td align="center">'.$fudata['cabang'].'</td>
			  <td align="center">'.$photonya1.'</td>
			  <td align="center">'.$photonya2.'</td>
			  </tr>';
                }
                echo '</table></form>';
            }
        } else {
            $data2 = $database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id_cost !="" AND id_polis !="" AND id_dn="" AND status_aktif="Approve" AND DEL IS NULL ORDER BY input_time DESC');
            $met = mysql_num_rows($data2);
            echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="1%">No</th>
				<th>Nama Asuransi</th>
				<th width="10%">Premi Asuransi</th>
				<th width="10%">Persentase</th>
				<th width="10%">Premi Asuransi Bulan Lalu</th>
				<th width="10%">Persentase Bulan Lalu</th>
				<th width="10%">Premi Asuransi Bulan Ini</th>
				<th width="10%">Persentase Bulan Ini</th>
			</tr>';
            /*
               $alldata_as = mysql_fetch_array($database->doQuery('SELECT SUM(fu_ajk_peserta_as.nettpremi) AS aspremi
               FROM fu_ajk_peserta
               INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn
               WHERE fu_ajk_peserta.del IS NULL AND fu_ajk_peserta.id_dn != "" AND fu_ajk_peserta.status_peserta != "Batal"
               GROUP BY fu_ajk_peserta_as.del'));

               /*
               $alldata_as_pst = $database->doQuery('SELECT SUM(fu_ajk_peserta_as.nettpremi) AS aspremi, fu_ajk_asuransi.`name` AS nmasuransi
               FROM fu_ajk_peserta
               INNER JOIN fu_ajk_peserta_as ON fu_ajk_peserta.id_dn = fu_ajk_peserta_as.id_dn
               INNER JOIN fu_ajk_asuransi ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
               WHERE fu_ajk_peserta.del IS NULL AND fu_ajk_peserta.id_dn != "" AND fu_ajk_peserta.status_peserta != "Batal"
               GROUP BY fu_ajk_peserta_as.id_asuransi');
            */

            $alldata_as = mysql_fetch_array($database->doQuery('SELECT fu_ajk_asuransi.id as id_asuransi, fu_ajk_dn.id, fu_ajk_asuransi.`name` AS nmasuransi, Sum(fu_ajk_peserta_as.nettpremi) AS aspremi
															FROM fu_ajk_dn
															INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
															INNER JOIN fu_ajk_peserta_as ON fu_ajk_dn.id = fu_ajk_peserta_as.id_dn AND fu_ajk_dn.id_cost = fu_ajk_peserta_as.id_bank AND fu_ajk_dn.id_nopol = fu_ajk_peserta_as.id_polis AND fu_ajk_dn.id_polis_as = fu_ajk_peserta_as.id_polis_as AND fu_ajk_dn.id_as = fu_ajk_peserta_as.id_asuransi
															WHERE fu_ajk_dn.del IS NULL AND fu_ajk_peserta_as.del IS NULL
															GROUP BY fu_ajk_peserta_as.del'));

            //echo $alldata_as['aspremi'];
            $alldata_as_pst = $database->doQuery('SELECT fu_ajk_asuransi.id as id_asuransi, fu_ajk_dn.id, fu_ajk_asuransi.`name` AS nmasuransi, Sum(fu_ajk_peserta_as.nettpremi) AS aspremi
											  FROM fu_ajk_dn
											  INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
											  INNER JOIN fu_ajk_peserta_as ON fu_ajk_dn.id = fu_ajk_peserta_as.id_dn AND fu_ajk_dn.id_cost = fu_ajk_peserta_as.id_bank AND fu_ajk_dn.id_nopol = fu_ajk_peserta_as.id_polis AND fu_ajk_dn.id_polis_as = fu_ajk_peserta_as.id_polis_as AND fu_ajk_dn.id_as = fu_ajk_peserta_as.id_asuransi
											  WHERE fu_ajk_dn.del IS NULL AND fu_ajk_peserta_as.del IS NULL
											  GROUP BY fu_ajk_dn.id_as');
            while ($alldata_as_pst_ = mysql_fetch_array($alldata_as_pst)) {
                if (($no % 2) == 1) {
                    $objlass = 'tbl-odd';
                } else {
                    $objlass = 'tbl-even';
                }
                $metPersen = ($alldata_as_pst_['aspremi'] / $alldata_as['aspremi']) * 100;
                /*
                $metthismonth = mysql_fetch_array($database->doQuery('SELECT total_premi_periode,
                                                                            (total_premi_periode/total)*100 as persentase
                                                            FROM(
                                                            SELECT fu_ajk_asuransi.id as id_asuransi,
                                                                        fu_ajk_asuransi.name as nm_asuransi,
                                                                        sum(fu_ajk_peserta_as.nettpremi)as total_premi_periode,
                                                                        (SELECT sum(fu_ajk_peserta_as.nettpremi)
                                                                            FROM fu_ajk_asuransi
                                                                                     INNER JOIN fu_ajk_peserta_as
                                                                                     ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
                                                                                     INNER JOIN fu_ajk_dn
                                                                                     ON fu_ajk_dn.id = fu_ajk_peserta_as.id_dn
                                                                            WHERE fu_ajk_asuransi.del is null and
                                                                                        MONTH(fu_ajk_dn.tgl_createdn) = MONTH(CURDATE()) and
                                                                                        YEAR(fu_ajk_dn.tgl_createdn) = YEAR(CURDATE()))as total
                                                            FROM fu_ajk_asuransi
                                                                     INNER JOIN fu_ajk_peserta_as
                                                                     ON fu_ajk_peserta_as.id_asuransi = fu_ajk_asuransi.id
                                                                     INNER JOIN fu_ajk_dn
                                                                     ON fu_ajk_dn.id = fu_ajk_peserta_as.id_dn
                                                            WHERE fu_ajk_asuransi.del is null and
                                                                        MONTH(fu_ajk_dn.tgl_createdn) = MONTH(CURDATE()) and
                                                                        YEAR(fu_ajk_dn.tgl_createdn) = YEAR(CURDATE()) and
                                                                        fu_ajk_asuransi.id = '.$alldata_as_pst_['id_asuransi'].'
                                                            GROUP BY fu_ajk_asuransi.name
                                                            )AS TEMP1
                                                            ORDER BY id_asuransi'));
                */
                $metthismonth = mysql_fetch_array($database->doQuery('SELECT total_premi_asuransi_last_month,
																	 (total_premi_asuransi_last_month/total_premi_last_month)*100 as persentase_last_month,
																	 total_premi_asuransi,
																	 (total_premi_asuransi/total_premi)*100 as persentase
														FROM(
														SELECT (SELECT SUM(fu_ajk_peserta_as.nettpremi)
																		FROM fu_ajk_peserta_as
																				 INNER JOIN fu_ajk_dn
																				 on fu_ajk_dn.id = fu_ajk_peserta_as.id_dn
																		WHERE MONTH(fu_ajk_dn.tgl_createdn) = MONTH(CURDATE() - INTERVAL 1 MONTH) and
																					YEAR(fu_ajk_dn.tgl_createdn) = YEAR(CURDATE() - INTERVAL 1 MONTH))as total_premi_last_month,

																		(SELECT SUM(fu_ajk_peserta_as.nettpremi)
																		FROM fu_ajk_peserta_as
																				 INNER JOIN fu_ajk_dn
																				 on fu_ajk_dn.id = fu_ajk_peserta_as.id_dn
																		WHERE MONTH(fu_ajk_dn.tgl_createdn) = MONTH(CURDATE() - INTERVAL 1 MONTH) and
																					YEAR(fu_ajk_dn.tgl_createdn) = YEAR(CURDATE() - INTERVAL 1 MONTH) and
																					fu_ajk_peserta_as.id_asuransi= fu_ajk_asuransi.id)as total_premi_asuransi_last_month,

																		(SELECT SUM(fu_ajk_peserta_as.nettpremi)
																		FROM fu_ajk_peserta_as
																				 INNER JOIN fu_ajk_dn
																				 on fu_ajk_dn.id = fu_ajk_peserta_as.id_dn
																		WHERE MONTH(fu_ajk_dn.tgl_createdn) = MONTH(CURDATE()) and
																					YEAR(fu_ajk_dn.tgl_createdn) = YEAR(CURDATE()))as total_premi,

																			(SELECT SUM(fu_ajk_peserta_as.nettpremi)
																			FROM fu_ajk_peserta_as
																					 INNER JOIN fu_ajk_dn
																					 on fu_ajk_dn.id = fu_ajk_peserta_as.id_dn
																			WHERE MONTH(fu_ajk_dn.tgl_createdn) = MONTH(CURDATE()) and
																						YEAR(fu_ajk_dn.tgl_createdn) = YEAR(CURDATE()) and
																						fu_ajk_peserta_as.id_asuransi= fu_ajk_asuransi.id)as total_premi_asuransi
														FROM fu_ajk_asuransi
														WHERE id = '.$alldata_as_pst_['id_asuransi'].'
														)as temp1'));
                echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td align="center">'.++$no.'</td>
			<td>'.$alldata_as_pst_['nmasuransi'].'</td>
			<td align="right">'.duit($alldata_as_pst_['aspremi']).'</td>
			<td align="center">'.duitdollar($metPersen).'%</td>
			<td align="right">'.duit($metthismonth['total_premi_asuransi_last_month']).'</td>
			<td align="center">'.duitdollar($metthismonth['persentase_last_month']).'%</td>
			<td align="right">'.duit($metthismonth['total_premi_asuransi']).'</td>
			<td align="center">'.duitdollar($metthismonth['persentase']).'%</td>
			</tr>';
            }
            echo '</table><br /><br />';
            if ($met > 0) {
                echo '<center><font size="4">Ada <b>'.$met.' peserta</b> yang harus di buatkan Debit Note</font></center>';
                echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
			<tr><th width="1%">No</th>
				<th>Nama Perusahaan</th>
				<th width="10%">Nama Produk</th>
				<th width="10%">Total Premi Bank</th>
				<th width="10%">Total Data</th>
				<th width="20%">Cabang</th>
				<th width="10%">User Upload</th>
				<th width="10%">Tgl Upload</th>
			</tr>
			<tr></tr>';
                $data1 = $database->doQuery('SELECT *, count(id_polis) AS jum,sum(totalpremi)as premi FROM fu_ajk_peserta WHERE id_cost !="" AND id_polis !="" AND id_dn="" AND  status_aktif="Approve" AND DEL IS NULL GROUP BY input_time, input_by ORDER BY input_time DESC');
                while ($metdatadn = mysql_fetch_array($data1)) {
                    $datacost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metdatadn['id_cost'].'"'));
                    $datapolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metdatadn['id_polis'].'"'));
                    if (($no % 2) == 1) {
                        $objlass = 'tbl-odd';
                    } else {
                        $objlass = 'tbl-even';
                    }
                    echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.++$no.'</td>
			  <td><a href="ajk_dn.php?r=views&idCost='.$datacost['id'].'&idPolis='.$datapolis['id'].'&nmUser='.$metdatadn['input_by'].'&tglUser='.$metdatadn['input_time'].'">'.$datacost['name'].'</a></td>
			  <td align="center">'.$datapolis['nmproduk'].'</td>
			  <td align="center">'.duit($metdatadn['premi']).'</td>
			  <td align="center">'.$metdatadn['jum'].'</td>
			  <td>'.$metdatadn['cabang'].'</td>
			  <td align="center">'.$metdatadn['input_by'].'</td>
			  <td align="center">'.$metdatadn['input_time'].'</td>
			  </tr>';
                }
                echo '</table>';
            } else {
                echo '<center>Data Kosong.</center>';
            }
        }
        echo '<!--WILAYAH COMBOBOX-->
		<script src="javascript/metcombo/prototype.js"></script>
		<script src="javascript/metcombo/dynamicombo.js"></script>
		<!--WILAYAH COMBOBOX-->
		<script>
		document.observe("dom:loaded",function(){
			new DynamiCombo( "id_cost" , {
				elements:{
					"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
					"id_asuransi":	{url:\'javascript/metcombo/data.php?req=setpolisasuransi\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_asuransi"] ?>\'},
					"user_upload":	{url:\'javascript/metcombo/data.php?req=setuserupload\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["user_upload"] ?>\'},
					"tgl_upload":	{url:\'javascript/metcombo/data.php?req=settglupload\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["tgl_upload"] ?>\'},
				},
				loadingImage:\'../loader1.gif\',
				loadingText:\'Loading...\',
				debug:0
			} )
		});
		</script>';
            ;
} // switch

?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_dn.php?cat=' + val;
}
</script>

<SCRIPT language=JavaScript>
function reloaddn(form)
{
	var val=form.id_cost.options[form.id_cost.options.selectedIndex].value;
	self.location='ajk_dn.php?r=viewdn&id_cost=' + val;
}
</script>

<!--CHECKE ALL-->
<SCRIPT language="javascript">
$(function(){
    $("#selectall").click(function () {	$('.case').attr('checked', this.checked);	});			    // add multiple select / deselect functionality
    $(".case").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });
});
</SCRIPT>
