<?php

namespace app\models;

use app\models\Pegawai;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use \DateTime;

/**
 * PegawaiSearch represents the model behind the search form of `app\models\Pegawai`.
 */
class PegawaiSearch extends Pegawai
{
    const SCENARIO_BAWAHAN = 'bawahan';
    const SCENARIO_ATASAN = 'atasan';
    const SCENARIO_PEGAWAI = 'pegawai';

    /**
     * @inheritdoc
     */
    public $mode = 'pegawai';
    public $bulan = 1;
    public $tahun;
    public $tanggal;
    public $id_atasan_direct;
    protected $_date;
    public $nama_jabatan_relasi;

    public function rules()
    {
        return [
            [['id', 'id_instansi', 'id_jabatan', 'bulan', 'tahun', 'jumlah_userinfo', 'id_atasan_direct'], 'integer'],
            [['mode', 'nama_jabatan', 'tanggal'], 'safe'],
            [['nama', 'nip'], 'safe'],
            [['nama_jabatan_relasi','id_pegawai_jenis'],'safe'],
        ];
    }

    public function getDate($refresh = false)
    {
        if ($this->_date === null or $refresh) {
            $date = User::getTahun().'-'.$this->bulan.'-01';
            $this->_date = new DateTime($date);
        }
        return $this->_date;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = Model::scenarios();
        $scenarios[self::SCENARIO_BAWAHAN] = $scenarios[self::SCENARIO_DEFAULT];
        return $scenarios;
    }

    public function getIsScenarioBawahan()
    {
        return $this->scenario === self::SCENARIO_BAWAHAN;
    }

    public function getIsScenarioAtasan()
    {
        return $this->scenario === self::SCENARIO_ATASAN;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return PegawaiQuery
     */

    public function querySearch($params)
    {
        $this->load($params);

        $query = Pegawai::find();
        $query->with(['instansi', 'atasan']);

        if (User::isPegawai() and $this->getIsScenarioAtasan()) {
            /*
            $this->id_atasan = User::getIdPegawai();
            $query->joinWith('allInstansiPegawai');
            $query->andWhere('instansi_pegawai.id_atasan = :id_atasan OR
                pegawai.id_atasan = :id_atasan',[
                    ':id_atasan'=>$this->id_atasan
            ]);
            */

            /*
            if(User::getListIdJabatanBawahan()!=[]) {
                $query->andFilterWhere(['pegawai.id_jabatan'=>User::getListIdJabatanBawahan()]);
            } else {
                return $query->andWhere('pegawai.id IS NULL');
            }
            */
        }

        if (User::isAdminInstansi()) {
            $query->joinWith('allInstansiPegawai');
            $query->andWhere(['instansi_pegawai.id_instansi' => User::getListIdInstansi()]);
        }

        if (User::isInstansi()) {
            $query->joinWith('allInstansiPegawai');
            $query->andWhere(['instansi_pegawai.id_instansi' => User::getListIdInstansi()]);
        }

        if (User::isVerifikator()) {
            $query->joinWith('allInstansiPegawai');
            $query->andWhere(['instansi_pegawai.id_instansi' => User::getListIdInstansi()]);
        }

        if (User::isGrup()) {
            $query->andWhere(['id' => User::getListIdPegawai()]);
        }

        $query->andFilterWhere([
            'pegawai.id' => $this->id,
            // 'pegawai.id_instansi' => $this->id_instansi,
            'pegawai.id_jabatan' => $this->id_jabatan,
            'pegawai.id_atasan' => $this->id_atasan_direct,
        ]);
        $query->andFilterWhere(['or',
            ['like', 'pegawai.nama', $this->nama],
            ['like', 'pegawai.nip', $this->nama],
        ])
            ->andFilterWhere(['like', 'pegawai.nip', $this->nip])
            ->andFilterWhere(['like', 'pegawai.nama_jabatan', $this->nama_jabatan])
            ->andFilterWhere(['like', 'pegawai.id_pegawai_jenis', $this->id_pegawai_jenis]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->querySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => '10'],
        ]);

        return $dataProvider;
    }

    /*
    public function searchTest($params)
    {
    $this->load($params);

    $query = Pegawai::find();
    $query->leftJoin('pegawai_instansi','(SELECT MAX(tanggal_berlaku), id_instansi FROM pegawai_instansi WHERE tanggal_berlaku <= :tanggal) m2 ON m2.id_instansi = :id_instansi')
    //$query->joinWith(['pegawaiInstansi']);

    $query->andWhere('pegawai_instansi.tanggal_berlaku <= :tanggal',[
    ':tanggal'=>'2018-01-01'
    ]);

    $query = 'SELECT (*) FROM pegawai LEFT JOIN (SELECT MAX(tanggal_berlaku), id_instansi, id_pegawai FROM pegawai_instansi GROUP BY id_pegawai) pegawaiInstansi ON pegawaiInstansi.id_pegawai = pegawai.id WHERE pegawaiInstansi.tanggal_berlaku <= :tanggal';

    $model = Yii::$app->db->createCommand($query)
    ->bindValue(':tanggal','2017-12-01')
    ->queryAll();

    if($this->id_instansi!=null) {
    $query->andWhere([
    'pegawai_instansi.id_instansi'=>$this->id_instansi,
    ]);
    }

    $query

    $dataProvider = new ActiveDataProvider([
    'query' => $query,
    'pagination'=>['pageSize'=>'10']
    ]);

    return $dataProvider;
    }
     */

    public function searchBawahan($params)
    {
        $this->id_atasan = User::getIdPegawai();

        $query = $this->querySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}
