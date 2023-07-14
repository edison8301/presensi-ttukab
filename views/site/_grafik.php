<?php 
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\models\Anggaran;
use app\models\Realisasi;
use app\models\Rencana;
use app\models\Pagu;
use app\models\User;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">
            Grafik Anggaran Induk 
        </h3>
    </div>
    <div class="box-body">
        <div id="chart-anggaran"></div>
    </div>
</div>

<script type='text/javascript'>

    FusionCharts.ready(function(){
            var revenueChart = new FusionCharts({
                "type": "Column3d",
                "renderAt": "chart-anggaran",
                "width": "100%",
                "height": "300",
                "dataFormat": "json",
                "dataSource": {
                  "chart": {
                      "caption" : "Jumlah Anggaran Induk Per Tahun",
                      "xAxisName": "Tahun",
                      "yAxisName": "Jumlah Anggaran Induk",
                      "theme": "fint",
                      "formatNumberScale" : "0",
                   },
                  "data":        
                        [
                            <?= Anggaran::getGraphAnggaranList(); ?>
                        ]
                }
        });
        revenueChart.render();
    })
    /*
    FusionCharts.ready(function(){
            var revenueChart = new FusionCharts({
                "type": "Column3d",
                "renderAt": "chart-realisasi",
                "width": "100%",
                "height": "300",
                "dataFormat": "json",
                "dataSource": {
                  "chart": {
                      "caption" : "Jumlah Realisasi Per Triwulan",
                      "xAxisName": "Bulan",
                      "yAxisName": "Jumlah Realisasi",
                      "theme": "fint",
                   },
                  "data":        
                        [
                            <?= Anggaran::getGraphRealisasiList(); ?>
                        ]
                }
        });
        revenueChart.render();
    })
    */

    /*FusionCharts.ready(function(){
            var revenueChart = new FusionCharts({
                "type": "line",
                "renderAt": "chart-pagu",
                "width": "100%",
                "height": "300",
                "dataFormat": "json",
                "dataSource": {
                  "chart": {
                      "divlinealpha": "100",
                      "vdivlinealpha": "100",
                      "yaxismaxvalue": "100",
                      "numbersuffix": "%",
                      "caption" : "Persentasi Realisasi Terhadap Pagu Per Bulan",
                      "xAxisName": "Bulan",
                      "yAxisName": "Persentasi Realisasi Terhadap Pagu",
                      "theme": "fint",
                   },
                  "data":        
                        [
                            <?= Realisasi::getGraphPaguList(Yii::$app->session->get('tahun',date('Y'))) ?>
                        ]
                }
        });
        revenueChart.render();
    })*/
</script>