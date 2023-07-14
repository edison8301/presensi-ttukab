<?php

use app\components\Helper;
use app\models\Jabatan;
use app\modules\tukin\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */
/* @var $filter \app\modules\tukin\models\FilterTunjanganForm */
/* @var $isTampilNilaiTpp bool */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$rekap = $model->findOrCreatePegawaiRekapTunjangan($filter->bulan);

$tanggal = User::getTahun().'-'.$filter->bulan.'-15';
$instansiPegawai = $model->getInstansiPegawaiBerlaku($tanggal);

?>
<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">
            Detail Pegawai Bulan <?= Helper::getBulanLengkap($filter->bulan); ?>
            <?= User::getTahun(); ?>
        </h3>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'value' => $model->nama,
                ],
                [
                    'attribute' => 'nip',
                    'format' => 'raw',
                    'value' => $model->nip,
                ],
                [
                    'label' => 'Instansi',
                    'format' => 'raw',
                    'value' => @$instansiPegawai->instansi->nama,
                ],
                [
                    'label' => 'Jabatan',
                    'format' => 'raw',
                    'value' => @$instansiPegawai->namaJabatan,
                ],
                [
                    'label' => 'Kelas Jabatan',
                    'format' => 'raw',
                    'value' => @$instansiPegawai->jabatan->kelas_jabatan,
                ],
                [
                    'label' => 'Eselon Jabatan',
                    'value' => @$instansiPegawai->jabatan->eselon->nama.' '.@$instansiPegawai->jabatan->linkIconUpdate,
                    'format' => 'raw',
                    'visible' => @$instansiPegawai->jabatan->id_jenis_jabatan == Jabatan::STRUKTURAL
                ],
                [
                    'label' => 'Tingkatan Fungsional',
                    'value' => @$instansiPegawai->jabatan->tingkatanFungsional->nama.' '.@$instansiPegawai->jabatan->linkIconUpdate,
                    'format' => 'raw',
                    'visible' => @$instansiPegawai->jabatan->id_jenis_jabatan !== Jabatan::STRUKTURAL
                ],
                [
                    'label' => 'Golongan',
                    'format' => 'raw',
                    'value' => @$model->pegawaiGolongan->golongan.' '.$model->getLinkIconUpdateGolongan(),
                ],
            ],
        ]) ?>
    </div>
    <div class="box-footer">
        <?php if (User::isAdmin()) { ?>
            <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
        <?php } ?>
    </div>
</div>


<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            Filter Bulan
        </h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'layout' => 'inline',
            'action' => User::isAdmin() ? Url::to(['pegawai/view', 'id' => $model->id]) : Url::to(['/tunjangan/pegawai/view', 'id' => $model->id]),
            'method' => 'get',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
        ]); ?>

        <?= $form->field($filter, 'bulan')
            ->dropDownList(Helper::getBulanList(),['prompt'=>'- Filter Bulan -']) ?>

        <div class="form-group">
            <?= Html::submitButton('Filter', ['class' => 'btn btn-primary btn-flat']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?= $this->render('_perhitungan-tpp',[
    'model' => $model,
    'filter' => $filter,
    'isTampilNilaiTpp' => $isTampilNilaiTpp
]); ?>

<?= $this->render('_pembayaran-tpp',[
    'model' => $model,
    'filter' => $filter,
    'isTampilNilaiTpp' => $isTampilNilaiTpp
]); ?>
