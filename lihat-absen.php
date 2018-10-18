<?php

error_reporting(0);
require "db_connect.php";
$datetime = new DateTime();
$formatdate = date('Y-m');

$sql = "SELECT * FROM log_presensi WHERE periode='".$formatdate."' AND nip='".$_GET['nama']."'";

$query = mysqli_query($connection, $sql);
while($row = mysqli_fetch_array($query)) {

    if($row['total_jam'] != '0'){
        $jamkerjaexp = explode(':',$row['total_jam']);
        $jamkerja = $jamkerjaexp[0]." Jam ".$jamkerjaexp[1]." Menit";
    } else {
        $jamkerja = '0';
    }

    if($row['jam_lebih'] != '0' && $row['jam_lebih'] != '0:0'){
        $pieces = explode(":", $row['jam_lebih']);
        $jam_lebih = $pieces[0]." Jam ".$pieces[1]." Menit";
    } else {
        $jam_lebih = '0';
    }

    if($row['jam_kurang'] != '0' && $row['jam_kurang'] != '0:0'){
        $pieces = explode(":", $row['jam_kurang']);
        $jam_kurang = $pieces[0]." Jam ".$pieces[1]." Menit";
    } else {
        $jam_kurang = '0';
    }

    if($row['spl'] == "1"){
        $spl = "<img width=\"18\" src=\"yes.png\">";

    } else {
        $spl = "<img width=\"10\" src=\"no.png\">";
    }
    if($row['lembur'] == "1"){
        $lembur = "<img width=\"18\" src=\"yes.png\">";

    } else {
        $lembur = "<img width=\"10\" src=\"no.png\">";
    }
    if($row['ahad'] == "1"){
        $ahad = "<img width=\"18\" src=\"yes.png\">";
        $background = "style=\"background: rgb(255, 241, 114);\"";

    } else {
        $ahad = "<img width=\"10\" src=\"no.png\">";
        $background = "";
    }



    $show.="
    <tr $background>
        <td>".$row['tanggal']."</td>
        <td>".$row['jam_masuk']."</td>
        <td>".$row['jam_keluar']."</td>
        <td>".$jamkerja."</td>
        <td class=\"center\">".$spl."</td>
        <td class=\"center\">".$lembur."</td>
        <td class=\"center\">".$ahad."</td>
        <td>".$jam_kurang."</td>
        <td>".$jam_lebih."</td>
        <td>".$row['catatan']."</td>
    </tr>
    ";
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
    <div class="wrapper">
        <div class="table-responsive-vertical">
            <form id="form">
                <input type="hidden" name="periode" value="<?php echo $formatdate; ?>">
                <input type="hidden" name="nip" value="<?php echo $_GET['nama']; ?>">
                <table id="table" class="table table-hover table-mc-indigo">
                    <thead>
                        <tr>
                            <th class="center" colspan="10"><h2><?php echo $_GET['nama']?></h2></th>
                        </tr>
                        <tr>
                            <th class="center">Tanggal</th>
                            <th class="center">Masuk</th>
                            <th class="center">Pulang</th>
                            <th class="center">Jam Kerja</th>
                            <th width="1">SPL</th>
                            <th width="1">Lembur</th>
                            <th class="center">Hari Ahad</th>
                            <th class="center">Jam Kurang</th>
                            <th class="center">Jam Lebih</th>
                            <th class="center">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $show; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
