<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
switch ($_REQUEST['er']) {
	case "s":
		;
		break;

	case "pinjaman":
		$qcabang = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_cabang where name = '".$q['cabang']."' and del is NULL"));
		$qcabangcntrl = "SELECT name FROM fu_ajk_cabang where centralcbg = '".$qcabang['id']."' and del is NULL";
		$qcntrl = mysql_query("SELECT * FROM fu_ajk_cabang where centralcbg = '".$qcabang['id']."' and del is NULL");

		echo '<table border="1" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Nomor Pinjaman</font></th></tr></table>';
		$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));		
		$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND typeproduk="SPK" AND ('.$mametProdukUser.') AND del IS NULL ORDER BY nmproduk ASC');
		echo '<form method="post" action="">
						<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
						<table border="0" cellpadding="1" cellspacing="0" width="100%">
							<tr>
								<td width="10%">Nama Perusahaan</td><td>: '.$metcost['name'].'</td>
							</tr>
							<tr>
								<td>Cabang</td>
			  	    	<td> : ';
									if(mysql_num_rows(mysql_query($qcabangcntrl))>1){
										echo '<select name="selectcabang">
						  	    				<option value = "all">All</option>';
						  	    while($cabang = mysql_fetch_array($qcntrl)){
						  	    	if($_REQUEST['selectcabang']==$cabang['name']){
						  	    		$isselected = 'selected';
						  	    	}else{
						  	    		$isselected = '';
						  	    	}
						  	    	echo '<option value = "'.$cabang['name'].'" '.$isselected.'>'.$cabang['name'].'</option>';
						  	    }
						  	    echo '</select>';
									}else{
										echo $q['cabang'];
									}
		echo	    	'</td>
							</tr>							
							<tr>
								<td>Nomor Pinjaman</td>
			  	    	<td> : 
			  	    		<select name="jnspinjaman">
			  	    			<option value = "all">All</option>
			  	    			<option value = "outstanding">Blank</option>
			  	    			<option value = "notblank">Not Blank</option>
			  	    		</select>
			  	    	</td>
							</tr>
							<tr><td>Tanggal Mulai Asuransi</td>
								<td> : <input type="text" name="tglakad1" id="tglakad1" class="tanggal" value="'.$_REQUEST['tglakad1'].'" size="10"/> s/d
									  <input type="text" name="tglakad2" id="tglakad2" class="tanggal" value="'.$_REQUEST['tglakad2'].'" size="10"/>
								</td>
							</tr>							
							<tr>
								<td>No SPK/SKKT</td>
			  	    	<td> : <input type="text" name="spaj" value="'.$_REQUEST['spaj'].'"></td>
							</tr>
						  <tr>
						  	<td colspan="2"><input type="hidden" name="re" value="datapinjaman"><input type="submit" name="ere" value="Cari"></td>
						  </tr>
						  </table>
				  </form>';
		if ($_REQUEST['re']=="datapinjaman") {
			if($_REQUEST['jnspinjaman'] == 'outstanding'){
				$jnspinjaman = " and ifnull(nopinjaman,'') = ''";	
			}elseif($_REQUEST['jnspinjaman'] == 'notblank'){
				$jnspinjaman = " and ifnull(nopinjaman,'') != ''";	
			}else{
				$jnspinjaman = "";
			}


			if($_REQUEST['tglakad1'] != '' and $_REQUEST['tglakad2'] != ''){
				$date = " and kredit_tgl BETWEEN '".$_REQUEST['tglakad1']."' and '".$_REQUEST['tglakad2']."'";
			}else{
				$date = "";
			}

			//cek cabang central
			$qcabang = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_cabang where name = '".$q['cabang']."' and del is NULL"));
			$qcabangcntrl = "SELECT name FROM fu_ajk_cabang where centralcbg = '".$qcabang['id']."' and del is NULL";

			if($_REQUEST['selectcabang'] != ''){
				if($_REQUEST['selectcabang'] == 'all'){
					if(mysql_num_rows(mysql_query($qcabangcntrl))>1){
						$cabang = " (fu_ajk_peserta.cabang in(".$qcabangcntrl.") OR fu_ajk_peserta.cabang = '".$q['cabang']."')";
					}else{
						$cabang = " fu_ajk_peserta.cabang = '".$q['cabang']."'";
					}
				}else{
					$cabang = " fu_ajk_peserta.cabang = '".$_REQUEST['selectcabang']."'";
				}				
			}else{
				$cabang = " fu_ajk_peserta.cabang = '".$q['cabang']."'";
			}

			if($_REQUEST['spaj'] != ''){
				$spak = " AND fu_ajk_peserta.spaj = '".$_REQUEST['spaj']."'";
			}else{
				$spak = "";
			}

			if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
			$query = "SELECT fu_ajk_dn.dn_kode,
											 fu_ajk_dn.tgl_createdn,
											 fu_ajk_peserta.id_peserta,
											 fu_ajk_peserta.nama,
											 fu_ajk_peserta.cabang,
											 fu_ajk_peserta.spaj,
											 fu_ajk_peserta.tgl_lahir,
											 fu_ajk_peserta.usia,
											 fu_ajk_peserta.kredit_jumlah,
											 fu_ajk_peserta.kredit_tgl,
											 fu_ajk_peserta.kredit_tenor,
											 fu_ajk_peserta.kredit_akhir,
											 fu_ajk_peserta.premi,
											 fu_ajk_peserta.ext_premi,
											 fu_ajk_peserta.totalpremi,
											 fu_ajk_peserta.nopinjaman,
											 fu_ajk_peserta.sumberdana,
											 fu_ajk_polis.nmproduk
								FROM fu_ajk_peserta
										 INNER JOIN fu_ajk_dn
										 ON fu_ajk_dn.id = fu_ajk_peserta.id_dn		 
										 INNER JOIN fu_ajk_polis
										 ON fu_ajk_polis.id = fu_ajk_peserta.id_polis
								WHERE fu_ajk_peserta.del is NULL and
											fu_ajk_dn.del is NULL and
											".$cabang."
											".$jnspinjaman."
											".$spak."
											".$date."";
				
			$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
			echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
						<tr><td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_pinjaman_peserta&jnspinjaman='.$_REQUEST['jnspinjaman'].'&selectcabang='.$_REQUEST['selectcabang'].'&spaj='.$_REQUEST['spaj'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&user='.$q['nm_user'].'" target="_blank"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
						<tr>
							<th width="1%">No</th>
							<th>Produk</th>
							<th>Debit Note</th>
							<th>Tanggal DN</th>
							<th>No. Reg</th>
							<th>Nama Debitur</th>
							<th>No SPK/SKKT</th>
							<th>Cabang</th>
							<th>Tgl Lahir</th>
							<th>Usia</th>
							<th>Plafond</th>
							<th>Tanggal Akad</th>
							<th>Tenor</th>
							<th>Tanggal Akhir</th>							
							<th>Premi</th>
							<th>EM</th>		
							<th>Total Premi</th>
							<th>No Pinjaman</th>
							<th>Sumber Dana</th>
							<th>Option</th>
						</tr>';			
			
			$qpeserta = mysql_query($query."ORDER BY tgl_createdn DESC LIMIT ".$m.", 25");
			$totalRows = mysql_num_rows(mysql_query($query));
			while($peserta_ = mysql_fetch_array($qpeserta)){
				if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';			
				echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
							  <td align="center">'.$peserta_['nmproduk'].'</td>
							  <td align="center">'.$peserta_['dn_kode'].'</td>
							  <td align="center">'._convertDate($peserta_['tgl_createdn']).'</td>
							  <td>'.$peserta_['id_peserta'].'</td>
							  <td>'.$peserta_['nama'].'</td>
							  <td align="center">'.$peserta_['spaj'].'</td>
							  <td>'.$peserta_['cabang'].'</td>
							  <td align="center">'._convertDate($peserta_['tgl_lahir']).'</td>
							  <td align="center">'.$peserta_['usia'].'</td>
							  <td align="right">'.duit($peserta_['kredit_jumlah']).'</td>
							  <td align="center">'._convertDate($peserta_['kredit_tgl']).'</td>
							  <td align="center">'.$peserta_['kredit_tenor'].'</td>
							  <td align="center">'._convertDate($peserta_['kredit_akhir']).'</td>						  
							  <td align="right">'.duit($peserta_['premi']).'</td>
							  <td align="right">'.duit($peserta_['ext_premi']).'</td>						  
							  <td align="right">'.duit($peserta_['totalpremi']).'</td>
							  <td align="center">'.$peserta_['nopinjaman'].'</td>
							  <td align="center">'.$peserta_['sumberdana'].'</td>
							  <td align="center"><a href="er_peserta.php?er=editpinjaman&id='.$peserta_['id_peserta'].'" title="edit"><img src="image/edit3.png" width="20"></a></td>
						  </tr>';							
			}
			echo '<tr><td colspan="22">';
			echo createPageNavigations($file = 'er_peserta.php?er=pinjaman&re=datapinjaman&jnspinjaman='.$_REQUEST['jnspinjaman'].'&selectcabang='.$_REQUEST['selectcabang'].'&spaj='.$_REQUEST['spaj'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
			echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
			echo '</table>';	
		}
	break;

	case "editpinjaman":	//FORM EDIT		
		$qpeserta = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_peserta WHERE id_peserta = '".$_REQUEST['id']."'"));

		if($qpeserta['sumberdana']=="Taspen"){
			$taspen = 'selected';
		}else{
			$lainnya = 'selected';
		}

		echo'	<br><table border="0" cellpadding="5" cellspacing="0" width="100%">
								<tr>
									<th width="95%" align="left">Edit</font></th>					
								</tr>				
							</table>';
		echo '<br><table border="0" width="100%" cellpadding="1" cellspacing="1">
								<form method="post" name="frm_edit_deklarasi" action="er_peserta.php?er=edit&id='.$_REQUEST['id'].'" enctype="multipart/form-data">
									<tr>
										<td align="right" width="7%">No Pinjaman : </td>																				
										<td><input type="number" name="nopinjaman" value="'.$qpeserta['nopinjaman'].'" oninput="maxLengthCheck(this)" maxlength = "10" min = "1000000000" max = "9999999999"></td>
									</tr>
									<tr>
										<td align="right" width="7%">Sumber Dana : </td>																				
										<!--<td><input type="text" name="sumberdana" value="'.$qpeserta['sumberdana'].'"</td>-->
										<td>
											<select name="sumberdana">
												<option value="Taspen" '.$taspen.'>Taspen</option>
												<option value="Lainnya" '.$lainnya.'>Lainnya</option>
											</select>
										</td>
									</tr>									
									<tr>
										<td colspan="3"></td>
									</tr>
									<td colspan="3" align="center">
										<button type="submit" class="button" style="text-align:center">Submit</button>						
									</td>										
								</form>				
							</table>';	
	break;

	case "edit": //EXECUTE EDIT 
		$id_peserta = $_REQUEST['id'];
		$sumberdana = $_REQUEST['sumberdana'];
		$nopinjaman = $_REQUEST['nopinjaman'];

		
		$query = "UPDATE fu_ajk_peserta 
							set nopinjaman='".$nopinjaman."',
									sumberdana = '".$sumberdana."'
							WHERE id_peserta = '".$id_peserta."'";

		mysql_query($query) or die("<br><br>Error message = ".mysql_error().$query);
		echo 		'<br><br><center>Data Telah Diubah</center><meta http-equiv="refresh" content="5; url=er_peserta.php?er=pinjaman">';			
	break;

	case "produksi":
		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Data Produksi</font></th></tr></table>';

		$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));		

		if ($q['id_cost']!="" AND $q['id_polis']=="" AND $q['status']=="SUPERVISOR-ADMIN") {
			//SETTING PRODUK BERDASARKAN USER//
			$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND id_cost="'.$q['id_cost'].'" AND del IS NULL ORDER BY nmproduk ASC');
			$kolomproduk .= '<select name="id_produk" id="id_produk"><option value="">-- Pilih Produk --</option>';
			$mamet_produk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');

			while ($mamet_produk_ = mysql_fetch_array($mamet_produk)) {
				$kolomproduk .= '<option value="'.$mamet_produk_['id'].'"'._selected($_REQUEST['id_produk'], $mamet_produk_['id']).'>'.$mamet_produk_['nmproduk'].'</option>';
			}
			$kolomproduk .= '</select>';
			$setproduk_met .= $_REQUEST['id_produk'];
			//SETTING PRODUK BERDASARKAN USER//

			//SETTING REGIONAL BERDASARKAN USER//
			$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['wilayah'].'" AND del IS NULL'));
			$kolomregional .='<td>: '.$met_reg['name'].'</td>';

			$met_cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$met_reg['id'].'"');
			$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option>';

			while ($met_cab_ = mysql_fetch_array($met_cab)) {
				$kolomcabang .='<option value="'.$met_cab_['id'].'"'._selected($_REQUEST['id_cab'], $met_cab_['id']).'>'.$met_cab_['name'].'</option>';
			}
			$kolomcabang .='</select></td>';
			//SETTING REGIONAL BERDASARKAN USER//
			}
		elseif ($q['id_cost']!="" AND $q['id_polis']!="" AND $q['status']=="SUPERVISOR") {
			//SETTING PRODUK BERDASARKAN USER//
			$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
			$kolomproduk .= '<select name="id_produk" id="id_produk"><option value="">-- Pilih Produk --</option>';
			$mamet_produk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');

			while ($mamet_produk_ = mysql_fetch_array($mamet_produk)) {
				$kolomproduk .= '<option value="'.$mamet_produk_['id'].'"'._selected($_REQUEST['id_produk'], $mamet_produk_['id']).'>'.$mamet_produk_['nmproduk'].'</option>';
			}

			$kolomproduk .= '</select>';
			$setproduk_met .= $_REQUEST['id_produk'];
			//SETTING PRODUK BERDASARKAN USER//

			//SETTING REGIONAL BERDASARKAN USER//
			$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['wilayah'].'" AND del IS NULL');
			$kolomregional .='<td id="polis_rate">: <select name="id_reg" id="id_reg">
				<option value="">-- Pilih Regional --</option>';

			while ($_met_reg = mysql_fetch_array($met_reg)) {
				$kolomregional .='<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
			}

			$kolomregional .='</select></td>';

			$met_cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$met_reg['id'].'"');
			$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option>';

			while ($met_cab_ = mysql_fetch_array($met_cab)) {
				$kolomcabang .='<option value="'.$met_cab_['id'].'"'._selected($_REQUEST['id_cab'], $met_cab_['id']).'>'.$met_cab_['name'].'</option>';
			}

			$kolomcabang .='</select></td>';
			//SETTING REGIONAL BERDASARKAN USER//
		}else{
			$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL AND typeproduk="NON SPK" AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
			//SETTING REGIONAL BERDASARKAN USER//
			$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['wilayah'].'" AND del IS NULL');
			$kolomregional .='<td id="polis_rate">: <select name="id_reg" id="id_reg">

					<option value="">-- Pilih Regional --</option>';
			while ($_met_reg = mysql_fetch_array($met_reg)) {
				$kolomregional .='<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
			}
			$kolomregional .='</select></td>';

			$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab">
					<option value="">-- Pilih Cabang --</option>
					</select>';
			//SETTING REGIONAL BERDASARKAN USER//
		}

		if ($q['cabang']=="PUSAT") {
			$userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
			$_userCabang .= '<select id="id_cost" name="subcat"> <option value="">--- Pilih ---</option>';

			while($userCabang_ = mysql_fetch_array($userCabang)) {
				$_userCabang .= '<option value="'.$userCabang_['name'].'"'._selected($_REQUEST['subcat'], $userCabang_['name']).'>'.$userCabang_['name'].'</option>';
			}

			$_userCabang .= '</select>';
			$QueryCabang = 'AND cabang !=""';			
		}else{
			$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name = "'.$q['cabang'].'" and del is NULL'));
			$_userCabang = $q['cabang'];
			$QueryCabang = 'AND cabang ="'.$q['cabang'].'"';
			$QueryInput = 'AND input_by ="'.$q['nm_user'].'"';
		}

		echo '<table border="0" cellpadding="1" cellspacing="1" width="40%" align="center">
						<form method="post" action="" name="postform">
							<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
							<input type="hidden" name="id_cab" value="'.$met_cab['id'].'">
							<tr><td width="20%">Nama Perusahaan</td><td>: '.$metcost['name'].'</td></tr>
							<tr><td>Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
							<tr><td>Nama Produk</td><td>: 
							<select name="idpolis">
								<option value="">---Pilih Produk---</option>';
								while($met_polis_ = mysql_fetch_array($met_polis)) {
									echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
								}
						echo '</select></td></tr>
							<tr>
								<td>Tanggal Debitnote <font color="red">*</font></td>
								<td> : <input type="text" name="tgldn1" id="tgldn1" class="tanggal" value="'.$_REQUEST['tgldn1'].'" size="10"/> s/d <input type="text" name="tgldn2" id="tgldn2" class="tanggal" value="'.$_REQUEST['tgldn2'].'" size="10"/>
								</td>
							</tr>
							<tr>
								<td>Status Pembayaran </td><td> :
									<select size="1" name="paiddata">
										<option value="">--- Status ---</option>
										<option value="1"'._selected($_REQUEST['paiddata'], "1").'>Paid</option>
										<option value="0"'._selected($_REQUEST['paiddata'], "0").'>Unpaid</option>
									</select>
								</td>
							</tr>
							<tr><td>Status Peserta </td><td> :
								<select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
								  <option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Inforce</option>
								  <option value="Lapse"'._selected($_REQUEST['statpeserta'], "Lapse").'>Lapse</option>
								  <option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
								  <option value="Batal"'._selected($_REQUEST['statpeserta'], "Batal").'>Batal</option>
								</select>
							</td></tr>

							<tr><td></td><td colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>						
						</form>
					</table>';

		if ($_REQUEST['re']=="datapeserta") {
			if ($_REQUEST['tgldn1']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal awal Debitnote tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tgldn2']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal akhir Debitnote tidak boleh kosong</div></font></blink>';	}
			if ($error_1 OR $error_2) {	echo $error_1 .''.$error_2;	
			}else{

				if ($_REQUEST['tgldn1'])		{	$satu = 'AND fu_ajk_dn.tgl_createdn BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'" ';	}
				if ($_REQUEST['paiddata']=="0" or $_REQUEST['paiddata']=="1"){
					$dua = 'AND fu_ajk_peserta.status_bayar = "'.$_REQUEST['paiddata'].'"';						
				}
				if ($_REQUEST['statpeserta'])	{	$tiga = 'AND status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
				if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
					$empat = 'AND regional = "'.$met_reg['name'].'"';
				}
				if ($_REQUEST['id_cab'])		{ 	
					//buat sentralisasi					
					$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
					$central_cab = mysql_num_rows($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$_REQUEST['id_cab'].'"'));

					if($central_cab > 0){												
						$lima = 'AND cabang in (SELECT name FROM fu_ajk_cabang WHERE centralcbg="'.$_REQUEST['id_cab'].'")';
					}else{
						$lima = 'AND cabang = "'.$met_cab['name'].'"';
					}					
				}

				$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));

				if ($_REQUEST['idpolis']=="") {
					$searchproduk_ = "SEMUA PRODUK";
				}else{
					$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'"'));
					$searchproduk_ = $searchproduk['nmproduk'];
					$enam = 'AND fu_ajk_peserta.id_polis = "'.$_REQUEST['idpolis'].'"';
				}

				if ($_REQUEST['paiddata']=="1") {	$searchpaid = "PAID";	}
				elseif ($_REQUEST['paiddata']=="0") {	$searchpaid = "UNPAID";	}
				else{	$searchpaid = "SEMUA STATUS PEMBAYARAN";	}

				if ($_REQUEST['statpeserta']!="") {	$searchpaidstatus = $_REQUEST['statpeserta'] ;	}	else{	$searchpaidstatus = "SEMUA STATUS PESERTA";	}
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="16"><a href="aajk_report.php?er=eL_peserta_produksi&cat='.$q['id_cost'].'&subcat='.$_REQUEST['idpolis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>					
					<tr><td colspan="2">Nama Perusahaan</td><td colspan="14">: '.$searchbank['name'].'</td></tr>
					<tr><td colspan="2">Nama Produk</td><td colspan="14">: '.$searchproduk_.'</td></tr>
					<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tgldn1']).' s/d '._convertDate($_REQUEST['tgldn2']).'</td></tr>
					<tr><td colspan="2">Status Pembayaran</td><td colspan="14">: '.$searchpaid.'</td></tr>
					<tr><td colspan="2">Status Peserta</td><td colspan="14">: '.$searchpaidstatus.'</td></tr>
					<tr>
						<th width="1%">No</th>
						<th>Cabang</th>
						<th>Produk</th>
						<th>Nomor DN</th>
						<th>Tgl DN</th>
						<th>ID Peserta</th>
						<th>Nama Debitur</th>
						<th>Tgl Lahir</th>
						<th>Usia</th>
						<th>Plafond</th>
						<th>Jk.W</th>
						<th>Mulai Asuransi</th>
						<th>Akhir Asuransi</th>
						<th>EM</th>												
						<th>Total Premi</th>
					</tr>';		
					$query = "SELECT  fu_ajk_polis.nmproduk,
														 fu_ajk_dn.dn_kode,
														 fu_ajk_dn.tgl_createdn,
														 fu_ajk_peserta.id_peserta,
														 fu_ajk_peserta.nama,
														 fu_ajk_peserta.cabang,
														 fu_ajk_peserta.tgl_lahir,
														 fu_ajk_peserta.usia,
														 fu_ajk_peserta.kredit_jumlah,
														 fu_ajk_peserta.kredit_tenor,
														 fu_ajk_peserta.kredit_tgl,
														 fu_ajk_peserta.kredit_akhir,
														 fu_ajk_peserta.ext_premi,
														 fu_ajk_peserta.totalpremi
											FROM fu_ajk_peserta
													 INNER JOIN fu_ajk_dn
													 on fu_ajk_dn.id = fu_ajk_peserta.id_dn
													 INNER JOIN fu_ajk_polis
													 on fu_ajk_polis.id = fu_ajk_peserta.id_polis
											WHERE fu_ajk_peserta.del is NULL and
														fu_ajk_dn.del is NULL and
														fu_ajk_polis.del is NULL AND 
														fu_ajk_peserta.id_cost='".$q['id_cost']."' ".$satu." ".$dua." ".$tiga." ".$empat." ".$lima." ".$enam." ";
					$query_order = "ORDER BY tgl_createdn ASC LIMIT ".$m.", 25";

					//echo "$query $query_order";
					$met = $database->doQuery("$query $query_order");

					$totalRows = mysql_num_rows(mysql_query($query));
					//$totalRows = $totalRows[0];
					$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;					
					while ($met_ = mysql_fetch_array($met)) {
						$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));

						if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
						echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
								  	<td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
								  	<td align="center">'.$met_['cabang'].'</td>
								 		<td align="center">'.$met_['nmproduk'].'</td>
								 		<td align="center">'.$met_['dn_kode'].'</td>
								 		<td align="center">'.$met_['tgl_createdn'].'</td>
								 		<td align="center">'.$met_['id_peserta'].'</td>
								 		<td>'.$met_['nama'].'</td>
								 		<td align="center">'.$met_['tgl_lahir'].'</td>
								 		<td align="center">'.$met_['usia'].'</td>
								 		<td align="center">'.duit($met_['kredit_jumlah']).'</td>
								 		<td align="center">'.$met_['kredit_tenor'].'</td>
								 		<td align="center">'.$met_['kredit_tgl'].'</td>
								 		<td align="center">'.$met_['kredit_akhir'].'</td>
								 		<td align="center">'.duit($met_['ext_premi']).'</td>
								 		<td align="center">'.duit($met_['totalpremi']).'</td>
								  </tr>';
							$jumUP += $met_['kredit_jumlah'];
							$jumPremi += ROUND($met_['totalpremi']);
					}					
					echo '<tr class="tr1">
									<td colspan="8">Total</td>
									<td align="center">'.duit($jumUP).'</td>
									<td colspan="4"></td>
									<td align="center">'.duit($jumPremi).'</td>
								</tr>';
					echo '<tr><td colspan="22">';
					echo createPageNavigations($file = 'er_peserta.php?er=produksi&re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&idpolis='.$_REQUEST['idpolis'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'&paiddata='.$_REQUEST['paiddata'].'&statpeserta='.$_REQUEST['statpeserta'].'&id_cab='.$_REQUEST['id_cab'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
					echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
					echo '</table>';					
			}
		}
	break;

	case "spk":
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Data SPK</font></th></tr></table>';
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
//$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND typeproduk="SPK" AND ('.$mametProdukUser.') AND del IS NULL ORDER BY nmproduk ASC');
echo '<form method="post" action="">
		<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
		<table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr><td width="10%">Nama Perusahaan</td><td>: '.$metcost['name'].'</td></tr>
		<!--<tr><td align="right">Nama Produk</td><td>: '.$metpolis['nmproduk'].' ('.$metpolis['nopol'].')</td></tr>-->
	    <tr><td>Nama Produk</td><td>: <select name="nmproduk">
	    <option value="">---Pilih Produk---</option>';
while($met_polis_ = mysql_fetch_array($met_polis)) {
echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['nmproduk'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
}
echo '</select></td></tr>
		<tr><td>Tanggal Pemeriksaan <font color="red">*</font></td>
	  	    <td> : <input type="text" name="tglcheck1" id="tglcheck1" class="tanggal" value="'.$_REQUEST['tglcheck1'].'" size="10"/> s/d
				  <input type="text" name="tglcheck2" id="tglcheck2" class="tanggal" value="'.$_REQUEST['tglcheck2'].'" size="10"/>
			</td>
		</tr>
	  <tr><td colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['tglcheck1']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal mulai periksa tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglcheck2']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai periksa tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2) {	echo $error_1 .''.$error_2;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF"colspan="23"><a href="e_report.php?er=eL_spk&cat='.$q['id_cost'].'&subcat='.$_REQUEST['nmproduk'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&deb='.$q['id'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
			<th width="1%">No</th>
			<th>Nama Debitur</th>
			<th>Cabang</th>
			<th>No. SPK</th>
			<th>Tgl Pemeriksaan</th>
			<th>Tgl Terima SPK</th>
			<th>Tgl Lahir</th>
			<th>Usia Awal</th>
			<th>Usia Akhir</th>
			<th>Plafond</th>
			<th>Tenor</th>
			<th>TB</th>
			<th>BB</th>
			<th>SISTOLIK</th>
			<th>DIASTOLIK</th>
			<th>NADI</th>
			<th>PERNAFASAN</th>
			<th>GULA DARAH</th>
			<th>ITEM MEROKOK</th>
			<th>ITEM PERTANYAAN</th>
			<th>CATATAN SKS</th>
			<th>STATUS</th>
			<th>ANALISA DOKTER</th>
			</tr>';
//if ($_REQUEST['id_cost'])		{	$satu = 'AND fu_ajk_spak.id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['nmproduk'])		{	$satu = 'AND fu_ajk_spak.id_polis = "'.$_REQUEST['nmproduk'].'"';	}
if ($_REQUEST['tglcheck1'])		{	$duaa = 'AND fu_ajk_spak_form.tgl_periksa BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$_REQUEST['tglcheck2'].'" ';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang .= ' OR fu_ajk_spak_form.cabang ="'.$cekCentral__['id'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {

}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.id AS idcab, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral .= 'OR (fu_ajk_spak_form.cabang ="'.$cekCentral__['idcab'].'")';
	}
	$metCabangCentral = 'AND (fu_ajk_spak_form.cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';

}else{
	$met_cab_ = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
	if ($metCentralCabang=="") {
		$metCabangCentral = 'AND fu_ajk_spak_form.cabang ="'.$met_cab_['id'].'"';
	}else{
		$metCabangCentral = 'AND (fu_ajk_spak_form.cabang ="'.$met_cab_['id'].'" '.$metCentralCabang.')';
	}
}
//CEK DATA CABANG CENTRAL;

//$met = $database->doQuery('SELECT * FROM fu_ajk_spak_form WHERE id !="" AND del is NULL '.$metCabangCentral.' '.$satu.' '.$duaa.' ORDER BY tgl_periksa ASC LIMIT '.$m.', 25');
$met = $database->doQuery('SELECT
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_spak.spak,
fu_ajk_spak_form.input_date,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
(fu_ajk_spak_form.x_usia + fu_ajk_spak_form.tenor) AS usiaakhir,
fu_ajk_spak_form.plafond,
(fu_ajk_spak_form.tenor * 12) AS tenor,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.catatan,
fu_ajk_spak.`status` AS statusSPK,
fu_ajk_spak_form.kesimpulan,
fu_ajk_spak_form.tgl_periksa
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE fu_ajk_spak.id !="" AND fu_ajk_spak.del is NULL  AND fu_ajk_spak_form.del is NULL AND fu_ajk_spak.id_cost="'.$q['id_cost'].'" '.$metCabangCentral.' '.$satu.' '.$duaa.'
ORDER BY fu_ajk_spak_form.tgl_periksa ASC, fu_ajk_spak.id_polis ASC LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) FROM fu_ajk_spak INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk WHERE fu_ajk_spak.id !="" AND fu_ajk_spak.del is NULL  AND fu_ajk_spak_form.del is NULL AND fu_ajk_spak.id_cost="'.$q['id_cost'].'" '.$metCabangCentral.' '.$satu.' '.$duaa.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	//$cekdataspak = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$met_['idspk'].'"'));
	$tgl_terima_spak = explode(" ", $met_['input_date']);
	$tolik = explode("/", $met_['tekanandarah']);
	//$cekdatadn = mysql_fetch_array($database->doQuery('SELECT dn_kode, tgl_createdn FROM fu_ajk_dn WHERE dn_kode="'.$met_['id_dn'].'"'));

	//$umur = ceil(((strtotime($met_['tgl_asuransi']) - strtotime($met_['dob'])) / (60*60*24*365.2425)));									// FORMULA USIA
	//$umur_last = $umur + $met_['tenor'];

if ($met_['pertanyaan6']=="T") {	$pertanyaan6 = "Tidak";	}else{	$pertanyaan6 = "Iya";	}

if (is_numeric($met_['cabang'])) {
$metCbgSPK = mysql_fetch_array(mysql_query('SELECT * FROM fu_ajk_cabang WHERE id="'.$met_['cabang'].'" '));
$metCbgSPK_ = $metCbgSPK['name'];
}else{
$metCbgSPK_ = $met_['cabang'];
}

if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			  <td>'.$met_['nama'].'</td>
			  <td>'.$metCbgSPK_.'</td>
			  <td align="center">'.$met_['spak'].'</td>
			  <td align="center">'._convertDate($met_['tgl_periksa']).'</td>
			  <td align="center">'._convertDate($tgl_terima_spak[0]).'</td>
			  <td align="center">'._convertDate($met_['dob']).'</td>
			  <td align="center">'.$met_['x_usia'].'</td>
			  <td align="center">'.$met_['usiaakhir'].'</td>
			  <td align="center">'.duit($met_['plafond']).'</td>
			  <td align="center">'.$met_['tenor'].'</td>
			  <td align="center">'.$met_['tinggibadan'].'</td>
			  <td align="center">'.$met_['beratbadan'].'</td>
			  <td align="center">'.$tolik[0].'</td>
			  <td align="center">'.$tolik[1].'</td>
			  <td align="center">'.$met_['nadi'].'</td>
			  <td align="center">'.$met_['pernafasan'].'</td>
			  <td align="center">'.$met_['guladarah'].'</td>
			  <td>'.$pertanyaan6.'</td>
			  <td>'.$met_['ket6'].'</td>
			  <td>'.$met_['catatan'].'</td>
			  <td>'.$met_['statusSPK'].'</td>
			  <td>'.$met_['kesimpulan'].'</td>
			  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'er_peserta.php?er=spk&re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&nmproduk='.$_REQUEST['nmproduk'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
		;
		break;
	default:
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Laporan Data Kepesertaan</font></th></tr></table>';
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
//$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));

if ($q['id_cost']!="" AND $q['id_polis']=="" AND $q['status']=="SUPERVISOR-ADMIN") {
	//SETTING PRODUK BERDASARKAN USER//
	$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND id_cost="'.$q['id_cost'].'" AND del IS NULL ORDER BY nmproduk ASC');
		$kolomproduk .= '<select name="id_produk" id="id_produk"><option value="">-- Pilih Produk --</option>';
		$mamet_produk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');
		while ($mamet_produk_ = mysql_fetch_array($mamet_produk)) {
		$kolomproduk .= '<option value="'.$mamet_produk_['id'].'"'._selected($_REQUEST['id_produk'], $mamet_produk_['id']).'>'.$mamet_produk_['nmproduk'].'</option>';
		}
		$kolomproduk .= '</select>';
		$setproduk_met .= $_REQUEST['id_produk'];
	//SETTING PRODUK BERDASARKAN USER//

	//SETTING REGIONAL BERDASARKAN USER//
	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['wilayah'].'" AND del IS NULL'));
	$kolomregional .='<td>: '.$met_reg['name'].'</td>';

	$met_cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$met_reg['id'].'" and del is null');
	$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option>';
	while ($met_cab_ = mysql_fetch_array($met_cab)) {
	$kolomcabang .='<option value="'.$met_cab_['id'].'"'._selected($_REQUEST['id_cab'], $met_cab_['id']).'>'.$met_cab_['name'].'</option>';
	}
	$kolomcabang .='</select></td>';
	//SETTING REGIONAL BERDASARKAN USER//
}
elseif ($q['id_cost']!="" AND $q['id_polis']!="" AND $q['status']=="SUPERVISOR") {
	//SETTING PRODUK BERDASARKAN USER//
	$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
		$kolomproduk .= '<select name="id_produk" id="id_produk"><option value="">-- Pilih Produk --</option>';
		$mamet_produk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'"');
		while ($mamet_produk_ = mysql_fetch_array($mamet_produk)) {
			$kolomproduk .= '<option value="'.$mamet_produk_['id'].'"'._selected($_REQUEST['id_produk'], $mamet_produk_['id']).'>'.$mamet_produk_['nmproduk'].'</option>';
		}
		$kolomproduk .= '</select>';
		$setproduk_met .= $_REQUEST['id_produk'];
		//SETTING PRODUK BERDASARKAN USER//

		//SETTING REGIONAL BERDASARKAN USER//
		$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['wilayah'].'" AND del IS NULL');
		$kolomregional .='<td id="polis_rate">: <select name="id_reg" id="id_reg">
			<option value="">-- Pilih Regional --</option>';
	while ($_met_reg = mysql_fetch_array($met_reg)) {
		$kolomregional .='<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
	}
		$kolomregional .='</select></td>';

		$met_cab = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND id_reg="'.$met_reg['id'].'"');
		$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option>';
	while ($met_cab_ = mysql_fetch_array($met_cab)) {
		$kolomcabang .='<option value="'.$met_cab_['id'].'"'._selected($_REQUEST['id_cab'], $met_cab_['id']).'>'.$met_cab_['name'].'</option>';
	}
		$kolomcabang .='</select></td>';
		//SETTING REGIONAL BERDASARKAN USER//
}
else{
	//	$kolomproduk .= $metpolis['nmproduk'];
	//	$setproduk_met .= $q['id_polis'];
	//$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL AND typeproduk="NON SPK" AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
		$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id!="" AND del IS NULL AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
	//SETTING REGIONAL BERDASARKAN USER//
	$met_reg = $database->doQuery('SELECT * FROM fu_ajk_regional WHERE id_cost="'.$q['id_cost'].'" AND name="'.$q['wilayah'].'" AND del IS NULL');
	$kolomregional .='<td id="polis_rate">: <select name="id_reg" id="id_reg">
			<option value="">-- Pilih Regional --</option>';
	while ($_met_reg = mysql_fetch_array($met_reg)) {
	$kolomregional .='<option value="'.$_met_reg['id'].'"'._selected($_REQUEST['id_reg'], $_met_reg['id']).'>'.$_met_reg['name'].'</option>';
	}
	$kolomregional .='</select></td>';

	$kolomcabang .='<td id="polis_rate">: <select name="id_cab" id="id_cab">
			<option value="">-- Pilih Cabang --</option>
			</select>';
	//SETTING REGIONAL BERDASARKAN USER//
}
echo $_REQUEST['id_cab'];
if ($q['cabang']=="PUSAT") {
	$userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
	$_userCabang .= '<select id="id_cab" name="id_cab"> <option value="">--- Pilih ---</option>';
	while($userCabang_ = mysql_fetch_array($userCabang)) {
		$_userCabang .= '<option value="'.$userCabang_['id'].'"'._selected($_REQUEST['id_cab'], $userCabang_['id']).'>'.$userCabang_['name'].'</option>';
	}
	$_userCabang .= '</select>';
	$QueryCabang = 'AND cabang !=""';
	//$QueryInput = 'AND input_by !="" AND input_by IS NULL';
}else{
	$_userCabang = $q['cabang'];
	$QueryCabang = 'AND cabang ="'.$q['cabang'].'"';
	$QueryInput = 'AND input_by ="'.$q['nm_user'].'"';
}

echo '<form method="post" action="" name="postform">
	<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	<table border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr><td width="10%">Nama Perusahaan</td><td>: '.$metcost['name'].'</td></tr>
	<tr><td>Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
<!--<tr><td align="right">Nama Produk</td><td>: '.$kolomproduk.'</td></tr>-->
<tr><td>Nama Produk</td><td>: <select name="idpolis">
<option value="">---Pilih Produk---</option>';
while($met_polis_ = mysql_fetch_array($met_polis)) {
echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['idpolis'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
}
echo '</select></td></tr>
	<tr><td>Tanggal Mulai Asuransi <font color="red">*</font></td>
		<td> : <input type="text" name="tglakad1" id="tglakad1" class="tanggal" value="'.$_REQUEST['tglakad1'].'" size="10"/> s/d
			  <input type="text" name="tglakad2" id="tglakad2" class="tanggal" value="'.$_REQUEST['tglakad2'].'" size="10"/>
		</td>
	</tr>
	<tr><td>Status Pembayaran </td><td> :
		<select size="1" name="paiddata"><option value="">--- Status ---</option>
										 <option value="1"'._selected($_REQUEST['paiddata'], "1").'>Paid</option>
										 <option value="0"'._selected($_REQUEST['paiddata'], "0").'>Unpaid</option>
		</select>
	</td></tr>
	<tr><td>Status Peserta </td><td> :
	<select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
		  								  <option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Aktif</option>
										  <option value="Lapse"'._selected($_REQUEST['statpeserta'], "Lapse").'>Lapse</option>
										  <option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
										  <option value="Batal"'._selected($_REQUEST['statpeserta'], "Batal").'>Batal</option>
	 </select>
	</td></tr>';
/*
echo '<tr><td align="right">Regional</td>
	  '.$kolomregional.'</tr>
	  <tr><td align="right">Cabang</td>
	  '.$kolomcabang.'</td></tr>
*/
echo '<tr><td colspan="2"><input type="hidden" name="re" value="datapeserta"><input type="submit" name="ere" value="Cari"></td></tr>
	</table>
	</form>';
if ($_REQUEST['re']=="datapeserta") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglakad1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglakad2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{

	if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}
	if ($_REQUEST['paiddata'])		{	$empt = 'AND status_bayar = "'.$_REQUEST['paiddata'].'"';	}
	if ($_REQUEST['statpeserta'])	{	$lima = 'AND status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
	if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$enam = 'AND regional = "'.$met_reg['name'].'"';
}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$tujuh = 'AND cabang = "'.$met_cab['name'].'"';
}

$searchbank = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
/*
if ($q['id_cost']!="" AND $q['id_polis']!="") {
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'"'));
}else{
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['id_produk'].'"'));
}
*/
if ($_REQUEST['idpolis']=="") {
$searchproduk_ = "SEMUA PRODUK";
}else{
$searchproduk = mysql_fetch_array($database->doQuery('SELECT id, nmproduk FROM fu_ajk_polis WHERE id="'.$_REQUEST['idpolis'].'"'));
$searchproduk_ = $searchproduk['nmproduk'];
}

if ($_REQUEST['paiddata']=="1") {	$searchpaid = "PAID";	}
elseif ($_REQUEST['paiddata']=="0") {	$searchpaid = "UNPAID";	}
else{	$searchpaid = "SEMUA STATUS PEMBAYARAN";	}

if ($_REQUEST['statpeserta']!="") {	$searchpaidstatus = $_REQUEST['statpeserta'] ;	}	else{	$searchpaidstatus = "SEMUA STATUS PESERTA";	}

echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<!--<tr><td bgcolor="#FFF"colspan="16"><a href="e_report.php?er=eL_peserta&cat='.$q['id_cost'].'&subcat='.$setproduk_met.'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>-->
	<tr><td bgcolor="#FFF"colspan="17"><a href="e_report.php?er=eR_peserta&cat='.$q['id_cost'].'&subcat='.$_REQUEST['idpolis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&deb='.$q['id'].'&id_cab='.$_REQUEST['id_cab'].'" target="_blank"><img src="image/dninvoice1.jpg" width="35" border="0"><br /> &nbsp; PDF</a></td></tr>
	<tr><td colspan="2">Nama Perusahaan</td><td colspan="14">: '.$searchbank['name'].'</td></tr>
	<tr><td colspan="2">Nama Produk</td><td colspan="14">: '.$searchproduk_.'</td></tr>
	<tr><td colspan="2">Tanggal Akad</td><td colspan="14">: '._convertDate($_REQUEST['tglakad1']).' s/d '._convertDate($_REQUEST['tglakad2']).'</td></tr>
	<tr><td colspan="2">Status Pembayaran</td><td colspan="14">: '.$searchpaid.'</td></tr>
	<tr><td colspan="2">Status Peserta</td><td colspan="14">: '.$searchpaidstatus.'</td></tr>
	<tr><th width="1%">No</th>
		<th>Debit Note</th>
		<th>Tanggal DN</th>
		<th>No. Reg</th>
		<th>Nama Debitur</th>
		<th>Cabang</th>
		<th>Tgl Lahir</th>
		<th>Usia</th>
		<th>Plafond</th>
		<th>Tanggal Akad</th>
		<th>Tenor</th>
		<th>Tanggal Akhir</th>
		<th>Rate</th>
		<th>Premi</th>
		<th>EM</th>
		<!--<th>Total Rate</th>-->
		<th>Total Premi</th>
		<th>Cabang</th>
	</tr>';
if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}
if ($_REQUEST['idpolis'])		{	$delapan = 'AND id_polis = "'.$_REQUEST['idpolis'].'"';	}
if ($_REQUEST['paiddata'])		{	$empt = 'AND status_bayar = "'.$_REQUEST['paiddata'].'"';	}
if ($_REQUEST['statpeserta'])	{	$lima = 'AND status_aktif = "'.$_REQUEST['statpeserta'].'"';	}
if ($_REQUEST['id_reg'])		{	$met_reg = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'" and del is null'));
									$enam = 'AND regional = "'.$met_reg['name'].'"';
								}
if ($_REQUEST['id_cab'])		{ 	$met_cab = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'" and del is null'));
									$tujuh = 'AND cabang = "'.$met_cab['name'].'"';
								}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}


$cekCentral = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE name="'.$q['cabang'].'"'));
$cekCentral_ = $database->doQuery('SELECT * FROM fu_ajk_cabang WHERE centralcbg="'.$cekCentral['id'].'"');
while ($cekCentral__ = mysql_fetch_array($cekCentral_)) {
	$metCentralCabang .= ' OR cabang ="'.$cekCentral__['name'].'"';
}
if ($q['wilayah']=="PUSAT" AND $q['cabang']=="PUSAT") {
$metCabangCentral = '';
}elseif ($q['wilayah']!="PUSAT" AND $q['cabang']=="PUSAT") {
	$cekCentral = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang
									  FROM fu_ajk_regional
									  INNER JOIN fu_ajk_cabang ON fu_ajk_regional.id = fu_ajk_cabang.id_reg
									  WHERE fu_ajk_regional.`name` = "'.$q['wilayah'].'" AND fu_ajk_cabang.del IS NULL');
	while ($cekCentral__ = mysql_fetch_array($cekCentral)) {
		$metCabangCentral .= 'OR (cabang ="'.$cekCentral__['cabang'].'")';
	}
	$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCabangCentral.')';

}else{
	if ($metCentralCabang=="") {
		$metCabangCentral = 'AND cabang ="'.$q['cabang'].'"';
	}else{
		$metCabangCentral = 'AND (cabang ="'.$q['cabang'].'" '.$metCentralCabang.')';
	}
	}
//CEK DATA CABANG CENTRAL;
//echo 'SELECT * FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'" and del is null';
//echo 'SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kTenor FROM fu_ajk_peserta WHERE id_dn !="" '.$metCabangCentral.' AND id_cost="'.$q['id_cost'].'" '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND del is NULL ORDER BY kredit_tgl ASC LIMIT '.$m.', 25';
$met = $database->doQuery('SELECT *, IF(type_data="SPK", kredit_tenor * 12, kredit_tenor) AS kTenor FROM fu_ajk_peserta WHERE id_dn !="" '.$metCabangCentral.' AND id_cost="'.$q['id_cost'].'" '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND del is NULL ORDER BY kredit_tgl ASC LIMIT '.$m.', 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM fu_ajk_peserta WHERE id_dn !="" '.$metCabangCentral.' AND id_cost="'.$q['id_cost'].'" '.$tiga.' '.$empt.' '.$lima.' '.$enam.' '.$tujuh.' '.$delapan.' AND del is NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
$cekdatadn = mysql_fetch_array($database->doQuery('SELECT id, dn_kode, tgl_createdn FROM fu_ajk_dn WHERE id="'.$met_['id_dn'].'"'));

if ($met_['type_data']=="SPK") {
//	$mettenornya = $met_['kTenor'] / 12;
	$cekdataret = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$delapan.' AND usia="'.$met_['usia'].'" AND tenor="'.$met_['kTenor'] / 12 .'" AND status="baru"'));		// RATE PREMI
}else{
	$cekdataret = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id !="" AND id_cost="'.$q['id_cost'].'" '.$delapan.' AND tenor="'.$met_['kTenor'].'" AND status="baru"'));
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td align="center">'.$cekdatadn['dn_kode'].'</td>
	  <td align="center">'._convertDate($cekdatadn['tgl_createdn']).'</td>
	  <td>'.$met_['id_peserta'].'</td>
	  <td>'.$met_['nama'].'</td>
	  <td>'.$met_['cabang'].'</td>
	  <td align="center">'._convertDate($met_['tgl_lahir']).'</td>
	  <td align="center">'.$met_['usia'].'</td>
	  <td align="right">'.duit($met_['kredit_jumlah']).'</td>
	  <td align="center">'._convertDate($met_['kredit_tgl']).'</td>
	  <td align="center">'.$met_['kTenor'].'</td>
	  <td align="center">'._convertDate($met_['kredit_akhir']).'</td>
	  <td align="center">'.$cekdataret['rate'].'</td>
	  <td align="right">'.duit($met_['premi']).'</td>
	  <td align="right">'.duit($met_['ext_premi']).'</td>
	  <!--<td>'.$cekdataret['rate'].'</td>-->
	  <td align="right">'.duit($met_['totalpremi']).'</td>
	  <td>'.$met_['cabang'].'</td>
	  </tr>';
	$jumUP += $met_['kredit_jumlah'];
	$jumPremi += ROUND($met_['totalpremi']);
}
	echo '<tr class="tr1"><td colspan="8">Total</td><td align="right">'.duit($jumUP).'</td><td colspan="6"></td><td align="right">'.duit($jumPremi).'</td></tr>';
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'er_peserta.php?re=datapeserta&id_cost='.$_REQUEST['id_cost'].'&idpolis='.$_REQUEST['idpolis'].'&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&paiddata='.$_REQUEST['paiddata'].'&id_cab='.$_REQUEST['id_cab'].'&statpeserta='.$_REQUEST['statpeserta'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data Peserta: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
	;
} // switch

echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_reg" , {
		elements:{
			"id_cab":		{url:\'javascript/metcombo/data.php?req=setdatacabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
?>

<!--datepicker-->
<link type="text/css" href="includes/Rjs/themes/base/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="includes/Rjs/jquery-1.3.2.js"></script>
<script type="text/javascript" src="includes/Rjs/ui.core.js"></script>
<script type="text/javascript" src="includes/Rjs/ui.datepicker.js"></script>
<script type="text/javascript">
      $(document).ready(function(){
        $(".tanggal").datepicker({
		dateFormat  : "dd/mm/yy",
          changeMonth : true,
          changeYear  : true
        });
      });
    </script>
<!--datepicker-->