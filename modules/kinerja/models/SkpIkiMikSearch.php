<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\SkpIkiMik;

/**
 * SkpIkiMikSearch represents the model behind the search form of `app\modules\kinerja\models\SkpIkiMik`.
 */
class SkpIkiMikSearch extends SkpIkiMik
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_skp', 'id_skp_iki'], 'integer'],
            [['tujuan', 'definisi', 'formula', 'satuan_pengukuran', 'kualitas_tingkat_kendali', 'sumber_data', 'periode_pelaporan'], 'safe'],
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
        $query = SkpIkiMik::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_skp' => $this->id_skp,
            'id_skp_iki' => $this->id_skp_iki,
        ]);

        $query->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'definisi', $this->definisi])
            ->andFilterWhere(['like', 'formula', $this->formula])
            ->andFilterWhere(['like', 'satuan_pengukuran', $this->satuan_pengukuran])
            ->andFilterWhere(['like', 'kualitas_tingkat_kendali', $this->kualitas_tingkat_kendali])
            ->andFilterWhere(['like', 'sumber_data', $this->sumber_data])
            ->andFilterWhere(['like', 'periode_pelaporan', $this->periode_pelaporan]);

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
