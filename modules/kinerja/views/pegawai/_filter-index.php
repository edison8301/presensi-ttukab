<?php

use app\modules\kinerja\models\InstansiPegawaiSkp;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulananSearch */
/* @var $form kartik\form\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => $action,
    'type'=>'inline',
    'method' => 'get',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ]
]); ?>
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Filter Kegiatan Bulanan</h3>
    </div>

    <div class="box-body">

        <?= $form->field($searchModel, 'bulan',[
            'addon' => ['prepend' => ['content'=>'Bulan']]
        ])->dropDownList(Helper::getListBulan(), [
            'onchange' => 'this.form.submit()',
            'class' => 'form-control'
        ]); ?>

        <?php if($searchModel->id_pegawai!==null) { ?>
            <?= $form->field($searchModel, 'nomor_skp',[
                'addon' => ['prepend' => ['content'=>'No SKP']],
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-4',
                    'error' => '',
                    'hint' => '',
                ]
            ])->dropDownList(InstansiPegawaiSkp::getList(['id_pegawai'=>$searchModel->id_pegawai]),[
                'prompt'=>'Semua',
                'onchange'=>'this.form.submit()'
            ]) ?>
        <?php } ?>

        <?php if($searchModel->id_pegawai===null) { ?>
            <?= $form->field($searchModel, 'nomor_skp',[
                'addon' => ['prepend' => ['content'=>'No SKP']]
            ])->textInput()->label(false); ?>
        <?php } ?>

        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>

    </div>

</div>
<?php ActiveForm::end(); ?>
