<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TunjanganKomponen;

/**
 * TunjanganKomponenSearch represents the model behind the search form of `app\models\TunjanganKomponen`.
 */
class TunjanganKomponenSearch extends TunjanganKomponen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'urutan', 'status_hapus', 'waktu_hapus', 'id_user_hapus'], 'integer'],
            [['nama'], 'safe'],
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
        $query = TunjanganKomponen::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'urutan' => $this->urutan,
            'status_hapus' => $this->status_hapus,
            'waktu_hapus' => $this->waktu_hapus,
            'id_user_hapus' => $this->id_user_hapus,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama]);

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
