<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\SkpPerilaku;

/**
 * SkpPerilakuSearch represents the model behind the search form of `app\modules\kinerja\models\SkpPerilaku`.
 */
class SkpPerilakuSearch extends SkpPerilaku
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_skp', 'id_skp_perilaku_jenis'], 'integer'],
            [['ekspektasi'], 'safe'],
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
     * @return \yii\db\ActiveQuery
     */

    public function getQuerySearch($params)
    {
        $query = SkpPerilaku::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_skp' => $this->id_skp,
            'id_skp_perilaku_jenis' => $this->id_skp_perilaku_jenis,
        ]);

        $query->andFilterWhere(['like', 'ekspektasi', $this->ekspektasi]);

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
