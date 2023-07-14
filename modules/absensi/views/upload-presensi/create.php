<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\UploadPresensi */
/* @var $referrer string */

$this->title = "Tambah Upload Presensi";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-presensi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
