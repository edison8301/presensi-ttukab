<?php

namespace app\modules\absensi\models;

use app\components\Session;
use app\models\Instansi;
use app\models\InstansiPegawai;
use Yii;
use app\models\Pegawai;
use app\models\User;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ketidakhadiran_panjang".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property integer $id_instansi
 * @property int $id_ketidakhadiran_panjang_jenis
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $id_ketidakhadiran_panjang_status
 * @property string $keterangan [varchar(255)]
 * @property bool $isDisetujui
 * @property Pegawai $pegawai
 * @property bool $isDiklat
 * @property bool $isProses
 * @property bool $isDitolak
 * @property KetidakhadiranPanjangJenis $ketidakhadiranPanjangJenis
 * @property KetidakhadiranPanjangStatus $ketidakhadiranPanjangStatus
 * @property InstansiPegawai $instansiPegawai
 * @property InstansiPegawai[] $allInstansiPegawai
 * @property mixed $instansi
 * @property string $status_hapus [date]
 * @property string waktu_dibuat
 * @property string $waktu_disunting
 * @property int $id_user_pembuat
 * @property int $id_user_penyunting
 * @property User $userPembuat
 * @property User $userPenyunting
 *
 */
class KetidakhadiranPanjang extends \yii\db\ActiveRecord
{
    use ValidasiMutasiTrait;

    public $nama_pegawai;
    public $id_unit_kerja;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_panjang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'id_ketidakhadiran_panjang_jenis','id_ketidakhadiran_panjang_status', 'tanggal_mulai', 'tanggal_selesai', 'keterangan'], 'required'],
            [['id_pegawai', 'id_ketidakhadiran_panjang_jenis', 'id_ketidakhadiran_panjang_status','id_unit_kerja'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai','nama_pegawai'], 'safe'],
            ['keterangan', 'string', 'max' => 255],
            [['tanggal_mulai', 'tanggal_selesai'], 'validasiMutasiPegawai'],
            [['waktu_dibuat', 'id_user_pembuat', 'waktu_disunting', 'id_user_penyunting'], 'safe'],
        ];
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
            'attributeBehavior' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'id_instansi',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'id_instansi',
                ],
                'value' => function ($event) {

                    if(Session::isAdminInstansi()) {
                        return Session::getIdInstansi();
                    }

                    if(Session::isInstansi()) {
                        return Session::getIdInstansi();
                    }

                    $pegawai = $this->pegawai;
                    $instansiPegawai = null;

                    if($pegawai !== null) {
                        $instansiPegawai = $pegawai->getInstansiPegawaiBerlaku($this->tanggal_mulai);
                    }

                    if ($pegawai !== null && $instansiPegawai !== null) {
                        return $instansiPegawai->id_instansi;
                    }

                    return null;
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pegawai' => 'Pegawai',
            'id_ketidakhadiran_panjang_jenis' => 'Jenis',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'id_ketidakhadiran_panjang_status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }


    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(),['id'=>'id_pegawai']);
    }

    public function getAllInstansiPegawai()
    {
        return $this->hasMany(InstansiPegawai::className(), ['id_pegawai' => 'id'])
            ->orderBy(['tanggal_berlaku'=>SORT_ASC])
            ->via('pegawai');
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getInstansiPegawai()
    {
        return $this->hasOne(InstansiPegawai::class, ['id_pegawai' => 'id'])
            ->via('pegawai')
            ->aktif()
            ->andWhere(['<=', 'instansi_pegawai.tanggal_mulai', $this->tanggal_mulai])
            ->andWhere(['>=', 'instansi_pegawai.tanggal_selesai', $this->tanggal_mulai]);
    }

    public function getKetidakhadiranPanjangJenis()
    {
        return $this->hasOne(KetidakhadiranPanjangJenis::className(),['id'=>'id_ketidakhadiran_panjang_jenis']);
    }

    public function getKetidakhadiranPanjangStatus()
    {
        return $this->hasOne(KetidakhadiranPanjangStatus::className(),['id'=>'id_ketidakhadiran_panjang_status']);
    }

    public function getUserPembuat()
    {
        return $this->hasOne(User::class, ['id' => 'id_user_pembuat']);
    }

    public function getUserPenyunting()
    {
        return $this->hasOne(User::class, ['id' => 'id_user_penyunting']);
    }

    public function accessCreate($params=[])
    {
        if(User::isAdmin()) {
            return true;
        }
        if(User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }
        if(User::isInstansi()) {
            return true;
        }

        return false;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }
        if(User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }
        if(User::isInstansi() AND $this->id_ketidakhadiran_panjang_status==2) {
            return true;
        }

        return false;
    }

    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }
        if(User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }
        if(User::isInstansi() AND
            $this->id_ketidakhadiran_panjang_status==2 AND
            @$this->pegawai->id_instansi = User::getIdInstansi()
        ) {
            return true;
        }

        return false;
    }

    public function accessSetSetuju()
    {
        if($this->id_ketidakhadiran_panjang_status!=2) {
            return false;
        }

        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public function accessSetTolak()
    {
        if($this->id_ketidakhadiran_panjang_status!=2) {
            return false;
        }

        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public function accessIdKetidakhadiranPanjangStatus()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator() || User::isAdminInstansi()) {
            return true;
        }

        return false;
    }

    public function getIsDiklat()
    {
        return $this->id_ketidakhadiran_panjang_jenis === KetidakhadiranPanjangJenis::KETIDAKHADIRAN_DIKLAT;
    }

    public function getIsDisetujui()
    {
        return (int) $this->id_ketidakhadiran_panjang_status === KetidakhadiranPanjangStatus::SETUJU;
    }

    public function getIsProses()
    {
        return (int) $this->id_ketidakhadiran_panjang_status === KetidakhadiranPanjangStatus::PROSES;
    }

    public function getIsDitolak()
    {
        return (int) $this->id_ketidakhadiran_panjang_status === KetidakhadiranPanjangStatus::TOLAK;
    }

    public function getIsWajibCkhp($tanggal)
    {
        if (
            ($tanggal >= $this->tanggal_mulai && $tanggal <= $this->tanggal_selesai) &&
            $this->getIsDisetujui() &&
            (
                in_array(@$this->ketidakhadiranPanjangJenis->id_ketidakhadiran_jenis, [2, 3, 5]) OR
                (int) $this->id_ketidakhadiran_panjang_jenis === KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS
            )
        ) {
            if ((int) $this->id_ketidakhadiran_panjang_jenis === KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS) {
                return KetidakhadiranPanjangJenis::KETIDAKHADIRAN_ALASAN_KHUSUS;
            }
            return @$this->ketidakhadiranPanjangJenis->id_ketidakhadiran_jenis;
        }
        return false;
    }
}
