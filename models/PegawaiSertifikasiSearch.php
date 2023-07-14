<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PegawaiSertifikasi;

/**
 * PegawaiSertifikasiSearch represents the model behind the search form of `app\models\PegawaiSertifikasi`.
 */
class PegawaiSertifikasiSearch extends PegawaiSertifikasi
{
    public $nama_pegawai;
    public $nip_pegawai;
    public $nama_instansi;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'biaya'], 'safe'],
            [['nama_pegawai', 'nip_pegawai'], 'safe'],
            [['id_pegawai_sertifikasi_jenis', 'nama_instansi'], 'integer'],
            [['id_instansi'], 'integer'],
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
     * @return \yii\db\ActiveQuery
     */

    public function getQuerySearch($params)
    {
        $this->load($params);

        $query = PegawaiSertifikasi::find();

        if($this->nama_pegawai !== null) {
            $query->joinWith(['pegawai']);
            $query->andWhere(['like','pegawai.nama',$this->nama_pegawai]);
        }

        if($this->nip_pegawai !== null) {
            $query->joinWith(['pegawai']);
            $query->andWhere(['like','pegawai.nip',$this->nip_pegawai]);
        }

        $query->andFilterWhere([
            'pegawai_sertifikasi.id' => $this->id,
            'pegawai_sertifikasi.id_pegawai' => $this->id_pegawai,
            'pegawai_sertifikasi.id_instansi' => $this->id_instansi,
            'pegawai_sertifikasi.tanggal_mulai' => $this->tanggal_mulai,
            'pegawai_sertifikasi.tanggal_selesai' => $this->tanggal_selesai,
            'pegawai_sertifikasi.id_pegawai_sertifikasi_jenis' => $this->id_pegawai_sertifikasi_jenis
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
