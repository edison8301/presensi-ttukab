<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PegawaiNonTpp;

/**
 * PegawaiNonTppSearch represents the model behind the search form of `app\models\PegawaiNonTpp`.
 */
class PegawaiNonTppSearch extends PegawaiNonTpp
{
    public $nama_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_pegawai_non_tpp_jenis'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['nama_pegawai'], 'safe'],
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
        $query = PegawaiNonTpp::find();
        $query->joinWith(['pegawai']);

        $this->load($params);

        // add conditions that should always apply here
        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);

        // grid filtering conditions
        $query->andFilterWhere([
            'pegawai_non_tpp.id' => $this->id,
            'pegawai_non_tpp.id_pegawai_non_tpp_jenis' => $this->id_pegawai_non_tpp_jenis,
            'pegawai_non_tpp.id_pegawai' => $this->id_pegawai,
            'pegawai_non_tpp.tanggal_mulai' => $this->tanggal_mulai,
            'pegawai_non_tpp.tanggal_selesai' => $this->tanggal_selesai,
        ]);

        return $query;
    }
    
    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        

        return $dataProvider;
    }


}
