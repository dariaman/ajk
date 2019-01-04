<?php
include_once('../includes/ajk6106.php');
include_once('../includes/db.php');
include_once ("../includes/functions.php");
global $database;
$database = new db();
connect();

if (isset($_POST['download'])) {
  if($_POST['download']==1){
      header("Content-type: application/vnd-ms-excel");
      header("Content-Disposition: attachment; filename=DataDistribusi_".date('YmdHis').".xls");
    }else{
      include_once ("ui.php");
      connect();
    }
}else{
  include_once ("ui.php");
  connect();
}

$polisraw = isset($_POST['idpolis']) ? $_POST['idpolis'] : '';
$tglakad = isset($_POST['tglakad']) ? $_POST['tglakad'] : '';

$produk=$database->doQuery('SELECT * FROM fu_ajk_polis WHERE del IS NULL AND id_cost=1');

?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
        <br />
      <th width="100%" align="left">DATA DISTRIBUSI DEBITUR AJK PT BANK BUKOPIN, TBK </th>
    </tr>
  </tbody>
</table>

<?php
if (isset($_POST['download']) || !isset($_POST['download'])) {
  if($_POST['download']==0){

?>

<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>" name="postform" id="print-form">
	<input type="hidden" name="id_cost" value="1">
  <input type="hidden" id="download" name="download" value="0">
	<table border="0" cellpadding="1" cellspacing="0" width="100%">
  	<tbody>
      <tr>
        <td width="10%">Nama Perusahaan</td>
        <td>: PT. BANK BUKOPIN. TBK</td>
      </tr>
      <tr>
        <td>Nama Produk <font color="red">*</font></td>
        <td>:
          <select name="idpolis">
            <option value="">---ALL---</option>
            <?php while($p = mysql_fetch_array($produk)) { ?>
              <option value="<?= $p['id'].'|'.$p['nmproduk'] ?>" <?= $polisraw==$p['id'].'|'.$p['nmproduk'] ? 'selected' : '' ?>><?= $p['nmproduk'] ?></option>
            <?php } ?>
          </select>
        </td>
      </tr>
  	     <tr>
           <td>Tanggal Cut Off <font color="red">*</font> </td>
		       <td> : <input type="date" name="tglakad" id="tglakad" class="tanggal hasDatepicker" size="10" value="<?= $tglakad ?>" required>
	         </td>
  	</tr>
  	<tr>
      <td colspan="2">
        <input type="hidden" name="re" value="datadistribusi">
        <input id="submit" type="submit" value="Cari">
        <input id="print" type="submit" value="Download">
        <!-- <a id="print" href="#">Download</a> -->
      </td>
    </tr>
  	</tbody>
  </table>
</form>

<?php }} ?>

<?php

  if(isset($_POST['re'])){
    if($_POST['re']=='datadistribusi'){
      $polis = explode('|',$polisraw);
      $idpolis = $polis[0];
      $nmpolis = $polis[1] ? $polis[1] : 'ALL PRODUCT';

      $cond = '';
      if($idpolis){
        $cond .= ' AND id_polis = "'.$idpolis.'"';
      }

      if($tglakad){
        $tglawal=mysql_fetch_array($database->doQuery('SELECT kredit_tgl FROM adonai_ajk0109.fu_ajk_peserta where del is null and kredit_tgl is not null order by kredit_tgl asc LIMIT 1'));

        $ajkpeserta=$database->doQuery('SELECT YEAR(kredit_tgl) AS tahun, COUNT(id) AS jumlah_deb, SUM(kredit_jumlah) AS total_plafond, usia,
                                        SUM(CASE WHEN usia < 50 THEN 1 ELSE 0 END) AS deb1,
                                        SUM(CASE WHEN usia >= 50 and usia < 55 THEN 1 ELSE 0 END) AS deb2,
                                        SUM(CASE WHEN usia >= 55 and usia < 60 THEN 1 ELSE 0 END) AS deb3,
                                        SUM(CASE WHEN usia >= 60 and usia < 65 THEN 1 ELSE 0 END) AS deb4,
                                        SUM(CASE WHEN usia >= 65 and usia < 70 THEN 1 ELSE 0 END) AS deb5,
                                        SUM(CASE WHEN usia >= 70 and usia < 75 THEN 1 ELSE 0 END) AS deb6,
                                        SUM(CASE WHEN usia >= 75 and usia < 80 THEN 1 ELSE 0 END) AS deb7,

                                        SUM(CASE WHEN usia < 50 THEN kredit_jumlah ELSE 0 END) AS plafond1,
                                        SUM(CASE WHEN usia >= 50 and usia < 55 THEN kredit_jumlah ELSE 0 END) AS plafond2,
                                        SUM(CASE WHEN usia >= 55 and usia < 60 THEN kredit_jumlah ELSE 0 END) AS plafond3,
                                        SUM(CASE WHEN usia >= 60 and usia < 65 THEN kredit_jumlah ELSE 0 END) AS plafond4,
                                        SUM(CASE WHEN usia >= 65 and usia < 70 THEN kredit_jumlah ELSE 0 END) AS plafond5,
                                        SUM(CASE WHEN usia >= 70 and usia < 75 THEN kredit_jumlah ELSE 0 END) AS plafond6,
                                        SUM(CASE WHEN usia >= 75 and usia < 80 THEN kredit_jumlah ELSE 0 END) AS plafond7
                                        FROM fu_ajk_peserta
                                        WHERE kredit_tgl BETWEEN "'.$tglawal[0].'" AND "'.$tglakad.'"
                                        '.$cond.'
                                        AND del IS NULL GROUP BY YEAR(kredit_tgl);');
        $tblH = '<br /><table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tbody>
            <tr>
              <th width="100%" align="left">NAMA PRODUK : '.strtoupper($nmpolis).'</th>
            </tr>
            <tr>
              <th width="100%" align="left">Per Tanggal '.date('d F Y',strtotime($tglakad)).'</th>
            </tr>
          </tbody>
        </table>';

        $tap='<br />
        <div class="table-responsive">
        <table border="1" cellpadding="1" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th colspan="17" width="100%" align="left">JUMLAH PRODUKSI</th>
            </tr>
            <tr>
              <th rowspan="3">TAHUN</th>
              <th colspan="2">TOTAL</th>
              <th colspan="14">USIA SAAT MASUK</th>
            </tr>
            <tr>
              <th rowspan="2">JUMLAH DEB</th>
              <th rowspan="2">PLAFOND</th>
              <th colspan="2">< 50</th>
              <th colspan="2">50 < U < 55</th>
              <th colspan="2">55 < U < 60</th>
              <th colspan="2">60 < U < 65</th>
              <th colspan="2">65 < U < 70</th>
              <th colspan="2">70 < U < 75</th>
              <th colspan="2">75 < U < 80</th>
            </tr>
            <tr>
              <th>DEB</th>
              <th>PLAFOND</th>
              <th>DEB</th>
              <th>PLAFOND</th>
              <th>DEB</th>
              <th>PLAFOND</th>
              <th>DEB</th>
              <th>PLAFOND</th>
              <th>DEB</th>
              <th>PLAFOND</th>
              <th>DEB</th>
              <th>PLAFOND</th>
              <th>DEB</th>
              <th>PLAFOND</th>
            </tr>
          </thead>
          <tbody>';
            while($ap = mysql_fetch_array($ajkpeserta)) {

              $tap .= '<tr>';
                $tap .= '<td style="text-align:center"><b>'.$ap['tahun'].'</b></td>';
                $tap .= '<td style="text-align:right">'.duit($ap['jumlah_deb']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['total_plafond']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['deb1']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['plafond1']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['deb2']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['plafond2']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['deb3']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['plafond3']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['deb4']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['plafond4']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['deb5']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['plafond5']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['deb6']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['plafond6']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['deb7']).'</td>';
                $tap .= '<td style="text-align:right">'.duit($ap['plafond7']).'</td>';
              $tap.='</tr>';
            }

          $tap .= '</tbody>
        </table>
        </div>';

        $ajkpesertaum=$database->doQuery('SELECT YEAR(kredit_tgl) AS tahun, COUNT(fu_ajk_peserta.id_peserta) AS jumlah_deb, SUM(fu_ajk_cn.tuntutan_klaim) AS total_klaim, usia,
                                        SUM(CASE WHEN usia < 50 THEN 1 ELSE 0 END) AS deb1,
                                        SUM(CASE WHEN usia >= 50 and usia < 55 THEN 1 ELSE 0 END) AS deb2,
                                        SUM(CASE WHEN usia >= 55 and usia < 60 THEN 1 ELSE 0 END) AS deb3,
                                        SUM(CASE WHEN usia >= 60 and usia < 65 THEN 1 ELSE 0 END) AS deb4,
                                        SUM(CASE WHEN usia >= 65 and usia < 70 THEN 1 ELSE 0 END) AS deb5,
                                        SUM(CASE WHEN usia >= 70 and usia < 75 THEN 1 ELSE 0 END) AS deb6,
                                        SUM(CASE WHEN usia >= 75 and usia < 80 THEN 1 ELSE 0 END) AS deb7,

                                        SUM(CASE WHEN usia < 50 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim1,
                                        SUM(CASE WHEN usia >= 50 and usia < 55 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim2,
                                        SUM(CASE WHEN usia >= 55 and usia < 60 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim3,
                                        SUM(CASE WHEN usia >= 60 and usia < 65 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim4,
                                        SUM(CASE WHEN usia >= 65 and usia < 70 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim5,
                                        SUM(CASE WHEN usia >= 70 and usia < 75 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim6,
                                        SUM(CASE WHEN usia >= 75 and usia < 80 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim7
                                        FROM fu_ajk_peserta
                                        INNER JOIN fu_ajk_klaim ON fu_ajk_klaim.id_peserta = fu_ajk_peserta.id_peserta
                                        INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
                                        WHERE kredit_tgl BETWEEN "'.$tglawal[0].'" AND "'.$tglakad.'"
                                        '.$cond.'
                                        AND fu_ajk_peserta.del IS NULL GROUP BY YEAR(kredit_tgl);');

                  $tum='<br /><table border="1" cellpadding="1" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th colspan="17" width="100%" align="left">KLAIM BERDASARKAN USIA MASUK</th>
                      </tr>
                      <tr>
                        <th rowspan="3">TAHUN</th>
                        <th colspan="2">TOTAL</th>
                        <th colspan="14">USIA SAAT MASUK</th>
                      </tr>
                      <tr>
                        <th rowspan="2">JUMLAH DEB</th>
                        <th rowspan="2">NILAI KLAIM</th>
                        <th colspan="2">< 50</th>
                        <th colspan="2">50 < U < 55</th>
                        <th colspan="2">55 < U < 60</th>
                        <th colspan="2">60 < U < 65</th>
                        <th colspan="2">65 < U < 70</th>
                        <th colspan="2">70 < U < 75</th>
                        <th colspan="2">75 < U < 80</th>
                      </tr>
                      <tr>
                        <th>DEB</th>
                        <th>NILAI KLAIM</th>
                        <th>DEB</th>
                        <th>NILAI KLAIM</th>
                        <th>DEB</th>
                        <th>NILAI KLAIM</th>
                        <th>DEB</th>
                        <th>NILAI KLAIM</th>
                        <th>DEB</th>
                        <th>NILAI KLAIM</th>
                        <th>DEB</th>
                        <th>NILAI KLAIM</th>
                        <th>DEB</th>
                        <th>NILAI KLAIM</th>
                      </tr>
                    </thead>
                    <tbody>';
                      while($um = mysql_fetch_array($ajkpesertaum)) {

                        $tum .= '<tr>';
                          $tum .= '<td style="text-align:center"><b>'.$um['tahun'].'</b></td>';
                          $tum .= '<td style="text-align:right">'.duit($um['jumlah_deb']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['total_klaim']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['deb1']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['tklaim1']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['deb2']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['tklaim2']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['deb3']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['tklaim3']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['deb4']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['tklaim4']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['deb5']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['tklaim5']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['deb6']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['tklaim6']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['deb7']).'</td>';
                          $tum .= '<td style="text-align:right">'.duit($um['tklaim7']).'</td>';
                        $tum.='</tr>';
                      }

                    $tum .= '</tbody>
                  </table>';

        $ajkpesertaud=$database->doQuery('SELECT YEAR(kredit_tgl) AS tahun, COUNT(fu_ajk_peserta.id_peserta) AS jumlah_deb, SUM(fu_ajk_cn.tuntutan_klaim) AS total_klaim, usia,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 50 THEN 1 ELSE 0 END) AS deb1,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 50 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 55 THEN 1 ELSE 0 END) AS deb2,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 55 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 60 THEN 1 ELSE 0 END) AS deb3,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 60 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 65 THEN 1 ELSE 0 END) AS deb4,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 65 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 70 THEN 1 ELSE 0 END) AS deb5,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 70 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 75 THEN 1 ELSE 0 END) AS deb6,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 75 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 80 THEN 1 ELSE 0 END) AS deb7,

                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 50 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim1,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 50 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 55 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim2,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 55 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 60 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim3,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 60 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 65 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim4,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 65 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 70 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim5,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 70 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 75 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim6,
                                    SUM(CASE WHEN REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") >= 75 and REPLACE(TIMESTAMPDIFF(YEAR,tgl_klaim,tgl_lahir),"-","") < 80 THEN fu_ajk_cn.tuntutan_klaim ELSE 0 END) AS tklaim7
                                    FROM fu_ajk_peserta
                                    INNER JOIN fu_ajk_klaim ON fu_ajk_klaim.id_peserta = fu_ajk_peserta.id_peserta
                                    INNER JOIN fu_ajk_cn ON fu_ajk_cn.id_peserta = fu_ajk_klaim.id_peserta
                                    WHERE kredit_tgl BETWEEN "'.$tglawal[0].'" AND "'.$tglakad.'"
                                    '.$cond.'
                                    AND fu_ajk_peserta.del IS NULL GROUP BY YEAR(kredit_tgl);');


        $tud='<br /><table border="1" cellpadding="1" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th colspan="17" width="100%" align="left">KLAIM BERDASARKAN USIA DOL</th>
            </tr>
            <tr>
              <th rowspan="3">TAHUN</th>
              <th colspan="2">TOTAL</th>
              <th colspan="14">USIA BERDASARKAN DOL</th>
            </tr>
            <tr>
              <th rowspan="2">JUMLAH DEB</th>
              <th rowspan="2">NILAI KLAIM</th>
              <th colspan="2">< 50</th>
              <th colspan="2">50 < U < 55</th>
              <th colspan="2">55 < U < 60</th>
              <th colspan="2">60 < U < 65</th>
              <th colspan="2">65 < U < 70</th>
              <th colspan="2">70 < U < 75</th>
              <th colspan="2">75 < U < 80</th>
            </tr>
            <tr>
              <th>DEB</th>
              <th>NILAI KLAIM</th>
              <th>DEB</th>
              <th>NILAI KLAIM</th>
              <th>DEB</th>
              <th>NILAI KLAIM</th>
              <th>DEB</th>
              <th>NILAI KLAIM</th>
              <th>DEB</th>
              <th>NILAI KLAIM</th>
              <th>DEB</th>
              <th>NILAI KLAIM</th>
              <th>DEB</th>
              <th>NILAI KLAIM</th>
            </tr>
          </thead>
          <tbody>';
            while($ud = mysql_fetch_array($ajkpesertaud)) {

              $tud .= '<tr>';
                $tud .= '<td style="text-align:center"><b>'.$ud['tahun'].'</b></td>';
                $tud .= '<td style="text-align:right">'.duit($ud['jumlah_deb']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['total_klaim']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['deb1']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['tklaim1']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['deb2']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['tklaim2']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['deb3']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['tklaim3']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['deb4']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['tklaim4']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['deb5']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['tklaim5']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['deb6']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['tklaim6']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['deb7']).'</td>';
                $tud .= '<td style="text-align:right">'.duit($ud['tklaim7']).'</td>';
              $tud.='</tr>';
            }

          $tud .= '</tbody>
        </table>';
        //test
        $ajkpesertapl=$database->doQuery('SELECT YEAR(kredit_tgl) AS tahun, COUNT(id) AS jumlah_deb, SUM(kredit_jumlah) AS total_plafond, usia,
                                        SUM(CASE WHEN kredit_jumlah < 25000000 THEN 1 ELSE 0 END) AS deb1,
                                        SUM(CASE WHEN kredit_jumlah >= 25000000 and kredit_jumlah < 50000000 THEN 1 ELSE 0 END) AS deb2,
                                        SUM(CASE WHEN kredit_jumlah >= 50000000 and kredit_jumlah < 100000000 THEN 1 ELSE 0 END) AS deb3,
                                        SUM(CASE WHEN kredit_jumlah >= 100000000 and kredit_jumlah < 150000000 THEN 1 ELSE 0 END) AS deb4,
                                        SUM(CASE WHEN kredit_jumlah >= 150000000 and kredit_jumlah < 200000000 THEN 1 ELSE 0 END) AS deb5,
                                        SUM(CASE WHEN kredit_jumlah >= 200000000 and kredit_jumlah < 250000000 THEN 1 ELSE 0 END) AS deb6,
                                        SUM(CASE WHEN kredit_jumlah >= 250000000 and kredit_jumlah < 300000000 THEN 1 ELSE 0 END) AS deb7,

                                        SUM(CASE WHEN kredit_jumlah < 25000000 THEN kredit_jumlah ELSE 0 END) AS plafond1,
                                        SUM(CASE WHEN kredit_jumlah >= 25000000 and kredit_jumlah < 50000000 THEN kredit_jumlah ELSE 0 END) AS plafond2,
                                        SUM(CASE WHEN kredit_jumlah >= 50000000 and kredit_jumlah < 100000000 THEN kredit_jumlah ELSE 0 END) AS plafond3,
                                        SUM(CASE WHEN kredit_jumlah >= 100000000 and kredit_jumlah < 150000000 THEN kredit_jumlah ELSE 0 END) AS plafond4,
                                        SUM(CASE WHEN kredit_jumlah >= 150000000 and kredit_jumlah < 200000000 THEN kredit_jumlah ELSE 0 END) AS plafond5,
                                        SUM(CASE WHEN kredit_jumlah >= 200000000 and kredit_jumlah < 250000000 THEN kredit_jumlah ELSE 0 END) AS plafond6,
                                        SUM(CASE WHEN kredit_jumlah >= 250000000 and kredit_jumlah < 300000000 THEN kredit_jumlah ELSE 0 END) AS plafond7
                                        FROM fu_ajk_peserta
                                        WHERE kredit_tgl BETWEEN "'.$tglawal[0].'" AND "'.$tglakad.'"
                                        '.$cond.'
                                        AND del IS NULL GROUP BY YEAR(kredit_tgl);');


                              $pla='<br /><table border="1" cellpadding="1" cellspacing="0" width="100%">
                              <thead>
                              <tr>
                              <th colspan="17" width="100%" align="left">PRODUKSI PER PLAFOND</th>
                              </tr>
                              <tr>
                              <th rowspan="3">TAHUN</th>
                              <th colspan="2">TOTAL</th>
                              <th colspan="14">PLAFOND</th>
                              </tr>
                              <tr>
                              <th rowspan="2">JUMLAH DEB</th>
                              <th rowspan="2">NILAI PLAFOND</th>
                              <th colspan="2">PLAFOND < 25.000.000</th>
                              <th colspan="2">25.000.000 < PLAFOND < 50.000.000</th>
                              <th colspan="2">50.000.000 < PLAFOND < 100.000.000</th>
                              <th colspan="2">100.000.000 < PLAFOND < 150.000.000</th>
                              <th colspan="2">150.000.000 < PLAFOND < 200.000.000</th>
                              <th colspan="2">200.000.000 < PLAFOND < 250.000.000</th>
                              <th colspan="2">250.000.000 < PLAFOND < 300.000.000</th>
                              </tr>
                              <tr>
                              <th>DEB</th>
                              <th>NILAI PLAFOND</th>
                              <th>DEB</th>
                              <th>NILAI PLAFOND</th>
                              <th>DEB</th>
                              <th>NILAI PLAFOND</th>
                              <th>DEB</th>
                              <th>NILAI PLAFOND</th>
                              <th>DEB</th>
                              <th>NILAI PLAFOND</th>
                              <th>DEB</th>
                              <th>NILAI PLAFOND</th>
                              <th>DEB</th>
                              <th>NILAI PLAFOND</th>
                              </tr>
                              </thead>
                              <tbody>';
                              while($pl = mysql_fetch_array($ajkpesertapl)) {

                              $pla .= '<tr>';
                              $pla .= '<td style="text-align:center"><b>'.$pl['tahun'].'</b></td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['jumlah_deb']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['total_plafond']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['deb1']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['plafond1']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['deb2']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['plafond2']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['deb3']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['plafond3']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['deb4']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['plafond4']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['deb5']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['plafond5']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['deb6']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['plafond6']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['deb7']).'</td>';
                              $pla .= '<td style="text-align:right">'.duit($pl['plafond7']).'</td>';
                              $pla.='</tr>';
                              }

                              $pla .= '</tbody>
                              </table>';     

        echo $tblH;
        echo $tap;
        echo $tum;
        echo $tud;
        echo $pla;
      }
    }
  }
?>
<script>
$(function() {
  $('#print').click(function(){
    $('#download').val(1);
    $('#print-form').submit();
  });

  $('#submit').click(function(){
    $('#download').val(0);
    $('#print-form').submit();
  });


});
</script>
