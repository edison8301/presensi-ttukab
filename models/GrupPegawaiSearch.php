<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GrupPegawai;

/**
 * GrupPegawaiSearch represents the model behind the search form of `app\models\GrupPegawai`.
 */
class GrupPegawaiSearch extends GrupPegawai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_grup', 'id_pegawai'], 'integer'],
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
        $query = GrupPegawai::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_grup' => $this->id_grup,
            'id_pegawai' => $this->id_pegawai,
        ]);

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
