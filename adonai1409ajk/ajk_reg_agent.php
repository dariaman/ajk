<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
$riweuh = explode("/", "04/04/1953");			$cektglnya = $riweuh[0].'-'.$riweuh[1].'-'.$riweuh[2];							// FORMULA USIA
$riweuh2 = explode("/", "27/08/2014");			$cektglnya2 = $riweuh2[0].'-'.$riweuh2[1].'-'.$riweuh2[2];						// FORMULA USIA
$umur = ceil(((strtotime($cektglnya2) - strtotime($cektglnya."+6 month")) / (60*60*24*365.2425)));
switch ($_REQUEST['op']) {
	case "new":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
     <tr><th width="98%" align="left">Modul Agents - Tambah</font></th>
     	 <th align="center"><a href="ajk_reg_agent.php"><img border="0" src="image/Backward-64.png" width="25"></a></th>
     </tr>
     </table><br />';
if ($_REQUEST['ope']=="Simpan") {
	$_REQUEST['nama'] = $_POST['nama'];
	$_REQUEST['periode'] = $_POST['periode'];
	$_REQUEST['msname'] = $_POST['msname'];
	if (!$_REQUEST['nama'])  $error1 .='<blink><font color=red>Nama tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['periode'])  $error2 .='<blink><font color=red>Join date tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['phone'])  $error3 .='<blink><font color=red>Nomor telephone tidak boleh kosong</font></blink><br>';
	if ($error1 OR $error2 OR $error3)
	{		}
	else
	{
	$ceklvl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_master WHERE msname="'.$_REQUEST['msname'].'"'));
	//echo $_REQUEST['msname'].' | '.$ceklvl['msorder'].'<br />';
	$futgl = date("y-m"); $code = 'AJK-'.$futgl.'';
	$met = $database->doQuery('INSERT INTO fu_ajk_agent SET name="'.$_REQUEST['nama'].'",
														 code="'.$code.'",
														 joindate="'.$_REQUEST['periode'].'",
														 level="'.$ceklvl['msorder'].'",
														 phone="'.$_REQUEST['phone'].'",
														 npwp="'.$_REQUEST['npwp'].'",
														 aaji="'.$_REQUEST['aaji'].'",
														 term="'.$_REQUEST['term'].'",
														 input_by="'.$_SESSION['nm_user'].'",
														 input_date="'.$datelog.'" ');
	echo '<script language="Javascript">window.location="ajk_reg_agent.php"</script>';
	}
}
$msc = $database->doQuery('SELECT fu_ajk_master.msname FROM fu_ajk_master WHERE fu_ajk_master.msflag = "AGENTLEVEL"  ORDER BY msorder');

echo '<form method="POST" action="" class="input-list style-1 smart-green" name="jalurdistagent">
	<h1>Penambahan Data Agent</h1>
	<label><span>Jalur Distribusi <font color="red">*</font> '.$error1.'</span>
		<select size="1" name="jalurdist" id="jalurdist" onchange="enabledisabletext()">
	   			<option value="">--- Pilih ---</option>
	   			<option value="Broker"'._selected($_REQUEST['jalurdist'],"Broker").'>Broker</option>
	   			<option value="Direct"'._selected($_REQUEST['jalurdist'],"Direct").'>Direct</option>
	   			<option value="Unblinger"'._selected($_REQUEST['jalurdist'],"Unblinger").'>Unblinger</option>
	   	</select>
	</label>
	<label><span>Nama Perusahaan <font color="red">*</font> '.$error1.'</span><input type="text" name="namaperusahaan" value="'.$_REQUEST['namaperusahaan'].'" placeholder="Nama Perusahaan"></label>
	<label><span>Nama Unbringer <font color="red">*</font> '.$error1.'</span><input type="text" name="namaunbringer" value="'.$_REQUEST['namaunbringer'].'" placeholder="Nama Unbringer"></label>
	<label><span>Nama Penutup (Agent)<font color="red">*</font> '.$error1.'</span><input type="text" name="nama" value="'.$_REQUEST['nama'].'" size="30" placeholder="Nama Lengkap"></label>
	<label><span>Join Date <font color="red">*</font> '.$error2.'</span><input type="text" name="periode" id="periode" class="tanggal" value="'.$_REQUEST['periode'].'" size="10"/></label>
	  <label><span>Level</span>
	  		<select id="msname" name="msname">
	  		<option value="">-----Select Level-----</option>';
while ($agent = mysql_fetch_array($msc)) {	echo '<option value="'.$agent['msname'].'">'.$agent['msname'].'</option>';	}
echo '</select></label>
	<label><span>Phone <font color="red">*</font> '.$error3.'</span><input type="text" name="phone" value="'.$_REQUEST['phone'].'" size="20" placeholder="Hp / Tlp"></label>
	<label><span>NPWP</span><input type="text" name="npwp" value="'.$_REQUEST['npwp'].'" size="30" placeholder="Nomor NPWP"></label>
	<label><span>AAJI</span><input type="text" name="aaji" value="'.$_REQUEST['aaji'].'" size="30" placeholder="Nomor AAJI"></label>
	<label><span>Termination</span><input type="text" name="term" id="term" class="tanggal" value="'.$_REQUEST['term'].'" size="10"/></label>
	  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
	  </form>';
		;
		break;

	case "delagent":
$metdel = $database->doQuery('UPDATE fu_ajk_agent SET del="1", update_by="'.$_SESSION['nm_user'].'", update_date="'.$datelog.'" WHERE id="'.$_REQUEST['ida'].'"');
header("location:ajk_reg_agent.php");
	;
	break;

	case "edit":
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
     <tr><th width="98%" align="left">Modul Agents - Edit</font></th>
     	 <th align="center"><a href="ajk_reg_agent.php"><img border="0" src="image/Backward-64.png" width="25"></a></th>
     </tr>
     </table><br />';
$metagent = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_agent WHERE id="'.$_REQUEST['ida'].'"'));

if ($_REQUEST['ope']=="Simpan") {
	$_REQUEST['nama'] = $_POST['nama'];
	$_REQUEST['periode'] = $_POST['periode'];
	$_REQUEST['msname'] = $_POST['msname'];
	if (!$_REQUEST['nama'])  $error1 .='<blink><font color=red>Nama tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['periode'])  $error2 .='<blink><font color=red>Join date tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['phone'])  $error3 .='<blink><font color=red>Nomor telephone tidak boleh kosong</font></blink><br>';
	if ($error1 OR $error2 OR $error3)
	{		}
	else
	{
	$ceklvl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_master WHERE msname="'.$_REQUEST['msname'].'"'));
	//echo $_REQUEST['msname'].' | '.$ceklvl['msorder'].'<br />';
	$met = $database->doQuery('UPDATE fu_ajk_agent SET name="'.$_REQUEST['nama'].'",
														 joindate="'.$_REQUEST['periode'].'",
														 level="'.$ceklvl['msorder'].'",
														 phone="'.$_REQUEST['phone'].'",
														 npwp="'.$_REQUEST['npwp'].'",
														 aaji="'.$_REQUEST['aaji'].'",
														 term="'.$_REQUEST['term'].'",
														 update_by="'.$_SESSION['nm_user'].'",
														 update_date="'.$datelog.'"
														 WHERE id="'.$_REQUEST['ida'].'"');
	echo '<script language="Javascript">window.location="ajk_reg_agent.php"</script>';
	}
}
echo '<form method="POST" action=""class=" input-list style-1 smart-green">
		<h1>Edit Data Agent</h1>
		<label><span>Nama <font color="red">*</font> '.$error1.'</span><input type="text" name="nama" value="'.$metagent['name'].'" size="30" placeholder="Nama Lengkap"></label>
		<label><span>Join Date <font color="red">*</font> '.$error2.'</span>';
echo initCalendar();
echo calendarBox('periode', 'triger', $metagent['joindate']);
echo '</label>
	  <label><span>Level</span>
	  		<select id="msname" name="msname">
	  		<option value="">-----Select Level-----</option>';
$msc = $database->doQuery('SELECT * FROM fu_ajk_master WHERE msflag = "AGENTLEVEL" ORDER BY msorder');
while ($agent = mysql_fetch_array($msc)) {	echo '<option value="'.$agent['msorder'].'"'._selected($metagent['level'], $agent['msorder']).'>'.$agent['msname'].'</option>';	}
echo '</select></label>
		<label><span>Phone <font color="red">*</font> '.$error3.'</span><input type="text" name="phone" value="'.$metagent['phone'].'" size="20" placeholder="Hp / Tlp"></label>
		<label><span>NPWP</span><input type="text" name="npwp" value="'.$metagent['npwp'].'" size="30" placeholder="Nomor NPWP"></label>
		<label><span>AAJI</span><input type="text" name="aaji" value="'.$metagent['aaji'].'" size="30" placeholder="Nomor AAJI"></label>
		<label><span>Termination</span>';
echo initCalendar();
echo calendarBox('term', 'triger2', $metagent['term']);
echo '</label>
	  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
	  </form>';
		;
		break;
	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	  <tr><th width="95%" align="left">Modul Agents</font></th>
     	  <th align="center"><a href="ajk_reg_agent.php?op=new"><img src="image/new.png" border="0" width="25"></a></th>
     	  </tr>
     	  </table><br />';
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" bgcolor="#bde0e6">
	<tr>
		<th width="1%">No</th>
		<th width="10%">Code Agent</th>
		<th>Name</th>
		<th width="8%">Join Date</th>
		<th width="15%">Level</th>
		<th width="8%">Phone</th>
		<th width="10%">NPWP</th>
		<th width="15%">AAJI Code</th>
		<th width="10%">Termination</th>
		<th width="5%">Option</th>
	</tr>';
$agen = $database->doQuery('SELECT * FROM fu_ajk_agent WHERE del IS NULL ORDER BY id ASC');
while ($met = mysql_fetch_array($agen)) {
$ceklvl = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_master WHERE msflag="AGENTLEVEL" AND msorder = "'.$met['level'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else				$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.++$no.'</td>
	<td align="center">'.$met['code'].'</td>
	<td>'.$met['name'].'</td>
	<td align="center">'.$met['joindate'].'</td>
	<td align="center">'.$ceklvl['msname'].'</td>
	<td>'.$met['phone'].'</td>
	<td align="center">'.$met['npwp'].'</td>
	<td align="center">'.$met['aaji'].'</td>
	<td align="center">'.$met['term'].'</td>
	<td align="center"><a href="ajk_reg_agent.php?op=edit&ida='.$met['id'].'"><img src="../image/edit3.png"></a>
	<a href="ajk_reg_agent.php?op=delagent&ida='.$met['id'].'" onClick="if(confirm(\'Anda yakin akan menghapus data agent ini?\')){return true;}{return false;}"><img src="../image/delete1.png"></a>
	</td>
</tr>';
}
echo '</table>';
;
} // switch

?>
<script language="javascript">
    function enabledisabletext()
    {
    	if(document.jalurdistagent.jalurdist.value!='1')
    	{
    		document.distextkel.namaperusahaan.disabled=false;
    		document.distextkel.eralamattg.disabled=false;
    		document.distextkel.erdobttg.disabled=false;
    		document.getElementById("ergenderttgM").disabled=false;
    		document.getElementById("ergenderttgF").disabled=false;
    	}
    	if(document.jalurdistagent.jalurdist.value=='1')
    	{
    		document.distextkel.namaperusahaan.disabled=true;
    		document.distextkel.eralamattg.disabled=true;
    		document.distextkel.erdobttg.disabled=true;
    		document.getElementById("ergenderttgM").disabled=true;
    		document.getElementById("ergenderttgF").disabled=true;
    	}
    }
    </script>