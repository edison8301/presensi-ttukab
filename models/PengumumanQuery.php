<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Pengumuman]].
 *
 * @see Pengumuman
 */
class PengumumanQuery extends \yii\db\ActiveQuery
{
    public function aktif($state = true)
    {
        return $this->andWhere(["pengumuman.status_hapus" => !$state]);
    }

    public function berlaku($tanggal = null)
    {
        if ($tanggal === null) {
            $tanggal = date('Y-m-d');
        }
        return $this->andWhere(['status' => true])
            ->orderBy(['tanggal_mulai' => SORT_ASC])
            ->andWhere(['<=', 'tanggal_mulai', $tanggal])
            ->andWhere(['>=', 'tanggal_selesai', $tanggal]);
    }
}
