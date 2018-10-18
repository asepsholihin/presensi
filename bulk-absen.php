<?php

error_reporting(0);
require "db_connect.php";

$date = date('m-Y');
$datebefore = date('m-Y', strtotime('-1 month'));

$formatdate = date('Y-m');
$formatdatebefore = date('Y-m', strtotime('-1 month'));

for($tgl=25;$tgl<=31;$tgl++){
    $sql = "SELECT jam_masuk, jam_keluar, spl, ahad, catatan FROM log_presensi WHERE periode='".$formatdate."' AND nip='".$_GET['nama']."' AND tanggal='".$formatdatebefore."-".$tgl."'";
    $query = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($query);
    if($row[2] == 0) {
        $spl = '';
    } else {
        $spl = 'checked';
    }if($row[3] == 0) {
        $ahad = '';
    } else {
        $ahad = 'checked';
    }
    $show1.="
    <tr>
        <td>".$tgl."-".$datebefore." <input type=\"hidden\" name=\"tanggal".$tgl."\" value=\"".$formatdatebefore."-".$tgl."\"></td>
        <td><input class=\"clock\" name=\"masuk_tgl".$tgl."\" type=\"time\" value=\"".$row[0]."\"></td>
        <td><input class=\"clock\" name=\"pulang_tgl".$tgl."\" type=\"time\" value=\"".$row[1]."\"></td>
        <td class=\"center\"><input type=\"checkbox\" ".$spl." name=\"spl".$tgl."\" value=\"1\"></td>
        <td class=\"center\"><input type=\"checkbox\" ".$ahad." name=\"ahad".$tgl."\" value=\"1\"></td>
        <td><textarea rows=\"3\" name=\"catatan".$tgl."\" placeholder=\"catatan...\">".$row[4]."</textarea></td>
    </tr>";
}
for($tgl=1;$tgl<=24;$tgl++){
    $sql = "SELECT jam_masuk, jam_keluar, spl, ahad, catatan FROM log_presensi WHERE periode='".$formatdate."' AND nip='".$_GET['nama']."' AND tanggal='".$formatdate."-".$tgl."'";
    $query = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($query);
    if($row[2] == 0) {
        $spl = '';
    } else {
        $spl = 'checked';
    }if($row[3] == 0) {
        $ahad = '';
    } else {
        $ahad = 'checked';
    }
    $show2.="
    <tr>
        <td>".$tgl."-".$date." <input type=\"hidden\" name=\"tanggal".$tgl."\" value=\"".$formatdate."-".$tgl."\"></td>
        <td><input class=\"clock\" name=\"masuk_tgl".$tgl."\" type=\"time\" value=\"".$row[0]."\"></td>
        <td><input class=\"clock\" name=\"pulang_tgl".$tgl."\" type=\"time\" value=\"".$row[1]."\"></td>
        <td class=\"center\"><input type=\"checkbox\" ".$spl." name=\"spl".$tgl."\" value=\"1\"></td>
        <td class=\"center\"><input type=\"checkbox\" ".$ahad." name=\"ahad".$tgl."\" value=\"1\"></td>
        <td><textarea rows=\"3\" name=\"catatan".$tgl."\" placeholder=\"catatan...\">".$row[4]."</textarea></td>
    </tr>";
}
?>


<!doctype>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <style>
        #table {
            margin: auto;
            width: auto;
        }
    </style>
</head>
<body>
    <div class="menu">
        <a class="btn-menu" href="../presensi/">Kembali</a>
    </div>

    <div class="wrapper">
        <div class="table-responsive-vertical">
            <form id="form">
                <input type="hidden" name="periode" value="<?php echo $formatdate; ?>">
                <input type="hidden" name="nip" value="<?php echo $_GET['nama']; ?>">
                <table id="table" class="table table-hover table-mc-indigo">
                    <thead>
                        <tr>
                            <th class="center" colspan="6"><h2><?php echo $_GET['nama']?></h2></th>
                        </tr>
                        <tr>
                            <th class="center">Tanggal</th>
                            <th class="center">Masuk</th>
                            <th class="center">Pulang</th>
                            <th width="1">SPL</th>
                            <th width="1">Ahad</th>
                            <th class="center">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $show1.$show2.$show3; ?>
                        <tr>
                            <td class="center" colspan="6"><button class="btn success" hidden>Berhasil disimpan</button></td>
                        </tr>
                        <tr>
                            <td class="center" colspan="6"><button type="button" id="simpan" class="btn">SIMPAN</button></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <script src="jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#simpan').click(function() {
            $('.success').fadeOut('slow');
            $.post('tesapi.php', $('#form').serialize(), function(data) {
                if(data == 'success'){
                    $('.success').fadeIn('slow');
                }
            });
        });
    });
    </script>
</body>
</html>
