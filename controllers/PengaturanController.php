<?php

namespace app\controllers;

use Yii;
use app\models\Pengaturan;
use app\models\PengaturanSearch;
use app\models\User;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PengaturanController implements the CRUD actions for Pengaturan model.
 */
class PengaturanController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return User::isAdmin();
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Pengaturan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $allPengaturan = Pengaturan::find()->all();

        return $this->render('index', [
            'allPengaturan' => $allPengaturan,
        ]);
    }

    /**
     * Finds the Pengaturan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pengaturan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pengaturan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEditableUpdate()
    {
        if (Yii::$app->request->post('hasEditable')) {

            $id = Yii::$app->request->post('editableKey');
            $model = $this->findModel($id);

            $posted = Yii::$app->request->post();
            $post = ['Pengaturan' => $posted];

            if ($model->load($post)) {
                if ($model->save())
                    $out = Json::encode(['output'=> $model->getDisplayNilai(), 'message'=>'']);
                else
                    $out = Json::encode(['output'=>$model->getDisplayNilai(), 'message'=>'Internal Server Error']);
            } else {
                $out = Json::encode(['output'=>$model->getDisplayNilai(), 'message'=>'']);
            }

            return $out;
        }

        return Json::encode(['output'=>'', 'message'=>'Internal Server Error']);
    }

}
