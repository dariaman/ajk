<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
switch($_REQUEST['op']){
	case "delete":
	$d=$database->doQuery('DELETE FROM pengguna WHERE id="'.$_REQUEST['id'].'"');
	header("location:user.php");
	break;

	case "tambah":
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">
      <tr><th width="96%" align="left">Tambah Akun Baru</th><th><a href="user.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table>';
echo '</br>';
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['fname'])  $error1 .='<blink><font color=red>Nama lengkap tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['nm_userna'])  $error2 .='<blink><font color=red>Username tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['passwordna'])  $error3 .='<blink><font color=red>Password tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['rlevel'])  $error4 .='<blink><font color=red>Silahkan pilih level akses</font></blink><br>';
	//if (!$_REQUEST['rstatus'])  $error5 .='<blink><font color=red>Silahkan pilih status akses</font></blink><br>';
	if (!$_REQUEST['emailna'])  $error6 .='<blink><font color=red>email tidak boleh kosong</font></blink><br>';
	if ($error1 OR $error2 OR $error3 OR $error4 OR $error5 OR $error6)
	{		}
	else
	{
	if ($_REQUEST['rlevel']=="1") {	$setspv = "1";	}else{	$setspv = "0";	}

	if ($_REQUEST['id_reg']=="") {	$userwilayah = "PUSAT";	}
	else	{	$cekreg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$userwilayah = $cekreg['name'];	}
	if ($_REQUEST['id_cab']=="") {	$usercabang = "PUSAT";	}
	else	{	$cekcab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
	$usercabang = $cekcab['name'];	}

	foreach($_REQUEST['id_polis'] as $k => $val) {
		$metValProduk .= $val.'|';
	}
	$s=$database->doQuery('INSERT INTO pengguna SET id_cost="'.$_REQUEST['id_cost'].'",
									  				id_polis="'.$metValProduk.'",
									  				nm_user="'.$_REQUEST['nm_userna'].'",
	  								  				password="'.md5($_REQUEST['passwordna']).'",
													rahmad="'.$_REQUEST['passwordna'].'",
									  				nm_lengkap="'.$_REQUEST['fname'].'",
									  				gender="'.$_REQUEST['rgender'].'",
									  				dob="'.$_REQUEST['rdob'].'",
									  				wilayah="'.$userwilayah.'",
									  				cabang="'.$usercabang.'",
									  				email="'.$_REQUEST['emailna'].'",
									  				level="'.$_REQUEST['rlevel'].'",
									  				status="'.$_REQUEST['rstatus'].'",
									  				supervisor="'.$setspv.'"');
	header("location:user.php");
	}
}
echo '<table border="0" width="75%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>New Account</h1>
		<label><span>Nama Perusahaan</span><select name="id_cost" id="id_cost">
				<option value="">Pilih Perusahaan</option>';
				$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
				while ($ccost = mysql_fetch_array($metcost)) {
					echo '<option value="'.$ccost['id'].'"'._selected($_REQUEST['id_cost'], $ccost['id']).'>'.$ccost['name'].'</option>';
				}
				echo '</select>
		</label>
		<label><span>Nama Produk / Polis</span>
		<select name="id_polis[]" id="id_polis" multiple size="5"><option value="">&nbsp;</option></select>
		</lable>
<!--	<label><span>Wilayah</span><input type="hidden">
								   <input type="radio" name="rwilayah" value="Pusat"'.pilih($_REQUEST["rwilayah"], "Pusat").'>Pusat &nbsp;
		 						   <input type="radio" name="rwilayah" value=""'.pilih($_REQUEST["rwilayah"], "").'>Lainnya
		</label>-->
		<label><span>Regional</span><select name="id_reg" id="id_reg">
		<option value="">-- Regional --</option>
		</select>
		</label>
		<label><span>Cabang</span><select name="id_cab" id="id_cab">
		<option value="">-- Cabang --</option>
		</select>
		</label>
		<label><span>Full Name <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$_REQUEST['fname'].'" size="30" placeholder="Nama Lengkap"></label>
		<label><span>Jenis Kelamin</span><input type="hidden">
										 <input type="radio" name="rgender" value="P"'.pilih($_REQUEST["rgender"], "P").'>Pria &nbsp;
										 <input type="radio" name="rgender" value="W"'.pilih($_REQUEST["rgender"], "W").'>Wanita</label>
		<label><span>Tanggal Lahir</span><input type="text" name="rdob" id="rdob" class="tanggal" value="'.$_REQUEST['rdob'].'" size="10"/></label>
		<label><span>Username <font color="red">*</font> '.$error2.'</span><input type="text" name="nm_userna" value="'.$_REQUEST['nm_userna'].'" size="30" placeholder="Username"></label>
		<label><span>Password <font color="red">*</font> '.$error3.'</span><input type="password" name="passwordna" value="'.$_REQUEST['passwordna'].'" size="30" placeholder="Password"></label>
		<label><span>Level Akses <font color="red">*</font> '.$error4.'</span><select size="1" name="rlevel">
		<option value="">Select Level</option>';
		$metlevel = $database->doQuery('SELECT * FROM fu_ajk_level ORDER BY id ASC');
		while ($clevel = mysql_fetch_array($metlevel)) {	echo '<option value="'.$clevel['id'].'"'._selected($_REQUEST['rlevel'], $clevel['id']).'>'.$clevel['level'].'</option>';	}
		echo '</select></label>
		<label><span>Status Akses</span><select size="1" name="rstatus">
		<option value="">Select Status</option>
		<option value="ARM"'._selected($_REQUEST["rstatus"], "ARM").'>ARM</option>
		<option value="CLAIM"'._selected($_REQUEST["rstatus"], "CLAIM").'>Claim</option>
		<option value="MIS"'._selected($_REQUEST["rstatus"], "MIS").'>MIS</option>
		<option value="UNDERWRITING"'._selected($_REQUEST["rstatus"], "UNDERWRITING").'>Underwriting</option>
		<option value="SUPERVISOR"'._selected($_REQUEST["rstatus"], "SUPERVISOR").'>Supervisor</option>
		<option value="SUPERVISOR-ADMIN"'._selected($_REQUEST["rstatus"], "SUPERVISOR-ADMIN").'>Supervisor - Admin</option>
		<option value="SUPERVISOR"'._selected($_REQUEST["rstatus"], "SUPERVISOR").'>(Dokter) - Supervisor</option>
		<option value="STAFF"'._selected($_REQUEST["rstatus"], "STAFF").'>(Dokter) - Staff</option>
		</select></label>
		<label><span>Email <font color="red">*</font> '.$error6.'</span><input type="text" name="emailna" value="'.$_REQUEST['emailna'].'" size="30" placeholder="Email"></label>
		  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
		  </form></td></tr></table>';
echo '<!--WILAYAH COMBOBOX-->
<script src="javascript/metcombo/prototype.js"></script>
<script src="javascript/metcombo/dynamicombo.js"></script>
<!--WILAYAH COMBOBOX-->
<script>
document.observe("dom:loaded",function(){
	new DynamiCombo( "id_cost" , {
		elements:{
			"id_polis":	{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
			"id_reg":	{url:\'javascript/metcombo/data.php?req=setpoliscostumerregional\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_reg"] ?>\'},
			"id_cab":	{url:\'javascript/metcombo/data.php?req=setpoliscostumercabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
		},
		loadingImage:\'loader1.gif\',
		loadingText:\'Loading...\',
		debug:0
	} )
});
</script>';
		;
break;

	case "edit":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="95%" align="left">Edit Account</th><th><a href="user.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr>
      </table></br>';
if ($_REQUEST['ope']=="Updated") {
	if (!$_REQUEST['fname'])  $error1 .='<blink><font color=red>Nama lengkap tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['nm_userna'])  $error2 .='<blink><font color=red>Username tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['passwordna'])  $error3 .='<blink><font color=red>Password tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['rlevel'])  $error4 .='<blink><font color=red>Silahkan pilih level akses</font></blink><br>';
	//if (!$_REQUEST['rstatus'])  $error5 .='<blink><font color=red>Silahkan pilih status akses</font></blink><br>';
	if (!$_REQUEST['emailna'])  $error6 .='<blink><font color=red>email tidak bleh kosong</font></blink><br>';
	if ($error1 OR $error2 OR $error3 OR $error4 OR $error6)
	{		}
	else
	{
	if ($_REQUEST['rlevel']=="1") {	$setspv = "1";	}else{	$setspv = "0";	}
/*
	if ($_REQUEST['id_reg']=="") {	$userwilayah = "PUSAT";	}
	else	{	$cekreg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
	$userwilayah = $cekreg['name'];	}
*/
	if ($_REQUEST['met_cab']=="") {	$usercabang = "PUSAT";	}
	else	{	$cekcab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE name="'.$_REQUEST['met_cab'].'"'));
	$usercabang = $cekcab['name'];	}
	$s=$database->doQuery('UPDATE pengguna  SET nm_user="'.$_REQUEST['nm_userna'].'",
	  								  					password="'.md5($_REQUEST['passwordna']).'",
														rahmad="'.$_REQUEST['passwordna'].'",
									  					cabang="'.$usercabang.'",
									  					email="'.$_REQUEST['emailna'].'",
									  					gender="'.$_REQUEST['rgender'].'",
									  					dob="'.$_REQUEST['rdob'].'",
									  					status="'.$_REQUEST['rstatus'].'",
									  					level="'.$_REQUEST['rlevel'].'",
									  					supervisor="'.$setspv.'",
									  					nm_lengkap="'.$_REQUEST['fname'].'" WHERE id="'.$_REQUEST['id'].'"');
	header("location:user.php");
	}
}
$e=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id="'.$_REQUEST['id'].'"'));
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$e['id_cost'].'"'));
//$metrod = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$e['id_polis'].'"'));
$metWilayah = $database->doQuery('SELECT fu_ajk_regional.`name` AS regional, fu_ajk_cabang.`name` AS cabang, fu_ajk_cabang.`id_cost`
FROM fu_ajk_cabang
INNER JOIN fu_ajk_regional ON fu_ajk_cabang.id_reg = fu_ajk_regional.id AND fu_ajk_cabang.id_cost = fu_ajk_regional.id_cost
WHERE fu_ajk_regional.`name` = "'.$e['wilayah'].'" AND fu_ajk_cabang.id_cost="'.$e['id_cost'].'"');

echo '<table border="0" width="75%" align="center"><tr><td>
		<form method="POST" action="" class="smart-green">
		<h1>Edit Data Account</h1>
		<label><span>Nama Perusahaan</span><input type="text" name="id_cost" value="'.$metcost['name'].'" disabled></label>
		<!--<label><span>Nama Produk</span><input type="text" name="id_polis" value="'.$metrod['nmproduk'].'" disabled></lable>-->
<!--	<label><span>Wilayah</span><input type="hidden">
									<input type="radio" name="rwilayah" value="Pusat"'.pilih($e["wilayah"], "Pusat").'>Pusat &nbsp;
		 							<input type="radio" name="rwilayah" value=""'.pilih($e["wilayah"], "").'>Lainnya
		</label>-->
		<label><span>Regional</span><input type="text" name="met_reg" value="'.strtoupper($e['wilayah']).'" disabled></label>
		<label><span>Cabang</span><select size="1" name="met_cab">
		<option value="">Select Status</option>';
		while ($metWilayah_ = mysql_fetch_array($metWilayah)) {
		echo '<option value="'.$metWilayah_["cabang"].'"'._selected($e["cabang"],$metWilayah_["cabang"]).'>'.$metWilayah_["cabang"].'</option>';
		}
		echo '</select></label>
		<label><span>Full Name <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$e['nm_lengkap'].'" size="30" placeholder="Nama Lengkap"></label>
		<label><span>Jenis Kelamin</span><input type="hidden">
										 <input type="radio" name="rgender" value="P"'.pilih($e["gender"], "P").'>Pria &nbsp;
		 								 <input type="radio" name="rgender" value="W"'.pilih($e["gender"], "W").'>Wanita
		</label>
		<label><span>Tanggal Lahir</span><input type="text" name="rdob" id="effdate" class="tanggal" value="'.$e['dob'].'" size="10"/></label>
		<label><span>Username <font color="red">*</font> '.$error2.'</span><input type="text" name="nm_userna" value="'.$e['nm_user'].'" size="30" placeholder="Username"></label>
		<label><span>Password <font color="red">*</font> '.$error3.'</span><input type="password" name="passwordna" value="'.$e['rahmad'].'" size="30" placeholder="Password"></label>
		<label><span>Level Akses <font color="red">*</font> '.$error4.'</span><select size="1" name="rlevel">
		<option value="">Select Level</option>';
		$metlevel = $database->doQuery('SELECT * FROM fu_ajk_level ORDER BY id ASC');
		while ($clevel = mysql_fetch_array($metlevel)) {	echo '<option value="'.$clevel['id'].'"'._selected($e['level'], $clevel['id']).'>'.$clevel['level'].'</option>';	}
		echo '</select></label>
		<label><span>Status Akses</span><select size="1" name="rstatus">
		<option value="">Select Status</option>
		<option value="ARM"'._selected($e["status"], "ARM").'>ARM</option>
		<option value="CLAIM"'._selected($e["status"], "CLAIM").'>Claim</option>
		<option value="MIS"'._selected($e["status"], "MIS").'>MIS</option>
		<option value="UNDERWRITING"'._selected($e["status"], "UNDERWRITING").'>Underwriting</option>
		<option value="SUPERVISOR"'._selected($_REQUEST["rstatus"], "SUPERVISOR").'>Supervisor</option>
		<option value="SUPERVISOR-ADMIN"'._selected($_REQUEST["rstatus"], "SUPERVISOR-ADMIN").'>Supervisor - Admin</option>
		<option value="SUPERVISOR"'._selected($e["status"], "SUPERVISOR").'>(Dokter) - Supervisor</option>
		<option value="STAFF"'._selected($e["status"], "STAFF").'>(Dokter) - Staff</option>
		</select></label>
		<label><span>Email <font color="red">*</font> '.$error6.'</span><input type="text" name="emailna" value="'.$e['email'].'" size="30" placeholder="Email"></label>
		  <label><span>&nbsp;</span><input type="submit" name="ope" value="Updated" class="button" /></label>
		  </form></td></tr></table>';
	break;

	case "delmob":
		$s=$database->doQuery('UPDATE user_mobile SET del="1", update_by="'.$q['id'].'", update_time="'.$futgl.'" WHERE id="'.$_REQUEST['id'].'"');
		header("location:user.php?op=vumobile");
	break;

	case "editmob":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="95%" align="left">Edit Account Mobile</th><th><a href="user.php?op=vumobile"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr>
      </table></br>';
$edMob = mysql_fetch_array($database->doQuery('SELECT
fu_ajk_costumer.`name`,
fu_ajk_polis.nmproduk,
user_mobile.type,
user_mobile.`level`,
user_mobile.nip_primary,
user_mobile.nip_secondary,
user_mobile.namalengkap,
user_mobile.nama,
user_mobile.passw,
user_mobile.`status`,
user_mobile.norek,
user_mobile.bank_rek,
user_mobile.atas_nama,
user_mobile.norek,
user_mobile.email,
user_mobile.cabang,
fu_ajk_grupproduk.nmproduk AS namamitra
FROM
user_mobile
INNER JOIN fu_ajk_costumer ON user_mobile.idbank = fu_ajk_costumer.id
INNER JOIN fu_ajk_polis ON user_mobile.idproduk = fu_ajk_polis.id
LEFT JOIN fu_ajk_grupproduk ON user_mobile.namamitra = fu_ajk_grupproduk.id
WHERE user_mobile.id="'.$_REQUEST['id'].'"'));
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['id_cab'])  $error1 .='<blink><font color=red>Silahkan pilih nama cabang</font></blink><br>';
	if (!$_REQUEST['fname'])  $error2 .='<blink><font color=red>Nama lengkap tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['nm_userna'])  $error3 .='<blink><font color=red>Username tidak boleh kosong</font></blink><br>';
	//if (!$_REQUEST['passwordna'])  $error4 .='<blink><font color=red>Password tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['rstatus'])  $error5 .='<blink><font color=red>Silahkan pilih status user</font></blink><br>';
	if (!$_REQUEST['emailna'])  $error6 .='<blink><font color=red>email tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['nippertama'])  $error7 .='<blink><font color=red>NIP Pertama tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['nipkedua'])  $error8 .='<blink><font color=red>NIP Kedua tidak boleh kosong</font></blink><br>';
	if ($_REQUEST['rstatus'] == "Dokter" AND $_REQUEST['norekdokter']=="")  $error9 .='<blink><font color=red>Nomor rekening tidak boleh kosong</font></blink><br>';
	if (!$_REQUEST['id_mitra'])  $error10 .='<blink><font color=red>Mitra tidak boleh kosong</font></blink><br>';
	
	if ($error1 OR $error2 OR $error3 OR $error5 OR $error6 OR $error7 OR $error8 OR $error9 OR $error10)
	{		}
	else
	{
		if ($_REQUEST['passwordna']=="") {
			$metPassw ='';
		}else{
			$metPassw ='passw="'.md5($_REQUEST['passwordna']).'",
						jangkrik="'.$_REQUEST['passwordna'].'",';
		}
/*
		$s=$database->doQuery('UPDATE user_mobile SET namalengkap="'.$_REQUEST['fname'].'",
	  								  				passw="'.md5($_REQUEST['passwordna']).'",
	  								  				level="'.$_REQUEST['rlevel'].'",
									  				type="'.$_REQUEST['rstatus'].'",
									  				nama="'.$_REQUEST['nm_userna'].'",
									  				nip_primary="'.$_REQUEST['nippertama'].'",
									  				nip_secondary="'.$_REQUEST['nipkedua'].'",
									  				cabang="'.$_REQUEST['id_cab'].'",
									  				supervisor="'.$_REQUEST['spv_cab'].'",
									  				email="'.$_REQUEST['emailna'].'"
									  				WHERE id="'.$_REQUEST['id'].'"');
*/
		$s=$database->doQuery('UPDATE user_mobile SET namalengkap="'.$_REQUEST['fname'].'",
													namamitra="'.$_REQUEST['id_mitra'].'",
	  								  				level="'.$_REQUEST['rlevel'].'",
									  				type="'.$_REQUEST['rstatus'].'",
									  				nama="'.$_REQUEST['nm_userna'].'",
									  				nip_primary="'.$_REQUEST['nippertama'].'",
									  				nip_secondary="'.$_REQUEST['nipkedua'].'",
									  				'.$metPassw.'
									  				cabang="'.$_REQUEST['id_cab'].'",
													bank_rek="'.$_REQUEST['bank_rek'].'",
													atas_nama="'.$_REQUEST['atas_nama'].'",
									  				supervisor="'.$_REQUEST['spv_cab'].'",
									  				email="'.$_REQUEST['emailna'].'",
									  				norek="'.$_REQUEST['norekdokter'].'"
									  				WHERE id="'.$_REQUEST['id'].'"');

		header("location:user.php?op=vumobile");
	}
}

if ($edMob['namamitra']=="") {
	$metMitra = "Bukopin";
}else{
	$metMitra = $edMob['namamitra'];
}
echo '<table border="0" width="75%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>New Account</h1>
	<label><span>Nama Perusahaan <b>'.$edMob['name'].'</b></span></label><br /><br />
	<label><span>Nama Produk <b>'.$edMob['nmproduk'].'</b></span></label><br /><br />
	<label><span>Nama Mitra  <font color="red">*</font> '.$error10.'</span><select size="1" name="id_mitra" id="id_mitra">
		<option value="">-- Pilih Mitra --</option>';
		$metmit = $database->doQuery('SELECT * FROM fu_ajk_grupproduk ORDER BY nmproduk');
		while ($metmit_ = mysql_fetch_array($metmit)) {	echo '<option value="'.$metmit_['id'].'"'._selected($edMob['namamitra'], $metmit_['nmproduk']).'>'.$metmit_['nmproduk'].'</option>';	}
		echo '</select>
	</label>
	<!--<label><span>Nama Mitra <b>'.$metMitra.'</b></span></label><br /><br />-->
	<label><span>Cabang <font color="red">*</font> '.$error1.'</span><select size="1" name="id_cab" id="id_cab">
		<option value="">-- Pilih Cabang --</option>';
		$metcab = $database->doQuery('SELECT * FROM fu_ajk_cabang GROUP BY name ORDER BY name');
		while ($metcab_ = mysql_fetch_array($metcab)) {	echo '<option value="'.$metcab_['id'].'"'._selected($edMob['cabang'], $metcab_['id']).'>'.$metcab_['name'].'</option>';	}
		echo '</select>
	</label>
	<label><span>Full Name <font color="red">*</font> '.$error2.'</span><input type="text" name="fname" value="'.$edMob['namalengkap'].'" size="30" placeholder="Nama Lengkap"></label>
	<label><span>NIP Pertama <font color="red">*</font> '.$error7.'</span><input type="text" name="nippertama" value="'.$edMob['nip_primary'].'" size="30" placeholder="NIP Primary"></label>
	<label><span>NIP Kedua <font color="red">*</font> '.$error8.'</span><input type="text" name="nipkedua" value="'.$edMob['nip_secondary'].'" size="30" placeholder="NIP Secundary"></label>
	<label><span>Username <font color="red">*</font> '.$error3.'</span><input type="text" name="nm_userna" value="'.$edMob['nama'].'" size="30" placeholder="Username"></label>
	<label><span>Password <font color="red">*</font> '.$error4.'</span><input type="password" name="passwordna" value="" size="30" placeholder="Password"></label>
	<label><span>Level Akses</span><select size="1" name="rlevel">
	<option value="">Select Level</option>
	<option value="Supervisor"'._selected($edMob["level"], "Supervisor").'>Supervisor</option>
	<option value="Staff"'._selected($edMob["level"], "Staff").'>Staff</option>
	</select></label>
	<label><span>Nama Supervisor</span>
	<select name="spv_cab" id="spv_cab">
	<option value="">-- SPV --</option>
	</select></label>
	<label><span>Status Akses <font color="red">*</font> '.$error5.'</span><select size="1" name="rstatus">
	<option value="">Select Status</option>
	<option value="Direksi_GM"'._selected($edMob["type"], "Direksi_GM").'>GM/Direksi</option>
	<option value="Kadiv"'._selected($edMob["type"], "Kadiv").'>Kadiv</option>
	<option value="Kacab"'._selected($edMob["type"], "Kacab").'>Kacab</option>
	<option value="Dokter"'._selected($edMob["type"], "Dokter").'>Dokter</option>
	<option value="Marketing"'._selected($edMob["type"], "Marketing").'>Marketing</option>
	</select></label>
	<label><span>Email <font color="red">*</font> '.$error6.'</span><input type="text" name="emailna" value="'.$edMob['email'].'" size="30" placeholder="Email"></label>
	<label><span>Nama Bank</span><input type="text" name="bank_rek" value="'.$edMob['bank_rek'].'" size="30" placeholder="Nama Bank"></label>
	<label><span>Nomor Rekening <font color="red">*</font> '.$error9.'</span><input type="text" name="norekdokter" value="'.$edMob['norek'].'" size="30" placeholder="Nomor Rekekning"></label>
	<label><span>Atas Nama</span><input type="text" name="atas_nama" value="'.$edMob['atas_nama'].'" size="30" placeholder="Atas Nama"></label>
	<label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
	</form></td></tr></table>';
echo '<!--WILAYAH COMBOBOX-->
	<script src="javascript/metcombo/prototype.js"></script>
	<script src="javascript/metcombo/dynamicombo.js"></script>
	<!--WILAYAH COMBOBOX-->
	<script>
	document.observe("dom:loaded",function(){
		new DynamiCombo( "id_cab" , {
			elements:{
				"spv_cab":	{url:\'javascript/metcombo/data.php?req=editspvcabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["spv_cab"] ?>\'},
			},
			loadingImage:\'../loader1.gif\',
			loadingText:\'Loading...\',
			debug:0
		} )
	});
	</script>';
		break;

case "offuser":
$metoff = $database->doQuery('UPDATE pengguna SET log="T" WHERE id="'.$_REQUEST['idu'].'"');
header("location:user.php");
	;
	break;

case "vumobile":
if ($q['status'] =="" OR $q['status'] =="UNDERWRITING") {
	$metnewuser ='<th><a href="user.php?op=newusrmobile"><img border="0" src="../image/new.png" width="25"></a></th>';
}else{
	$metnewuser = '';
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
     <tr><th width="100%" align="left">Daftar User Mobile</font></th>'.$metnewuser.'</tr>
     </table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="10%">Nama Lengkap</td><td>: <input type="text" name="nmlengkap" value="'.$_REQUEST['nmlengkap'].'"></td></tr>
	<tr><td width="10%">Username</td><td>: <input type="text" name="username_" value="'.$_REQUEST['username_'].'"></td></tr>
	<tr><td>Level User</td><td>:
	  <select id="lvluser" name="lvluser">
	  	<option value="">-----Level-----</option>
	  	<option value="Dokter"'._selected($_REQUEST['lvluser'], "Dokter").'>Dokter</option>
	  	<option value="Marketing"'._selected($_REQUEST['lvluser'], "Marketing").'>Marketing</option>
	  </select>
	  </td></tr>
	  <tr><td colspan="2"> <input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form></table>';
if ($_REQUEST['username_'])		{	$satu = 'AND nama LIKE "%' . $_REQUEST['username_'] . '%"';		}
if ($_REQUEST['nmlengkap'])		{	$tiga = 'AND namalengkap LIKE "%' . $_REQUEST['nmlengkap'] . '%"';		}
if ($_REQUEST['lvluser'])		{	$dua = 'AND type = "' . $_REQUEST['lvluser'] . '"';		}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
if ($q['status'] =="") {
	$t=$database->doQuery('SELECT * FROM user_mobile WHERE idbank!="" '.$satu.' '.$dua.' '.$tiga.' and del is null ORDER BY id DESC LIMIT '.$m.' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM user_mobile WHERE  del is null and level!="" '.$satu.' '.$dua.' '.$tiga.''));
}else{
	$t=$database->doQuery('SELECT * FROM user_mobile WHERE  del is null and (status="'.$q['status'].'" OR idbank !="" '.$satu.' '.$dua.' '.$tiga.') ORDER BY id DESC LIMIT '.$m.' , 25');
	$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM user_mobile WHERE  del is null and (status="'.$q['status'].'" OR idbank !="" '.$satu.' '.$dua.' '.$tiga.')'));
}
	$totalRows = $totalRows[0];
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
	     <tr align="center">
	     <th width="3%">No</th>
	     <th>Costumer</th>
	     <th width="8%">Produk</th>
	     <th width="1%">Akses</th>
	     <th width="1%">Fee</th>
	     <th width="10%">Fullname</th>
	     <th width="8%">NIP Pertama</th>
	     <th width="8%">NIP Kedua</th>
	     <th width="10%">E-mail</th>
	     <th width="5%">Cabang</th>
	     <th width="10%">Level</th>
	     <th width="1%">Status</th>
	     <th width="1%">Device</th>
	     <th width="5%">Pilih</th>
		 </tr>';
	$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while($tt=mysql_fetch_array($t)){
if ($tt['type']=="Dokter") {
	$metFee = mysql_fetch_array($database->doQuery('SELECT id, fee_dokter FROM fu_ajk_dokter_fee WHERE id_user_mobile="'.$tt['id'].'" AND del IS NULL'));
	if ($metFee['fee_dokter']<= 0) {
	$inputFee = '<a href="user.php?op=feedokter&idd='.$tt['id'].'" title="tambah fee dokter"><img src="image/rmf_2.png" width="20"></a>';
	}else{
	$inputFee = '<a href="user.php?op=feedokteredit&idd='.$tt['id'].'" title="edit fee dokter"><img src="image/rmf_1.png" width="20"></a>';
	}
	$_metFEE = duit($metFee['fee_dokter']);
}else{
	$inputFee = '';
	$_metFEE = '';
}
	$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$tt['idbank'].'" AND del IS NULL'));
	$metpol = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$tt['idproduk'].'" AND del IS NULL'));
	$metcab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$tt['cabang'].'" AND del IS NULL'));

if ($tt['idbank']=="") {	$perusahaan = '<font color="blue">PT Adonai Pialang Asuransi</font>';	}	else	{	$perusahaan=$metcost['name'];	}

if ($tt['supervisor']=="1") {	if ($tt['idbank'] !="") {	$metspv = "(Supervisor)";	}else{	$metspv="";	}	}else{	$metspv="";	}

	$metlevelnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_level WHERE id="'.$tt['level'].'"'));

if ($tt['log']=="Y") {	$loguser = '<a href="user.php?op=offuser&idu='.$tt['id'].'"><font color="red" size="3"><b>'.$tt['log'].'</b></font></a>';	}else{	$loguser =$tt['log'];	}
if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	      <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td>'.$perusahaan.'</td>
		  <td align="center">'.$metpol['nmproduk'].'</td>
		  <td align="center">'.$tt['type'].'</td>
		  <td align="center">'.$_metFEE.'</td>
		  <td>'.strtoupper($tt['namalengkap']).' '.$metspv.'</td>
		  <td>'.$tt['nip_primary'].'</td>
		  <td>'.$tt['nip_secondary'].'</td>
		  <td>'.$tt['email'].'</td>
		  <td align="center">'.strtoupper($metcab['name']).'</td>
		  <td align="center">'.$tt['level'].'</td>
		  <td align="center">'.$tt['status'].'</td>
		  <td align="center">'.$tt['ver_device'].'</td>';

if ($_SESSION['nm_user']=="admin") {
echo ' <td align="center">'.$inputFee.'
	   <a href="user.php?id='.$tt['id'].'&op=editmob"><img border="0" src="../image/editaja.png" width="20">&nbsp;
	   <a href="user.php?id='.$tt['id'].'&op=delmob" onClick="if(confirm(\'Anda yakin akan menghapus data ?\')){return true;}{return false;}"><img border="0" src="../image/delete.png" width="20">
	   <!--<a href="#" onClick="if(confirm(\'Anda yakin akan menghapus data ?\')){return true;}{return false;}"><img border="0" src="../image/delete.png" width="20">--!>
	   </td>';
}elseif ($_SESSION['nm_user']=="underwriting") {
echo ' <td align="center">'.$inputFee.'
	   <a href="user.php?id='.$tt['id'].'&op=editmob"><img border="0" src="../image/editaja.png" width="20">&nbsp;
	   <a href="user.php?id='.$tt['id'].'&op=delmob" onClick="if(confirm(\'Anda yakin akan menghapus data ?\')){return true;}{return false;}"><img border="0" src="../image/delete.png" width="20">
	   <!--<a href="#" onClick="if(confirm(\'Anda yakin akan menghapus data ?\')){return true;}{return false;}"><img border="0" src="../image/delete.png" width="20">--!>
	   </td>';
}else{
	echo ' <td align="center"><img border="0" src="../image/editdis.png" Disable></td></tr>';
}
	echo '</tr>';
} // while
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'user.php?op=vumobile&lvluser='.$_REQUEST['lvluser'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data User Mobile: <u>' . $totalRows . '</u></b></td></tr>';
	;
	break;

case "feedokter":
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">
      <tr><th width="96%" align="left">Tambah Fee Dokter</th><th><a href="user.php?op=vumobile"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table></br>';
$metDokter = mysql_fetch_array($database->doQuery('SELECT id, namalengkap FROM user_mobile WHERE id="'.$_REQUEST['idd'].'"'));
if ($_REQUEST['ope']=="SimpanFee") {
$metFee = $database->doQuery('INSERT INTO fu_ajk_dokter_fee SET id_user_mobile="'.$_REQUEST['iddokter'].'",
																tgl_mulai_fee="'.$_REQUEST['tglawal'].'",
																tgl_akhir_fee="'.$_REQUEST['tglakhir'].'",
																fee_dokter="'.$_REQUEST['nilaifee'].'",
																input_by="'.$q['id'].'",
																input_time="'.$futgl.'"');
header("location:user.php?op=vumobile");
}
echo '<table border="0" width="75%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>New Fee Dokter</h1>
	<input type="hidden" name="iddokter" value="'.$_REQUEST['idd'].'">
	<label><span>Full Name <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$metDokter['namalengkap'].'" size="30" placeholder="Nama Lengkap" disabled></label>
	<label><span>Tanggal Mulai <font color="red">*</font> '.$error2.'</span>';
	echo initCalendar();
	echo calendarBox('tglawal', 'triger1', $_REQUEST['tglawal']);
	echo '</label>
	<label><span>Tanggal Akhir <font color="red">*</font> '.$error3.'</span>';
	echo initCalendar();
	echo calendarBox('tglakhir', 'triger2', $_REQUEST['tglakhir']);
	echo '</label>
	<label><span>Fee <font color="red">*</font> '.$error4.'</span><input type="text" name="nilaifee" value="'.$_REQUEST['nilaifee'].'" size="30" placeholder="Nilai Fee"></label>
	<label><span>&nbsp;</span><input type="submit" name="ope" value="SimpanFee" class="button" /></label>
	</form></td></tr></table>';
	;
	break;

case "feedokteredit":
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">
      <tr><th width="96%" align="left">Tambah Fee Dokter</th><th><a href="user.php?op=vumobile"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table></br>';
//$metDokter = mysql_fetch_array($database->doQuery('SELECT id, namalengkap FROM user_mobile WHERE id="'.$_REQUEST['idd'].'"'));
$metDokter = mysql_fetch_array($database->doQuery('SELECT user_mobile.id,
														  user_mobile.namalengkap,
														  fu_ajk_dokter_fee.tgl_mulai_fee,
														  fu_ajk_dokter_fee.tgl_akhir_fee,
														  fu_ajk_dokter_fee.fee_dokter,
														  fu_ajk_dokter_fee.fee_laboratorium
												   FROM user_mobile
												   LEFT JOIN fu_ajk_dokter_fee ON user_mobile.id = fu_ajk_dokter_fee.id_user_mobile
												   WHERE user_mobile.id = "'.$_REQUEST['idd'].'"'));
if ($_REQUEST['ope']=="SimpanFee") {
$metFee = $database->doQuery('UPDATE fu_ajk_dokter_fee SET tgl_mulai_fee="'.$_REQUEST['tglawal'].'",
														   tgl_akhir_fee="'.$_REQUEST['tglakhir'].'",
														   fee_dokter="'.$_REQUEST['nilaifee'].'",
														   fee_laboratorium="'.$_REQUEST['nilaifeelab'].'",
														   update_by="'.$q['id'].'",
														   update_time="'.$futgl.'"
								WHERE id_user_mobile="'.$_REQUEST['idd'].'"');
header("location:user.php?op=vumobile");
}
echo '<table border="0" width="75%" align="center"><tr><td>
	<form method="POST" action="" class="input-list style-1 smart-green">
	<h1>New Fee Dokter</h1>
	<input type="hidden" name="iddokter" value="'.$_REQUEST['idd'].'">
	<label><span>Full Name <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$metDokter['namalengkap'].'" size="30" placeholder="Nama Lengkap" disabled></label>
	<label><span>Tanggal Mulai <font color="red">*</font> '.$error2.'</span>';
echo initCalendar();
echo calendarBox('tglawal', 'triger1', $metDokter['tgl_mulai_fee']);
echo '</label>
	<label><span>Tanggal Akhir <font color="red">*</font> '.$error3.'</span>';
echo initCalendar();
echo calendarBox('tglakhir', 'triger2', $metDokter['tgl_akhir_fee']);
echo '</label>
	<label><span>Fee Dokter<font color="red">*</font> '.$error4.'</span><input type="text" name="nilaifee" value="'.$metDokter['fee_dokter'].'" size="30" placeholder="Nilai Fee Dokter"></label>
	<label><span>Fee Laboratorium<font color="red">*</font> '.$error5.'</span><input type="text" name="nilaifeelab" value="'.$metDokter['fee_laboratorium'].'" size="30" placeholder="Nilai Fee Laboratorium"></label>
	<label><span>&nbsp;</span><input type="submit" name="ope" value="SimpanFee" class="button" /></label>
	</form></td></tr></table>';
	;
	break;


case "newusrmobile":
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">
      <tr><th width="96%" align="left">Tambah User Mobile</th><th><a href="user.php?op=vumobile"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table>';
	echo '</br>';
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['id_cost'])  $error9 .='<blink><font color=red>Silahkan pilih nama perusahaan </font></blink><br>';
	if (!$_REQUEST['id_polis'])  $error10 .='<blink><font color=red>Silahkan pilih nama produk</font></blink><br>';
	if (!$_REQUEST['id_cab'])  $error11 .='<blink><font color=red>Silahkan pilih nama cabang</font></blink><br>';
	if (!$_REQUEST['fname'])  $error1 .='<blink><font color=red>Nama lengkap tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['nm_userna']){
		$error2 .='<blink><font color=red>Username tidak bleh kosong</font></blink><br>';
	}else{
		$metCekDoubleUser = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE nama="'.$_REQUEST['nm_userna'].'" AND del IS NULL'));
		if ($_REQUEST['nm_userna'] == $metCekDoubleUser['nama'])	{
			$errorun .='<blink><font color=red>Username sudah digunakan</font></blink><br>';
		}
	}
	if (!$_REQUEST['passwordna'])  $error3 .='<blink><font color=red>Password tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['rstatus'])  $error4 .='<blink><font color=red>Silahkan pilih status user</font></blink><br>';
	//if (!$_REQUEST['rstatus'])  $error5 .='<blink><font color=red>Silahkan pilih status akses</font></blink><br>';
	if (!$_REQUEST['emailna'])  $error6 .='<blink><font color=red>email tidak bleh kosong</font></blink><br>';

	if (!$_REQUEST['nippertama'])  {
		$error7 .='<blink><font color=red>NIP Pertama tidak bleh kosong</font></blink><br>';
	}else{
		$ceknip1 = mysql_fetch_array($database->doQuery('SELECT nip_primary, nip_secondary FROM user_mobile WHERE nip_primary="'.$_REQUEST['nippertama'].'" AND del IS NULL'));
		if ($ceknip1['nip_primary']==$_REQUEST['nippertama']) {
			$errornip1 .='<blink><font color=red>NIP Pertama sudah pernah diinput</font></blink><br>';
		}
	}

	if (!$_REQUEST['nipkedua'])  {
		$error8 .='<blink><font color=red>NIP Kedua tidak bleh kosong</font></blink><br>';
	}else{
		$ceknip2 = mysql_fetch_array($database->doQuery('SELECT nip_primary, nip_secondary FROM user_mobile WHERE nip_secondary="'.$_REQUEST['nipkedua'].'" AND del IS NULL'));
		if ($ceknip2['nip_secondary']==$_REQUEST['nipkedua']) {
			$errornip2 .='<blink><font color=red>NIP Kedua sudah pernah diinput</font></blink><br>';
		}
	}
	//CEK DOUBLE USER

	if ($_REQUEST['nm_userna']) {
		$metCekDoubleUser = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE nama="'.$_REQUEST['nm_userna'].'" AND del IS NULL'));
		if ($_REQUEST['nm_userna'] == $metCekDoubleUser['nama'])	$error2 .='<blink><font color=red>Username sudah digunakan</font></blink><br>';
	}elseif ($_REQUEST['nm_userna']){

	}

	//CEK DOUBLE USER
	if ($error1 OR $error2 OR $error3 OR $error4 OR $error5 OR $error6 OR $error7 OR $error8 OR $errornip1 OR $errornip2 OR $error9 OR $error10 OR $error11 OR $errorun)
	{		}
	else
	{
		if ($_REQUEST['rlevel']=="1") {	$setspv = "1";	}else{	$setspv = "0";	}

		if ($_REQUEST['id_reg']=="") {	$userwilayah = "PUSAT";	}
		else	{	$cekreg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
			$userwilayah = $cekreg['name'];	}
		if ($_REQUEST['id_cab']=="") {	$usercabang = "PUSAT";	}
		else	{	$cekcab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
			$usercabang = $cekcab['name'];	}
		$s=$database->doQuery('INSERT INTO user_mobile SET idbank="'.$_REQUEST['id_cost'].'",
									  				idproduk="'.$_REQUEST['id_polis'].'",
									  				namalengkap="'.$_REQUEST['fname'].'",
									  				namamitra="'.$_REQUEST['fnamemitra'].'",
	  								  				passw="'.md5($_REQUEST['passwordna']).'",
	  								  				jangkrik="'.$_REQUEST['passwordna'].'",
	  								  				level="'.$_REQUEST['rlevel'].'",
									  				type="'.$_REQUEST['rstatus'].'",
									  				supervisor="'.$_REQUEST['spv_cab'].'",
									  				nama="'.$_REQUEST['nm_userna'].'",
									  				nip_primary="'.$_REQUEST['nippertama'].'",
									  				nip_secondary="'.$_REQUEST['nipkedua'].'",
									  				regional="'.$_REQUEST['id_reg'].'",
									  				cabang="'.$_REQUEST['id_cab'].'",
									  				email="'.$_REQUEST['emailna'].'",
													input_by="' . $q['nm_user'] . '",
								   					input_time="' . $futgl . '"');
		header("location:user.php?op=vumobile");
	}
}
echo '<table border="0" width="75%" align="center"><tr><td>
			<form method="POST" action="" class="input-list style-1 smart-green">
			<h1>New Account</h1>
			<label><span>Nama Perusahaan <font color="red">*</font> '.$error9.'</span><select name="id_cost" id="id_cost">
					<option value="">Pilih Perusahaan</option>';
	$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
	while ($ccost = mysql_fetch_array($metcost)) {	echo '<option value="'.$ccost['id'].'"'._selected($_REQUEST['id_cost'], $ccost['id']).'>'.$ccost['name'].'</option>';	}
	echo '</select>
			</label>
			<label><span>Nama Produk <font color="red">*</font> '.$error10.'</span><select name="id_polis" id="id_polis">
			<option value="">-- Nama Produk --</option>
			</select>
			</lable>
	<!--	<label><span>Wilayah</span><input type="hidden">
									   <input type="radio" name="rwilayah" value="Pusat"'.pilih($_REQUEST["rwilayah"], "Pusat").'>Pusat &nbsp;
			 						   <input type="radio" name="rwilayah" value=""'.pilih($_REQUEST["rwilayah"], "").'>Lainnya
			</label>-->
			<label><span>Nama Mitra</span><select name="fnamemitra" id="fnamemitra">
			<option value="">-- Nama Mitra --</option>
			</select>
			</label>
			<label><span>Regional</span><select name="id_reg" id="id_reg">
			<option value="">-- Regional --</option>
			</select>
			</label>
			<label><span>Cabang <font color="red">*</font> '.$error11.'</span><select name="id_cab" id="id_cab">
			<option value="">-- Cabang --</option>
			</select>
			</label>
			<label><span>Full Name <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$_REQUEST['fname'].'" size="30" placeholder="Nama Lengkap"></label>
			<label><span>NIP Pertama <font color="red">*</font> '.$error7.''.$errornip1.'</span><input type="text" name="nippertama" value="'.$_REQUEST['nippertama'].'" size="30" placeholder="NIP Primary"></label>
			<label><span>NIP Kedua <font color="red">*</font> '.$error8.''.$errornip2.'</span><input type="text" name="nipkedua" value="'.$_REQUEST['nipkedua'].'" size="30" placeholder="NIP Secundary"></label>
			<label><span>Username <font color="red">*</font> '.$error2.'</span><input type="text" name="nm_userna" value="'.$_REQUEST['nm_userna'].'" size="30" placeholder="Username"></label>
			<label><span>Password <font color="red">*</font> '.$error3.'</span><input type="password" name="passwordna" value="'.$_REQUEST['passwordna'].'" size="30" placeholder="Password"></label>
			<label><span>Level Akses</span><select size="1" name="rlevel">
			<option value="">Select Level</option>
			<option value="Supervisor"'._selected($_REQUEST["rlevel"], "Supervisor").'>Supervisor</option>
			<option value="Staff"'._selected($_REQUEST["rlevel"], "Staff").'>Staff</option>
			<option value="Klinik"'._selected($_REQUEST["rlevel"], "Klinik").'>Klinik</option>
			</select></label>
			<label><span>Nama Supervisor</span>
			<select name="spv_cab" id="spv_cab">
			<option value="">-- SPV --</option>
			</select></label>
			<label><span>Status Akses <font color="red">*</font> '.$error4.'</span><select size="1" name="rstatus">
			<option value="">Select Status</option>
			<option value="GM/Direksi"'._selected($_REQUEST["rlevel"], "GM/Direksi").'>GM/Direksi</option>
			<option value="Kadiv Operasional"'._selected($_REQUEST["rlevel"], "Kadiv Operasional").'>Kadiv Operasional</option>
			<option value="Kacab Operasional"'._selected($_REQUEST["rlevel"], "Kacab Operasional").'>Kacab Operasional</option>
			<option value="Management Adonai"'._selected($_REQUEST["rlevel"], "Management Adonai").'>Management Adonai</option>
			<option value="Dokter"'._selected($_REQUEST["rstatus"], "Dokter").'>Dokter</option>
			<option value="Marketing"'._selected($_REQUEST["rstatus"], "Marketing").'>Marketing</option>
			</select></label>
			<label><span>Email <font color="red">*</font> '.$error6.'</span><input type="text" name="emailna" value="'.$_REQUEST['emailna'].'" size="30" placeholder="Email"></label>
			  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
			  </form></td></tr></table>';
echo '<!--WILAYAH COMBOBOX-->
	<script src="javascript/metcombo/prototype.js"></script>
	<script src="javascript/metcombo/dynamicombo.js"></script>
	<!--WILAYAH COMBOBOX-->
	<script>
	document.observe("dom:loaded",function(){
		new DynamiCombo( "id_cost" , {
			elements:{
				"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
				"fnamemitra":	{url:\'javascript/metcombo/data.php?req=setpoliscostumermitra\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["fnamemitra"] ?>\'},
				"id_reg":		{url:\'javascript/metcombo/data.php?req=setpoliscostumerregional\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_reg"] ?>\'},
				"id_cab":		{url:\'javascript/metcombo/data.php?req=setpoliscostumercabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
				"spv_cab":		{url:\'javascript/metcombo/data.php?req=setspvcabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["spv_cab"] ?>\'},
			},
			loadingImage:\'../loader1.gif\',
			loadingText:\'Loading...\',
			debug:0
		} )
	});
	</script>';
	;
	break;

case "vemobile":
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">
      <tr><th width="96%" align="left">Edit User Mobile</th><th><a href="user.php?op=vumobile"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table>';
	echo '</br>';
$metMob = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE id="'.$_REQUEST['idm'].'"'));
if ($_REQUEST['ope']=="Simpan") {
	if (!$_REQUEST['fname'])  $error1 .='<blink><font color=red>Nama lengkap tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['nm_userna'])  $error2 .='<blink><font color=red>Username tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['passwordna'])  $error3 .='<blink><font color=red>Password tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['rlevel'])  $error4 .='<blink><font color=red>Silahkan pilih level akses</font></blink><br>';
	//if (!$_REQUEST['rstatus'])  $error5 .='<blink><font color=red>Silahkan pilih status akses</font></blink><br>';
	if (!$_REQUEST['emailna'])  $error6 .='<blink><font color=red>email tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['nippertama'])  $error7 .='<blink><font color=red>NIP Pertama tidak bleh kosong</font></blink><br>';
	if (!$_REQUEST['nipkedua'])  $error8 .='<blink><font color=red>NIP Kedua tidak bleh kosong</font></blink><br>';
	if ($error1 OR $error2 OR $error3 OR $error4 OR $error5 OR $error6 OR $error7 OR $error8)
	{		}
	else
	{
		if ($_REQUEST['rlevel']=="1") {	$setspv = "1";	}else{	$setspv = "0";	}

		if ($_REQUEST['id_reg']=="") {	$userwilayah = "PUSAT";	}
		else	{	$cekreg = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_regional WHERE id="'.$_REQUEST['id_reg'].'"'));
			$userwilayah = $cekreg['name'];	}
		if ($_REQUEST['id_cab']=="") {	$usercabang = "PUSAT";	}
		else	{	$cekcab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$_REQUEST['id_cab'].'"'));
			$usercabang = $cekcab['name'];	}
		$s=$database->doQuery('UPDATE user_mobile SET namalengkap="'.$_REQUEST['fname'].'",
	  								  				passw="'.md5($_REQUEST['passwordna']).'",
	  								  				level="'.$_REQUEST['rlevel'].'",
									  				type="'.$_REQUEST['rstatus'].'",
									  				nip_primary="'.$_REQUEST['nippertama'].'",
									  				nip_secondary="'.$_REQUEST['nipkedua'].'",
									  				email="'.$_REQUEST['emailna'].'"
									  				WHERE id="'.$_REQUEST['idm'].'"');
		header("location:user.php?op=vumobile");
	}
}
echo '<table border="0" width="75%" align="center"><tr><td>
			<form method="POST" action="" class="input-list style-1 smart-green">
			<h1>Edit User Mobile</h1>
			<label><span>Nama Perusahaan</span><select name="id_cost" id="id_cost">
					<option value="">Pilih Perusahaan</option>';
	$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
	while ($ccost = mysql_fetch_array($metcost)) {	echo '<option value="'.$ccost['id'].'"'._selected($metMob['idbank'], $ccost['id']).' disabled>'.$ccost['name'].'</option>';	}
	echo '</select>
			</label>
			<label><span>Full Name <font color="red">*</font> '.$error1.'</span><input type="text" name="fname" value="'.$metMob['namalengkap'].'" size="30" placeholder="Nama Lengkap"></label>
			<label><span>NIP Pertama <font color="red">*</font> '.$error7.'</span><input type="text" name="nippertama" value="'.$metMob['nip_primary'].'" size="30" placeholder="NIP Primary"></label>
			<label><span>NIP Kedua <font color="red">*</font> '.$error8.'</span><input type="text" name="nipkedua" value="'.$metMob['nip_secondary'].'" size="30" placeholder="NIP Secundary"></label>
			<label><span>Username <font color="red">*</font> '.$error2.'</span><input type="text" name="nm_userna" value="'.$metMob['namalengkap'].'" size="30" placeholder="Username"></label>
			<label><span>Password <font color="red">*</font> '.$error3.'</span><input type="password" name="passwordna" value="'.$metMob['passwordna'].'" size="30" placeholder="Password"></label>
			<label><span>Level Akses <font color="red">*</font> '.$error4.'</span><select size="1" name="rlevel">
			<option value="">Select Level</option>
			<option value="Supervisor"'.pilih($metMob["level"], "Supervisor").'>Supervisor</option>
			<option value="Staff"'.pilih($metMob["level"], "Staff").'>Staff</option>
			<option value="Klinik"'.pilih($metMob["level"], "Klinik").'>Klinik</option>
			</select></label>
			<label><span>Status Akses</span><select size="1" name="rstatus">
			<option value="">Select Status</option>
			<option value="GM/Direksi"'.pilih($metMob["type"], "GM/Direksi").'>GM/Direksi</option>
			<option value="Kadiv Operasional"'.pilih($metMob["type"], "Kadiv Operasional").'>Kadiv Operasional</option>
			<option value="Kacab Operasional"'.pilih($metMob["type"], "Kacab Operasional").'>Kacab Operasional</option>
			<option value="Management Adonai"'.pilih($metMob["type"], "Management Adonai").'>Management Adonai</option>
			<option value="Dokter"'.pilih($metMob["type"], "Dokter").'>Dokter</option>
			<option value="Marketing"'.pilih($metMob["type"], "Marketing").'>Marketing</option>
			</select></label>
			<label><span>Email <font color="red">*</font> '.$error6.'</span><input type="text" name="emailna" value="'.$metMob['email'].'" size="30" placeholder="Email"></label>
			  <label><span>&nbsp;</span><input type="submit" name="ope" value="Simpan" class="button" /></label>
			  </form></td></tr></table>';
echo '<!--WILAYAH COMBOBOX-->
	<script src="javascript/metcombo/prototype.js"></script>
	<script src="javascript/metcombo/dynamicombo.js"></script>
	<!--WILAYAH COMBOBOX-->
	<script>
	document.observe("dom:loaded",function(){
		new DynamiCombo( "id_cost" , {
			elements:{
				"id_polis":		{url:\'javascript/metcombo/data.php?req=setpoliscostumer\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_polis"] ?>\'},
				"id_reg":	{url:\'javascript/metcombo/data.php?req=setpoliscostumerregional\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_reg"] ?>\'},
				"id_cab":	{url:\'javascript/metcombo/data.php?req=setpoliscostumercabang\', value:\'id\', label:\'name\', init:\'<?php echo $_POST["id_cab"] ?>\'},
			},
			loadingImage:\'loader1.gif\',
			loadingText:\'Loading...\',
			debug:0
		} )
	});
	</script>';
	;
	break;

case "editprod":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr><th width="95%" align="left">Edit Produk User</th><th><a href="user.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr>
      </table></br>';
$e=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id="'.$_REQUEST['id'].'"'));
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$e['id_cost'].'"'));
$met_polis = $database->doQuery('SELECT * FROM fu_ajk_polis WHERE tipe_produk="ajk" AND id_cost="'.$e['id_cost'].'" AND del IS NULL ORDER BY nmproduk ASC');

if ($_REQUEST['ope']=="Updated") {
	foreach($_REQUEST['nmproduk'] as $k => $val) {
		$metProduknya .= $val.'|';
	}
	$metUpdProd = $database->doQuery('UPDATE pengguna SET id_polis="'.$metProduknya.'" WHERE id="'.$e['id'].'"');
	echo '<center><h2>Data produk telah direvisi oleh ' . $q['nm_lengkap'] . ' pada tanggal ' . $futgl . '.</h2></center><meta http-equiv="refresh" content="2;URL=user.php">';
}

echo '<table border="0" width="75%" align="center"><tr><td>
		<form method="POST" action="" class="smart-green">
		<h1>Edit Data Account</h1>
		<label><span>Nama Perusahaan</span><input type="text" name="id_cost" value="'.$metcost['name'].'" disabled></label>
		<label><span>Nama Produk</span><br /><br />';
while($met_polis_ = mysql_fetch_array($met_polis)) {
	echo '<input type="checkbox" class="case" name="nmproduk[]" value="' . $met_polis_['id'] . '">'.$met_polis_['nmproduk'].'<br />';
}
echo '</lable>
	  	<br /><br /><label><span>&nbsp;</span><input type="submit" name="ope" value="Updated" class="button" /></label>
	  </form></td></tr></table>';
	;
	break;

case "setupmenus":
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%">
      <tr><th width="96%" align="left">Setup Menu</th><th><a href="user.php"><img src="../image/Backward-64.png" width="20" border="0"></a></th></tr></table>';
echo '</br>';
$met = mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE id="'.$_REQUEST['id'].'"'));
if ($_REQUEST['sc']=="savemenus") {
	$delUserMenus = $database->doQuery('DELETE FROM fu_ajk_menususer WHERE iduser="'.$met['id'].'"');
	foreach($_REQUEST['idparent'] as $k => $val) {
	$metUserParent = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$val.'"');
	}

	foreach($_REQUEST['a_idparentsub'] as $a_k => $a_val) {
	$met_aEx = explode("a_sub", $a_val);
	$metUserSub_a = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_aEx[1].'"');
	}

	foreach($_REQUEST['v_idparentsub'] as $v_k => $v_val) {
	$met_vEx = explode("v_sub", $v_val);
	$metUserSub_v = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_vEx[1].'"');
	}

	foreach($_REQUEST['e_idparentsub'] as $e_k => $e_val) {
	$met_eEx = explode("e_sub", $e_val);
	$metUserSub_e = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_eEx[1].'"');
	}

	foreach($_REQUEST['d_idparentsub'] as $d_k => $d_val) {
	$met_dEx = explode("d_sub", $d_val);
	$metUserSub_d = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_dEx[1].'"');
	}


	foreach($_REQUEST['a_idparentsubb'] as $a_kk => $a_vall) {
	$met_aExx = explode("a_subb", $a_vall);
	$metUserSubb_a = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_aExx[1].'"');
	}

	foreach($_REQUEST['v_idparentsubb'] as $v_kk => $v_vall) {
	$met_vExx = explode("v_subb", $v_vall);
	$metUserSubb_v = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_vExx[1].'"');
	}

	foreach($_REQUEST['e_idparentsubb'] as $e_kk => $e_vall) {
	$met_eExx = explode("e_subb", $e_vall);
	$metUserSubb_e = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_eExx[1].'"');
	}

	foreach($_REQUEST['d_idparentsubb'] as $d_kk => $d_vall) {
	$met_dExx = explode("d_subb", $d_vall);
	$metUserSubb_d = $database->doQuery('INSERT INTO fu_ajk_menususer SET iduser="'.$met['id'].'", idmenu="'.$met_dExx[1].'"');
	}
	echo '<center>Akses usermenu atas nama <font color="red"><b>'.$met['nm_lengkap'].'</b></font> telah dibuat oleh '.$_SESSION['nm_user'].' pada tanggal '.$futgldn.'.<br />
		 <meta http-equiv="refresh" content="2;URL=user.php">';
}
echo '<table border="0" width="90%" align="center"><tr><td>
		<form method="POST" action="" class="input-list style-1 smart-green">
		<h1>User : '.$met['nm_lengkap'].'</h1>
		<table border="0" width="100%" cellpadding="1" cellspacing="1">
		<tr><th width="5%">No</th>
			<th>Menu</th>
			<th width="10%">Akses</th>
			<th width="10%">View</th>
			<th width="10%">Edit</th>
			<th width="10%">Delete</th>
		</tr>';
$metparent = $database->doQuery('SELECT * FROM fu_ajk_menus WHERE parent = 0');
while ($metparent_ = mysql_fetch_array($metparent)) {
if (($no % 2))	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
$cekEd = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_menususer WHERE idmenu="'.$metparent_['id'].'" AND iduser="'.$_REQUEST['id'].'"'));
if ($cekEd['idmenu']) {	$__menu = 'checked';	}else{	$__menu = '';	}

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		<td valign="top" align="center">'.++$no.'</td>
		<td>'.$metparent_['menu'].'</td>
		<td valign="top" align="center"><input type="checkbox" class="case" name="idparent[]" value="' . $metparent_['id'] . '" '.$__menu.'></td>
		<td valign="top" align="center"> </td>
		<td valign="top" align="center"> </td>
		<td valign="top" align="center"> </td>
	 </tr>
	 <tr><td colspan="6">
	 <table border="0" width="100%" cellpadding="1" cellspacing="1">';
	$metparentSub = $database->doQuery('SELECT * FROM fu_ajk_menus WHERE parent = "'.$metparent_['id'].'" ');
	while ($metparentSub_ = mysql_fetch_array($metparentSub)) {
	if (($no1 % 2))	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
	$cekEdSub = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_menususer WHERE idmenu="'.$metparentSub_['id'].'" AND iduser="'.$_REQUEST['id'].'"'));
	if ($cekEdSub['idmenu']) {	$__menusub = 'checked';	}else{	$__menusub = '';	}
	echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			<td valign="top" align="right" width="5%"> </td>
			<td>'.$metparentSub_['menu'].'</td>
			<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="a_idparentsub[]" value="a_sub' . $metparentSub_['id'] . '" '.$__menusub.'></td>
			<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="v_idparentsub[]" value="v_sub' . $metparentSub_['id'] . '"></td>
			<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="e_idparentsub[]" value="e_sub' . $metparentSub_['id'] . '"></td>
			<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="d_idparentsub[]" value="d_sub' . $metparentSub_['id'] . '"></td>
		</tr>
		<tr><td colspan="6">
		<table border="0" width="100%" cellpadding="1" cellspacing="1">';
		$metparentSubb = $database->doQuery('SELECT * FROM fu_ajk_menus WHERE parent = "'.$metparentSub_['id'].'" ');
		while ($metparentSubb_ = mysql_fetch_array($metparentSubb)) {
		if (($no2 % 2) == 3)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
		$cekEdSubb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_menususer WHERE idmenu="'.$metparentSubb_['id'].'" AND iduser="'.$_REQUEST['id'].'"'));
		if ($cekEdSubb['idmenu']) {	$__menusubb = 'checked';	}else{	$__menusubb = '';	}
		echo '<tr>
					<td valign="top" align="right" width="8%"> - </td>
					<td>'.$metparentSubb_['menu'].'</td>
					<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="a_idparentsubb[]" value="a_subb' . $metparentSubb_['id'] . '" '.$__menusubb.'></td>
					<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="v_idparentsubb[]" value="v_subb' . $metparentSubb_['id'] . '"></td>
					<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="e_idparentsubb[]" value="e_subb' . $metparentSubb_['id'] . '"></td>
					<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="d_idparentsubb[]" value="d_subb' . $metparentSubb_['id'] . '"></td>
				</tr>
				<tr><td colspan="6">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">';
					$metparentSubbchild = $database->doQuery('SELECT * FROM fu_ajk_menus WHERE parent = "'.$metparentSubb_['id'].'" ');
					while ($metparentSubbchild_ = mysql_fetch_array($metparentSubbchild)) {
					if (($no3 % 2) == 4)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';
					$cekEdSubb = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_menususer WHERE idmenu="'.$metparentSubbchild_['id'].'" AND iduser="'.$_REQUEST['id'].'"'));
					if ($cekEdSubb['idmenu']) {	$__menusubbchild = 'checked';	}else{	$__menusubbchild = '';	}
					echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
							<td>&nbsp;</td>
							<td valign="top" align="right" width="8%"> -> </td>
							<td>'.$metparentSubbchild_['menu'].'</td>
							<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="a_idparentsubb[]" value="a_subb' . $metparentSubbchild_['id'] . '" '.$__menusubbchild.'></td>
							<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="v_idparentsubb[]" value="v_subb' . $metparentSubbchild_['id'] . '"></td>
							<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="e_idparentsubb[]" value="e_subb' . $metparentSubbchild_['id'] . '"></td>
							<td width="10%" valign="top" align="center"><input type="checkbox" class="case" name="d_idparentsubb[]" value="d_subb' . $metparentSubbchild_['id'] . '"></td>
						</tr>';
				}
				echo '</table>';
		}
		echo '</table>
		</tr>';
	}
echo '</table>
	 </td></tr>';
}
echo '</table>
		<tr><td colspan="6" align="center"><input type="hidden" name="sc" value="savemenus" class="button" /><input type="submit" name="ope" value="Simpan" class="button" /></td></tr>
		</form></td></tr></table>';
	;
	break;

default:
if ($q['status'] =="" OR $q['status'] =="UNDERWRITING") {
	$metnewuser ='<th><a href="user.php?op=tambah"><img border="0" src="../image/new.png" width="25"></a></th>';
}else{
	$metnewuser = '';
}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%">
     <tr><th width="95%" align="left">Daftar User</font></th>'.$metnewuser.'</tr>
     </table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1">
	<form method="post" action="">
	<tr><td width="10%">Username</td><td>: <input type="text" name="uName" value="'.$_REQUEST['uName'].'"></td></tr>
	<tr><td>Level User</td>
	<td>: <select id="levelUser" name="levelUser">
	  	<option value="">-----Level-----</option>';
	$metLevelUser = $database->doQuery('SELECT
pengguna.id,
pengguna.level AS lvluser,
fu_ajk_level.level AS lvlnama
FROM pengguna
INNER JOIN fu_ajk_level ON pengguna.`level` = fu_ajk_level.id
WHERE pengguna.del IS NULL AND fu_ajk_level.del IS NULL
GROUP BY pengguna.`level`
ORDER BY fu_ajk_level.`level` ASC');
while ($metLevelUser_ = mysql_fetch_array($metLevelUser)) {	echo '<option value="'.$metLevelUser_['lvluser'].'">'.$metLevelUser_['lvlnama'].'</option>';	}
echo '</select></td></tr>
	<tr><td colspan="2"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form></table>';
if ($_REQUEST['uName'])			{	$satu = 'AND nm_user LIKE "%' . $_REQUEST['uName'] . '%"';		}
if ($_REQUEST['levelUser'])		{	$dua = 'AND level LIKE "%' . $_REQUEST['levelUser'] . '%"';		}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
if ($q['status'] =="") {
$t=$database->doQuery('SELECT * FROM pengguna WHERE level!="" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL ORDER BY id_cost, id_polis, status, level ASC LIMIT '.$m.' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM pengguna WHERE level!="" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL'));
}else{
$t=$database->doQuery('SELECT * FROM pengguna WHERE id !="" AND id !=1 '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL ORDER BY id_cost, id_polis, status, level ASC LIMIT '.$m.' , 25');
$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(id) FROM pengguna WHERE id !="" '.$satu.' '.$dua.' '.$tiga.' AND del IS NULL'));
}
$totalRows = $totalRows[0];
echo '<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#bde0e6">
     <tr align="center">
     <th width="3%">No</th>
     <th>Costumer</th>
     <!--<th width="15%">Polis / Produk</th>-->
     <th width="15%">Fullname</th>
     <th width="10%">Username</th>
     <th width="15%">E-mail</th>
     <!--<th width="5%">Wilayah</th>-->
     <th width="5%">Cabang</th>
     <th width="10%">Level</th>
     <th width="1%">Aktif</th>
     <th width="1%">Produk</th>
     <th width="8%">Pilih</th>
	 </tr>';
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while($tt=mysql_fetch_array($t)){
$metcost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="'.$tt['id_cost'].'"'));
$metpol = mysql_fetch_array($database->doQuery('SELECT id, id_cost, nmproduk, nopol FROM fu_ajk_polis WHERE id="'.$tt['id_polis'].'"'));
if ($tt['id_cost']=="") {	$perusahaan = '<font color="blue">PT Adonai Pialang Asuransi</font>';	}	else	{	$perusahaan=$metcost['name'];	}

if ($tt['supervisor']=="1") {	if ($tt['id_cost'] !="") {	$metspv = "(Supervisor)";	}else{	$metspv="";	}	}else{	$metspv="";	}

$metlevelnya = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_level WHERE id="'.$tt['level'].'"'));

if ($tt['log']=="Y") 	{	$loguser = '<a href="user.php?op=offuser&idu='.$tt['id'].'"><font color="red" size="3"><b>'.$tt['log'].'</b></font></a>';	}else{	$loguser =$tt['log'];	}
if ($tt['id_cost']!="") {	$userproduk = '<a href="user.php?op=editprod&id='.$tt['id'].'" title="edit produk"><img border="0" src="image/edit3.png" width="20"></a>';	}else{	$userproduk = '';	}

if (($no % 2) == 1)	$objlass = 'tbl-odd';	else	$objlass = 'tbl-even';
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
      <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
	  <td>'.$perusahaan.'</td>
	  <!--<td align="center">'.$metpol['nmproduk'].'</td>-->
	  <td>'.strtoupper($tt['nm_lengkap']).' '.$metspv.'</td>
	  <td>'.$tt['nm_user'].'</td>
	  <td>'.$tt['email'].'</td>
	  <!--<td align="center">'.strtoupper($tt['wilayah']).'</td>-->
	  <td align="center">'.strtoupper($tt['cabang']).'</td>
	  <td align="center">'.$metlevelnya['level'].'</td>
	  <td align="center">'.$loguser.'</td>
	  <td align="center">'.$userproduk.'</td>';

if ($q['level']=="" OR $q['level']=="4" AND $q['status']=="UNDERWRITING") {
echo ' <td align="center"><a href="user.php?id='.$tt['id'].'&op=edit"><img border="0" src="../image/editaja.png" width="20">&nbsp;
	  					  <a href="user.php?id='.$tt['id'].'&op=delete" onClick="if(confirm(\'Anda yakin akan menghapus data ?\')){return true;}{return false;}"><img border="0" src="../image/delete.png" width="20">
						  <a href="user.php?id='.$tt['id'].'&op=setupmenus" title="struktur menu user"><img border="0" src="../image/ya2.png" width="20">&nbsp;
	   </td>';
}elseif ($_SESSION['nm_user']==$tt['nm_user'] OR $_SESSION['nm_user']=="underwriting") {
echo ' <td align="center"><a href="user.php?id='.$tt['id'].'&op=edit"><img border="0" src="../image/editaja.png" width="20"></td>';
}else{
	echo ' <td align="center"><img border="0" src="../image/editdis.png" Disable></td></tr>';
}
echo '</tr>';
	 } // while
echo '<tr><td colspan="22">';
echo createPageNavigations($file = 'user.php?uName='.$_REQUEST['uName'].'&levelUser='.$_REQUEST['levelUser'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
echo '<b>Total Data User : <u>' . $totalRows . '</u></b></td></tr>
	  </table>';
} // switch
?>