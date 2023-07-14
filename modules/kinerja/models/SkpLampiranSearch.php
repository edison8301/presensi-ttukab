<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\SkpLampiran;

/**
 * SkpLampiranSearch represents the model behind the search form of `app\modules\kinerja\models\SkpLampiran`.
 */
class SkpLampiranSearch extends SkpLampiran
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_skp', 'id_skp_lampiran_jenis'], 'integer'],
            [['uraian'], 'safe'],
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
        $query = SkpLampiran::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_skp' => $this->id_skp,
            'id_skp_lampiran_jenis' => $this->id_skp_lampiran_jenis,
        ]);

        $query->andFilterWhere(['like', 'uraian', $this->uraian]);

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
