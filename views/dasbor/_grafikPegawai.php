<?php 
  use app\models\Honor; 
?>
<script>

FusionCharts.ready(function(){
      var revenueChart = new FusionCharts({
        "type": "Column3d",
        "renderAt": "grafik-pegawai",
        "width": "100%",
        "height": "300",
        "dataFormat": "json",
        "dataSource": {
          "chart": {
              "caption" : "Grafik Jumlah Pegawai per Honor",
              "xAxisName": "Honor",
              "yAxisName": "Jumlah Pegawai",
              "theme": "fint"
           },
          "data":        
              [ <?php print Honor::getGrafikHonorPerPegawai(); ?> ]
        }
    });
    revenueChart.render();
})
		
</script> 
<div id="grafik-pegawai"> FusionChart XT will load here! </div>