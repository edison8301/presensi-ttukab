<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Artikel;

/**
 * ArtikelSearch represents the model behind the search form of `app\models\Artikel`.
 */
class ArtikelSearch extends Artikel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user_buat', 'id_user_ubah', 'id_artikel_kategori', 'waktu_buat', 'waktu_ubah'], 'integer'],
            [['judul', 'slug', 'konten', 'waktu_terbit', 'thumbnail'], 'safe'],
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
        $query = Artikel::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_user_buat' => $this->id_user_buat,
            'id_user_ubah' => $this->id_user_ubah,
            'id_artikel_kategori' => $this->id_artikel_kategori,
            'waktu_buat' => $this->waktu_buat,
            'waktu_ubah' => $this->waktu_ubah,
            'waktu_terbit' => $this->waktu_terbit,
        ]);

        $query->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'konten', $this->konten])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail]);

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