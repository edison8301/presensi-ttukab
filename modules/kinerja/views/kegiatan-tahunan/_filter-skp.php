<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use app\models\KegiatanStatus;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulananSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['kegiatan-tahunan/skp'],
    'layout'=>'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kegiatan Tahunan</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-6">
                <?= Select2::widget([
                    'name' => 'id_pegawai',
                    'data' => $model->getListPegawai(),
                    'options' => [
                        'placeholder' => '- Pilih Pegawai -',
                    ],
                ]); ?>
            </div>
            <div class="col-sm-3">
                <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>

    </div>

</div>
<?php ActiveForm::end(); ?>
