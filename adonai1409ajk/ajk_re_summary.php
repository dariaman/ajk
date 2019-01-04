<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :kepodank@gmail.com
// ADONAI - AJK Online 2014
// ----------------------------------------------------------------------------------
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (isset($_SESSION['nm_user'])) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));}
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan Summary</font></th></tr></table>';
switch ($_REQUEST['c']) {
	case "ranking":
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$metcostproduk = $database->doQuery('SELECT * FROM fu_ajk_grupproduk WHERE id_cost="1" ORDER BY nmproduk DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
				<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Nama Produk <font color="red">*</font></td>
		<td id="polis_rate">: <select name="id_polis" id="id_polis">
		<option value="">-- Pilih Produk --</option>
		</select></td></tr>
				<tr><td align="right">Group Produk <font color="red">*</font></td>
					<!--<td> : <select name="grupprod" id="grupprod">
					  	<option value="">---Pilih Group Produk---</option>
					  	<option value="BUKOPIN"'._selected($_REQUEST["grupprod"], "BUKOPIN").'>BUKOPIN</option>
					  	<option value="BPR"'._selected($_REQUEST["grupprod"], "BPR").'>BPR</option>
					  	<option value="KNS"'._selected($_REQUEST["grupprod"], "KNS").'>KNS</option>
					  	<option value="KOSSPI"'._selected($_REQUEST["grupprod"], "KOSSPI").'>KOSSPI</option>
					  	<option value="MEKARSARI"'._selected($_REQUEST["grupprod"], "MEKARSARI").'>MEKARSARI</option>
				</select></td>-->
				<td> : <select name="grupprod" id="grupprod">
				<option value="">---Pilih Group Produk---</option>';
		while($metcostproduk_ = mysql_fetch_array($metcostproduk)) {
			echo  '<option value="'.$metcostproduk_['id'].'"'._selected($_REQUEST['grupprod'], $metcostproduk_['id']).'>'.$metcostproduk_['nmproduk'].'</option>';
		}
		echo '</select></td>
			  </tr>
			  <tr><td align="right">Tanggal Debit Note<font color="red">*</font></td>
				  <td> : <input type="text" id="fromdn1" name="tglstat1" value="'.$_REQUEST['tglstat1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;"/>
						 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;">
						 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
						 s/d
						 <input type="text" id="fromdn2" name="tglstat2" value="'.$_REQUEST['tglstat2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;"/>
						 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;">
						 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
						 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td>
			  </tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
			  </table>
			  </form> ';
		if ($_REQUEST['re']=="dataDN") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
			if ($_REQUEST['grupprod']=="") 	{	$error_4 = '<div align="center"><font color="red"><blink>Silahkan pilih Group Produk...!!<br /></div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3 OR $error_4) {	echo $error_1 .''.$error_2.''.$error_3.''.$error_4;	}
			else{
				echo'<a target="_BLANK" href="e_report_summary.php?er=eL_rank&cat='.$_REQUEST['id_cost'].'&idp='.$_REQUEST['id_polis'].'&gpr='.$_REQUEST['grupprod'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Statistik Rangking</a>';
				echo"<script>
				window.open('e_report_summary.php?er=eL_rank&cat=".$_REQUEST['id_cost']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."&idp=".$_REQUEST['id_polis']."&gpr=".$_REQUEST['grupprod']."');
				</script>";

			}
		}
		echo"<script type='text/javascript' src='js/jquery/jquery.min-1.11.1.js'></script>
<script type='text/javascript'>//<![CDATA[
 	$(window).load(function(){
 		$(document).ready(function () {
 			(function ($) {
 				$('#cari').keyup(function () {
 					var rex = new RegExp($(this).val(), 'i');
 					$('.caritable tr').hide();
 					$('.caritable tr').filter(function () {
 						return rex.test($(this).text());
 					}).show();

 				})

 			}(jQuery));

			$('#id_cost').change(function(){
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumer',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_polis').html(returndata);
					}
				});

				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumerregional',cost:idcost},
					cache:false,
					success:function(returndata) {
						$('#id_reg').html(returndata);
					}
				});

			});
			$('#id_reg').change(function(){
			var noreg = document.getElementById('id_reg').value;
			var idcost = document.getElementById('id_cost').value;
				$.ajax({
					type:'post',
					url:'javascript/metcombo/data_peserta.php',
					data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
					cache:false,
					success:function(returndata) {
						$('#id_cab').html(returndata);
					}
				});

			});

 		});
 			var idcost = document.getElementById('id_cost').value;
			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumer',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_polis').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumerregional',cost:idcost},
				cache:false,
				success:function(returndata) {
					$('#id_reg').html(returndata);
				}
			});

			$.ajax({
				type:'post',
				url:'javascript/metcombo/data_peserta.php',
				data:{functionname: 'setpoliscostumercabang',cost:idcost,regi:noreg},
				cache:false,
				success:function(returndata) {
					$('#id_cab').html(returndata);
				}
			});
 	});


</script>";
		;
		break;
	case "lostrasio":
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
			  <tr><td align="right">Tanggal<font color="red">*</font></td>
		  <td> :<input type="text" id="fromdn1" name="tglstat1" value="'.$_REQUEST['tglstat1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglstat2" value="'.$_REQUEST['tglstat2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td>
			  </tr>
				<tr>
					<td width="40%" align="right">Option</td>
					<td> :
						<select name="opt" id="opt">
						  <option value="bank"'._selected($_REQUEST["opt"],"bank").'>Bank</option>
						  <option value="asuransi"'._selected($_REQUEST["opt"],"asuransi").'>Asuransi</option>
			  		</select>
			  	</td>
			  </tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
			  </table>
			  </form> ';
		if ($_REQUEST['re']=="dataDN") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
			else{
				mysql_query('DELETE FROM rpt_report_summary WHERE UserName = "'.$_SESSION['nm_user'].'" AND TglPrint <> now()');
				mysql_query("call sp_report_summary('".$_REQUEST['id_cost']."','".$_REQUEST['tglstat1']."', '".$_REQUEST['tglstat2']."',now(), '".$_SESSION['nm_user']."') ");
				echo'<a target="_BLANK" href="e_report_summary.php?er=eL_lossrasio&cat='.$_REQUEST['id_cost'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'&gpr='.$_REQUEST['grupprod'].'&opt='.$_REQUEST['opt'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Lost Rasio Per cabang</a><br>';
				echo'<a target="_BLANK" href="e_report_summary.php?er=su_lossratio&cat='.$_REQUEST['id_cost'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'&gpr='.$_REQUEST['grupprod'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Lost Rasio</a>';
				echo"<script>
				window.open('e_report_summary.php?er=eL_lossrasio&cat=".$_REQUEST['id_cost']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."&gpr=".$_REQUEST['grupprod']."');
				</script><script>
				window.open('e_report_summary.php?er=su_lossratio&cat=".$_REQUEST['id_cost']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."&gpr=".$_REQUEST['grupprod']."');
				</script>";

			}
		};
		break;
	case "klaim":
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
			  <tr><td align="right">Tanggal Input<font color="red">*</font></td>
		  <td> :<input type="text" id="fromdn1" name="tglstat1" value="'.$_REQUEST['tglstat1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglstat2" value="'.$_REQUEST['tglstat2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td>
			  </tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
			  </table>
			  </form> ';
		if ($_REQUEST['re']=="dataDN") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
			else{
				echo'<a target="_BLANK" href="e_report_summary.php?er=eL_klaim&cat='.$_REQUEST['id_cost'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Klaim</a>';
				echo"<script>
				window.open('e_report_summary.php?er=eL_klaim&cat=".$_REQUEST['id_cost']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."');
				</script>";

			}
		};
		break;
	case "produksi":
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
			  <tr><td align="right">Tanggal Debit Note<font color="red">*</font></td>
		  <td> : <input type="text" id="fromdn1" name="tglstat1" value="'.$_REQUEST['tglstat1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglstat2" value="'.$_REQUEST['tglstat2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td>
			  </tr>
			  <tr><td align="right">Status Peserta </td><td> :
					  <select size="1" name="statpeserta"><option value="">--- Status Peserta---</option>
		  								  <option value="Inforce"'._selected($_REQUEST['statpeserta'], "Inforce").'>Inforce</option>
										  <option value="Maturity"'._selected($_REQUEST['statpeserta'], "Maturity").'>Maturity</option>
										  <option value="Pending"'._selected($_REQUEST['statpeserta'], "Pending").'>Pending</option>
										  <option value="Lapse-Batal"'._selected($_REQUEST['statpeserta'], "Lapse-Batal").'>Lapse - Batal</option>
										  <option value="Lapse-Refund"'._selected($_REQUEST['statpeserta'], "Lapse-Refund").'>Lapse - Refund</option>
										  <option value="Lapse-Death"'._selected($_REQUEST['statpeserta'], "Lapse-Death").'>Lapse - Meninggal</option>
					  </select>
			  </td></tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
			  </table>
			  </form> ';
		if ($_REQUEST['re']=="dataDN") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
			else{
				echo'<a target="_BLANK" href="e_report_summary.php?er=eL_prod&cat='.$_REQUEST['id_cost'].'&statpeserta='.$_REQUEST['statpeserta'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Produksi</a>';
				echo"<script>
				window.open('e_report_summary.php?er=eL_prod&cat=".$_REQUEST['id_cost']."&statpeserta=".$_REQUEST['statpeserta']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."');
				</script>";

			}
		};
		break;
	case "tagprem":
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Group Produk</td><td> : <select name="grupprod" id="grupprod">
					  	<option value="">---Pilih Group Produk---</option>
					  	<option value="BUKOPIN"'._selected($_REQUEST["grupprod"], "BUKOPIN").'>BUKOPIN</option>
					  	<option value="BPR"'._selected($_REQUEST["grupprod"], "BPR").'>BPR</option>
					  	<option value="KNS"'._selected($_REQUEST["grupprod"], "KNS").'>KNS</option>
					  	<option value="KOSSPI"'._selected($_REQUEST["grupprod"], "KOSSPI").'>KOSSPI</option>
					  	<option value="MEKARSARI"'._selected($_REQUEST["grupprod"], "MEKARSARI").'>MEKARSARI</option>
				</select></td></tr>
			  <tr><td align="right">Tanggal Debit Note<font color="red">*</font></td>
		  <td> : <input type="text" id="fromdn1" name="tglstat1" value="'.$_REQUEST['tglstat1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglstat2" value="'.$_REQUEST['tglstat2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td>
			  </tr>
			  <tr><td align="right">Status Pembayaran Debitnote</td><td> :
			  <select size="1" name="paiddata"><option value="">--- Status ---</option>
			  								  <option value="paid"'._selected($_REQUEST['paiddata'], "paid").'>Paid</option>
			  								  <option value="paid(*)"'._selected($_REQUEST['paiddata'], "paid(*)").'>Paid(*)</option>
											  <option value="unpaid"'._selected($_REQUEST['paiddata'], "unpaid").'>Unpaid</option>
			  </select>
			  </td></tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
			  </table>
			  </form> ';
		if ($_REQUEST['re']=="dataDN") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal akhir asuransi tidak boleh kosong</div></font></blink>';	}
			//if ($_REQUEST['grupprod']=="") 	{	$error_4 = '<div align="center"><font color="red"><blink>Silahkan pilih Group Produk...!!<br /></div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
			else{
				echo'<a target="_BLANK" href="e_report_summary.php?er=eL_tagprem&cat='.$_REQUEST['id_cost'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'&gpr='.$_REQUEST['grupprod'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Tagihan Premi</a>';
				echo"<script>
				window.open('e_report_summary.php?er=eL_tagprem&cat=".$_REQUEST['id_cost']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."&gpr=".$_REQUEST['grupprod']."');
				</script>";

			}
		};
		break;
	case "prodcab":
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
				<tr><td align="right">Group Produk <font color="red">*</font></td><td> : <select name="grupprod" id="grupprod">
					  	<option value="">---Pilih Group Produk---</option>
					  	<option value="BUKOPIN"'._selected($_REQUEST["grupprod"], "BUKOPIN").'>BUKOPIN</option>
					  	<option value="BPR"'._selected($_REQUEST["grupprod"], "BPR").'>BPR</option>
					  	<option value="KNS"'._selected($_REQUEST["grupprod"], "KNS").'>KNS</option>
					  	<option value="KOSSPI"'._selected($_REQUEST["grupprod"], "KOSSPI").'>KOSSPI</option>
					  	<option value="MEKARSARI"'._selected($_REQUEST["grupprod"], "MEKARSARI").'>MEKARSARI</option>
				</select></td></tr>
			  <tr><td align="right">Tanggal Debit Note<font color="red">*</font></td>
		  <td> :<input type="text" id="fromdn1" name="tglstat1" value="'.$_REQUEST['tglstat1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglstat2" value="'.$_REQUEST['tglstat2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td>
			  </tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
			  </table>
			  </form> ';
		if ($_REQUEST['re']=="dataDN") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
			if ($_REQUEST['grupprod']=="") 	{	$error_4 = '<div align="center"><font color="red"><blink>Silahkan pilih Group Produk...!!<br /></div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3 OR $error_4) {	echo $error_1 .''.$error_2.''.$error_3.''.$error_4;	}
			else{
				echo'<a target="_BLANK" href="e_report_summary.php?er=eL_prodcab&cat='.$_REQUEST['id_cost'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'&gpr='.$_REQUEST['grupprod'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Produksi Per Cabang</a>';
				echo"<script>
				window.open('e_report_summary.php?er=eL_prodcab&cat=".$_REQUEST['id_cost']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."&gpr=".$_REQUEST['grupprod']."');
				</script>";

			}
		};
		break;
	case "pemkes":
		$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
		$ls_cost = $metcost['id'];
		echo '<form method="post" action="" name="statrankform">
			  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
			  <table border="0" cellpadding="1" cellspacing="0" width="100%">
		      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
		while($metcost_ = mysql_fetch_array($metcost)) {
			echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
		}
		echo '</select></td></tr>
			  <tr><td align="right">Tanggal Input<font color="red">*</font></td>
		  <td> :<input type="text" id="fromdn1" name="tglstat1" value="'.$_REQUEST['tglstat1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglstat2" value="'.$_REQUEST['tglstat2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.statrankform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
		echo '</td>
			  </tr>
			  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
			  </table>
			  </form> ';
		if ($_REQUEST['re']=="dataDN") {
			if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
			if ($_REQUEST['tglstat2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
			if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
			else{
				echo'<a target="_BLANK" href="e_report_summary.php?er=eL_pemkes&cat='.$_REQUEST['id_cost'].'&tgl1='.$_REQUEST['tglstat1'].'&tgl2='.$_REQUEST['tglstat2'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Pemeriksa Kesehatan</a>';
				echo"<script>
				window.open('e_report_summary.php?er=eL_pemkes&cat=".$_REQUEST['id_cost']."&tgl1=".$_REQUEST['tglstat1']."&tgl2=".$_REQUEST['tglstat2']."');
				</script>";

			}
		};
		break;

	default:
$metcost = $database->doQuery('SELECT * FROM fu_ajk_costumer ORDER BY name DESC');
$ls_cost = $metcost['id'];
echo '<form method="post" action="" name="postform">
	  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="40%" align="right">Nama Perusahaan <font color="red">*</font></td><td> : <select name="id_cost" id="id_cost">
		<option value="">---Pilih Perusahaan---</option>';
while($metcost_ = mysql_fetch_array($metcost)) {
echo  '<option value="'.$metcost_['id'].'"'._selected($_REQUEST['id_cost'], $metcost_['id']).'>'.$metcost_['name'].'</option>';
}
echo '</select></td></tr>
	  <tr><td align="right">Tanggal<font color="red">*</font></td>
		  <td> :<input type="text" id="fromdn1" name="tglakad1" value="'.$_REQUEST['tglakad1'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn1);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
				 s/d
				 <input type="text" id="fromdn2" name="tglakad2" value="'.$_REQUEST['tglakad2'].'" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;"/>
				 <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.postform.fromdn2);return false;">
				 <img name="popcal" align="absmiddle" style="border:none" src="calender/calender.jpeg" width="30" height="25" border="0" alt=""></a></div>
				 <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>';
echo '</td>
	  </tr>
	  <tr><td align="center"colspan="2"><input type="hidden" name="re" value="dataDN"><input type="submit" name="ere" id="btncari" value="Cari"></td></tr>
	  </table>
	  </form>';
if ($_REQUEST['re']=="dataDN") {
if ($_REQUEST['id_cost']=="") 	{	$error_1 = '<div align="center"><font color="red"><blink>Silahkan pilih nama Costumer...!!<br /></div></font></blink>';	}
if ($_REQUEST['tglakad1']=="") 	{	$error_2 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong<br /></div></font></blink>';	}
if ($_REQUEST['tglakad2']=="") 	{	$error_3 = '<div align="center"><font color="red"><blink>Tanggal mulai asuransi tidak boleh kosong</div></font></blink>';	}
if ($error_1 OR $error_2 OR $error_3) {	echo $error_1 .''.$error_2.''.$error_3;	}
else{
if ($_REQUEST['id_cost'])		{	$satu = 'AND id_cost = "'.$_REQUEST['id_cost'].'"';	}
if ($_REQUEST['tglakad1'])		{	$tiga = 'AND kredit_tgl BETWEEN "'.$_REQUEST['tglakad1'].'" AND "'.$_REQUEST['tglakad2'].'" ';	}

echo '
<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
	<tr>
	<td bgcolor="#FFF"colspan="1" ><a target="_BLANK" href="e_report_summary.php?er=eL_rank&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Statistik Rangking</a></td>
	<td bgcolor="#FFF"colspan="1" ><a target="_BLANK" href="e_report_summary.php?er=eL_lossrasio&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Lost Rasio</a></td>
	<td bgcolor="#FFF"colspan="1" ><a target="_BLANK" href="e_report_summary.php?er=eL_klaim&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Klaim</a></td>
	<td bgcolor="#FFF"colspan="1" ><a target="_BLANK" href="e_report_summary.php?er=eL_prod&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Produksi</a></td>
	<td bgcolor="#FFF"colspan="1" ><a target="_BLANK" href="e_report_summary.php?er=eL_tagprem&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Tagihan Premi</a></td>
	<td bgcolor="#FFF"colspan="1" ><a target="_BLANK" href="e_report_summary.php?er=eL_prodcab&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Produksi Percabang</a></td>
	<td bgcolor="#FFF"colspan="1" ><a target="_BLANK" href="e_report_summary.php?er=eL_pemkes&cat='.$_REQUEST['id_cost'].'&subcat='.$_REQUEST['id_polis'].'&tgl1='.$_REQUEST['tglakad1'].'&tgl2='.$_REQUEST['tglakad2'].'&paid='.$_REQUEST['paiddata'].'&status='.$_REQUEST['statpeserta'].'&id_reg='.$_REQUEST['id_reg'].'&id_cab='.$_REQUEST['id_cab'].'"><img src="image/dninvoice1.jpg" width="25" border="0"><br />Pemeriksaan Kesehatan</a></td>
	</tr>';
	echo '</table>';
}
}
		;
} // switch
?>
