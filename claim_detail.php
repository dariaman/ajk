<?php
ob_start();
session_start();
if(!isset($_SESSION['ri_usernm']) && !isset($_SESSION['ri_passwd'])) header("location:login.php");
include 'include/conf.inc';
include 'include/my.function.php';
include 'include/mysqli.class.php';
include 'include/excel_reader2.inc';

$db = new database();
$func = new getfunc_();
$dbfunc = new getdbfunc_();
$pageku = new getpage_();

$pageku->_exec();
$pageku ->_header();


$results = $db->queryku("
			
			SELECT
			fu_ajk_asuransi.`name` AS asuransi,
			fu_ajk_cn.id,
			fu_ajk_polis.id AS id_polis,
			fu_ajk_cn.id_cost,
			fu_ajk_cn.id_cn,
			fu_ajk_dn.dn_kode,
			fu_ajk_peserta.id_peserta,
			fu_ajk_peserta.nama,
			`fu_ajk_peserta`.`gender`,
			`fu_ajk_peserta`.`tgl_lahir`, 
			`fu_ajk_peserta`.`usia`,
			`fu_ajk_peserta`.`nip`,
			`fu_ajk_peserta`.`no_ktp`,
			fu_ajk_peserta.kredit_tgl,
			IF(fu_ajk_peserta.type_data='SPK', fu_ajk_peserta.kredit_tenor * 12, fu_ajk_peserta.kredit_tenor) AS tenor,
			fu_ajk_peserta.kredit_akhir,
			fu_ajk_peserta.kredit_jumlah,
			fu_ajk_peserta.spaj AS spak,
			fu_ajk_cn.tgl_claim,
			fu_ajk_cn.premi,
			`fu_ajk_klaim`.`tempat_meninggal`,
		    `fu_ajk_namapenyakit`.`namapenyakit`,
		    `fu_ajk_klaim`.`diagnosa`,
		    `fu_ajk_klaim`.`ket_dokter`,
			fu_ajk_cn.confirm_claim,
			fu_ajk_cn.tuntutan_klaim,
			fu_ajk_cn.total_claim,
			fu_ajk_cn.tgl_byr_claim,
			fu_ajk_polis.nmproduk,
			fu_ajk_peserta.cabang,
			fu_ajk_klaim.tgl_kirim_dokumen,
			IF(fu_ajk_cn.tgl_bayar_asuransi IS NULL OR fu_ajk_cn.tgl_bayar_asuransi='0000-00-00','UNPAID' ,CONCAT('PAID @ ',fu_ajk_cn.tgl_bayar_asuransi)) AS status_bayar,
			fu_ajk_cn.input_date,
			fu_ajk_cn.total_bayar_asuransi,
			fu_ajk_klaim_status.status_klaim,
			fu_ajk_klaim.estimasi_bayar,
			if(fu_ajk_klaim.tgl_estimasi_bayar is null or fu_ajk_klaim.tgl_estimasi_bayar='0000-00-00','-',fu_ajk_klaim.tgl_estimasi_bayar) as tgl_estimasi_bayar,
			if(fu_ajk_klaim.tgl_lapor_klaim is null or fu_ajk_klaim.tgl_lapor_klaim='0000-00-00','ADONAI','ASURANSI') as proses,
			fu_ajk_cn.status_bayar as jenis_klaim
			FROM fu_ajk_cn
			INNER JOIN fu_ajk_peserta ON fu_ajk_cn.id_peserta = fu_ajk_peserta.id_peserta
			INNER JOIN fu_ajk_costumer ON fu_ajk_cn.id_cost = fu_ajk_costumer.id
			INNER JOIN fu_ajk_polis ON fu_ajk_cn.id_nopol = fu_ajk_polis.id
			INNER JOIN fu_ajk_dn ON fu_ajk_cn.id = fu_ajk_dn.id
			INNER JOIN fu_ajk_asuransi ON fu_ajk_dn.id_as = fu_ajk_asuransi.id
			LEFT JOIN fu_ajk_klaim ON fu_ajk_cn.id_cost = fu_ajk_klaim.id_cost AND fu_ajk_cn.id_dn = fu_ajk_klaim.id_dn AND fu_ajk_cn.id = fu_ajk_klaim.id_cn AND fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
			LEFT JOIN fu_ajk_klaim_status ON fu_ajk_klaim.id_klaim_status = fu_ajk_klaim_status.id
			LEFT JOIN `fu_ajk_namapenyakit` ON (`fu_ajk_klaim`.`sebab_meninggal` = `fu_ajk_namapenyakit`.`id`)
			WHERE fu_ajk_cn.type_claim = 'Death' AND fu_ajk_cn.del IS NULL  AND fu_ajk_cn.id=".$func->_decrypt($_GET['idp']));

$q_=mysqli_fetch_array($results);

$status_bayar='<small><label class="label label-success">'.$q_['status_bayar'].'</label></small>';
if($q_['status_bayar']=='UNPAID'){
	$status_bayar='<small><label class="label label-warning">'.$q_['status_bayar'].'</label></small>';
}

$result=$db->queryku("SELECT
    `fu_ajk_spak`.`spak`
    , `fu_ajk_spak`.`photo_spk`
    , `fu_ajk_spak_form`.*
FROM
    `fu_ajk_spak`
    INNER JOIN `fu_ajk_spak_form` 
        ON (`fu_ajk_spak`.`id` = `fu_ajk_spak_form`.`idspk`)
	where `fu_ajk_spak`.`status`='Realisasi' and `fu_ajk_spak`.`spak`='".$q_['spak']."'");
$b_=mysqli_fetch_array($result);
?>

        <div id="wrapper">
            <div id="layout-static">
                <div class="static-content-wrapper">
                    <div class="static-content">
                        
                        <div class="page-content">
                            <ol class="breadcrumb">
							                                
							<li><a href="index.html">Home</a></li>
							<li class="active"><a href="profile.html">Data Debitur - Klaim</a></li>

                            </ol>
                            <div class="page-heading">
                                <h1>Data Debitur - Klaim</h1>
                            </div>
                            <div class="container-fluid">
								                                 
								<div data-widget-group="group1">
									<div class="row">
										<div class="col-sm-3">
											<div class="panel panel-default" data-widget='{"draggable": "false"}'>
												<div class="panel-heading">
													<h2>Lampiran Klaim</h2>
													<div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
													<div class="options">
													</div>
												</div>
											  <div class="panel-body">
											  	<div class="table-responsive">
											      <table class="table">
											        <tbody>
												    <?php 
												    $resultnya = $db->queryku("
											    		SELECT
														     `fu_ajk_dokumenklaim_bank`.`id`
														    , `fu_ajk_dokumenklaim`.`nama_dok`
														    , `fu_ajk_dokumenklaim`.`view`
														    , `fu_ajk_dokumenklaim_bank`.`id_bank`
														    , `fu_ajk_dokumenklaim_bank`.`id_produk`
														    , `fu_ajk_dokumenklaim_bank`.`input_date`
														FROM
														    `fu_ajk_dokumenklaim_bank`
														    INNER JOIN `fu_ajk_dokumenklaim` ON (`fu_ajk_dokumenklaim_bank`.`id_dok` = `fu_ajk_dokumenklaim`.`id`)
														WHERE  `fu_ajk_dokumenklaim_bank`.`del` IS NULL
														AND `fu_ajk_dokumenklaim_bank`.id_bank=".$q_['id_cost'] ." AND `fu_ajk_dokumenklaim_bank`.id_produk=". $q_['id_polis'] ."
														ORDER BY `fu_ajk_dokumenklaim`.`id` ASC");
											    
											    while ($x_ = mysqli_fetch_array($resultnya)) {
											    	$queryku="SELECT * FROM fu_ajk_klaim_doc WHERE id_pes='".$q_['id_peserta']."' AND id_cost='".$q_['id_cost']."' AND dokumen=".$x_['id']." AND del IS NULL";
											    	$resultku=$db->queryku($queryku);
											    	$z_=mysqli_fetch_array($resultku);
											    	$jmlz_=mysqli_num_rows($resultku);
											    	//$CekDokumen = mysql_fetch_array($database->doQuery('SELECT * FROM fu_ajk_klaim_doc WHERE id_pes="' . $q_['id_peserta'] . '" AND id_cost="' . $rdok['id_bank'] . '" AND dokumen="' . $rdok['id'] . '" AND del IS NULL'));
											    	if ($jmlz_==0) {
											    		echo '<tr>
													            <th>'.$x_['nama_dok'].'
													            <br><small class="text-danger"><i>Dokumen tidak dipilih</i></small></th>
													          </tr>';
											    	} elseif (!is_null($z_['dokumen']) AND $z_['nama_dokumen'] != "") {
											    		echo '<tr>
													            <th>'.$x_['nama_dok'].'<br>
													            <a target="_blank" href="../ajk/ajk_file/klaim/'.$z_['nama_dokumen'].'"><smal>'.$z_['nama_dokumen'].'</small></a></th>
													          </tr>';
											    	} else {
											    		echo '<tr>
													            <th>'.$x_['nama_dok'].'
													            <small class="text-danger"><font color="red">*</font>' . $errno[$i] . '</small></th>
													          </tr>';
											    		
											    	}
											    	
											    }
											    
											    ?>
											    </tbody>
											    </table>
											    </div>
											  </div>
											</div><!-- panel -->
										</div><!-- col-sm-3 -->
										<div class="col-sm-6">
													<div class="panel panel-default">
													    <div class="panel-heading">
													    	<h2>Informasi Debitur</h2>
													    </div>
														<div class="panel-body">
													      	<div class="about-area">
														      	<h4><?php echo $q_['nama'].' <span class="text-danger">['.$q_['dn_kode'].']</span>'; ?></h4>
														      	
															</div>
															<div class="row">
																<div class="col-md-4">
																	<?php 
																	$query="select * from fu_ajk_photo where id_peserta='".$q_['id_peserta']."'";
																	$resultme=$db->queryku($query);
																	$o_=mysqli_fetch_array($resultme);
																	?>
																	<img src="../ajk/ajk_file/_spak/<?php echo $o_['photo_dekl_1']; ?>" class="img-circle">
																</div>
															</div>
															<div class="about-area">
																    <div class="table-responsive">
																      <table class="table">
																        <tbody>
																          <tr>
																            <td width="20%">ID Peserta</td>
																            <td><a href="#">: <?php echo $q_['id_peserta']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Nama Debitur</td>
																            <td><a href="#">: <?php echo $q_['nama']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Jenis Kelamin</td>
																            <td><a href="#">: <?php echo $q_['gender']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Tanggal Lahir</td>
																            <td>: <?php echo $q_['tgl_lahir']; ?></td>
																          </tr>
																          <tr>
																            <td width="20%">Usia</td>
																            <td>: <?php echo $q_['usia']; ?> Tahun</td>
																          </tr>
																          <tr>
																            <td width="20%">Alamat</td>
																            <td>: <?php echo $b_['alamat']; ?></td>
																          </tr>
																          <tr>
																            <td width="20%">Tinggi Badan</td>
																            <td>: <?php echo $b_['tinggibadan']; ?> Cm</td>
																          </tr>
																          <tr>
																            <td width="20%">Berat Badan</td>
																            <td>: <?php echo $b_['beratbadan']; ?> Kg</td>
																          </tr>
																          
																          <tr>
																            <td width="20%">No. KTP</td>
																            <td>: <?php echo $q_['no_ktp']; ?></td>
																          </tr>
																        </tbody>
																      </table>
																    </div>
															</div>
															
														</div>
													</div>
													<div class="panel panel-default">
													    <div class="panel-heading">
													    	<h2>Informasi Kredit</h2>
													    </div>
														<div class="panel-body">
																    <div class="table-responsive">
																      <table class="table about-table">
																        <tbody>
																          <tr>
																            <td width="20%">Periode Kredit</td>
																            <td>: <?php echo $q_['kredit_tgl'].' s.d '.$q_['kredit_akhir']; ?></td>
																          </tr>
																          <tr>
																            <td width="20%">Tenor</td>
																            <td>: <?php echo $q_['tenor']/12 .' Tahun'; ?></td>
																          </tr>
																          <tr>
																            <td width="20%">Plafond</td>
																            <td>: <?php echo 'Rp. '.number_format($q_['kredit_jumlah'],2,",","."); ?></td>
																          </tr>
																          <tr>
																            <td width="20%">Cabang</td>
																            <td>: <?php echo strtoupper($q_['cabang']); ?></td>
																          </tr>
																        </tbody>
																      </table>
																    </div>
														</div>
													</div>
												<div class="panel panel-default">
													    <div class="panel-heading">
													    	<h2>Informasi Klaim</h2>
													    </div>
														<div class="panel-body">
																    <div class="table-responsive">
																      <table class="table about-table">
																        <tbody>
																          <tr>
																            <td width="20%">Tanggal Meninggal</td>
																            <td><a href="#">: <?php echo $q_['tgl_claim']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Lokasi Meninggal</td>
																            <td><a href="#">: <?php echo $q_['tempat_meninggal']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Penyebab Meninggal</td>
																            <td><a href="#">: <?php echo $q_['namapenyakit']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Keterangan Diagnosa</td>
																            <td><a href="#">: <?php echo $q_['diagnosa']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Keterangan Dokter Adonai</td>
																            <td><a href="#">: <?php echo $q_['ket_dokter']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Opini Medis</td>
																            <td><a href="#">: <?php echo number_format($q_['tuntutan_klaim'],2,",","."); ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Tanggal Klaim</td>
																            <td><a href="#">: <?php echo $q_['tgl_kirim_dokumen']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Tuntutan Klaim</td>
																            <td><a href="#">: <?php echo 'Rp.'.number_format($q_['tuntutan_klaim'],2,",","."); ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Klaim Dibayar</td>
																            <td><a href="#">: <?php echo 'Rp.'.number_format($q_['total_bayar_asuransi'],2,",","."); ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Jenis Klaim</td>
																            <td><a href="#">: <?php echo $q_['jenis_klaim']; ?></a></td>
																          </tr>
																          <tr>
																            <td width="20%">Status Pembayaran</td>
																            <td>: <?php echo $status_bayar; ?></td>
																          </tr>
																        </tbody>
																      </table>
																    </div>
														</div>
													</div>
											</div><!-- col-sm-6 -->
											<div class="col-md-3">
											<form role="form" class="form-horizontal" method="post"  enctype="multipart/form-data">
												<div class="panel panel-default">
											    
												<div class="panel-body">
														    <div class="table-responsive">
														      <span class="text-default">Tuntutan Klaim</span>
														      <h2><span  class="text-warning"><?php echo 'Rp. '.number_format($q_['tuntutan_klaim'],2,",","."); ?></span></h2>
														      <span class="text-default">Klaim Dibayar</span>
														      <h2><span class="text-success"><?php echo 'Rp.'.number_format($q_['total_bayar_asuransi'],2,",","."); ?></span></h2>
														      <h2><?php echo $status_bayar; ?></h2>
														      
														     <?php 
														      
														      if($q_['status_bayar']=='UNPAID'){
														      	
														      }
														      
														      ?>
														    </div>
												</div>
											</div>
											<?php 
											if($q_['status_bayar']=='UNPAID' && $q_['proses']=='ASURANSI'){
											echo '<div class="panel" style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
												<div class="panel-heading">
													<div class="options">
														<ul class="nav nav-tabs">
															<li class="active"><a href="#tab-1-1" data-toggle="tab" aria-expanded="true">Informasi Estimasi</a></li>
															<li class=""><a href="#tab-1-2" data-toggle="tab" aria-expanded="false">Update Estimasi</a></li>
															
														</ul>
													</div>
												</div>
													<div class="panel-body">
													<div class="tab-content">
														<div class="tab-pane active" id="tab-1-1">
															 <span class="text-default">Estimasi Bayar</span>
														      <h2><span class="text-success">Rp.'.number_format($q_['estimasi_bayar'],2,",",".").'</span></h2>
														      <span class="text-default">Tanggal Estimasi Bayar</span>
														      <h2><span class="text-success">'.$q_['tgl_estimasi_bayar'].'</span></h2>
														</div>
														<div class="tab-pane" id="tab-1-2">
															<div class="form-group">
																<div class="col-sm-12">
																	<label class="control-label">Jumlah Estimasi Bayar</label>
																</div>
																<div class="col-sm-12">
																	<input type="text" class="form-control" name="estimasi_bayar" value="'.$q_['tuntutan_klaim'].'">
																</div>
															</div>
															<div class="form-group">
																<div class="col-sm-12">
																	<label class="control-label">Tanggal Estimasi bayar</label>
																</div>
																<div class="col-sm-12">
																	<input type="text" class="form-control input-sm" id="datepicker" name="tgl_estimasi_bayar" value="'.date("m/d/Y").'">
																</div>
															</div>
															<hr>
															<div class="form-group">
																<div class="col-sm-12">
																	<button type="submit" name="save_estimasi" class="btn btn-sm btn-info pull-right"><i class="ti ti-save"></i> Update</button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>';
												
											
											}
											?>
											<div class="panel panel-default">
												<div class="panel-heading">
											    	<h2>Comment</h2>
											    </div>
												<div class="panel-body">
													<?php 
													$query="select fu_ajk_comment.*,`pengguna`.`nm_lengkap` from fu_ajk_comment 
																INNER JOIN `pengguna` ON (`fu_ajk_comment`.`input_by` = `pengguna`.`nm_user`)
																where fu_ajk_comment.subject_comment='klaim' and fu_ajk_comment.table_id=".$func->_decrypt($_GET['idp'])."
															order by fu_ajk_comment.id asc";
													$result=$db->queryku($query);
													while($rdata=mysqli_fetch_array($result)){
														if($rdata['input_by']==$func->_decrypt($_SESSION['ri_usernm'])){
															echo '<div class="row">
												                <blockquote class="pull-right">
												                    <p>'.$rdata['comment'].'</p>
												                    <small>'.$rdata['nm_lengkap'].'</small>
												                </blockquote></div>';
														}else{
															echo '<div class="row">
																<blockquote>
												                    <p>'.$rdata['comment'].'</p>
												                    <small>'.$rdata['nm_lengkap'].'</small>
												                </blockquote></div>';
														}
													}
													
													?>
													<div class="form-group">
														<div class="col-sm-12">
															<textarea class="form-control autosize" name="comm" rows="4"></textarea>
														</div>
													</div>
													<button type="submit" class="btn btn-sm btn-info pull-right" name="comment"><i class="ti ti-plus"></i> Add New Comment</button>
												</div>
											</div>
											</form>
										</div>
									</div>
								</div>

                            </div> <!-- .container-fluid -->
                        </div> <!-- #page-content -->
                        
                    </div>
                    <footer>
				    <div class="clearfix">
				        <ul class="list-unstyled list-inline pull-left">
				       
				            <li><h6 style="margin: 0;">&copy; Copyright@2016</h6></li>
				        </ul>
				        <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
				    </div>
				</footer>
				
                </div>
            </div>
        </div>

    
   
    
<?php 
$pageku ->_footer();
?>