<?php

error_reporting(0);
require "db_connect.php";

$sql = "SELECT * FROM pegawai WHERE aktif=1 ORDER BY nama";
$query = mysqli_query($connection, $sql);
while($row = mysqli_fetch_array($query)) {
    $data .="
    <tr>
        <td>".$row['nip']."</td>
        <td><a class=\"link\" href=\"lihat-absen.php?nama=".$row['nama']."\" target=\"_blank\">".$row['nama']."</td>
        <td>".$row['panggilan']."</td>
        <td>".$row['kelamin']."</td>
        <td>".$row['tmp_lahir']."</td>
        <td>".$row['tgl_lahir']."</td>
        <td><a href=\"bulk-absen.php?nama=".$row['nama']."\" ><img width=\"18\" src=\"input.png\"></a> &nbsp;
        <a href=\"lihat-absen.php?nama=".$row['nama']."\" target=\"_blank\"><img width=\"18\" src=\"info.png\"></a></td>
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
        <a class="btn-menu" href="laporan.php">Laporan</a>
    </div>

    <div class="wrapper">
        <div class="table-responsive-vertical shadow-z-1">
          <!-- Table starts here -->
          <table id="table" class="table table-hover table-mc-indigo">
              <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Panggilan</th>
                    <th>Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  <?php echo $data; ?>
              </tbody>
            </table>
          </div>

    </div>

    <script src="jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.js"></script>
    <script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
    </script>
</body>
</html>
