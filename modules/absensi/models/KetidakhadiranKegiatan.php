<?php

namespace app\modules\absensi\models;

use Yii;
use app\models\Pegawai;
use app\models\User;

/**
 * This is the model class for table "ketidakhadiran_kegiatan".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property string $tanggal
 * @property int $id_ketidakhadiran_kegiatan_jenis
 * @property int $id_ketidakhadiran_kegiatan_keterangan
 * @property string $keterangan
 */
class KetidakhadiranKegiatan extends \yii\db\ActiveRecord
{
    use ValidasiMutasiTrait;

    public $nama_pegawai;
    public $id_instansi;
    public $nama_instansi;
    public $bulan = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ketidakhadiran_kegiatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'tanggal', 'id_ketidakhadiran_kegiatan_jenis', 'id_ketidakhadiran_kegiatan_keterangan'], 'required'],
            [['id_pegawai', 'id_ketidakhadiran_kegiatan_jenis', 'id_ketidakhadiran_kegiatan_keterangan',
                'id_ketidakhadiran_kegiatan_status'
            ], 'integer'],
            [['tanggal','status_hapus'], 'safe'],
            [['keterangan'], 'string'],
            [['tanggal'], 'validasiMutasiPegawai'],
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
            'tanggal' => 'Tanggal',
            'id_ketidakhadiran_kegiatan_jenis' => 'Id Ketidakhadiran Kegiatan Jenis',
            'id_ketidakhadiran_kegiatan_keterangan' => 'Jenis Keterangan',
            'id_ketidakhadiran_kegiatan_status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }

    public function getKetidakhadiranKegiatanJenis()
    {
        return $this->hasOne(KetidakhadiranKegiatanJenis::className(),['id'=>'id_ketidakhadiran_kegiatan_jenis']);
    }

    public function getKetidakhadiranKegiatanKeterangan()
    {
        return $this->hasOne(KetidakhadiranKegiatanKeterangan::className(),['id'=>'id_ketidakhadiran_kegiatan_keterangan']);
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(),['id'=>'id_pegawai'])->inverseOf('manyKetidakhadiranKegiatan');
    }

    public static function accessCreate()
    {
        return User::isAdmin()
            || User::isInstansi()
            || User::isAdminInstansi();
    }

    public static function accessUpdate($params = [])
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        if(User::isInstansi() || User::isAdminInstansi()) {
            if(isset($params['id_ketidakhadiran_kegiatan_jenis']) AND
                ($params['id_ketidakhadiran_kegiatan_jenis']==KetidakhadiranKegiatanJenis::APEL_PAGI OR
                $params['id_ketidakhadiran_kegiatan_jenis']==KetidakhadiranKegiatanJenis::APEL_SORE)
            ) {
                return true;
            }
        }

        return false;
    }

    public static function accessDelete($params = [])
    {
        if(User::isAdmin()) {
            return true;
        }
        if(User::isVerifikator()) {
            return true;
        }
        if(User::isInstansi() || User::isAdminInstansi()) {
            if(isset($params['id_ketidakhadiran_kegiatan_jenis']) AND
                ($params['id_ketidakhadiran_kegiatan_jenis']==KetidakhadiranKegiatanJenis::APEL_PAGI OR
                $params['id_ketidakhadiran_kegiatan_jenis']==KetidakhadiranKegiatanJenis::APEL_SORE)
            ) {
                return true;
            }
        }

        return false;
    }

    public function getPotongan()
    {
        if ($this->pegawai->getIsPegawaiDispensasi($this->tanggal) || $this->getIsLiburan()) {
            return 0;
        }
        if ((int) $this->id_ketidakhadiran_kegiatan_keterangan === Absensi::KETIDAKHADIRAN_KEGIATAN_TANPA_KETERANGAN) {
            return 2.5;
        }

        return 0;
    }

    public function accessIdKetidakhadiranKegiatanStatus()
    {
        if(User::isAdmin()) {
            return true;
        }
        if(User::isVerifikator()) {
            return true;
        }

        return false;
    }

    public function getLabelIdKetidakhadiranKegiatanStatus()
    {
        if($this->id_ketidakhadiran_kegiatan_status==Absensi::KETIDAKHADIRAN_SETUJU) {
            return '<span class="label label-success">Setuju</span>';
        }

        if($this->id_ketidakhadiran_kegiatan_status==Absensi::KETIDAKHADIRAN_PROSES) {
            return '<span class="label label-warning">Proses</span>';
        }

        if($this->id_ketidakhadiran_kegiatan_status==Absensi::KETIDAKHADIRAN_TOLAK) {
            return '<span class="label label-danger">Tolak</span>';
        }
    }

    public function getLabelIdKetidakhadiranKegiatanJenis()
    {
        return '<span class="label label-primary">'.@$this->ketidakhadiranKegiatanJenis->nama.'</span>';
    }

    public function isMasihPunyaJatah()
    {
        if ($this->isNewRecord) {
            //Izin tanpa keterangan boleh berapapun
            if($this->id_ketidakhadiran_kegiatan_keterangan==1) {
                return true;
            }

            $date = date_create($this->tanggal);

            $query = KetidakhadiranKegiatan::find();

            $query->andWhere('status_hapus IS NULL');

            $query->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
                ':tanggal_awal'=>$date->format('Y-m-01'),
                ':tanggal_akhir'=>$date->format('Y-m-t')
            ]);

            $query->andWhere('id_ketidakhadiran_kegiatan_keterangan != :id_ketidakhadiran_kegiatan_keterangan',[
                ':id_ketidakhadiran_kegiatan_keterangan'=>'1',
            ]);

            $query->andWhere([
                'id_pegawai'=>$this->id_pegawai,
                'id_ketidakhadiran_kegiatan_status'=>1
            ]);

            $jumlah = $query->count();

            print $jumlah;

            if($jumlah < 4) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function softDelete()
    {
        $this->status_hapus = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function getIsLiburan()
    {
        $ketidakHadiranPanjang = KetidakhadiranPanjang::find()
            ->andWhere(['id_pegawai' => $this->id_pegawai])
            ->andWhere(['<=', 'tanggal_mulai', $this->tanggal])
            ->andWhere(['>=', 'tanggal_selesai', $this->tanggal])
            ->one();
        $ketidakHadiranHariKerja = Ketidakhadiran::find()
            ->andWhere([
                'id_pegawai' => $this->id_pegawai,
                'tanggal' => $this->tanggal
            ])
            ->setuju()
            ->one();
        return $ketidakHadiranPanjang !== null || $ketidakHadiranHariKerja !== null;
    }

    public function getIsJenisApelPagi()
    {
        return (int) $this->id_ketidakhadiran_kegiatan_jenis === KetidakhadiranKegiatanJenis::APEL_PAGI;
    }

    public function getIsJenisApelSore()
    {
        return (int) $this->id_ketidakhadiran_kegiatan_jenis === KetidakhadiranKegiatanJenis::APEL_SORE;
    }

    public function beforeSave($insert)
    {
        if (($this->getIsJenisApelPagi() || $this->getIsJenisApelSore()) AND !User::isAdmin()) {
            $this->id_ketidakhadiran_kegiatan_status = KetidakhadiranKegiatanStatus::SETUJU;
        }
        return parent::beforeSave($insert);
    }
}
