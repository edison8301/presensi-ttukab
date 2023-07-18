<?php

namespace app\modules\iclock\models;

use app\models\InstansiPegawai;
use app\models\Kegiatan;
use app\models\Peta;
use Location\Coordinate;
use Location\Polygon;
use Yii;

/**
 * This is the model class for table "checkinout".
 *
 * @property integer $id
 * @property integer $userid
 * @property string $checktime
 * @property string $checktype
 * @property integer $verifycode
 * @property string $SN
 * @property string $sensorid
 * @property string $WorkCode
 * @property string $Reserved
 *
 * @property Iclock $sN
 * @property Userinfo $user
 */
class Checkinout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const STATUS_KIRIM_BELUM = 0;
    const STATUS_KIRIM_SUKSES = 1;
    const STATUS_KIRIM_GAGAL_KONEKSI = 2;
    const STATUS_KIRIM_TERIMA = 3;
    const STATUS_KIRIM_GAGAL_SIMPAN = 4;

    const DIDALAM_TIMOR_TENGAH_UTARA = 1;
    const DILUAR_TIMOR_TENGAH_UTARA = 2;

    const MESIN_ABSEN = 1;
    const MOBILE = 2;

    const DALAM_KANTOR = 1;
    const LUAR_KANTOR = 2;
    const TANPA_PETA = 3;

    public $nama_pegawai;
    public $id_instansi;
    public $userinfo_badgenumber;

    public $nip;

    public static function tableName()
    {
        return 'checkinout';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_iclock');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'checktime', 'checktype', 'verifycode'], 'required'],
            [['userid', 'verifycode'], 'integer'],
            [['checktime', 'userinfo_badgenumber'], 'safe'],
            [['checktype'], 'string', 'max' => 1],
            [['SN', 'WorkCode', 'Reserved'], 'string', 'max' => 20],
            [['sensorid'], 'string', 'max' => 5],
            [['userid', 'checktime'], 'unique', 'targetAttribute' => ['userid', 'checktime'], 'message' => 'The combination of Userid and Checktime has already been taken.'],
            [['SN'], 'exist', 'skipOnError' => true, 'targetClass' => Iclock::className(), 'targetAttribute' => ['SN' => 'SN']],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => Userinfo::className(), 'targetAttribute' => ['userid' => 'userid']],
            [['status_kirim'], 'integer'],
            [['waktu_kirim'], 'safe'],
            [['nip'], 'safe'],
            [['latitude', 'longitude'], 'string'],
            [['id_checkinout_sumber', 'status_lokasi', 'status_lokasi_kantor'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'checktime' => 'Checktime',
            'checktype' => 'Checktype',
            'verifycode' => 'Verifycode',
            'SN' => 'Sn',
            'sensorid' => 'Sensorid',
            'WorkCode' => 'Work Code',
            'Reserved' => 'Reserved',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSN()
    {
        return $this->hasOne(Iclock::className(), ['SN' => 'SN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserinfo()
    {
        return $this->hasOne(Userinfo::className(), ['userid' => 'userid']);
    }

    public function getPegawai()
    {
        return $this->hasOne(\app\models\Pegawai::className(), ['nip' => 'badgenumber'])
            ->via('userinfo');
    }

    public function getPegawaiUserinfo()
    {
        return $this->hasOne(\app\models\Pegawai::className(), ['nip' => 'badgenumber'])
            ->via('userinfo');
    }

    public function getKeterangan()
    {
        $interval = $this->getInterval();
        if ($interval->d == 0) {
            return "Hari Ini";
        } elseif ($interval->d == 1) {
            return "Kemarin";
        } elseif ($interval->d > 1) {
            return $interval->d . " Hari Lalu";
        }
    }

    public function getInterval()
    {
        $time1 = date_create($this->checktime);
        $time2 = date_create(date('Y-m-d H:i:s'));
        return date_diff($time1, $time2);
    }


    public function deleteDuplicate()
    {
        Checkinout::deleteAll('userid != :userid AND checktime = :checktime AND SN = :SN', [
            ':userid' => $this->userid,
            ':checktime' => $this->checktime,
            ':SN' => $this->SN
        ]);
    }

    public function getStatusHadir()
    {
        return true;
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->checktime);
        $jam = $datetime->format('H:i:s');

        $tanggal = $datetime->format('Y-m-d');

        $jadwalKehadiran = JadwalKehadiran::findOneByTanggal($tanggal);

        if (
            $jam >= $jadwalKehadiran->awal_masuk_kerja and $jam <= $jadwalKehadiran->akhir_masuk_kerja
            or $jam >= $jadwalKehadiran->awal_keluar_kerja and $jam <= $jadwalKehadiran->akhir_keluar_kerja
        ) {
            return "Tepat Waktu";
        } else {
            return "Di Luar Ketentuan Waktu";
        }
    }

    public function setLokasiAbsen()
    {
        return $this->status_lokasi = self::DIDALAM_TIMOR_TENGAH_UTARA;
        $ttu = Peta::findOne(1);
        $geofence = new Polygon();
        foreach ($ttu->findAllPetaPoint() as $petaPoint) {
            $geofence->addPoint(new Coordinate($petaPoint->latitude,$petaPoint->longitude));
        }

        if ($geofence->contains(new Coordinate($this->latitude, $this->longitude))) {
            $this->status_lokasi = self::DIDALAM_TIMOR_TENGAH_UTARA;
        } else {
            $this->status_lokasi = self::DILUAR_TIMOR_TENGAH_UTARA;
        }
    }

    public function getPetaBanyak()
    {
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->checktime);
        $tanggal = substr($this->checktime,0,9);

        if ($datetime != false) {
            $tanggal = $datetime->format('Y-m-d');
        }

        $query = InstansiPegawai::find();
        $query->joinWith(['pegawai']);
        $query->andWhere(['pegawai.nip'=> $this->nip]);
        $query->berlaku($tanggal);
        $instansiPegawai = $query->one();

        if($instansiPegawai === null) {
            return [];
        }

        $peta = Peta::findAll([
            'id_instansi' => $instansiPegawai->id_instansi
        ]);

        if($peta === null) {
            return [];
        }

        return $peta;
    }

    public function getPetaPegawaiBanyak($params=[])
    {
        $query = InstansiPegawai::find();
        $query->joinWith(['pegawai']);
        $query->andWhere(['pegawai.nip'=> $this->nip]);
        $query->berlaku(substr($this->checktime,0,9));
        $instansiPegawai = $query->one();

        if($instansiPegawai === null) {
            return [];
        }

        $peta = Peta::findAll([
            'id_pegawai' => $instansiPegawai->id_pegawai,
            'status_rumah' => @$params['status_rumah'],
        ]);

        if($peta === null) {
            return [];
        }

        return $peta;
    }

    public function getAllPetaKegiatan()
    {
        $query = Peta::find();
        $query->andWhere('id_instansi is null AND id_pegawai is null');

        $allPeta = $query->all();

        return $allPeta;
    }

    public function getAllKegiatan()
    {
        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $query = Kegiatan::find();
        $query->andWhere(['tanggal' => $datetime->format('Y-m-d')]);
        $query->andWhere(':jam_absen >= :jam_mulai_absen AND :jam_absen <= :jam_selesai_absen', [
            ':jam_absen' => date('H:i:s'),
        ]);

        return $query->all();
    }

    public function setStatusLokasiKantorBanyak()
    {
        if ($this->id_checkinout_sumber == 1) {
            return $this->status_lokasi_kantor = self::DALAM_KANTOR;
        }

        foreach ($this->getAllPetaKegiatan() as $peta) {
            $x = floatval($this->latitude) - floatval($peta->latitude);
            $y = floatval($this->longitude) - floatval($peta->longitude);

            $r = sqrt($x*$x + $y*$y);

            $batas = $peta->jarak * 0.00000909;

            if ($r <= $batas) {
                return $this->status_lokasi_kantor = self::DALAM_KANTOR;
            }
        }

        // peta pegawai
        $allPetaPegawaiBanyak = $this->getPetaPegawaiBanyak(['status_rumah' => 0]);

        foreach ($allPetaPegawaiBanyak as $peta) {

            $x = floatval($this->latitude) - floatval($peta->latitude);
            $y = floatval($this->longitude) - floatval($peta->longitude);

            $r = sqrt($x*$x + $y*$y);

            $batas = $peta->jarak * 0.00000909;

            if($r <= $batas) {
                return $this->status_lokasi_kantor = self::DALAM_KANTOR;
            }
        }

        $petaBanyak = $this->getPetaBanyak();

        if(count($petaBanyak) == 0) {
            return $this->status_lokasi_kantor = self::TANPA_PETA;
        }

        $status_lokasi_kantor = self::LUAR_KANTOR;

        foreach ($petaBanyak as $peta) {

            $x = floatval($this->latitude) - floatval($peta->latitude);
            $y = floatval($this->longitude) - floatval($peta->longitude);

            $r = sqrt($x*$x + $y*$y);

            $batas = $peta->jarak * 0.00000909;

            if($r <= $batas) {
                $status_lokasi_kantor = self::DALAM_KANTOR;
                return $this->status_lokasi_kantor = $status_lokasi_kantor;
            }
        }

        if (date('Y-m-d') < '2022-04-03') {
            // peta pegawai wfh
            $allPetaPegawaiBanyak = $this->getPetaPegawaiBanyak(['status_rumah' => 1]);
            foreach ($allPetaPegawaiBanyak as $peta) {

                $x = floatval($this->latitude) - floatval($peta->latitude);
                $y = floatval($this->longitude) - floatval($peta->longitude);

                $r = sqrt($x*$x + $y*$y);

                $batas = $peta->jarak * 0.00000909;

                if($r <= $batas) {
                    return $this->status_lokasi_kantor = self::DALAM_KANTOR;
                }
            }
        }

        return $this->status_lokasi_kantor = $status_lokasi_kantor;
    }

    public function getStringPosisiAbsen()
    {
        if ($this->status_lokasi == self::DIDALAM_TIMOR_TENGAH_UTARA) {
            return "Didalam Timor Tengah Utara";
        }
        return "Diluar Timor Tengah Utara";
    }

    public function getNamaStatusLokasiKantor()
    {
        if($this->status_lokasi_kantor == 1) {
            return 'Dalam Kantor';
        }

        if($this->status_lokasi_kantor == 2) {
            return 'Luar Kantor';
        }

        if($this->status_lokasi_kantor == 3) {
            return 'Instansi Pegawai Tidak Ada';
        }

        if($this->status_lokasi_kantor == 4) {
            return 'Peta Tidak Ada';
        }
    }
}
