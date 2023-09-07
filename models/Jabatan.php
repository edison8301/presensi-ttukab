<?php

namespace app\models;

use app\components\Session;
use Yii;
use app\modules\tunjangan\models\TingkatanFungsional;
use kartik\editable\Editable;
use kartik\popover\PopoverX;
use yii2mod\query\ArrayQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "jabatan".
 *
 * @property integer $id
 * @property string $kode
 * @property string $kode_induk
 * @property string $nama
 * @property integer $nilai_jabatan
 * @property integer $kelas_jabatan
 * @property bool $status_impor
 * @property bool $status_input_langsung
 * @property bool $status_kepala
 * @property int $id_jabatan_evjab [int(11)]
 * @property int $id_jenis_jabatan [int(11)]
 * @property int $id_instansi [int(11)]
 * @property int $id_instansi_bidang [int(11)]
 * @property int $id_jabatan_eselon [int(11)]
 * @property int $id_eselon
 * @property int $id_tingkatan_fungsional
 * @property string $bidang [varchar(255)]
 * @property string $subbidang [varchar(255)]
 * @property int $persediaan_pegawai [int(11)]
 * @property float $penyeimbang [double(5,2)]
 * @property int $id_parent [int(11)]
 * @property bool $status_jumlah_tetap [tinyint(1)]
 * @property float $jumlah_tetap [double(20,2)]
 * @property int $status_verifikasi [int(11)]
 * @property string $waktu_verifikasi [datetime]
 * @property int $id_user_verifikasi [int(11)]
 * @property int $status_hapus [int(11)]
 * @property string $waktu_hapus [datetime]
 * @property int $id_user_hapus [int(11)]
 * @property null|string $linkIconView
 * @property Instansi $instansi
 * @property Pegawai[] $manyPegawai
 * @property string $editableStatusVerifikasi
 * @property mixed $instansiBidang
 * @property null $tooltipInstansiBidang
 * @property mixed $jabatanInduk
 * @property string $jenisJabatan
 * @property mixed $subJabatan
 * @property mixed $manySub
 * @property mixed $namaInstansi
 * @property mixed $jabatanEvjab
 * @property mixed $jabatanEselon
 * @property null|string $linkIconUpdate
 * @property mixed $listNamaPegawai
 * @property string $textStatusVerifikasi
 * @property string $iconIdJabatanEvjab
 * @see Jabatan::getManyInstansiPegawai()
 * @property InstansiPegawai[] $manyInstansiPegawai
 * @property null|string $linkIconDelete
 * @property InstansiPegawai[] $manyInstansiPegawaiByBulanTahun
 * @method softDelete() dari SoftDeleteBehavior
 * @see Jabatan::getEselon()
 * @property Eselon $eselon
 * @see Jabatan::getTingkatanFungsional()
 * @property TingkatanFungsional $tingkatanFungsional
 * @property int $hasil_abk
 * @property int $status_tampil
 * @property string $nama_2021
 * @property string $nama_2022
 * @property string $nama_2023
 * @see Jabatan::getNamaJabatan()
 * @property string $namaJabatan
 */
class Jabatan extends \yii\db\ActiveRecord
{
    public $tahun;
    public $bulan;

    const STRUKTURAL = 1;
    const NON_STRUKTURAL_JFT = 2;
    const NON_STRUKTURAL_JFU = 3;

    const FUNGSIONAL = 2;
    const PELAKSANA = 3;

    const TAMPIL = 1;
    const TIDAK_TAMPIL = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'anjab_jabatan';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_anjab');
    }

    public static function getListStatusKepala()
    {
        return [
            1=>'Ya',
            0=>'Bukan'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
            [['id_instansi', 'status_kepala', 'id_parent', 'id_jenis_jabatan',
                'kelas_jabatan','nilai_jabatan'
            ],'integer'],
            [['bulan','tahun', 'nilai_jabatan'],'integer'],
            [['status_impor'], 'boolean'],
            [['status_verifikasi','id_user_verifikasi'],'integer'],
            [['waktu_verifikasi','id_tingkatan_fungsional','id_eselon'],'safe'],
            [['id_instansi_bidang','id_jabatan_evjab','id_jabatan_eselon'],'integer'],
            [['status_input_langsung'], 'boolean'],
            [['nama_2022', 'nama_2021', 'nama_2023'], 'safe'],
            [['hasil_abk', 'status_tampil'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama Jabatan',
            'nama_2021' => 'Nama Jabatan (2021)',
            'nama_2022' => 'Nama Jabatan (2022)',
            'nama_2023' => 'Nama Jabatan (2023)',
            'id_instansi' => 'Perangkat Daerah',
            'id_parent' => 'Atasan Jabatan',
            'id_jabatan_jenis' => 'Jenis Jabatan',
            'id_jenis_jabatan' => 'Jenis Jabatan',
            'id_instansi_bidang' => 'Bidang',
            'id_jabatan_evjab' => 'Jabatan Evjab'
        ];
    }

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

    /**
     * @inheritdoc
     * @return JabatanQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new JabatanQuery(get_called_class());
        //$query->andWhere(['jabatan.status_hapus' => 0]);
        return $query;
    }


    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getInstansiBidang()
    {
        return $this->hasOne(InstansiBidang::class, ['id' => 'id_instansi_bidang']);
    }

    public function getJabatanInduk()
    {
        return $this->hasOne(self::class, ['id' => 'id_parent']);
    }

    public function getJabatanEvjab()
    {
        return $this->hasOne(JabatanEvjab::class, ['id' => 'id_jabatan_evjab']);
    }

    public function getJabatanEselon()
    {
        return $this->hasOne(JabatanEselon::class, ['id' => 'id_jabatan_eselon']);
    }

    public function getEselon()
    {
        return $this->hasOne(Eselon::class, ['id' => 'id_eselon']);
    }

    public function getTingkatanFungsional()
    {
        return $this->hasOne(TingkatanFungsional::class, ['id' => 'id_tingkatan_fungsional']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManyInstansiPegawai()
    {
        return $this->hasMany(InstansiPegawai::class, ['id_jabatan' => 'id']);
    }

    public function getManyInstansiPegawaiByBulanTahun()
    {
        if($this->bulan==null) {
            $this->bulan = date('n');
        }

        if($this->tahun==null) {
            $this->tahun = date('Y');
        }

        return $this->getManyInstansiPegawai()->filterByBulanTahun($this->bulan,$this->tahun);
    }

    public function getManyPegawai()
    {
        $query = $this->hasMany(Pegawai::class, ['id_jabatan' => 'id']);
        /*
        if ($this->instansi !== null) {
            $instansi = $this->instansi;
            $ids = array_keys($instansi->getManySubInstansi()->select('id')->indexBy('id')->asArray()->all());
            $ids[] = $instansi->id;
            $query->andWhere(['in', 'id_instansi', $ids]);
        }
        */
        return $query;
    }

    public function getSubJabatan()
    {
        return $this->hasMany(self::class, ['id_parent' => 'id']);
    }

    public static function findByKode($kode)
    {
        $model = self::findOne(['kode'=>$kode]);
        if ($model === null) {
            $model = new Jabatan;
            $model->kode = $kode;
        }
        return $model;
    }

    public function getNamaStatusKepala()
    {
        if($this->status_kepala==1) {
            return "Ya";
        }

        return "Bukan";
    }

    public function countPegawai()
    {
        $query = Pegawai::find();
        $query->andWhere(['kode_jabatan'=>$this->kode]);
        return $query->count();
    }

    public static function getList($params=[])
    {
        return ArrayHelper::map(self::find()->all(),'id','namaJabatan');
    }

    public static function getListKepala()
    {
        $query = Jabatan::find();
        $query->with('instansi');
        $query->andWhere([
            'status_kepala'=>1
        ]);

        return ArrayHelper::map($query->all(),'id','namaJabatan');
    }

    public static function getListStruktur($id_instansi, $status_kepala = false)
    {
        $instansi = Instansi::findOne($id_instansi);

        $query = Jabatan::find();

        if ($status_kepala == false) {
            $query->andWhere(['id_instansi'=>$id_instansi]);
        }

        if ($status_kepala == true) {
            $query->andWhere('status_kepala = 1');
            $list = [];
            foreach($query->all() as $data) {
                $list[$data->id] = $data->getNamaJabatan();
            }

            return $list;

        }

        $arrayJabatan = $query->all();

        $query->andWhere(['status_kepala'=>1]);
        $kepala = $query->one();

        $list = [];
        if($kepala!==null) {
            $list[$kepala->id] = $kepala->getNamaJabatan();
            $level = 1;
            $list = $list + Jabatan::getListSub($kepala->id,$level,$arrayJabatan);
        }

        return $list;
    }

    public static function getListSub($id_jabatan,$level,$arrayJabatan=[])
    {
        $arrayQuery = new ArrayQuery();
        $arrayQuery->from($arrayJabatan);
        $arrayQuery->andWhere(['id_parent'=>$id_jabatan]);

        $list = [];

        $prepend = '';
        for($i=1;$i<=$level;$i++) {
            $prepend .= '- - ';
        }
        $level++;
        foreach($arrayQuery->all() as $jabatan) {
            $list[$jabatan->id] = $prepend.' '.$jabatan->getNamaJabatan();
            $list = $list + Jabatan::getListSub($jabatan->id,$level,$arrayJabatan);
        }

        return $list;
    }

    public function getNamaJabatan(array $params = [])
    {
        $plt = '';

        if(@$params['status_plt'] === true) {
            $plt = ' (Plt)';
        }

        $nama_jabatan = $this->nama_jabatan;

        return $nama_jabatan . $plt;
    }

    public function getListNamaPegawai()
    {
        $list = [];
        foreach($this->getManyPegawai()->all() as $data) {
            $list[] = $data->nama;
        }

        return implode(", ",$list);
    }

    public function getNamaInstansi()
    {
        return @$this->instansi->nama;
    }

    public function getJenisJabatan()
    {
        if ((int) $this->id_jenis_jabatan === 1) {
            return "Struktural";
        }

        if ((int) $this->id_jenis_jabatan === 2) {
            return "Fungsional";
        }

        if ((int) $this->id_jenis_jabatan === 3) {
            return "Pelaksana";
        }
    }

    public function isStruktural()
    {
        if ((int) $this->id_jenis_jabatan === 1) {
            return true;
        }
        return false;
    }

    public function setIdEselon()
    {
        if($this->id_jenis_jabatan == 2) {
            $this->id_eselon = 10;
        }

        if($this->id_jenis_jabatan == 3) {
            $this->id_eselon = 10;
        }
    }


    public function getManySub()
    {
        return $this->hasMany(static::class, ['id_parent' => 'id']);
    }

    /**
     * @param array $params
     * @return Jabatan[]
     */
    public function findAllSub($params=[])
    {
        $query = $this->getManySub();

        if(@$params['arrayJabatan']!=null) {
            $query = new ArrayQuery();
            $query->from($params['arrayJabatan']);
            $query->andFilterWhere(['id_parent'=>$this->id]);
        }

        $query->andFilterWhere([
            'anjab_jabatan.status_tampil' => @$params['status_tampil'],
            'anjab_jabatan.id_instansi' => @$params['id_instansi'],
        ]);

        return $query->all();
    }

    public function countSub()
    {
        return count($this->manySub);
    }

    /**
     * @return int
     */
    public function countInstansiPegawai()
    {
        return count($this->manyInstansiPegawai);
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
                '/jabatan/view',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'View'
            ]).' ';
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

    public function getLinkIconUpdate()
    {
        if($this->accessUpdate()==false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-pencil"></i>',[
                '/jabatan/update',
                'id'=>$this->id
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Ubah'
            ]).' ';
    }


    public function accessDelete()
    {
        if(User::isAdmin()) {
            return true;
        }

        return false;


    }

    public function getLinkIconDelete()
    {
        if($this->accessDelete()==false) {
            return null;
        }

        return Html::a('<i class="glyphicon glyphicon-trash"></i>',[
                '/jabatan/delete',
                'id'=>$this->id
            ],[
                'data-method'=>'post',
                'data-confirm'=>'Yakin akan menghapus data?',
                'data-toggle'=>'tooltip',
                'title'=>'Hapus'
            ]).' ';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getEditableStatusVerifikasi()
    {
        return Editable::widget([
            'id'=>'Jabatan_status_verifikasi-'.$this->id,
            'model'=>$this,
            'name'=>'status_verifikasi',
            'asPopover' => true,
            'value' => $this->getTextStatusVerifikasi(),
            'header' => 'Status Verifikasi',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'placement' => 'top',
            'beforeInput' => Html::hiddenInput('editableKey',$this->id),
            'formOptions' => [
                'action'=>['/jabatan/update-editable-status-verifikasi']
            ],
            'data' => ['0'=>'Belum Sesuai','1'=>'Sudah Sesuai'],
            'displayValueConfig' => ['0'=>'Belum','1'=>'Sesuai'],
            'options' => ['class'=>'form-control', 'placeholder'=>'Enter person name...']
        ]);
    }

    public static function getJenisJabatanList()
    {
        return [
            1 => "Struktural",
            2 => "Non Struktural (JFT)",
            3 => "Non Struktural (JFU)"
        ];
    }

    public function getTextStatusVerifikasi()
    {
        if($this->status_verifikasi==0) {
            return "Belum";
        }

        if($this->status_verifikasi==1) {
            return "Sesuai";
        }
    }

    public function accessIdInstansiBidang()
    {
        if(@$this->jabatanInduk->status_kepala==1) {
            return true;
        }

        return false;
    }

    public function updateIdInstansiBidang($id_instansi_bidang)
    {
        foreach($this->getManySub()->all() as $sub) {
            $sub->updateAttributes([
                'id_instansi_bidang'=>$id_instansi_bidang
            ]);
            $sub->updateIdInstansiBidang($id_instansi_bidang);
        }
    }

    public function copyJabatanEvjab($save = true)
    {
        if($this->jabatanEvjab!=null) {
            $this->nama = @$this->jabatanEvjab->nama;
            $this->nilai_jabatan = @$this->jabatanEvjab->nilai_jabatan;
            $this->kelas_jabatan = @$this->jabatanEvjab->kelas_jabatan;
        } else {
            $this->status_input_langsung = 1;
        }
        if ($save) {
            $this->save();
        }
    }

    public function getIconIdJabatanEvjab()
    {
        if($this->jabatanEvjab!=null)  {
            return "<i class='fa fa-check-circle' data-toggle='tooltip' title='Terverifikasi Evjab'></i>";
        }
    }

    public function getTooltipInstansiBidang()
    {

        if($this->instansiBidang==null) {
            return null;
        }

        return Html::tag("span",substr($this->instansiBidang->nama,0,20),[
           'data-toggle'=>'tooltip',
           'title'=> @$this->instansiBidang->nama
        ]);

    }

    public function updateAtasanKepalaSubinstansi()
    {
        if ($this->status_kepala && @$this->instansi->induk !== null) {
            $instansi = @$this->instansi->induk;
            /** @var Jabatan $jabatanKepalaInstansi */
            $jabatanKepalaInstansi = $instansi->getManyJabatanKepala()->one();
            if ($jabatanKepalaInstansi !== null) {
                return $this->updateAttributes(['id_parent' => $jabatanKepalaInstansi->id]);
            }
        }
        if ($this->status_kepala) {
            return $this->updateAttributes(['id_parent' => null]);
        }
    }

    public function getLinkUpdateAtasanKepala()
    {
        if($this->status_kepala==0) {
            return false;
        }

        return Html::a('<i class="fa fa-sitemap"></i> Ubah Atasan Kepala',[
            '/jabatan/update-atasan-kepala',
            'id'=>$this->id
        ],[
            'class' =>  'btn btn-success btn-flat'
        ]);
    }

    public function getLabelTingkatanFungsional()
    {
        $jenisJabatan = [
            self::NON_STRUKTURAL_JFT,
            self::NON_STRUKTURAL_JFU,
        ];
        if (in_array($this->id_jenis_jabatan,$jenisJabatan,false)) {
            if ($this->tingkatanFungsional == null) {
                return Html::a("<i class='fa fa-warning'></i>",['/jabatan/update','id' => $this->id],['data-toggle' => 'tooltip','title' => 'Tingkatan Jabatan Belum Diset: berpengaruh kepada Besaran TPP','style' => 'color: red','target' => '_blank']);
            } else {
                return Html::tag("b",'('.$this->tingkatanFungsional->nama.')');
            }
        }
    }

    public function getLabelTingkatanStruktural()
    {
        if ($this->id_jenis_jabatan == self::STRUKTURAL) {
            if ($this->eselon == null) {
                return Html::a("<i class='fa fa-warning'></i>",['/jabatan/update','id' => $this->id],['data-toggle' => 'tooltip','title' => 'Eselon Jabatan Belum Diset: berpengaruh kepada Besaran TPP','style' => 'color: red']);
            } else {
                return Html::tag("b",'('.$this->eselon->nama.')');
            }
        }
    }

    public function getCountBesaranTpp()
    {
        return;
    }

    public static function query($params = [])
    {
        $query = Jabatan::find();
        $query->andFilterWhere(['like','nama',@$params['nama']]);
        return $query;
    }

    public static function findArrayId(array $params = [])
    {
        $query = Jabatan::query($params);
        $query->select('id');
        return $query->column();
    }

    public function getArrayIdBawahan($params = []): array
    {
        $allSubJabatan = $this->getManySub()->all();

        $arrayIdBawahan = [];

        foreach ($allSubJabatan as $subJabatan) {
            $arrayIdBawahan[] = $subJabatan->id;

            if(@$params['recursive'] !== null) {
                $arrayIdBawahan = array_merge($arrayIdBawahan, $subJabatan->getArrayIdBawahan($params));
            }
        }

        return $arrayIdBawahan;

    }

    public function isKepala()
    {
        return $this->status_kepala == 1;
    }

    public function getEditableHasilAbk()
    {
        if (Session::isAdmin() == false) {
            return $this->hasil_abk;
        }

        return Editable::widget([
            'id' => 'hasil_abk-'.$this->id,
            'model' => $this,
            'name'=>'hasil_abk',
            'asPopover' => true,
            'value' => $this->hasil_abk,
            'valueIfNull' => 0,
            'header' => 'Hasil ABK',
            'inputType' => Editable::INPUT_TEXT,
            'placement' => 'top',
            'beforeInput' => Html::hiddenInput('editableKey',$this->id),
            'formOptions' => [
                'action' => ['/jabatan/update-editable']
            ],
            'options' => [
                'class' => 'form-control',
                'placeholder' => 'Hasil ABK',
            ],
            'pluginEvents' => [
                'editableSuccess' => 'function(event) { location.reload() }'
            ],
        ]);
    }

    public static function getListStatusTampil()
    {
        return [
            Jabatan::TAMPIL => 'Tampil',
            Jabatan::TIDAK_TAMPIL => 'Tidak Tampil',
        ];
    }
}
