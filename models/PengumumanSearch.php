<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pengumuman;

/**
 * PengumumanSearch represents the model behind the search form of `app\models\Pengumuman`.
 */
class PengumumanSearch extends Pengumuman
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_pembuat', 'user_pengubah'], 'integer'],
            [['judul', 'teks', 'status', 'tanggal_mulai', 'tanggal_selesai', 'waktu_dibuat', 'waktu_diubah'], 'safe'],
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
        $query = Pengumuman::find()
            ->aktif();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'user_pembuat' => $this->user_pembuat,
            'waktu_dibuat' => $this->waktu_dibuat,
            'user_pengubah' => $this->user_pengubah,
            'waktu_diubah' => $this->waktu_diubah,
        ]);

        $query->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'teks', $this->teks])
            ->andFilterWhere(['like', 'status', $this->status]);

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
