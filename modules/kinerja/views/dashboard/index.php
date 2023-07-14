<?php

use app\models\Instansi;

$this->title = 'IKI - E-SAKIP';
$this->params['breadcrumbs'][] = $this->title;

$allInstansiInduk = Instansi::find()
    ->andWhere(['!=', 'id_instansi_jenis', 3])
    ->all();
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>

    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="text-align: center;width: 10px;">No</th>
                <th style="text-align: left;">Instansi</th>
                <th style="text-align: center;width: 100px;">Jumlah IKI</th>
            </tr>
            <?php $no=1; foreach ($allInstansiInduk as $instansi) { ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="text-align: left;"><?= $instansi->nama ?></td>
                    <td style="text-align: center;">
                        <?= $instansi->getLinkJumlahIkiKepala() ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>