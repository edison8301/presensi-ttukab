<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\KetidakhadiranKegiatan;
use app\models\User;

/**
 * KetidakhadiranKegiatanSearch represents the model behind the search form of `app\models\KetidakhadiranKegiatan`.
 */
class KetidakhadiranKegiatanSearch extends KetidakhadiranKegiatan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_ketidakhadiran_kegiatan_jenis',
                'id_ketidakhadiran_kegiatan_keterangan','id_ketidakhadiran_kegiatan_status', 'bulan'
            ], 'integer'],
            [['tanggal', 'keterangan','nama_pegawai','nama_instansi'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function getQuerySearch($params)
    {
        $this->load($params);

        $query = KetidakhadiranKegiatan::find();

        $query->joinWith(['pegawai.allInstansiPegawai'])
            ->groupBy('ketidakhadiran_kegiatan.id');

        if(User::isInstansi() || User::isAdminInstansi()) {
            if (empty($this->id_instansi)) {
                $query->andWhere(['in', 'instansi_pegawai.id_instansi', User::getListIdInstansi()]);
            } elseif (!empty($this->id_instansi) && in_array($this->id_instansi, User::getListIdInstansi(), false)) {
                $query->andWhere(['instansi_pegawai.id_instansi' => $this->id_instansi]);
            }
            $date = \DateTime::createFromFormat('Y-n-d',User::getTahun().'-'.$this->bulan.'-01');
            $query->andWhere('instansi_pegawai.tanggal_mulai <= :tanggal AND instansi_pegawai.tanggal_selesai >= :tanggal',[
                ':tanggal'=>$date->format('Y-m-15')
            ]);
        }

        if(User::isVerifikator()) {
            $query->andWhere(['instansi_pegawai.id_instansi'=>User::getListIdInstansi()]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'id_ketidakhadiran_kegiatan_jenis' => $this->id_ketidakhadiran_kegiatan_jenis,
            'id_ketidakhadiran_kegiatan_status' => $this->id_ketidakhadiran_kegiatan_status,
            'id_ketidakhadiran_kegiatan_keterangan' => $this->id_ketidakhadiran_kegiatan_keterangan,
        ]);

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai]);
        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);
        $query->andFilterWhere(['like', 'tanggal', $this->tanggal]);

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
