<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\ExportPdf;

/**
 * ExportPdfSearch represents the model behind the search form of `app\modules\absensi\models\ExportPdf`.
 */
class ExportPdfSearch extends ExportPdf
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'bulan'], 'integer'],
            [['tahun', 'hash'], 'safe'],
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
    public function search($params)
    {
        $query = ExportPdf::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);

        $query->andFilterWhere(['like', 'hash', $this->hash]);

        return $dataProvider;
    }
}
