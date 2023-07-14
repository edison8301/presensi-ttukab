<?php

namespace app\models;

use app\components\TunjanganBulan;
use app\modules\absensi\models\PegawaiShiftKerja;
use DateTime;
use Yii;
use Zend\Validator\Date;
use app\components\Helper;
use app\modules\kinerja\models\InstansiPegawaiFungsi;
use app\modules\kinerja\models\InstansiPegawaiSasaran;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\InstansiPegawaiTugas;
use app\modules\kinerja\models\KegiatanTahunan;
use app\modules\kinerja\models\KegiatanTahunanQuery;
use app\modules\tukin\models\Pegawai;
use yii2mod\query\ArrayQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "instansi_pegawai".
 *
 * @property int $id
 * @property string $tahun
 * @property int $id_instansi
 * @property int $id_pegawai
 * @property int $id_atasan
 * @property int $id_jabatan
 * @property int $nama_jabatan
 * @property string $tanggal_berlaku
 * @property string $tanggal_selesai
 * @property int $status_hapus
 * @property string $waktu_dihapus
 * @property int $id_golongan [int(11)]
 * @property int $id_eselon [int(11)]
 * @property int $status_plt
 * @property Instansi $instansi
 * @property Pegawai $pegawai
 * @property Jabatan $jabatan
 * @property Eselon $eselon
 * @property KegiatanTahunan[] $manyKegiatanTahunan
 * @property KegiatanTahunan[] $manyKegiatanTahunanInduk
 * @property KegiatanTahunan[] $manyKegiatanTahunanIndukSetuju
 * @property Golongan $golongan
 * @property Pegawai $atasan
 * @property int $lama [int(11)]
 * @property string tanggal_mulai
 * @property Jabatan $jabatanAtasan
 * @property InstansiPegawaiTugas[] $manyInstansiPegawaiTugas
 * @property InstansiPegawaiFungsi[] $manyInstansiPegawaiFungsi
 * @property InstansiPegawaiSasaran[] $manyInstansiPegawaiSasaran
 * @property mixed|string|null tanggal_mulai_text
 * @method softDelete() dari behaviors -> softDeleteBehavior
 * @property string $waktu_refresh
 */
class InstansiPegawai extends ActiveRecord
{
    public $tahun;
    public $bulan;

    public $lorem = 222;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instansi_pegawai';
    }

    /**
     * @inheritdoc
     */
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
    public function rules()
    {
        return [
            [['tahun', 'tanggal_berlaku', 'waktu_dihapus','lorem'], 'safe'],
            [['id_instansi', 'id_pegawai', 'tanggal_berlaku'], 'required'],
            [['id_instansi', 'id_pegawai', 'id_jabatan','status_plt'], 'integer'],
            ['status_hapus', 'boolean'],
            ['nama_jabatan', 'string', 'max' => 255],
            ['status_hapus', 'default', 'value' => 0],
            [['tanggal_mulai','tanggal_selesai'],'safe'],
            [['nama_jabatan_atasan', 'nama_instansi', 'status_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun' => 'Tahun',
            'id_instansi' => 'Perangkat Daerah',
            'id_pegawai' => 'Pegawai',
            'id_golongan' => 'Golongan',
            'id_eselon' => 'Eselon',
            'id_jabatan' => 'Jabatan',
            'nama_jabatan' => 'Nama Jabatan',
            'tanggal_berlaku' => 'Tanggal Berlaku',
            'id_atasan' => 'Atasan',
            'status_hapus' => 'Status Hapus',
            'waktu_dihapus' => 'Waktu Dihapus',
        ];
    }

    /**
     * @inheritdoc
     * @return InstansiPegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new InstansiPegawaiQuery(get_called_class());
        $query->aktif();
        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyKegiatanTahunan()
    {
        return $this->hasMany(KegiatanTahunan::class, ['id_instansi_pegawai' => 'id']);
    }

    /**
     * @return KegiatanTahunanQuery
     */
    public function getManyKegiatanTahunanInduk()
    {
        return $this->getManyKegiatanTahunan()
            ->induk();
    }

    public function getManyKegiatanTahunanIndukSetuju()
    {
        return $this->getManyKegiatanTahunanInduk()
            ->setuju()
            ->aktif();
    }

    public function beforeSoftDelete()
    {
        $this->waktu_dihapus = date('Y-m-d H:i:s');
        return true;
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai'])
            ->inverseOf('allInstansiPegawai');
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getOneInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getJabatan()
    {
        return $this->hasOne(Jabatan::class, ['id' => 'id_jabatan']);
    }

    public function getJabatanInduk()
    {
        return $this->hasOne(Jabatan::class, ['id' => 'id_induk'])
            ->via('jabatan');
    }

    public function getJabatanAtasan()
    {
        return $this->getJabatanInduk();
    }

    public function getAtasan()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai'])
            ->via('instansiPegawaiAtasan');
    }

    public function getInstansiPegawaiAtasan()
    {
        return $this->hasOne(InstansiPegawai::class, ['id_jabatan' => 'id'])
            ->via('jabatanAtasan')
            ->orderBy(['instansi_pegawai.tanggal_selesai' => SORT_DESC]);
    }

    public function getManyInstansiPegawaiBawahan()
    {
        return $this->hasMany(InstansiPegawai::class, ['id_jabatan' => 'id'])
            ->via('manyJabatanBawahan')
            ->filterByBulanTahun();
    }

    public function getManyJabatanBawahan()
    {
        return $this->getManyJabatanSub();
    }

    public function getManyJabatanSub()
    {
        return $this->hasMany(Jabatan::class, ['id_induk' => 'id_jabatan']);
    }

    public function getGolongan()
    {
        return $this->hasOne(Golongan::class, ['id' => 'id_golongan']);
    }

    public function getEselon()
    {
        return $this->hasOne(Eselon::class, ['id' => 'id_eselon']);
    }

    public function getManyPegawaiTundaBayar()
    {
        return $this->hasMany(PegawaiTundaBayar::class, ['id_pegawai' => 'id_pegawai']);
    }

    public function beforeSave($insert)
    {
        $date = new DateTime($this->tanggal_berlaku);
        $this->tahun = $date->format('Y');
        $this->setInformasiText(); // untuk kebutuhan API yang ditarik dari simpeg
        return parent::beforeSave($insert);
    }

    public static function getListPegawai()
    {
        return ArrayHelper::map(static::find()->with(['pegawai', 'instansi'])->aktif()->all(), 'id', function($element) {
            return $element->pegawai->getNamaNip() . " - " . $element->instansi->getSingkatan();
        });
    }

    public static function getListInstansi($id_pegawai, $map = false)
    {
        $query = static::find()
            ->aktif()
            ->andWhere(['id_pegawai' => $id_pegawai])
            ->with('instansi')
            ->orderBy(['id' => SORT_DESC]);
        if ($map === true) {
            return ArrayHelper::map($query->all(), 'id', function ($model) {
                return $model->nama_jabatan . ' - ' . @$model->instansi->nama;
            });
        }
        $list = [];
        foreach ($query->all() as $model) {
            $list[] = ['id' => $model->id, 'name' => $model->nama_jabatan . ' - ' .@$model->instansi->nama];
        }
        return $list;
    }

    public function setTanggalMulai($tanggal_mulai=null)
    {
        if($tanggal_mulai!=null) {
            $this->tanggal_mulai = $tanggal_mulai;
        }

        if($this->tanggal_mulai==null) {
            $date = DateTime::createFromFormat('Y-m-d',$this->tanggal_berlaku);
            if($date->format('j')<10) {
                $this->tanggal_mulai = $date->format('Y-m-01');
            } else {
                $tanggal = $date->format('Y-m-01');
                $date = DateTime::createFromFormat('Y-m-d',$tanggal);
                $date->modify('+1 month');
                $this->tanggal_mulai = $date->format('Y-m-01');
            }
        }
    }

    public function updateTanggalMulai($tanggal_mulai=null)
    {
        $this->setTanggalMulai($tanggal_mulai);
        $this->updateAttributes([
            'tanggal_mulai'=>$this->tanggal_mulai
        ]);
    }

    public function setTanggalSelesai($tanggal_selesai=null)
    {
        if($this->tanggal_mulai==null) {
            $this->updateTanggalMulai();
        }

        if($tanggal_selesai!=null) {
            $this->tanggal_selesai = $tanggal_selesai;
        }

        if($this->tanggal_selesai==null) {

            // Query untuk cari data instansi_pegawai yang lebih baru
            $query = InstansiPegawai::find();
            $query->andWhere([
                'id_pegawai'=>$this->id_pegawai,
                'status_plt'=>0
            ]);
            $query->andWhere('tanggal_mulai > :tanggal_mulai',[
                ':tanggal_mulai' => $this->tanggal_mulai
            ]);
            $query->orderBy(['tanggal_berlaku'=>SORT_ASC]);

            $model = $query->one();

            // Jika ada data instansi_pegawai
            if($model!==null) {
                $tanggal = DateTime::createFromFormat('Y-m-d',$model->tanggal_mulai);
                $tanggal->modify('-1 day');
                $this->tanggal_selesai = $tanggal->format('Y-m-d');
            }

            // Tidak ada data instansi pegawai yang lebih besar lagi
            if($model===null) {
                $this->tanggal_selesai = '9999-12-31';
            }
        }

    }

    public function updateTanggalSelesai($tanggal_selesai=null)
    {
        $this->setTanggalSelesai($tanggal_selesai);
        $this->updateAttributes([
            'tanggal_selesai'=>$this->tanggal_selesai
        ]);
    }

    /**
     * @return InstansiPegawai
     */
    public function findMundur()
    {
        $query = InstansiPegawai::find();
        $query->andWhere(['id_pegawai'=>$this->id_pegawai]);
        $query->andWhere('tanggal_berlaku < :tanggal_berlaku',[
            ':tanggal_berlaku' => $this->tanggal_berlaku
        ]);
        $query->orderBy(['tanggal_berlaku'=>SORT_DESC]);

        return $query->one();
    }

    public function updateMundurTanggalSelesai()
    {
        if($this->status_plt == 1) {
            return true;
        }

        $model = $this->findMundur();

        if($model!==null) {
            $tanggal = DateTime::createFromFormat('Y-m-d',$this->tanggal_mulai);
            $tanggal->modify('-1 day');
            $tanggal_selesai = $tanggal->format('Y-m-d');
            $model->updateTanggalSelesai($tanggal_selesai);
        }

    }

    /**
     * @param null $tahun
     */
    public function findOrCreateInstansiPegawaiSkp($params = [])
    {
        $tahun = User::getTahun();
        if(@$params['tahun'] == null) {
            $tahun = @$params['tahun'];
        }

        $status_plt = 0;
        if(@$params['status_plt'] == 1) {
            $status_plt = 1;
        }

        $query = InstansiPegawaiSkp::find();
        $query->andWhere([
            'instansi_pegawai_skp.id_instansi_pegawai' => $this->id,
            'instansi_pegawai_skp.tahun' => $tahun,
        ]);

        $model = $query->one();

        if($model===null) {
            $model = new InstansiPegawaiSkp();
            $model->id_instansi_pegawai = $this->id;
            $model->tahun = $tahun;
            $model->status_plt = $status_plt;
            $model->setTanggalMulai();
            $model->setTanggalSelesai();

            if($model->save()==false) {
                print_r($model->getErrors());
                die();
            }

            $model->pegawai->refreshInstansiPegawaiSkp(['tahun'=>$tahun]);
        }

        return $model;
    }

    public function getNamaJabatan($html = true)
    {
        $plt = '';

        if($this->status_plt == true) {
            $plt .= ' (Plt)';
        }
        if($this->id_jabatan != null) {
            /*
            if ($this->tanggal_mulai >= '2023-01-01') {
                return @$this->jabatan->nama_2023.$plt;
            }

            if ($this->tanggal_mulai >= '2022-01-01') {
                return @$this->jabatan->nama_2022.$plt;
            }

            if ($this->tanggal_mulai < '2022-01-01') {
                return @$this->jabatan->nama_2021.$plt;
            }
            */

            return @$this->jabatan->nama.$plt;
        }

        $output = $this->nama_jabatan.$plt.' ';
        if ($html) {
            $output .= '<i class="fa fa-warning" data-toggle="tooltip" title="Nama Jabatan Belum Ditautkan dengan Referensi Jabatan"></i>';
        }
        return $output;
    }

    public function getNamaJabatanAtasan()
    {
        $jabatan = $this->jabatanAtasan;

        if ($jabatan != null) {

            /*
            if ($this->tanggal_mulai >= '2023-01-01') {
                return $jabatan->nama_2023;
            }

            if ($this->tanggal_mulai >= '2022-01-01') {
                return $jabatan->nama_2022;
            }

            if ($this->tanggal_mulai < '2022-01-01') {
                return $jabatan->nama_2021;
            }
            */

            return $jabatan->nama;
        }

        return null;
    }

    public function getNamaPegawai()
    {
        $plt = null;
        if($this->status_plt == true) {
            $plt .= '<b> (Plt)</b> ';
        }
        return $this->pegawai->nama . $plt.' - ' . $this->pegawai->nip;
    }

    public function getJenisJabatan()
    {
        return $this->jabatan
            ? $this->jabatan->getJenisJabatan()
            : null;
    }

    /**
     * @param array $params terdiri dari tahun* dan urutan*
     */
    public function updateInstansiPegawaiSkp($params=[])
    {
        $urutan = @$params['urutan'];
        $tahun = @$params['tahun'];

        $model = $this->findOrCreateInstansiPegawaiSkp($params);
        $model->setTanggalMulai();
        $model->setTanggalSelesai();
        $model->urutan = $urutan;
        $model->setNomor();
        $model->save(false);
    }

    public function getNamaNipPegawai()
    {
        return @$this->pegawai->nama.' ('.@$this->pegawai->nip.')';
    }

    public function getNamaInstansi()
    {
        return @$this->instansi->nama;
    }

    public static function accessIndex()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        return false;
    }

    public static function accessCreate()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        return false;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        return false;
    }

    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;

    }


    public function accessView()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isVerifikator()) {
            return true;
        }

        if(User::isMapping()) {
            return true;
        }

        return false;


    }

    public function getLinkIconView()
    {
        if($this->accessView()==false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',[
                '/instansi-pegawai/view',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'View'
            ]).' ';
    }

    public function getLinkIconUpdate()
    {
        if($this->accessUpdate()==false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-pencil"></i>',[
                '/instansi-pegawai/update',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Ubah'
            ]).' ';
    }

    public function getLinkIconDelete()
    {
        if($this->accessDelete()==false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-trash"></i>',[
                '/instansi-pegawai/delete',
                'id'=>$this->id
            ],[
                'data-method'=>'post',
                'data-confirm'=>'Yakin akan menghapus data?',
                'data-toggle'=>'tooltip',
                'title'=>'Hapus'
            ]).' ';
    }

    public function setInformasiText()
    {
        $this->nama_instansi = @$this->instansi->nama;
        $this->tanggal_mulai_text = Helper::getTanggal($this->tanggal_mulai);
        $this->tanggal_selesai_text = Helper::getTanggal($this->tanggal_selesai);

        if ($this->tanggal_selesai == '9999-12-31') {
            $this->tanggal_selesai_text = 'Saat Ini';
        }
        return true;
    }

    public function getManyInstansiPegawaiTugas()
    {
        return $this->hasMany(InstansiPegawaiTugas::class, ['id_instansi_pegawai' => 'id'])->orderBy(['urutan' => SORT_ASC]);
    }

    public function getManyInstansiPegawaiFungsi()
    {
        return $this->hasMany(InstansiPegawaiFungsi::class, ['id_instansi_pegawai' => 'id'])->orderBy(['urutan' => SORT_ASC]);
    }

    public function getManyInstansiPegawaiSasaran()
    {
        return $this->hasMany(InstansiPegawaiSasaran::class, ['id_instansi_pegawai' => 'id'])->orderBy(['urutan' => SORT_ASC]);
    }

    public function isMengisiIki()
    {
        return $this->manyInstansiPegawaiSasaran !== []
            && $this->manyInstansiPegawaiFungsi !== []
            && $this->manyInstansiPegawaiSasaran !== [];
    }

    public function getQuery($params=[])
    {

    }

    public function getTextStatusPegawai()
    {
        $sekarang = date('Y-m-d');

        if ($this->tanggal_selesai >= $sekarang) {
            return 'Aktif';
        } else {
            return 'Pensiun';
        }
    }

    public static function query($params=[])
    {
        $query = InstansiPegawai::find();

        $query->andFilterWhere([
            'id_jabatan' => @$params['id_jabatan'],
            'id_instansi' => @$params['id_instansi']
        ]);

        if(@$params['tahun'] == null) {
            @$params['tahun'] = date('Y');
        }

        if(@$params['bulan'] != null) {
            $query->andWhere('tanggal_mulai <= :tanggal AND tanggal_selesai >= :tanggal',[
                ':tanggal' => $params['tahun'].'-'.$params['bulan'].'-15'
            ]);
        }

        return $query;
    }

    public function getNamaAtasan()
    {
        $id_jabatan_induk = $this->jabatan->id_induk;
        $query = InstansiPegawai::find();
        $query->andWhere([
            'id_jabatan'=>$id_jabatan_induk
        ]);
        $query->berlaku(date('Y-m-d'));

        $instansiPegawaiInduk = $query->one();
        return $instansiPegawaiInduk->pegawai->nama;
    }

    public static function getListDataVerifikasiTandatangan()
    {
        // BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA DAERAH
        $query = InstansiPegawai::query();
        $query->andWhere(['id_instansi' => 1]);
        $query->berlaku();

        $arrayId = [];

        // Sekretaris Badan Kepegawaian Dan Pengembangan Sumber Daya Manusia Daerah
        $sekretaris = new ArrayQuery();
        $sekretaris->from($query->all());
        $sekretaris->andWhere(['id_jabatan' => 687]);

        if($sekretaris->one() != null) {
            $arrayId[] = $sekretaris->one()->id;
        }

        // Kepala Sub Bagian Umum
        $kasubagUmum = new ArrayQuery();
        $kasubagUmum->from($query->all());
        $kasubagUmum->andWhere(['id_jabatan' => 688]);

        if($kasubagUmum->one() != null) {
            $arrayId[] = $kasubagUmum->one()->id;
        }

        $list = [];

        $dataVerifikasi = InstansiPegawai::query();
        $dataVerifikasi->andWhere(['id' => $arrayId]);

        foreach($dataVerifikasi->all() as $instansiPegawai) {
            $list[] = [
                'nama' => @$instansiPegawai->pegawai->nama,
                'nip' => @$instansiPegawai->pegawai->nip,
                'jabatan' => @$instansiPegawai->jabatan->nama,
            ];
        }

        return $list;
    }

    public function getEselonJabatan()
    {
        $jabatan = @$this->jabatan;

        if ($jabatan == null) {
            return null;
        }

        return @$jabatan->eselon->nama;
    }

    public function isJpt()
    {
        $eselon = $this->jabatan->eselon;

        if ($eselon == null) {
            return false;
        }

        if (in_array(@$this->jabatan->id_eselon, [1,2,3,4]) == false) {
            return false;
        }

        return true;
    }


    public function getPegawaiGuruKepsek()
    {
        $listJabatan = Jabatan::find()
        ->andWhere([
            'OR',
            // ['LIKE', 'nama', '%kepala sekolah%', false],
            // ['LIKE', 'nama', 'Guru%', false],
            // ['LIKE', 'nama', 'Calon Guru%', false],
            ['LIKE', 'nama', 'Guru Ahli%', false],
        ])
        ->select('id')->column();

        $query= InstansiPegawai::find()->andWhere([
            'id_jabatan' => $listJabatan,
        ]);

        $query->berlaku('2023-06-15');

        return $query->all();

    }

    public function createShiftKerjaPegawai(){

        foreach ($this->getPegawaiGuruKepsek() as $pegawai ){

            if ($pegawai === null) {
                continue;
            }

                $shiftKerja = new PegawaiShiftKerja();
                
                $shiftKerja->id_pegawai = $pegawai->id_pegawai;
                $shiftKerja->id_shift_kerja = 55;
                $shiftKerja->tanggal_berlaku = date('Y-m-d', strtotime('2023-06-01'));
                
                $shiftKerja->save();
        }
    }

}
