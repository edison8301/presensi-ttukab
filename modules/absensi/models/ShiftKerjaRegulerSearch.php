<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\ShiftKerjaReguler;

/**
 * ShiftKerjaRegulerSearch represents the model behind the search form of `app\modules\absensi\models\ShiftKerjaReguler`.
 */
class ShiftKerjaRegulerSearch extends ShiftKerjaReguler
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_shift_kerja'], 'integer'],
            [['nama', 'tanggal_mulai', 'tanggal_selesai', 'status_hapus', 'waktu_dihapus'], 'safe'],
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
        $query = ShiftKerjaReguler::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_shift_kerja' => $this->id_shift_kerja,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'waktu_dihapus' => $this->waktu_dihapus,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'status_hapus', $this->status_hapus]);

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
