<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tunjangan_potongan".
 *
 * @property int $id
 * @property string $nama
 */
class TunjanganPotongan extends \yii\db\ActiveRecord
{

    const TIDAK_ADA_DUPAK = 1;
    const BELUM_DIANGKAT_JF_SELAMA_7_TAHUN = 2;
    const LHKPN = 3;
    const TPTGR = 4;
    const CPNS = 5;
    const CUTI_BESAR_ALASAN_KEAGAMAAN = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tunjangan_potongan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Jenis Potongan',
        ];
    }

    public function getManyTunjanganPotonganPegawai()
    {
        return $this->hasMany(TunjanganPotonganPegawai::className(), ['id_tunjangan_potongan' => 'id']);
    }

    public function getManyTunjanganPotonganNilai()
    {
        return $this->hasMany(TunjanganPotonganNilai::className(), ['id_tunjangan_potongan' => 'id'])
            ->orderBy(['tanggal_selesai' => SORT_DESC]);
    }

    public function getOneTunjanganPotonganNilai()
    {
        return $this->hasOne(TunjanganPotonganNilai::className(), ['id_tunjangan_potongan' => 'id'])
            ->orderBy(['tanggal_selesai' => SORT_DESC]);
    }

    public function countTunjanganPotonganPegawai()
    {
        return $this->getManyTunjanganPotonganPegawai()
            ->count();
    }

    public function getPersenPotongan()
    {
        return @$this->oneTunjanganPotonganNilai->nilai;
    }

    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'nama');
    }
}
