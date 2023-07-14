<?php

namespace app\modules\tunjangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tunjangan\models\TunjanganPotongan;

/**
 * TunjanganPotonganSearch represents the model behind the search form of `app\modules\tunjangan\models\TunjanganPotongan`.
 */
class TunjanganPotonganSearch extends TunjanganPotongan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
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
        $query = TunjanganPotongan::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
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
