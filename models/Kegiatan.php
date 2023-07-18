<?php

namespace app\models;

use app\modules\iclock\models\Checkinout;
use app\modules\iclock\models\Userinfo;
use Yii;
use yii\helpers\Html;

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

    public function findAllPegawaiChecktime()
    {
        $queryCheckinout = Checkinout::find();
        $queryCheckinout->joinWith('userinfo');
        $queryCheckinout->andWhere('checktime >= :tanggal_awal AND checktime <= :tanggal_akhir', [
            ':tanggal_awal' => $this->tanggal . ' ' . $this->jam_mulai_absen,
            ':tanggal_akhir' => $this->tanggal . ' ' . $this->jam_selesai_absen,
        ]);

        $arrayBadgenumber = $queryCheckinout->select('userinfo.badgenumber')->column();

        $query = Pegawai::find();
        $query->andWhere(['nip' => $arrayBadgenumber]);

        return $query->all();
    }

    public function getAllChecktimePegawai(array $params = [])
    {
        $id_pegawai = $params['id_pegawai'];
        $pegawai = Pegawai::findOne($id_pegawai);

        $query = $pegawai->getManyCheckinout();
        $query->andWhere('checktime >= :tanggal_awal AND checktime <= :tanggal_akhir', [
            ':tanggal_awal' => $this->tanggal . ' ' . $this->jam_mulai_absen,
            ':tanggal_akhir' => $this->tanggal . ' ' . $this->jam_selesai_absen,
        ]);

        $checktime = [];

        foreach ($query->all() as $checkinout) {
            $checktime[] = $checkinout->checktime;
        }

        return $checktime;
    }

    public function getStatusHadirPegawai(array $params = [])
    {
        $allChecktimePegawai = $this->getAllChecktimePegawai($params);

        if (count($allChecktimePegawai) == 0) {
            return 'Tidak Hadir';
        }

        return 'Hadir';
    }

    public function getLinkExportExcelButton(array $params = [])
    {
        return Html::a('<i class="fa fa-file-excel-o"></i> Excel', [
            '/kegiatan/export-excel-rekap',
            'id' => $this->id,
            'id_instansi' => @$params['id_instansi']
        ], ['class' => 'btn btn-success btn-flat btn-xs']);
    }

    public function getLinkExporPdfButton(array $params = [])
    {
        return Html::a('<i class="fa fa-file-pdf-o"></i> PDF', [
            '/kegiatan/export-pdf-rekap',
            'id' => $this->id,
            'id_instansi' => @$params['id_instansi']
        ], ['class' => 'btn btn-primary btn-flat btn-xs', 'target' => '_blank']);
    }
}
