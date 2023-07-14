<?php

namespace app\models;

use app\components\Session;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PegawaiRb;

/**
 * PegawaiRbSearch represents the model behind the search form of `app\models\PegawaiRb`.
 */
class PegawaiRbSearch extends PegawaiRb
{
    public $nama_pegawai;
    public $id_instansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_pegawai_rb_jenis', 'status_realisasi'], 'integer'],
            [['tahun', 'tanggal'], 'safe'],
            [['nama_pegawai', 'id_instansi'], 'safe'],
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
        $this->load($params);

        $query = PegawaiRb::find();
        $query->joinWith(['pegawai']);

        if (Session::isInstansi()) {
            $this->id_instansi = Session::getIdInstansi();
        }

        if ($this->id_instansi != null) {
            $query->joinWith(['manyInstansiPegawai']);
            $query->andWhere(['instansi_pegawai.id_instansi' => $this->id_instansi]);
            $query->andWhere('instansi_pegawai.tanggal_mulai <= :tanggal AND instansi_pegawai.tanggal_selesai >= :tanggal', [
                ':tanggal' => date('Y-m-d'),
            ]);
        }

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tahun' => $this->tahun,
            'tanggal' => $this->tanggal,
            'id_pegawai' => $this->id_pegawai,
            'id_pegawai_rb_jenis' => $this->id_pegawai_rb_jenis,
            'status_realisasi' => $this->status_realisasi,
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
