<?php

use app\widgets\LabelKegiatan;
use app\components\Helper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Harian Tanggal ' . Helper::getTanggal($tanggal);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Tanggal</h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'action' => ['kegiatan-harian/pegawai-index'],
            'method' => 'get',
        ]); ?>
        <div class="box-header">
            <div class="row">
                <div class="col-sm-4">
                    <?=  DatePicker::widget([
                        'name' => 'tanggal',
                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                        'value' => $tanggal,
                        'pickerButton' => ['icon' => 'calendar'],
                        'options' => [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]); ?>
                </div>
            </div>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="kegiatan-harian-index box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Kegiatan Harian', ['kegiatan-harian/create'], ['class' => 'btn btn-success btn-flat']); ?>
    </div>
    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= $this->render('//kegiatan-harian/partials/_grid-index', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]); ?>
    </div>
</div>
