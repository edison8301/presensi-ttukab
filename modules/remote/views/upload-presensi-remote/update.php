<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\remote\models\UploadPresensiRemote */
/* @var $referrer string */

$this->title = "Sunting Upload Presensi Remote";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensi Remotes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="upload-presensi-remote-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
