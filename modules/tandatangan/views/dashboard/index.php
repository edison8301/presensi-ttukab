<?php

use app\components\Helper;
use app\models\Instansi;
use app\models\User;
use app\modules\tandatangan\models\Berkas;
use app\modules\tandatangan\models\BerkasStatus;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Dashboard Tandatangan';

$url = @Yii::$app->params['url_tandatangan'];

?>

<div id="app">
    <div class="row">
        <div class="col-sm-3">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <p>Belum Tandatangan</p>
                    <h3><?= $jumlahBelumTandatangan ?> Berkas</h3>
                </div>
                <div class="icon">
                    <i class="fa fa-list"></i>
                </div>
                <?= Html::a('<i class="fa fa-arrow-right"></i> Lihat', [
                    '/tandatangan/berkas/index',
                    'id_berkas_status' => BerkasStatus::VERIFIKASI,
                ], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box bg-green">
                <div class="inner">
                    <p>Sudah Tandatangan</p>
                    <h3><?= $jumlahSudahTandatangan ?> Berkas</h3>
                </div>
                <div class="icon">
                    <i class="fa fa-list"></i>
                </div>
                <?= Html::a('<i class="fa fa-arrow-right"></i> Lihat', [
                    '/tandatangan/berkas/index',
                    'id_berkas_status' => BerkasStatus::SELESAI,
                ], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <p>Total</p>
                    <h3><?= $jumlahTotalTandatangan ?> Berkas</h3>
                </div>
                <div class="icon">
                    <i class="fa fa-list"></i>
                </div>
                <?= Html::a('<i class="fa fa-arrow-right"></i> Lihat', [
                    '/tandatangan/berkas/index',
                ], ['class' => 'small-box-footer']) ?>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Berkas Yang Harus Ditandatangan
            </h3>
        </div>

        <div class="box-body">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => 'No',
                        'headerOptions' => ['style' => 'text-align:center;width:10px;'],
                        'contentOptions' => ['style' => 'text-align:center']
                    ],
                    [
                        'attribute' => 'nama',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:left;'],
                    ],
                    [
                        'attribute' => 'id_instansi',
                        'filter' => Instansi::getList(),
                        'format' => 'raw',
                        'value' => function($data) {
                            return @$data->instansi->nama;
                        },
                        'headerOptions' => ['style' => 'text-align:center;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'bulan',
                        'format' => 'raw',
                        'filter' => Helper::getBulanList(),
                        'value' => function(Berkas $data) {
                            return Helper::getBulanLengkap($data->bulan);
                        },
                        'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'berkas_mentah',
                        'label' => 'Berkas<br>Mentah',
                        'encodeLabel' => false,
                        'format' => 'raw',
                        'value' => function(Berkas $data) {
                            return Html::a('<i class="fa fa-download"></i>', [
                                '/tandatangan/berkas/get-berkas',
                                'id' => $data->id,
                                'dir' => 'berkas-mentah',
                                'filename' => $data->berkas_mentah,
                            ], [
                                'target' => '_blank',
                            ]);
                        },
                        'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'berkas_tandatangan',
                        'label' => 'Berkas<br>Yang Sudah<br>Ditandatangani',
                        'encodeLabel' => false,
                        'visible' => @$searchModel->id_berkas_status == BerkasStatus::SELESAI,
                        'format' => 'raw',
                        'value' => function(Berkas $data) use ($url) {
                            $filepath = $url.'/berkas-tandatangan/'.$data->berkas_tandatangan;
                            if($data->id_berkas_status == BerkasStatus::SELESAI) {
                                return Html::a('<i class="fa fa-download"></i>', [
                                    '/tandatangan/berkas/get-berkas',
                                    'id' => $data->id,
                                    'dir' => 'berkas-tandatangan',
                                    'filename' => $data->berkas_tandatangan,
                                ], [
                                    'target' =>'_blank',
                                ]);
                            }
                        },
                        'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'attribute' => 'id_berkas_status',
                        'filter' => BerkasStatus::getList(),
                        'label' => 'Status',
                        'value' => function(Berkas $data) {
                            return @$data->getLabelBerkasStatus();
                        },
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                    [
                        'format'=>'raw',
                        // 'visible' => User::isAdmin() OR User::isPegawai(),
                        'value' => function(Berkas $data) {
                            $btn = '';
                            $btn .= $data->getLinkIconTandatangan();
                            $btn .= ' '.$data->getLinkIconRiwayat();
                            $btn .= ' '.$data->getLinkIconView();
                            $btn .= ' '. $data->getLinkIconDelete();
                            return $btn;
                        },
                        'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                        'contentOptions' => ['style' => 'text-align:center;'],
                    ],
                ],
            ]); ?>
        </div>
        </div>
    </div>
        
    <?= $this->render('/modal/_modal-tandatangan'); ?>
    <?= $this->render('/modal/_modal-view-berkas'); ?>
    <?= $this->render('/modal/_modal-riwayat'); ?>
    <?= $this->render('/modal/_modal-log'); ?>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-vue/2.21.2/bootstrap-vue.min.css" integrity="sha512-YnP4Ql71idaMB+/ZG38+1adSSQotdqvixQ+dQg8x/IFA4heIj6i0BC31W5T5QUdK1Uuwa01YdqdcT42q+RldAg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-vue/2.21.2/bootstrap-vue.min.js" integrity="sha512-Z0dNfC81uEXC2iTTXtE0rM18I3ATkwn1m8Lxe0onw/uPEEkCmVZd+H8GTeYGkAZv50yvoSR5N3hoy/Do2hNSkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js" integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    var base_url = '<?= @Yii::$app->params['url_tandatangan'] ?>';
    window.base_url = base_url;
</script>
<script src="js/berkas-vue.js"></script>