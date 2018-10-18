<?php

header("Content-type: application/vnd-ms-excel");
// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=absensi-".$_GET['periode'].".xls");

error_reporting(0);
require "db_connect.php";
$periode = $_GET['periode'];

if($periode != '') {
    $periode = $_GET['periode'];
} else {
    $periode = date('Y-m');
}

$sql_cekperiode = "SELECT jam FROM log_jambulan WHERE periode='".$periode."'";
$query_cekperiode = mysqli_query($connection, $sql_cekperiode);
$cekperiode = mysqli_fetch_row($query_cekperiode);

$sql = "SELECT nama FROM pegawai WHERE aktif=1 ORDER BY nama";
$query = mysqli_query($connection, $sql);
while($row = mysqli_fetch_array($query)) {
    $sqllaporan = "SELECT nip,
    /*SEC_TO_TIME( SUM( TIME_TO_SEC( jam_lebih ) ) ) AS jam_lebih,
    SEC_TO_TIME( SUM( TIME_TO_SEC( jam_kurang ) ) ) AS jam_kurang,*/
    (SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( jam_lebih ) ) ) FROM log_presensi WHERE spl='1' AND  nip='".$row['nama']."' AND periode='".$periode."') AS jam_lembur,
    (SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( total_jam ) ) ) FROM log_presensi WHERE nip='".$row['nama']."' AND periode='".$periode."') AS total_jam,
    (SELECT SUM(spl) FROM log_presensi WHERE ahad='0' AND spl='1' AND nip='".$row['nama']."' AND periode='".$periode."') AS lembur,
    (SELECT SUM(ahad) FROM log_presensi WHERE ahad='1' AND spl='1' AND nip='".$row['nama']."' AND periode='".$periode."') AS ahad,
    (SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( total_jam ) ) ) FROM log_presensi WHERE ahad='1' AND nip='".$row['nama']."' AND periode='".$periode."') AS jam_ahad
    FROM log_presensi WHERE nip='".$row['nama']."' and periode='".$periode."' GROUP BY nip";
    $querylaporan = mysqli_query($connection, $sqllaporan);
    $laporan = mysqli_fetch_array($querylaporan);

    //echo $sqllaporan;


    if($laporan['jam_lebih'] != '00:00:00' && $laporan['jam_lebih'] != '' ){
        $piecesjam_lebih = explode(":", $laporan['jam_lebih']);
        $jam_lebih = $piecesjam_lebih[0]." Jam ".$piecesjam_lebih[1]." Menit";
    } else {
        $jam_lebih = '0';
    }
    //
    // if($laporan['jam_kurang'] != '00:00:00' && $laporan['jam_kurang'] != '' ){
    //     $piecesjam_kurang = explode(":", $laporan['jam_kurang']);
    //     $jam_kurang = $piecesjam_kurang[0]." Jam ".$piecesjam_kurang[1]." Menit";
    // } else {
    //     $jam_kurang = '0';
    // }

    if($laporan['jam_lembur'] != '00:00:00' && $laporan['jam_lembur'] != '' ){
        $piecesjam_lembur = explode(":", $laporan['jam_lembur']);
        $jam_lembur = $piecesjam_lembur[0]." Jam ".$piecesjam_lembur[1]." Menit";
    } else {
        $jam_lembur = '0';
    }

    if($laporan['jam_ahad'] != '00:00:00' && $laporan['jam_ahad'] != '' ){
        $piecesjam_ahad = explode(":", $laporan['jam_ahad']);
        $jam_ahad = $piecesjam_ahad[0]." Jam ".$piecesjam_ahad[1]." Menit";
    } else {
        $jam_ahad = '0';
    }

    if($laporan['total_jam'] != '00:00:00' && $laporan['total_jam'] != '' ){
        $piecestotal_jam = explode(":", $laporan['total_jam']);
        $total_jam = $piecestotal_jam[0]." Jam ".$piecestotal_jam[1]." Menit";
    } else {
        $total_jam = '0';
    }


    $totaljam= $laporan['total_jam'];
    $jambulan= $cekperiode[0];
    if($totaljam > 0) {
        if($totaljam < $jambulan) {
            list($h,$m,$s) = explode(":",$totaljam);
            $dtAwal = mktime($h,$m,$s,"1","1","1");
            list($h,$m,$s) = explode(":",$jambulan);
            $dtAkhir = mktime($h,$m,$s,"1","1","1");
            $dtSelisih = $dtAkhir-$dtAwal;
            $totalmenit=$dtSelisih/60;
            $jam = explode(".",$totalmenit/60);
            $sisamenit= ($totalmenit/60)-$jam[0];
            $sisamenit2= floor($sisamenit*60);
            $jml_jam= $jam[0];
            if($jml_jam > 0){
                $jam_kurang= $jml_jam." Jam ".$sisamenit2." Menit";
            } else {
                $jam_kurang= "0";
            }
        } else {
            list($h,$m,$s) = explode(":",$totaljam);
            $dtAwal = mktime($h,$m,$s,"1","1","1");
            list($h,$m,$s) = explode(":",$jambulan);
            $dtAkhir = mktime($h,$m,$s,"1","1","1");
            $dtSelisih = $dtAwal - $dtAkhir;
            $totalmenit=$dtSelisih/60;
            $jam = explode(".",$totalmenit/60);
            $sisamenit= ($totalmenit/60)-$jam[0];
            $sisamenit2= floor($sisamenit*60);
            $jml_jam= $jam[0];
            if($jml_jam > 0){
                $jam_lebih= $jml_jam." Jam ".$sisamenit2." Menit";
            } else {
                $jam_lebih= "0";
            }
            $jam_kurang= "0";
        }
    } else {
        $jam_kurang= "0";
    }

    $data .="
    <tr>
        <td>".$row['nip']."</td>
        <td>".$row['nama']."</td>
        <td>".$jam_kurang."</td>
        <td>".$jam_lebih."</td>
        <td>".$jam_lembur."</td>
        <!--<td>".$laporan['lembur']."</td>
        <td>".$laporan['ahad']."</td>-->
        <td>".$jam_ahad."</td>
        <td>".$total_jam."</td>
        <td>".$cekperiode[0]." Jam</td>
    </tr>
    ";
}

?>

<!doctype>
<html>
<head>
</head>
<body>

<table border>
<thead>
  <tr>
      <th>NIP</th>
      <th>Nama</th>
      <th>Jam Kurang</th>
      <th>Jam Lebih</th>
      <th>Jam Lembur</th>
      <th>Jam Lembur Ahad</th>
      <th>Total Jam</th>
      <th>Jam Bulan ini</th>
  </tr>
</thead>
<tbody>
    <?php echo $data; ?>
</tbody>
</table>
</body>
</html>
