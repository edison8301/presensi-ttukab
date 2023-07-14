<?php

namespace app\modules\kinerja\models;

use app\components\Session;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use Yii;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "kegiatan_rhk".
 *
 * @property int $id
 * @property string $tahun
 * @property int $id_kegiatan_rhk_atasan
 * @property int $id_pegawai
 * @property int $id_instansi_pegawai
 * @property int $id_instansi_pegawai_skp
 * @property int $id_kegiatan_rhk_jenis
 * @property string $nama
 * @property Pegawai $pegawai
 * @property KegiatanRhkJenis $kegiatanRhkJenis
 * @property KegiatanRhk $kegiatanRhkAtasan
 * @property InstansiPegawaiSkp $instansiPegawaiSkp
 * @property InstansiPegawai $instansiPegawai
 * @property int $id_induk
 * @property KegiatanRhk $induk
 * @property int $status_hapus
 * @method softDelete() dari SoftDeleteBehavior
 */
class KegiatanRhk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan_rhk';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun', 'id_pegawai', 'id_instansi_pegawai', 'id_kegiatan_rhk_jenis',
                'id_instansi_pegawai_skp', 'nama'], 'required'],
            [['tahun', 'nama'], 'safe'],
            [['id_pegawai', 'id_instansi_pegawai', 'id_kegiatan_rhk_jenis', 'id_kegiatan_rhk_atasan'], 'integer'],
            [['id_kegiatan_rhk_atasan'], 'required', 'when' => function(KegiatanRhk $data) {
                return $data->isRequiredIdKegiatanRhkAtasan();
            }],
            [['id_induk', 'status_hapus'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun' => 'Tahun',
            'id_kegiatan_rhk_atasan' => 'Rencana Hasil Kerja Atasan Yang Diintervensi',
            'id_pegawai' => 'Pegawai',
            'id_instansi_pegawai' => 'Instansi Pegawai',
            'id_instansi_pegawai_skp' => 'Instansi Pegawai SKP',
            'id_kegiatan_rhk_jenis' => 'Jenis RHK',
            'nama' => 'Rencana Hasil Kerja',
        ];
    }

    public function behaviors()
    {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'status_hapus' => true,
                ],
            ],
        ];
    }

    public function afterSoftDelete()
    {
        foreach ($this->findAllSub() as $sub) {
            $sub->softDelete();
        }

        foreach ($this->findAllKegiatanTahunan() as $kegiatanTahunan) {
            $kegiatanTahunan->softDelete();
        }

        return true;
    }

    public static function find()
    {
        $query = parent::find();
        $query->andWhere(['kegiatan_rhk.status_hapus' => false]);
        return $query;
    }

    public function isRequiredIdKegiatanRhkAtasan()
    {
        $instansiPegawai = $this->instansiPegawaiSkp->instansiPegawai;
        $eselon = @$instansiPegawai->jabatan->eselon;

        if ($eselon == null) {
            return false;
        }

        if (@$eselon->non_eselon == 1) {
            return true;
        }

        if ($instansiPegawai->isJpt() == false) {
            return true;
        }

        return false;
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::class, ['id' => 'id_pegawai']);
    }

    public function getKegiatanRhkJenis()
    {
        return $this->hasOne(KegiatanRhkJenis::class, ['id' => 'id_kegiatan_rhk_jenis']);
    }

    public function getKegiatanRhkAtasan()
    {
        return $this->hasOne(KegiatanRhk::class, ['id' => 'id_kegiatan_rhk_atasan']);
    }

    public function getManyKegiatanTahunan()
    {
        return $this->hasMany(KegiatanTahunan::class, ['id_kegiatan_rhk' => 'id']);
    }

    public function getInstansiPegawaiSkp()
    {
        return $this->hasOne(InstansiPegawaiSkp::class, ['id' => 'id_instansi_pegawai_skp']);
    }

    public function getInstansiPegawai()
    {
        return $this->hasOne(InstansiPegawai::class, ['id' => 'id_instansi_pegawai']);
    }

    public function getInduk()
    {
        return $this->hasOne(KegiatanRhk::class, ['id' => 'id_induk']);
    }

    public function getManySub()
    {
        return $this->hasOne(KegiatanRhk::class, ['id_induk' => 'id']);
    }

    /**
     * @return KegiatanTahunan[]|array|\yii\db\ActiveRecord[]
     */
    public function findAllKegiatanTahunan()
    {
        $query = $this->getManyKegiatanTahunan();
        $query->orderBy([
            'id_kegiatan_rhk' => SORT_ASC,
        ]);

        return $query->all();
    }

    public function getListKegiatanRhkAtasan(array $params = [])
    {
        $instansiPegawai = $this->instansiPegawaiSkp->instansiPegawai;
        $instansiPegawaiAtasan = $instansiPegawai->getInstansiPegawaiAtasan()
            ->filterByTahun($this->tahun)
            ->one();

        $query = KegiatanRhk::find();
        $query->andWhere([
            'tahun' => $this->tahun,
            'id_pegawai' => @$instansiPegawaiAtasan->id_pegawai,
        ]);

        if (@$params['id_kegiatan_rhk_jenis'] != null) {
            $query->andWhere(['id_kegiatan_rhk_jenis' => @$params['id_kegiatan_rhk_jenis']]);
        }

        return ArrayHelper::map($query->all(), 'id', 'nama');
    }

    public function canUpdate()
    {
        if (Session::isAdmin()) {
            return true;
        }

        if (Session::isPegawai() AND $this->id_pegawai == Session::getIdPegawai()) {
            return true;
        }

        return false;
    }

    public function getJumlahKegiatanTahunan()
    {
        $query = $this->getManyKegiatanTahunan();

        return $query->count();
    }

    public function getLinkUpdateButton()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-pencil"></i> Ubah', [
            '/kinerja/kegiatan-rhk/update',
            'id' => $this->id,
        ], [
            'class' => 'btn btn-warning btn-flat btn-xs'
        ]);
    }

    public function getLinkDeleteButton()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-trash"></i> Hapus', [
            '/kinerja/kegiatan-rhk/delete',
            'id' => $this->id,
        ], [
            'class' => 'btn btn-danger btn-flat btn-xs',
            'data-method' => 'post',
            'data-confirm' => 'Yakin akan menghapus data?',
        ]);
    }

    public function getLinkCreateTahapan()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-plus"></i> Tahapan', [
            '/kinerja/kegiatan-rhk/create',
            'id_induk' => $this->id,
        ], [
            'class' => 'btn btn-primary btn-flat btn-xs',
        ]);
    }

    public function getLinkCreateKegiatanTahunan()
    {
        if ($this->canUpdate() == false) {
            return null;
        }

        return Html::a('<i class="fa fa-plus"></i> Indikator', [
            '/kinerja/kegiatan-tahunan/create-v3',
            'id_kegiatan_rhk' => $this->id,
        ], [
            'class' => 'btn btn-success btn-flat btn-xs'
        ]);
    }

    public function isJpt()
    {
        if (@$this->instansiPegawai == null) {
            return false;
        }

        return $this->instansiPegawai->isJpt();
    }

    public function getButtonDropdown()
    {
        return ButtonDropdown::widget([
            'label' => '',
            'options' => ['class' => 'btn btn-xs btn-primary btn-flat'],
            'dropdown' => [
                'encodeLabels' => false,
                'items' => [
                    ['label' => '<i class="fa fa-plus"></i> Tambah Tahapan', 'url' => [
                        '/kinerja/kegiatan-rhk/create',
                        'id_induk' => $this->id,
                    ], 'visible' => $this->canUpdate()],
                    ['label' => '<i class="fa fa-plus"></i> Tambah Indikator', 'url' => [
                        '/kinerja/kegiatan-tahunan/create-v3',
                        'id_kegiatan_rhk' => $this->id,
                    ], 'visible' => $this->canUpdate()],
                    ['label' => '<i class="fa fa-pencil"></i> Ubah', 'url' => ['/kinerja/kegiatan-rhk/update', 'id' => $this->id], 'visible' => $this->canUpdate()],
                    ['label' => '<i class="fa fa-trash"></i> Hapus', 'url' => ['/kinerja/kegiatan-rhk/delete', 'id' => $this->id], 'visible' => $this->canUpdate()],
                ],
            ],
        ]);
    }

    public function getNamaInduk()
    {
        return @$this->induk->nama;
    }

    public function loadAttributes()
    {
        $this->id_instansi_pegawai = @$this->instansiPegawaiSkp->id_instansi_pegawai;
        $this->id_pegawai = @$this->instansiPegawaiSkp->instansiPegawai->id_pegawai;

        if ($this->induk != null) {
            $this->id_instansi_pegawai_skp = $this->induk->id_instansi_pegawai_skp;
            $this->id_instansi_pegawai = $this->induk->id_instansi_pegawai;
            $this->id_pegawai = $this->induk->id_pegawai;
        }
    }

    /**
     * @return array|\yii\db\ActiveRecord[]|KegiatanRhk[]
     */
    public function findAllSub()
    {
        $query = $this->getManySub();

        return $query->all();
    }

    public function getJumlahSub()
    {
        $query = $this->getManySub();

        return $query->count();
    }

    public function getNomorSkpLengkap()
    {
        $instansiPegawaiSkp = $this->instansiPegawaiSkp;

        if ($instansiPegawaiSkp != null) {
            return $instansiPegawaiSkp->nomor.' : '.$instansiPegawaiSkp->getNamaJabatan().' - '.$instansiPegawaiSkp->getNamaInstansi();
        }

        return '-';
    }
}
