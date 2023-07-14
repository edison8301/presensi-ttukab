<?php

namespace app\modules\kinerja\models;

use Yii;

/**
 * This is the model class for table "pegawai_rekap_tunjangan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property int $bulan
 * @property string $tahun
 * @property double $potongan_total
 */
class PegawaiRekapTunjangan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pegawai_rekap_tunjangan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_instansi', 'bulan', 'tahun'], 'required'],
            [['id', 'id_pegawai', 'id_instansi', 'bulan'], 'integer'],
            [['tahun'], 'safe'],
            [['potongan_total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'id_instansi' => 'Id Instansi',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'potongan_total' => 'Potongan Total',
        ];
    }
}
