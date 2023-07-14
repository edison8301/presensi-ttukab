<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PegawaiAtribut;

/**
 * PegawaiAtributSearch represents the model behind the search form of `app\models\PegawaiAtribut`.
 */
class PegawaiAtributSearch extends PegawaiAtribut
{
    public $nama_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai'], 'integer'],
            [['keterangan', 'tanggal', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = PegawaiAtribut::find();
        $query->joinWith(['pegawai']);

        $this->load($params);

        // add conditions that should always apply here
        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'tanggal' => $this->tanggal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

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
