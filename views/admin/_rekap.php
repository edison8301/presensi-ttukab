<?php 
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\models\Realisasi;
use app\models\Rencana;
use app\models\Pagu;
use app\models\User;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            Filter Tahun
        </h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'layout'=>'inline',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-lg-1',
                    'wrapper' => 'col-lg-2',
                    'error' => '',
                    'hint' => '',
                ],
            ]
        ]); ?>

        <?= $form->field($model, 'tahun')->textInput() ?>

        <?= Html::submitButton('<i class="fa fa-search"></i> Filter', ['class' => 'btn btn-success btn-flat']) ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>
                            <?php 
                            if (User::isAdmin()) {
                                print 'Rp '.Helper::rp(Realisasi::getJumlahSampaiBulan(Yii::$app->session->get('tahun',date('Y')),date('m')));
                            } else {
                                print 'Rp '.Helper::rp(Realisasi::getJumlahSampaiBulan(Yii::$app->session->get('tahun',date('Y')),date('m'),Yii::$app->user->identity->id_opd));
                            } ?>
                                
                        </h3>
                        <p>Jumlah Realisasi Sampai Bulan Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="<?= Url::to(['opd/index']); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>
                            <?php
                            if (User::isAdmin()) {
                                print Helper::rp(Pagu::getJumlah(Yii::$app->session->get('tahun',date('Y'))));
                            } else {
                                print Helper::rp(Pagu::getJumlah(Yii::$app->session->get('tahun',date('Y')),Yii::$app->user->identity->id_opd));
                            } ?>
                        </h3>
                        <p>Jumlah Total Pagu</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money"></i>
                    </div>
                    <a href="<?= Url::to(['opd/index']); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                            <?php
                            if (User::isAdmin()) {
                                print Realisasi::getPersenPerPaguSampaiBulan(Yii::$app->session->get('tahun',date('Y')),date('m')).'%'; 
                            } else {
                                print Realisasi::getPersenPerPaguSampaiBulan(Yii::$app->session->get('tahun',date('Y')),date('m'),Yii::$app->user->identity->id_opd).'%'; 
                            } ?>
                                
                        </h3>
                        <p>Persentasi Realisasi Terhadap Pagu</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <a href="<?= Url::to(['opd/index']); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">
            Grafik
        </h3>
    </div>
    <div class="box-body">
        <div id="chart-realisasi"></div>
    </div>
    <div class="box-body">
        <div id="chart-pagu"></div>
    </div>
</div>

<script type='text/javascript'>
    FusionCharts.ready(function(){
            var revenueChart = new FusionCharts({
                "type": "Column3d",
                "renderAt": "chart-realisasi",
                "width": "100%",
                "height": "300",
                "dataFormat": "json",
                "dataSource": {
                  "chart": {
                      "caption" : "Jumlah Realisasi Per Bulan",
                      "xAxisName": "Bulan",
                      "yAxisName": "Jumlah Realisasi",
                      "theme": "fint",
                   },
                  "data":        
                        [
                            <?= Realisasi::getGraphList(Yii::$app->session->get('tahun',date('Y'))) ?>
                        ]
                }
        });
        revenueChart.render();
    })

    FusionCharts.ready(function(){
        var revenueChart = new FusionCharts({
            "type": "msline",
            "renderAt": "chart-pagu",
            "width": "100%",
            "height": "400",
            "dataFormat": "json",
            "dataSource": {
              "chart": {
                  "caption" : "Grafik Penyerapan Anggaran",
                  "numbersuffix": "%",
                  "xAxisName": "Bulan",
                  "yAxisName": "Persentase Penyerapan",
                  "yAxisMaxValue" : 100,
                  "theme": "fint"
               },
               "categories": [{
                "category": [{"label": "Jan"},{"label": "Feb"},{"label": "Mar"},{"label": "Apr"},{"label": "May"},{"label": "Jun"},{"label": "Jul"},{"label": "Aug"},{"label": "Sep"},{"label": "Oct"},{"label": "Nov"},{"label": "Dec"}]
              }],
              "dataset": [{
                "seriesname": "Realisasi",
                "data": [
                        <?php
                        if (User::isAdmin()) {
                            print Realisasi::getGraphPaguList(Yii::$app->session->get('tahun',date('Y')));
                        } else {
                            print Realisasi::getGraphPaguList(Yii::$app->session->get('tahun',date('Y')),Yii::$app->user->identity->id_opd);
                        } ?>]
              },{
                "seriesname": "Rencana",
                "data": [
                        <?php
                        if (User::isAdmin()) {
                            print Rencana::getGraphPaguList(Yii::$app->session->get('tahun',date('Y')));
                        } else {
                            print Rencana::getGraphPaguList(Yii::$app->session->get('tahun',date('Y')),Yii::$app->user->identity->id_opd);
                        } ?>]
              }]
                
            }
        });

        revenueChart.render();
    })

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