<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \app\modules\kinerja\models\KegiatanTahunan */
/* @var $skpIkiMik \app\modules\kinerja\models\SkpIkiMik */

$this->title = 'Manual Indikator Kinerja';
$this->params['breadcrumbs'][] = ['label' => 'Indikator Kinerja Individu', 'url' => ['index-mik']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <?= $skpIkiMik->getLinkUpdateButton() ?>
    </div>
    <div class="box-body">

        <?= $this->render('_table-mik', [
            'skpIkiMik' => $skpIkiMik,
        ]) ?>

    </div>
</div>
