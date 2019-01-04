if (document.getElementById) {

<?php

//kalo mo nambahin php disini aza ... :) yu' ..
$gisURL = 'http://rahmad/';
?>

  var tree = new WebFXTree('Home', 'index.php');
  tree.setBehavior('classic');
  tree.openIcon = 'images/gohome.png';
  tree.icon = 'images/gohome.png';


	var profile = new WebFXTreeItem('Profil','profil.php');
	profile.openIcon = 'images/next.png';
	profile.icon = 'images/next.png';
	tree.add(profile);

	var manDat = new WebFXTreeItem('Manajemen Data', '#');
	manDat.openIcon = 'images/actions/finish.png';
	manDat.icon = 'images/actions/showmenu.png';

	var satuan = new WebFXTreeItem('Setup Satuan', 'SetupSatuan.php');
	satuan.openIcon = 'images/newtodo.png';
	satuan.icon = 'images/actions/terminal.png';
	tree.add(manDat);
	manDat.add(satuan);

	var Barang = new WebFXTreeItem('Setup Barang', 'SetupBarang.php');
	Barang.openIcon = 'images/newtodo.png';
	Barang.icon = 'images/actions/runprog.png';
	manDat.add(Barang);

	var Harga = new WebFXTreeItem('Setup Harga', 'SetupHarga.php');
	Harga.openIcon = 'images/newtodo.png';
	Harga.icon = 'images/actions/bookmark_add.png';
	manDat.add(Harga);

	var Customer = new WebFXTreeItem('Setup Customer', 'SetupCustomer.php');
	Customer.openIcon = 'images/newtodo.png';
	Customer.icon = 'images/menu/users.png';
	manDat.add(Customer);

	var Transak = new WebFXTreeItem('Transaksi', '#');
	Transak.openIcon = 'images/actions/finish.png';
	Transak.icon = 'images/actions/folder_new.png';
	tree.add(Transak);

	var Input = new WebFXTreeItem('Input Transaksi', 'Input.php');
	Input.openIcon = 'images/spreadsheet.png';
	Input.icon = 'images/actions/todo.png';
	Transak.add(Input);

	var viewOrd = new WebFXTreeItem('View Order', 'viewOrder.php');
	viewOrd.openIcon = 'images/spreadsheet.png';
	viewOrd.icon = 'images/actions/tooloptions.png';
	Transak.add(viewOrd);

    var viewSum = new WebFXTreeItem('View Summary', 'summary.php');
	viewSum.openIcon = 'images/spreadsheet.png';
	viewSum.icon = 'images/spreadsheet.png';
	Transak.add(viewSum);

	var  gallery = new WebFXTreeItem('Cetak Aplikasi','#');
	gallery.openIcon = 'images/actions/finish.png';
	gallery.icon = 'images/actions/filequickprint.png';
	tree.add(gallery);

	var Sales = new WebFXTreeItem('Sales Memo', 'salesmemo.php');
	Sales.openIcon = 'images/spreadsheet.png';
	Sales.icon = 'images/actions/editcopy.png';
	gallery.add(Sales);

	var faktur = new WebFXTreeItem('Faktur', 'faktur.php');
	faktur.openIcon = 'images/spreadsheet.png';
	faktur.icon = 'images/actions/klipper_dock.png';
	gallery.add(faktur);

	var surja = new WebFXTreeItem('Surat Jalan', 'suratjalan.php');
	surja.openIcon = 'images/spreadsheet.png';
	surja.icon = 'images/actions/signature.png';
	gallery.add(surja);

	var dord = new WebFXTreeItem('Delivery Order', 'DOrder.php');
	dord.openIcon = 'images/spreadsheet.png';
	dord.icon = 'images/actions/today.png';
	gallery.add(dord);

	document.write(tree);
<?
	if(eregi('index.php',$_ENV['HTTP_REFERER'])){

/*
?>
	tree.expandAll();
	siswa.collapseAll();
	guru.collapseAll();
	kurikulum.collapseAll();
	Keuangan.collapseAll();
	bantuan.collapseAll();
	lokasi.collapseAll();
	SP.collapseAll();
	<?
*/	}
	?>
}
