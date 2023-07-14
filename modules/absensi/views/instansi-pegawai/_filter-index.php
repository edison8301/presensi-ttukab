<?php

use app\components\Helper;
use app\models\Grup;
use app\models\Instansi;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form kartik\form\ActiveForm */
/* @var $searchModel \app\absensi\models\InstansiPegawaiSearch */


?>

<?php $form = ActiveForm::begin([
    'action' => Url::current(),
    'method' => 'get',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-12',
            'wrapper' => 'col-sm-12',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Filter</h3>
    </div>

    <div class="box-body" >
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($searchModel, 'id_instansi')->widget(Select2::class, [
                    'data' => Instansi::getList(),
                    'options' => [
                        'placeholder' => '- Semua Perangkat Daerah -',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label(false); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($searchModel, 'bulan')->dropDownList(Helper::getListBulan(), [
                    'prompt'=>'- Pilih Bulan -',
                    //'onchange' => 'this.form.submit()',
                    'class' => 'form-control'
                ])->label(false); ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($searchModel, 'nama_pegawai')->textInput([
                    'placeholder' => 'Nama Pegawai'
                ])->label(false); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>
    </div>

</div>
<?php ActiveForm::end(); ?>
