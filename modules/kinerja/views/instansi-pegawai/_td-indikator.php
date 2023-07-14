<?php

use app\models\User;
use yii\bootstrap\ButtonDropdown;

/* @var $this \yii\web\View */
/* @var $indikator \app\modules\kinerja\models\InstansiPegawaiSasaranIndikator|mixed|null */
/* @var $tr bool */

?>
<?php if ($tr) { ?>
<tr>
    <?php } ?>
    <td>
        <?php if (User::isPegawai()) {
            echo ButtonDropdown::widget([
                'label' => '',
                'options' => [
                    'class' => 'btn btn-primary btn-xs btn-flat'
                ],
                'dropdown' => [
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => '<i class="fa fa-pencil"></i>Sunting', 'url' => ['instansi-pegawai-sasaran-indikator/update', 'id' => $indikator->id]],
                        [
                            'label' => '<i class="fa fa-trash"></i>Hapus',
                            'url' => ['instansi-pegawai-sasaran-indikator/delete', 'id' => $indikator->id],
                            'linkOptions' => [
                                'data-confirm' => 'Yakin akan menghapus Indikator?',
                                'data-method' => 'post'
                            ],
                        ],
                        // '<li class="divider"></li>',
                        // ['label' => '<i class="fa fa-arrow-up"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                        // ['label' => '<i class="fa fa-arrow-down"></i>Naik Posisi', 'url' => ['komoditas/turun-urutan','id' => $anjabJabatan->id]],
                    ],
                ],
            ]);
        } ?>
        <?= $indikator->nama ?>
    </td>
    <td><?= $indikator->penjelasan ?></td>
    <td><?= $indikator->sumber_data ?></td>
    <?php if ($tr) { ?>
</tr>
<?php } ?>
