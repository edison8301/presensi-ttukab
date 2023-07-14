<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "pegawai_rekap_kinerja".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_instansi
 * @property int $bulan
 * @property int $tahun
 * @property double $potongan_skp
 * @property double $potongan_ckhp
 * @property double $potongan_total
 * @property string $waktu_buat
 * @property string $waktu_update
 */
class PegawaiRekapKinerja extends \yii\db\ActiveRecord implements RekapTunjanganInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai_rekap_kinerja';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'id_instansi', 'bulan'], 'required'],
            [['id_pegawai', 'id_instansi', 'bulan', 'tahun'], 'integer'],
            [['potongan_skp', 'potongan_ckhp', 'potongan_total'], 'number'],
            [['waktu_buat', 'waktu_update'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Id Pegawai',
            'id_instansi' => 'Id Instansi',
            'bulan' => 'Bulan',
            'potongan_skp' => 'Potongan Skp',
            'potongan_ckhp' => 'Potongan Ckhp',
            'potongan_total' => 'Potongan Total',
            'waktu_buat' => 'Waktu Buat',
            'waktu_update' => 'Waktu Diperbarui',
        ];
    }

    public function getPotonganTotal()
    {
        return $this->potongan_total;
    }

    public function getPersen()
    {
        return 100 - $this->getPotonganTotal();
    }
}
