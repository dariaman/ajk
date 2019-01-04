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
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Rekap Summary SPK</font></th>'.$metnewuser.'</tr></table>';
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$metgrupk = $database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="1" ORDER BY nmproduk ASC');
		echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
      <tr><td width="40%" align="right">Grouop Produk</td><td> : <select name="grproduk" id="grproduk">
		  	<option value="">---Pilih Grup Produk---</option>';
		while($metgrupk_ = mysql_fetch_array($metgrupk)) {
			echo  '<option value="'.$metgrupk_['id'].'"'._selected($_REQUEST['grproduk'], $metgrupk_['id']).'>'.$metgrupk_['nmproduk'].'</option>';
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
	  <tr><td align="right">Tanggal Input SPK</td>
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
			//if ($_REQUEST ['grproduk'] == "") {
			//	$error_4 = '<div align="center"><font color="red"><blink>Silahkan pilih grup produk</div></font></blink>';
			//}
			if ($error_1 or $error_2 or $error_3 or $error_4 ) {
				echo $error_1 . '' . $error_2 . '' . $error_3.'' . $error_4 ;
			} else {


				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
						<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=eL_SumSPK&idCost=' . $_REQUEST ['id_cost'] . '&idReg=' . $_REQUEST ['id_reg'] . '&idCab=' . $_REQUEST ['id_cab'] . '&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th width="1%">No</th>
					<th>Gorup Produk</th>
					<th>Cabang</th>
					<th width="10%">Total</th>
					<th width="10%">Realisasi</th>
					<th width="10%">Aktif</th>
					<th width="10%">Approve</th>
					<th width="10%">Proses</th>
					<th width="10%">Preapproval</th>
					<th width="10%">Pending</th>
					<th width="10%">Batal</th>
					<th width="10%">Tolak</th>
					<th width="10%">Kadaluarsa</th>
					</tr>';

				$sql="";
				if($_REQUEST['id_reg']!=""){
					$sql=" and fu_ajk_regional.id=".$_REQUEST['id_reg'];
				}

				if($_REQUEST['id_cab']){
					$sql=$sql." and fu_ajk_cabang.id=".$_REQUEST['id_cab'];
				}
				
				if($_REQUEST['grproduk']){
					$sql=$sql." and fu_ajk_grupproduk.id=".$_REQUEST['grproduk'];
				}				
				
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlSPK = $database->doQuery("SELECT
							nama_cost,
							nama_regional,
							nama_cabang,
							GROUP_CONCAT(`status`,' : ',CAST(jml_pemeriksaan AS CHAR),'|') AS ok, grupproduk
							FROM (SELECT fu_ajk_costumer.`name` AS nama_cost,
										 fu_ajk_regional.`name` AS nama_regional,
										 fu_ajk_cabang.`name` AS nama_cabang,
										 fu_ajk_spak.`status`,
										 fu_ajk_grupproduk.nmproduk AS grupproduk,
								  COUNT(fu_ajk_cabang.`name`) AS jml_pemeriksaan
								  FROM fu_ajk_spak
								  INNER JOIN fu_ajk_costumer ON fu_ajk_spak.id_cost = fu_ajk_costumer.id
								  INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
								  LEFT JOIN fu_ajk_spak_form ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
								  LEFT JOIN fu_ajk_cabang ON fu_ajk_cabang.id=fu_ajk_spak_form.`cabang`
								  LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
								  LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
							WHERE fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
							AND DATE_FORMAT(fu_ajk_spak_form.input_date,'%Y-%m-%d') BETWEEN '".$_REQUEST ['tglcheck1']."'  AND '".$_REQUEST ['tglcheck2']."'
							AND fu_ajk_costumer.id=".$_REQUEST['id_cost']." ".$sql." and LEFT(spak,2) != 'MP'							
							GROUP BY
							fu_ajk_cabang.`name`,
							fu_ajk_regional.`name`,
							fu_ajk_spak.`status`,
							fu_ajk_costumer.`name`
							) aa
							GROUP BY nama_cost,nama_regional,nama_cabang");

/*
				$sqlSPK1 = $database->doQuery("select
						nama_cost,
						nama_regional,
						nama_cabang,
						GROUP_CONCAT(`status`,' : ',CAST(jml_pemeriksaan AS CHAR),'|') as ok
						from (
						SELECT
						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						fu_ajk_spak.`status`,
						Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
						FROM
						fu_ajk_spak_form
						INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
						LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where date(fu_ajk_spak_form.input_date) between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
						and fu_ajk_costumer.id=".$_REQUEST['id_cost']." ".$sql."
						group BY
						fu_ajk_cabang.`name`,
						fu_ajk_regional.`name`,
						fu_ajk_spak.`status`,
						fu_ajk_costumer.`name`
						) aa group by nama_cost,nama_regional,nama_cabang");
*/



				$totalRows=mysql_num_rows($sqlSPK1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlSPK)){
					$status=explode("|", $datanya_['ok']);

					$pending=0;
					$aktif=0;
					$proses=0;
					$approve=0;
					$preapproval=0;
					$batal=0;
					$tolak=0;
					$realisasi=0;
					$kadaluarsa=0;
					//Aktif | Proses | Approve | Preapproval | Batal | Tolak | Realisasi
					for($x=0;$x<=sizeof($status);$x++){
						list($list_name,$list_count)=explode(" : ", $status[$x]);

						if(str_replace(",", "", $list_name)=="Pending"){
							$pending=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Aktif"){
							$aktif=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Proses"){
							$proses=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Approve"){
							$approve=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Preapproval"){
							$preapproval=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Batal"){
							$batal=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Tolak"){
							$tolak=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Realisasi"){
							$realisasi=$list_count;
						}

						if(str_replace(",", "", $list_name)=="Kadaluarsa"){
							$kadaluarsa=$list_count;
						}
					}
					$totalspk = $pending + $aktif + $proses + $approve + $preapproval + $batal + $tolak + $realisasi + $kadaluarsa;
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['grupproduk'].'</td>
					<td>'.$datanya_['nama_cabang'].'</td>
					<td align="right">'.$totalspk.'</td>
					<td align="right">'.$realisasi.'</td>
					<td align="right">'.$aktif.'</td>
					<td align="right">'.$approve.'</td>
					<td align="right">'.$proses.'</td>
					<td align="right">'.$preapproval.'</td>
					<td align="right">'.$pending.'</td>
					<td align="right">'.$batal.'</td>
					<td align="right">'.$tolak.'</td>
					<td align="right">'.$kadaluarsa.'</td>
					</tr>';
					$no++;
					$totalSPK_ += $totalspk;
					$totalPending += $pending;
					$totalAktif += $aktif;
					$totalProses += $proses;
					$totalApprove += $approve;
					$totalPreapproval += $preapproval;
					$totalBATAL += $batal;
					$totalToLaK += $tolak;
					$totalRealisasi += $realisasi;
					$totalkadaluarsa += $kadaluarsa;
				}
//				echo createPageNavigations($file = 'ajkspk.php?er=rkpsumm&re=dataasuransi&id_cost='.$_REQUEST['id_cost'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				//echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '<tr><td colspan="3"><b>Total Data : </u></b></td>
					  	  <td align="right"><b>' . $totalSPK_ . '</b></td>
					  	  <td align="right"><b>' . $totalRealisasi . '</b></td>
					  	  <td align="right"><b>' . $totalAktif . '</b></td>
					  	  <td align="right"><b>' . $totalApprove . '</b></td>
					  	  <td align="right"><b>' . $totalProses . '</b></td>
					  	  <td align="right"><b>' . $totalPreapproval . '</b></td>
					  	  <td align="right"><b>' . $totalPending . '</b></td>
					  	  <td align="right"><b>' . $totalBATAL . '</b></td>
					  	  <td align="right"><b>' . $totalToLaK . '</b></td>
					  	  <td align="right"><b>' . $totalkadaluarsa . '</b></td>
					  </tr>';
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
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> :
	  		<select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
			<tr><td align="right">Regional</td><td>: <select name="id_reg" id="id_reg"><option value="">-- Pilih Regional --</option></select></td></tr>
	  		<tr><td align="right">Cabang</td><td>: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option></select></td></tr>
	  <tr><td align="right">Tanggal Pemeriksaan</td>
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
							<tr><td width="4%" bgcolor="#FFF"colspan="24"><a href="e_report.php?er=eL_UserSPK&idCost=' . $_REQUEST ['id_cost'] . '&idReg=' . $_REQUEST ['id_reg'] . '&idCab=' . $_REQUEST ['id_cab'] . '&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td>
							<td width="7%" bgcolor="#FFF"colspan="24"><a href="e_report.php?er=eL_UserSPKSummary&idCost=' . $_REQUEST ['id_cost'] . '&idReg=' . $_REQUEST ['id_reg'] . '&idCab=' . $_REQUEST ['id_cab'] . '&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Summary User</a></td>
							<td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=eL_UserSPKDetail&idCost=' . $_REQUEST ['id_cost'] . '&idReg=' . $_REQUEST ['id_reg'] . '&idCab=' . $_REQUEST ['id_cab'] . '&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Detail User</a></td></tr>								
							</table>
					<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">				
					<tr>
					<th width="1%">No</th>
					<th>Cabang</th>
					<th width="30%">User Input</th>
					<th width="20%">No. SPK</th>
					<th width="10%">Status</th>
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
						fu_ajk_spak.spak,
						fu_ajk_spak.input_date,
						ifnull(user_mobile.namalengkap,fu_ajk_spak.input_by) as namalengkap,
						fu_ajk_spak.`status`
						FROM
						fu_ajk_spak
						LEFT JOIN user_mobile ON fu_ajk_spak.input_by = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where fu_ajk_spak.input_date between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
						and fu_ajk_costumer.id=".$_REQUEST['id_cost']." 
						and fu_ajk_spak.danatalangan is null ".$sql."
						LIMIT ". $m ." , 25");
 
				$sqlSPK1 = $database->doQuery("SELECT
						fu_ajk_costumer.`name` as nama_cost,
						fu_ajk_regional.`name` as nama_regional,
						fu_ajk_cabang.`name` as nama_cabang,
						fu_ajk_spak.spak,
						fu_ajk_spak.input_date,
						ifnull(user_mobile.namalengkap,fu_ajk_spak.input_by) as namalengkap,
						fu_ajk_spak.`status`
						FROM
						fu_ajk_spak
						LEFT JOIN user_mobile ON fu_ajk_spak.input_by = user_mobile.id
						INNER JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
						INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						INNER JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where fu_ajk_spak.input_date between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
						and fu_ajk_costumer.id=".$_REQUEST['id_cost']." 
						and fu_ajk_spak.danatalangan is null ".$sql."");

				$totalRows=mysql_num_rows($sqlSPK1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlSPK)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['nama_cabang'].'</td>
					<td>'.$datanya_['namalengkap'].'</td>
					<td>'.$datanya_['spak'].'</td>
					<td>'.$datanya_['status'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajkspk.php?er=rkpuser&re=datarkpuser&id_cost='.$_REQUEST['id_cost'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}
		break;
	case "feespkdokter":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Laporan Fee Dokter</font></th>'.$metnewuser.'</tr></table>';
		echo '<form method="post" action="" name="postform">
				<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
				<table border="0" cellpadding="1" cellspacing="0" width="100%">
						<tr><td width="40%" align="right">Dokter </td><td> : <select name="iddokter" id="iddokter">
						<option value="">---Dokter Pemeriksa---</option>';
				$metcost = $database->doQuery('SELECT * FROM user_mobile WHERE type="Dokter" and del is null ORDER BY namalengkap ASC');
				while($metcost_ = mysql_fetch_array($metcost)) {
				echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['iddokter'], $metcost_['id']).'>'.$metcost_['namalengkap'].'</option>';
				}
				echo '</select></td></tr>
						<tr><td align="right" width="40%">Tanggal Pemeriksaan</td>
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
				<td align="center"colspan="2"><input type="hidden" name="re" value="datafeedokter"><input type="submit" name="ere" value="Cari"></td></tr>
				</table>
				</form>';


				if ($_REQUEST['re']=="datafeedokter") {


		if ($_REQUEST ['tglcheck1'] == "")	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';	}
		if ($_REQUEST ['tglcheck2'] == "")	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong</div></font></blink>';	}
		if ($_REQUEST['iddokter'])			{	$satu = 'AND dokter_pemeriksa = "'.$_REQUEST['iddokter'].'"';	}

		if ($error_1 or $error_2 ) {	echo $error_1 . '' . $error_2 ;
		} else {
			echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr>
					<td bgcolor="#FFF"colspan="24">
					<a href="e_report.php?er=eL_FeeDokter&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '&dk=' . $_REQUEST ['iddokter'] . '"><img src="image/excel.png" width="25" border="0">Excel</a>
					<a href="e_report.php?er=feepemeriksaandokterdetail&tgl1='.$_REQUEST ['tglcheck1'].'&tgl2='.$_REQUEST ['tglcheck2'].'&id_dokter='.$_REQUEST ['iddokter'].'"><img src="image/excel.png" width="25" border="0">Excel Detail</a>
					</td>
					</tr>
						<th width="1%">No</th>
						<th>Nama Perusahaan</th>
						<th width="10%">Regional</th>
						<th width="10%">Cabang</th>
						<th width="10%">Nama Dokter</th>
						<th width="10%">Tgl Periksa</th>
						<th width="10%">Jumlah Periksa</th>
						<th width="10%">Fee Dokter</th>
						<th width="10%">Total</th>
					</tr>';
		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
		$sqlSPK = $database->doQuery("SELECT aa.ID,
							aa.namalengkap,
							aa.norek,
							aa.atas_nama,
							aa.bank_rek,
							aa.nama_cost,
							aa.nama_regional,
							aa.nama_cabang,
							DATE_FORMAT(aa.tgl_periksa,'%M %Y') as tgl,
							sum(aa.jml_pemeriksaan) as jml_pemeriksaan,
							fu_ajk_dokter_fee.fee_dokter,
							sum(aa.jml_pemeriksaan) * fu_ajk_dokter_fee.fee_dokter as total
							FROM (SELECT
							user_mobile.ID,
							user_mobile.namalengkap,
							user_mobile.norek,
							user_mobile.atas_nama,
							user_mobile.bank_rek,
							fu_ajk_costumer.`name` as nama_cost,
							fu_ajk_regional.`name` as nama_regional,
							fu_ajk_cabang.`name` as nama_cabang,
							fu_ajk_spak_form.tgl_periksa,
							Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
							FROM
							fu_ajk_spak_form
							INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
							LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
							LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
							LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
							LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
							where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
							and fu_ajk_spak_form.tgl_periksa between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."' ".$satu."

							group BY
							user_mobile.ID,
							user_mobile.namalengkap,
							user_mobile.norek,
							user_mobile.atas_nama,
							user_mobile.bank_rek,
							fu_ajk_cabang.`name`,
							fu_ajk_spak_form.tgl_periksa,
							fu_ajk_regional.`name`,
							fu_ajk_costumer.`name`) aa
							INNER JOIN fu_ajk_dokter_fee ON fu_ajk_dokter_fee.id_user_mobile = aa.id
							where aa.tgl_periksa between fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee
							group by
							aa.ID,
							aa.namalengkap,
							aa.norek,
							aa.atas_nama,
							aa.bank_rek,
							aa.nama_cost,
							aa.nama_regional,
							aa.nama_cabang,
							DATE_FORMAT(aa.tgl_periksa,'%M %Y'),
							fu_ajk_dokter_fee.fee_dokter
							ORDER BY DATE_FORMAT(aa.tgl_periksa,'%Y%m'), aa.namalengkap LIMIT ". $m ." , 25");


						$sqlSPK1 = $database->doQuery("SELECT aa.ID,
								aa.namalengkap,
								aa.norek,
								aa.atas_nama,
								aa.bank_rek,
								aa.nama_cost,
								aa.nama_regional,
								aa.nama_cabang,
								DATE_FORMAT(aa.tgl_periksa,'%M %Y') as tgl,
								sum(aa.jml_pemeriksaan) as jml_pemeriksaan,
								fu_ajk_dokter_fee.fee_dokter,
								sum(aa.jml_pemeriksaan) * fu_ajk_dokter_fee.fee_dokter as total
								FROM (SELECT
								user_mobile.ID,
								user_mobile.namalengkap,
								user_mobile.norek,
								user_mobile.atas_nama,
								user_mobile.bank_rek,
								fu_ajk_costumer.`name` as nama_cost,
								fu_ajk_regional.`name` as nama_regional,
								fu_ajk_cabang.`name` as nama_cabang,
								fu_ajk_spak_form.tgl_periksa,
								Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
								FROM
								fu_ajk_spak_form
								INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
								LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
								LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
								LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
								LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
								where user_mobile.`type`='Dokter'  and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
								and fu_ajk_spak_form.tgl_periksa between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."' ".$satu."

								group BY
								user_mobile.ID,
								user_mobile.namalengkap,
								user_mobile.norek,
								user_mobile.atas_nama,
								user_mobile.bank_rek,
								fu_ajk_cabang.`name`,
								fu_ajk_spak_form.tgl_periksa,
								fu_ajk_regional.`name`,
								fu_ajk_costumer.`name`) aa
								INNER JOIN fu_ajk_dokter_fee ON fu_ajk_dokter_fee.id_user_mobile = aa.id
								where aa.tgl_periksa between fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee
								group by
								aa.ID,
								aa.namalengkap,
								aa.norek,
								aa.atas_nama,
								aa.bank_rek,
								aa.nama_cost,
								aa.nama_regional,
								aa.nama_cabang,
								DATE_FORMAT(aa.tgl_periksa,'%M %Y'),
								fu_ajk_dokter_fee.fee_dokter
								ORDER BY DATE_FORMAT(aa.tgl_periksa,'%Y%m'), aa.namalengkap");

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
							<td>'.$datanya_['tgl'].'</td>
							<td align="right">'.number_format($datanya_['jml_pemeriksaan']).'</td>
							<td align="right">'.number_format($datanya_['fee_dokter'],2).'</td>
							<td align="right">'.number_format($datanya_['total'],2).'</td>
							</tr>';
							$no++;
						}
						echo createPageNavigations($file = 'ajkspk.php?er=feespkdokter&re=datafeedokter&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'&dk=' . $_REQUEST ['iddokter'] . '', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
						echo '<tr><td><b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
						echo '</table>';
					}
				}
			;
	break;

	case "rptklaimspk":
		echo '
		<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Laporan Fee Dokter</font></th>'.$metnewuser.'</tr></table>

		<form method="post" action="" name="postform">
			<input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			<table border="0" cellpadding="1" cellspacing="0" width="100%">
				<tr>
					<td align="right" width="40%">Tanggal Akad</td>
					<td> : <input type="text" id="fromakad1" name="tglakad1" value="'.$_REQUEST['tglakad1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad1);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
							s/d
							<input type="text" id="fromakad2" name="tglakad2" value="'.$_REQUEST['tglakad2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromakad2);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
					</td>
				</tr>

				<tr>
					<td align="right" width="40%">Tanggal Meninggal</td>
					<td> : <input type="text" id="fromdol1" name="tgldol1" value="'.$_REQUEST['tgldol1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdol1);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdol1);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
							s/d
							<input type="text" id="fromdol2" name="tgldol2" value="'.$_REQUEST['tgldol2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdol2);return false;"/>
						<a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdol2);return false;">
						<img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						<iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
					</td>
				</tr>

				<td align="center"colspan="2"><input type="hidden" name="re" value="dataklaimspk"><input type="submit" name="ere" value="Cari"></td></tr>
			</table>
		</form>';
		if($_REQUEST['re'] == "dataklaimspk"){

			if($_REQUEST['tglakad1']){
				$qs_tgl_akad = ' AND tglakad between "'.$_REQUEST['tglakad1'].'" and "'.$_REQUEST['tglakad2'].'"';
			}
			if($_REQUEST['tgldol1']){
				$qs_tgl_dol = ' AND tgl_klaim between "'.$_REQUEST['tgldol1'].'" and "'.$_REQUEST['tgldol2'].'"';
			}
			echo '
			<table border="1" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
				<tr>
					<td bgcolor="#FFF"colspan="24">
						<a href="e_report.php?er=eL_KlaimSPK&tglakad1=' . $_REQUEST ['tglakad1'] . '&tglakad2=' . $_REQUEST ['tglakad2'] .'&tgldol1=' . $_REQUEST ['tgldol1'] . '&tgldol2=' . $_REQUEST ['tgldol2'].'"><img src="image/excel.png" width="25" border="0">Excel</a>						
					</td>
				</tr>
				<tr>
					<th width="1%">No</th>
					<th>Cabang</th>
					<th width="10%">Mitra</th>
					<th width="10%">Produk</th>
					<th width="10%">ID Peserta</th>
					<th width="10%">Nama Debitur</th>
					<th width="10%">Tgl Lahir</th>
					<th width="10%">Usia</th>
					<th width="10%">Plafond</th>
					<th width="10%">Tuntutan Klaim</th>
					<th width="10%">Tgl Akad</th>
					<th width="10%">J.wkt(th)</th>
					<th width="10%">DOL</th>
					<th width="10%">Akad s/d DOL (hari)</th>
					<th width="10%">EM</th>
					<th width="10%">Dokter Pemeriksa</th>
					<th width="10%">Dokter Klinik</th>
					<th width="10%">SPAK</th>
				</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
				$query = "SELECT * FROM rptklaimspk WHERE 1=1 ".$qs_tgl_akad." ".$qs_tgl_dol." ";
				
				$sqlSPK = mysql_query($query." LIMIT ". $m ." , 25");
				
				$totalRows=mysql_num_rows(mysql_query($query));
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlSPK)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '
					<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
						<td width="1%" align="center">'.$no.'</td>
						<td>'.$datanya_['cabang'].'</td>
						<td>'.$datanya_['nmmitra'].'</td>
						<td>'.$datanya_['nmproduk'].'</td>
						<td>'.$datanya_['id_peserta'].'</td>
						<td>'.$datanya_['nama'].'</td>
						<td align="center">'._convertDate($datanya_['tgl_lahir']).'</td>
						<td align="center">'.$datanya_['usia'].'</td>
						<td align="right">'.duit($datanya_['plafond']).'</td>
						<td align="right">'.duit($datanya_['tuntutan_klaim']).'</td>
						<td align="center">'._convertDate($datanya_['tglakad']).'</td>
						<td align="center">'.$datanya_['tenor'].'</td>
						<td align="center">'._convertDate($datanya_['tgl_klaim']).'</td>
						<td align="center">'.$datanya_['dolvsakad'].'</td>
						<td align="center">'.$datanya_['ext_premi'].'</td>
						<td>'.$datanya_['dokter'].'</td>
						<td>'.$datanya_['dokter_pemeriksa_klinik'].'</td>
						<td>'.$datanya_['spak'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajkspk.php?er=rptklaimspk&re=dataklaimspk&tglakad1='.$_REQUEST['tglakad1'].'&tglakad2='.$_REQUEST['tglakad2'].'&tgldol1='.$_REQUEST['tgldol1'].'&tgldol1='.$_REQUEST['tgldol1'], $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '				
			</table><b>Total Data : <u>' . $totalRows . '</u></b>';
		}

	break;

	case "feepemeriksaandokter":
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Laporan Fee Pemeriksaan Dokter</font></th>'.$metnewuser.'</tr></table>';
		echo '<form method="post" action="" name="postform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">

			<tr><td align="right" width="40%">Tanggal Pemeriksaan</td>
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
		<td align="center"colspan="2"><input type="hidden" name="re" value="datafeedokter"><input type="submit" name="ere" value="Cari"></td></tr>
	  </table>
	  </form>';


		if ($_REQUEST['re']=="datafeedokter") {


			if ($_REQUEST ['tglcheck1'] == "") {
				$error_1 = '<div align="center"><font color="red"><blink>Tanggal Pemeriksaan SPK tidak boleh kosong<br /></div></font></blink>';
			}
			if ($_REQUEST ['tglcheck2'] == "") {
				$error_2 = '<div align="center"><font color="red"><blink>Tanggal Pemeriksaan SPK tidak boleh kosong</div></font></blink>';
			}

			if ($error_1 or $error_2 ) {
				echo $error_1 . '' . $error_2 ;
			} else {
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="24">
					<a href="e_report.php?er=eL_FeePemeriksaanDokter&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0">Excel</a>
					<a href="e_report.php?er=eL_FeePemeriksaanDokterDetailAll&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0">Excell Detail</a></td></tr>
					<th width="1%">No</th>
					<th width="15%">Nama Dokter</th>
					<th width="10%">Jumlah SPK</th>
					<th width="10%">Biaya</th>
					<th width="10%">Total</th>
					<th width="10%">Periode Periksa</th>
					<th width="15%">Atas Nama</th>
					<th width="10%">No Rekening</th>
					<th width="10%">Bank</th>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
				
				$sqlSPK = $database->doQuery("SELECT aa.ID,
					aa.namalengkap,
					aa.norek,
					aa.atas_nama,
					aa.bank_rek,
					aa.nama_cost,
					aa.nama_regional,
					aa.nama_cabang,
					DATE_FORMAT(aa.tgl_periksa,'%M %Y') as tgl,
					sum(aa.jml_pemeriksaan) as jml_pemeriksaan,
					fu_ajk_dokter_fee.fee_dokter,
					sum(aa.jml_pemeriksaan) * fu_ajk_dokter_fee.fee_dokter as total
					FROM (
					SELECT *
					FROM(
					SELECT	user_mobile.ID,
									user_mobile.namalengkap,
									user_mobile.norek,
									user_mobile.atas_nama,
									user_mobile.bank_rek,
									fu_ajk_costumer.`name` as nama_cost,
									fu_ajk_regional.`name` as nama_regional,
									fu_ajk_cabang.`name` as nama_cabang,
									fu_ajk_spak_form.tgl_periksa,
									Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
					FROM
					fu_ajk_spak_form
					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
					LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
					LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
					LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
					where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
					and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
					and (nopermohonan is NULL or nopermohonan = '')
					group BY
					user_mobile.ID,
					user_mobile.namalengkap,
					user_mobile.norek,
					user_mobile.atas_nama,
					user_mobile.bank_rek,
					fu_ajk_cabang.`name`,
					fu_ajk_spak_form.tgl_periksa,
					fu_ajk_regional.`name`,
					fu_ajk_costumer.`name`

					UNION ALL

					SELECT	user_mobile.ID,
									user_mobile.namalengkap,
									user_mobile.norek,
									user_mobile.atas_nama,
									user_mobile.bank_rek,
									fu_ajk_costumer.`name` as nama_cost,
									fu_ajk_regional.`name` as nama_regional,
									fu_ajk_cabang.`name` as nama_cabang,
									fu_ajk_spak_form.tgl_periksa,
									1 AS jml_pemeriksaan
					FROM
					fu_ajk_spak_form
					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
					LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
					LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
					LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
					where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
					and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."' and nopermohonan != ''

					group BY
					user_mobile.ID,
					user_mobile.namalengkap,
					user_mobile.norek,
					user_mobile.atas_nama,
					user_mobile.bank_rek,
					fu_ajk_cabang.`name`,
					fu_ajk_spak_form.tgl_periksa,
					fu_ajk_regional.`name`,
					fu_ajk_costumer.`name`,nopermohonan 
					)as temp)aa
					LEFT JOIN fu_ajk_dokter_fee ON fu_ajk_dokter_fee.id_user_mobile = aa.id
					and DATE_FORMAT(aa.tgl_periksa,'%Y-%m-%d') between fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee
					group by
					aa.ID,
					aa.namalengkap,
					aa.norek,
					aa.atas_nama,
					aa.bank_rek,
					aa.nama_cost,
					aa.nama_regional,
					aa.nama_cabang,
					DATE_FORMAT(aa.tgl_periksa,'%M %Y'),
					fu_ajk_dokter_fee.fee_dokter
					ORDER BY DATE_FORMAT(aa.tgl_periksa,'%Y%m'), aa.namalengkap LIMIT ". $m ." , 25");
					

				$sqlSPK1 = $database->doQuery("SELECT aa.ID,
					aa.namalengkap,
					aa.norek,
					aa.atas_nama,
					aa.bank_rek,
					aa.nama_cost,
					aa.nama_regional,
					aa.nama_cabang,
					DATE_FORMAT(aa.tgl_periksa,'%M %Y') as tgl,
					sum(aa.jml_pemeriksaan) as jml_pemeriksaan,
					fu_ajk_dokter_fee.fee_dokter,
					sum(aa.jml_pemeriksaan) * fu_ajk_dokter_fee.fee_dokter as total
					FROM (
					SELECT *
					FROM(
					SELECT	user_mobile.ID,
									user_mobile.namalengkap,
									user_mobile.norek,
									user_mobile.atas_nama,
									user_mobile.bank_rek,
									fu_ajk_costumer.`name` as nama_cost,
									fu_ajk_regional.`name` as nama_regional,
									fu_ajk_cabang.`name` as nama_cabang,
									fu_ajk_spak_form.tgl_periksa,
									Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
					FROM
					fu_ajk_spak_form
					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
					LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
					LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
					LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
					where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
					and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
					and (nopermohonan is NULL or nopermohonan = '')
					group BY
					user_mobile.ID,
					user_mobile.namalengkap,
					user_mobile.norek,
					user_mobile.atas_nama,
					user_mobile.bank_rek,
					fu_ajk_cabang.`name`,
					fu_ajk_spak_form.tgl_periksa,
					fu_ajk_regional.`name`,
					fu_ajk_costumer.`name`

					UNION ALL

					SELECT	user_mobile.ID,
									user_mobile.namalengkap,
									user_mobile.norek,
									user_mobile.atas_nama,
									user_mobile.bank_rek,
									fu_ajk_costumer.`name` as nama_cost,
									fu_ajk_regional.`name` as nama_regional,
									fu_ajk_cabang.`name` as nama_cabang,
									fu_ajk_spak_form.tgl_periksa,
									1 AS jml_pemeriksaan
					FROM
					fu_ajk_spak_form
					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
					LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
					LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
					LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
					where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
					and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."' and nopermohonan != ''

					group BY
					user_mobile.ID,
					user_mobile.namalengkap,
					user_mobile.norek,
					user_mobile.atas_nama,
					user_mobile.bank_rek,
					fu_ajk_cabang.`name`,
					fu_ajk_spak_form.tgl_periksa,
					fu_ajk_regional.`name`,
					fu_ajk_costumer.`name`,nopermohonan 
					)as temp)aa
					LEFT JOIN fu_ajk_dokter_fee ON fu_ajk_dokter_fee.id_user_mobile = aa.id
					and DATE_FORMAT(aa.tgl_periksa,'%Y-%m-%d') between fu_ajk_dokter_fee.tgl_mulai_fee and fu_ajk_dokter_fee.tgl_akhir_fee
					group by
					aa.ID,
					aa.namalengkap,
					aa.norek,
					aa.atas_nama,
					aa.bank_rek,
					aa.nama_cost,
					aa.nama_regional,
					aa.nama_cabang,
					DATE_FORMAT(aa.tgl_periksa,'%M %Y'),
					fu_ajk_dokter_fee.fee_dokter
					ORDER BY DATE_FORMAT(aa.tgl_periksa,'%Y%m'), aa.namalengkap");
					/*
				$sqlSPK = $database->doQuery("SELECT	SELECT aa.ID,
					aa.namalengkap,
					aa.norek,
					aa.atas_nama,
					aa.bank_rek,
					aa.nama_cost,
					aa.nama_regional,
					aa.nama_cabang,
					DATE_FORMAT(aa.tgl_periksa,'%M %Y') as tgl,
					sum(aa.jml_pemeriksaan) as jml_pemeriksaan,
					fu_ajk_dokter_fee.fee_dokter,
					sum(aa.jml_pemeriksaan) * fu_ajk_dokter_fee.fee_dokter as total
																			FROM
																			fu_ajk_spak_form
																			INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																			LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
																			LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
																			LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
																			LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
																			where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
																			and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
																			and fu_ajk_spak.id_mitra != 23
																			group BY
																			user_mobile.ID,
																			user_mobile.namalengkap,
																			user_mobile.norek,
																			user_mobile.atas_nama,
																			user_mobile.bank_rek,
																			fu_ajk_cabang.`name`,
																			fu_ajk_spak_form.tgl_periksa,
																			fu_ajk_regional.`name`,
																			fu_ajk_costumer.`name`");
					
					$sqlSPK1 = $database->doQuery("	SELECT	user_mobile.ID,
																									user_mobile.namalengkap,
																									user_mobile.norek,
																									user_mobile.atas_nama,
																									user_mobile.bank_rek,
																									fu_ajk_costumer.`name` as nama_cost,
																									fu_ajk_regional.`name` as nama_regional,
																									fu_ajk_cabang.`name` as nama_cabang,
																									fu_ajk_spak_form.tgl_periksa,
																									Count(fu_ajk_cabang.`name`) AS jml_pemeriksaan
																					FROM
																					fu_ajk_spak_form
																					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																					LEFT JOIN user_mobile ON fu_ajk_spak_form.dokter_pemeriksa = user_mobile.id
																					LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
																					LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
																					LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
																					where user_mobile.`type`='Dokter' and  fu_ajk_spak.del IS NULL AND fu_ajk_spak_form.del IS NULL
																					and DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tglcheck1']."'  and '".$_REQUEST ['tglcheck2']."'
																					and fu_ajk_spak.id_mitra != 23
																					group BY
																					user_mobile.ID,
																					user_mobile.namalengkap,
																					user_mobile.norek,
																					user_mobile.atas_nama,
																					user_mobile.bank_rek,
																					fu_ajk_cabang.`name`,
																					fu_ajk_spak_form.tgl_periksa,
																					fu_ajk_regional.`name`,
																					fu_ajk_costumer.`name`");*/
				$totalRows=mysql_num_rows($sqlSPK1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlSPK)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['namalengkap'].'</td>
					<td align="center"><a href="ajkspk.php?er=feepemeriksaandokterdetail&tgl_awal='.$_REQUEST ['tglcheck1'].'&tgl_akhir='.$_REQUEST ['tglcheck2'].'&id_dokter='.$datanya_['ID'].'">'.number_format($datanya_['jml_pemeriksaan']).'</a></td>
					<td align="right">'.number_format($datanya_['fee_dokter'],2).'</td>
					<td align="right">'.number_format($datanya_['total'],2).'</td>
					<td align="center">'.$datanya_['tgl'].'</td>
					<td>'.$datanya_['atas_nama'].'</td>
					<td>'.$datanya_['norek'].'</td>
					<td>'.$datanya_['bank_rek'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajkspk.php?er=feepemeriksaandokter&re=datafeedokter&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
			}
		}
		;
		break;

	case "feepemeriksaandokterdetail":
		$sqldokter = $database->doQuery("select * from user_mobile where id = '".$_REQUEST['id_dokter']."'");
		while($sqldokter_ = mysql_fetch_array($sqldokter)){
		echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Pemeriksaan Oleh : '.$sqldokter_['namalengkap'].'</font></th><th><a href="ajkspk.php?er=feepemeriksaandokter&re=datafeedokter&tglcheck1='.$_REQUEST['tgl_awal'].'&tglcheck2='.$_REQUEST['tgl_akhir'].'"><img src="image/back.png" width="20"></a></th>'.$metnewuser.'</tr></table>';
	}
				echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
					<tr><td bgcolor="#FFF"colspan="24"><a href="e_report.php?er=feepemeriksaandokterdetail&id_dokter='.$_REQUEST ['id_dokter'].'&tgl1=' . $_REQUEST ['tgl_awal'] . '&tgl2=' . $_REQUEST ['tgl_akhir'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
					<th width="1%">No</th>
					<th width="15%">Nama</th>
					<th width="5%">No SPK</th>
					<th width="7%">Tanggal Periksa</th>
					<th width="5%">Usia</th>
					<th width="12%">Cabang</th>
					<th>Keterangan</th>
					</tr>';
				if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

				$sqlSPK = $database->doQuery("SELECT nama,
																							spak,
																							tgl_periksa,
																							x_usia,
																							name,
																							catatan
																			FROM (
																			SELECT fu_ajk_spak_form.nama,
																						fu_ajk_spak.spak,
																						DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																						fu_ajk_spak_form.x_usia,
																						fu_ajk_cabang.name,
																						catatan,
																						fu_ajk_spak_form.nopermohonan
																			FROM fu_ajk_spak_form
																					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk and fu_ajk_spak_form.del is NULL
																					INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																			WHERE dokter_pemeriksa = ".$_REQUEST ['id_dokter']." and 
																						DATE_FORMAT(tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tgl_awal']."' and '".$_REQUEST ['tgl_akhir']."'
																						and (nopermohonan is NULL or nopermohonan = '')		

																			UNION

																			SELECT fu_ajk_spak_form.nama,
																						fu_ajk_spak.spak,
																						DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																						fu_ajk_spak_form.x_usia,
																						fu_ajk_cabang.name,
																						catatan,
																						fu_ajk_spak_form.nopermohonan
																			FROM fu_ajk_spak_form
																					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk and fu_ajk_spak_form.del is NULL
																					INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																			WHERE dokter_pemeriksa = ".$_REQUEST ['id_dokter']." and 
																						DATE_FORMAT(tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tgl_awal']."' and '".$_REQUEST ['tgl_akhir']."' and nopermohonan != ''
																			GROUP BY nopermohonan)as temp
																			ORDER BY tgl_periksa LIMIT ". $m ." , 25");


				$sqlSPK1 = $database->doQuery("SELECT nama,
																							spak,
																							tgl_periksa,
																							x_usia,
																							name,
																							catatan
																			FROM (
																			SELECT fu_ajk_spak_form.nama,
																						fu_ajk_spak.spak,
																						DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																						fu_ajk_spak_form.x_usia,
																						fu_ajk_cabang.name,
																						catatan,
																						fu_ajk_spak_form.nopermohonan
																			FROM fu_ajk_spak_form
																					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																					INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																			WHERE dokter_pemeriksa = ".$_REQUEST ['id_dokter']." and 
																						DATE_FORMAT(tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tgl_awal']."' and '".$_REQUEST ['tgl_akhir']."'
																						and (nopermohonan is NULL or nopermohonan = '')		

																			UNION

																			SELECT fu_ajk_spak_form.nama,
																						fu_ajk_spak.spak,
																						DATE_FORMAT(fu_ajk_spak_form.tgl_periksa,'%d-%m-%Y')as tgl_periksa,
																						fu_ajk_spak_form.x_usia,
																						fu_ajk_cabang.name,
																						catatan,
																						fu_ajk_spak_form.nopermohonan
																			FROM fu_ajk_spak_form
																					INNER JOIN fu_ajk_spak ON fu_ajk_spak.id = fu_ajk_spak_form.idspk
																					INNER JOIN fu_ajk_cabang ON fu_ajk_cabang.id = fu_ajk_spak_form.cabang and fu_ajk_cabang.del is null
																			WHERE dokter_pemeriksa = ".$_REQUEST ['id_dokter']." and 
																						DATE_FORMAT(tgl_periksa,'%Y-%m-%d') between '".$_REQUEST ['tgl_awal']."' and '".$_REQUEST ['tgl_akhir']."' and nopermohonan != ''
																			GROUP BY nopermohonan )as temp
																			ORDER BY tgl_periksa");

				$totalRows=mysql_num_rows($sqlSPK1);
				$no=$m+1;
				$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
				while($datanya_ = mysql_fetch_array($sqlSPK)){
					if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<td width="1%" align="center">'.$no.'</td>
					<td>'.$datanya_['nama'].'</td>
					<td align="center">'.$datanya_['spak'].'</td>
					<td align="center">'.$datanya_['tgl_periksa'].'</td>
					<td align="center">'.$datanya_['x_usia'].'</td>
					<td align="center">'.$datanya_['name'].'</td>
					<td>'.$datanya_['catatan'].'</td>
					</tr>';
					$no++;
				}
				echo createPageNavigations($file = 'ajkspk.php?er=feepemeriksaandokterdetail&re=datafeedokter&tgl_awal='.$_REQUEST['tgl_awal'].'&tgl_akhir='.$_REQUEST['tgl_akhir'].'&id_dokter='.$_REQUEST['id_dokter'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
				echo '<b>Total Data : <u>' . $totalRows . '</u></b></td></tr>';
				echo '</table>';
		;
		break;

		case "rkp_user":
			echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Rekapitulasi Asuransi</font></th>'.$metnewuser.'</tr></table>';
			$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
			echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> :
	  		<select name="id_cost" id="id_cost">
		  	<option value="">---Pilih Perusahaan---</option>';
			while($metcost_ = mysql_fetch_array($metcost)) {
				echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
			}
			echo '</select></td></tr>
			<tr><td align="right">Regional</td><td>: <select name="id_reg" id="id_reg"><option value="">-- Pilih Regional --</option></select></td></tr>
	  		<tr><td align="right">Cabang</td><td>: <select name="id_cab" id="id_cab"><option value="">-- Pilih Cabang --</option></select></td></tr>
	  <tr><td align="right">Tipe User</td><td>:
	  <select name="tipe">
		<option value="Marketing" '._selected($_REQUEST['tipe'],'Marketing').'>Marketing</option>
		<option value="Dokter" '._selected($_REQUEST['tipe'],'Dokter').'>Dokter</option>
		</select>
	  </td></tr>
	  <tr><td align="right">Tanggal Pemeriksaan</td>
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
					<tr><td bgcolor="#FFF"colspan="24">
						<a href="e_report.php?er=eL_User_SPK&idCost=' . $_REQUEST ['id_cost'] . '&tipe='.$_REQUEST['tipe'].'&idReg=' . $_REQUEST ['id_reg'] . '&idCab=' . $_REQUEST ['id_cab'] . '&tgl1=' . $_REQUEST ['tglcheck1'] . '&tgl2=' . $_REQUEST ['tglcheck2'] . '"><img src="image/excel.png" width="25" border="0"><br />Excel</a>
						<a href="#"><img src="image/pdf.png" width="25" border="0"><br />PDF</a>
					</td></tr>
					<th width="1%">No</th>
					<th>Cabang</th>
					<th width="30%">User Input</th>
					<th width="20%">Jml Data</th>
					</tr>';

					$sql="";
					if($_REQUEST['id_reg']!=""){
						$sql=" and fu_ajk_regional.id=".$_REQUEST['id_reg'];
					}

					if($_REQUEST['id_cab']){
						$sql=$sql." and fu_ajk_cabang.id=".$_REQUEST['id_cab'];
					}


					if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}

					if($_REQUEST['tipe']=='Dokter'){

					$sqlSPK = $database->doQuery("SELECT
						fu_ajk_costumer.`name` AS nama_cost,
						fu_ajk_regional.`name` AS nama_regional,
						ifnull(fu_ajk_cabang.`name`,aa.cabang) AS nama_cabang,
						aa.namalengkap,
						COUNT(aa.id) AS jml
						FROM
						(
						SELECT
						fu_ajk_spak_form.id,
						IFNULL(user_mobile.cabang,4) AS cabang,
						IF(`pengguna`.`nm_lengkap` IS NOT NULL, `pengguna`.`nm_lengkap`,`user_mobile`.`namalengkap`) AS namalengkap,
						fu_ajk_spak_form.input_date
						FROM
						    `fu_ajk_spak_form`
						    LEFT JOIN `user_mobile`
						        ON (`fu_ajk_spak_form`.`dokter_pemeriksa` = `user_mobile`.`id`)
						    LEFT JOIN `pengguna`
						        ON (`fu_ajk_spak_form`.`input_by` = `pengguna`.`nm_user`)
						WHERE `user_mobile`.`namalengkap` IS NOT NULL
						) aa
						LEFT JOIN fu_ajk_cabang ON aa.cabang = fu_ajk_cabang.id
						LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where  fu_ajk_costumer.id=".$_REQUEST['id_cost']." and aa.input_date between '".$_REQUEST['tglcheck1']."' and '".$_REQUEST['tglcheck2']."' ".$sql."
						GROUP BY
						fu_ajk_costumer.`name`,
						fu_ajk_regional.`name`,
						fu_ajk_cabang.`name`,
						aa.namalengkap
						LIMIT ". $m ." , 25");

					$sqlSPK1 = $database->doQuery("SELECT
						fu_ajk_costumer.`name` AS nama_cost,
						fu_ajk_regional.`name` AS nama_regional,
						ifnull(fu_ajk_cabang.`name`,aa.cabang) AS nama_cabang,
						aa.namalengkap,
						COUNT(aa.id) AS jml
						FROM
						(
						SELECT
						fu_ajk_spak_form.id,
						IFNULL(user_mobile.cabang,4) AS cabang,
						IF(`pengguna`.`nm_lengkap` IS NOT NULL, `pengguna`.`nm_lengkap`,`user_mobile`.`namalengkap`) AS namalengkap,
						fu_ajk_spak_form.input_date
						FROM
						    `fu_ajk_spak_form`
						    LEFT JOIN `user_mobile`
						        ON (`fu_ajk_spak_form`.`dokter_pemeriksa` = `user_mobile`.`id`)
						    LEFT JOIN `pengguna`
						        ON (`fu_ajk_spak_form`.`input_by` = `pengguna`.`nm_user`)
						WHERE `user_mobile`.`namalengkap` IS NOT NULL
						) aa
						LEFT JOIN fu_ajk_cabang ON aa.cabang = fu_ajk_cabang.id
						LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						where  fu_ajk_costumer.id=".$_REQUEST['id_cost']." and aa.input_date between '".$_REQUEST['tglcheck1']."' and '".$_REQUEST['tglcheck2']."' ".$sql."
						GROUP BY
						fu_ajk_costumer.`name`,
						fu_ajk_regional.`name`,
						fu_ajk_cabang.`name`,
						aa.namalengkap");

					}elseif($_REQUEST['tipe']=='Marketing'){

					$sqlSPK = $database->doQuery("SELECT
						fu_ajk_costumer.`name` AS nama_cost,
						fu_ajk_regional.`name` AS nama_regional,
						ifnull(fu_ajk_cabang.`name`,aa.cabang) AS nama_cabang,
						aa.namalengkap,
						COUNT(aa.id) AS jml
						FROM
						(
						SELECT
						fu_ajk_spak.id,
						IFNULL(user_mobile.cabang,4) AS cabang,
						IF(`pengguna`.`nm_lengkap` IS NOT NULL, `pengguna`.`nm_lengkap`,`user_mobile`.`namalengkap`) AS namalengkap,
						fu_ajk_spak.input_date
						FROM
						    `fu_ajk_spak`
						    LEFT JOIN `user_mobile`
						        ON (`fu_ajk_spak`.`input_by` = `user_mobile`.`id`)
						    LEFT JOIN `pengguna`
						        ON (`fu_ajk_spak`.`input_by` = `pengguna`.`nm_user`)
						) aa
						LEFT JOIN fu_ajk_cabang ON aa.cabang = fu_ajk_cabang.id
						LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						WHERE  fu_ajk_costumer.id=".$_REQUEST['id_cost']." AND aa.input_date BETWEEN '".$_REQUEST['tglcheck1']."' AND '".$_REQUEST['tglcheck2']."' ".$sql."
						GROUP BY
						fu_ajk_costumer.`name`,
						fu_ajk_regional.`name`,
						fu_ajk_cabang.`name`,
						aa.namalengkap
						LIMIT ". $m ." , 25");

					$sqlSPK1 = $database->doQuery("SELECT
						fu_ajk_costumer.`name` AS nama_cost,
						fu_ajk_regional.`name` AS nama_regional,
						ifnull(fu_ajk_cabang.`name`,aa.cabang) AS nama_cabang,
						aa.namalengkap,
						COUNT(aa.id) AS jml
						FROM
						(
						SELECT
						fu_ajk_spak.id,
						IFNULL(user_mobile.cabang,4) AS cabang,
						IF(`pengguna`.`nm_lengkap` IS NOT NULL, `pengguna`.`nm_lengkap`,`user_mobile`.`namalengkap`) AS namalengkap,
						fu_ajk_spak.input_date
						FROM
						    `fu_ajk_spak`
						    LEFT JOIN `user_mobile`
						        ON (`fu_ajk_spak`.`input_by` = `user_mobile`.`id`)
						    LEFT JOIN `pengguna`
						        ON (`fu_ajk_spak`.`input_by` = `pengguna`.`nm_user`)
						) aa
						LEFT JOIN fu_ajk_cabang ON aa.cabang = fu_ajk_cabang.id
						LEFT JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id
						LEFT JOIN fu_ajk_costumer ON fu_ajk_regional.id_cost = fu_ajk_costumer.id
						WHERE  fu_ajk_costumer.id=".$_REQUEST['id_cost']." AND aa.input_date BETWEEN '".$_REQUEST['tglcheck1']."' AND '".$_REQUEST['tglcheck2']."' ".$sql."
						GROUP BY
						fu_ajk_costumer.`name`,
						fu_ajk_regional.`name`,
						fu_ajk_cabang.`name`,
						aa.namalengkap");

					}


					$totalRows=mysql_num_rows($sqlSPK1);
					$no=$m+1;
					$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
					while($datanya_ = mysql_fetch_array($sqlSPK)){
						if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';


						echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							<td width="1%" align="center">'.$no.'</td>
							<td>'.$datanya_['nama_cabang'].'</td>
							<td>'.$datanya_['namalengkap'].'</td>
							<td>'.$datanya_['jml'].'</td>
							</tr>';
						$no++;
					}
					echo createPageNavigations($file = 'ajkspk.php?er=rkp_user&re=datarkpuser&id_cost='.$_REQUEST['id_cost'].'&tipe='.$_REQUEST['tipe'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
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