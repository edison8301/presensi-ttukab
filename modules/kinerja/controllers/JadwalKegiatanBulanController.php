<?php

namespace app\modules\kinerja\controllers;

use app\components\Helper;
use app\models\User;
use app\modules\kinerja\models\JadwalKegiatanBulan;
use app\modules\kinerja\models\JadwalKegiatanBulanSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * JadwalKegiatanBulanController implements the CRUD actions for JadwalKegiatanBulan model.
 */
class JadwalKegiatanBulanController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'editable-update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isAdmin();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ]
            ]
        ];
    }

    /**
     * Lists all JadwalKegiatanBulan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new \app\modules\kinerja\models\JadwalKegiatanBulanIndex();

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * Finds the JadwalKegiatanBulan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JadwalKegiatanBulan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JadwalKegiatanBulan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionEditableUpdate()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                $model->save();

                return Json::encode(['output' => Helper::getTanggal($model->tanggal), 'message' => '']);
            }
        }
        throw new NotFoundHttpException('Halaman yang anda cari tidak ditemukan.');
    }
}
