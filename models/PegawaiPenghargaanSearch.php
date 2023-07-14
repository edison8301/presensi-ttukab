<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PegawaiPenghargaan;

/**
 * PegawaiPenghargaanSearch represents the model behind the search form of `app\models\PegawaiPenghargaan`.
 */
class PegawaiPenghargaanSearch extends PegawaiPenghargaan
{
    public $nama_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_pegawai_penghargaan_status', 'id_pegawai_penghargaan_tingkat'], 'integer'],
            [['nama', 'tanggal'], 'safe'],
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
        $query = PegawaiPenghargaan::find();
        $query->joinWith(['pegawai']);

        $this->load($params);

        // add conditions that should always apply here
        if (Session::isInstansi() OR Session::isAdminInstansi()) {
            $query->joinWith(['pegawai.instansiPegawai']);
            $query->andWhere(['instansi_pegawai.id_instansi' => Session::getIdInstansi()]);
        }

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'tanggal' => $this->tanggal,
            'id_pegawai_penghargaan_status' => $this->id_pegawai_penghargaan_status,
            'id_pegawai_penghargaan_tingkat' => $this->id_pegawai_penghargaan_tingkat,
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
