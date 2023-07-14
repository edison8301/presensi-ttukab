<?php

namespace app\modules\kinerja\controllers;

use Yii;
use app\models\User;
use app\modules\kinerja\models\Dasbor;

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
            return $this->redirect(['/kinerja/dasbor/admin']);
        }

        if(User::isInstansi()) {
            return $this->redirect(['/kinerja/dasbor/pegawai']);
        }

        if(User::isPegawai()) {
            return $this->redirect(['/kinerja/dasbor/pegawai']);
        }
    }

    public function actionAdmin()
    {
        return $this->redirect(['/kinerja/pegawai/index']);  
    }

    public function actionInstansi($id_instansi=null)
    {
        
    }

    public function actionPegawai($id_pegawai=null)
    {
        $dasborKinerja = new Dasbor;

        if(User::isPegawai())
        {
            $dasborKinerja->bulan == null ? $dasborKinerja->bulan = date('n') : null;
            $dasborKinerja->tanggal == null ? $dasborKinerja->tanggal = date('Y-m-d') : null;
            $dasborKinerja->id_pegawai = User::getIdPegawai();  
        }

        $dasborKinerja->load(Yii::$app->request->queryParams);

        return $this->render('pegawai',[
            'dasborKinerja'=>$dasborKinerja
        ]);

    }

}
