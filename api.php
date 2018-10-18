<?php

$datetime = new DateTime();

$jammasuk = $datetime->createFromFormat('H:i:s', '08:00:00');
$masuk = $jammasuk->format('H:i:s');

$jampulang = $datetime->createFromFormat('H:i:s', '16:59:00');
$pulang = $jampulang->format('H:i:s');


$jamhari = $jammasuk->diff($jampulang)->format('%h:%i:%s');
$lamakerja = $jammasuk->diff($jampulang)->format('%h jam %i menit %s detik');

if($jamhari > '8:30:00') {
    $lembur = "lembur";
} else {
    $lembur = "nggak lembur";
}

?>

Jam Masuk : <?php echo $masuk; ?> <br>
Jam Pulang : <?php echo $pulang; ?> <br><br>

Lama Kerja : <?php echo $lamakerja; ?><br><br>

Anda <?php echo $lembur; ?>
