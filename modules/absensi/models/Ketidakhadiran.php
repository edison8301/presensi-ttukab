<?php

namespace app\modules\absensi\models;

use Yii;
use app\components\Helper;
use app\models\Pegawai;
use app\models\Pengaturan;
use app\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ketidakhadiran".
 *
 * @property integer $id
 * @property integer $id_pegawai
 * @property string $tanggal
 * @property integer $id_jam_kerja
 * @property integer $id_ketidakhadiran_jenis
 * @property integer $id_ketidakhadiran_status
 * @property string $berkas
 * @property string $keterangan
 */
class Ketidakhadiran extends \app\components\RangeActiveRecord
{

    public $bulan;
    public $nama_pegawai;
    public $id_unit_kerja;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran';
    }

    public function behaviors()
    {
        return [
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal', 'id_ketidakhadiran_jenis', 'id_ketidakhadiran_status', 'keterangan'], 'required'],
            [['keterangan'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['keterangan'], 'filter', 'filter' => 'trim'],
            [['id_pegawai', 'id_jam_kerja', 'id_ketidakhadiran_jenis', 'id_ketidakhadiran_status', 'id_unit_kerja'], 'integer'],
            [['tanggal', 'status_hapus'], 'safe'],
            [['keterangan', 'nama_pegawai'], 'string'],
            [['berkas'], 'string', 'max' => 255],
            [['tanggal'], 'validasiRentang'],
            [['id_ketidakhadiran_jenis'], 'validateKetidakhadiranAktif'],
        ];
    }

    public function validateKetidakhadiranAktif($attribute, $params)
    {
        if (!$this->hasErrors() && $this->isNewRecord && !$this->ketidakhadiranJenis->isAktif) {
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
            'id_ketidakhadiran_jenis' => 'Jenis',
            'id_ketidakhadiran_status' => 'Status',
            'berkas' => 'Berkas',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * @inheritdoc
     * @return PegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KetidakhadiranQuery(get_called_class());
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['id' => 'id_pegawai']);
    }

    public function getTanggal()
    {
        return $this->tanggal;
    }

    public function getJamKerja()
    {
        return $this->hasOne(JamKerja::className(), ['id' => 'id_jam_kerja']);
    }

    public function getKetidakhadiranJenis()
    {
        return $this->hasOne(KetidakhadiranJenis::className(), ['id' => 'id_ketidakhadiran_jenis']);
    }

    public function getKetidakhadiranStatus()
    {
        return $this->hasOne(KetidakhadiranStatus::className(), ['id' => 'id_ketidakhadiran_status']);
    }

    public function accessIdKetidakhadiranStatus()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        return false;
    }

    public function accessUpdate()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi() and $this->id_ketidakhadiran_status == Absensi::KETIDAKHADIRAN_PROSES) {
            return true;
        }

        return false;
    }

    public function accessDelete()
    {
        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        if (User::isInstansi() and $this->id_ketidakhadiran_status == Absensi::KETIDAKHADIRAN_PROSES) {
            return true;
        }

        return false;
    }

    public function accessSetSetuju()
    {
        if ($this->id_ketidakhadiran_status != Absensi::KETIDAKHADIRAN_PROSES) {
            return false;
        }

        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        return false;
    }

    public function accessSetTolak()
    {
        if ($this->id_ketidakhadiran_status != Absensi::KETIDAKHADIRAN_PROSES) {
            return false;
        }

        if (User::isAdmin()) {
            return true;
        }

        if (User::isVerifikator()) {
            return true;
        }

        return false;
    }

    public function getLabelIdKetidakhadiranStatus()
    {
        if ($this->id_ketidakhadiran_status == Absensi::KETIDAKHADIRAN_SETUJU) {
            return '<span class="label label-success">Setuju</span>';
        }

        if ($this->id_ketidakhadiran_status == Absensi::KETIDAKHADIRAN_PROSES) {
            return '<span class="label label-warning">Proses</span>';
        }

        if ($this->id_ketidakhadiran_status == Absensi::KETIDAKHADIRAN_TOLAK) {
            return '<span class="label label-danger">Tolak</span>';
        }
    }

    public function getLabelIdKetidakhadiranJenis()
    {
        return '<span class="label label-primary">' . $this->ketidakhadiranJenis->nama . '</span>';
    }

    public function softDelete()
    {
        $this->status_hapus = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function getMasa()
    {
        if ($this->bulan != null) {
            return Helper::getBulanLengkap($this->bulan) . ' ' . User::getTahun();
        }
        if ($this->tanggal != null) {
            return Helper::getHariTanggal($this->tanggal);
        }

        return User::getTahun();
    }

    public function setKetidakhadiranStatus($status)
    {
        $this->id_ketidakhadiran_status = $status;
    }

    public function getIsDisetujui()
    {
        return (int) $this->id_ketidakhadiran_status === KetidakhadiranStatus::SETUJU;
    }

    public function getIsWajibCkhp()
    {
        if ($this->getIsDisetujui() && in_array($this->id_ketidakhadiran_jenis, [
            KetidakhadiranJenis::SAKIT,
            KetidakhadiranJenis::CUTI,
            KetidakhadiranJenis::TUGAS_BELAJAR
        ])) {
            return $this->id_ketidakhadiran_jenis;
        }
        return false;
    }

    public function validasiRentang($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ((User::isPegawai() || User::isInstansi()) && !$this->getIsInRange(date('Y-m-d'))) {
                $this->addError($attribute, 'Batas pengajuan ketidakhadiran maksimal 5 (Lima) hari kerja dari sebelumnya.');
            }
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
