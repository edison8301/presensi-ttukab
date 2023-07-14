<?php

/* @var $modelExportPdf \app\modules\absensi\models\ExportPdf */

?>

<div class="row" style="float: right; ">
    <div class="col-lg-8" style=" text-align: right;"> <barcode code="<?= $modelExportPdf->hash ?>" type="C128B" /></div>
</div>

<?php

/* @var $this \yii\web\View */
/* @var $modelExportPdf \app\modules\absensi\models\ExportPdf */
/* @var $fileName */

/*
use yii\helpers\Url;

$nama = Url::toRoute([
    '/file/tandatangan',
    'fileName' => $fileName,
], true);

if ($tandatangan == false) {
    $nama = $fileName;
}

$qr = Yii::$app->qr->setText($nama)
    ->setSize(60);

use yii\helpers\Html; ?>
<div class="row" style="float: right; ">
    <div class="col-lg-8" style=" text-align: right;">
        <?= Html::img($qr->writeDataUri()) ?>
    </div>
</div>
*/ ?>

