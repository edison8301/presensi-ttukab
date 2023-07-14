<?php

namespace app\modules\kinerja\models;

use Yii;

/**
*
*/
class KegiatanRiwayatHarian extends KegiatanRiwayat
{
    public function init()
    {
        $this->id_kegiatan_jenis = self::HARIAN;
        parent::init();
    }

    public static function find()
    {
        return new KegiatanRiwayatQuery(get_called_class(), ['id_kegiatan_jenis' => self::HARIAN, 'tableName' => self::tableName()]);
    }

    public function beforeSave($insert)
    {
        $this->id_kegiatan_jenis = self::HARIAN;
        return parent::beforeSave($insert);
    }
}
