<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SkpNilai;

/**
 * SkpNilaiSearch represents the model behind the search form of `app\models\SkpNilai`.
 */
class SkpNilaiSearch extends SkpNilai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi_pegawai_skp', 'id_skp_periode', 'periode','nilai_hasil_kerja','nilai_perilaku_kerja'], 'integer'],
            [['feedback_hasil_kerja', 'feedback_perilaku_kerja'], 'string']
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
        $query = SkpNilai::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi_pegawai_skp' => $this->id_instansi_pegawai_skp,
            'id_skp_periode' => $this->id_skp_periode,
            'periode' => $this->periode,
            'feedback_hasil_kerja' => $this->feedback_hasil_kerja,
            'nilai_hasil_kerja' => $this->nilai_hasil_kerja,
            'feedback_perilaku_kerja' => $this->feedback_perilaku_kerja,
            'nilai_perilaku_kerja' => $this->nilai_perilaku_kerja,
        ]);

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
