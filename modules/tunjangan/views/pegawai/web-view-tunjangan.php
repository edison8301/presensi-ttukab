<?php

use app\components\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            Filter Bulan
        </h3>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'layout' => 'inline',
            'action' => Url::to(['/tunjangan/pegawai/web-view-tunjangan', 'nip' => $model->nip]),
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

<script type="text/javascript">
    document.body.style.zoom = "80%" 
</script>