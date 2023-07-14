<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\KegiatanBulananBreakdown;

/**
 * KegiatanBulananBreakdownSearch represents the model behind the search form about `app\modules\absensi\models\KegiatanBulananBreakdown`.
 */
class KegiatanBulananBreakdownSearch extends KegiatanBulananBreakdown
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_kegiatan_tahunan_detil', 'kuantitas', 'id_satuan_kuantitas', 'penilaian_kualitas'], 'integer'],
            [['kegiatan'], 'safe'],
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
        $query = KegiatanBulananBreakdown::find();

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
            'id_kegiatan_tahunan_detil' => $this->id_kegiatan_tahunan_detil,
            'kuantitas' => $this->kuantitas,
            'id_satuan_kuantitas' => $this->id_satuan_kuantitas,
            'penilaian_kualitas' => $this->penilaian_kualitas,
        ]);

        $query->andFilterWhere(['like', 'kegiatan', $this->kegiatan]);

        return $dataProvider;
    }
}
