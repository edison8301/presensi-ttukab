<?php

use yii\helpers\Html;

$filepath = Yii::getAlias('@web/images/logo.png'); 

?>

<table class="table" style="page-break-inside: avoid">
    <tr>
        <td style="width: 65%"></td>
        <td style="border: 3px solid black; padding: 10px;">
            <table>
                <tr>
                    <td>
                        <?= Html::img($filepath) ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <u>Ditandatangani secara elektronik oleh:</u>
                        <p><?= strtoupper($jabatan) ?></p>
                        <br>
                        <b><?= $nama ?></b><br>
                        <b>NIP. <?= $nip ?></b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>