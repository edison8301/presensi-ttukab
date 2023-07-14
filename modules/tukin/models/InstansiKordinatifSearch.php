<?php

namespace app\modules\tukin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tukin\models\InstansiKordinatif;

/**
 * InstansiKordinatifSearch represents the model behind the search form of `app\modules\tukin\models\InstansiKordinatif`.
 */
class InstansiKordinatifSearch extends InstansiKordinatif
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi'], 'integer'],
            [['tanggal_berlaku_mulai', 'tanggal_berlaku_selesai'], 'safe'],
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
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function getQuerySearch($params)
    {
        $query = InstansiKordinatif::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'tanggal_berlaku_mulai' => $this->tanggal_berlaku_mulai,
            'tanggal_berlaku_selesai' => $this->tanggal_berlaku_selesai,
        ]);

        return $query;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->getQuerySearch($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
