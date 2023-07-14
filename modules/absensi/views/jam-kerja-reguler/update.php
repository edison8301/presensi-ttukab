<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerjaReguler */
/* @var $referrer string */

$this->title = "Sunting Jam Kerja Reguler";
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja Regulers', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="jam-kerja-reguler-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
