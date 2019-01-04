<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();

if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$futgl = date("Y-m-d g:i:a");
switch ($_REQUEST['r']) {
	case "approve":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr><th width="100%" align="left">Modul cek medik - Edit Data</font></th><th width="5%"><a href="ajk_cekmedik_fu.php">Back</a></th></tr>
		</table>';
if ($_REQUEST['ope']=="Simpan") {
if ($_REQUEST['statement1']=="Y" AND $_REQUEST['statement2']=="T" AND $_REQUEST['statement3']=="T" AND $_REQUEST['statement4']=="T") {
	$metstatus="Approve";		}else{	$metstatus="pending";		}
$el = $database->doQuery('UPDATE fu_ajk_peserta SET spaj="'.$_REQUEST['rspaj'].'",
													nama="'.$_REQUEST['rnama'].'",
													gender="'.$_REQUEST['rgender'].'",
													tgl_lahir="'.$_REQUEST['rdob'].'",
													kartu_type="'.$_REQUEST['rktype'].'",
													kartu_no="'.$_REQUEST['rkno'].'",
													kartu_period="'.$_REQUEST['rkperiode'].'",
													badant="'.$_REQUEST['rbadant'].'",
													badanb="'.$_REQUEST['rbadanb'].'",
													statement1="'.$_REQUEST['statement1'].'",
													statement2="'.$_REQUEST['statement2'].'",
													statement3="'.$_REQUEST['statement3'].'",
													statement4="'.$_REQUEST['statement4'].'",
													status_aktif="'.$metstatus.'",
													update_by="'.$q['nm_lengkap'].'",
													update_time="'.$futgl.'"
													WHERE id="'.$_REQUEST['id'].'"');
echo '<center>Data telah di edit.</center><meta http-equiv="refresh" content="2;URL=ajk_cekmedik_fu.php">';
}
$metmedik = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_peserta WHERE id="'.$_REQUEST['id'].'"'));
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$metmedik['id_cost'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metmedik['id_polis'].'"'));
echo '<form method="POST" action="">
	  <table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <tr><td width="10%">Costumer</td><td>: <b>'.$metcost['name'].'</b></td></tr>
	  <tr><td>No. Polis</td><td>: <b>'.$metpolis['nopol'].'</b></td></tr>
	  <tr><td>SPAJ</td><td>: <input type="text" name="rspaj" value="'.$metmedik['spaj'].'"></td></tr>
	  <tr><td>Nama</td><td>: <input type="text" name="rnama" value="'.$metmedik['nama'].'"></td></tr>
	  <tr><td>Jenis Kelamin</td><td>: <input type=radio '.pilih($metmedik["gender"], "P").'  name="rgender" value="P">P &nbsp; <input type=radio '.pilih($metmedik["gender"], "W").'  name="rgender" value="W">W</td></tr>
	  <tr><td>Tanggal Lahir</td><td>: ';
		echo initCalendar();
		echo calendarBox('rdob', 'triger1', $metmedik['tgl_lahir']);
		echo '</td></tr>
	  <tr><td>Type ID</td><td>:  <select size="1" name="rktype">
	   			<option value="">--</option>
	   			<option value="KTP"'._selected($metmedik['kartu_type'],"KTP").'>KTP</option>
	   			<option value="SIM"'._selected($metmedik['kartu_type'],"SIM").'>SIM</option>
	   			</select></td></tr>
	  <tr><td>No ID</td><td>: <input type="text" name="rkno" value="'.$metmedik['kartu_no'].'"></td></tr>
	  <tr><td>Periode ID</td><td>: ';
		echo initCalendar();
		echo calendarBox('rkperiode', 'triger2', $metmedik['kartu_period']);
		echo '</td></tr>
	  <tr><td>Kredit Jumlah</td><td>: <input type="text" name="rkjumlah" value="'.duit($metmedik['kredit_jumlah']).'" disabled></td></tr>
	  <tr><td>Tenor</td><td>: <input type="text" name="rktenor" value="'.$metmedik['kredit_tenor'].'" size="3" disabled></td></tr>
	  <tr><td>Premi</td><td>: <input type="text" name="rpremi" value="'.duit($metmedik['premi']).'" disabled></td></tr>
	  <tr><td>Bunga</td><td>: <input type="text" name="rbunga" value="'.$metmedik['bunga'].'" size="3" disabled></td></tr>
	  <tr><td>Biaya Administrasi</td><td>: <input type="text" name="radm" value="'.duit($metmedik['biaya_adm']).'" disabled></td></tr>
	  <tr><td>Biaya Refund</td><td>: <input type="text" name="rrefund" value="'.duit($metmedik['biaya_refund']).'" disabled></td></tr>
	  <tr><td>Extra Premi</td><td>: <input type="text" name="rextpremi" value="'.duit($metmedik['ext_premi']).'" disabled></td></tr>
	  <tr><td>Total Premi</td><td>: <input type="text" name="rtpremi" value="'.duit($metmedik['totalpremi']).'" disabled></td></tr>
	  <tr><td>Tinggi/Berat Badan</td><td>: <input type="text" name="rbadant" value="'.$metmedik['badant'].'" size="1"> / <input type="text" name="rbadanb" value="'.$metmedik['badanb'].'" size="1"></td></tr>
	  <tr><td>Statement 1</td><td>: <input type=radio '.pilih($metmedik["statement1"], "Y").'  name="statement1" value="Y">Y &nbsp;
			  						<input type=radio '.pilih($metmedik["statement1"], "T").'  name="statement1" value="T">T &nbsp;
									<font color="red">'.$metmedik['p1_ket'].'</font></td></tr>
	  <tr><td>Statement 2</td><td>: <input type=radio '.pilih($metmedik["statement2"], "Y").'  name="statement2" value="Y">Y &nbsp;
			  						<input type=radio '.pilih($metmedik["statement2"], "T").'  name="statement2" value="T">T &nbsp;
									<font color="red">'.$metmedik['p2_ket'].'</font></td></tr>
	  <tr><td>Statement 3</td><td>: <input type=radio '.pilih($metmedik["statement3"], "Y").'  name="statement3" value="Y">Y &nbsp;
			  						<input type=radio '.pilih($metmedik["statement3"], "T").'  name="statement3" value="T">T &nbsp;
									<font color="red">'.$metmedik['p3_ket'].'</font></td></tr>
	  <tr><td>Statement 4</td><td>: <input type=radio '.pilih($metmedik["statement4"], "Y").'  name="statement4" value="Y">Y &nbsp;
			  						<input type=radio '.pilih($metmedik["statement4"], "T").'  name="statement4" value="T">T &nbsp;
									<font color="red">'.$metmedik['p4_ket'].'</font></td></tr>
	  <tr><td colspan="3" align="center"><input type="submit" name="ope" value="Simpan"></td></tr>
	  </form>
	  </table>';
		;
		break;
	case "dmedic":
header('Content-Type: application/vnd.ms-word');
header('Content-Disposition: attachment; filename=' . $_REQUEST['id'] .' - Medical.doc');		/* kolom nomor urut invoice*/

echo $_REQUEST['id'];
		;
		break;
	default:
echo '<fieldset style="padding: 2">
	<legend align="center">S e a r c h</legend>
	<table border="0" width="100%" cellpadding="3" cellspacing="1">
		<form method="post" action="">
		<tr><td width="40%" align="right">Per Tanggal :</td><td>';
		print initCalendar();	print calendarBox('tgl', 'triger', $_REQUEST['tgl']);	echo 's/d';
		print initCalendar();	print calendarBox('tgl2', 'triger2', $_REQUEST['tgl2']);
		$reg = $database->doQuery('SELECT * FROM fu_ajk_regional GROUP BY name ORDER BY name ASC');
		echo '</td></tr>
				<tr><td align="right">Regional :</td>
      				<td><select size="1" name="cregional">
	   			<option value="">- - - Pilih Regional - - -</option>';
while ($creg = mysql_fetch_array($reg)) {	echo '<option value="'.$creg['name'].'">'.$creg['name'].'</option>';	}
		echo '</select></td>
      		</tr>
			<tr><td align="right">Nama :</td><td><input type="text" name="snama" value="'.$_REQUEST['snama'].'"></td></tr>
			<tr><td align="center" colspan="2"><input type="submit" name="button" value="Cari" class="button"></td>
			</tr>
			</form></table></fieldset>';
		//	echo $_REQUEST['tgl'].'<br />';
		//	echo $_REQUEST['tgl2'].'<br />';
		if ($_REQUEST['input_time']!='' AND $_REQUEST['tgl2']!='')	{	$satu='AND tgl BETWEEN \''.$_REQUEST['tgl'].'\' AND \''.$_REQUEST['tgl2'].'\'';	}
		if ($_REQUEST['cregional'])		{	$dua = 'AND regional LIKE "%' . $_REQUEST['cregional'] . '%"';		}
		if ($_REQUEST['snama'])		{	$tiga = 'AND nama LIKE "%' . $_REQUEST['snama'] . '%"';		}

echo '<form method="post" action="ajk_uploader_fu.php?r=approve">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr><th width="1%" rowspan="2">Opt</th>
		<th width="1%" rowspan="2">No</th>
		<th width="5%" rowspan="2">SPAJ</th>
		<th width="5%" rowspan="2">No. Reg</th>
		<th rowspan="2">Nama Debitur<br /><font size="1"><i>(sesuai KTP/SIM)</i></font></th>
		<th width="1%" rowspan="2">P/W</th>
		<th colspan="2">Kartu Identitas</th>
		<th rowspan="2">Tgl Lahir</th>
		<th rowspan="2">Usia</th>
		<th colspan="3">Status Kredit</th>
		<th width="1%" rowspan="2">Bunga<br>%</th>
		<th width="1%" rowspan="2">Premi</th>
		<th colspan="3">Biaya</th>
		<th width="1%" rowspan="2">Total Premi</th>
		<th rowspan="2">Medical</th>
		<th rowspan="2">Regional</th>
		<th rowspan="2">Area</th>
		<th rowspan="2">Cabang</th>
	</tr>
	<tr><th width="5%">Type</th>
		<th width="5%">No</th>
		<th>Tgl Kredit</th>
		<th>Jumlah</th>
		<th>Tenor</th>
		<th>Adm</th>
		<th>Refund</th>
		<th>Ext. Premi</th>

	</tr>';
$data = $database->doQuery('SELECT * FROM v_fu_ajk_peserta WHERE id!=""  AND status_aktif="pending" '.$satu.' '.$dua.' '.$tiga.' ORDER BY cabang ASC');
while ($fudata = mysql_fetch_array($data)) {
	$idp1 = 100000000 + $fudata['id'];		$idp2 = substr($idp1,1);	// ID PESERTA //

echo '<tr class="'.rowClass(++$i).'">
		  <td align="center"><a href="ajk_cekmedik_fu.php?r=approve&id='.$fudata['id'].'"><img src="../image/edit3.png"></a></td>
		  <td align="center">'.++$no.'</td>
		  <td>'.$fudata['spaj'].'</td>
		  <td>'.$idp2.'</td>
		  <td><a href="ajk_cekmedik_fu.php?r=vmedik&id='.$fudata['id'].'">'.$fudata['nama'].'</a></td>
		  <td align="center">'.$fudata['gender'].'</td>
		  <td width="1%" align="center">'.$fudata['kartu_type'].'</td>
		  <td>'.$fudata['kartu_no'].'</td>
		  <td align="center">'.$fudata['tgl_lahir'].'</td>
		  <td align="center">'.$fudata['usia'].'</td>
		  <td align="right">'.$fudata['kredit_tgl'].'</td>
		  <td align="right">'.duit($fudata['kredit_jumlah']).'</td>
		  <td align="center">'.$fudata['kredit_tenor'].'</td>
		  <td align="center">'.$fudata['bunga'].'</td>
		  <td align="right">'.duit($fudata['premi']).'</td>
		  <td align="right">'.duit($fudata['biaya_adm']).'</td>
		  <td align="right">'.duit($fudata['biaya_refund']).'</td>
		  <td align="right">'.duit($fudata['ext_premi']).'</td>
		  <td align="right">'.duit($fudata['totalpremi']).'</td>
		  <td align="center"><blink><a href="ajk_cekmedik_word.php?r=dmedic&id='.$fudata['id'].'">'.$fudata['status_medik'].'</a></blink></td>
		  <td align="center">'.$fudata['regional'].'</td>
		  <td align="center">'.$fudata['area'].'</td>
		  <td align="center">'.$fudata['cabang'].'</td>
		  </tr>';
}
		echo '</table>';
		;
} // switch
?>