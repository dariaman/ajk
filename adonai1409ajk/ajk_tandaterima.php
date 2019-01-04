<?php
include_once ("ui.php");
include_once ("../includes/functions.php");
connect();
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array($database->doQuery('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}

$futgl = date("Y-m-d");
$futglidcn = date("Y");
$today = date("Y-m-d G:i:s");

switch ($_REQUEST['er']) {
  case "actionheader":
    $action = $_REQUEST['c'];
    $NO_TT = $_REQUEST['NO_TT'];
    $TIPE_TT = $_REQUEST['TIPE_TT'];
    $TGL_TT = $_REQUEST['TGL_TT'];

    if($action == "new"){
      $attachment = $_FILES['ATTACHMENT']['name'];
      $attachment_tmp = $_FILES['ATTACHMENT']['tmp_name'];
      $attachment_name =null;
  
      if($attachment!= ""){
        $attachment_info = pathinfo($attachment);		
        $attachment_extension = strtolower($attachment_info["extension"]); //image extension
        $attachment_name_only = strtolower($attachment_info["filename"]);//file name only, no extension						
        $num_file = date('YmdHis');		

        $attachment_name = $attachment_name_only.'-'.$num_file.'.'.$attachment_extension;              
        $destination_folder		= '../ajk_file/_tandaterima/'.$attachment_name;
        move_uploaded_file($attachment_tmp,$destination_folder) or die( "Could not upload file!");
      }	      

      $query = "INSERT INTO FU_AJK_TT 
      SET NO_TT='".$NO_TT."',
          TIPE_TT='".$TIPE_TT."',
          TGL_TT='".$TGL_TT."',
          ATTACHMENT='".$attachment_name."',
          INPUT_BY='".$_SESSION['nm_user']."',
          INPUT_DATE='".$today."',
          UPDATE_BY='".$_SESSION['nm_user']."',
          UPDATE_DATE='".$today."'";

    }elseif($action == "update"){
      $query = "UPDATE FU_AJK_TT 
      SET NO_TT='".$NO_TT."',
          TIPE_TT='".$TIPE_TT."',
          TGL_TT='".$TGL_TT."',
          ATTACHMENT='".$attachment."',
          UPDATE_BY='".$_SESSION['nm_user']."',
          UPDATE_DATE='".$today."'
      WHERE ID_TT = '".$_REQUEST['id']."'";
    }
    //echo $query;
    $result = mysql_query($query);
    if($result){
      echo '<br><br><center>Data Berhasil Disimpan Silahkan masukan peserta di dalam tanda terima ini</center><meta http-equiv="refresh" content="5; url=ajk_tandaterima.php">';		
    }else{
      echo '<br><br><center>Gagal Di simpan, Silahkan Ulangi.</center><meta http-equiv="refresh" content="5; url=ajk_tandaterima.php">';		
    }

  break;

  case "actiondetail":
    $action = $_REQUEST['c'];
    $ID_PESERTA = $_REQUEST['b'];
    $ID_TT = $_REQUEST['a'];
    
    $peserta = "SELECT * FROM fu_ajk_peserta WHERE id_peserta = '".$ID_PESERTA."'";
    $result = mysql_query($peserta);
    if(mysql_num_rows($result) == 0){      
      echo '<br><br><center>Peserta Tidak terdaftar di Sistem</center><meta http-equiv="refresh" content="5; url=ajk_tandaterima.php?er=formheader&a='.$ID_TT.'">';		
    }else{

      if($action == "new"){
        $qtt = "SELECT * FROM FU_AJK_TT_D WHERE ID_PESERTA = '".$ID_PESERTA."' AND ID_TT = '".$ID_TT."'";
        $result = mysql_query($qtt);
        if(mysql_num_rows($result) > 0){      
          echo '<br><br><center>Peserta Sudah Terdaftar</center><meta http-equiv="refresh" content="5; url=ajk_tandaterima.php?er=formheader&a='.$ID_TT.'">';		
        }else{          
          $query="
          INSERT INTO FU_AJK_TT_D
          SET ID_TT = ".$ID_TT.",
              ID_PESERTA = '".$ID_PESERTA."',
              INPUT_BY = '".$_SESSION['nm_user']."',
              INPUT_DATE = '".$today."'";
        }
      }elseif($action == "del"){
        $query="DELETE FROM FU_AJK_TT_D WHERE ID_TT = '".$ID_TT."' AND ID_PESERTA = '".$ID_PESERTA."'";
      }
      // echo $query;
      if($query){
        $result = mysql_query($query);
        if($result){
          echo '<br><br><center>Data Berhasil Disimpan</center><meta http-equiv="refresh" content="5; url=ajk_tandaterima.php?er=formheader&a='.$ID_TT.'">';		
        }else{
          echo '<br><br><center>Gagal Di simpan, Silahkan Ulangi.</center><meta http-equiv="refresh" content="5; url=ajk_tandaterima.php?er=formheader&a='.$ID_TT.'">';		
        }
      }
      
      
    }
  break;

  case "formheader":
    $ID_TT = $_REQUEST['a'];
    if($ID_TT){
      $action = 'ajk_tandaterima.php?er=actionheader&c=update';
      $judul = "EDIT";
      $header = mysql_fetch_array(mysql_query("SELECT * FROM FU_AJK_TT WHERE ID_TT = '".$ID_TT."'"));
      $id = '<input type="hidden" name="id" value="'.$ID_TT.'">';
      $val = 'value="'.date('Y-m-d',strtotime($header['TGL_TT'])).'"';
      $att = '<a href="../ajk_file/_tandaterima/'.$header['ATTACHMENT'].'" target="_blank">'.$header['ATTACHMENT'].'</a>';
      if($header['TIPE_TT']=="Pengajuan Klaim Ke Asuransi"){
        $select1 = 'selected';
      }elseif($header['TIPE_TT']=="Kelengkapan Dokumen Klaim Ke Asuransi"){
        $select2 = 'selected';
      }elseif($header['TIPE_TT']=="Sanggahan ke Asuransi"){
        $select3 = 'selected';
      }elseif($header['TIPE_TT']=="Tolakan ke Bank"){
        $select4 = 'selected';
      }elseif($header['TIPE_TT']=="Surat Pembayaran Ke Bank"){
        $select5 = 'selected';
      }
    }else{
      $judul = "NEW";
      $action = 'ajk_tandaterima.php?er=actionheader&c=new';
      $att = '<input id="ATTACHMENT" name="ATTACHMENT" type="file" accept="application/pdf,image/gif, image/jpeg">';
    }

    echo '
    <br>
    <table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr>
        <th width="95%" align="left">'.$judul.' Tanda Terima</font></th>
        <th><a href="ajk_tandaterima.php"><img src="image/back.png" width="20"></a></th>
      </tr>
    </table>
    <br>
    <table border="0" width="100%" cellpadding="1" cellspacing="1">
      <form method="post" name="frm_tt" action="'.$action.'" enctype="multipart/form-data">
        '.$id.'
        <tr>
          <td align="right" width="10%">No Tanda Terima : </td>
          <td><input type="text" size="40" name="NO_TT" id="NO_TT" value="'.$header['NO_TT'].'" required></td>
        </tr>
        <tr>
          <td align="right" width="10%">Tipe Tanda Terima : </td>
          <td>
            <select name="TIPE_TT" required>
              <option value= "">-Pilih-</option>
              <option value= "Pengajuan Klaim Ke Asuransi" '.$select1.'>Pengajuan Klaim Ke Asuransi</option>
              <option value= "Kelengkapan Dokumen Klaim Ke Asuransi" '.$select2.'>Kelengkapan Dokumen Klaim Ke Asuransi</option>
              <option value= "Sanggahan ke Asuransi" '.$select3.'>Sanggahan ke Asuransi</option>
              <option value= "Tolakan ke Bank" '.$select4.'>Tolakan ke Bank</option>
              <option value= "Surat Pembayaran Ke Bank" '.$select5.'>Surat Pembayaran Ke Bank</option>
            </select>          
          </td>
        </tr>
        <tr>
          <td align="right" width="10%">Tgl Tanda Terima : </td>
          <td><input type="date" name="TGL_TT" '.$val.' required></td>
        </tr>
        <tr>
          <td align="right" width="10%">Attachment : </td>
          <td>'.$att.'</td>
        </tr>
        <td colspan="3" align="center">
          <button type="submit" class="button" style="text-align:center">Submit</button>						
        </td>										
      </form>				
    </table>';
    if($ID_TT){
    echo '
    <hr>
    <br>
    <form action="ajk_tandaterima.php?er=formdetail" method="POST">
      <input type="hidden" name="a" value="'.$ID_TT.'">
      <button tipe="submit">Tambah Detail</button>
    </form>
    <table border="1" width="50%" cellpadding="1" cellspacing="1">
      <tr>
        <td align="center">No</td>
        <td align="center">Id Peserta</td>
        <td align="center">Nama Peserta</td>
        <td align="center">Action</td>
      <tr>';
      $no = 1;
      $detail = mysql_query("SELECT * FROM FU_AJK_TT_D WHERE ID_TT = '".$ID_TT."'");      
      while($row = mysql_fetch_array($detail)){
        $peserta = mysql_fetch_array(mysql_query("SELECT * FROM fu_ajk_peserta WHERE id_peserta = '".$row['ID_PESERTA']."'"));
        echo'
        <tr>
          <td align="center">'.$no.'</td>
          <td align="center">'.$row['ID_PESERTA'].'</td>
          <td align="center">'.$peserta['nama'].'</td>
          <td align="center" width="20%"><a href="ajk_tandaterima.php?er=actiondetail&c=del&a='.$row['ID_TT'].'&b='.$row['ID_PESERTA'].'">Delete</a></td>
        <tr>';
        $no++;
      }
      echo'
    </table>';		
    }
  break;	

  case "formdetail":
    $ID_TT = $_REQUEST['a'];
    $ID_PESERTA = $_REQUEST['b'];
    if($ID_TT and $ID_PESERTA){
      $action = 'ajk_tandaterima.php?er=actiondetail&c=update';
      $judul = "EDIT";
      $detail = mysql_fetch_array(mysql_query("SELECT * FROM FU_AJK_TT_D WHERE ID_TT = '".$ID_TT."' AND ID_PESERTA = '".$ID_PESERTA."'"));      
    }else{
      $judul = "NEW";
      $action = 'ajk_tandaterima.php?er=actiondetail&c=new';
    }
    echo '
    <br>
    <table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <th width="95%" align="left">'.$judul.' Tanda Terima Detail</font></th>
      <th><a href="ajk_tandaterima.php?er=formheader&a='.$ID_TT.'"><img src="image/back.png" width="20"></a></th>
    </tr>
    </table>    
    <br>
    <table border="0" width="100%" cellpadding="1" cellspacing="1">
      <form method="post" name="frm_tt" action="'.$action.'" enctype="multipart/form-data">
        <input type="hidden" name="a" value="'.$ID_TT.'">
        <tr>
          <td align="right" width="10%">Id Peserta : </td>
          <td><input type="text" size="40" name="b" id="ID_PESERTA" value="'.$detail['ID_PESERTA'].'" required></td>
        </tr>
        <td colspan="3" align="center">
          <button type="submit" class="button" style="text-align:center">Submit</button>						
        </td>										
      </form>				
    </table>';		
  break;	  
  
  case "listbypeserta":
    $ID_PESERTA = $_REQUEST['b'];
    echo '
    <table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr>
        <th width="95%" align="left">List Tanda Terima By Peserta</font></th>
        <th><a href="ajk_tandaterima.php"><img src="image/back.png" width="20"></a></th>
      </tr>
    </table><br>
    <table border="0" width="100%" cellpadding="1" cellspacing="1">
      <form method="post" name="frm_tt" action="'.$action.'" enctype="multipart/form-data">
        <input type="hidden" name="han" value="search">
        <tr>
          <td align="right" width="10%">Id Peserta : </td>
          <td><input type="text" size="40" name="b" id="ID_PESERTA" value="'.$ID_PESERTA.'" required></td>
        </tr>
        <td colspan="3" align="center">
          <button type="submit" class="button" style="text-align:center">Search</button>						
        </td>										
      </form>				
    </table>';	

    if($_REQUEST['han'] == "search"){
      $query = "
      SELECT * 
      FROM FU_AJK_TT
      INNER JOIN FU_AJK_TT_D ON FU_AJK_TT_D.ID_TT = FU_AJK_TT.ID_TT
      WHERE  ID_PESERTA = '".$ID_PESERTA."'";
      
      $qtt = $database->doQuery($query);
      echo'
      <br>
      <hr>
      <br>
      <table border="1" cellpadding="5" cellspacing="0" width="100%" >
      <tr>
        <td align="center" width="2%" bgcolor="#bde0e6">No</td>
        <td align="center" width="5%" bgcolor="#bde0e6">No Tanda Terima</td>
        <td align="center" width="10%" bgcolor="#bde0e6">Tipe Tanda Terima</td>
        <td align="center" width="5%" bgcolor="#bde0e6">Tgl Tanda Terima</td>
        <td align="center" width="5%" bgcolor="#bde0e6">Attachment</td>
        <td align="center" width="3%" bgcolor="#bde0e6">User Input</td>
        <td align="center" width="3%" bgcolor="#bde0e6">Tgl Input</td>
      </tr>';
      $no = 1;
      while($row = mysql_fetch_array($qtt)){
        echo 		'
        <tr>
          <td align="center">'.$no.'</td>
          <td align="center">'.$row['NO_TT'].'</td>
          <td align="center">'.$row['TIPE_TT'].'</td>
          <td align="center">'.date("d-m-Y",strtotime($row['TGL_TT'])).'</td>
          <td align="center"><a href="../ajk_file/_tandaterima/'.$row['ATTACHMENT'].'" target="_blank">'.$row['ATTACHMENT'].'</a></td>
          <td align="center">'.$row['INPUT_BY'].'</td>
          <td align="center">'.$row['INPUT_DATE'].'</td>
        </tr>';
        $no++;
      }
    echo '
    </table>'; 
    }

     
  break;

  default:
    $query = "SELECT *,(SELECT COUNT(*) FROM FU_AJK_TT_D WHERE FU_AJK_TT_D.ID_TT = FU_AJK_TT.ID_TT)AS JML FROM FU_AJK_TT";
    $qtt = $database->doQuery($query);
    echo'
    <br>
    <table border="0" cellpadding="5" cellspacing="0" width="100%">
      <tr>
        <th width="95%" align="left">List Tanda Terima</font></th>
        <th><a href="ajk_tandaterima.php?er=listbypeserta">List By Peserta</a></th>
      </tr>
    </table><br>
    <form action="ajk_tandaterima.php?er=formheader" method="POST">
      <button type="submit">Tanda Terima Baru</button>
    </form>
    <br>
    <table border="1" cellpadding="5" cellspacing="0" width="100%" >
      <tr>
        <td align="center" width="2%" bgcolor="#bde0e6">No</td>
        <td align="center" width="5%" bgcolor="#bde0e6">No Tanda Terima</td>
        <td align="center" width="10%" bgcolor="#bde0e6">Tipe Tanda Terima</td>
        <td align="center" width="5%" bgcolor="#bde0e6">Tgl Tanda Terima</td>
        <td align="center" width="5%" bgcolor="#bde0e6">Attachment</td>
        <td align="center" width="3%" bgcolor="#bde0e6">User Input</td>
        <td align="center" width="3%" bgcolor="#bde0e6">Tgl Input</td>
        <td align="center" width="2%" bgcolor="#bde0e6">Action</td>
      </tr>';
      $no = 1;
      while($row = mysql_fetch_array($qtt)){
        echo 		'
        <tr>
          <td align="center">'.$no.'</td>
          <td align="center">'.$row['NO_TT'].' ['.$row['JML'].']</td>
          <td align="center">'.$row['TIPE_TT'].'</td>
          <td align="center">'.date("d-m-Y",strtotime($row['TGL_TT'])).'</td>
          <td align="center"><a href="../ajk_file/_tandaterima/'.$row['ATTACHMENT'].'" target="_blank">'.$row['ATTACHMENT'].'</a></td>
          <td align="center">'.$row['INPUT_BY'].'</td>
          <td align="center">'.$row['INPUT_DATE'].'</td>
          <td align="center"><a href="ajk_tandaterima.php?er=formheader&a='.$row['ID_TT'].'">Edit</a></td>
        </tr>';
        $no++;
      }
    echo '
    </table>';
}
?>