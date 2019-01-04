<?php
$link = mysql_connect('localhost:3361', 'developer', 'devAdonai@17!');
if (!$link) die('Could not connect: ' . mysql_error());
mysql_select_db("adonai_ajk0109",$link);

switch($_POST["functionname"]){
	case "setpoliscostumer": // city
		?>
			<option value="">-- Pilih Produk --</option>
        <?php
		$qry = "select fu_ajk_polis.id, fu_ajk_polis.nmproduk from fu_ajk_polis where id_cost='".$_POST["cost"]."' AND del IS NULL";
		$sql = mysql_query($qry);
		while($row = mysql_fetch_array($sql)){
			$id = $row['id'];
			$nmproduk = $row['nmproduk'];
			echo '<option value="'.$id.'">'.$nmproduk.'</option>';
		}
		break;
	case "setpolisasuransi": // city
		$sql = "SELECT fu_ajk_asuransi.id, fu_ajk_asuransi.`name`
				FROM fu_ajk_asuransi
				INNER JOIN fu_ajk_polis_as ON fu_ajk_asuransi.id = fu_ajk_polis_as.id_as where nmproduk='".$_POST["id_polis"]."' AND fu_ajk_polis_as.del IS NULL";
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
		$sql = "select * from fu_ajk_cabang where id_reg='".$_POST["id_reg"]."' and id_area='".$_POST["id_area"]."' AND del IS NULL";
		;break;

	case "armregional": // district
		$sql = "select * from fu_ajk_regional where id_cost='".$_POST["company"]."' AND del IS NULL";
		;break;
	case "setpoliscostumerregional": // city
		?>
			<option value="">-- Pilih Regional --</option>
        <?php
		$qry = "select fu_ajk_regional.id, fu_ajk_regional.name from fu_ajk_regional where id_cost='".$_POST['cost']."' AND del IS NULL";
		$sql = mysql_query($qry);
		while($row = mysql_fetch_array($sql)){
			$id = $row['id'];
			$nmreg = $row['name'];
			echo '<option value="'.$id.'">'.$nmreg.'</option>';
		}
		break;
	case "setpoliscostumercabang": // city
		?>
			<option value="">-- Pilih Cabang --</option>
        <?php
		$qry = "select fu_ajk_cabang.id, fu_ajk_cabang.name from fu_ajk_cabang where id_cost='".$_POST['cost']."' AND id_reg='".$_POST['regi']."' AND del IS NULL";
		$sql = mysql_query($qry);
		while($row = mysql_fetch_array($sql)){
			$id = $row['id'];
			$nmcab = $row['name'];
			echo '<option value="'.$id.'">'.$nmcab.'</option>';
		}
		break;
	case "setdokter": // city
		$sql = "select * from fu_ajk_spak_form where idcost='".$_POST['id_cost']."' AND del IS NULL GROUP BY dokter_pemeriksa";
		;break;
	case "setuserupload": // city
		$sql = "select * from fu_ajk_peserta where id_dn='' AND id_cost='".$_POST["id_cost"]."' AND id_polis='".$_POST["id_polis"]."' AND del IS NULL GROUP BY input_by";
		break;
	case "settglupload": // city
		$sql = "select * from fu_ajk_peserta where id_dn='' AND status_aktif ='Approve' AND id_cost='".$_POST["id_cost"]."' AND id_polis='".$_POST["id_polis"]."' AND del IS NULL GROUP BY input_time ORDER BY input_time DESC";
		break;

	case "editpolisasuransi": // POLIS ASURANSI
		$sql = 'select * from fu_ajk_polis_as where id_as="'.$_POST["id_as"].'" AND del IS NULL';
		break;
}

$rows = mysql_query($sql,$link);
$data = "[";
$first = true;
while($row = mysql_fetch_assoc($rows)) {
if ($row['nmproduk']=="") {	$metproduknya = $row['nopol'];	}else{	$metproduknya = $row['nmproduk'];	}

	if ($first) $first = false; else $data .= ",";
	$data .= "{";
	if ($_REQUEST['req']=="setwilarea") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setpolisasuransi") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setpoliscostumerregional") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setpoliscostumercabang") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	elseif ($_REQUEST['req']=="setuserupload") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["input_by"].'"';	}
	elseif ($_REQUEST['req']=="settglupload") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["input_time"].'"';	}
	elseif ($_REQUEST['req']=="setdokter")		{	$data .= '"id":"'.$row["dokter_pemeriksa"].'","name":"'.$row["dokter_pemeriksa"].'"';	}
	elseif ($_REQUEST['req']=="editpolisasuransi")		{	$data .= '"id":"'.$row["nopol"].'","name":"'.$row["nopol"].'"';	}
	else	{	$data .= '"id":"'.$row["id"].'","name":"'.$metproduknya.'"';	}
	$data .= "}";
}
$data .= "]";
mysql_close($link);

echo $data;
?>