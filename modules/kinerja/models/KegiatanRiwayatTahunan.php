<?php

namespace app\modules\kinerja\models;

use Yii;
/**
*
*/
class KegiatanRiwayatTahunan extends KegiatanRiwayat
{
    public function init()
    {
        $this->id_kegiatan_jenis = self::TAHUNAN;
        parent::init();
    }

    public static function find()
    {
        return new KegiatanRiwayatQuery(get_called_class(), ['id_kegiatan_jenis' => self::TAHUNAN, 'tableName' => self::tableName()]);
    }

    public function beforeSave($insert)
    {
        $this->id_kegiatan_jenis = self::TAHUNAN;
        return parent::beforeSave($insert);
    }
}
