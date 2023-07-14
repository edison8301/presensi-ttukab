<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\JamKerja;

/**
 * JamKerjaSearch represents the model behind the search form about `app\modules\absensi\models\JamKerja`.
 */
class JamKerjaSearch extends JamKerja
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_shift_kerja', 'hari', 'jenis', 'jam_selesai_normal', 'status_wajib'], 'integer'],
            [['nama', 'jam_mulai_pindai', 'jam_selesai_pindai', 'jam_mulai_normal'], 'safe'],
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
        $query = JamKerja::find();

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
            'id_shift_kerja' => $this->id_shift_kerja,
            'hari' => $this->hari,
            'jenis' => $this->jenis,
            'jam_selesai_normal' => $this->jam_selesai_normal,
            'status_wajib' => $this->status_wajib,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'jam_mulai_pindai', $this->jam_mulai_pindai])
            ->andFilterWhere(['like', 'jam_selesai_pindai', $this->jam_selesai_pindai])
            ->andFilterWhere(['like', 'jam_mulai_normal', $this->jam_mulai_normal]);

        return $dataProvider;
    }
}
