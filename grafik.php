<?php

require "db_connect.php";
$periode = $_GET['periode'];

if($periode != '') {
    $periode = $_GET['periode'];
} else {
    $periode = date('Y-m');
}

$sql_cekperiode = "SELECT tanggal FROM log_presensi WHERE nip = '".$_GET['nama']."' and periode='".$periode."' ORDER by tanggal ASC";
$query_cekperiode = mysqli_query($connection, $sql_cekperiode);
$cekperiode = mysqli_fetch_row($query_cekperiode);

$jam = 0;
for($i=0;$i<=3;$i++) {
    $date = date_create($cekperiode[0]);
    $newdate = strtotime ( '+'.($i*6+$jam).' day' , strtotime ( date_format($date,"Y-m-d") ) ) ;
    $format = date ( 'Y-m-j' , $newdate );

    $sql = "SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( total_jam ) ) ) AS total_time  FROM log_presensi WHERE tanggal BETWEEN '".$format."' AND '".$format."' + INTERVAL 6 DAY AND nip = '".$_GET['nama']."' and periode='".$periode."'";
    $query = mysqli_query($connection, $sql);
    $result = mysqli_fetch_row($query);

    if($result[0] != '00:00:00' && $result[0] != '' ){
        $piecesjam_total = explode(":", $result[0]);
        $total_jam = $piecesjam_total[0]." Jam ".$piecesjam_total[1]." Menit";
        $total_jam_chart = $piecesjam_total[0].",".$piecesjam_total[1];
    } else {
        $total_jam = 0;
        $total_jam_chart = 0;
    }

    $data .= "['".$total_jam."', ".$total_jam_chart."]".',';

    $jam++;
}

?>



<div id="container"></div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        Highcharts.chart('container', {

        chart: {
            type: 'spline'
        },
        title: {
            text: 'Gafik Jumlah Jam Karyawan Perminggu'
        },
        xAxis: {
            categories: ['Minggu ke-1', 'Minggu ke-2', 'Minggu ke-3', 'Minggu ke-4']
        },
        yAxis: {
            title: {
                text: 'Jumlah Jam'
            }
        },
        plotOptions: {
            series: {
                label: {
                    enabled: false
                },
                dataLabels: {
                    enabled: false
                }
            }
        },

        series: [{
            name: 'Kehadiran',
            data: [<?php echo $data; ?>]
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

        });
    });
</script>
