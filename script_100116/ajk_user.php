<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));
$qsescost=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
}
switch($_REQUEST['op']){
	case "Updated":
$_REQUEST['nm_userna'] = $_POST['nm_userna'];	if (!$_REQUEST['nm_userna'])  $error .='<blink><font color=red>Nama User tidak boleh kosong</font></blink><br>';
$_REQUEST['passwordna'] = $_POST['passwordna'];	if (!$_REQUEST['passwordna'])  $error .='<blink><font color=red>Password tidak boleh kosong</font></blink><br>';
$_REQUEST['emailna'] = $_POST['emailna'];	if (!$_REQUEST['emailna'])  $error .='<blink><font color=red>Email tidak boleh kosong</font></blink><br>';
$_REQUEST['nm_lengkapna'] = $_POST['nm_lengkapna'];	if (!$_REQUEST['nm_lengkapna'])  $error .='<blink><font color=red>Nama Lengkap tidak boleh kosong</font></blink><br>';

	if ($error)
	{	echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: solid 1px red">
			  <tr><td><table width="100%" class="bgcolor1">
					  <tr><td align="left"><img src="image/warning.gif" border="0"></td>
						  <td align="center"><font class="option"><blink>'.$error.'</blink></td>
						  <td align="right"><img src="image/warning.gif" border="0"></td>
					  </tr>
			  </table></td></tr>
			  </table><meta http-equiv="refresh" content="2; url=ajk_user.php?op=edit&id='.$_REQUEST['id'].'">';
	}
	else
	{
	$u=$database->doQuery('UPDATE pengguna SET nm_user="'.$_REQUEST['nm_userna'].'",
	  								  		   password="'.md5($_REQUEST['passwordna']).'",
	  								  		   rahmad="'.$_REQUEST['passwordna'].'",
									  		   email="'.$_REQUEST['emailna'].'",
									  		   nm_lengkap="'.$_REQUEST['nm_lengkapna'].'",
									  		   gender="'.$_REQUEST['genderna'].'",
									  		   dob="'.$_REQUEST['dobna'].'"
									  		   WHERE id="'.$_REQUEST['id'].'"');
	//header("location:ajk_user.php");
echo '<center>Data peserta telah di edit</center><meta http-equiv="refresh" content="2; url=ajk_user.php">';
}
	;
	break;

	case "edit":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="100%" align="left">Edit User</th></tr>
      </table></br>';

$e=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id="'.$_REQUEST['id'].'"'));
$ecost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$e['id_cost'].'"'));
if ($e['status']=="") { $metlev = "SUPERVISOR";	}	else	{	$metlev = $e['status'];	}

echo '<table border="0" width="100%" style="border: solid 1px #DEDEDE">
      <form method="post" action="ajk_user.php">
      <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
      <tr>
      <td bgcolor="DEDEDE" width="15%">Company</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td><b>'.$ecost['name'].'</b></td>
      </tr>
	  <tr>
      <tr>
      <td bgcolor="DEDEDE" width="15%">Level Access</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td><b>'.$metlev.'</b></td>
      </tr>
	  <tr>
      <td bgcolor="DEDEDE" width="15%">Username</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td><input type="text" name="nm_userna" value="'.$e['nm_user'].'" size="25">	<font color="red">*</font></td>
      </tr>
      <tr>
      <td bgcolor="DEDEDE" width="15%">Gender</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td><input type="radio"'.pilih($e['gender'], "L").' name="genderna" value="L">Laki-laki
		  <input type="radio"'.pilih($e['gender'], "P").' name="genderna" value="P">Perempuan </td>
      </tr>
      <tr>
      <td bgcolor="DEDEDE" width="15%">Full name</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td><input type="text" name="nm_lengkapna" value="'.$e['nm_lengkap'].'" size="35">	<font color="red">*</font></td>
      </tr>
	  <tr>
      <td bgcolor="DEDEDE" width="15%">D O B</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td>';echo initCalendar();	echo calendarBox('dobna', 'triger', $el['dob']);
echo '</td>
      </tr>
      <tr>
      <td bgcolor="DEDEDE" width="15%">Password</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td><input type="password" name="passwordna" value="'.$e['rahmad'].'" size="30">	<font color="red">*</font></td>
      </tr>
      <tr>
      <td bgcolor="DEDEDE" width="15%">E-mail</td>
      <td width="2%" align="center"><strong>:</strong></td>
      <td><input type="text" name="emailna" value="'.$e['email'].'" size="40">	<font color="red">*</font></td>
      </tr>
	  <tr>
      <td width="15%"></td>
      <td width="2%"></td>
      <td><input type="Submit" name="op" value="Updated"></td>
      </tr>
      </form>
      </table><br/>';
      echo '<a href="ajk_user.php"><img border="0" src="image/back.gif">[kembali]</a>';

	break;

	case "offlog":
$met = $database->doQuery('UPDATE pengguna SET log="T" WHERE id="'.$_REQUEST['idlog'].'"');
header("location:ajk_user.php");
			;
			break;

default:
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$q['id_cost'].'"'));
if ($q['level']== 6	) {	$produkuser = '';	$tblstatus = '<th width="1%">Status</th>';	}
else{	$produkuser = 'AND id_polis="'.$q['id_polis'].'"';	$tblstatus = '';	}
$t=$database->doQuery('SELECT * FROM pengguna WHERE id_cost="'.$met['id'].'" '.$produkuser.' AND wilayah="'.$q['wilayah'].'" AND level <=7 AND aktif="Y" ORDER BY level ASC, nm_user ASC');
echo '<table border="0" cellpadding="2" cellspacing="1" width="100%" bgcolor="#E2E2E2">
     <tr align="center">
     <th width="2%">No</th>
     <th>Company</th>
     <th width="15%">Fullname</th>
     <th width="10%">Username</th>
     <th width="20%">E-mail</th>
     <th width="5%">Wilayah</th>
     <th width="15%">Level</th>
     '.$tblstatus.'
     <th width="5%">Option</th>
	 </tr>';
while($tt=mysql_fetch_array($t)){

if ($q['supervisor']==1) {	$metprev ='<a href="ajk_user.php?op=edit&id='.$tt['id'].'"><img src="image/edit3.png" width="30%" border="0"></a>';	}
elseif ($tt['nm_user']==$q['nm_user']) {	$metprev ='<a href="ajk_user.php?op=edit&id='.$tt['id'].'"><img src="image/edit3.png" width="30%" border="0"></a>';	}
else	{	$metprev ='<img src="image/edit2.png" width="30%" border="0">';		}
$metlevel = mysql_fetch_array($database->doQuery('SELECT id, level FROM fu_ajk_level WHERE id="'.$tt['level'].'"'));

if ($tt['log']=="Y") {	$metLog = '<a href="ajk_user.php?op=offlog&idlog='.$tt['id'].'"><img src="image/save.png" width="40%" border="0"></a>';	}
else{	$metLog = '<img src="image/save_off.png" width="40%" border="0">';	}

if ($tt['id_cost']== $q['id_cost'] AND $q['level']==6) {
	$tblstatus_ = '<td align="center">'.$metLog.'</td>';
}
else{
	$tblstatus_ = '';
}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center">'.++$no.'</td>
	  <td>'.$qsescost['name'].'</td>
	  <td>'.$tt['nm_lengkap'].'</td>
	  <td align="center">'.$tt['nm_user'].'</td>
	  <td>'.$tt['email'].'</td>
	  <td align="center">'.$tt['wilayah'].'</td>
	  <td align="center">'.$metlevel['level'].'</td>
	  '.$tblstatus_.'
	  <td align="center">'.$metprev.'</td>';
	 } // while
echo '</table><br/>';
} // switch
?>