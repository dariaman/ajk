<?php
// ----------------------------------------------------------------------------------
// Original Author Of File : Rahmad
// E-mail :penting_kaga@yahoo.com
// ----------------------------------------------------------------------------------
include_once('ui.php');
include_once ("includes/functions.php");
connect();
$today = date("Y-m-d G:i:s");
$q=mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE nama="'.$_SESSION['nm_user'].'"'));
$central=mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_cabang WHERE del is null and id = '.$q['cabang']));
if(isset($_SESSION['timeout']) ) {	$session_life = time() - $_SESSION['timeout'];	if($session_life > $inactive) echo "0";	else echo "1";	}
$_SESSION['timeout'] = time();
echo '<script>
$(document).ready(function(){
setInterval(function(){
        $.get("imob.php", function(data){
        if(data==0) window.location.href="login.php?op=logout";
        });
    },1*60*1000);
});
</script>';

$mb=mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE nama="'.$_SESSION['nm_user'].'"'));
if ($mb['type']=="Dokter") {
echo "<div id='cssmenu'>
		  <ul><li><a href='imob.php'><span>Home</span></a></li>
			  <li><a href='imob.php?ob=tabgen'><span>Tab Generator</span></a></li>
			  <li><a href='login.php?op=logout'><span>Logout</span></a></li>
			  <li class='displayname'>".$mb['namalengkap']."</li>
		</ul>
	</div><br /><br />";
}elseif ($mb['type']=="Marketing"){
echo "<div id='cssmenu'>
		  <ul><li><a href='imob.php'><span>Home</span></a></li>
			  <li><a href='imob.php?ob=appspk'><span>Approve SPK</span></a></li>
			  <li><a href='imob.php?ob=listspk'><span>List Debitur SPK</span></a></li>
			  <li><a href='imob.php?ob=rSPK'><span>Report</span></a></li>";
 				if($central['centralcbg']!="" or $q['namamitra']!= 1 or $q['cabang']== 4){
			  	echo "<li><a href='imob.php?ob=listrevisi'><span>Revisi</span></a></li>";
			  }			  
echo   "<li><a href='login.php?op=logout'><span>Logout</span></a></li>
			  <li class='displayname'>".$mb['namalengkap']."</li>
		</li>
	</ul>
	</div><br /><br />";
}else{
	header('Location: login.php?op=logout');
}
switch ($_REQUEST['ob']) {
	case "listrevisi":
		echo'<br>
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<tr>
							<th width="95%" align="left">List Revisi</font></th>
						</tr>
					</table><br>';					
					echo'<form action="imob.php?ob=newrevisi" method="POST">
							 		<button type="submit">Revisi Baru</button>
							 </form>';
					$q_revisi = $database->doQuery("SELECT *,DATE_FORMAT(date_input,'%Y-%m-%d')as tgl_revisi 
																					FROM t_revisi 
																					WHERE user_input = '".$_SESSION['nm_user']."' and flag_aktif = 'T' ORDER BY date_input DESC","adnoffice");
			
			echo '<br>
					 <table border="1" cellpadding="5" cellspacing="0" width="100%" >
							<tr>
								<td align="center" width="2%" bgcolor="#bde0e6">No</td>
								<td align="center" width="3%" bgcolor="#bde0e6">No Tiket</td>
								<td align="center" width="3%" bgcolor="#bde0e6">Tgl Revisi</td>
								<td align="center" width="5%" bgcolor="#bde0e6">No Referensi</td>
								<td align="center" width="5%" bgcolor="#bde0e6">Nama Peserta</td>
								<td align="center" width="5%" bgcolor="#bde0e6">Produk</td>
								<td align="center" width="20%" bgcolor="#bde0e6">Keterangan</td>
								<td align="center" width="3%" bgcolor="#bde0e6">Atch</td>
								<td align="center" width="3%" bgcolor="#bde0e6">Atch2</td>
								<td align="center" width="3%" bgcolor="#bde0e6">Status</td>							
								<td align="center" width="10%" bgcolor="#bde0e6">Catatan Status</td>
							</tr>';
						$no = 1;
						while($q_revisi_r = mysql_fetch_array($q_revisi)){
							
							$status = mysql_fetch_array(mysql_query("SELECT nm_status,note
																											 FROM t_revisi_progress 
																											 			INNER JOIN m_status
			 																											ON m_status.kd_status = t_revisi_progress.kd_status
																														WHERE kd_revisi = '".$q_revisi_r['kd_revisi']."' ORDER BY KD_PROGRESS DESC LIMIT 1"));
							
						if($q_revisi_r['attachment'] != ""){
							$attachment = '<a href="../adnoffice/assets/documents/revisi/'.$q_revisi_r['attachment'].'" target="_blank"><img src="image/edit3.png" width="20"></a>';						
						}else{
							$attachment = ' - ';
						}
						
						if($q_revisi_r['attachment2'] != ""){
							$attachment2 = '<a href="../adnoffice/assets/documents/revisi/'.$q_revisi_r['attachment2'].' target="_blank"><img src="image/edit3.png" width="20"></a>';						
						}else{
							$attachment2 = ' - ';
						}

			echo 		'<tr>
									<td align="center">'.$no.'</td>
									<td align="center">'.$q_revisi_r['no_revisi'].'</td>								
									<td align="center">'._convertDate($q_revisi_r['tgl_revisi']).'</td>
									<td align="center">'.$q_revisi_r['no_ref'].'</td>
									<td align="center">'.$q_revisi_r['nm_peserta'].'</td>
									<td align="center">'.$q_revisi_r['produk'].'</td>								
									<td>'.$q_revisi_r['keterangan'].'</td>	
									<td align="center">'.$attachment.'</td>
									<td align="center">'.$attachment2.'</td>															
									<td align="center">'.$status['nm_status'].'</td>
									<td align="center">'.$status['note'].'</td>
							 </tr>';
							 $no++;
						} 
			echo '</table>';	
	break;

	case "newrevisi":
		echo '<br>
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<tr>
							<th width="95%" align="left">Revisi</font></th>					
						</tr>				
					</table>';
		echo '<br>
					<table border="0" width="100%" cellpadding="1" cellspacing="1">
						<form method="post" name="frm_pengajuan" action="imob.php?ob=addrevisi" enctype="multipart/form-data">
							<tr>
								<td align="right" width="7%">No Referensi : </td>
								<td><input type="text" size="20" name="ref" id="ref" required><font color="red"> *No Referensi diisi No SPK /No DN / No Peserta / No Percepatan</font></td>
							</tr>
							<tr>
								<td align="right" width="7%">Nama Peserta : </td>
								<td><input type="text" size="20" name="peserta" id="peserta" required></td>
							</tr>
							<tr>
								<td align="right" width="7%">Produk : </td>
								<td>
									<select id="produk" name="produk" required>
										<option value="">- Pilih -</option>';								
							
							$qproduk = $database->doQuery("select * from fu_ajk_polis where del is null and id_cost = 1");
							
							while($qproduk_r = mysql_fetch_array($qproduk)){
					echo '<option value="'.$qproduk_r['nmproduk'].'">'.$qproduk_r['nmproduk'].'</option>';
							}

		echo '</select></td>
							</tr>
							<tr>
								<td align="right" width="7%">Keterangan : </td>
								<td><textarea id="keterangan" name="keterangan" cols="40" required></textarea></td>
							</tr>
							<tr>
								<td align="right" width="7%">Attachment : </td>
								<td><input id="attachment" name="attachment" type="file"></td>
							</tr>			
							<tr>
								<td align="right" width="7%">Attachment 2 : </td>
								<td><input id="attachment2" name="attachment2" type="file"></td>
							</tr>								
							<tr>
								<td colspan="3"></td>
							</tr>
							<td colspan="3" align="center">
								<button type="submit" class="button" style="text-align:center">Submit</button>						
							</td>										
						</form>				
					</table>';		
	break;		

	case "addrevisi":		
		$no_revisi = date('YmdGis');
		$no_ref = $_REQUEST['ref'];
		$peserta = $_REQUEST['peserta'];
		$produk = $_REQUEST['produk'];
		$keterangan = $_REQUEST['keterangan'];
		$attachment = $_FILES['attachment']['name'];
		$attachment_tmp = $_FILES['attachment']['tmp_name'];
		$attachment2 = $_FILES['attachment2']['name'];
		$attachment_tmp2 = $_FILES['attachment2']['tmp_name'];			
		
		$attachment_name =null;
		
		if($produk == 'SPK REGULER' or $produk == 'SPK REGULER MPP'){
			$dept_terkait = 'Underwriter';	
		}else{
			$dept_terkait = 'Life Insurance';	
		}
		
		if($attachment!= ""){				
			$attachment_info = pathinfo($attachment);		
			$attachment_extension = strtolower($attachment_info["extension"]); //image extension
			$attachment_name_only = strtolower($attachment_info["filename"]);//file name only, no extension						
			$num_file = date('YmdHis');		

			$attachment_name = $no_revisi.'-'.$attachment_name_only.'-'.$num_file.'.'.$attachment_extension;
						
			$destination_folder		= '../adnoffice/assets/documents/revisi/'.$attachment_name;
			move_uploaded_file($attachment_tmp,$destination_folder) or die( "Could not upload file!");
		}			

		if($attachment2!= ""){				
			$attachment_info2 = pathinfo($attachment2);		
			$attachment_extension2 = strtolower($attachment_info2["extension"]); //image extension
			$attachment_name_only2 = strtolower($attachment_info2["filename"]);//file name only, no extension						
			$num_file2 = date('YmdHis');		

			$attachment_name2 = $no_revisi.'-'.$attachment_name_only2.'-'.$num_file2.'.'.$attachment_extension2;
						
			$destination_folder2 = '../adnoffice/assets/documents/revisi/'.$attachment_name2;
			move_uploaded_file($attachment_tmp2,$destination_folder2) or die( "Could not upload file!");
		}					
		
		$cabang = $central['name'];			
		$qspv = mysql_fetch_array(mysql_query("select * from pengguna where nm_user = '".$q['spv']."'"));
		
		$dear = 'Dear '.$qspv['nm_lengkap'];
		$body = 'No Revisi #'.$no_revisi.' telah dibuat, tolong diperiksa dan melakukan approval.';
		$link = 'http://150.107.149.27/ajk/ajk_revisi.php?er=approve';
		$footer = 'Demikian informasi yang dapat kami sampaikan.<br><br>Regards, <br><br>'.$q['nm_user'];					
		$tomail = $qspv['email'];
		$ls_toemail = strtolower($tomail);
		$ls_toname = $qspv['nm_lengkap'];
		$ls_subject = "[App AJK] New Revisi";
		$ls_countemail = 1;
		$ls_fromname = 'Adonai Notification [no reply]';
		$ls_fromemail = $quser['UserEmail'];		
		$ls_ccname = '';
		$ls_ccmail = '';
		$li_countcc = 0;
		
		$ls_body = '<!DOCTYPE html><html> <head> <meta name="viewport" content="width=device-width"> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <title>Simple Transactional Email</title> <style type="text/css"> /* ------------------------------------- INLINED WITH https://putsmail.com/inliner ------------------------------------- */ /* ------------------------------------- RESPONSIVE AND MOBILE FRIENDLY STYLES ------------------------------------- */ @media only screen and (max-width: 620px) { table[class=body] h1 { font-size: 28px !important; margin-bottom: 10px !important; } table[class=body] p, table[class=body] ul, table[class=body] ol, table[class=body] td, table[class=body] span, table[class=body] a { font-size: 16px !important; } table[class=body] .wrapper, table[class=body] .article { padding: 10px !important; } table[class=body] .content { padding: 0 !important; } table[class=body] .container { padding: 0 !important; width: 100% !important; } table[class=body] .main { border-left-width: 0 !important; border-radius: 0 !important; border-right-width: 0 !important; } table[class=body] .btn table { width: 100% !important; } table[class=body] .btn a { width: 100% !important; } table[class=body] .img-responsive { height: auto !important; max-width: 100% !important; width: auto !important; }} /* ------------------------------------- PRESERVE THESE STYLES IN THE HEAD ------------------------------------- */ @media all { .ExternalClass { width: 100%; } .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; } .apple-link a { color: inherit !important; font-family: inherit !important; font-size: inherit !important; font-weight: inherit !important; line-height: inherit !important; text-decoration: none !important; } .btn-primary table td:hover { background-color: #34495e !important; } .btn-primary a:hover { background-color: #34495e !important; border-color: #34495e !important; } } </style></head>
			 					 <body class="" style="background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin:0;padding:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;"> <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#f6f6f6;width:100%;"> <tr> <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td> <td class="container" style="font-family:sans-serif;font-size:14px;vertical-align:top;display:block;max-width:580px;padding:10px;width:580px;Margin:0 auto !important;"> <div class="content" style="box-sizing:border-box;display:block;Margin:0 auto;max-width:580px;padding:10px;"> <!-- START CENTERED WHITE CONTAINER --><table class="main" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#fff;border-radius:3px;width:100%;"> <!-- START MAIN CONTENT AREA --> <tr> <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;"> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;"> <tr> <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
								 <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">'.$dear.'</p>
								 <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">'.$body.'</p>
			 					 <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;"> <tbody> <tr> <td align="left" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;"> <table border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;width:auto;"> <tbody> <tr>
								 <td style="font-family:sans-serif;font-size:14px;vertical-align:top;background-color:#ffffff;border-radius:5px;text-align:center;background-color:#3498db;"> <a href="'.$link.'" target="_blank" style="text-decoration:underline;background-color:#ffffff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;">View</a> </td>
								 </tr></tbody></table></td></tr></tbody></table>
								 <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">'.$footer.'</p>
			 					 </td> </tr> </table> </td> </tr> <!-- END MAIN CONTENT AREA --> </table> <!-- END CENTERED WHITE CONTAINER --> </div> </td> <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td> </tr> </table> </body></html>';						
					
		$mail	= new PHPMailer; // call the class
		$mail->IsSMTP();
		$mail->Host = SMTP_HOST; //Hostname of the mail server
		$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
		$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
		$mail->Password = SMTP_PWORD; //Password for SMTP authentication
		$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
		$mail->debug = 1;
		$mail->SMTPSecure = "ssl";
		$mail->IsHTML(true);
		$mail->SetFrom ($ls_fromemail, $ls_fromname); //From address of the mail	
		$mail->Subject = $ls_subject;
		$mail->AddAddress($ls_toemail, $ls_toname); //MAIL STAFF	
		//$mail->AddBCC("hansen@adonai.co.id");
		$mail->MsgHTML($ls_body); //Put your body of the message you can place html code here

		$send = $mail->Send(); //Send the mails			
		
				
		$query = "INSERT INTO t_revisi(no_revisi,no_ref,nm_peserta,produk,cabang,dept_terkait,keterangan,attachment,attachment2,flag_aktif,user_input,date_input) 
							VALUES('".$no_revisi."','".$no_ref."','".$peserta."','".$produk."','".$cabang."','".$dept_terkait."','".$keterangan."','".$attachment_name."','".$attachment_name2."','T','".$_SESSION['nm_user']."','".$today."')";
		
		$database->doQuery($query,"adnoffice");
		
		echo '<br><br><center>Revisi telah dibuat dengan no tiket ' . $no_revisi . '</center><meta http-equiv="refresh" content="5; url=ajk_revisi.php">';		
	break;

	case "appspk":
echo '<form method="post" action="imob.php?ob=appspkspv">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="5%">Option</th>
	  	  <th width="1%"><input type="checkbox" id="selectall"/></th>
	  	  <th width="1%">No</th>
	  	  <th width="10%">Produk</th>
		  <th width="5%">No.Permohonan</th>
		  <th width="5%">SPAK</th>
		  <th>Nama</th>
		  <th width="8%">Tgl Lahir</th>
<!--	  <th width="1%">Usia</th>
<!--	  <th width="5%">Awal Asuransi</th>-->
		  <th width="6%">Tenor (thn)</th>
<!--	  <th width="5%">Akhir Asuransi</th>-->
		  <th width="5%">Plafond</th>
<!--	  <th width="5%">Premi</th>-->
		  <th width="5%">Grace Period</th>
		  <th width="10%">Pertanyaan</th>
		  <th width="5%">Nomor Permohonan</th>
	  	  <th width="1%">Status</th>
		  <th width="10%">Cabang</th>
		  <th width="8%">Staff</th>
	  	  <th width="5%">Tgl Input</th>
	  </tr>';

/*
if ($mb['id']=="97") {
	$alldata = 'fu_ajk_spak.`status` ="Pending" OR fu_ajk_spak.`status` ="Proses"';
}else{
	$alldata = 'fu_ajk_spak.`status` ="Pending" AND user_mobile.supervisor ="'.$mb['id'].'"';
}
*/
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 50;	}	else {	$m = 0;		}
$mobUserSPK = $database->doQuery('SELECT
user_mobile.id,
user_mobile.idbank,
user_mobile.idproduk,
user_mobile.`type`,
user_mobile.level,
user_mobile.supervisor,
user_mobile.namalengkap AS unama,
user_mobile.cabang AS usercabang,
fu_ajk_spak_form_temp.id AS idspaktemp,
fu_ajk_spak_form_temp.idcost,
fu_ajk_spak_form_temp.idspk,
fu_ajk_spak_form_temp.nama,
fu_ajk_spak_form_temp.jns_kelamin,
fu_ajk_spak_form_temp.dob,
fu_ajk_spak_form_temp.tgl_periksa,
fu_ajk_spak_form_temp.plafond,
fu_ajk_spak_form_temp.tgl_asuransi,
fu_ajk_spak_form_temp.tenor,
fu_ajk_spak_form_temp.tgl_akhir_asuransi,
fu_ajk_spak_form_temp.x_premi,
fu_ajk_spak_form_temp.mpp,
fu_ajk_spak_form_temp.x_usia,
fu_ajk_spak_form_temp.cabang,
fu_ajk_spak_form_temp.nopermohonan,
DATE_FORMAT(fu_ajk_spak_form_temp.input_date, "%Y-%m-%d") AS tglInput,
fu_ajk_spak.spak,
fu_ajk_spak.ext_premi,
fu_ajk_spak.`status`,
fu_ajk_polis.nmproduk
FROM user_mobile
INNER JOIN fu_ajk_spak_form_temp ON user_mobile.id = fu_ajk_spak_form_temp.input_by AND user_mobile.idbank = fu_ajk_spak_form_temp.idcost
LEFT JOIN fu_ajk_spak ON fu_ajk_spak_form_temp.idspk = fu_ajk_spak.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
WHERE fu_ajk_spak.`status` ="Pending" AND user_mobile.supervisor ="'.$mb['id'].'" AND fu_ajk_spak.del IS NULL AND fu_ajk_spak_form_temp.del IS NULL
ORDER BY fu_ajk_spak_form_temp.nopermohonan DESC,fu_ajk_spak_form_temp.idspk ASC LIMIT ' . $m . ' , 50');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(user_mobile.id) FROM user_mobile
Inner JOIN fu_ajk_spak_form_temp ON user_mobile.id = fu_ajk_spak_form_temp.input_by AND user_mobile.idbank = fu_ajk_spak_form_temp.idcost
INNER JOIN fu_ajk_spak ON fu_ajk_spak_form_temp.idspk = fu_ajk_spak.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
WHERE fu_ajk_spak.`status` ="Pending" AND user_mobile.supervisor ="'.$mb['id'].'" AND fu_ajk_spak.del IS NULL AND fu_ajk_spak_form_temp.del IS NULL'));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($mobUserSPK_ = mysql_fetch_array($mobUserSPK)) {
$metCab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mobUserSPK_['usercabang'].'"'));
$metAskDebitur = mysql_fetch_array($database->doQuery('SELECT kode_spak, question_2  FROM fu_ajk_skkt WHERE kode_spak="'.$mobUserSPK_['spak'].'" '));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';

if ($mobUserSPK_['nopermohonan']) {
	$metCheckSplit = '<td align="center"><input type="checkbox" class="case" name="nama[]" value="'.$mobUserSPK_['idspaktemp'].'" checked></td>';
}else{
	$metCheckSplit = '<td align="center"><input type="checkbox" class="case" name="nama[]" value="'.$mobUserSPK_['idspaktemp'].'"></td>';
}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
	  <td align="center"><a href="imob.php?ob=btlspk&id='.$mobUserSPK_['idspaktemp'].'&idu='.$q['id'].'&x_spk=mobdel" onClick="if(confirm(\'Apakah anda yakin untuk menghapus data SPK ini ?\')){return true;}{return false;}"><img src="image/delete.gif"></a>&nbsp;
	  					 <a href="imob.php?ob=edtspk&id='.$mobUserSPK_['idspaktemp'].'"><img src="image/edit3.png"></a>
	  </td>
	  '.$metCheckSplit.'
	  <td align="center">'.(++$no + ($pageNow-1) * 50).'</td>
	  <td align="center">'.$mobUserSPK_['nmproduk'].'</td>
	  <td align="center"><b>'.$mobUserSPK_['nopermohonan'].'</b></td>
	  <td align="center">'.$mobUserSPK_['spak'].'</td>
	  <td><a title="preview photo" href="imob.php?ob=vphoto&idp='.$mobUserSPK_['idspaktemp'].'" target="_blank">'.$mobUserSPK_['nama'].'</a></td>
	  <td align="center">'._convertDate($mobUserSPK_['dob']).'</td>
<!--  <td align="center">'.$mobUserSPK_['x_usia'].'</td>
	  <td align="center">'._convertDate($mobUserSPK_['tgl_asuransi']).'</td>-->
	  <td align="center">'.$mobUserSPK_['tenor'].'</td>
<!--  <td align="center">'._convertDate($mobUserSPK_['tgl_akhir_asuransi']).'</td>-->
	  <td align="right">'.duit($mobUserSPK_['plafond']).'</td>
<!--  <td align="right">'.duit($mobUserSPK_['x_premi']).'</td-->
	  <td align="center">'.$mobUserSPK_['mpp'].'</td>
	  <td>'.$metAskDebitur['question_2'].'</td>
	  <td align="center">'.$mobUserSPK_['nopermohonan'].'</td>
	  <td align="center">'.$mobUserSPK_['status'].'</td>
	  <td align="center">'.$metCab['name'].'</td>
	  <td align="center">'.$mobUserSPK_['unama'].'</td>
	  <td align="center">'._convertDate($mobUserSPK_['tglInput']).'</td>
	  </tr>';
}
echo '<tr><th colspan="3" align="center"><a href="imob.php?ob=appspkspv" onClick="if(confirm(\'Apakah anda sudah yakin dengan semua data SPK ini ?\')){return true;}{return false;}"><input type="submit" name="ApproveClaim" Value="Approve"></a></th></tr>';
		echo '<tr><td colspan="16">';
		echo createPageNavigations($file = 'imob.php?ob=appspk', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 50);
		echo 'Total Data SPK: <strong>' . duit($totalRows) . '</strong></td></tr>';
		echo '</table>';
echo '</table></form>';
		;
		break;

case "btlspk":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['id'].'"'));
$metspk = mysql_fetch_array($database->doQuery('SELECT id, spak FROM fu_ajk_spak WHERE id="'.$met['idspk'].'"'));
if ($met['nopermohonan']=="") {
	$metpermohonan ='';
}else{
	$metpermohonan ='<tr><td>Nomor Pensiun</td><td>'.$met['nopermohonan'].'</td></tr>';
}
echo '<form method="post" action="ajk_val_upl.php?v=spk_del&id='.$_REQUEST['id'].'&x_spk=mobdel">
	  <table border="0" width="100%" cellpadding="1" cellspacing="1">
	  <input type="hidden" name="idspv" value="'.$q['id'].'">
	  <tr><td width="20%">Nomor SPK</td><td><b>'.$metspk['spak'].'</b></td></tr>
	  <tr><td>Nama Nasabah</td><td>'.$met['nama'].'</td></tr>
	  '.$metpermohonan.'
	  <tr><td valign="top">Alasan Pembatalan <font color="red">*</font></td><td><textarea name="pembatalan" cols="50" rows="2" required>'.$_REQUEST['pembatalan'].'</textarea></td></tr>
	  <tr><td colspan="2"><input type="submit" name="exx" Value="Batal"></td></tr>
	  </table>
	  </form>';
	;
	break;

case "edtspk":
$met = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['id'].'"'));
$metSPK = mysql_fetch_array($database->doQuery('SELECT id, id_cost, id_polis, spak FROM fu_ajk_spak WHERE id="'.$met['idspk'].'"'));
$metpolis = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$metSPK['id_polis'].'"'));
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><th width="100%" align="left">Modul Edit Data SPK</font></th><th><a href="imob.php?ob=appspk"><img src="image/Backward-64.png" width="20"></a></th></tr></table>';
if ($_REQUEST['ed_metspk']=="Simpan") {
if ($_REQUEST['metnama'] == "") {	$error_1 = '<font color="red"><blink>Nama debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metktp'] == "") {	$error_2 = '<font color="red"><blink>Nomor identitas debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metsex'] == "") {	$error_3 = '<font color="red"><blink>Silahkan pilih jenis kelamin.</font>';	}
if ($_REQUEST['metdob'] == "") {	$error_4 = '<font color="red"><blink>Tanggal lahir debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metalamat'] == "") {	$error_5 = '<font color="red"><blink>Alamat debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metpekerjaan'] == "") {	$error_6 = '<font color="red"><blink>Pekerjaan debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metplafond'] == "") {	$error_7 = '<font color="red"><blink>Nilai pinjaman debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['mettenor'] == "") {	$error_8 = '<font color="red"><blink>Tenor pinjaman debitur tidak boleh kosong.</font>';	}
if ($_REQUEST['metmpp'] == "") {	$error_9 = '<font color="red"><blink>MPP Bulan tidak boleh kosong.</font>';	}
if($_REQUEST['metmpp'] < $metpolis['mppbln_min'] ){	$error_9 = '<font color="red"><blink>MPP Bulan minimum '.$metpolis['mppbln_min'].' Bulan.</font>';}
if($_REQUEST['metmpp'] >= $metpolis['mppbln_max']){	$error_9 = '<font color="red"><blink>MPP Bulan maksimum '.$metpolis['mppbln_max'].' Bulan.</font>';}
if($metpolis['mpptype']!="Y"){	$error_9 = "";	}
if ($error_1 OR $error_2 OR $error_3 OR $error_4 OR $error_5 OR $error_6 OR $error_7 OR $error_8 OR $error_9) {

}else{
	//echo $metSPK['id_cost'].'<br />';
	//echo $metSPK['id_polis'].'<br />';
	//USIA
	$met_Date = datediff($futoday, $_REQUEST['metdob']);
	$met_Date_ = explode(",", $met_Date);
	if ($met_Date_[1] >= 6) {	$umur = $met_Date_[0] + 1;	} else {	$umur = $met_Date_[0];	}
	$umur_ = $umur;
	//echo $umur_.'<br />';
	//USIA
	if ($metpolis['mpptype']=="Y") {
		if ($_REQUEST['metmpp']=="0") {
			$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metSPK['id_cost'].'" AND id_polis="2" AND tenor="'.$_REQUEST['mettenor'] * 12 .'" AND status="baru" AND del IS NULL'));	//VALIDASI CEK TENOR DENGAN SETTING RATE POLIS
		}else{
			$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metSPK['id_cost'].'" AND id_polis="'.$metSPK['id_polis'].'" AND tenor="'.$_REQUEST['mettenor'] .'" AND '.$_REQUEST['metmpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND del IS NULL'));
		}
	}else{
		if ($metpolis['singlerate']=="Y") {
			$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metSPK['id_cost'].'" AND id_polis="'.$metSPK['id_polis'].'" AND usia="'.$umur_.'" AND tenor="'.$_REQUEST['mettenor'] .'" AND status="baru" AND del IS NULL'));		// RATE PREMI
		}else{
			$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$metSPK['id_cost'].'" AND id_polis="'.$metSPK['id_polis'].'" AND tenor="'.$_REQUEST['mettenor'] * 12 .'" AND status="baru" AND del IS NULL'));		// RATE PREMI
		}
	}
	if ($_REQUEST['metplafond'] > $metpolis['up_max']) {
		$erroredit = '<center><font color=red><blink>Data tidak bisa diedit, karena plafond melebihi batas setup produk. !</blink></font>';
	}elseif (!$cekrate['id']) {
		$erroredit = '<center><font color=red><blink>Data tidak bisa diedit, karena tidak termasuk ke dalam rate. !</blink></font>';
	}else{

	}

	if ($erroredit) {

	}else{
	$METuP = $database->doQuery('UPDATE fu_ajk_spak_form_temp SET nama="'.$_REQUEST['metnama'].'",
																  noidentitas="'.$_REQUEST['metktp'].'",
																  jns_kelamin="'.$_REQUEST['metsex'].'",
																  dob="'.$_REQUEST['metdob'].'",
																  alamat="'.$_REQUEST['metalamat'].'",
																  noidentitas="'.$_REQUEST['metktp'].'",
																  pekerjaan="'.$_REQUEST['metpekerjaan'].'",
																  plafond="'.$_REQUEST['metplafond'].'",
																  tenor="'.$_REQUEST['mettenor'].'",
																  mpp="'.$_REQUEST['metmpp'].'"
								 WHERE id="'.$_REQUEST['id'].'"');

	$mail	= new PHPMailer; // call the class
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST; //Hostname of the mail server
	$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = SMTP_PWORD; //Password for SMTP authentication
	$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
	$mail->debug = 1;
	$mail->SMTPSecure = "ssl";
	$mail->IsHTML(true);

	$mail->SetFrom ($mb['email'], $mb['namalengkap']);
	$mail->Subject = "AJKOnline - EDIT DATA SPK";
	//EMAIL PENERIMA KANTOR U/W

$mailStaff = mysql_fetch_array($database->doQuery('SELECT * FROM user_mobile WHERE idbank="'.$metSPK['id_cost'].'" AND type="Marketing" AND level="Staff" AND id="'.$met['input_by'].'"'));
$message .= '<table border="0" width="100%">
			 <tr><td colspan="2">To '.$mailStaff['namalengkap'].',<br />
				 Telah dilakukan refisi data debitur oleh '.$mb['namalengkap'].' pada tanggal '._convertDate($futoday).'.</td></tr>
			 <tr><td width="10%">No. SPK</td>
			 	 <td>Nama</td>
			 </tr>
			 <tr><td>'.$metSPK['spak'].'</td>
				<td>'.$met['nama'].'</td>
			</tr>
			</table>';

	//EMAIL STAFF INPUT
	$mail->AddAddress($mailStaff['email'], $mailStaff['namalengkap']); //To address who will receive this email
	//echo $mailStaff['email'].'<br />';
	//echo $mailStaff['namalengkap'].'<br />';
	//EMAIL STAFF INPUT

	$mailclient = $database->doQuery('SELECT * FROM fu_ajk_mail WHERE type="Produksi" AND status="Aktif"');
	while ($_mailclient = mysql_fetch_array($mailclient)) {
		$mail->AddAddress($_mailclient['emailto'], $_mailclient['emailnama']); //To address who will receive this email
	}

	//$mail->AddBCC("adn.info.notif@gmail.com");
	//$mail->AddCC("rahmad@adonaits.co.id");
	//$mail->AddCC("hansen@adonai.co.id");

	$mail->MsgHTML($message); //Put your body of the message you can place html code here
	$send = $mail->Send(); //Send the mails
	echo '<center>Data SPK telah direvisi oleh '.$mb['namalengkap'].' pada tanggal '._convertDate($futoday).'.<br /><meta http-equiv="refresh" content="3;URL=imob.php?ob=appspk"></center>';
	}
	}
}
echo ''.$erroredit.'<form name="f1" method="post" enctype="multipart/form-data" action="">
	  <input type="hidden" name="id" value="'.$_REQUEST['id'].'">
	  <table border="0" cellpadding="1" cellspacing="0" width="100%">
      <tr><td width="20%">Nomor SPK</td><td> : <b>'.$metSPK['spak'].'</b></td></tr>
      <tr><td>Nama <font color="red">*</font></td><td> : <input name="metnama" type="text" size="50" value="' . $met['nama'] . '"> '.$error_1.'</td></tr>
      <tr><td>Nomor KTP <font color="red">*</font></td><td> : <input name="metktp" type="text" size="50" value="' . $met['noidentitas'] . '"> '.$error_2.'</td></tr>
      <tr><td>Jenis Kelamin <font color="red">*</font></td><td> : <input type="radio" name="metsex" value="M"' . pilih($met["jns_kelamin"], "M") . '>Laki-Laki
				<input type="radio" name="metsex" value="F"' . pilih($met["jns_kelamin"], "F") . '>Perempuan '.$error_3.'</td></tr>
      <tr><td>Tanggal Lahir <font color="red">*</font></td><td> : <input type="text" name="metdob" id="metdob" class="tanggal" value="' . $met['dob'] . '" size="10"/> '.$error_4.'</td></tr>
      <tr><td valign="top">Alamat <font color="red">*</font></td><td> : <textarea name="metalamat" value="'.$met['alamat'].'">'.$met['alamat'].'</textarea> '.$error_5.'</td></tr>
      <tr><td>Pekerjaan <font color="red">*</font></td><td> : <input type="text" name="metpekerjaan" value="'.$met['pekerjaan'].'"> '.$error_6.'</td></tr>
      <tr><td>Jumlah Pinjaman <font color="red">*</font></td><td> : <input type="text" name="metplafond" value="'.$met['plafond'].'" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')"> '.$error_7.'</td></tr>
      <tr><td>Jangka Waktu Pinjaman <font color="red">*</font></td><td> : <input type="text" name="mettenor" value="'.$met['tenor'].'" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')" maxlength="2" size="1"> tahun '.$error_8.'</td></tr>';
		if($metpolis['mpptype']=="Y" OR $metSPK['id_polis']=="12" OR $metSPK['id_polis']=="11"){
			echo '<tr><td>Bulan MPP (Grace Period) <font color="red">*</font></td><td> : <input type="text" name="metmpp" value="'.$met['mpp'].'" onkeyup="this.value=this.value.replace(/[^0-9/,]/g,\'\')" maxlength="2" size="1"> bulan '.$error_9.'</td></tr>';
		}
	  echo '
  	  <tr><td colspan="2"><input type="hidden" name="el" value="parsingspk"><input name="ed_metspk" type="submit" value="Simpan"></td></tr>
  	  </table></form>';
	;
	break;

case "vphoto":
echo '<link rel="stylesheet" href="javascript/jscssmobile/css/lightbox.css" type="text/css" media="screen" />
	  <script src="javascript/jscssmobile/js/prototype.js" type="text/javascript"></script>
	  <script src="javascript/jscssmobile/js/scriptaculous.js?load=effects" type="text/javascript"></script>
	  <script src="javascript/jscssmobile/js/lightbox.js" type="text/javascript"></script>';
/*
if ($_REQUEST['ev']=="vwdata") {
$metVPhoto = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['vid'].'"'));
echo '<img src="ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" width="175">';
}else{
*/
	$metVPhoto = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$_REQUEST['idp'].'"'));
	$metVPhotoSPK = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$metVPhoto['idspk'].'"'));
	//echo '<a href="ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" rel="lightbox"><img src=ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" width="100" height="40" alt="" /></a>';
	if ($metVPhoto['jns_kelamin'] =="M"){	$gender = "Laki-Laki";	}else	{	$gender = "Perempuan";	}
echo '<br /><table border="0" width="100%">
	<tr><td colspan="2" align="center"><font size="2"><b>DATA DEBITUR</font></td></tr>
	<tr><td width="20%">Nomor SPK</td><td>: '.$metVPhotoSPK['spak'].'</td></tr>
	<tr><td>Nomor Identitas </td><td>: '.$metVPhoto['noidentitas'].'</td></tr>
	<tr><td>Nomor Token </td><td>: '.$metVPhoto['token'].'</td></tr>
	<tr><td>Nama Debitur </td><td>: '.$metVPhoto['nama'].'</td></tr>
	<tr><td>Jenis Kelamin </td><td>: '.$gender.'</td></tr>
	<tr><td>Tanggal Lahir </td><td>: '._convertDate($metVPhoto['dob']).'</td></tr>
	<tr><td>Alamat </td><td>: '.$metVPhoto['alamat'].'</td></tr>
	<tr><td>Pekerjaan </td><td>: '.$metVPhoto['pekerjaan'].'</td></tr>
	<tr><td>Jumlah Penjaminan </td><td>: '.duit($metVPhoto['plafond']).'</td></tr>
	<tr><td>Jangka Waktu Penjaminan </td><td>: '.$metVPhoto['tenor'].' tahun</td></tr>
	</table>
	<table border="0" width="100%">
	<tr><td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filefotodebitursatu'].'" width="175"><br>Foto Debitur</a></td>
		<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filefotoktp'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filefotoktp'].'" width="175"><br>Foto KTP</a></td>
		<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filettddebitur'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filettddebitur'].'" width="175"><br>TTD Debitur</a></td>
		<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filettdmarketing'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filettdmarketing'].'" width="175"><br>TTD Marketing</a></td>
		<td width="25%" align="center"><a href="../ajkmobilescript/'.$metVPhoto['filefotoskpensiun'].'" rel="lightbox"><img src="../ajkmobilescript/'.$metVPhoto['filefotoskpensiun'].'" width="175"><br>Foto SK</a></td>
	</tr>
	</table>';
//}
	;
	break;

case "appspkspv":
	if (!$_REQUEST['nama']) {
		echo '<center><font color=red><blink>Tidak ada data yang di pilih, silahkan ceklist data yang akan di Approve. !</blink></font><br/>
			  <a href="imob.php?ob=appspk">Kembali Ke Halaman Approve SPK</a></center>';
	}else{
		foreach($_REQUEST['nama'] as $k => $val){
			echo $val;
			$metFormSPK = mysql_fetch_array($database->doQuery('SELECT *, DATE_FORMAT(input_date,"%Y-%m-%d") AS tglinputspk FROM fu_ajk_spak_form_temp WHERE id="'.$val.'"'));

			//CEK IDSPK PADA TABLE FORM SPAK
			$cekFormSPK = mysql_fetch_array($database->doQuery('SELECT idspk FROM fu_ajk_spak_form WHERE idspk="'.$metFormSPK['idspk'].'" AND del IS NULL'));
			if (!$cekFormSPK['idspk']) {

				$newSPK = mysql_fetch_array($database->doQuery('SELECT *, DATE_FORMAT(input_date,"%Y-%m-%d") AS tglinput FROM fu_ajk_spak WHERE id="'.$metFormSPK['idspk'].'"'));
				$metproduk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_polis WHERE id="'.$newSPK['id_polis'].'"'));
				$_ratevar = 0;
				$_cekSPK = substr($newSPK['spak'],0,2);

				//cek jika mitra = mekarsari dan produk = platinum -- Hansen 14-08-2017								
				$qcekmitra = mysql_query("SELECT * FROM fu_ajk_ganti_cabang WHERE namamitra	= '".$newSPK['id_mitra']."' and id_polis = '".$newSPK['id_polis']."' and cabang = '".$metFormSPK['cabang']."'");
				if(mysql_num_rows($qcekmitra) > 0){
					$qcekmitra_ = mysql_fetch_array($qcekmitra);
					mysql_query("UPDATE fu_ajk_spak_form_temp set cabang = '".$qcekmitra_['value']."' where idspk = '".$newSPK['id']."'");
					$cabang = $qcekmitra_['value'];
				}else{
					$cabang = $metFormSPK['cabang'];
				}

				if ($_cekSPK =="MP" OR $_cekSPK =="PL" OR $_cekSPK =="AB" OR $_cekSPK =="HK" OR $_cekSPK =="PA") {	//catatan : PL=Platinum, AB=Abri, HK=Hakashima, PA=Pegawai Aktif
					$tenorConvertBln = $metFormSPK['tenor'] * 12;
					$tglakadsistem = explode(" ", $metFormSPK['input_date']);
					$tglakadsistem_ = $metFormSPK['tglinputspk']; //tgl akad

					$met_Date = datediff($tglakadsistem_, $metFormSPK['dob']);
					$met_Date_ = explode(",", $met_Date);

					if ($met_Date_[1] >= 6) {	
						$umur = $met_Date_[0] + 1;	} else {	$umur = $met_Date_[0];	
					}
					$umur_ = $umur;				//USIA

					$cekSKKT = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_skkt WHERE kode_spak="'.$newSPK['spak'].'"'));
					$posisi=strpos($cekSKKT['question_2'],"P");

					if ($posisi !== FALSE){
						$metUpdSPK = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Preapproval", update_by="'.$mb['id'].'", update_date="'.$futgl.'" WHERE id="'.$metFormSPK['idspk'].'"'));
					}else {
						$cekMEdical = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_medical WHERE id_polis="'.$newSPK['id_polis'].'" AND "'.$umur_.'" BETWEEN age_from AND age_to AND "'.$metFormSPK['plafond'].'" BETWEEN si_from AND si_to'));

						if ($cekMEdical['type_medical']=="Medical A") {
							$metUpdSPK = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Preapproval", update_by="'.$mb['id'].'", update_date="'.$futgl.'" WHERE id="'.$metFormSPK['idspk'].'"'));
						}else{
							$metUpdSPK = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Aktif", update_by="'.$mb['id'].'", update_date="'.$futgl.'" WHERE id="'.$metFormSPK['idspk'].'"'));
						}
					}

					if ($metproduk['mpptype']=="Y") {
						//CEK MPP TENOR BARU
						$cekMPPbaru = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form_temp.id,
																			   fu_ajk_spak_form_temp.tenor,
																			   fu_ajk_spak_form_temp.mpp,
																			   fu_ajk_spak_form_temp.nopermohonan,
																			   fu_ajk_spak.spak,
																			   fu_ajk_spak.`status`,
																			   fu_ajk_spak.danatalangan
																		FROM fu_ajk_spak_form_temp
																		INNER JOIN fu_ajk_spak ON fu_ajk_spak_form_temp.idspk = fu_ajk_spak.id
																		WHERE fu_ajk_spak_form_temp.id = '.$metFormSPK['id'].' AND
																			  fu_ajk_spak.status ="Aktif"'));
						
						if($cekMPPbaru['danatalangan']==1){
							if ($cekMPPbaru['mpp'] <= 12) {
								$tenortalangan = 1;
							}elseif($cekMPPbaru['mpp'] >= 25){
								$tenortalangan = 3;
							}else{
								$tenortalangan = 2;
							}
							
							$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$newSPK['id_cost'].'" AND id_polis="'.$newSPK['id_polis'].'" AND tenor="'.$tenortalangan .'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$newSPK['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));
							$met_tgl_akhir = date('Y-m-d', strtotime('+' .$cekMPPbaru['tenor']. ' month', strtotime($tglakadsistem_))); //tanggal akhir asuransi																
						}else{
							$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$newSPK['id_cost'].'" AND id_polis="'.$newSPK['id_polis'].'" AND tenor="'.$metFormSPK['tenor'] .'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$newSPK['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));
							$met_tgl_akhir = date('Y-m-d', strtotime('+' .$cekMPPbaru['tenor']. ' year', strtotime($tglakadsistem_))); //tanggal akhir asuransi								
						}						
						//CEK MPP TENOR BARU						
					}else{
						$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$newSPK['id_cost'].'" AND id_polis="'.$newSPK['id_polis'].'" AND tenor="'.$tenorConvertBln.'" AND status="baru" AND "'.$newSPK['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));		// RATE PREMI
						$met_tgl_akhir = date('Y-m-d', strtotime('+' .$metFormSPK['tenor'] . ' year', strtotime($tglakadsistem_))); //tanggal akhir asuransi						
					}
				}else{ 
					$tglakadsistem = explode(" ", $metFormSPK['input_date']);
					$tglakadsistem_ = $metFormSPK['tglinputspk'];																		//tgl akad

					$met_Date = datediff($tglakadsistem_, $metFormSPK['dob']);
					$met_Date_ = explode(",", $met_Date);
					if ($met_Date_[1] >= 6) {	$umur = $met_Date_[0] + 1;	} else {	$umur = $met_Date_[0];	}
					$umur_ = $umur;				//USIA
					
					if($_cekSPK =="MS"){
						$metUpdSPK = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Aktif", update_by="'.$mb['id'].'", update_date="'.$futgl.'" WHERE id="'.$metFormSPK['idspk'].'"'));	
					}else{
						$metUpdSPK = mysql_fetch_array($database->doQuery('UPDATE fu_ajk_spak SET status="Proses", update_by="'.$mb['id'].'", update_date="'.$futgl.'" WHERE id="'.$metFormSPK['idspk'].'"'));
					}
					
					if ($metproduk['mpptype']=="Y") {
						//CEK MPP TENOR BARU
						$cekMPPbaru = mysql_fetch_array($database->doQuery('SELECT fu_ajk_spak_form_temp.id,
																			   fu_ajk_spak_form_temp.tenor,
																			   fu_ajk_spak_form_temp.mpp,
																			   fu_ajk_spak_form_temp.nopermohonan,
																			   fu_ajk_spak.spak,
																			   fu_ajk_spak.`status`,
																			   fu_ajk_spak.danatalangan
																		FROM fu_ajk_spak_form_temp
																		INNER JOIN fu_ajk_spak ON fu_ajk_spak_form_temp.idspk = fu_ajk_spak.id
																		WHERE fu_ajk_spak_form_temp.id = '.$metFormSPK['id'].' AND
																			  fu_ajk_spak.status ="Proses"'));
						if ($cekMPPbaru['danatalangan']==1) {
								if ($cekMPPbaru['mpp'] <= 12) {
									$tenortalangan = 1;
								}elseif($cekMPPbaru['mpp'] >= 25){
									$tenortalangan = 3;
								}else{
									$tenortalangan = 2;
								}
							$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$newSPK['id_cost'].'" AND id_polis="'.$newSPK['id_polis'].'" AND tenor="'.$tenortalangan .'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$newSPK['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));
							$met_tgl_akhir = date('Y-m-d', strtotime('+' .$cekMPPbaru['mpp'] . ' month', strtotime($tglakadsistem_))); //tanggal akhir asuransi								
						}else{
							$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$newSPK['id_cost'].'" AND id_polis="'.$newSPK['id_polis'].'" AND tenor="'.$metFormSPK['tenor'] .'" AND '.$metFormSPK['mpp'].' BETWEEN mpp_s AND mpp_e AND status="baru" AND "'.$newSPK['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));
							$met_tgl_akhir = date('Y-m-d', strtotime('+' .$metFormSPK['tenor'] . ' year', strtotime($tglakadsistem_))); //tanggal akhir asuransi							
						}
						//CEK MPP TENOR BARU
					}else{
						$tglakadsistem = explode(" ", $metFormSPK['input_date']);
						$tglakadsistem_ = $metFormSPK['tglinputspk'];																		//tgl akad
						$met_tgl_akhir = date('Y-m-d', strtotime('+' .$metFormSPK['tenor'] . ' year', strtotime($tglakadsistem_))); //tanggal akhir asuransi

						$met_Date = datediff($tglakadsistem_, $metFormSPK['dob']);
						$met_Date_ = explode(",", $met_Date);
						if ($met_Date_[1] >= 6) {	$umur = $met_Date_[0] + 1;	} else {	$umur = $met_Date_[0];	}	$umur_ = $umur;				//USIA

						$cekrate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$newSPK['id_cost'].'" AND id_polis="'.$newSPK['id_polis'].'" AND tenor="'.$metFormSPK['tenor'].'" AND usia="'.$umur_.'" AND status="baru" AND "'.$newSPK['tglinput'].'" BETWEEN eff_from AND eff_to AND del IS NULL'));		// RATE PREMI
					}
					
				}

				$_ratevar= $cekrate['rate'];

				$mpppremi = $metFormSPK['plafond'] * $_ratevar / 1000;		//PREMI

				if ($mpppremi < $metproduk['min_premium']) {
					$premi_x = $mpppremi;
				} else {
					$premi_x = $mpppremi;
				}

				$metForm = $database->doQuery('INSERT INTO fu_ajk_spak_form SET idcost="'.$metFormSPK['idcost'].'",
																			dokter="'.$metFormSPK['dokter'].'",
																			idspk="'.$metFormSPK['idspk'].'",
																			noidentitas="'.$metFormSPK['noidentitas'].'",
																			nama="'.$metFormSPK['nama'].'",
																			jns_kelamin="'.$metFormSPK['jns_kelamin'].'",
																			dob="'.$metFormSPK['dob'].'",
																			alamat="'.$metFormSPK['alamat'].'",
																			pekerjaan="'.$metFormSPK['pekerjaan'].'",
																			pertanyaan1="'.$metFormSPK['pertanyaan1'].'",
																			ket1="'.$metFormSPK['ket1'].'",
																			pertanyaan2="'.$metFormSPK['pertanyaan2'].'",
																			ket2="'.$metFormSPK['ket2'].'",
																			pertanyaan3="'.$metFormSPK['pertanyaan3'].'",
																			ket3="'.$metFormSPK['ket3'].'",
																			pertanyaan4="'.$metFormSPK['pertanyaan4'].'",
																			ket4="'.$metFormSPK['ket4'].'",
																			pertanyaan5="'.$metFormSPK['pertanyaan5'].'",
																			ket5="'.$metFormSPK['ket5'].'",
																			pertanyaan6="'.$metFormSPK['pertanyaan6'].'",
																			ket6="'.$metFormSPK['ket6'].'",
																			tgl_periksa="'.$metFormSPK['tgl_periksa'].'",
																			plafond="'.$metFormSPK['plafond'].'",
																			tgl_asuransi="'.$tglakadsistem_.'",
																			medical="'.$metFormSPK['medical'].'",
																			tenor="'.$metFormSPK['tenor'].'",
																			tgl_akhir_asuransi="'.$met_tgl_akhir.'",
																			tinggibadan="'.$metFormSPK['tinggibadan'].'",
																			beratbadan="'.$metFormSPK['beratbadan'].'",
																			tekanandarah="'.$metFormSPK['tekanandarah'].'",
																			nadi="'.$metFormSPK['nadi'].'",
																			pernafasan="'.$metFormSPK['pernafasan'].'",
																			mpp="'.$metFormSPK['mpp'].'",
																			guladarah="'.$metFormSPK['guladarah'].'",
																			ratebank="'.$_ratevar.'",
																			x_premi="'.$premi_x.'",
																			x_usia="'.$umur_.'",
																			cabang="'.$cabang.'",
																			nopermohonan="'.$metFormSPK['nopermohonan'].'",
																			nolink="'.$metFormSPK['nolink'].'",
																			vermarketing="'.$metFormSPK['vermarketing'].'",
																			dokter_pemeriksa_klinik="'.$metFormSPK['dokter_pemeriksa_klinik'].'",
																			filefotodebitursatu="'.$metFormSPK['filefotodebitursatu'].'",
																			filefotodebiturdua="'.$metFormSPK['filefotodebiturdua'].'",
																			filefotoktp="'.$metFormSPK['filefotoktp'].'",
																			filettddebitur="'.$metFormSPK['filettddebitur'].'",
																			filettdmarketing="'.$metFormSPK['filettdmarketing'].'",
																			filefotoskpensiun="'.$metFormSPK['filefotoskpensiun'].'",
																			input_by="'.$metFormSPK['input_by'].'",
																			input_date="'.$metFormSPK['input_date'].'",
																			update_by="'.$mb['id'].'",
																			update_date="'.$futgl.'"');
			}
		}
		$message .= '<table border="0" width="100%">
					 <tr><td width="1%">No.</td>
					 	 <td width="10%">No. SPK</td>
					 	 <td>Nama</td>
					 </tr>';
		foreach($_REQUEST['nama'] as $k_mail => $val_mail){
			$metFormSPK_mail = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak_form_temp WHERE id="'.$val_mail.'" AND del IS NULL'));
			$metFormSPK_mailspk = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_spak WHERE id="'.$metFormSPK_mail['idspk'].'" AND del IS NULL'));
			$metSPKnmMobile_mail = mysql_fetch_array($database->doQuery('SELECT id, nama,cabang FROM user_mobile WHERE id="'.$metFormSPK_mail['input_by'].'"'));
			$message .='<tr><td align="center">'.++$no.'</td>
							<td>'.$metFormSPK_mailspk['spak'].'</td>
							<td>'.$metFormSPK_mail['nama'].'</td>
						</tr>';
		}
		$message .='</table>';
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host = SMTP_HOST; //Hostname of the mail server
			$mail->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
			$mail->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
			$mail->Password = SMTP_PWORD; //Password for SMTP authentication
			$mail->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
			$mail->debug = 1;
			$mail->SMTPSecure = "ssl";
			$mail->SetFrom ($mb['email'], $mb['namalengkap']);
			$mail->Subject = "AJKOnline - APPROVE PESERTA BARU SPK AJK ONLINE";
			//EMAIL PENERIMA KANTOR U/W

			//EMAIL DOKTER
			$mailDokter = $database->doQuery('SELECT * FROM user_mobile WHERE idbank="'.$metFormSPK_mail['idcost'].'" AND type="Dokter" AND cabang="'.$metSPKnmMobile_mail['cabang'].'"');
		while ($mailDokter_ = mysql_fetch_array($mailDokter)) {
			$mail->AddAddress($mailDokter_['email'], $mailDokter_['nama']); //To address who will receive this email
		}
		//EMAIL DOKTER

		//EMAIL STAFF INPUT
		$mailStaff = $database->doQuery('SELECT * FROM user_mobile WHERE idbank="'.$metFormSPK_mail['idcost'].'" AND type="Marketing" AND level="Staff" AND supervisor="'.$mb['id'].'"');
		while ($mailStaff_ = mysql_fetch_array($mailStaff)) {
			$mail->AddAddress($mailStaff_['email'], $mailStaff_['nama']); //To address who will receive this email
		}
			//EMAIL STAFF INPUT
			//$mail->AddCC("kepodank@gmail.co.id");
			//$mail->AddCC("hansen@adonai.co.id");
			$mail->MsgHTML($message); //Put your body of the message you can place html code here
			$send = $mail->Send(); //Send the mails

			/* cek platinum */
			$cekplatinum = mysql_query("select *,plafond/1000*ratebank_sys
																					from(
																					select fu_ajk_spak.id,
																									spak,
																									fu_ajk_spak.`status`,
																									ratebank,
																									tenor,
																									plafond,
																									(select round(rate,0) from fu_ajk_ratepremi where id_polis = 16 and status = 'baru' and fu_ajk_ratepremi.tenor = (fu_ajk_spak_form.tenor *12))as ratebank_sys,fu_ajk_spak.input_date
																					from fu_ajk_spak
																							 inner join fu_ajk_spak_form
																							 on fu_ajk_spak_form.idspk = fu_ajk_spak.id
																					where fu_ajk_spak.del is null and
																								fu_ajk_spak_form.del is null and
																								id_polis = 16
																					)as temp
																					where ratebank != ratebank_sys");
			if(mysql_num_rows($cekplatinum)>0){
				$mail2 = new PHPMailer;
				$mail2->IsSMTP();
				$mail2->Host = SMTP_HOST; //Hostname of the mail server
				$mail2->Port = SMTP_PORT; //Port of the SMTP like to be 25, 80, 465 or 587
				$mail2->Username = SMTP_UNAME; //Username for SMTP authentication any valid email created in your domain
				$mail2->Password = SMTP_PWORD; //Password for SMTP authentication
				$mail2->AddReplyTo = SMTP_REPLYMAIL; //reply-to address
				$mail2->debug = 1;
				$mail2->SMTPSecure = "ssl";
				$mail2->SetFrom ($mb['email'], $mb['namalengkap']);
				$mail2->Subject = "AJKOnline - Cek Data";
				$mail2->AddAddress('hansen@adonai.co.id');
				$mail2->MsgHTML("Cek Data Platinum");
				$send = $mail2->Send();
			}

			echo '<center>Approve data SPK telah berhasil.<br /> <meta http-equiv="refresh" content="2; url=imob.php?ob=appspk"></center>';
	};
break;

case "listspk":
echo '<table border="0" width="100%" cellpadding="3" cellspacing="1" style="border: solid 1px #DEDEDE">
	<form method="post" action="" name="frmcust" onSubmit="return valcust()">
	<tr><td width="10%">Status Data</td>
		<td><select name="statusdata"><option value="">Pilih Status</option>
									 <option value="Realisasi"'._selected($_REQUEST['statusdata'], "Realisasi").'>Realisasi</option>
									 <option value="Aktif"'._selected($_REQUEST['statusdata'], "Aktif").'>Aktif</option>
									 <option value="Preapproval"'._selected($_REQUEST['statusdata'], "Preapproval").'>Preapproval</option>
									 <option value="Approve"'._selected($_REQUEST['statusdata'], "Approve").'>Approve</option>
									 <option value="Batal"'._selected($_REQUEST['statusdata'], "Batal").'>Batal</option>
									 <option value="Proses"'._selected($_REQUEST['statusdata'], "Proses").'>Proses</option>
									 <option value="Tolak"'._selected($_REQUEST['statusdata'], "Tolak").'>Tolak</option>
		</select></td>
	</tr>
	<tr><td>Nomor SPK</td>
		<td><input type="text" name="spaknya" value="' . $_REQUEST['spaknya'] . '"/></td>
	</tr>
	<tr><td>Nama Debitur</td>
		<td><input type="text" name="namanya" value="' . $_REQUEST['namanya'] . '"/></td>
	</tr>
	<tr><td><input type="hidden" name="mametdn" value="createme" class="button"><input type="submit" name="button" value="Cari" class="button"></td></tr>
	</form>
	</table>';
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1" bgcolor="#bde0e6">
	  <tr><th width="1%">No</th>
	  	  <th>Produk</th>
	  	  <th width="1%">Status</th>
		  <th width="1%">Mitra</th>
		  <th width="1%">SPAK</th>
		  <th>Nama</th>
		  <th width="5%">Tgl Lahir</th>
		  <th width="1%">Usia</th>
		  <th>Alamat</th>
		  <th width="5%">Awal Asuransi</th>
		  <th width="1%">Tenor (thn)</th>
		  <th width="5%">Akhir Asuransi</th>
		  <th width="1%">Plafond</th>
		  <th width="1%">Premi</th>
		  <th width="1%">EM(%)</th>
		  <th width="1%">Total Premi</th>
		  <th width="1%">Grace Period</th>
		  <th width="1%">Cabang</th>
		  <th width="5%">Staff</th>
	  	  <th width="5%">Tgl Input</th>
	  	  <th width="5%">Tgl Approve</th>
	  </tr>';
if ($_REQUEST['spaknya'])		{	$satu = 'AND fu_ajk_spak.spak LIKE "%' . $_REQUEST['spaknya'] . '%"';	}
if ($_REQUEST['statusdata'])	{	$dua = 'AND fu_ajk_spak.status LIKE "%' . $_REQUEST['statusdata'] . '%"';	}
if ($_REQUEST['namanya'])		{	$tiga = 'AND fu_ajk_spak_form.nama LIKE "%' . $_REQUEST['namanya'] . '%"';	}

if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
//echo $mb['namamitra'];
if ($mb['supervisor']=="0") {
	$ImobCabang = 'fu_ajk_spak.update_by = "'.$mb['id'].'" AND';
}else{
	$ImobCabang = 'fu_ajk_spak_form.cabang = "'.$mb['cabang'].'" AND';
}

$mobUserSPK = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.id_mitra,
fu_ajk_spak.ext_premi,
fu_ajk_spak.spak,
fu_ajk_spak.`status`,
fu_ajk_spak.input_by AS inputstaff,
DATE_FORMAT(fu_ajk_spak.input_date,"%Y-%m-%d") AS tglinputstaff,
fu_ajk_spak.update_by AS approvespv,
DATE_FORMAT(fu_ajk_spak.update_date,"%Y-%m-%d") AS tglapprovespv,
fu_ajk_spak_form.noidentitas,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.alamat,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.tgl_akhir_asuransi,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.x_usia,
fu_ajk_spak_form.ratebank,
fu_ajk_spak_form.x_premi,
fu_ajk_spak_form.mpp,
fu_ajk_spak_form.nopermohonan,
fu_ajk_spak_form.cabang,
user_mobile.namalengkap,
user_mobile.namamitra,
user_mobile.cabang AS usercabang,
fu_ajk_polis.nmproduk AS produk,
fu_ajk_polis.min_premium,
fu_ajk_grupproduk.nmproduk
FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN user_mobile ON fu_ajk_spak.input_by = user_mobile.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
WHERE '.$ImobCabang.' fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.' '.$tiga.'
ORDER BY fu_ajk_spak.update_date DESC LIMIT ' . $m . ' , 25');

/*WHERE fu_ajk_spak.update_by = "'.$mb['id'].'" AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.' '.$tiga.'*/
/*WHERE fu_ajk_spak_form.cabang = "'.$mb['cabang'].'" AND fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.' '.$tiga.'*/

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak.id) FROM fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
INNER JOIN user_mobile ON fu_ajk_spak.input_by = user_mobile.id
INNER JOIN fu_ajk_polis ON fu_ajk_spak.id_polis = fu_ajk_polis.id
LEFT JOIN fu_ajk_grupproduk ON fu_ajk_spak.id_mitra = fu_ajk_grupproduk.id
WHERE '.$ImobCabang.' fu_ajk_spak_form.del IS NULL '.$satu.' '.$dua.' '.$tiga.'
'));
/*WHERE fu_ajk_spak.id!="" AND fu_ajk_spak.update_by = "'.$mb['id'].'" '.$satu.' '.$dua.' '.$tiga.'*/

$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;

while ($mobUserSPK_ = mysql_fetch_array($mobUserSPK)) {
$metCab = mysql_fetch_array($database->doQuery('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$mobUserSPK_['usercabang'].'"'));
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';

//CEK DATA SPK BARU
$kucing = substr($mobUserSPK_['spak'],0, 2);
if ($kucing == "MP") {
	$tglAkad_ =_convertDate($mobUserSPK_['tglinputstaff']);

	if ($mobUserSPK_['nopermohonan']!="") {
				$dana_talangan = mysql_fetch_array(mysql_query("SELECT IFNULL(CASE WHEN (S.id_polis in (select id from fu_ajk_polis where mpptype = 'Y')) AND
																											F.idspk = (SELECT idspk FROM fu_ajk_spak_form WHERE nopermohonan = F.nopermohonan ORDER BY idspk DESC LIMIT 1)
																						THEN 'mpp' END,'')AS datampp
																		FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S,fu_ajk_polis AS P
																		WHERE S.spak='".$mobUserSPK_['spak']."' AND F.idspk=S.id
																		AND P.id = S.id_polis"));

				if($dana_talangan['datampp']=="mpp"){
					$tenor = ceil($mobUserSPK_['tenor'] / 12);
				}else{
					$tenor = $mobUserSPK_['tenor'];
				}
	}else{
		$tenor = $mobUserSPK_['tenor'];
	}


	$tglakhir = date('Y-m-d', strtotime('+' . $tenor . ' year', strtotime($mobUserSPK_['tglinputstaff']))); //tanggal akhir asuransi
	$tglakhir_ = _convertDate($tglakhir);

	$mets = datediff($mobUserSPK_['tglinputstaff'], $mobUserSPK_['dob']);
	$metTgl = explode(",",$mets);
	if ($metTgl[1] >= 6 ) {	$umur = $metTgl[0] + 1;	}else{	$umur = $metTgl[0];	}
	$_metUsia = $umur;
}else{
	$tglAkad_ =_convertDate($mobUserSPK_['tgl_asuransi']);
	$tglakhir_ = _convertDate($mobUserSPK_['tgl_akhir_asuransi']);
	$_metUsia = $mobUserSPK_['x_usia'];
}
//CEK DATA SPK BARU

if ($mobUserSPK_['status']=="Aktif" OR $mobUserSPK_['status']=="Tolak" OR $mobUserSPK_['status']=="Realisasi" OR $mobUserSPK_['status']=="Preapproval") {
$metViewSPK = '<a href="aajk_report.php?er=_spk&ids='.$mobUserSPK_['id'].'&mod=adn" target="_blank">'.$mobUserSPK_['nama'].'</a>';
}else{
$metViewSPK = $mobUserSPK_['nama'];
$metXpremi = 0;
$metXpremiMinPremi = 0;
}

//27092016
if ($mobUserSPK_['status']=="Aktif") {
	$premi_x_ = $mobUserSPK_['x_premi'];
}else{
	if ($mobUserSPK_['ratebank']=="") {
		$premi_x_ = $mobUserSPK_['x_premi'];
	}else{
		$premi_x_ = $mobUserSPK_['plafond'] * $mobUserSPK_['ratebank'] / 1000;
	}
}
//27092016

//$metXpremiMinPremi =$mobUserSPK_['x_premi'] * $mobUserSPK_['ext_premi'] / 100;	27092016
$metXpremiMinPremi =$premi_x_ * $mobUserSPK_['ext_premi'] / 100;
//$metXpreminya = $mobUserSPK_['x_premi'] + $metXpremiMinPremi;						27092016
$metXpreminya = $premi_x_ + $metXpremiMinPremi;
if ($metXpreminya <= $mobUserSPK_['min_premium']) {
	$metXpremiMinPremi_ = $mobUserSPK_['min_premium'];
}else{
	$metXpremiMinPremi_ = $metXpreminya;
}
//$met_EM = ROUND($mobUserSPK_['x_premi'] * $mobUserSPK_['ext_premi'] / 100) + $mobUserSPK_['x_premi'];
/*
if ($mobUserSPK_['x_usia']<=0) {

}else{
$xRate = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_ratepremi WHERE id_cost="'.$mobUserSPK_['id_cost'].'" AND id_polis="'.$mobUserSPK_['id_polis'].'" AND usia="'.$_metUsia.'" AND tenor="'.$mobUserSPK_['tenor'].'"'));
$metXpremi = $mobUserSPK_['plafond'] * $xRate['rate'] / 1000;
$metXpremiEM = $metXpremi * $mobUserSPK_['ext_premi'] / 100;
$metXpremi_ = ROUND($metXpremi + $metXpremiEM);
if ($metXpremi_ <= $mobUserSPK_['min_premium']) {
	$metXpremiMinPremi = $mobUserSPK_['min_premium'];
}else{
	$metXpremiMinPremi = $mobUserSPK_['x_premi'];
}
}
*/
if ($mobUserSPK_['id_mitra']=="") {	$metGroup = "Bukopin";	}else{	$metGroup = $mobUserSPK_['nmproduk'];	}

			if ($mobUserSPK_['nopermohonan']!="") {
				$dana_talangan = mysql_fetch_array(mysql_query("SELECT IFNULL(CASE WHEN (S.id_polis in (select id from fu_ajk_polis where mpptype = 'Y')) AND
																											F.idspk = (SELECT idspk FROM fu_ajk_spak_form WHERE nopermohonan = F.nopermohonan ORDER BY idspk DESC LIMIT 1)
																						THEN 'mpp' END,'')AS datampp
																		FROM fu_ajk_spak_form AS F, fu_ajk_spak AS S,fu_ajk_polis AS P
																		WHERE S.spak='".$mobUserSPK_['spak']."' AND F.idspk=S.id
																		AND P.id = S.id_polis"));

				if($dana_talangan['datampp']=="mpp"){
					if($mobUserSPK_['tenor'] <= 12){
						$tenor = 1;
					}elseif($mobUserSPK_['tenor'] >= 25){
						$tenor = 3;
					}else{
						$tenor = 2;
					}
				}else{
					$tenor = $mobUserSPK_['tenor'];
				}
			}else{
				$tenor = $mobUserSPK_['tenor'];
			}

echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
		  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
		  <td align="center">'.strtoupper($mobUserSPK_['produk']).'</td>
		  <td align="center">'.strtoupper($mobUserSPK_['status']).'</td>
		  <td align="center">'.$metGroup.'</td>
		  <td align="center">'.$mobUserSPK_['spak'].'</td>
		  <td>'.$metViewSPK.'</td>
		  <td align="center">'._convertDate($mobUserSPK_['dob']).'</td>
		  <td align="center">'.$_metUsia.'</td>
		  <td>'.$mobUserSPK_['alamat'].'</td>
		  <td align="center">'.$tglAkad_.'</td>
		  <td align="center">'.$tenor.'</td>
		  <td align="center">'.$tglakhir_.'</td>
		  <td align="right">'.duit($mobUserSPK_['plafond']).'</td>
		  <td align="right">'.duit($premi_x_).'</td>
		  <td align="center">'.duit($mobUserSPK_['ext_premi']).'</td>
		  <td align="right">'.duit($metXpremiMinPremi_).'</td>
		  <td align="center">'.$mobUserSPK_['mpp'].'</td>
		  <td align="center">'.$metCab['name'].'</td>
		  <td align="center">'.$mobUserSPK_['namalengkap'].'</td>
		  <td align="center">'._convertDate($mobUserSPK_['tglinputstaff']).'</td>
		  <td align="center">'._convertDate($mobUserSPK_['tglapprovespv']).'</td>
		  </tr>';
}
	echo '<tr><td colspan="19">';
	echo createPageNavigations($file = 'imob.php?ob=listspk&spaknya='.$_REQUEST['spaknya'].'&statusdata='.$_REQUEST['statusdata'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo 'Total Data SPK: <strong>' . duit($totalRows) . '</strong></td></tr>';
	echo '</table>';
	;
	break;

case "rSPK":
echo '<table border="0" cellpadding="5" cellspacing="0" width="100%"><tr><th width="95%" align="left">Modul Laporan SPK</font></th></tr></table>';
$metcost = mysql_fetch_array($database->doQuery('SELECT fu_ajk_costumer.`name`, fu_ajk_polis.nmproduk
													 FROM user_mobile
													 INNER JOIN fu_ajk_costumer ON user_mobile.idbank = fu_ajk_costumer.id
													 INNER JOIN fu_ajk_polis ON user_mobile.idproduk = fu_ajk_polis.id
													 WHERE user_mobile.id="'.$mb['idbank'].'"'));
echo '<form method="post" action="">
		  <input type="hidden" name="id_cost" value="'.$metcost['id'].'">
		  <table border="0" cellpadding="1" cellspacing="0" width="100%">
	      <tr><td width="40%" align="right">Nama Perusahaan</td><td> : '.$metcost['name'].'</td></tr>
	      <tr><td width="40%" align="right">Nama Produk</td><td> : '.$metcost['nmproduk'].'</td></tr>
		  <tr><td align="right">Tanggal Input SPK <font color="red">*</font></td>
		 	  <td> :';print initCalendar();	print calendarBox('tglcheck1', 'triger1', $_REQUEST['tglcheck1']);	echo 's/d';
	print initCalendar();	print calendarBox('tglcheck2', 'triger2', $_REQUEST['tglcheck2']);
echo '</td></tr>
		<tr><td width="40%" align="right">Status</td><td> : <select name="statusdata"><option value="">Pilih Status</option>
									 <option value="Aktif"'._selected($_REQUEST['statusdata'], "Aktif").'>Aktif</option>
									 <option value="Approve"'._selected($_REQUEST['statusdata'], "Approve").'>Approve</option>
									 <option value="Batal"'._selected($_REQUEST['statusdata'], "Batal").'>Batal</option>
									 <option value="Proses"'._selected($_REQUEST['statusdata'], "Proses").'>Proses</option>
									 <option value="Tolak"'._selected($_REQUEST['statusdata'], "Tolak").'>Tolak</option>
									 </select>
		</td></tr>
		<tr><td align="center"colspan="2"><input type="hidden" name="re" value="rListSPK"><input type="submit" name="ere" value="Cari"></td></tr>
		</table>
		</form>';
if ($_REQUEST['re']=="rListSPK") {
if ($_REQUEST['tglcheck1']=="" OR $_REQUEST['tglcheck2']=="") 	{	$error1 = '<div align="center"><font color="red"><blink>Tanggal input SPK tidak boleh kosong<br /></div></font></blink>';	}
if ($error1) {	echo $error1;	}
else{
echo '<table border="0" width="100%" cellpadding="1" cellspacing="1"  bgcolor="#E2E2E2">
			<tr><td bgcolor="#FFF"colspan="23"><a href="aajk_report.php?er=eL_ListSPK&idB='.$mb['idbank'].'&ispv='.$mb['id'].'&tgl1='.$_REQUEST['tglcheck1'].'&tgl2='.$_REQUEST['tglcheck2'].'&st='.$_REQUEST['statusdata'].'"><img src="image/excel.png" width="25" border="0"><br />Excel</a></td></tr>
			<th width="1%">No</th>
			<th>Nama Debitur</th>
			<th>Cabang</th>
			<th>No. SPK</th>
			<th>Tgl Input SPK</th>
			<th>Tgl Approve SPK</th>
			<th>Tgl Lahir</th>
			<th>Usia</th>
			<th>Plafond</th>
			<th>EM(%)</th>
			<th>Tenor</th>
			<th>TB</th>
			<th>BB</th>
			<th>SISTOLIK</th>
			<th>DIASTOLIK</th>
			<th>NADI</th>
			<th>PERNAFASAN</th>
			<th>GULA DARAH</th>
			<th>MEROKOK</th>
			<th>JML ROKOK</th>
			<th>CATATAN SKS</th>
			<th>STATUS</th>
			</tr>';
if ($_REQUEST['tglcheck1'])		{
	if ($_REQUEST['tglcheck1'] == $_REQUEST['tglcheck2']) {
		$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
		$newdate = date ( 'Y-m-d' , $PenambahanTgl );
		$satu = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate.'" ';
	}else{
		$PenambahanTgl = strtotime ( '+1 day' , strtotime ( $_REQUEST['tglcheck2'] ) ) ;;
		$newdate2 = date ( 'Y-m-d' , $PenambahanTgl );
		$satu = 'AND fu_ajk_spak.input_date BETWEEN "'.$_REQUEST['tglcheck1'].'" AND "'.$newdate2.'" ';
	}
	}

if ($_REQUEST['statusdata'])	{	$dua = 'AND fu_ajk_spak.status = "'.$_REQUEST['statusdata'].'"';	}
if ($_REQUEST['x']) {	$m = ($_REQUEST['x']-1) * 25;	}	else {	$m = 0;		}
$met = $database->doQuery('SELECT
fu_ajk_spak.id,
fu_ajk_spak.id_cost,
fu_ajk_spak.id_polis,
fu_ajk_spak.spak,
DATE_FORMAT(fu_ajk_spak.input_date, "%Y-%m-%d") AS tglInput,
DATE_FORMAT(fu_ajk_spak.update_date, "%Y-%m-%d") AS tglApproveSPV,
fu_ajk_spak.ext_premi,
fu_ajk_spak.`status`,
fu_ajk_spak.keterangan,
fu_ajk_spak_form.nama,
fu_ajk_spak_form.cabang,
fu_ajk_spak_form.tgl_periksa,
fu_ajk_spak_form.tgl_asuransi,
fu_ajk_spak_form.tgl_akhir_asuransi,
fu_ajk_spak_form.dob,
fu_ajk_spak_form.x_usia,
fu_ajk_spak_form.plafond,
fu_ajk_spak_form.tenor,
fu_ajk_spak_form.tinggibadan,
fu_ajk_spak_form.beratbadan,
fu_ajk_spak_form.tekanandarah,
fu_ajk_spak_form.nadi,
fu_ajk_spak_form.pernafasan,
fu_ajk_spak_form.guladarah,
fu_ajk_spak_form.catatan,
fu_ajk_spak_form.pertanyaan6,
fu_ajk_spak_form.ket6,
fu_ajk_spak_form.kesimpulan
FROM
fu_ajk_spak
INNER JOIN fu_ajk_spak_form ON fu_ajk_spak.id_cost = fu_ajk_spak_form.idcost AND fu_ajk_spak.id = fu_ajk_spak_form.idspk
WHERE
fu_ajk_spak.id_cost = '.$mb['idbank'].' AND fu_ajk_spak.update_by = "'.$mb['id'].'" '.$satu.' '.$dua.'
ORDER BY fu_ajk_spak.input_date DESC LIMIT '.$m.', 25');

$totalRows = mysql_fetch_array($database->doQuery('SELECT COUNT(fu_ajk_spak_form.id)
												   FROM fu_ajk_spak_form
												   INNER JOIN fu_ajk_spak ON fu_ajk_spak_form.idspk = fu_ajk_spak.id
												   LEFT JOIN fu_ajk_peserta ON fu_ajk_spak_form.idcost = fu_ajk_peserta.id_cost AND fu_ajk_spak.id_polis = fu_ajk_peserta.id_polis AND fu_ajk_spak.spak = fu_ajk_peserta.spaj
												   WHERE fu_ajk_spak.id_cost = '.$mb['idbank'].' AND fu_ajk_spak.update_by = "'.$mb['id'].'" '.$satu.' '.$dua.''));
$totalRows = $totalRows[0];
$_REQUEST['x'] ? $pageNow = $_REQUEST['x'] : $pageNow = 1;
while ($met_ = mysql_fetch_array($met)) {
	$tgl_terima_spak = explode(" ", $met_['input_date']);
	$tolik = explode("/", $met_['tekanandarah']);

if ($met_['pertanyaan6']=="T") {	$pertanyaan6 = "Tidak";	}else{	$pertanyaan6 = "Iya";	}
if (($no % 2) == 1)	$objlass = 'tbl-odd'; else	$objlass = 'tbl-even';

if (is_numeric($met_['cabang'])) {
	$met_Cabang = mysql_fetch_array(mysql_query('SELECT id, name FROM fu_ajk_cabang WHERE id="'.$met_['cabang'].'"'));
	$inputcabang = $met_Cabang['name'];
}else{
	$inputcabang = $met_['cabang'];
}
echo '<tr onmouseover="this.className=\'tbl-over\'" onmouseout="this.className=\'' . $objlass . '\'" class="' . $objlass . '">
			  <td align="center">'.(++$no + ($pageNow-1) * 25).'</td>
			  <td>'.$met_['nama'].'</td>
			  <td>'.$inputcabang.'</td>
			  <td align="center">'.$met_['spak'].'</td>
			  <td align="center">'._convertDate($met_['tglInput']).'</td>
			  <td align="center">'._convertDate($met_['tglApproveSPV']).'</td>
			  <td align="center">'._convertDate($met_['dob']).'</td>
			  <td align="center">'.$met_['x_usia'].'</td>
			  <td align="center">'.duit($met_['plafond']).'</td>
			  <td align="center">'.$met_['ext_premi'].'</td>
			  <td align="center">'.$met_['tenor'].'</td>
			  <td align="center">'.$met_['tinggibadan'].'</td>
			  <td align="center">'.$met_['beratbadan'].'</td>
			  <td align="center">'.$tolik[0].'</td>
			  <td align="center">'.$tolik[1].'</td>
			  <td align="center">'.$met_['nadi'].'</td>
			  <td align="center">'.$met_['pernafasan'].'</td>
			  <td align="center">'.$met_['guladarah'].'</td>
			  <td align="center">'.$pertanyaan6.'</td>
			  <td align="center">'.$met_['ket6'].'</td>
			  <td>'.$met_['catatan'].'</td>
			  <td>'.$met_['status'].'</td>
			  </tr>';
}
	echo '<tr><td colspan="22">';
	echo createPageNavigations($file = 'imob.php?ob=rSPK&re='.$_REQUEST['re'].'&id_cost='.$mb['idbank'].'&update_by='.$mb['id'].'&tglcheck1='.$_REQUEST['tglcheck1'].'&tglcheck2='.$_REQUEST['tglcheck2'].'&statusdata='.$_REQUEST['statusdata'].'', $total = $totalRows, $psDeh = 10 , $anchor = '', $perPage = 25);
	echo '<b>Total Data SPK: <u>' . $totalRows . '</u></b></td></tr>';
	echo '</table>';
}
}
		;
		break;


case "tabgen":
echo '<script>
		function generator(){
			var time =
		}
	</script>';
echo '<center><form action="" method="POST">
	<input name="action" value="generate-code"
	<input type="submit" value="Generate">
	</form></center>
	<h1 id="output" style="margin-left: auto;margin-right:auto;text-align: center;border:1px solid black; width: 200px">';
$hour = date("H");
$month= date("m");
$token="ADONAI";
if(isset($_POST['action'])){
	if($_POST['action']=="generate-code"){
		$string = $hour.$month.$token;
		$code= md5($string);
		$code = substr($code,0,6);
		echo $code;
	}
}
echo '</h1>';
	;
	break;

	default:
echo '<table border="0" cellpadding="3" cellspacing="0" width="100%">
	<tr><td align="center" colspan="2"><font color="#ed2124" size="7"><img src="image/logo_adonai_1.gif" width="50"> A D O N A I </font> <font size="7">| Pialang Asuransi</font></td></tr>
	<tr><td align="center" colspan="2"><font color="#ffa800" size="5">Aplikasi Asuransi Jiwa Kredit dan Pensiunan</font><br /><br /><td></tr>
	</table>';
		;
} // switch

function IPnya() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'IP Tidak Dikenali';

	return $ipaddress;
}
//$ipaddress = $_SERVER['REMOTE_ADDR'];
$userGeoData = getGeoIP(IPnya());
$metCountryCode = $userGeoData->country_code;
$metCountryName = $userGeoData->country_name;
$metCityName = $userGeoData->city;
$mycountry = $metCountryName.' '.$metCityName;
//echo $mycountry[0];
$metHistory = $database->doQuery('INSERT INTO fu_ajk_user_history SET iduser="'.$mb['id'].'",loginuser="'.$futgl.'",ipuser="'.IPnya().'",opuser="'.php_uname().'",browseruser="'.$_SERVER['HTTP_USER_AGENT'].'",countryuser="'.$mycountry.'",linkbrow="'.$_SERVER['REQUEST_URI'].'"');


function getGeoIP($ip = null, $jsonArray = false) {
	try {
		// If no IP is provided use the current users IP
		if($ip == null) {
			$ip   = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
		}
		// If the IP is equal to 127.0.0.1 (IPv4) or ::1 (IPv6) then cancel, won't work on localhost
		if($ip == "127.0.0.1" || $ip == "::1") {
			throw new Exception('You are on a local sever, this script won\'t work right.');
		}
		// Make sure IP provided is valid
		if(!filter_var($ip, FILTER_VALIDATE_IP)) {
			throw new Exception('Invalid IP address "' . $ip . '".');
		}
		if(!is_bool($jsonArray)) {
			throw new Exception('The second parameter must be a boolean - true (return array) or false (return JSON object); default is false.');
		}
		// Fetch JSON data with the IP provided
		$url  = "http://freegeoip.net/json/" . $ip;
		// Return the contents, supress errors because we will check in a bit
		$json = @file_get_contents($url);
		// Did we manage to get data?
		if($json === false) {
			return false;
		}
		// Decode JSON
		$json = json_decode($json, $jsonArray);
		// If an error happens we can assume the JSON is bad or invalid IP
		if($json === null) {
			// Return false
			return false;
		} else {
			// Otherwise return JSON data
			return $json;
		}
	} catch(Exception $e) {
		return $e->getMessage();
	}
}
?>
<!--CHECKE ALL-->
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
