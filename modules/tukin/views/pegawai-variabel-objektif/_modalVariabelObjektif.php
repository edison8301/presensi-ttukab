<?php

use app\modules\tukin\models\RefVariabelObjektif;
use app\modules\tukin\models\PegawaiVariabelObjektif;
use app\modules\tukin\models\RefVariabelObjektifSearch;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/** @var yii\web\View $this */
/** @var PegawaiVariabelObjektif $model */

$searchModel = new RefVariabelObjektifSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
?>
<?php Modal::begin([
    'header' => '<h2>Daftar Variabel Objektif</h2>',
    'toggleButton' => [
        'label' => '<i class="fa fa-folder-open"></i> Variabel Objektif',
        'class' => 'btn btn-success btn-flat'
    ],
    'size' => 'modal-lg',
]); ?>
<?php Pjax::begin(['timeout' => 10000]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => [
        'id' => 'refmodal',
    ],
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => 'No',
            'headerOptions' => ['style' => 'text-align:center'],
            'contentOptions' => ['style' => 'text-align:center']
        ],

        [
            'attribute' => 'kode',
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'kelompok',
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'uraian',
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'satuan',
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'tarif',
            'format' => 'integer',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],

        [
            'class' => 'app\components\ToggleActionColumn',
            'template' => '{create}',
            'buttons' => [
                'create' => function ($url, $row, $key) use ($model) {
                    $id = Html::getInputId($model, 'id_ref_variabel_objektif');
                    $vId = Html::getInputId($model, 'variabel_objektif');
                    $tarif = Html::getInputId($model, 'tarif');
                    return Html::a(
                        '<i class="fa fa-plus"></i>',
                        '#',
                        [
                            'onclick' => new JsExpression("
                                $('#$id').val(\"$row->id\");
                                $('#$vId').val(\"$row->uraian\");
                                $('#$tarif').val(\"$row->tarif\");
                                console.log('test');
                            "),
                            'data-dismiss' => 'modal'
                        ]
                    );
                }
            ],
            'headerOptions'=>['style'=>'text-align:center;width:80px'],
            'contentOptions'=>['style'=>'text-align:center']
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
<?php Modal::end();?>
