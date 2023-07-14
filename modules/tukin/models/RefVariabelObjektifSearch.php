<?php

namespace app\modules\tukin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tukin\models\RefVariabelObjektif;

/**
 * RefVariabelObjektifSearch represents the model behind the search form of `app\modules\tukin\models\RefVariabelObjektif`.
 */
class RefVariabelObjektifSearch extends RefVariabelObjektif
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['kode', 'kelompok', 'uraian', 'satuan'], 'safe'],
            [['tarif'], 'number'],
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
        $query = RefVariabelObjektif::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tarif' => $this->tarif,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'kelompok', $this->kelompok])
            ->andFilterWhere(['like', 'uraian', $this->uraian])
            ->andFilterWhere(['like', 'satuan', $this->satuan]);

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
