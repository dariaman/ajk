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
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><th width="80%" align="left" colspan="2">Modul Report MIS</font></th></tr></table>';

$cat=$_GET['cat']; // Use this line or below line if register_global is off
if(strlen($cat) > 0 and !is_numeric($cat)){ // to check if $cat is numeric data or not.
	echo "Data Error";	exit;
}
echo '<fieldset style="padding: 2">
	<legend align="center">Report Data MIS</legend>
	<table border="0" width="50%" cellpadding="1" cellspacing="1" align="center">
		<form method="post" action="">
		<tr><td width="25%">Nama Perusahaan</td>
	  <td width="30%"> : <select id="cat" name="cat" onchange="reload(this.form)">
	  	<option value="">---Select Company---</option>';
$quer2=$database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name ASC');
while($noticia2 = mysql_fetch_array($quer2)) {
if($noticia2['id']==$cat){echo '<option selected value="'.$noticia2['id'].'">'.$noticia2['name'].'</option><BR>';}
else{echo  '<option value="'.$noticia2['id'].'">'.$noticia2['name'].'</option>';}
}
echo '</select></td></tr>
	<tr><td width="10%">Nomor Polis</td>
		<td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_polis where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_polis ORDER BY id ASC"); }
echo '<select name="subcat"><option value="">---Select Policy---</option>';
while($noticia = mysql_fetch_array($quer)) {
	echo  '<option value='.$noticia['id'].'>'.$noticia['nopol'].'</option>';
}
echo '</select></td></tr>
	<tr><td width="10%">Regional</td>
		<td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_regional where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_regional ORDER BY id ASC"); }
echo '<select name="subcatreg"><option value="">---Pilih Regional---</option>';
while($noticia = mysql_fetch_array($quer)) {	echo  '<option value="'.$noticia['name'].'">'.$noticia['name'].'</option>';	}
echo '</select></td></tr>
	<tr><td width="10%">Area</td>
		<td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_area where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_area ORDER BY id ASC"); }
echo '<select name="subcatarea"><option value="">---Pilih Area---</option>';
while($noticia = mysql_fetch_array($quer)) {	echo  '<option value="'.$noticia['name'].'">'.$noticia['name'].'</option>';	}
echo '</select></td></tr>
	<tr><td width="10%">Cabang</td>
		<td width="20%"> : ';
if(isset($cat) and strlen($cat) > 0){
	$quer=$database->doQuery('SELECT * FROM fu_ajk_cabang where id_cost="'.$cat.'" ORDER BY id ASC');
}else{$quer=$database->doQuery("SELECT * FROM fu_ajk_cabang ORDER BY id ASC"); }
echo '<select name="subcatcab"><option value="">---Pilih Cabang---</option>';
while($noticia = mysql_fetch_array($quer)) {	echo  '<option value='.$noticia['name'].'>'.$noticia['name'].'</option>';	}
echo '</select></td></tr>
<tr><td>Tanggal Mulai Asuransi</td><td> :
<input type="text" name="tanggal1" id="tanggal1" class="tanggal" value="'.$_REQUEST['tanggal1'].'" size="10"/> s/d
<input type="text" name="tanggal2" id="tanggal2" class="tanggal" value="'.$_REQUEST['tanggal2'].'" size="10"/>
</td></tr>
	<tr><td>Status Pembayaran <input type="radio"'.pilih($_REQUEST['Rpembayaran'], "3").' name="Rpembayaran" value="3" checked disabled>All</td></td><td> :
		<!--<select size="1" name="Rpembayaran">
		<option value="">--- Pembayaran ---</option>
		<option value="1"'._selected($_REQUEST['Rpembayaran'], "1").'>Paid</option>
		<option value="0"'._selected($_REQUEST['Rpembayaran'], "0").'>UnPaid</option>
		</select>-->
		<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "1").' name="Rpembayaran" value="1" disabled>Paid &nbsp;
 		<input type="radio"'.pilih($_REQUEST['Rpembayaran'], "2").' name="Rpembayaran" value="2" disabled>Unpaid &nbsp;
		</td></tr>
	<tr><td>Status Peserta <input type="checkbox" id="selectall" />ALL</td><td> :
	 <input type="checkbox" class="case" name="statspeserta[]" value="aktif" id="cbx" checked disabled>Inforce &nbsp;
	 <input type="checkbox" class="case" name="statspeserta[]" value="Lapse" id="cbx" disabled>Lapse
	 <input type="checkbox" class="case" name="statspeserta[]" value="Maturity" id="cbx" disabled>Maturity
	 <input type="checkbox" class="case" name="statspeserta[]" value="Pending" id="cbx" disabled>Pending
	 <input type="checkbox" class="case" name="statspeserta[]" value="Cancel" id="cbx" disabled>Cancel
	  </td></tr>
		<tr><td>Tipe DN/CN <input type="checkbox" id="selectalldncn" />ALL</td><td> :
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Restruktur" id="cbx2" checked disabled>Restruktur &nbsp;
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Top Up" id="cbx2" checked disabled>Top Up
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Baloon" id="cbx2" checked disabled>Baloon
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Refund" id="cbx2" disabled disabled>Refund
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Death" id="cbx2" disabled>Meninggal
	 <input type="checkbox" class="casedncn" name="typepeserta[]" value="Batal" id="cbx2" disabled>Batal
	  </td></tr>
<tr><td>Tanggal DN dibuat</td><td> :
<input type="text" name="tanggal3" id="tanggal3" class="tanggal" value="'.$_REQUEST['tanggal3'].'" size="10"/> s/d
<input type="text" name="tanggal4" id="tanggal4" class="tanggal" value="'.$_REQUEST['tanggal4'].'" size="10"/>
</td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="metreport" value="Cari" class="button"></td>
	</tr>
	</form></table></fieldset>';
if ($_REQUEST['metreport']=="Cari") {


}
?>
<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='ajk_Rmis.php?cat=' + val;
}
</script>

<!--CHECKE ALL STATUS PESERTA-->
<SCRIPT language="javascript">
$(function(){
    $("#selectall").click(function () {	$('.case').attr('checked', this.checked);	});			    // add multiple select / deselect functionality
    $(".case").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }

    });
});
</SCRIPT>

<!--CHECKE ALL STATUS DATA DN/CN-->
<SCRIPT language="javascript">
$(function(){
    $("#selectalldncn").click(function () {	$('.casedncn').attr('checked', this.checked);	});			    // add multiple select / deselect functionality
    $(".casedncn").click(function(){																	// if all checkbox are selected, check the selectall checkbox	// and viceversa
        if($(".casedncn").length == $(".casedncn:checked").length) {
            $("#selectalldncn").attr("checked", "checked");
        } else {
            $("#selectalldncn").removeAttr("checked");
        }

    });
});
</SCRIPT>