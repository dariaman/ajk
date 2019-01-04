<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// Relife - AJK Online 2013
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
switch ($_REQUEST['h']) {
	case "a":
		;
		break;
	case "tab":
		echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">History CLient Tablet</font></th></tr></table>';
		echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
					<form method="post" action="" name="postform">
					<tr><td width="10%">Tanggal Login : </td><td> : <input type="text" id="fromdn1" name="tgldn1" value="'.$_REQUEST['tgldn1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
								 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
								 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
								 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
								 s/d
								 <input type="text" id="fromdn2" name="tgldn2" value="'.$_REQUEST['tgldn2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
								 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
								 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
								 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td></tr>
					  <tr><td colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
					</form>
					</table>';
		if ($_REQUEST['tgldn1'])	{	$satu = 'AND DATE(fu_ajk_user_mobile_history.`timestamp`) BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';	}
		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 100;	}	else {	$m = 0;		}
		//$kucing = $database->doQuery('SELECT * FROM fu_ajk_user_mobile_history WHERE id != "" '.$satu.' ORDER BY `timestamp` DESC LIMIT ' . $m . ' , 100');
echo "xx";
exit;

		$kucing = $database->doQuery('SELECT
fu_ajk_user_mobile_history.iduser,
fu_ajk_user_mobile_history.ipuser,
fu_ajk_user_mobile_history.module,
fu_ajk_user_mobile_history.`timestamp`,
user_mobile.type,
user_mobile.nama,
user_mobile.namalengkap,
fu_ajk_spak.spak,
fu_ajk_spak.input_date,
fu_ajk_cabang.`name`
FROM
fu_ajk_user_mobile_history
INNER JOIN user_mobile ON fu_ajk_user_mobile_history.iduser = user_mobile.id
LEFT JOIN fu_ajk_spak ON fu_ajk_user_mobile_history.iduser = fu_ajk_spak.input_by
LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
WHERE fu_ajk_user_mobile_history.id != "" '.$satu.'
ORDER BY fu_ajk_user_mobile_history.`timestamp` DESC LIMIT ' . $m . ' , 100');
		$jumkucing = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_user_mobile_history.id) FROM fu_ajk_user_mobile_history
												   INNER JOIN user_mobile ON fu_ajk_user_mobile_history.iduser = user_mobile.id
												   LEFT JOIN fu_ajk_spak ON fu_ajk_user_mobile_history.iduser = fu_ajk_spak.input_by
												   LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
												   WHERE fu_ajk_user_mobile_history.id != "" '.$satu.''));
		$jumkucing = $jumkucing[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		if ($_REQUEST['button']=="Cari") {
			echo '<a href="e_report.php?er=eL_HistUserTab&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'"><img src="image/excel.png" width="35" border="0"></a>';
		}
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
					  <tr><th width="3%">No</th>
					  	  <th>USER</th>
					  	  <th width="5%">Status</th>
					  	  <!--<th width="10%">Date Time</th>-->
					  	  <th width="5%">IP Address</th>
					  	  <th width="5%">Nomor SPK</th>
					  	  <th width="10%">Tanggal SPK</th>
					  	  <th width="5%">Cabang</th>
					  </tr>';
		while ($jangkrik = mysql_fetch_array($kucing)) {
			/*
			   $namajangkrik = mysql_fetch_array($database->doQuery('SELECT fu_ajk_cabang.`name` AS cabang,
			   user_mobile.id,
			   user_mobile.type,
			   user_mobile.`level`,
			   user_mobile.supervisor,
			   user_mobile.`status`,
			   user_mobile.namalengkap
			   FROM user_mobile
			   LEFT JOIN fu_ajk_cabang ON user_mobile.cabang = fu_ajk_cabang.id
			   WHERE user_mobile.id="'.$jangkrik['iduser'].'"'));
			   $jumlahSPK = mysql_fetch_array($database->doQuery('SELECT input_by, COUNT(spak) AS jSPAK FROM fu_ajk_spak WHERE input_by="'.$jangkrik['iduser'].'" GROUP BY input_by'));
			*/
			if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			      <td align="center">'.(++$no + ($pageNow-1) * 100).'</td>
			      <td>'.$jangkrik['namalengkap'].'</td>
			      <td>'.$jangkrik['type'].'</td>
			      <!--<td align="center">'.$jangkrik['timestamp'].'</td>-->
			      <td align="center">'.$jangkrik['ipuser'].'</td>
			      <td align="center">'.$jangkrik['spak'].'</td>
			      <td align="center">'.$jangkrik['input_date'].'</td>
			      <td align="center">'.$jangkrik['name'].'</td>

				  </tr>';
		}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'trj_log.php?h=tab&button='.$_REQUEST['button'].'&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'', $total = $jumkucing, $psDeh = 10 , $anchor = '', $perPage = 100);
		echo '<b>Total Data History : <u>' . $jumkucing . '</u></b></td></tr>';
		echo '</table>';
		;
		break;

	default:
		echo '<table border="0" cellpadding="5" cellspacing="1" width="100%"><tr><th width="100%" align="left">History CLient Website</font></th></tr></table>';
		echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
			<form method="post" action="" name="postform">
			<tr><td width="10%">Tanggal Login : </td><td> : <input type="text" id="fromdn1" name="tgldn1" value="'.$_REQUEST['tgldn1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
						 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
						 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
						 s/d
						 <input type="text" id="fromdn2" name="tgldn2" value="'.$_REQUEST['tgldn2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
						 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
						 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td></tr>
			  <tr><td colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
			</form>
			</table>';
		if ($_REQUEST['tgldn1'])		{	$satu = 'AND lastdate_login BETWEEN "'.$_REQUEST['tgldn1'].'" AND "'.$_REQUEST['tgldn2'].'"';	}
		if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 100;	}	else {	$m = 0;		}
		$kucing = $database->doQuery('SELECT * FROM ajk_logger WHERE id != "" '.$satu.' ORDER BY id DESC LIMIT ' . $m . ' , 100');
		$jumkucing = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM ajk_logger WHERE id != "" '.$satu.''));
		$jumkucing = $jumkucing[0];
		$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
		if ($_REQUEST['button']=="Cari") {
			echo '<a href="e_report.php?er=eL_HistUser&tgldn1='.$_REQUEST['tgldn1'].'&tgldn2='.$_REQUEST['tgldn2'].'"><img src="image/excel.png" width="35" border="0"></a>';
		}
		echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
			  <tr><th width="3%">No</th>
			  	  <th width="5%">USER</th>
			  	  <th width="20%">Client</th>
			  	  <th width="10%">Login Date Time</th>
			  	  <th width="10%">Logout Date Time</th>
			  	  <th width="5%">IP Address</th>
			  	  <th width="15%">Komputer Name</th>
			  	  <th>Browser</th>
			  </tr>';
		while ($jangkrik = mysql_fetch_array($kucing)) {
			$namajangkrik = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id="'.$jangkrik['id_user'].'"'));
			$namagajah = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$namajangkrik['id_cost'].'"'));
			if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
			echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			      <td align="center">'.(++$no + ($pageNow-1) * 100).'</td>
			      <td>'.$namajangkrik['nm_lengkap'].'</td>
			      <td>'.$namagajah['name'].'</td>
			      <td align="center">'.$jangkrik['lastdate_login'].' - '.$jangkrik['lasttime_login'].'</td>
			      <td align="center">'.$jangkrik['lastdate_logout'].' - '.$jangkrik['lasttime_logout'].'</td>
			      <td align="center">'.$jangkrik['user_ip'].'</td>
			      <td>'.$jangkrik['user_referer'].'</td>
			      <td>'.$jangkrik['user_browser'].'</td>

				  </tr>';
		}
		echo '<tr><td colspan="22">';
		echo createPageNavigations($file = 'trj_log.php?tgldn1='.$_REQUEST['tgldn1'].'&button='.$_REQUEST['button'].'&tgldn2='.$_REQUEST['tgldn2'].'', $total = $jumkucing, $psDeh = 10 , $anchor = '', $perPage = 100);
		echo '<b>Total Data History : <u>' . $jumkucing . '</u></b></td></tr>';
		echo '</table>';
	;
} // switch

//$url = $_SERVER['REQUEST_URI'];
//header("Refresh: 10; URL=$url");
?>
