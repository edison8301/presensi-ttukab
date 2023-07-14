<?php

namespace app\modules\kinerja\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kinerja\models\PegawaiUbah;

/**
 * PegawaiUbahSearch represents the model behind the search form of `app\modules\kinerja\models\PegawaiUbah`.
 */
class PegawaiUbahSearch extends PegawaiUbah
{
    public $nama_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_pegawai_ubah_jenis'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai'], 'safe'],
            [['nama_pegawai'], 'safe'],
            [['keterangan'], 'safe'],
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
     * @return PegawaiUbahQuery
     */

    public function getQuerySearch($params)
    {
        $query = PegawaiUbah::find();

        $this->load($params);

        $query->joinWith(['pegawai']);
        $query->andFilterWhere([
            'like','pegawai.nama',$this->nama_pegawai
        ]);

        $query->andFilterWhere([
            'id_pegawai' => $this->id_pegawai,
            'id_pegawai_ubah_jenis' => $this->id_pegawai_ubah_jenis,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
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
