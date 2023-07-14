<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper;
use kartik\date\DatePicker;
use app\models\Pegawai;
use app\models\KegiatanStatus;

/* @var $this yii\web\View */
/* @var $model app\models\KegiatanBulananSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $dasborKinerja \app\models\DasborKinerja */

?>

<?php $form = ActiveForm::begin([
    'action' => ['dasbor/kinerja-pegawai'],
    'layout'=>'inline',
    'method' => 'get',
]); ?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Filter Kegiatan Tahunan</h3>
    </div>

    <div class="box-body">
        <?= $form->field($dasborKinerja, 'id_pegawai')->dropDownList(
            Pegawai::getList(),
            [
                'prompt'=>'- Pilih Pegawai -',
            ]
        ); ?>
        <?= $form->field($dasborKinerja, 'bulan')->dropDownList(
            Helper::getListBulan(),
            [
                'prompt'=>'- Pilih Bulan -',
            ]
        ); ?>
        <?= $form->field($dasborKinerja, 'tanggal')->widget(DatePicker::className(), [
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
