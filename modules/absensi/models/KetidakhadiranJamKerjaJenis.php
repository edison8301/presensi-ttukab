<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_jam_kerja_jenis".
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 */
class KetidakhadiranJamKerjaJenis extends \yii\db\ActiveRecord
{
    const IZIN = 1;
    const TUGAS_KEDINASAN = 6;
    const ALASAN_TEKNIS = 7;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_jam_kerja_jenis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['kode'], 'string', 'max' => 10],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'nama' => 'Nama',
        ];
    }

    /**
     * @inheritdoc
     * @return PegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KetidakhadiranJamKerjaJenisQuery(get_called_class());
    }

    public static function getList()
    {
        return ArrayHelper::map(static::find()->aktif()->all(), 'id', 'nama');
    }

    public function getLabelNama()
    {
        return '<span class="label label-primary">' . $this->nama . '</span>';
    }

    public function getIsAktif()
    {
        return (int) $this->status_aktif === 1;
    }
}
