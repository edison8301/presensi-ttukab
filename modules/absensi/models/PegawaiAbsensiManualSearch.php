<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\PegawaiAbsensiManual;

/**
 * PegawaiAbsensiManualSearch represents the model behind the search form of `app\modules\absensi\models\PegawaiAbsensiManual`.
 */
class PegawaiAbsensiManualSearch extends PegawaiAbsensiManual
{
    public $nama_pegawai;
    public $nip_pegawai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'status_hapus', 'id_user_hapus'], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai', 'waktu_hapus'], 'safe'],
            [['status', 'nama_pegawai', 'nip_pegawai'], 'safe'],
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
        $query = PegawaiAbsensiManual::find();
        $query->joinWith(['pegawai']);

        $this->load($params);

        // add conditions that should always apply here
        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);
        $query->andFilterWhere(['like', 'pegawai.nip', $this->nip_pegawai]);

        // grid filtering conditions
        $query->andFilterWhere([
            'pegawai_absensi_manual.id' => $this->id,
            'pegawai_absensi_manual.id_pegawai' => $this->id_pegawai,
            'pegawai_absensi_manual.tanggal_mulai' => $this->tanggal_mulai,
            'pegawai_absensi_manual.tanggal_selesai' => $this->tanggal_selesai,
            'pegawai_absensi_manual.status_hapus' => $this->status_hapus,
            'pegawai_absensi_manual.waktu_hapus' => $this->waktu_hapus,
            'pegawai_absensi_manual.id_user_hapus' => $this->id_user_hapus,
            'pegawai_absensi_manual.status' => $this->status,
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
