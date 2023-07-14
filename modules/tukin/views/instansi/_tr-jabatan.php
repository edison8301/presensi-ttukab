<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14/11/2018
 * Time: 12:02
 */
/* @var $level int */
/* @var $jabatan \app\modules\tukin\models\Jabatan */
/* @var $this \yii\web\View */

use yii\helpers\Html;

?>

<tr>
    <td style="padding-left: <?= (8 + $level*25); ?>px"><?= $jabatan->nama; ?></td>
    <td style="text-align:center"><?= $jabatan->status_kepala; ?></td>
    <td style="text-align: center;"><?= @$jabatan->getJenisJabatan(); ?></td>
    <td style="text-align: left">
        <ul>
        <?php foreach ($jabatan->manyPegawai as $pegawai) { ?>
            <li>
                <?= Html::a(str_replace('-', '<br>', @$pegawai->namaNip),['/pegawai/view','id'=>$pegawai->id]) ?>
                <?= Html::a('<i class="fa fa-times"></i>',
                    ['jabatan/remove', 'id_pegawai' => $pegawai->id, 'id_jabatan' => $jabatan->id],
                    [
                        'data' => [
                            'toggle' => 'tooltip',
                            'confirm' => 'Yakin akan menghapus pegawai sebagai pemangku?'
                        ],
                        'title' => 'Hapus Pemangku'
                    ]) ?>
            </li>
        <?php } ?>
        </ul>
    </td>
    <td style="text-align: center;">
        <?= Html::a('<i class="fa fa-users"></i>', '#', ['onclick' => "id_jabatan=$jabatan->id;$('#modal-pegawai').modal('show')", 'data-toggle' => 'tooltip', 'title' => 'Tambah Pemangku']) ?>
        <?= Html::a('<i class="fa fa-pencil"></i>', ['/jabatan/update', 'id' => $jabatan->id], ['data-toggle' => 'tooltip', 'title' => 'Edit']); ?>
        <?= Html::a('<i class="fa fa-trash"></i>', ['/jabatan/delete', 'id' => $jabatan->id], [
            'data' => [
                'toggle' => 'tooltip',
                'method' => 'post',
                'confirm' => 'Yakin akan menhapus jabatan?'
            ],
            'title' => 'Hapus',
        ]); ?>
    </td>
</tr>

<?php $level++; foreach($jabatan->findAllSub() as $subjabatan) {
    echo $this->render('_tr-jabatan', ['jabatan' => $subjabatan, 'level' => $level]);
} ?>
