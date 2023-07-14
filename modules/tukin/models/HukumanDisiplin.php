<?php

namespace app\modules\tukin\models;

use Yii;

/**
 * This is the model class for table "hukuman_disiplin".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_hukuman_disiplin_jenis
 * @property int $bulan
 * @property string $tahun
 * @property string $keterangan
 * @property int $status_hapus
 * @property string $waktu_dihapus
 *
 * @property HukumanDisiplinJenis $hukumanDisiplinJenis
 * @property float|int $potongan
 */
class HukumanDisiplin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hukuman_disiplin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'id_hukuman_disiplin_jenis', 'keterangan'], 'required'],
            [['id_pegawai', 'id_hukuman_disiplin_jenis', 'bulan', 'status_hapus'], 'integer'],
            [['tahun', 'waktu_dihapus'], 'safe'],
            [['keterangan'], 'string', 'max' => 255],
            [['tanggal_mulai','tanggal_selesai'], 'safe']
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
            'id_hukuman_disiplin_jenis' => 'Id Hukuman Disiplin Jenis',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'keterangan' => 'Keterangan',
            'status_hapus' => 'Status Hapus',
            'waktu_dihapus' => 'Waktu Dihapus',
        ];
    }

    /**
     * {@inheritdoc}
     * @return HukumanDisiplinQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new HukumanDisiplinQuery(get_called_class());
        $query->andWhere('status_hapus = 0');
        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHukumanDisiplinJenis()
    {
        return $this->hasOne(HukumanDisiplinJenis::class, ['id' => 'id_hukuman_disiplin_jenis']);
    }

    public function getPotongan()
    {
        return @$this->hukumanDisiplinJenis->potongan;
    }
}
