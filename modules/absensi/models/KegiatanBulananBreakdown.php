<?php

namespace app\modules\absensi\models;

use Yii;

/**
 * This is the model class for table "kegiatan_bulanan_breakdown".
 *
 * @property integer $id
 * @property integer $id_kegiatan_tahunan_detil
 * @property string $kegiatan
 * @property integer $kuantitas
 * @property integer $id_satuan_kuantitas
 * @property integer $penilaian_kualitas
 */
class KegiatanBulananBreakdown extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kegiatan_bulanan_breakdown';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbKinerja');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kegiatan_tahunan_detil', 'kegiatan', 'kuantitas', 'id_satuan_kuantitas', 'penilaian_kualitas'], 'required'],
            [['id_kegiatan_tahunan_detil', 'kuantitas', 'id_satuan_kuantitas', 'penilaian_kualitas'], 'integer'],
            [['kegiatan'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kegiatan_tahunan_detil' => 'Id Kegiatan Tahunan Detil',
            'kegiatan' => 'Kegiatan',
            'kuantitas' => 'Kuantitas',
            'id_satuan_kuantitas' => 'Id Satuan Kuantitas',
            'penilaian_kualitas' => 'Penilaian Kualitas',
        ];
    }
}
