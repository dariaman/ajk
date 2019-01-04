<?php
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Compare System</font></th></tr></table>';
switch ($_REQUEST['c']) {

	default:
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
$ls_cost = $metcost['id'];
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	  	<tr>
	  		<td width="35%" align="right">Tanggal DN <font color="red">*</font>
	  		</td>
		  <td> :<input type="text" id="fromdn1" name="tgldn1" value="'.$_REQUEST['tglakad1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tgldn2" value="'.$_REQUEST['tglakad2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="dataDN") {
	if ($_REQUEST['tgldn1']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Tanggal mulai DN tidak boleh kosong<br /></div></font></blink>';	}
	if ($_REQUEST['tgldn2']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal akhir DN tidak boleh kosong</div></font></blink>';	}
	if ($error_1 OR $error_2) {	
		echo $error_1 .''.$error_2;	
	}else{
			
			$database->doQuery("call sp_compare('".$_REQUEST['tgldn1']."','".$_REQUEST['tgldn2']."')");
			$qpeserta	= $database->doQuery("select * from v_compare where usia_system != usia_manual or premi_system != premi_manual or rate_system != rate_manual");
		
		
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
				<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
					<th>Id Peserta</th>
					<th>Usia System</th>
					<th>Usia Manual</th>
					<th>Premi System</th>
					<th>Premi Manual</th>
					<th>Rate System</th>					
					<th>Rate Manual</th>
				</tr>';
				while($qpeserta_r = mysql_fetch_array($qpeserta)){																														
					if (($no % 2) == 1) $objlass = 'tbl-odd'; else $objlass = 'tbl-even';			
echo			'	<tr>
							<td align="center">'.$qpeserta_r['id_peserta'].'</td>
							<td align="center">'.$qpeserta_r['usia_system'].'</td>
							<td align="center">'.$qpeserta_r['usia_manual'].'</td>
							<td align="center">'.duit($qpeserta_r['premi_system']).'</td>
							<td align="center">'.duit($qpeserta_r['premi_manual']).'</td>
							<td align="center">'.$qpeserta_r['rate_system'].'</td>
							<td align="center">'.$qpeserta_r['rate_manual'].'</td>
						</tr>';
				}				

echo			'</table>';		
	}
}else{
	if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
	if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}	
}

		;
} // switch
?>
