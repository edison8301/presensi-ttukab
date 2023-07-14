<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\Pegawai;
use app\models\User;
use app\components\Helper;

/**
 * PegawaiSearch represents the model behind the search form of `app\models\Pegawai`.
 */
class PegawaiSearch extends Pegawai
{
    /**
     * @inheritdoc
     */
    public $mode = 'pegawai';
    public $bulan;
    public $tahun;
    public $tanggal;

    public function rules()
    {
        return [
            [['id', 'id_instansi', 'id_jabatan', 'id_atasan'], 'integer'],
            [['mode','nama_jabatan'],'safe'],
            [['bulan','tahun'],'integer'],
            [['tanggal'],'safe'],
            [['nama', 'nip', 'gender', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'telepon', 'email', 'foto', 'grade'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    
    public function querySearch($params)
    {
        $this->load($params);

        if(empty($params['PegawaiSearch']['bulan'])) {
            $this->bulan = date('n');
        }

        $query = Pegawai::find();

        if(User::isPegawai() AND $this->mode = 'bawahan')
        {
            $this->id_atasan = User::getIdPegawai();
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'id_jabatan' => $this->id_jabatan,
            'id_atasan' => $this->id_atasan,
            'tanggal_lahir' => $this->tanggal_lahir,
        ]);

        $query->andFilterWhere(['or',['like', 'nama', $this->nama],['like', 'nip', $this->nama]])
            ->andFilterWhere(['like', 'nip', $this->nip])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'telepon', $this->telepon])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'grade', $this->grade]);

        return $query;
    }
    
    public function search($params)
    {
        $query = $this->querySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        

        return $dataProvider;
    }

    public function searchBawahan($params)
    {
        $this->id_atasan = User::getIdPegawai();

        $query = $this->querySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        

        return $dataProvider;
    }


    public function getMasa() {
        $masa = '';

        $masa = User::getTahun();

        if($this->bulan!=null) {
            $masa = Helper::getBulanLengkap($this->bulan).' '.User::getTahun();
        }

        if($this->tanggal!=null) {
            $masa = Helper::getHariTanggal($this->tanggal);
        }

        return $masa;
    }


}
