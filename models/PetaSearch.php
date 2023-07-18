<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Peta;

/**
 * PetaSearch represents the model behind the search form of `app\models\Peta`.
 */
class PetaSearch extends Peta
{   
    public $nama_instansi;
    public $nama_pegawai;
    public $nip_pegawai;
    public $mode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_induk'], 'integer'],
            [['nama', 'keterangan','jarak','id_instansi','latitude','longitude','nama_instansi',
                'mode', 'nama_pegawai', 'status_rumah', 'nip_pegawai'], 'safe'],
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

    public function getQuerySearch($params)
    {
        $query = Peta::find();

        $this->load($params);

        if (Session::isInstansi()) {
            $query->andWhere(['id_instansi' => Session::getIdInstansi()]);
        }

        if (Session::isAdminInstansi()) {
            $query->andWhere(['id_instansi' => Session::getIdInstansi()]);
        }

        // add conditions that should always apply here
        if ($this->mode == 'instansi') {
            $query->andWhere('peta.id_instansi is not null OR peta.id_pegawai is null');
        }

        if ($this->mode == 'pegawai') {
            $this->status_rumah = 0;
            $query->andWhere('peta.id_pegawai is not null');
        }

        if ($this->mode == 'pegawai-wfh') {
            $this->status_rumah = 1;
            $query->andWhere('peta.id_pegawai is not null');
        }

        if ($this->mode == 'kegiatan') {
            $query->andWhere('peta.id_instansi is null AND peta.id_pegawai is null');
        }

        if($this->nama_pegawai != null) {
            $query->joinWith(['pegawai']);
            $query->andWhere(['like', 'pegawai.nama', $this->nama_pegawai]);
        }

        if($this->nip_pegawai != null) {
            $query->joinWith(['pegawai']);
            $query->andWhere(['like', 'pegawai.nip', $this->nip_pegawai]);
        }

        if($this->nama_instansi != null){
            $query->joinWith('instansi');
            $query->andFilterWhere(['like', 'instansi.nama', $this->nama_instansi]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'peta.id' => $this->id,
            'peta.id_induk' => $this->id_induk,
            'peta.id_instansi' => $this->id_instansi,
            'peta.jarak' => $this->jarak,
            'peta.status_rumah' => $this->status_rumah,
        ]);

        $query->andFilterWhere(['like', 'peta.nama', $this->nama])
            ->andFilterWhere(['like', 'peta.id_instansi', $this->id_instansi])
            ->andFilterWhere(['like', 'peta.latitude', $this->latitude])
            ->andFilterWhere(['like', 'peta.longitude', $this->longitude])
            ->andFilterWhere(['like', 'peta.keterangan', $this->keterangan]);

        return $query;
    }
    
    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        

        return $dataProvider;
    }


}
