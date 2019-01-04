<?php
error_reporting(0);
session_start();
require('../fpdf.php');
include "../includes/fu6106.php";


if(!empty($_POST["keyword"])) {
	$query ="SELECT * FROM fu_ajk_diagnosis WHERE diagnosis_code like '" . $_POST["keyword"] . "%' or diagnosis_name like '" . $_POST["keyword"] . "%'  ORDER BY diagnosis_code LIMIT 0,10";
	$result = mysql_query($query);
	echo '<ul id="country-list">';
	while ($row = mysql_fetch_array($result)) {
		echo '<li onClick="selectCountry(\''.$row["diagnosis_code"].' - '.$row["diagnosis_name"].'\');">'.$row["diagnosis_code"].' - '.$row["diagnosis_name"].'</li>';
	}
	echo '</ul>';

}
?>
