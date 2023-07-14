<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Instansi;
use app\models\KegiatanStatus;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulananSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([   
    'action' => ['/tunjangan/tunjangan-potongan/view','id' => $model->id],
    'layout'=>'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h2 class="box-title"><i class="fa fa-search"></i> Tahun dan Bulan Potongan</h2>
    </div>

    <div class="box-body">

        <?= $form->field($model, 'tahun')->textInput(['type' => 'number']); ?>

        <?= $form->field($model, 'bulan')->dropDownList(
            Helper::getListBulan(),
            [
                'prompt'=>'- Pilih Bulan -',
            ]
        ); ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>
       
    </div>
    
</div>
<?php ActiveForm::end(); ?>
