<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use Location\Coordinate;
use Location\Polygon;


/* @var $this yii\web\View */
/* @var $model app\models\Peta */

$this->title = "Detail Peta";
$this->params['breadcrumbs'][] = ['label' => 'Peta', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peta-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Peta</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => $model->keterangan,
            ],
        ],
    ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Peta', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Peta', [
            '/peta/index',
            'mode' => Yii::$app->request->get('mode')
        ], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>
</div>

<?php if($model->id_peta_jenis == 2) { ?>
    <div class="peta-view box box-primary">

      <div class="box-header">
          <h3 class="box-title">Detail Peta</h3>
      </div>

      <div class="box-body">
          <?= Html::a('<i class="fa fa-plus"></i> Tambah Titik Point',['peta-point/create','id_peta' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
          <div>&nbsp;</div>
          <table class="table table-bordered">
              <tr>
                  <th class="text-center" style="width: 10px">No</th>
                  <th class="text-center">Latitude</th>
                  <th class="text-center">Longitude</th>
                  <th class="text-center" style="width: 90px">&nbsp;</th>
              </tr>
              <?php $no=1; foreach ($model->findAllPetaPoint() as $petaPoint) { ?>
              <tr>
                  <td class="text-center"><?= $no++; ?></td>
                  <td class="text-center"><?= $petaPoint->latitude; ?></td>
                  <td class="text-center"><?= $petaPoint->longitude; ?></td>
                  <td class="text-center">
                      <?= Html::a("<i class='fa fa-pencil'></i>",['peta-point/update','id' => $petaPoint->id]); ?>
                      <?= Html::a("<i class='fa fa-trash'></i>",['peta-point/delete','id' => $petaPoint->id],['data-method' => 'post','data-confirm' => 'Apa anda yakin untuk menghapus data ini?']); ?>
                  </td>
              </tr>
              <?php } ?>
          </table>
      </div>
  </div>

  <div class="box box-primary">
      <div class="box-header with-border">
          <h2 class="box-title"><i class="fa fa-map-marker"></i> Detail Peta</h2>
      </div>
      <div class="box-body" id="peta">
          <div id="map" style="height: 400px; width: 100%;"></div>
      </div>
  </div>  
<?php } else { ?>
    <div class="box box-primary">

    <div class="box-header with-border">
        <h2 class="box-title"><i class="fa fa-map-marker"></i></h2>
    </div>
    <div class="box-body">
        <iframe width="100%" height="500" frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?zoom=17&q=<?= $model->latitude; ?>,<?= $model->longitude; ?>&key=AIzaSyAd5Ju4I8znQiroqJEJTlrg2QD_38Ky1nY" allowfullscreen></iframe>
    </div>
</div>
<?php } ?>

<script>
  // This example requires the Drawing library. Include the libraries=drawing
  // parameter when you first load the API. For example:
  // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">

  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: <?= @$petaPoint->latitude; ?>, lng: <?= @$petaPoint->longitude; ?>},
      zoom: 9.5
    });

    var triangleCoords = [<?= $model->getJsonPetaPoint(); ?>];

    var defaultPolygon = new google.maps.Polygon({
      paths: triangleCoords,
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35
    });    

    var drawingManager = new google.maps.drawing.DrawingManager({
      drawingMode: google.maps.drawing.OverlayType.POLYGON,
      drawingControl: true,
      drawingControlOptions: {
        position: google.maps.ControlPosition.TOP_CENTER,
        drawingModes: ['polygon']
      },
      markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
      circleOptions: {
        fillColor: '#FF0000',
        fillOpacity: 1,
        strokeWeight: 5,
        clickable: false,
        editable: true,
        zIndex: 1
      }
    });

    defaultPolygon.setMap(map);
    drawingManager.setMap(map);
    
    google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
        var coordinates = (polygon.getPath().getArray());
        $.post(
            "<?= Yii::$app->urlManager->createUrl(['/peta/set-peta-point','id' => $model->id]) ?>",
            {koordinat: coordinates.toString()}).done(function(data) {
                console.log(data.toString());
            })
    });        
  }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLbcFvQVGGMRTpHlD4WTXXorz7otiR7VA&libraries=drawing&callback=initMap"
     async defer></script>


