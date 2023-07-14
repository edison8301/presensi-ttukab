<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\InstansiAbsensiManual;

/**
 * InstansiAbsensiManualSearch represents the model behind the search form of `app\modules\absensi\models\InstansiAbsensiManual`.
 */
class InstansiAbsensiManualSearch extends InstansiAbsensiManual
{
    public $nama_instansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'status_hapus', 'id_user_hapus'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'waktu_hapus'], 'safe'],
            [['nama_instansi'], 'safe'],
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
        $query = InstansiAbsensiManual::find();
        $query->joinWith(['instansi']);

        $this->load($params);

        // add conditions that should always apply here
        $query->andFilterWhere(['like', 'instansi.nama', $this->nama_instansi]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'status_hapus' => $this->status_hapus,
            'waktu_hapus' => $this->waktu_hapus,
            'id_user_hapus' => $this->id_user_hapus,
        ]);

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
