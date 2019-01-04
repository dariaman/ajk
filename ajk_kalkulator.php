<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch ($_REQUEST['k']) {
	case "d":
		;
		break;
	case "refund":
		echo '<script language="javascript" src="javascript/autonumber.js"></script>
		<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Kalkulator Debitur</font></th></tr></table>';
			$userPerusahaan = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
			if ($q['level']=="6") {
				$userProduk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL');
				$_ProdukUser .= '<select id="id_cost" name="cat"> <option value="">--- Pilih ---</option>';
				while($userProduk_ = mysql_fetch_array($userProduk)) {
					$_ProdukUser .= '<option value="'.$userProduk_['id'].'"'._selected($_REQUEST['cat'], $userProduk_['id']).'>'.$userProduk_['nmproduk'].'</option>';
				}
				$_ProdukUser .= '</select>';
				$QueryProduk = 'AND id_polis !=""';
			}else{
				$userProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'" AND del IS NULL'));
				$_ProdukUser = $userProduk['nmproduk'].' ('.$userProduk['nopol'].')';
				$QueryProduk = 'AND id_polis="'.$q['id_polis'].'"';
			}

			if ($q['cabang']=="PUSAT" OR $q['level']=="6") {
				$userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
				$_userCabang .= '<select id="id_cost" name="subcat"> <option value="">--- Pilih ---</option>';
				while($userCabang_ = mysql_fetch_array($userCabang)) {
					$_userCabang .= '<option value="'.$userCabang_['name'].'"'._selected($_REQUEST['subcat'], $userCabang_['name']).'>'.$userCabang_['name'].'</option>';
				}
				$_userCabang .= '</select>';
				$QueryCabang = 'AND cabang !=""';
				//$QueryInput = 'AND input_by !="" AND input_by IS NULL';
			}else{
				$_userCabang = $q['cabang'];
				$QueryCabang = 'AND cabang ="'.$q['cabang'].'"';
				$QueryInput = 'AND input_by ="'.$q['nm_user'].'"';
			}

			if ($q['id_polis']=="") {
				$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id !="" AND del IS NULL ORDER BY nmproduk ASC');
			}else{
				$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id !="" AND del IS NULL AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
			}
			$met_Mitra = $database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost ="'.$q['id_cost'].'" ORDER BY nmproduk ASC');
		echo '
		<fieldset style="padding: 2">
			<legend align="center">Kalkulator Refund Debitur</legend>
			<table border="0" width="100%" cellpadding="1" cellspacing="1">
				<form method="post" action="">
					<tr><td width="10%">Nama Perusahaan</td><td>: '.$userPerusahaan['name'].'</td></tr>
					<tr><td width="10%">Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
					<tr><td>Nama Produk <font color="red">*</font></td>
						<td>: 
							<select name="kProduk">
								<option value="">---Pilih Produk---</option>';
								while($met_polis_ = mysql_fetch_array($met_polis)) {
									echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['kProduk'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
								}
								echo '
							</select>
						</td>
					</tr>					
					<tr><td width="10%">Tanggal Akad <font color="red">*</font></td><td>: <input type="text" name="kTglAkad" id="kTglAkad" class="tanggal" value="'.$_REQUEST['kTglAkad'].'" size="10"/>
					<tr><td width="10%">Tenor <font color="red">*</font></td><td>: <input type="text" name="kTenorBln" value="'.$_REQUEST['kTenorBln'].'" size="10" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"> Bulan</td></tr>
					<tr><td width="10%">Tanggal Refund <font color="red">*</font></td><td>: <input type="text" name="kTglRefund" id="kTglRefund" class="tanggal" value="'.$_REQUEST['kTglRefund'].'" size="10"/>
					<tr><td width="10%">Premi <font color="red">*</font></td><td>: <input type="text" name="kPremi" value="'.$_REQUEST['kPremi'].'" size="12" maxlength="12"  id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"/ ></td></tr>
					<tr><td colspan="2"><input type="hidden" name="opp" value="hitung" class="button"><input type="submit" name="button" value="Hitung Refund" class="button"></td></tr>
				</form>
			</table>
		</fieldset>';
		if ($_REQUEST['opp']=="hitung") {

			$tglakad = $_POST['kTglAkad'];
			$tglrefund = $_POST['kTglRefund'];
			$tenor = $_POST['kTenorBln'];
			$premi = str_replace('.', '',$_POST['kPremi']);
			$polis = $_POST['kProduk'];

			
			$int = explode(',',datediff(date('Y-m-d'),$tglrefund));
			$day = $int[0]*365 + $int[1]*30 + $int[2];

			
			if($day > 30){
				$tglrefund = date('Y-m-d');
			}
			

			$interval = datediff($tglakad,$tglrefund);
			$interval_ = explode(',',$interval);
			$sisatenor = $tenor - ($interval_[0]*12 + $interval_[1]);
			
			$persenbiaya = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_rasio_claim WHERE id_polis = '".$polis."' AND '".$tglakad."' BETWEEN eff_from and eff_to"));
			
			$biaya = round($premi * $persenbiaya['refund'],0);
			$refund = round($sisatenor / $tenor * ($premi - $biaya),0);
			$persen = $persenbiaya['refund']*100;
			echo '
			<fieldset style="padding: 2">
				<legend align="center">Hasil Perhitungan Refund</legend>
				<table border="0" width="100%" cellpadding="1" cellspacing="1">

					<tr>
						<td width="10%">Masa Pertanggungan Asuransi</td>
						<td width="1%">:</td>
						<td width="50%">'.$tenor.' Bulan</td>
					</tr>
					<tr><td width="1%">Sisa Waktu Pertanggungan Asuransi</td><td>:</td><td>'.$sisatenor.' Bulan</td></tr>
					<tr><td width="1%">Biaya Pengelolaan & Penutupan</td><td>:</td><td>IDR '.duit($biaya).'</td></tr>
					<tr><td width="1%"><b>Total Pengembalian Premi</td><td>:</td><td><b>IDR '.duit($refund).'</b></td></tr>
				</table>

				<p><b>Catatan: <br>Pengembalian Premi : t/n * (Premi dibayar - biaya pengelolaan)<br>
				t : Sisa Waktu Pertanggungan Asuransi (dalam bulan)<br>
				n : Masa Pertanggungan Asuransi (dalam bulan)<br>
				Biaya pengelolaan & Penutupan sebesar '.$persen.'% dari premi di bayar
				</p>
			</fieldset>';
		}
	break;
	default:
echo '<script language="javascript" src="javascript/autonumber.js"></script>
	<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Kalkulator Debitur</font></th></tr></table>';
		$userPerusahaan = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
		if ($q['level']=="6") {
			$userProduk = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL');
			$_ProdukUser .= '<select id="id_cost" name="cat"> <option value="">--- Pilih ---</option>';
			while($userProduk_ = mysql_fetch_array($userProduk)) {
				$_ProdukUser .= '<option value="'.$userProduk_['id'].'"'._selected($_REQUEST['cat'], $userProduk_['id']).'>'.$userProduk_['nmproduk'].'</option>';
			}
			$_ProdukUser .= '</select>';
			$QueryProduk = 'AND id_polis !=""';
		}else{
			$userProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$q['id_polis'].'" AND del IS NULL'));
			$_ProdukUser = $userProduk['nmproduk'].' ('.$userProduk['nopol'].')';
			$QueryProduk = 'AND id_polis="'.$q['id_polis'].'"';
		}

		if ($q['cabang']=="PUSAT" OR $q['level']=="6") {
			$userCabang = $database->doQuery('SELECT DISTINCT * FROM fu_ajk_cabang WHERE id_cost="'.$q['id_cost'].'" AND del IS NULL GROUP BY name ORDER BY name ASC');
			$_userCabang .= '<select id="id_cost" name="subcat"> <option value="">--- Pilih ---</option>';
			while($userCabang_ = mysql_fetch_array($userCabang)) {
				$_userCabang .= '<option value="'.$userCabang_['name'].'"'._selected($_REQUEST['subcat'], $userCabang_['name']).'>'.$userCabang_['name'].'</option>';
			}
			$_userCabang .= '</select>';
			$QueryCabang = 'AND cabang !=""';
			//$QueryInput = 'AND input_by !="" AND input_by IS NULL';
		}else{
			$_userCabang = $q['cabang'];
			$QueryCabang = 'AND cabang ="'.$q['cabang'].'"';
			$QueryInput = 'AND input_by ="'.$q['nm_user'].'"';
		}

		if ($q['id_polis']=="") {
			$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id !="" AND del IS NULL ORDER BY nmproduk ASC');
		}else{
			$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE id !="" AND del IS NULL AND ('.$mametProdukUser.') ORDER BY nmproduk ASC');
		}
		$met_Mitra = $database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost ="'.$q['id_cost'].'" ORDER BY nmproduk ASC');
echo '<fieldset style="padding: 2">
	<legend align="center">Kalkulator Debitur</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="10%">Nama Perusahaan</td><td>: '.$userPerusahaan['name'].'</td></tr>
	<tr><td width="10%">Cabang</td><td><b>: '.$_userCabang.'</b></td></tr>
	<tr><td>Nama Produk <font color="red">*</font></td><td>: <select name="kProduk">
	<option value="">---Pilih Produk---</option>';
	while($met_polis_ = mysql_fetch_array($met_polis)) {
		echo '<option value="'.$met_polis_['id'].'"'._selected($_REQUEST['kProduk'], $met_polis_['id']).'>'.$met_polis_['nmproduk'].'</option>';
	}
echo '</select></td></tr>
	<tr><td width="10%">Nama </td><td>: <input type="text" name="kNama" value="'.$_REQUEST['kNama'].'"></td></tr>
	<tr><td width="10%">Tanggal Lahir <font color="red">*</font></td><td>: <input type="text" name="kTglLahir" id="kTglLahir" class="tanggal" value="'.$_REQUEST['kTglLahir'].'" size="10"/>
	<tr><td width="10%">Tanggal Akad <font color="red">*</font></td><td>: <input type="text" name="kTglAkad" id="kTglAkad" class="tanggal" value="'.$_REQUEST['kTglAkad'].'" size="10"/>
	<tr><td width="10%">Tenor</td><td>: <input type="text" name="kTenorThn" value="'.$_REQUEST['kTenorThn'].'" size="1" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"> Tahun <input type="text" name="kTenorBln" value="'.$_REQUEST['kTenorBln'].'" size="1" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"> Bulan</td></tr>
	<tr><td width="10%">Plafond <font color="red">*</font></td><td>: <input type="text" name="kPlafond" value="'.$_REQUEST['kPlafond'].'" size="12" maxlength="12"  id="inputku" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"/ ></td></tr>
	<tr><td width="10%">MPP</td><td>: <input type="text" name="kMPP" value="'.$_REQUEST['kMPP'].'" size="1" maxlength="3" onkeyup="this.value=this.value.replace(/[^0-9]/g,\'\')"> Bulan</td></tr>
	<tr><td colspan="2"><input type="hidden" name="opp" value="hitung" class="button"><input type="submit" name="button" value="Hitung Premi" class="button"></td></tr>
	</form>
	</table></fieldset>';
	if ($_REQUEST['opp']=="hitung") {
		if (!$_REQUEST['kProduk']) {	$error .='Silahkan pilih nama produk.<br />';	}
		if (!$_REQUEST['kTglLahir']) {	$error .='Silahkan isi tanggal lahir.<br />';	}
		if (!$_REQUEST['kTglAkad']) {	$error .='Silahkan isi tanggal akad.<br />';	}
		if (!$_REQUEST['kPlafond']) {	$error .='Silahkan isi nilai plafond.';	}
		$cekProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['kProduk'].'"'));
		if ($cekProduk['mpptype']=="Y" AND $_REQUEST['kMPP'] =="") {	$error .='Silahkan isi nilai bulan MPP.';	}
		if ($error) {
			echo '<blink><center><font color=red>'.$error.'</font></blink></center>';
		}else{
		$plafondnya__ = str_replace(".", "", $_REQUEST['kPlafond']);
		if ($_REQUEST['kNama']=="") {	$_nama = 'Guest';	} else {	$_nama = $_REQUEST['kNama'];	}
		$cekProduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$_REQUEST['kProduk'].'"'));
		if ($cekProduk['typeproduk']=="SPK") {
			$tenorbln_ = CEIL($_REQUEST['kTenorBln'] / 12);
			$tenorkalkulator = $_REQUEST['kTenorThn'] + $tenorbln_ .'Year';
			$tenorkalkulator1 = $_REQUEST['kTenorThn'] + $tenorbln_;
			$tenorDebiturnya = $tenorkalkulator1.' tahun';
		}else{
			$tenorbln_ = $_REQUEST['kTenorThn'] * 12;
			$tenorkalkulator = $tenorbln_ + $_REQUEST['kTenorBln'].' Month';
			$tenorkalkulator1 = $tenorbln_ + $_REQUEST['kTenorBln'];
			$tenorDebiturnya = $tenorkalkulator1.' bulan';
		}

		$tgl_akhir_kredit = date('Y-m-d',strtotime($_REQUEST['kTglAkad']."+".$tenorkalkulator."-".$cekProduk['day_kredit']." day"));	//KREDIT AKHIR


		$mets = datediff($_REQUEST['kTglAkad'], $_REQUEST['kTglLahir']);
		$metTgl = explode(",",$mets);
		if ($metTgl[1] >= 6 ) {	$umur = $metTgl[0] + 1;	}else{	$umur = $metTgl[0];	}

		if ($_REQUEST['kMPP'] =="") {
			$_mpp = '';
		}else{
			$_mpp = '<tr><td>MPP</td><td><b>'.$_REQUEST['kMPP'].' bulan </b></td></tr>';
		}

		if ($cekProduk['singlerate']=="T") {
			if ($cekProduk['mpptype']=="Y") {
			$tenorkalkulator1 = CEIL($tenorkalkulator1 / 12);
			$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$_REQUEST['kProduk'].'" AND tenor="'.$tenorkalkulator1 .'" AND '.$_REQUEST['kMPP'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
			}else{
			$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$_REQUEST['kProduk'].'" AND tenor="'.$tenorkalkulator1.'" AND status="baru" AND del IS NULL'));		// RATE PREMI
			}
		}else{
			if ($cekProduk['mpptype']=="Y") {
				$tenorkalkulator1 = $tenorkalkulator1;
				$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$_REQUEST['kProduk'].'" AND tenor="'.$tenorkalkulator1 .'" AND '.$_REQUEST['kMPP'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
			}else{
				$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$q['id_cost'].'" AND id_polis="'.$_REQUEST['kProduk'].'" AND usia="'.$umur.'" AND tenor="'.$tenorkalkulator1.'" AND status="baru" AND del IS NULL'));		// RATE PREMI
			}
		}

if (!$cekrate['rate']) {
echo '<blink><center><font color=red>Rate tidak ditemukan</font></blink></center>';
}else{
$premi = ROUND(($cekrate['rate'] / 1000) * $plafondnya__);
echo '<fieldset style="padding: 2">
	<legend align="center">Hasil Premi Kalkulator</legend>
	<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<tr><td width="10%">Produk</td><td>'.$cekProduk['nmproduk'].'</td></tr>
	<tr><td>Nama</td><td>'.$_nama.'</td></tr>
	<tr><td>Tanggal Lahir</td><td>'._convertDate($_REQUEST['kTglLahir']).'</td></tr>
	<tr><td>Usia</td><td><b>'.$umur.' Tahun</b></td></tr>
	<tr><td>Tanggal Akad</td><td>'._convertDate($_REQUEST['kTglAkad']).'</td></tr>
	<tr><td>Tenor</td><td>'.$tenorDebiturnya.'</td></tr>
	<tr><td>Tanggal Akhir</td><td><b>'._convertDate($tgl_akhir_kredit).'</b></td></tr>
	<tr><td>Plafond</td><td>'.duit($plafondnya__).'</td></tr>
	'.$_mpp.'
	<tr><td>Rate</td><td><b>'.$cekrate['rate'].'</b></td></tr>
	<tr><td>Premi</td><td><b>'.duit($premi).'</b></td></tr>
	</table>
	</fieldset>';
}
		}
	}
		;
} // switch
?>