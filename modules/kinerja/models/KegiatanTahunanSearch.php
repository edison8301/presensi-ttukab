<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Pegawai;

/**
 * KegiatanTahunanSearch represents the model behind the search form of `app\models\KegiatanTahunan`.
 */
class KegiatanTahunanSearch extends KegiatanTahunan
{
    const SCENARIO_PEGAWAI = 'pegawai';
    const SCENARIO_BAWAHAN = 'bawahan';
    const SCENARIO_ATASAN =  'atasan';

    public $mode = 'pegawai';
    public $nomor_skp;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_hapus', 'id_pegawai', 'target_waktu', 'id_pegawai_penyetuju',
                'id_kegiatan_status', 'id_instansi_pegawai'
            ], 'integer'],
            [['nama_pegawai','nomor_skp'],'string'],
            [['nama', 'satuan_kuantitas', 'target_kuantitas', 'waktu_dibuat', 'waktu_disetujui',
                'tahun','target_angka_kredit'
            ], 'safe'],
            [['id_kegiatan_tahunan_versi', 'id_instansi_pegawai_skp'], 'safe'],
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
     * @param $params
     * @return KegiatanTahunanQuery
     */
    public function querySearch($params)
    {

        $this->load($params);

        if (empty($this->tahun)) {
            $this->tahun = User::getTahun();
        }

        $query = KegiatanTahunan::find()
            ->tahun($this->tahun)
            ->induk();

        $query->joinWith(['pegawai','instansiPegawai']);
        $query->with(['manySub','instansiPegawai','instansiPegawaiSkp',
            'manyKegiatanHarian']);

        if($this->nomor_skp!=null) {
            $query->joinWith(['instansiPegawaiSkp']);
            $query->andWhere(['like','instansi_pegawai_skp.nomor',$this->nomor_skp]);
        }

        if (User::isPegawai() AND $this->isScenarioPegawai()) {
            $this->id_pegawai = User::getIdPegawai();
        }

        $query->andFilterWhere([
            'kegiatan_tahunan.id' => $this->id,
            'instansi_pegawai.id_pegawai' => $this->id_pegawai,
            'kegiatan_tahunan.target_waktu' => $this->target_waktu,
            'kegiatan_tahunan.nama_pegawai' => $this->nama_pegawai,
            'kegiatan_tahunan.id_kegiatan_status' => $this->id_kegiatan_status,
            'kegiatan_tahunan.id_pegawai_penyetuju' => $this->id_pegawai_penyetuju,
            'kegiatan_tahunan.id_instansi_pegawai' => $this->id_instansi_pegawai,
            'kegiatan_tahunan.waktu_dibuat' => $this->waktu_dibuat,
            'kegiatan_tahunan.waktu_disetujui' => $this->waktu_disetujui,
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => $this->id_kegiatan_tahunan_versi,
            'kegiatan_tahunan.id_kegiatan_tahunan_jenis' => $this->id_kegiatan_tahunan_jenis,
            'kegiatan_tahunan.id_instansi_pegawai_skp' => $this->id_instansi_pegawai_skp,
        ]);
        $query->andFilterWhere(['like', 'kegiatan_tahunan.nama', $this->nama])
            ->andFilterWhere(['like', 'satuan_kuantitas', $this->satuan_kuantitas])
            ->andFilterWhere(['like', 'target_kuantitas', $this->target_kuantitas]);

        return $query;
    }

    public function getQueryBawahan($params)
    {
        $query = $this->querySearch($params);

        $query->joinWith(['jabatan']);
        $query->andWhere(['jabatan.id_induk' => User::getIdJabatanBerlaku()]);
        $query->andWhere(['!=', 'kegiatan_tahunan.id_kegiatan_status', KegiatanStatus::KONSEP]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->querySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize'=>15],
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        return $dataProvider;
    }

    public function searchBawahan($params)
    {
        $query = $this->getQueryBawahan($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize'=>15],
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        return $dataProvider;
    }

    public function searchBySession($params)
    {
        $query = $this->querySearch($params);
        $query->byPegawaiSession();

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

    public function searchSubordinat($params)
    {
        $query = $this->getQuerySearch($params);
        $query->subordinat();

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

    public function getListPegawaiBawahan()
    {


    }

    public function getListPegawai()
    {
        if ($this->isScenarioPegawai()) {
            return Pegawai::getList();
        }

        /*
        if ($this->isScenarioAtasan())  {
            $query = Pegawai::find()
                ->joinWith('allInstansiPegawai')
                ->andWhere(['{{%instansi_pegawai}}.id_atasan' => User::getIdPegawai()])
                ->orderBy(['nama' => SORT_ASC]);

            return ArrayHelper::map($query->all(),'id','namaNip');
        }
        */
    }

    public function loadDefaultAttributes()
    {
        if (User::isPegawai() && !$this->getIsScenarioBawahan()) {

            $this->id_pegawai = User::getIdPegawai();

            $pegawai = User::getModelPegawai();

            if ($pegawai !== null AND $pegawai->getHasMutasi()) {
                $this->id_instansi_pegawai = $pegawai->getInstansiPegawaiBerlaku()->id;
            }
        }
    }

    public function getNomorSkpLengkap()
    {
        if($this->nomor_skp!=null) {
            return $this->nomor_skp.' : '.$this->instansiPegawai;
        }
    }

}
