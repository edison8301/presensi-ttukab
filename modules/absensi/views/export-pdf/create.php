<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\ExportPdf */

$this->title = 'Create Export Pdf';
$this->params['breadcrumbs'][] = ['label' => 'Export Pdfs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="export-pdf-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
