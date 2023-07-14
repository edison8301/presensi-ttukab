<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JabatanEvjab;

/**
 * JabatanEvjabSearch represents the model behind the search form of `app\models\JabatanEvjab`.
 */
class JabatanEvjabSearch extends JabatanEvjab
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_jenis_jabatan', 'id_instansi', 'id_instansi_bidang', 'nilai_jabatan', 'kelas_jabatan', 'persediaan_pegawai', 'id_induk', 'status_hapus', 'id_user_hapus', 'nomor'], 'integer'],
            [['nama', 'waktu_hapus'], 'safe'],
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
        $query = JabatanEvjab::find();

        $this->load($params);

        // add conditions that should always apply here

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_jenis_jabatan' => $this->id_jenis_jabatan,
            'id_instansi' => $this->id_instansi,
            'id_instansi_bidang' => $this->id_instansi_bidang,
            'nilai_jabatan' => $this->nilai_jabatan,
            'kelas_jabatan' => $this->kelas_jabatan,
            'persediaan_pegawai' => $this->persediaan_pegawai,
            'id_induk' => $this->id_induk,
            'status_hapus' => $this->status_hapus,
            'waktu_hapus' => $this->waktu_hapus,
            'id_user_hapus' => $this->id_user_hapus,
            'nomor' => $this->nomor,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama]);

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
