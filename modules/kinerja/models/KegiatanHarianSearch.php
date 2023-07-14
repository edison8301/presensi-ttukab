<?php

namespace app\modules\kinerja\models;

use app\components\Helper;
use app\components\Session;
use app\models\Instansi;
use app\models\InstansiPegawai;
use app\models\Pegawai;
use app\models\User;
use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * KegiatanHarianSearch represents the model behind the search form of `app\models\KegiatanHarian`.
 *
 * @property bool|DateTime $date
 * @property bool $isScenarioPegawai
 * @property mixed $listPegawai
 * @property mixed $tahun
 * @property Instansi $instansi
 * @property string $bulanLengkapTahun
 * @property bool $isScenarioBawahan
 */
class KegiatanHarianSearch extends KegiatanHarian
{

    const SCENARIO_PEGAWAI = 'pegawai';
    const SCENARIO_BAWAHAN = 'bawahan';
    const SCENARIO_ATASAN = 'atasan';

    public $tahun;
    public $bulan;
    public $id_instansi;
    public $id_instansi_pegawai;
    public $id_kegiatan_tahunan_versi;

    public function init()
    {
        if ($this->tahun === null) {
            $this->tahun = User::getTahun();
        }
        if ($this->bulan === null) {
            $this->bulan = date('n');
        }
        parent::init();
    }

    protected $_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kuantitas', 'id_pegawai_penyetuju', 'id_kegiatan_status', 'waktu',
                'id_pegawai', 'id_kegiatan_harian_jenis', 'bulan', 'id_instansi', 'id_instansi_pegawai',
            ], 'integer'],
            [['tahun'], 'number'],
            [['waktu'], 'safe'],
            [['tanggal', 'uraian', 'jam_mulai', 'jam_selesai', 'berkas', 'kode_kegiatan_status', 'waktu_disetujui', 'id_kegiatan_tahunan'], 'safe'],
            [['id_kegiatan_tahunan_versi'], 'integer'],
            [['id_kegiatan_harian_versi', 'id_kegiatan_harian_jenis'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        /**
         * bypass scenarios() implementation in the parent class
         * implements self scenario rules
         */
        $scenarios = Model::scenarios();
        $scenarios[self::SCENARIO_PEGAWAI] = $scenarios[self::SCENARIO_DEFAULT];
        $scenarios[self::SCENARIO_BAWAHAN] = $scenarios[self::SCENARIO_DEFAULT];
        $scenarios[self::SCENARIO_ATASAN] = $scenarios[self::SCENARIO_DEFAULT];
        return $scenarios;
    }

    public function isScenarioPegawai()
    {
        return $this->scenario === self::SCENARIO_PEGAWAI;
    }

    public function isScenarioBawahan()
    {
        return $this->scenario === self::SCENARIO_BAWAHAN;
    }

    public function isScenarioAtasan()
    {
        return $this->scenario === self::SCENARIO_ATASAN;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return KegiatanHarianQuery
     */

    public function querySearch($params)
    {
        $this->load($params);

        $query = KegiatanHarian::find();

        //$query->joinWith(['instansiPegawaiViaPegawai']);

        $query->with(['kegiatanHarianTambahan', 'kegiatanTahunan', 'kegiatanHarianJenis',
                'kegiatanStatus', 'pegawai']);

        if (User::isPegawai() and $this->isScenarioPegawai()) {
            $this->id_pegawai = Session::getIdPegawai();
        }

        if($this->tanggal != null) {
            $query->filterByTanggal($this->tanggal);
        } elseif($this->bulan !=null) {
            $query->filterByBulan($this->bulan,User::getTahun());
        } else {
            $query->filterByTahun(User::getTahun());
        }

        $query->andFilterWhere([
            'kegiatan_harian.id' => $this->id,
            'kegiatan_harian.tanggal' => $this->tanggal,
            'kegiatan_harian.kuantitas' => $this->kuantitas,
            'kegiatan_harian.id_pegawai' => $this->id_pegawai,
            'kegiatan_harian.jam_mulai' => $this->jam_mulai,
            'kegiatan_harian.jam_selesai' => $this->jam_selesai,
            'kegiatan_harian.id_kegiatan_harian_jenis' => $this->id_kegiatan_harian_jenis,
            'kegiatan_harian.id_pegawai_penyetuju' => $this->id_pegawai_penyetuju,
            'kegiatan_harian.waktu_disetujui' => $this->waktu_disetujui,
            'kegiatan_harian.id_instansi_pegawai' => $this->id_instansi_pegawai,
            'kegiatan_harian.id_kegiatan_status' => $this->id_kegiatan_status,
            'kegiatan_harian.id_kegiatan_tahunan' => $this->id_kegiatan_tahunan,
            'kegiatan_harian.id_kegiatan_harian_versi' => $this->id_kegiatan_harian_versi,
        ]);

        $query->andFilterWhere(['like', 'kegiatan_harian.uraian', $this->uraian])
            ->andFilterWhere(['like', 'kegiatan_harian.berkas', $this->berkas]);

        $query->orderBy(['kegiatan_harian.tanggal' => SORT_ASC, 'kegiatan_harian.jam_mulai' => SORT_ASC]);

        return $query;
    }

    public function getQueryBawahan($params)
    {
        $query = $this->querySearch($params);

        //$query->joinWith(['instansiPegawaiViaPegawai']);

        //$query->andWhere(['instansi_pegawai.id_jabatan'=>User::getListIdJabatanBawahan()]);

        $queryInstansiPegawai = InstansiPegawai::find();

        $queryInstansiPegawai->andWhere([
            'id_jabatan' => User::getListIdJabatanBawahan()
        ]);

        $tahun = $this->tahun;

        if($tahun == null) {
            $tahun = Session::getTahun();
        }

        $bulan = $this->bulan;

        if($bulan == null) {
            $bulan = date('n');
        }

        $datetime = DateTime::createFromFormat('Y-n-d', $tahun.'-'.$bulan.'-01');

        $tanggal = $datetime->format('Y-m-15');

        $queryInstansiPegawai->berlaku($tanggal);

        $id_pegawai_bawahan_list = $queryInstansiPegawai->select('id_pegawai')->column();

        if(count($id_pegawai_bawahan_list) == 0) {
            $id_pegawai_bawahan_list = '-';
        }

        $query->andWhere(['kegiatan_harian.id_pegawai'=>$id_pegawai_bawahan_list]);


        $query->andWhere('kegiatan_harian.id_kegiatan_status != :konsep', [
            ':konsep' => KegiatanStatus::KONSEP
        ]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->querySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        return $dataProvider;
    }

    public function searchBawahan($params)
    {
        $query = $this->getQueryBawahan($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        return $dataProvider;
    }

    public function searchHariIni($params)
    {
        if ($this->tanggal == null and $this->id_kegiatan_status == null) {
            $this->tanggal = date('Y-m-d');
        }

        $query = $this->querySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function searchBySession($params, $tanggal = null)
    {
        $query = $this->querySearch($params);
        $query->byPegawaiSession();
        if (!empty($tanggal)) {
            $query->andWhere(['tanggal' => $tanggal]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function searchSubordinat($params, $tanggal = null)
    {
        $query = $this->getQuerySearch($params);
        $query->subordinat();
        if (!empty($tanggal)) {
            $query->andWhere(['tanggal' => $tanggal]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function getListPegawai()
    {
        if ($this->mode == 'pegawai') {
            return Pegawai::getList();
        }

        if ($this->mode == 'bawahan') {
            $query = Pegawai::find();
            $query->andWhere(['id_atasan' => User::getIdPegawai()]);
            $query->orderBy(['nama' => SORT_ASC]);

            return ArrayHelper::map($query->all(), 'id', 'nama');
        }
    }

    public function getDate()
    {
        if ($this->bulan === null) {
            return false;
        }
        if ($this->_date === null) {
            $this->_date = new DateTime(date("$this->tahun-$this->bulan-d"));
        }
        return $this->_date;
    }

    /**
     * @return Pegawai[]
     */
    public function searchPegawaiRekap()
    {
        if (User::isInstansi()) {
            $this->id_instansi = User::getIdInstansi();
        }
        if (empty($this->id_instansi)) {
            return false;
        }
        $date = $this->getDate();
        $query = Pegawai::find()
            ->with([
                'manyKegiatanHarian' => function (\yii\db\ActiveQuery $query) use ($date) {
                    $query->andWhere('id_kegiatan_status = ' . KegiatanStatus::SETUJU)
                        ->andWhere(['between', 'tanggal', $date->format("Y-m-01"), $date->format("Y-m-t")]);
                },
                'manyKegiatanTahunan',
                'manyKetidakhadiran' => function (\yii\db\ActiveQuery $query) {
                    $query->indexBy('tanggal');
                },
                'manyKetidakhadiranPanjang' => function (\yii\db\ActiveQuery $query) use ($date) {
                    $query->andWhere('
                            (tanggal_mulai <= :tanggal_awal AND (tanggal_selesai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir)) OR
                            (tanggal_mulai >= :tanggal_awal AND tanggal_selesai <= :tanggal_akhir) OR
                            ((tanggal_mulai >= :tanggal_awal AND tanggal_mulai <= :tanggal_akhir) AND tanggal_selesai >= :tanggal_akhir) OR
                            (tanggal_mulai <= :tanggal_awal AND tanggal_selesai >= :tanggal_akhir)
                        ',
                        [
                            ':tanggal_awal' => $date->format("Y-m-01"),
                            ':tanggal_akhir' => $date->format("Y-m-t"),
                        ]
                    );
                },
                'manyPegawaiShiftKerja.shiftKerja.manyJamKerja',
            ])
            ->andWhere(['id_instansi' => $this->id_instansi])
            ->orderBy(['id_golongan' => SORT_DESC]);
        if (User::isPegawai()) {
            $query->andWhere(['id' => User::getIdPegawai()]);
        }
        return $query->all();
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::class, ['id' => 'id_instansi']);
    }

    public function getBulanLengkapTahun()
    {
        $output = $this->tahun;

        if ($this->bulan != null) {
            $output = Helper::getBulanLengkap($this->bulan) . ' ' . $this->tahun;
        }

        return $output;
    }
}
