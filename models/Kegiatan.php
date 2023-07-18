<?php

namespace app\models;

use app\modules\iclock\models\Checkinout;
use app\modules\iclock\models\Userinfo;
use Yii;

/**
 * This is the model class for table "kegiatan".
 *
 * @property int $id
 * @property string $nama
 * @property string $tanggal
 * @property string $jam_mulai_absen
 * @property string $jam_selesai_absen
 */
class Kegiatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'tanggal', 'jam_mulai_absen', 'jam_selesai_absen'], 'required'],
            [['tanggal', 'jam_mulai_absen', 'jam_selesai_absen'], 'safe'],
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
            'nama' => 'Nama',
            'tanggal' => 'Tanggal',
            'jam_mulai_absen' => 'Jam Mulai Absen',
            'jam_selesai_absen' => 'Jam Selesai Absen',
        ];
    }

    public function findAllPegawai()
    {
        $queryCheckinout = Checkinout::find();
        $queryCheckinout->andWhere('checktime >= :tanggal_awal AND checktime <= :tanggal_akhir', [
            ':tanggal_awal' => $this->tanggal . ' ' . $this->jam_mulai_absen,
            ':tanggal_akhir' => $this->tanggal . ' ' . $this->jam_selesai_absen,
        ]);

        $arrayUserid = $queryCheckinout->select('userid')->column();

        $queryUserinfo = Userinfo::find();
        $queryUserinfo->andWhere(['userinfo' => $arrayUserid]);

        $arrayBadgenumber = [];

        $query = Pegawai::find();
        $query->andWhere(['nip' => $arrayBadgenumber]);

        return $query->all();
    }
}
