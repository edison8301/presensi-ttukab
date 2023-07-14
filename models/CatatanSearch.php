<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Catatan;

/**
 * CatatanSearch represents the model behind the search form of `app\models\Catatan`.
 */
class CatatanSearch extends Catatan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_kegiatan_tahunan', 'id_induk', 'id_user'], 'integer'],
            [['catatan', 'waktu_buat'], 'safe'],
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
        $query = Catatan::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_kegiatan_tahunan' => $this->id_kegiatan_tahunan,
            'id_induk' => $this->id_induk,
            'id_user' => $this->id_user,
            'waktu_buat' => $this->waktu_buat,
        ]);

        $query->andFilterWhere(['like', 'catatan', $this->catatan]);

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
