<?php

use app\models\User;
use yii\helpers\Html;

/* @var $allJabatan \app\models\Jabatan[] */

?>

<table class="table table-borderd table-hover">
    <thead>
    <tr>
        <th style="width: 30px; text-align: center">No</th>
        <th>Nama Jabatan</th>
        <th style="text-align: center;">Jenis Jabatan</th>
        <th style="text-align: center;">Nilai<br/>Jabatan</th>
        <th style="text-align: center;">Kelas<br/>Jabatan</th>
        <th style="text-align: center;">Pegawai</th>
        <th style="text-align: center;">Mutasi/<br/>Promosi</th>
        <th></th>
    </tr>
    </thead>
    <?php $i = 1; ?>
    <?php foreach ($allJabatan as $item) { ?>
        <tr>
            <td style="text-align: center"><?= $i++; ?></td>
            <td><?= $item->nama; ?></td>
            <td style="text-align: center"><?= @$item->getJenisJabatan(); ?></td>
            <td style="text-align: center"><?= $item->nilai_jabatan; ?></td>
            <td style="text-align: center"><?= $item->kelas_jabatan; ?></td>
            <td>
                <ul style="padding-left:20px">
                    <?php foreach ($item->manyInstansiPegawai as $instansiPegawai) { ?>
                        <li>
                            <?php if (User::isAdmin() || User::isMapping()) { ?>
                                <?= Html::a(str_replace('-', '<br>', @$instansiPegawai->pegawai->namaNip),['/pegawai/view','id'=>@$instansiPegawai->pegawai->id]) ?>
                            <?php } else { ?>
                                <?= @$instansiPegawai->pegawai->namaNip ?>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </td>
            <td style="text-align: center;">
                <?php if (User::isAdmin() || User::isMapping()) { ?>
                    <?= Html::a($item->countInstansiPegawai(),[
                        'absensi/instansi-pegawai/index',
                        'InstansiPegawaiSearch[id_jabatan]' => $item->id
                    ]); ?>
                <?php } else { ?>
                    <?= $item->countInstansiPegawai() ?>
                <?php } ?>
            </td>
            <td style="text-align: center;">
                <?php if (User::isAdmin() || User::isMapping()) { ?>
                    <?= Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/jabatan/create', 'id_instansi'=>$item->id_instansi, 'id_induk' => $item->id], ['data-toggle' => 'tooltip', 'title' => 'Tambah Sub Jabatan']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['/jabatan/update', 'id' => $item->id], ['data-toggle' => 'tooltip', 'title' => 'Edit']); ?>
                    <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['/jabatan/delete', 'id' => $item->id], [
                        'data' => [
                            'toggle' => 'tooltip',
                            'method' => 'post',
                            'confirm' => 'Yakin akan menhapus jabatan?'
                        ],
                        'title' => 'Hapus',
                    ]); ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
