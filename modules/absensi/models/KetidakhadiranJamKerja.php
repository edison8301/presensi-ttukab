<?php

namespace app\modules\absensi\models;

use app\components\Session;
use app\modules\tukin\models\InstansiPegawai;
use Yii;
use app\components\Helper;
use app\models\Pegawai;
use app\models\User;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ketidakhadiran".
 *
 * @property integer $id
 * @property integer $id_pegawai
 * @property integer $id_instansi
 * @property string $tanggal
 * @property integer $id_jam_kerja
 * @property integer $id_ketidakhadiran_jam_kerja_jenis
 * @property integer $id_ketidakhadiran_jam_kerja_status
 * @property string $berkas
 * @property string $keterangan
 * @property string $status_hapus [datetime]
 * @property string $waktu_dibuat [datetime]
 * @property int $id_user_pembuat [int(11)]
 * @property string $waktu_disunting [datetime]
 * @property int $id_user_penyunting [int(11)]
 *
 * @property string $labelIdKetidakhadiranJamKerjaJenis
 * @property InstansiPegawai $instansiPegawai
 * @property bool $isProses
 * @property mixed $ketidakhadiranJamKerjaStatus
 * @property mixed $jamKerja
 * @property KetidakhadiranJamKerjaJenis $ketidakhadiranJamKerjaJenis
 * @property mixed $userPembuat
 * @property bool $isDisetujui
 * @property mixed $userPenyunting
 * @property bool $isDitolak
 * @property string $labelIdKetidakhadiranJamKerjaStatus
 * @property Pegawai $pegawai
 */
class KetidakhadiranJamKerja extends \app\components\RangeActiveRecord
{
    use ValidasiMutasiTrait;

    public $bulan;
    public $nama_pegawai;
    public $id_unit_kerja;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_jam_kerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal', 'id_ketidakhadiran_jam_kerja_jenis', 'id_ketidakhadiran_jam_kerja_status'], 'required'],
            [['id_pegawai', 'id_jam_kerja', 'id_ketidakhadiran_jam_kerja_jenis', 'id_ketidakhadiran_jam_kerja_status', 'id_unit_kerja'], 'integer'],
            ['id_ketidakhadiran_jam_kerja_jenis', 'filter', 'filter' => 'intval'],
            [['tanggal', 'status_hapus'], 'safe'],
            [['id_ketidakhadiran_jam_kerja_jenis'], 'validateUnik'],
            [['id_ketidakhadiran_jam_kerja_jenis'], 'validateJatahIzin'],
            [['keterangan', 'nama_pegawai'], 'string'],
            [['berkas'], 'string', 'max' => 255],
            [['tanggal'], 'validasiRentang'],
            [['id_ketidakhadiran_jam_kerja_jenis'], 'validateKetidakhadiranAktif'],
            [['tanggal'], 'validasiMutasiPegawai'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'status_hapus' => date('Y-m-d H:i:s')
                ],
            ],
            'blameableBehavior' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'id_user_pembuat',
                'updatedByAttribute' => 'id_user_penyunting',
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'waktu_dibuat',
                'updatedAtAttribute' => 'waktu_disunting',
                'value' => date('Y-m-d H:i:s'),
            ],
            'attributeBehavior' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'id_instansi',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'id_instansi',
                ],
                'value' => function ($event) {
                    $pegawai = $this->pegawai;
                    if ($pegawai !== null
                        && ($instansiPegawai = $pegawai->getInstansiPegawaiBerlaku($this->tanggal)) !== null) {
                        return $instansiPegawai->id_instansi;
                    }
                    return null;
                }
            ],
        ];
    }

    public function getInstansiPegawai()
    {
        return $this->pegawai->getInstansiPegawaiBerlaku($this->tanggal);
    }

    public function validateKetidakhadiranAktif($attribute, $params)
    {
        if (!$this->hasErrors() && $this->isNewRecord && !$this->ketidakhadiranJamKerjaJenis->isAktif) {
            $this->addError($attribute, 'Jenis Izin tidak valid');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'tanggal' => 'Tanggal',
            'id_jam_kerja' => 'Jam Kerja',
            'id_ketidakhadiran_jam_kerja_jenis' => 'Jenis',
            'id_ketidakhadiran_jam_kerja_status' => 'Status',
            'berkas' => 'Berkas',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * {@inheritdoc}
     * @return KetidakhadiranJamKerjaJenisQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KetidakhadiranJamKerjaJenisQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getTanggal()
    {
        return $this->tanggal;
    }

    public function getJamKerja()
    {
        return $this->hasOne(JamKerja::className(), ['id' => 'id_jam_kerja']);
    }

    public function getKetidakhadiranJamKerjaJenis()
    {
        return $this->hasOne(KetidakhadiranJamKerjaJenis::className(), ['id' => 'id_ketidakhadiran_jam_kerja_jenis']);
    }

    public function getKetidakhadiranJamKerjaStatus()
    {
        return $this->hasOne(KetidakhadiranJamKerjaStatus::className(), ['id' => 'id_ketidakhadiran_jam_kerja_status']);
    }

    public function accessIdKetidakhadiranJamKerjaStatus()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public static function accessCreate()
    {
        if (User::isAdmin()) {
            return true;
        }
        if (User::isVerifikator() || User::isAdminInstansi() || User::isInstansi() || User::isOperatorAbsen()) {
            return true;
        }
        return false;
    }

    public function accessUpdate()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        if ((User::isInstansi() || User::isOperatorAbsen()) and $this->id_ketidakhadiran_jam_kerja_status == Absensi::KETIDAKHADIRAN_PROSES) {
            return true;
        }

        return false;
    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        if ((User::isInstansi() || User::isOperatorAbsen()) and $this->id_ketidakhadiran_jam_kerja_status == Absensi::KETIDAKHADIRAN_PROSES) {
            return true;
        }

        return false;
    }

    public function accessSetSetuju()
    {
        if ($this->id_ketidakhadiran_jam_kerja_status != Absensi::KETIDAKHADIRAN_PROSES) {
            return false;
        }

        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public function accessSetTolak()
    {
        if ($this->id_ketidakhadiran_jam_kerja_status != Absensi::KETIDAKHADIRAN_PROSES) {
            return false;
        }

        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public function getLabelIdKetidakhadiranJamKerjaStatus()
    {
        if ($this->id_ketidakhadiran_jam_kerja_status == Absensi::KETIDAKHADIRAN_SETUJU) {
            return '<span class="label label-success">Setuju</span>';
        }

        if ($this->id_ketidakhadiran_jam_kerja_status == Absensi::KETIDAKHADIRAN_PROSES) {
            return '<span class="label label-warning">Proses</span>';
        }

        if ($this->id_ketidakhadiran_jam_kerja_status == Absensi::KETIDAKHADIRAN_TOLAK) {
            return '<span class="label label-danger">Tolak</span>';
        }
    }

    public function getLabelIdKetidakhadiranJamKerjaJenis()
    {
        return '<span class="label label-primary">' . $this->ketidakhadiranJamKerjaJenis->nama . '</span>';
    }

    public function validateJatahIzin($attribute, $params, $validator)
    {
        if ($this->isMasihPunyaJatahIzin() == false) {
            $this->addError('id_ketidakhadiran_jam_kerja_jenis', 'Pegawai sudah melewati batas pengajuan izin jam kerja sebanyak 4 kali dalam 1 bulan');
        }
    }

    public function validateUnik($attribute, $params, $validator)
    {
        if (!$this->hasErrors() && $this->isNewRecord) {
            $result = static::find()
                ->andWhere('status_hapus IS NULL')
                ->andWhere([
                    'id_jam_kerja' => $this->id_jam_kerja,
                    'tanggal' => $this->tanggal,
                    'id_pegawai' => $this->id_pegawai,
                ])
                ->andWhere([
                    'or',
                    ['id_ketidakhadiran_jam_kerja_status' => KetidakhadiranStatus::SETUJU],
                    ['id_ketidakhadiran_jam_kerja_status' => KetidakhadiranStatus::PROSES],
                ])
                ->exists();
            if ($result === true) {
                $this->addError(
                    $attribute,
                    'Anda telah mengajukan izin pada tanggal ' .
                    Helper::getTanggal($this->tanggal) .
                    ' pada jam kerja ' . @$this->jamKerja->nama .
                    ', silahkan edit izin ketidakhadiran untuk mengubah keterangan ketidakhadiran'
                );
            }
        }
    }

    public function isMasihPunyaJatahIzin()
    {
        if ($this->id_ketidakhadiran_jam_kerja_jenis != KetidakhadiranJamKerjaJenis::IZIN) {
            return true;
        }

        $date = \DateTime::createFromFormat('Y-m-d',$this->tanggal);

        $arrayIdJamKerja = [];
        for ($i=1; $i <= $date->format('t'); $i++) {
            $datetime = \DateTime::createFromFormat('Y-n-d', $date->format('Y-n') . '-' . $i);
            
            $shiftKerja = $this->pegawai->findShiftKerja($datetime->format('Y-m-d'));

            if ($shiftKerja != null) {
                foreach ($shiftKerja->findAllJamKerja($datetime->format('N')) as $jamKerja) {
                    $arrayIdJamKerja[] = $jamKerja->id;
                }
            }
        }

        $query = KetidakhadiranJamKerja::find();
        $query->andWhere([
            'id_pegawai' => $this->id_pegawai,
            'id_ketidakhadiran_jam_kerja_jenis' => KetidakhadiranJamKerjaJenis::IZIN,
        ]);
        $query->andWhere('status_hapus IS NULL');
        $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir', [
            ':tanggal_awal' => $date->format('Y-m-01'),
            ':tanggal_akhir' => $date->format('Y-m-t'),
        ]);

        $query->andWhere('id_ketidakhadiran_jam_kerja_status = :setuju OR id_ketidakhadiran_jam_kerja_status = :proses', [
            ':setuju' => KetidakhadiranStatus::SETUJU,
            ':proses' => KetidakhadiranStatus::PROSES,
        ]);
        $query->andWhere(['not in', 'tanggal', $this->getArrayTanggalKetidakhadiranPanjang()]);
        $query->andWhere(['in', 'id_jam_kerja', $arrayIdJamKerja]);

        $count = $query->count();

        if($this->isNewRecord AND $count < 4) {
            return true;
        }

        if($this->isAttributeChanged('id_ketidakhadiran_jam_kerja_jenis') == true AND $count < 4) {
            return true;
        }

        if($this->isAttributeChanged('id_ketidakhadiran_jam_kerja_jenis') == false AND $count <= 4) {
            return true;
        }

        return false;

    }

    public function getArrayTanggalKetidakhadiranPanjang()
    {
        $datetime = \DateTime::createFromFormat('Y-m-d',$this->tanggal);

        $query = KetidakhadiranPanjang::find();
        $query->andWhere([
            'id_pegawai' => $this->id_pegawai,
        ]);
        $query->andWhere('tanggal_mulai >= :tanggal_mulai AND tanggal_selesai <= :tanggal_selesai', [
            ':tanggal_mulai' => $datetime->format('Y-m-01'),
            ':tanggal_selesai' => $datetime->format('Y-m-t'),
        ]);

        $array = [];

        foreach ($query->all() as $ketidakhadiranPanjang) {
            $begin = new \DateTime($ketidakhadiranPanjang->tanggal_mulai);
            $end = new \DateTime($ketidakhadiranPanjang->tanggal_selesai);
            $end->modify('+1 days');

            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                $tanggal = $dt->format('Y-m-d');
                $array[$tanggal] = $tanggal;
            }
        }

        return $array;
    }

    public function getIsDisetujui()
    {
        return (int) $this->id_ketidakhadiran_jam_kerja_status === Absensi::KETIDAKHADIRAN_SETUJU;
    }

    public function getIsProses()
    {
        return (int) $this->id_ketidakhadiran_jam_kerja_status === Absensi::KETIDAKHADIRAN_PROSES;
    }

    public function getIsDitolak()
    {
        return (int) $this->id_ketidakhadiran_jam_kerja_status === Absensi::KETIDAKHADIRAN_TOLAK;
    }

    public function validasiRentang($attribute, $params)
    {
        if(Session::isAdmin()) {
            return true;
        }

        if($this->isNewRecord == false) {
            return true;
        }

        if ($this->getIsInRange() == false) {
            $this->addError($attribute, 'Batas pengajuan ketidakhadiran maksimal 5 (Lima) hari kerja dari sebelumnya.');
        }
    }

    public function getUserPembuat()
    {
        return $this->hasOne(User::class, ['id' => 'id_user_pembuat']);
    }

    public function getUserPenyunting()
    {
        return $this->hasOne(User::class, ['id' => 'id_user_penyunting']);
    }
}
