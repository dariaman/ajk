<?php
	$host = "localhost:3361";
	$user = "developer";
	$pass = "devAdonai@17!";
	$db   = "adonai_ajk0109";

	$con = mysqli_connect($host, $user, $pass, $db);
	if($con)
	{
	echo ("koneksi sukses");
	}
	else
	{
	echo ("koneksi gagal");
	}
?>