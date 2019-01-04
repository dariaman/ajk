<?php
$link = mysql_connect('localhost', 'adonai010914ajk', 'GtZpZWXy53aJUcWU');
if (!$link) die('Could not connect: ' . mysql_error());
mysql_select_db("adonai_ajk0109",$link);
if (session_is_registered('nm_user')) {	$q=mysql_fetch_array(mysql_query('SELECT * FROM pengguna WHERE nm_user="'.$_SESSION['nm_user'].'"'));	}
$req = $_GET["req"];
switch($req) {
	case "setpoliscostumer": // city
		$sql = "select * from fu_ajk_polis where id_cost='".$_POST["id_cost"]."'";
		break;
	case "c": // city
		$sql = "select * from fu_ajk_area where id_reg='".$_POST["id_reg"]."'";
		break;
	case "d": // district
		$sql = "select * from fu_ajk_cabang where id_reg='".$_POST["id_reg"]."' and id_area='".$_POST["id_area"]."'";
		break;

	case "armregional": // district
		$sql = "select * from fu_ajk_regional where id_cost='".$_POST["company"]."'";
		break;
	case "setdatacabang": // city
		$sql = "select * from fu_ajk_cabang where id_reg='".$_POST['id_reg']."'";
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
	elseif ($_REQUEST['req']=="setdatacabang") {	$data .= '"id":"'.$row["id"].'","name":"'.$row["name"].'"';	}
	else	{	$data .= '"id":"'.$row["id"].'","name":"'.$metproduknya.'"';	}
	$data .= "}";
}
$data .= "]";
mysql_close($link);

echo $data;
?>