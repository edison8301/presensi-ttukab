<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PegawaiTundaBayar;

/**
 * PegawaiTundaBayarSearch represents the model behind the search form of `app\models\PegawaiTundaBayar`.
 */
class PegawaiTundaBayarSearch extends PegawaiTundaBayar
{
    public $nama_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_pegawai_tunda_bayar_jenis'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama_pegawai'], 'safe'],
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
        $query = PegawaiTundaBayar::find();
        $query->joinWith(['pegawai']);

        $this->load($params);

        // add conditions that should always apply here
        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'id_pegawai_tunda_bayar_jenis' => $this->id_pegawai_tunda_bayar_jenis,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
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
