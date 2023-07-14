<?php

namespace app\modules\absensi\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ketidakhadiran_panjang_jenis".
 *
 * @property int $id
 * @property string $labelNama
 * @property string $nama
 * @property int $id_ketidakhadiran_jenis [int(11)]
 * @property int $status_hapus [int(11)]
 */
class KetidakhadiranPanjangJenis extends \yii\db\ActiveRecord
{
    const KETIDAKHADIRAN_CUTI_BESAR = 1;
    const KETIDAKHADIRAN_CUTI_ALASAN_PENTING = 2;
    const KETIDAKHADIRAN_CUTI_TAHUNAN = 3;
    const KETIDAKHADIRAN_CUTI_DILUAR_TANGGUNGAN_NEGARA = 4;
    const KETIDAKHADIRAN_TUGAS_BELAJAR = 5;
    const KETIDAKHADIRAN_MAGANG = 6;
    const KETIDAKHADIRAN_PEGAWAI_DITITIPKAN = 7;
    const KETIDAKHADIRAN_CUTI_MELAHIRKAN = 8;
    const KETIDAKHADIRAN_DIKLAT = 9;
    const KETIDAKHADIRAN_CUTI_SAKIT = 10;
    const KETIDAKHADIRAN_DINAS_LUAR = 11;
    const KETIDAKHADIRAN_TUGAS_KEDINASAN = 12;
    const KETIDAKHADIRAN_ALASAN_TEKNIS = 13;
    const KETIDAKHADIRAN_ALASAN_KHUSUS = 14;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_panjang_jenis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
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
            'nama' => 'Nama',
        ];
    }

    public static function getList($params=[])
    {
        $query = Ketidakhadiranpanjangjenis::find();

        if (@$params['id_is_not'] != null) {
            $query->andWhere(['!=', 'id', @$params['id_is_not']]);
        }

        return ArrayHelper::map($query->all(),'id','nama');
    }

    public function getLabelNama()
    {
        return '<span class="label label-primary">' . $this->nama . '</span>';
    }
}
