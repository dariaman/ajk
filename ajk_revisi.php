<?php

	include_once ("ui.php");
	include_once ("../includes/functions.php");
	connect();
	$today = date("Y-m-d G:i:s");

	if (isset($_SESSION['nm_user'])) {
			$query = $database->doQuery("SELECT *,REPLACE(REPLACE(nm_user, 'staff', 'spv'),'!','$')as spv FROM pengguna WHERE nm_user= '". $_SESSION['nm_user'] ."' ");
	    $q = mysql_fetch_array($query);
	    $qsescost = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_costumer WHERE id="' . $q['id_cost'] . '"'));
	}

	if(mysql_num_rows($query) > 0){

	switch ($_REQUEST['er']) {
		case "add":		
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
			
			$cabang = $q['cabang'];			
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
			
					
			$query = "INSERT INTO t_revisi(customer,no_revisi,no_ref,nm_peserta,produk,cabang,dept_terkait,keterangan,attachment,attachment2,flag_aktif,user_input,date_input) 
								VALUES('BUKOPIN','".$no_revisi."','".$no_ref."','".$peserta."','".$produk."','".$cabang."','".$dept_terkait."','".$keterangan."','".$attachment_name."','".$attachment_name2."','T','".$_SESSION['nm_user']."','".$today."')";
			
			$database->doQuery($query,"adnoffice");
			
			echo '<br><br><center>Revisi telah dibuat dengan no tiket ' . $no_revisi . '</center><meta http-equiv="refresh" content="5; url=ajk_revisi.php">';		
		break;
		case "new":
			echo '<br>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<th width="95%" align="left">Revisi</font></th>					
							</tr>				
						</table>';
			echo '<br>
						<table border="0" width="100%" cellpadding="1" cellspacing="1">
							<form method="post" name="frm_pengajuan" action="ajk_revisi.php?er=add" enctype="multipart/form-data">
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
		case "app":
		
			$kd_revisi = $_REQUEST['id'];		

			$revisi = mysql_fetch_array($database->doQuery("select * from t_revisi where kd_revisi = ".$kd_revisi,"adnoffice"));		
			$no_revisi = $revisi['no_revisi'];
			$quser = mysql_fetch_array($database->doQuery("select * from approval where table_name = 't_revisi' and kd_primary_table = '".$kd_revisi."' order by kd_approval desc limit 1 ","adnoffice"));		
			$dear = 'Dear Tim Adonai';
			$body = 'No Revisi #'.$no_revisi.' approved by '.$quser['user_approval'].', tolong segera dikerjakan.';
			$link = 'http://150.107.149.27/adnoffice/revisi';
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
			//EMAIL PENERIMA  KANTOR U/W
						
			$mailOffice = $database->doQuery("select * from usermst where UserDepartment='".$bagian_terkait."'","adnoffice");		
			
			while ($mailOffice_ = mysql_fetch_array($mailOffice)) {
				$mail->AddCC($mailOffice_['UserEmail'], $mailOffice_['UserName']); //To address who will receive this email
			}		
			//$mail->AddBCC("hansen@adonai.co.id");
			$mail->MsgHTML($ls_body); //Put your body of the message you can place html code here

			$send = $mail->Send(); //Send the mails						
			
			$query_approval = "UPDATE approval SET date_approval = '".$today."', kd_action = 1 WHERE table_name = 't_revisi' and kd_primary_table = '".$kd_revisi."'";
			$query_progress = "INSERT INTO t_revisi_progress(kd_revisi,kd_status,note,user_input,date_input) VALUES(".$kd_revisi.",3,'Approved by ".$_SESSION['nm_user']."','".$_SESSION['nm_user']."','".$today."')";
					
			$database->doQuery($query_approval,"adnoffice");
			$database->doQuery($query_progress,"adnoffice");								
					
			echo 		'<br><br><center>no tiket ' . $revisi['no_revisi'] . '. Telah di approve</center><meta http-equiv="refresh" content="5; url=ajk_revisi.php?er=approve">';
		break;		
		case "approve":
			echo'<br>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<th width="95%" align="left">Approval Revisi</font></th>
							</tr>
						</table>';

			$q_revisi = $database->doQuery("SELECT *,DATE_FORMAT(date_input,'%Y-%m-%d')as tgl_revisi 
																			FROM t_revisi 
																						INNER JOIN approval
																						ON approval.kd_primary_table = t_revisi.kd_revisi and
																							 approval.table_name = 't_revisi'
																			WHERE approval.user_approval = '".$_SESSION['nm_user']."' and 
																						date_approval is null ORDER BY t_revisi.date_input DESC","adnoffice");	
		
			echo '<br>			 
						 <table border="1" cellpadding="5" cellspacing="0" width="100%" bgcolor="#bde0e6">
								<tr>
									<td align="center" width="2%">No</td>
									<td align="center" width="5%">Action</td>
									<td align="center" width="7%">No Tiket</td>
									<td align="center" width="5%">Tgl Revisi</td>
									<td align="center" width="10%">No Referensi</td>
									<td align="center" width="10%">Nama Peserta</td>
									<td align="center" width="10%">Produk</td>
									<td align="center" width="25%">Keterangan</td>
									<td align="center" width="5%">atch</td>
									<td align="center" width="5%">atch2</td>
									<td align="center" width="5%">Status</td>							
								</tr>';
								$no = 1;
							while($q_revisi_r = mysql_fetch_array($q_revisi)){						
								$status = mysql_fetch_array($database->doQuery("SELECT nm_status
																																 FROM t_revisi_progress 
																																 			INNER JOIN m_status
								 																											ON m_status.kd_status = t_revisi_progress.kd_status
																																			WHERE kd_revisi = '".$q_revisi_r['kd_revisi']."' ORDER BY KD_PROGRESS DESC LIMIT 1","adnoffice"));
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
								
			echo		 	'<tr>
										<td align="center">'.$no.'</td>
										<td align="center"><a href="ajk_revisi.php?er=app&id='.$q_revisi_r['kd_revisi'].'">Approve</a>|<a href="ajk_revisi.php?er=reject&id='.$q_revisi_r['kd_revisi'].'">Reject</a></td>
										<td align="center">'.$q_revisi_r['no_revisi'].'</td>								
										<td align="center">'._convertDate($q_revisi_r['tgl_revisi']).'</td>
										<td align="center">'.$q_revisi_r['no_ref'].'</td>
										<td align="center">'.$q_revisi_r['nm_peserta'].'</td>
										<td align="center">'.$q_revisi_r['produk'].'</td>								
										<td>'.$q_revisi_r['keterangan'].'</td>
										<td align="center">'.$attachment.'</td>
										<td align="center">'.$attachment2.'</td>
										<td align="center">'.$status['nm_status'].'</td>
								 </tr>';
								 $no++;						
							} 
				echo'</table>';														
		break;
		case "reject":
			echo '<br>
						<table border="0" cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<th width="95%" align="left">Reject Revisi</font></th>					
							</tr>				
						</table>';
			echo '<br>
						<table border="0" width="100%" cellpadding="1" cellspacing="1">
							<form method="post" name="frm_reject" action="ajk_revisi.php?er=reject_action&id='.$_REQUEST['id'].'" enctype="multipart/form-data">
								<tr>
									<td align="right" width="7%">Keterangan Reject : </td>
									<td><textarea id="keterangan" name="keterangan" cols="40" required></textarea></td>
								</tr>							
								<td colspan="3" align="center">
									<button type="submit" class="button" style="text-align:center">Submit</button>						
								</td>								
							</form>
						</table>';
		break;
		case "reject_action":
			$kd_revisi = $_REQUEST['id'];		
			$note = '"'.$_REQUEST['keterangan'].'"';

			$revisi = mysql_fetch_array($database->doQuery("select * from t_revisi where kd_revisi = ".$kd_revisi,"adnoffice"));		
			$no_revisi = $revisi['no_revisi'];
							
			$query_progress = "INSERT INTO t_revisi_progress(kd_revisi,kd_status,note,user_input,date_input) 
												 VALUES(".$kd_revisi.",9,'Rejected by ".$_SESSION['nm_user']." with dengan catatan ".$note."','".$_SESSION['nm_user']."','".$today."')";
			$query_approval = "UPDATE approval SET date_approval = '".$today."', kd_action = 9 WHERE table_name = 't_revisi' and kd_primary_table = '".$kd_revisi."'";

			$database->doQuery($query_approval,"adnoffice");
			$database->doQuery($query_progress,"adnoffice");	

			echo 		'<br><br><center>no tiket ' . $revisi['no_revisi'] . '. Telah di Reject</center><meta http-equiv="refresh" content="5; url=ajk_revisi.php?er=approve">';
		break;
		default:
		
			echo'<br>
					<table border="0" cellpadding="5" cellspacing="0" width="100%">
						<tr>
							<th width="95%" align="left">List Revisi</font></th>
						</tr>
					</table><br>';		
			if(substr($_SESSION['nm_user'],0,5) == "staff"){	
					echo'<form action="ajk_revisi.php?er=new" method="POST">
							 		<button type="submit">Revisi Baru</button>
							 </form>';
					$q_revisi = $database->doQuery("SELECT *,DATE_FORMAT(date_input,'%Y-%m-%d')as tgl_revisi 
																					FROM t_revisi 
																					WHERE user_input = '".$_SESSION['nm_user']."' and flag_aktif = 'T' ORDER BY date_input DESC","adnoffice");
			}else{
					$q_revisi = $database->doQuery("SELECT *,DATE_FORMAT(date_input,'%Y-%m-%d')as tgl_revisi 
																					FROM t_revisi	
																							 INNER JOIN approval
																							 ON approval.table_name = 't_revisi' AND
																									approval.kd_primary_table = t_revisi.kd_revisi
																					WHERE approval.user_approval = '".$_SESSION['nm_user']."' and flag_aktif = 'T' ORDER BY date_input DESC","adnoffice");
			}
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
	}
}
?>