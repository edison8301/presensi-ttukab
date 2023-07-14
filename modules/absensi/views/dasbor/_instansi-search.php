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
/* @var $dasborAbsensi \app\models\DasborAbsensi */

?>

<?php $form = ActiveForm::begin([
    'action' => ['dasbor/absensi-admin'],
    'layout'=>'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kegiatan Tahunan</h3>
    </div>

    <div class="box-body">
        <?= $form->field($dasborAbsensi, 'id_instansi')->dropDownList(
            Instansi::getList(),
            [
                'prompt'=>'- Pilih Instansi -',
            ]
        ); ?>
        <?= $form->field($dasborAbsensi, 'bulan')->dropDownList(
            Helper::getListBulan(),
            [
                'prompt'=>'- Pilih Bulan -',
            ]
        ); ?>
        <?= $form->field($dasborAbsensi, 'tanggal')->widget(DatePicker::className(), [
            'removeButton' => false,
            'options' => ['placeholder' => 'Tanggal'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd'
            ]
        ]) ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> Filter',['class' => 'btn btn-primary btn-flat']) ?>

    </div>

</div>
<?php ActiveForm::end(); ?>
