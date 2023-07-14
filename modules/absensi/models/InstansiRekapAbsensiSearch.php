<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\InstansiRekapAbsensi;
use app\models\User;

/**
 * InstansiRekapAbsensiSearch represents the model behind the search form of `app\models\InstansiRekapAbsensi`.
 */
class InstansiRekapAbsensiSearch extends InstansiRekapAbsensi
{
    public $status_aktif;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_instansi', 'bulan'], 'integer'],
            [['tahun', 'waktu_diperbarui','nama_instansi'], 'safe'],
            [['persen_potongan_total', 'persen_potongan_fingerprint', 'persen_potongan_kegiatan'], 'number'],
            [['persen_hadir','persen_tidak_hadir','persen_tanpa_keterangan'], 'safe'],
            [['status_aktif'], 'safe'],
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

        $query = InstansiRekapAbsensi::find();
        $query->joinWith(['instansi']);

        $this->tahun = User::getTahun();

        if($this->bulan==null) {
            $this->bulan=1;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_instansi' => $this->id_instansi,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'persen_potongan_total' => $this->persen_potongan_total,
            'persen_potongan_fingerprint' => $this->persen_potongan_fingerprint,
            'persen_potongan_kegiatan' => $this->persen_potongan_kegiatan,
            'waktu_diperbarui' => $this->waktu_diperbarui,
            'instansi.status_aktif' => $this->status_aktif,
        ]);

        $query->andFilterWhere(['like', 'instansi.nama', $this->nama_instansi]);

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
