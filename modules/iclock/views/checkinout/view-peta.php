<?php

use kartik\detail\DetailView;

$this->title = 'Peta';
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
                'attribute' => 'userid',
                'label' => 'Nama Pegawai',
                'format' => 'raw',
                'value' => $model->pegawai->nama,
            ],
            [
                'attribute' => 'checktime',
                'label' => 'Waktu',
                'format' => 'raw',
                'value' => $model->checktime,
            ],
        ],
    ]) ?>
    </div>
</div>

<div class="box box-primary">

    <div class="box-header with-border">
        <h2 class="box-title"><i class="fa fa-map-marker"></i></h2>
    </div>
    <div class="box-body">
        <iframe width="100%" height="500" frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?zoom=17&q=<?= $model->latitude; ?>,<?= $model->longitude; ?>&key=AIzaSyAd5Ju4I8znQiroqJEJTlrg2QD_38Ky1nY" allowfullscreen></iframe>
    </div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLbcFvQVGGMRTpHlD4WTXXorz7otiR7VA&libraries=drawing&callback=initMap"
     async defer></script>


