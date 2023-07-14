<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\ShiftKerjaReguler */
/* @var $referrer string */

$this->title = "Sunting Shift Kerja Reguler";
$this->params['breadcrumbs'][] = ['label' => 'Shift Kerja Regulers', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="shift-kerja-reguler-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
