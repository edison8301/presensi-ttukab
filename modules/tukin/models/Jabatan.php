<?php

namespace app\modules\tukin\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "jabatan".
 *
 * @property int $id
 * @property int $id_jenis_jabatan
 * @property int $id_instansi
 * @property int $id_induk
 * @property string $bidang
 * @property string $subbidang
 * @property string $nama
 * @property int $kelas_jabatan
 * @property int $persediaan_pegawai
 * @property int $penyeimbang
 * @property bool $status_jumlah_tetap
 * @property float $jumlah_tetap
 *
 * @property Instansi $instansi
 * @property string $jenisJabatan
 * @property Pegawai $pegawai
 * @property Jabatan[] $manySub
 * @property bool $isJumlahTetap
 * @property Pegawai[] $manyPegawai
 */
class Jabatan extends \app\models\Jabatan
{
    /**
     * @var int
     */
    public $id_pegawai;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public static function getGrafikList()
    {
        return [['Struktural', (int) self::countStruktural()], [ 'Non Struktural', (int) self::countNonStruktural()]];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jenis_jabatan', 'id_instansi', 'kelas_jabatan', 'persediaan_pegawai', 'id_induk', 'id_pegawai'], 'integer'],
            [['penyeimbang', 'jumlah_tetap'], 'number'],
            [['nama', 'kelas_jabatan'], 'required'],
            [['status_jumlah_tetap'], 'boolean'],
            [['status_jumlah_tetap'], 'default', 'value' => false],
            [['jumlah_tetap'], 'required', 'when' => function ($row) {
                return $row->getIsJumlahTetap();
            }],
            [['bidang', 'subbidang', 'nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_jenis_jabatan' => 'Jenis Jabatan',
            'id_instansi' => 'Instansi',
            'nama' => 'Nama Jabatan',
            'kelas_jabatan' => 'Kelas Jabatan',
            'persediaan_pegawai' => 'Persediaan Pegawai',
            'id_induk' => 'Jabatan Induk',
            'id_pegawai' => 'Pegawai',
        ];
    }

    public static function getJenisJabatanList()
    {
        return [
            1 => "Struktural",
            2 => "Fungsional",
            3 => "Pelaksana"
        ];
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public static function countPersediaanPegawai()
    {
        return static::find()->sum('persediaan_pegawai');
    }

    public static function countTotal()
    {
        return static::find()->count();
    }

    public static function countStruktural()
    {
        return static::find()->andWhere(['id_jenis_jabatan' => 1])->count();
    }

    public static function countNonStruktural()
    {
        return static::find()->andWhere(['!=', 'id_jenis_jabatan', 1])->count();
    }

    public static function getList($id_instansi = null, $self_id = null)
    {
        $query = static::find()
            ->with('instansi')
            ->andFilterWhere([
               'id_instansi' => $id_instansi
            ])
            ->andFilterWhere([
                '!=',
                'id',
                $self_id
            ]);

        return ArrayHelper::map($query->all(), 'id', 'nama', 'instansi.nama');
    }

    /*
    public function getJenisJabatan()
    {
        return $this->hasOne(JenisJabatan::class,['id'=>'id_jenis_jabatan']);
    }
    */


    public function getPegawai()
    {
        $query = $this->hasOne(Pegawai::class, ['id_jabatan' => 'id']);
        if ($this->instansi !== null) {
            $instansi = $this->instansi;
            $ids = array_keys($instansi->getManySubInstansi()->select('id')->indexBy('id')->asArray()->all());
            $ids[] = $instansi->id;
            $query->andWhere(['in', 'id_instansi', $ids]);
        }
        return $query;
    }

    public function setIdPegawai()
    {
        $this->id_pegawai = @$this->pegawai->id;
    }

    public function saveIdPegawai()
    {
        if ($this->validate()) {
            $pegawai = Pegawai::findOne($this->id_pegawai);
            if ($pegawai !== null) {
                $pegawai->updateAttributes(['id_jabatan' => $this->id]);
            }
        }
        return true;
    }

    public function getIsJumlahTetap()
    {
        return (bool) $this->status_jumlah_tetap;
    }

    public function getIsStruktural()
    {
        return (bool) $this->id_jenis_jabatan === 1;
    }
}
