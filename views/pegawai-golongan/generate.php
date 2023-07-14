<?php

use yii\helpers\Html;

?>

<?= Html::beginForm(['/pegawai-golongan/generate'], 'post') ?>
    <?= Html::textarea('data', null, [
        'class' => 'form-control',
        'style' => 'height: 500px;',
    ]) ?>
    <button>Simpan</button>
<?= Html::endForm() ?>
