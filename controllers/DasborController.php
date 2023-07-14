<?php

namespace app\controllers;

use Yii;
use app\models\DasborKinerja;
use app\models\DasborAbsensi;
use app\models\User;
use yii\filters\VerbFilter;

class DasborController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if(User::isAdmin()) {
            return $this->redirect(['/instansi/admin']);
        }

        if(User::isInstansi()) {
            return $this->redirect(['/absensi/dasbor/instansi']);
        }

        if(User::isPegawai()) {
            return $this->redirect(['/absensi/dasbor/pegawai']);
        }
    }

    public function actionAbsensiAdmin()
    {
        $dasborAbsensi = new DasborAbsensi;

        $dasborAbsensi->load(Yii::$app->request->queryParams);

        return $this->render('absensi-admin',[
            'dasborAbsensi'=>$dasborAbsensi
        ]);

        return $this->render('absensi-admin',[
            'dasborAbsensi' => $dasborAbsensi
        ]);
    }

    public function actionKinerjaPegawai()
    {
    	$dasborKinerja = new DasborKinerja;

    	if(User::isPegawai())
		{
			$dasborKinerja->bulan == null ? $dasborKinerja->bulan = date('n') : null;
			$dasborKinerja->tanggal == null ? $dasborKinerja->tanggal = date('Y-m-d') : null;
			$dasborKinerja->id_pegawai = User::getIdPegawai();
		}

    	$dasborKinerja->load(Yii::$app->request->queryParams);

        return $this->render('kinerja-pegawai',[
        	'dasborKinerja'=>$dasborKinerja
        ]);
    }

    public function actionKinerjaAdmin()
    {
        $dasborKinerja = new DasborKinerja;

        if(User::isPegawai())
        {
            $dasborKinerja->bulan == null ? $dasborKinerja->bulan = date('n') : null;
            $dasborKinerja->tanggal == null ? $dasborKinerja->tanggal = date('Y-m-d') : null;
            $dasborKinerja->id_pegawai = User::getIdPegawai();
        }

        $dasborKinerja->load(Yii::$app->request->queryParams);

        return $this->render('kinerja-pegawai',[
            'dasborKinerja'=>$dasborKinerja
        ]);

        return $this->render('kinerja-admin',[
            'dasborKinerja' => $dasborKinerja
        ]);
    }

}
