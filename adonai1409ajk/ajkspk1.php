<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
switch ($_REQUEST['er']) {
	case "rkpsumm":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Asuransi</font></th>'.$metnewuser.'</tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>


	  <tr><td align="right">Regional</td>
		<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>
		</select></td></tr>
	  <tr><td align="right">Cabang</td>
		<td id="polis_rate">: <select name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>
		</select></td></tr>
	  <tr>
				<tr><td align="right">Tanggal Input</td>
	  <td> : <input type="text" id="fromakad1" name="tglcheck1" value="'.$_REQUEST['tglakad3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromakad2" name="tglcheck2" value="'.$_REQUEST['tglakad4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
		<td align="center"colspan="2"><input type="hidden" name="re" value="dataasuransi"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
		if ($_REQUEST['re']=="dataasuransi") {
			if ($_REQUEST ['id_cost'] == "") {
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';
			}

			if ($_REQUEST ['tglcheck1'] == "") {
				$error_2 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong</div></font></blink>';
			}

			if ($error_1 or $error_2 or $error_3 ) {
				echo $error_1 . '' . $error_2 . '' . $error_3 ;
			} else {


				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=eL_SumSPK&idCost=' . $_REQUEST ['id_cost'] . '&idReg=' . $_REQUEST ['id_reg'] . '&idCab=' . $_REQUEST ['id_cab'] . '&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th width="1%">No</th>
					<th>Nama Perusahaan</th>
					<th width="10%">Regional</th>
					<th width="10%">Cabang</th>
					<th width="10%">Nama Dokter</th>
					<th width="10%">Tgl Periksa</th>
					<th width="10%">Jumlah Periksa</th>
					<th width="10%">Fee</th>
					<th width="10%">Total</th>
					</tr>';

				$sql="";
				if($_REQUEST['id_reg']!=""){
					$sql=" and fu_ajk_regional.id=".$_REQUEST['id_reg'];
				}

				if($_REQUEST['id_cab']){
					$sql=$sql." nd fu_ajk_cabang.id=".$_REQUEST['id_cab'];
				}
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlSPK = $database->doQuery("SELECT
						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						ifnull(user_mobile.namalengkap,fu_ajk_spak_form.dokter_pemeriksa) as namalengkap,
						fu_ajk_spak_form.tgl_periksa,
						Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan,
						ifnull((select sum(fu_ajk_dokter_fee.fee_dokter) from fu_ajk_dokter_fee where fu_ajk_dokter_fee.id_user_mobile=user_mobile.id and
						fu_ajk_spak_form.tgl_periksa BETWEEN fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee),0) AS fee_dokter,
						ifnull((select sum(fu_ajk_dokter_fee.fee_dokter) from fu_ajk_dokter_fee where fu_ajk_dokter_fee.id_user_mobile=user_mobile.id and
						fu_ajk_spak_form.tgl_periksa BETWEEN fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee)*count(fu_ajk_cabang.`name`),0) AS total
						FROM
						fu_ajk_spak_form
						INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
						LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where fu_ajk_spak_form.tgl_periksa between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
						and fu_ajk_costumer.id=".$_REQUEST['id_cost']." ".$sql."
						group BY
						fu_ajk_cabang.`name`,
						user_mobile.namalengkap,
						fu_ajk_spak_form.dokter_pemeriksa,
						fu_ajk_spak_form.tgl_periksa,
						fu_ajk_regional.`name`,
						fu_ajk_costumer.`name` LIMIT ". $m ." , 25");

				$sqlSPK1 = $database->doQuery("SELECT

						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						ifnull(user_mobile.namalengkap,fu_ajk_spak_form.dokter_pemeriksa) as namalengkap,
						fu_ajk_spak_form.tgl_periksa,
						Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan,
						ifnull((select sum(fu_ajk_dokter_fee.fee_dokter) from fu_ajk_dokter_fee where fu_ajk_dokter_fee.id_user_mobile=user_mobile.id and
						fu_ajk_spak_form.tgl_periksa BETWEEN fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee),0) AS fee_dokter,
						ifnull((select sum(fu_ajk_dokter_fee.fee_dokter) from fu_ajk_dokter_fee where fu_ajk_dokter_fee.id_user_mobile=user_mobile.id and
						fu_ajk_spak_form.tgl_periksa BETWEEN fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee)*count(fu_ajk_cabang.`name`),0) AS total
						FROM
						fu_ajk_spak_form
						INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
						LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where fu_ajk_spak_form.tgl_periksa between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
						and fu_ajk_costumer.id=".$_REQUEST['id_cost']." ".$sql."
						group BY
						fu_ajk_cabang.`name`,
						user_mobile.namalengkap,
						fu_ajk_spak_form.dokter_pemeriksa,
						fu_ajk_spak_form.tgl_periksa,
						fu_ajk_regional.`name`,
						fu_ajk_costumer.`name`");

				$totalRows=mysql_num_rows($sqlSPK1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlSPK)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['nama_cost'].'</td>
					<td>'.$datanya_['nama_regional'].'</td>
					<td>'.$datanya_['nama_cabang'].'</td>
					<td>'.$datanya_['namalengkap'].'</td>
					<td>'.$datanya_['tgl_periksa'].'</td>
					<td align="right">'.$datanya_['jml_pemeriksaan'].'</td>
					<td align="right">'.$datanya_['fee_dokter'].'</td>
					<td align="right">'.$datanya_['total'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajkspk.php?er=rkpsumm&re=dataasuransi&id_cost='.$_REQUEST['id_cost'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}
		break;
	case "rkpuser":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Asuransi</font></th>'.$metnewuser.'</tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>


	  <tr><td align="right">Regional</td>
		<td id="polis_rate">: <select name="id_reg" id="id_reg">
		<option value="">-- Pilih Regional --</option>
		</select></td></tr>
	  <tr><td align="right">Cabang</td>
		<td id="polis_rate">: <select name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>
		</select></td></tr>
	  <tr>
				<tr><td align="right">Tanggal Input</td>
	  <td> : <input type="text" id="fromakad1" name="tglcheck1" value="'.$_REQUEST['tglakad3'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  		s/d
	  		<input type="text" id="fromakad2" name="tglcheck2" value="'.$_REQUEST['tglakad4'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;"/>
			<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;">
			<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
			<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
	  </td>
	  </tr>
		<td align="center"colspan="2"><input type="hidden" name="re" value="datarkpuser">
	  				<input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';
		if ($_REQUEST['re']=="datarkpuser") {
			if ($_REQUEST ['id_cost'] == "") {
				$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama perusahaan...!!<br /></div></font></blink>';
			}

			if ($_REQUEST ['tglcheck1'] == "") {
				$error_2 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_3 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong</div></font></blink>';
			}

			if ($error_1 or $error_2 or $error_3 ) {
				echo $error_1 . '' . $error_2 . '' . $error_3 ;
			} else {


				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=eL_UserSPK&idCost=' . $_REQUEST ['id_cost'] . '&idReg=' . $_REQUEST ['id_reg'] . '&idCab=' . $_REQUEST ['id_cab'] . '&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th width="1%">No</th>
					<th>Nama Perusahaan</th>
					<th width="10%">Regional</th>
					<th width="10%">Cabang</th>
					<th width="10%">Nama Marketing</th>
					<th width="10%">Tgl SPK</th>
					<th width="10%">Jumlah SPK</th>
					</tr>';

				$sql="";
				if($_REQUEST['id_reg']!=""){
					$sql=" and fu_ajk_regional.id=".$_REQUEST['id_reg'];
				}

				if($_REQUEST['id_cab']){
					$sql=$sql." and fu_ajk_cabang.id=".$_REQUEST['id_cab'];
				}
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlSPK = $database->doQuery("SELECT
						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						ifnull(user_mobile.namalengkap,fu_ajk_spak_form.input_by) as namalengkap,
						date(fu_ajk_spak_form.input_date) as date_input,
						Count(fu_ajk_cabang.`name`) AS jml_spak
						FROM
						fu_ajk_spak_form
						INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
						LEFT JOIN user_mobile ON fu_ajk_spak_form.input_by = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where fu_ajk_spak_form.input_date between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
						and fu_ajk_costumer.id=".$_REQUEST['id_cost']." ".$sql."
						group BY
						fu_ajk_cabang.`name`,
						user_mobile.namalengkap,
						fu_ajk_spak_form.input_by,
						date(fu_ajk_spak_form.input_date),
						fu_ajk_regional.`name`,
						fu_ajk_costumer.`name` LIMIT ". $m ." , 25");

				$sqlSPK1 = $database->doQuery("SELECT
						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						ifnull(user_mobile.namalengkap,fu_ajk_spak_form.input_by) as namalengkap,
						date(fu_ajk_spak_form.input_date) as date_input,
						Count(fu_ajk_cabang.`name`) AS jml_spak
						FROM
						fu_ajk_spak_form
						INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
						LEFT JOIN user_mobile ON fu_ajk_spak_form.input_by = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where fu_ajk_spak_form.input_date between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
						and fu_ajk_costumer.id=".$_REQUEST['id_cost']." ".$sql."
						group BY
						fu_ajk_cabang.`name`,
						user_mobile.namalengkap,
						fu_ajk_spak_form.input_by,
						date(fu_ajk_spak_form.input_date),
						fu_ajk_regional.`name`,
						fu_ajk_costumer.`name`");

				$totalRows=mysql_num_rows($sqlSPK1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlSPK)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['nama_cost'].'</td>
					<td>'.$datanya_['nama_regional'].'</td>
					<td>'.$datanya_['nama_cabang'].'</td>
					<td>'.$datanya_['namalengkap'].'</td>
					<td>'.$datanya_['date_input'].'</td>
					<td align="right">'.$datanya_['jml_spak'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajkspk.php?er=rkpuser&re=datarkpuser&id_cost='.$_REQUEST['id_cost'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
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