<?php

namespace app\modules\absensi\models;

use app\components\Helper;
use app\components\Session;
use app\models\Instansi;
use DateTime;
use Yii;
use app\models\Pegawai;
use app\models\UserMenu;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "hukuman_disiplin".
 *
 * @property int $id
 * @property int $id_pegawai
 * @property int $id_hukuman_disiplin_jenis
 * @property int $bulan
 * @property int $tahun
 * @property string $keterangan
 * @property int $status_hapus
 * @property HukumanDisiplinJenis $hukumanDisiplinJenis
 * @property mixed $pegawai
 * @property bool $isHukumanRingan
 * @property mixed $potongan
 * @property bool $isHukumanBerat
 * @property bool $isHukumanSedang
 * @property string $waktu_dihapus
 */
class HukumanDisiplin extends \yii\db\ActiveRecord
{
    protected $_tanggal_akhir;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hukuman_disiplin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'id_instansi', 'id_hukuman_disiplin_jenis', 'keterangan',
                'tanggal_mulai'
            ], 'required'],
            [['id_pegawai', 'id_instansi', 'id_hukuman_disiplin_jenis', 'status_hapus',
                'bulan'
            ], 'integer'],
            [['waktu_dihapus', 'tahun'], 'safe'],
            [['keterangan'], 'string', 'max' => 255],
            [['tanggal_mulai','tanggal_selesai'], 'safe']
        ];
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'status_hapus' => true
                ],
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
            'id_hukuman_disiplin_jenis' => 'Jenis',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'keterangan' => 'Keterangan',
            'id_instansi' => 'Perangkat Daerah',
            'status_hapus' => 'Status Hapus',
            'waktu_dihapus' => 'Waktu Dihapus',
            'namaHukumanDisiplinJenis' => 'Jenis'
        ];
    }

    /**
     * @inheritdoc
     * @return HukumanDisiplinQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new HukumanDisiplinQuery(get_called_class());
        $query->andWhere('status_hapus = 0');
        return $query;
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getHukumanDisiplinJenis()
    {
        return $this->hasOne(HukumanDisiplinJenis::class, ['id' => 'id_hukuman_disiplin_jenis']);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi']);
    }

    public function beforeSoftDelete()
    {
        $this->waktu_dihapus = date('Y-m-d H:i:s');
        return true;
    }

    public function getIsHukumanBerat()
    {
        return (int) $this->id_hukuman_disiplin_jenis === HukumanDisiplinJenis::BERAT;
    }

    public function getIsHukumanSedang()
    {
        return (int) $this->id_hukuman_disiplin_jenis === HukumanDisiplinJenis::SEDANG;
    }

    public function getIsHukumanRingan()
    {
        return (int) $this->id_hukuman_disiplin_jenis === HukumanDisiplinJenis::RINGAN;
    }

    public function getPotongan()
    {
        return @$this->hukumanDisiplinJenis->potongan;
    }

    public function getNamaHukumanDisiplinJenis()
    {
        return @$this->hukumanDisiplinJenis->nama;
    }

    public function getLinkViewIcon()
    {
        return Html::a('<i class="fa fa-eye"></i>',[
            '/absensi/hukuman-disiplin/view',
            'id'=>$this->id
        ]);
    }

    public function getLinkUpdateIcon()
    {
        if($this->getAccessUpdate() == false) {
            return '';
        }

        return Html::a('<i class="fa fa-pencil"></i>',[
            '/absensi/hukuman-disiplin/update',
            'id'=>$this->id
        ]);
    }

    public function getLinkDeleteIcon()
    {
        if($this->getAccessDelete() == false) {
            return '';
        }

        return Html::a('<i class="fa fa-trash"></i>',[
            '/absensi/hukuman-disiplin/delete',
            'id'=>$this->id
        ],[
            'data-method'=>'post',
            'data-confirm'=>'Yakin akan menghapus data?'
        ]);
    }

    public function setTanggalMulaiSelesai()
    {
        if($this->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::RINGAN) {
            if($this->bulan == null) {
                $this->addError('bulan','Bulan tidak boleh kosong');
                return false;
            }

            $datetime = \DateTime::createFromFormat('Y-n-d',$this->tahun.'-'.$this->bulan.'-01');
            $this->tanggal_mulai = $datetime->format('Y-m-01');
            $this->tanggal_selesai = $datetime->format('Y-m-t');
        }

        if($this->tanggal_selesai == null) {
            $this->tanggal_selesai = '9999-12-31';
        }

    }

    public function getBulanTahun()
    {
        return Helper::getBulanLengkap($this->bulan).' '.$this->tahun;
    }

    public static function accessCreate(array $params)
    {
        if(@$params['id_hukuman_disiplin_jenis']==HukumanDisiplinJenis::RINGAN
            AND Session::isAdminInstansi()
        ) {
            return true;
        }

        if(@$params['id_hukuman_disiplin_jenis']==HukumanDisiplinJenis::RINGAN
            AND Session::isInstansi()
        ) {
            return true;
        }

        if(Session::isAdmin()) {
            return true;
        }

        if (Session::isPemeriksaAbsensi() AND UserMenu::findStatusAktif(['path'=>'/absensi/hukuman-disiplin/index'])) {
            return true;
        }

        if (Session::isPemeriksaKinerja() AND UserMenu::findStatusAktif(['path'=>'/absensi/hukuman-disiplin/index'])) {
            return true;
        }

        return false;
    }

    public function getAccessUpdate()
    {
        if($this->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::RINGAN
            AND Session::isInstansi()
        ) {
            return true;
        }

        if($this->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::RINGAN
            AND Session::isAdminInstansi()
        ) {
            return true;
        }

        if(Session::isAdmin()) {
            return true;
        }

        return false;
    }

    public function getAccessDelete()
    {
        return $this->getAccessUpdate();
    }

    public function setIdInstansi()
    {
        if($this->pegawai === null) {
            return false;
        }

        $instansiPegawai = $this->pegawai->getInstansiPegawaiBerlaku($this->tanggal_mulai);

        $this->id_instansi = $instansiPegawai->id_instansi;

    }

    public function getPeriode()
    {
        if($this->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::RINGAN) {
            return $this->getBulanTahun();
        }

        $output = Helper::getTanggalSingkat($this->tanggal_mulai);
        $output .= ' s.d ';
        $output .= Helper::getTanggalSingkat($this->tanggal_selesai);
        return $output;
    }
}
