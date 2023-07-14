<?php

namespace app\modules\tukin\models;

/**
 * This is the ActiveQuery class for [[InstansiPegawai]].
 *
 * @see InstansiPegawai
 */
class InstansiPegawaiQuery extends \yii\db\ActiveQuery
{
    public function aktif($state = true)
    {
        return $this->andWhere(["instansi_pegawai.status_hapus" => !$state]);
    }

    public function berlaku($tanggal = null)
    {
        if ($tanggal === null) {
            $tanggal = date('Y-m-d');
        }
        return $this->orderBy(['tanggal_berlaku' => SORT_DESC])->andWhere(['<=', 'tanggal_berlaku', $tanggal]);
    }
}
