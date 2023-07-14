<?php

namespace app\modules\absensi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\models\User;

/**
 * KetidakhadiranPanjangSearch represents the model behind the search form of `app\models\KetidakhadiranPanjang`.
 */
class KetidakhadiranPanjangSearch extends KetidakhadiranPanjang
{
    public $bulan;
    public $id_instansi;
    public $tanggal_mulai_awal;
    public $tanggal_mulai_akhir;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pegawai', 'id_ketidakhadiran_panjang_jenis',
                'id_ketidakhadiran_panjang_status','id_unit_kerja', 'bulan', 'id_instansi'
            ], 'integer'],
            [['tanggal_mulai', 'tanggal_selesai','nama_pegawai','id_unit_kerja',
                'keterangan'
            ], 'safe'],
            [['tanggal_mulai_awal'], 'safe'],
            [['tanggal_mulai_akhir'], 'safe'],
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
        $query = KetidakhadiranPanjang::find();
        $query->joinWith(['pegawai', 'allInstansiPegawai'])
            ->groupBy('ketidakhadiran_panjang.id');

        $this->load($params);

        if (!empty($this->id_instansi)) {
            $this->id_unit_kerja = $this->id_instansi;
        }

        if(!empty($this->bulan)) {
            $date = \DateTime::createFromFormat('Y-n-d',User::getTahun().'-'.$this->bulan.'-01');
            $tanggal_awal = $date->format('Y-m-01');
            $tanggal_akhir = $date->format('Y-m-t');
            $query->andWhere('
                (ketidakhadiran_panjang.tanggal_mulai <= :tanggal_awal AND (ketidakhadiran_panjang.tanggal_selesai >= :tanggal_awal AND ketidakhadiran_panjang.tanggal_selesai <= :tanggal_akhir)) OR
                (ketidakhadiran_panjang.tanggal_mulai >= :tanggal_awal AND ketidakhadiran_panjang.tanggal_selesai <= :tanggal_akhir) OR
                ((ketidakhadiran_panjang.tanggal_mulai >= :tanggal_awal AND ketidakhadiran_panjang.tanggal_mulai <= :tanggal_akhir) AND ketidakhadiran_panjang.tanggal_selesai >= :tanggal_akhir) OR
                (ketidakhadiran_panjang.tanggal_mulai <= :tanggal_awal AND ketidakhadiran_panjang.tanggal_selesai >= :tanggal_akhir)
                ', [
                ':tanggal_awal' => $tanggal_awal,
                ':tanggal_akhir' => $tanggal_akhir,
            ]);

            $query->andWhere('instansi_pegawai.tanggal_mulai <= :tanggal AND instansi_pegawai.tanggal_selesai >= :tanggal',[
                ':tanggal'=>$date->format('Y-m-15')
            ]);
        }

        if(User::isInstansi() || User::isAdminInstansi() || User::isOperatorAbsen()) {
            $query->andWhere(['ketidakhadiran_panjang.id_instansi'=>User::getListIdInstansi()]);
        }

        if(User::isVerifikator()) {
            $query->andWhere(['ketidakhadiran_panjang.id_instansi'=>User::getListIdInstansi()]);
        }

        if($this->tanggal_mulai_awal != null) {
            $query->andWhere('ketidakhadiran_panjang.tanggal_mulai >= :tanggal_mulai_awal',[
                ':tanggal_mulai_awal' => $this->tanggal_mulai_awal
            ]);
        }

        if($this->tanggal_mulai_akhir != null) {
            $query->andWhere('ketidakhadiran_panjang.tanggal_mulai <= :tanggal_mulai_akhir',[
                ':tanggal_mulai_akhir' => $this->tanggal_mulai_akhir
            ]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_pegawai' => $this->id_pegawai,
            'instansi_pegawai.id_instansi' => $this->id_unit_kerja,
            'id_ketidakhadiran_panjang_jenis' => $this->id_ketidakhadiran_panjang_jenis,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'id_ketidakhadiran_panjang_status' => $this->id_ketidakhadiran_panjang_status,
        ]);

        $query->andFilterWhere(['like', 'pegawai.nama', $this->nama_pegawai])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $query->orderBy(['tanggal_mulai'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize'=>10]
        ]);

        return $dataProvider;
    }


}
