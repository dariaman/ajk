<?php
$link = mysql_connect('localhost:3361', 'developer', 'devAdonai@17!');
if (!$link) die('Could not connect: ' . mysql_error());
mysql_select_db("adonai_ajk0109",$link);

$req = $_GET["req"];
switch($req) {
	case "setpoliscostumer": // city
		$sql = "select * from fu_ajk_polis where id_cost='".$_POST["id_cost"]."' AND del IS NULL ORDER BY nmproduk ASC";
		break;
	case "setpolisasuransi": // city
		if (!$_POST["id_polis"]) {
		$sql = "SELECT fu_ajk_asuransi.id, fu_ajk_asuransi.`name`, fu_ajk_polis_as.nopol
				FROM fu_ajk_asuransi
				INNER JOIN fu_ajk_polis_as ON fu_ajk_asuransi.id = fu_ajk_polis_as.id_as
				WHERE fu_ajk_polis_as.id_cost = ".$_POST["id_cost"]." AND fu_ajk_polis_as.del IS NULL AND fu_ajk_asuransi.status='Aktif'
				GROUP BY fu_ajk_asuransi.id
				ORDER BY fu_ajk_asuransi.`name` ASC";

		}else{
   $sql = "SELECT fu_ajk_asuransi.id, fu_ajk_asuransi.`name`, fu_ajk_polis_as.nopol
   		   FROM fu_ajk_asuransi
   		   INNER JOIN fu_ajk_polis_as ON fu_ajk_asuransi.id = fu_ajk_polis_as.id_as
   		   WHERE fu_ajk_polis_as.nmproduk='".$_POST["id_polis"]."' AND fu_ajk_polis_as.del IS NULL AND fu_ajk_asuransi.status='Aktif'
   		   ORDER BY fu_ajk_asuransi.`name` ASC";
		}
		break;
	case "setasuransi":
		$sql = "select * from fu_ajk_asuransi where id='".$_POST["id_polis"]."'";
		;break;
	case "setwilarea": // city
		$sql = "select * from fu_ajk_area where id_reg='".$_POST['id_reg']."' AND del IS NULL";
		;break;
	case "c": // city
		$sql = "select * from fu_ajk_area where id_reg='".$_POST["id_reg"]."' AND del IS NULL";
		;break;
	case "d": // district
		$sql = "select * from fu_ajk_cabang where id_reg='".$_POST["id_reg"]."' and id_area='".$_POST["id_area"]."' AND del IS NULL ORDER BY name ASC";
		;break;

	case "armregional": // district
		$sql = "select * from fu_ajk_regional where id_cost='".$_POST["company"]."' AND del IS NULL";
		;break;
	case "setpoliscostumermitra": // Nama mitra
		$sql = "select * from fu_ajk_grupproduk where id_cost='".$_POST['id_cost']."' AND del IS NULL";
		break;

	case "setpoliscostumerregional": // city
		$sql = "select * from fu_ajk_regional where id_cost='".$_POST['id_cost']."' AND del IS NULL";
		break;
	case "setpoliscostumercabang": // city
		$sql = "select * from fu_ajk_cabang where id_cost='".$_POST['id_cost']."' AND id_reg='".$_POST['id_reg']."' AND del IS NULL ORDER BY name ASC";
		;break;
	case "setdokter": // city
		$sql = "select * from fu_ajk_spak_form where idcost='".$_POST['id_cost']."' GROUP BY dokter_pemeriksa";
		;break;
	case "setuserupload": // city
		$sql = "select * from fu_ajk_peserta where id_dn='' AND id_cost='".$_POST["id_cost"]."' AND id_polis='".$_POST["id_polis"]."' AND del IS NULL GROUP BY input_by";
		//$sql = "select * from fu_ajk_peserta limit 1";
		break;
	case "settglupload": // city
		$sql = "select * from fu_ajk_peserta where id_dn='' AND status_aktif ='Approve' AND id_cost='".$_POST["id_cost"]."' AND id_polis='".$_POST["id_polis"]."' AND del IS NULL GROUP BY input_time ORDER BY input_time DESC";
		break;

	case "editpolisasuransi": // POLIS ASURANSI
		$sql = 'select * from fu_ajk_polis_as where id_as="'.$_POST["id_as"].'"';
		break;

	case "setspvcabang": // POLIS ASURANSI
		//		$sql = 'select * from user_mobile where idbank="'.$_POST["id_cost"].'" AND type="Marketing" AND level="Supervisor" AND cabang="'.$_POST["id_cab"].'" ORDER BY namalengkap ASC';
		$sql = 'select * from user_mobile where idbank="'.$_POST["id_cost"].'" AND type="Marketing" AND level="Supervisor" ORDER BY namalengkap ASC';
		break;
	case "editspvcabang": // POLIS ASURANSI
		$sql = 'select * from user_mobile where type="Marketing" AND level="Supervisor" AND cabang="'.$_POST["id_cab"].'" ORDER BY namalengkap ASC';
		break;

	case "setcabang": // district
		$sql = "select * from fu_ajk_cabang where id_cost='".$_POST["id_cost"]."' AND del IS NULL GROUP BY name ORDER BY name ASC";
		;break;

	case "setmitra": // MITRA
		$sql = "select * from fu_ajk_grupproduk where id_cost='".$_POST["id_cost"]."' AND del IS NULL ORDER BY nmproduk ASC";
		;break;
}

$rows = mysql_query($sql,$link);
$data = "[";
$first = true;
while($row = mysql_fetch_assoc($rows)) {
if ($row['nmproduk']=="") {	$metproduknya = $row['nopol'];	}else{	$metproduknya = $row['nmproduk'];	}

	if ($first) $first = false; else $data .= ",";
	$data .= "{";
	if ($_REQUEST['req']=="setwilarea") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setpolisasuransi") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].' - '.$row["nopol"].'"';	}
	elseif ($_REQUEST['req']=="setpoliscostumerregional") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setpoliscostumercabang") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setuserupload") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["input_by"].'"';	}
	elseif ($_REQUEST['req']=="settglupload") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["input_time"].'"';	}
	elseif ($_REQUEST['req']=="setdokter")		{

		if (is_numeric($row["dokter_pemeriksa"])) {
			$cekDokternya = mysql_fetch_array(mysql_query('SELECT * FROM user_mobile WHERE id="'.$row["dokter_pemeriksa"].'"'));
			$metDokterPemeriksa = $cekDokternya['namalengkap'];	}else{	$metDokterPemeriksa = $row["dokter_pemeriksa"];
		}

		$data .= '"id":"'.$row["dokter_pemeriksa"].'","name":"'.$metDokterPemeriksa.'"';
	}
	elseif ($_REQUEST['req']=="editpolisasuransi")		{	$data .= '"id":"'.$row["nopol"].'","name":"'.$row["nopol"].'"';	}
	elseif ($_REQUEST['req']=="setspvcabang")		{	$data .= '"id":"'.$row["id"].'","name":"'.$row["namalengkap"].'"';	}
	elseif ($_REQUEST['req']=="editspvcabang")		{	$data .= '"id":"'.$row["id"].'","name":"'.$row["namalengkap"].'"';	}
	elseif ($_REQUEST['req']=="setcabang")		{	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setmitra")		{	$data .= '"id":"'.$row["id"].'","name":"'.$row["nmproduk"].'"';	}
	else	{	$data .= '"id":"'.$row["id"].'","name":"'.$metproduknya.'"';	}
	$data .= "}";
}
$data .= "]";
mysql_close($link);

echo $data;
?>