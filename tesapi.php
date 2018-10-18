<?php
error_reporting(0);
//Untuk tanggal 25 sampai 31 di bulan sebelumnya
for($tgl=25;$tgl<=31;$tgl++){

    $spl = isset($_POST['spl'.$tgl.''])?$_POST['spl'.$tgl.'']:0;
    $ahad = isset($_POST['ahad'.$tgl.''])?$_POST['ahad'.$tgl.'']:0;

    if(!empty($_POST['masuk_tgl'.$tgl.''])) {
        $formatmasuk = date_format(date_create($_POST['masuk_tgl'.$tgl.'']),"H:i");
    } else {
        $formatmasuk = 0;
    }

    if(!empty($_POST['pulang_tgl'.$tgl.''])) {
        $formatpulang = date_format(date_create($_POST['pulang_tgl'.$tgl.'']),"H:i");

    } else {
        $formatpulang = 0;
    }

    $datetime = new DateTime();

    if(!empty($_POST['masuk_tgl'.$tgl.'']) && !empty($_POST['pulang_tgl'.$tgl.''])) {
        //jam masuk
        $jammasuk = $datetime->createFromFormat('H:i', $formatmasuk);
        $masuk = $jammasuk->format('H:i');

        //jam pulang
        $jampulang = $datetime->createFromFormat('H:i', $formatpulang);
        $pulang = $jampulang->format('H:i');
        if($jampulang > '12:00:00') {
            $dateformatpulang = date_add($jampulang, date_interval_create_from_date_string('24 hours'));
            //echo $dateformatpulang->format('H:i').'<br>';
        }

        //acuan jam lembur 8 jam
        $jamlembur = $datetime->createFromFormat('h:i', '08:00');
        $intervaljamkerja = $jamlembur->format('h:i');

        //total lama masuk kerja
        $totaljam = $jammasuk->diff($jampulang)->format('%h:%i');
        //setting supaya total jam masuk format waktu
        $settotaljam = $datetime->createFromFormat('h:i', date_format(date_create($totaljam),"h:i"));

        //seting baru!!!
        $jamlemburbaru = new DateTime('08:00');
        $settotaljambaru = new DateTime($totaljam);

        //acuan default 8 jam 30 menit
        $formatinterval = $datetime->createFromFormat('h:i', '8:30');
        $interval = $formatinterval->format('h:i');

        if($ahad == 0) {

            if(strtotime($totaljam) >= strtotime($interval)) {
                if($_POST['spl'.$tgl.''] == 1) {
                    $lembur = 1;
                } else {
                    $lembur = 0;
                }
                //selesih antara total jam dan jam pulang
                $jamlebih = $settotaljambaru->diff($jamlemburbaru)->format('%h:%i');
                $jamkurang = 0;
            } else {
                $lembur = 0;
                //selesih antara total jam dan jam pulang
                $jamlebih = 0;
            }

            if(strtotime($totaljam) <= strtotime($intervaljamkerja)) {
                $jamkurang = $settotaljambaru->diff($jamlemburbaru)->format('%h:%i');
            } else {
                $jamkurang = 0;
            }
        } else {
            //menentukan lembur di hari minggu
            if($spl == 1) {
                $lembur = 1;
                $jamlebih = $totaljam;
                $jamkurang = 0;
            } else {
                if(strtotime($totaljam) >= strtotime($interval)) {
                    if($_POST['spl'.$tgl.''] == 1) {
                        $lembur = 1;
                    } else {
                        $lembur = 0;
                    }
                    //selesih antara total jam dan jam pulang
                    $jamlebih = $settotaljam->diff($jamlembur)->format('%h:%i');
                    $jamkurang = 0;
                } else {
                    $lembur = 0;
                    //selesih antara total jam dan jam pulang
                    $jamlebih = 0;
                }

                if(strtotime($totaljam) <= strtotime($intervaljamkerja)) {
                    $jamkurang = $settotaljam->diff($jamlembur)->format('%h:%i');
                } else {
                    $jamkurang = 0;
                }
            }
        }
    } else {
        $jamlebih=0;
        $jamkurang=0;
        $lembur=0;
        $totaljam=0;
    }
    $sql .= "INSERT INTO log_presensi VALUES ('".$_POST['nip']."', '".$_POST['periode']."', '".$_POST['tanggal'.$tgl.'']."', '".$formatmasuk."', '".$formatpulang."','".$totaljam."','".$spl."','".$ahad."','".$lembur."','".$jamlebih."','".$jamkurang."', '".$_POST['catatan'.$tgl.'']."');";
}


//Untuk tanggal 1 sampai 24 di bulan ini
for($tgl=1;$tgl<=24;$tgl++){

    $spl = isset($_POST['spl'.$tgl.''])?$_POST['spl'.$tgl.'']:0;
    $ahad = isset($_POST['ahad'.$tgl.''])?$_POST['ahad'.$tgl.'']:0;

    if(!empty($_POST['masuk_tgl'.$tgl.''])) {
        $formatmasuk = date_format(date_create($_POST['masuk_tgl'.$tgl.'']),"H:i");
    } else {
        $formatmasuk = 0;
    }

    if(!empty($_POST['pulang_tgl'.$tgl.''])) {
        $formatpulang = date_format(date_create($_POST['pulang_tgl'.$tgl.'']),"H:i");
    } else {
        $formatpulang = 0;
    }

    $datetime = new DateTime();

    if(!empty($_POST['masuk_tgl'.$tgl.'']) && !empty($_POST['pulang_tgl'.$tgl.''])) {
        //jam masuk
        $jammasuk = $datetime->createFromFormat('H:i', $formatmasuk);
        $masuk = $jammasuk->format('H:i');

        //jam pulang
        $jampulang = $datetime->createFromFormat('H:i', $formatpulang);
        $pulang = $jampulang->format('H:i');
        if($jampulang > '12:00:00') {
            $dateformatpulang = date_add($jampulang, date_interval_create_from_date_string('24 hours'));
            //echo $dateformatpulang->format('H:i').'<br>';
        }

        //acuan jam lembur 8 jam
        $jamlembur = $datetime->createFromFormat('H:i', '08:00');
        $intervaljamkerja = $jamlembur->format('h:i');

        //total lama masuk kerja
        $totaljam = $jammasuk->diff($jampulang)->format('%h:%i');
        //setting supaya total jam masuk format waktu
        $settotaljam = $datetime->createFromFormat('h:i', date_format(date_create($totaljam),"h:i"));

        //seting baru!!!
        $jamlemburbaru = new DateTime('08:00');
        $settotaljambaru = new DateTime($totaljam);

        //acuan default 8 jam 30 menit
        $formatinterval = $datetime->createFromFormat('h:i', '8:30');
        $interval = $formatinterval->format('h:i');

        if($ahad == 0) {

            if(strtotime($totaljam) >= strtotime($interval)) {
                if($_POST['spl'.$tgl.''] == 1) {
                    $lembur = 1;
                } else {
                    $lembur = 0;
                }
                //selesih antara total jam dan jam pulang
                $jamlebih = $settotaljambaru->diff($jamlemburbaru)->format('%h:%i');
                $jamkurang = 0;
            } else {
                $lembur = 0;
                //selesih antara total jam dan jam pulang
                $jamlebih = 0;
            }

            if(strtotime($totaljam) <= strtotime($intervaljamkerja)) {
                $jamkurang = $settotaljambaru->diff($jamlemburbaru)->format('%h:%i');
            } else {
                $jamkurang = 0;
            }
        } else {
            //menentukan lembur di hari minggu
            if($ahad == 1 && $spl == 1) {
                $lembur = 1;
                $jamlebih = $totaljam;
                $jamkurang = 0;
            }
        }
    } else {
        $jamlebih=0;
        $jamkurang=0;
        $lembur=0;
        $totaljam=0;
    }
    $sql .= "INSERT INTO log_presensi VALUES ('".$_POST['nip']."', '".$_POST['periode']."', '".$_POST['tanggal'.$tgl.'']."', '".$formatmasuk."', '".$formatpulang."','".$totaljam."','".$spl."','".$ahad."','".$lembur."','".$jamlebih."','".$jamkurang."', '".$_POST['catatan'.$tgl.'']."');";
}
require "db_connect.php";

$sql_cekperiode = "SELECT periode FROM log_presensi WHERE nip='".$_POST['nip']."' AND periode='".$_POST['periode']."'";
$query_cekperiode = mysqli_query($connection, $sql_cekperiode);
$cekperiode = mysqli_num_rows($query_cekperiode);

if($cekperiode > 0) {
    mysqli_query($connection, "DELETE FROM log_presensi WHERE nip='".$_POST['nip']."' AND periode='".$_POST['periode']."'");
    if (mysqli_multi_query($connection, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    if (mysqli_multi_query($connection, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}

mysqli_close($connection);
?>
