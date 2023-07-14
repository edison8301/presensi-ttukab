<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use app\models\Instansi;
use app\models\Pegawai;
use app\models\InstansiPegawai;
use app\models\SkpNilai;
use Yii;
use app\models\User;
use yii\helpers\Html;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "instansi_pegawai_skp".
 *
 * @property int $id
 * @property int $id_instansi_pegawai
 * @property string $tahun
 * @property int $urutan
 * @property string $nomor
 * @property int $status_hapus
 * @property string $waktu_hapus
 * @property int $id_user_hapus
 * @property \app\models\InstansiPegawai instansiPegawai
 * @property KegiatanTahunan[] manyKegiatanTahunan
 * @property \app\models\Pegawai pegawai
 * @method softDelete() dari behaviors -> softDeleteBehavior
 */
class InstansiPegawaiSkp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instansi_pegawai_skp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_instansi_pegawai', 'tahun'], 'required'],
            [['id_instansi_pegawai', 'urutan', 'status_hapus', 'id_user_hapus'], 'integer'],
            [['tahun', 'waktu_hapus'], 'safe'],
            [['nomor'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_instansi_pegawai' => 'Id Instansi Pegawai',
            'tahun' => 'Tahun',
            'urutan' => 'Urutan',
            'nomor' => 'Nomor',
            'status_hapus' => 'Status Hapus',
            'waktu_hapus' => 'Waktu Hapus',
            'id_user_hapus' => 'Id User Hapus',
        ];
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
                    'status_hapus' => true,
                    'waktu_hapus' => date('Y-m-d H:i:s'),
                    'id_user_hapus' => User::getIdUser()
                ],
            ],
        ];
    }

    public function afterSoftDelete()
    {
        foreach ($this->findAllKegiatanRhk() as $kegiatanRhk) {
            $kegiatanRhk->softDelete();
        }

        return true;
    }

    public static function find()
    {
        $query = parent::find();
        $query->andWhere(['instansi_pegawai_skp.status_hapus'=>0]);

        return $query;
    }

    public function getInstansiPegawai()
    {
        return $this->hasOne(InstansiPegawai::class,['id'=>'id_instansi_pegawai']);
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class,['id'=>'id_pegawai'])
            ->via('instansiPegawai');
    }

    public function getManyKegiatanTahunan()
    {
        return $this->hasMany(KegiatanTahunan::class,[
           'id_instansi_pegawai'=>'id_instansi_pegawai',
           'tahun'=>'tahun'
        ]);
    }

    public function getManyKegiatanTahunanUtama()
    {
        $query = $this->getManyKegiatanTahunan();
        $query->andWhere('id_induk IS NULL');

        return $query;
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class,['id'=>'id_instansi'])
            ->via('instansiPegawai');
    }

    public function setTanggalMulai()
    {
        if($this->tahun==null) {
            $this->tahun = User::getTahun();
        }

        $awalTahun = $this->tahun.'-01-01';

        if($this->instansiPegawai->tanggal_mulai < $awalTahun) {
            $this->tanggal_mulai = $awalTahun;
        }

        if($this->instansiPegawai->tanggal_mulai >= $awalTahun) {
            $this->tanggal_mulai = $this->instansiPegawai->tanggal_mulai;
        }
    }

    public function updateTanggalMulai()
    {
        $this->setTanggalMulai();
        $this->updateAttributes([
            'tanggal_mulai'=>$this->tanggal_mulai
        ]);
    }

    public function setTanggalSelesai()
    {
        if($this->tahun==null) {
            $this->tahun = User::getTahun();
        }

        $akhirTahun = $this->tahun.'-12-31';

        //Jika tanggal selesai diatas 31 Des, maka tanggal selesai = 31 Des
        if($this->instansiPegawai->tanggal_selesai > $akhirTahun OR
            $this->instansiPegawai->tanggal_selesai == null
        ) {
            $this->tanggal_selesai = $akhirTahun;
        }

        //Jika tanggal selesai dibawah 31 Des, maka tanggal selesai = sesuai tanggal selesai
        if($this->instansiPegawai->tanggal_selesai <= $akhirTahun AND
            $this->instansiPegawai->tanggal_selesai != null
        ) {
            $this->tanggal_selesai = $this->instansiPegawai->tanggal_selesai;
        }
    }

    public function updateTanggalSelesai()
    {
        $this->setTanggalSelesai();
        $this->updateAttributes([
           'tanggal_selesai'=>$this->tanggal_selesai
        ]);
    }

    public function countKegiatanTahunan()
    {
        return count($this->manyKegiatanTahunan);
    }

    public function countKegiatanTahunanUtama()
    {
        return count($this->manyKegiatanTahunanUtama);
    }


    public function countKegiatanTahunanV2($params=[])
    {
        $query = $this->getManyKegiatanTahunan();
        $query->andWhere('id_induk IS NULL');
        $query->andWhere(['id_kegiatan_tahunan_jenis' => @$params['id_kegiatan_tahunan_jenis']]);
        $query->andWhere(['id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']]);

        return $query->count();
    }

    public function findAllKegiatanTahunanUtama()
    {
        $query = $this->getManyKegiatanTahunan();
        $query->andWhere('id_induk IS NULL');
        return $query->all();
    }

    public function findAllKegiatanTahunanTambahan()
    {
        $query = $this->getManyKegiatanTahunan();
        $query->andWhere('id_induk is not null');
        return $query->all();
    }

    public function findAllKegiatanTahunan($params=[])
    {
        $query = $this->getManyKegiatanTahunan();
        $query->andWhere('id_induk IS NULL');
        $query->andFilterWhere(['id_kegiatan_tahunan_jenis' => @$params['id_kegiatan_tahunan_jenis']]);
        $query->andWhere(['id_kegiatan_tahunan_versi' => @$params['id_kegiatan_tahunan_versi']]);
        return $query->all();
    }

    public function accessView()
    {
        if(User::isAdmin()) {
            return true;
        }

        if(User::isPegawai() AND $this->pegawai->id == User::getIdPegawai())  {
            return true;
        }

        if(Session::isPemeriksaKinerja()) {
            return true;
        }

        return false;
    }

    public function accessUpdate()
    {
        if(User::isAdmin()) {
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

    public function setNomor()
    {
        if($this->urutan==null) {
            $this->updateUrutan();
        }
        $this->nomor = $this->urutan.'-'.$this->tahun;

        if($this->status_plt == 1) {
            $this->nomor = $this->urutan.'-'.$this->tahun.'-Plt';
        }
    }

    public static function getList($params=[])
    {
        $id_pegawai = @$params['id_pegawai'];

        if(User::isPegawai()) {
            $id_pegawai = User::getIdPegawai();
        }

        if ($id_pegawai == null) {
            return [];
        }

        $query = InstansiPegawaiSkp::find();

        $query->joinWith(['instansiPegawai']);

        $query->andWhere([
           'instansi_pegawai_skp.tahun'=>User::getTahun(),
        ]);

        if($id_pegawai!=null) {
            $query->andWhere([
                'instansi_pegawai.id_pegawai'=>$id_pegawai
            ]);
        }

        $list = [];
        foreach($query->all() as $data) {
            $list[$data->nomor] = $data->nomor;
        }

        return $list;
    }

    public static function getListNomor(array $params = [])
    {
        $id_pegawai = @$params['id_pegawai'];

        if (Session::isPegawai()) {
            $id_pegawai = Session::getIdPegawai();
        }

        $query = InstansiPegawaiSkp::find();
        $query->joinWith(['instansiPegawai']);
        $query->andWhere([
            'instansi_pegawai_skp.tahun' => User::getTahun(),
            'instansi_pegawai.id_pegawai' => $id_pegawai,
        ]);

        $list = [];

        foreach($query->all() as $data) {
            if($data->instansiPegawai!=null) {
                $list[$data->nomor] = $data->nomor.' : '.$data->instansiPegawai->getNamaJabatan().' - '.@$data->instansi->nama;
            } else {
                $list[$data->nomor] = $data->nomor.' - '.@$data->instansi->nama;
            }
        }

        return $list;
    }

    public static function getListId()
    {
        $query = InstansiPegawaiSkp::find();
        $query->joinWith(['instansiPegawai']);
        $query->andWhere([
            'instansi_pegawai_skp.tahun'=>User::getTahun(),
            'instansi_pegawai.id_pegawai'=>User::getIdPegawai()
        ]);

        $list = [];

        foreach($query->all() as $data) {
            if($data->instansiPegawai!=null) {
                $list[$data->id] = $data->nomor.' : '.$data->instansiPegawai->getNamaJabatan().' - '.@$data->instansi->nama;
            } else {
                $list[$data->id] = $data->nomor.' - '.@$data->instansi->nama;
            }
        }

        return $list;
    }

    public static function getListIdInstansiPegawai()
    {
        $query = InstansiPegawaiSkp::find();
        $query->joinWith(['instansiPegawai']);
        $query->andWhere([
            'instansi_pegawai_skp.tahun'=>User::getTahun(),
            'instansi_pegawai.id_pegawai'=>User::getIdPegawai()
        ]);

        $list = [];
        foreach($query->all() as $data) {
            $list[$data->id_instansi_pegawai] = $data->nomor.' : '.$data->instansiPegawai->nama_jabatan.' - '.@$data->instansi->nama;
        }

        return $list;
    }

    public static function getListDepdrop(array $params = [])
    {
        $tahun = @$params['tahun'];
        $id_pegawai = @$params['id_pegawai'];

        if ($tahun == null) {
            $tahun = Session::getTahun();
        }

        if (Session::isPegawai()) {
            $id_pegawai = Session::getIdPegawai();
        }

        $query = InstansiPegawaiSkp::find();
        $query->joinWith(['instansiPegawai']);
        $query->andWhere([
            'instansi_pegawai_skp.tahun' => $tahun,
            'instansi_pegawai.id_pegawai' => $id_pegawai,
        ]);

        $list = [];
        foreach ($query->all() as $data) {
            $name = $data->nomor.' : '.$data->instansiPegawai->nama_jabatan.' - '.@$data->instansi->nama;;
            $list[] = [
                'id' => $data->id,
                'name' => $name,
            ];
        }

        return $list;
    }

    public function accessRefresh()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;
    }

    public function getNamaJabatan()
    {
        if($this->instansiPegawai === null) {
            return '';
        }

        return @$this->instansiPegawai->getNamaJabatan();
    }

    public function getNamaInstansi()
    {
        return @$this->instansi->nama;
    }

    /**
     * @param array $params
     * @return array|\yii\db\ActiveRecord[]|KegiatanRhk[]
     */
    public function findAllKegiatanRhk(array $params = [])
    {
        $query = KegiatanRhk::find();
        $query->andWhere([
            'id_instansi_pegawai_skp' => $this->id,
        ]);

        if (@$params['id_kegiatan_rhk_jenis'] !== null) {
            $query->andWhere(['id_kegiatan_rhk_jenis' => @$params['id_kegiatan_rhk_jenis']]);
        }

        if (@$params['id_induk_is_null'] == true) {
            $query->andWhere('id_induk is null');
        }

        return $query->all();
    }

    public function canUpdate()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai()) {

            if ($this->instansiPegawai->id_pegawai == Session::getIdPegawai()) {
                return true;
            }

            if (in_array(@$this->instansiPegawai->jabatan->id_induk, User::getIdJabatanBerlaku())) {
                return true;
            }
        }

        return false;
    }

    public function canViewV3()
    {
        if ($this->canUpdate()) {
            return true;
        }

        if (Session::isPemeriksaKinerja()) {
            return true;
        }

        return false;
    }

    public function canCreateKegiatanRhk()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai()
            AND @$this->instansiPegawai->id_pegawai == Session::getIdPegawai()
        ) {
            return true;
        }

        return false;
    }

    public function isJpt()
    {
        if (@$this->instansiPegawai == null) {
            return false;
        }

        return $this->instansiPegawai->isJpt();
    }

    public function canCreateSkpLampiran()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai()
            AND $this->instansiPegawai->id_pegawai == Session::getIdPegawai()
        ) {
            return true;
        }

        return false;
    }

    public function getLinkCreateSkpLampiran(array $params = [])
    {
        if ($this->canCreateSkpLampiran() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-plus"></i>', [
            '/kinerja/skp-lampiran/create',
            'id_skp' => $this->id,
            'id_skp_lampiran_jenis' => @$params['id_skp_lampiran_jenis'],
        ], [
            'style' => 'color: #3c8dbc',
        ]);
    }

    /**
     * @param array $params
     * @return SkpNilai
     */
    public function getOneOrCreateSkpNilai(array $params): SkpNilai
    {
        $query = SkpNilai::find();
        $query->andWhere([
            'id_instansi_pegawai_skp' => $this->id,
            'id_skp_periode' => $params['id_skp_periode'],
            'periode' => $params['periode']
        ]);

        $skpNilai = $query->one();

        if($skpNilai === null) {
            $skpNilai = new SkpNilai();
            $skpNilai->id_instansi_pegawai_skp = $this->id;
            $skpNilai->id_skp_periode = $params['id_skp_periode'];
            $skpNilai->periode = $params['periode'];

            $skpNilai->save();
        }

        return $skpNilai;

    }
}
