<?php

namespace app\modules\kinerja\models;

/**
 * This is the ActiveQuery class for [[KegiatanRiwayat]].
 *
 * @see KegiatanRiwayat
 */
class KegiatanRiwayatQuery extends \yii\db\ActiveQuery
{
    public $id_kegiatan_jenis;
    public $tableName;

    public function prepare($builder)
    {
        if ($this->id_kegiatan_jenis !== null) {
            $this->andWhere(["$this->tableName.id_kegiatan_jenis" => $this->id_kegiatan_jenis]);
        }
        return parent::prepare($builder);
    }
}
