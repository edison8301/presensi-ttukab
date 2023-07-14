<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ref_kegiatan_status".
 *
 * @property int $id
 * @property string $kode_kegiatan_status
 * @property string $nama
 *
 * @property Kegiatan[] $kegiatans
 */
class RefKegiatanStatus extends \yii\db\ActiveRecord
{
    const DISETUJUI = 1;
    const KONSEP = 2;
    const DIVERIFIKASI = 3;
    const DITOLAK = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_kegiatan_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_kegiatan_status', 'nama'], 'required'],
            [['kode_kegiatan_status'], 'string', 'max' => 10],
            [['nama'], 'string', 'max' => 255],
            [['kode_kegiatan_status'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_kegiatan_status' => 'Kode Kegiatan Status',
            'nama' => 'Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKegiatans()
    {
        return $this->hasMany(Kegiatan::className(), ['kode_kegiatan_status' => 'kode_kegiatan_status']);
    }

    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'kode_kegiatan_status', 'nama');
    }
}
