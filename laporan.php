<?php

error_reporting(0);
require "db_connect.php";
$periode = $_GET['periode'];

if($periode != '') {
    $periode = $_GET['periode'];
} else {
    $periode = date('Y-m');
}

$exp = explode("-",$_GET['periode']);
$periode_tahun =  $exp[0];
$periode_bulan =  $exp[1];

$sql_cekperiode = "SELECT jam FROM log_jambulan WHERE periode='".$periode."'";
$query_cekperiode = mysqli_query($connection, $sql_cekperiode);
$cekperiode = mysqli_fetch_row($query_cekperiode);

$sql = "SELECT nama FROM pegawai WHERE aktif=1 ORDER BY nama";
$query = mysqli_query($connection, $sql);
while($row = mysqli_fetch_array($query)) {
    $sqllaporan = "SELECT nip,
    /*SEC_TO_TIME( SUM( TIME_TO_SEC( jam_lebih ) ) ) AS jam_lebih,
    SEC_TO_TIME( SUM( TIME_TO_SEC( jam_kurang ) ) ) AS jam_kurang,*/
    (SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( jam_lebih ) ) ) FROM log_presensi WHERE spl='1' AND ahad='0'  AND  nip='".$row['nama']."' AND periode='".$periode."') AS jam_lembur,
    (SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( total_jam ) ) ) FROM log_presensi WHERE nip='".$row['nama']."' AND periode='".$periode."') AS total_jam,
    (SELECT SUM(spl) FROM log_presensi WHERE ahad='0' AND spl='1' AND nip='".$row['nama']."' AND periode='".$periode."') AS lembur,
    (SELECT SUM(ahad) FROM log_presensi WHERE ahad='1' AND spl='1' AND nip='".$row['nama']."' AND periode='".$periode."') AS ahad,
    (SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( total_jam ) ) ) FROM log_presensi WHERE ahad='1' AND nip='".$row['nama']."' AND periode='".$periode."') AS jam_ahad
    FROM log_presensi WHERE nip='".$row['nama']."' and periode='".$periode."' GROUP BY nip";
    $querylaporan = mysqli_query($connection, $sqllaporan);
    $laporan = mysqli_fetch_array($querylaporan);

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
                $jam_kurang= "0";
            }
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
        }
    } else {
        $jam_kurang= "0";
    }

    $data .="
    <tr>
        <td>".$row['nip']."</td>
        <td><a class=\"link\" href=\"lihat-absen.php?nama=".$row['nama']."\" target=\"_blank\">".$row['nama']."</td>
        <td>".$jam_kurang."</td>
        <td>".$jam_lebih."</td>
        <td>".$jam_lembur."</td>
        <!--<td>".$laporan['lembur']."</td>
        <td>".$laporan['ahad']."</td>-->
        <td>".$jam_ahad."</td>
        <td>".$total_jam."</td>
        <td>".$cekperiode[0]." Jam</td>
        <td><a href=\"grafik.php?nama=".$row['nama']."&periode=".$periode_tahun."-".$periode_bulan."\" target=\"_blank\"><img width=\"18\" src=\"connection.png\"></a></td>
    </tr>
    ";
}

?>

<!doctype>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="menu">
        <a class="btn-menu" href="index.php">Kembali</a>
    </div>

    <div class="wrapper">

        <div class="menu">
            <input class="jambulan clock" name="periode" type="hidden" value="<?php echo $periode; ?>">
            Jam bulan ini:
            <input class="jambulan clock" name="jambulan" type="text" value="<?php echo $cekperiode[0]; ?>" placeholder="Jam bulan ini">
        </div>

        <div class="table-responsive-vertical shadow-z-1">
          <!-- Table starts here -->
          <div class="order">
              <select name="bulan">
                  <option value="<?php echo $periode_bulan; ?>"><?php $periode_bulan; ?></option>
                  <option value="01">Januari</option>
                  <option value="02">Februari</option>
                  <option value="03">Maret</option>
                  <option value="04">April</option>
                  <option value="05">Mei</option>
                  <option value="06">Juni</option>
                  <option value="07">Juli</option>
                  <option value="08">Agustus</option>
                  <option value="09">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
              </select>

              <select name="tahun">
                  <option value="<?php echo $periode_tahun; ?>"><?php echo $periode_tahun; ?></option>
                  <option value="2016">2016</option>
                  <option value="2017">2017</option>
                  <option value="2018">2018</option>
                  <option value="2019">2019</option>
                  <option value="2020">2020</option>
              </select>

              <button type="button" onclick="orderBy()" name="button">Lihat Laporan</button>
          </div>
          <div class="order">
              <a href="export.php?periode=<?php echo $periode; ?>" target="_blank">Export to Excel</a>
          </div>
          <table id="table" class="table table-hover table-mc-indigo">
              <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jam Kurang</th>
                    <th>Jam Lebih</th>
                    <th>Jam Lembur</th>
                    <!--<th>Lembur Biasa</th>
                    <th>Lembur Ahad</th>-->
                    <th>Jam Lembur Ahad</th>
                    <th>Total Jam</th>
                    <th>Jam Bulan ini</th>
                    <th></th>
                </tr>
              </thead>
              <tbody>
                  <?php echo $data; ?>
              </tbody>
            </table>
          </div>

    </div>

    <script src="jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="jquery.dataTables.js"></script>
    <script>
    $(document).ready(function () {

        $('select[name="bulan"]').val(<?php echo $periode_bulan; ?>);

        $('#table').DataTable();

        $('.jambulan').keypress(function (e) {
            if (e.which == 13) {
                var jambulan = $(this).val();
                var periode = $('input[name=periode]').val();

                $.ajax({
                    method: 'GET',
                    data: {
                        jam:jambulan,
                        periode:periode
                    },
                    url: 'apijambulan.php',
                    success: function(result) {
                        if(result=='success') {
                            location.reload();
                        } else {
                            alert(result);
                        }
                    }
                });

            return false;
            }
        });


    });

    function orderBy() {
        var bulan = $('select[name="bulan"]').val();
        var tahun = $('select[name="tahun"]').val();
        window.location.href = 'laporan.php?periode='+tahun+"-"+bulan;
    }
    </script>
</body>
</html>
