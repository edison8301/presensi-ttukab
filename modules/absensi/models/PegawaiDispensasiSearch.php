<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\PegawaiDispensasi;

/**
 * PegawaiDispensasiSearch represents the model behind the search form of `app\modules\absensi\models\PegawaiDispensasi`.
 */
class PegawaiDispensasiSearch extends PegawaiDispensasi
{
    public $id_instansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_hapus'], 'integer'],
            [['tanggal_mulai', 'id_pegawai', 'tanggal_akhir', 'user_pembuat', 'waktu_dibuat', 'user_pengubah', 'waktu_diubah', 'id_instansi'], 'safe'],
            [['id_pegawai_dispensasi_jenis'], 'integer']
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
        $query = PegawaiDispensasi::find()
            ->joinWith(['pegawai', 'pegawai.instansi']);

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_akhir' => $this->tanggal_akhir,
            'status_hapus' => $this->status_hapus,
            'waktu_dibuat' => $this->waktu_dibuat,
            'waktu_diubah' => $this->waktu_diubah,
            'id_pegawai_dispensasi_jenis' => $this->id_pegawai_dispensasi_jenis
        ]);

        $query
            ->andFilterWhere(['like', 'pegawai.nama', $this->id_pegawai])
            ->andFilterWhere(['like', 'user_pembuat', $this->user_pembuat])
            ->andFilterWhere(['like', 'user_pengubah', $this->user_pengubah])
            ->andFilterWhere(['like', 'instansi.nama', $this->id_instansi]);

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
