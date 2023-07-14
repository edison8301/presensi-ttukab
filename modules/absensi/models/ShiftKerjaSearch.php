<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\ShiftKerja;

/**
 * ShiftKerjaSearch represents the model behind the search form about `app\modules\absensi\models\ShiftKerja`.
 */
class ShiftKerjaSearch extends ShiftKerja
{
    public $status_double_shift;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nama'], 'safe'],
            [['status_libur_nasional', 'status_double_shift'], 'boolean'],
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
        $query = ShiftKerja::find()->aktif();

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
            'status_libur_nasional' => $this->status_libur_nasional,
        ]);

        if ((int) $this->status_double_shift === 1) {
            $query->andWhere(['hari_kerja' => 2]);
        }

        $query->andFilterWhere(['like', 'nama', $this->nama]);

        return $dataProvider;
    }
}
