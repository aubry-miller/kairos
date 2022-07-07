<?php
     
     $dataPoints = array( 
         array("label"=>"Occupé", "y"=>60),
         array("label"=>"Libre", "y"=>40)
     )
      
     ?>
     <!DOCTYPE HTML>
     <html>
     <head>
     <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
     <script>
     window.onload = function() {
      
      
     var chart = new CanvasJS.Chart("chartContainer", {
         animationEnabled: true,
         title: {
             text: "Usage Share of Desktop Browsers"
         },
         subtitles: [{
             text: "November 2017"
         }],
         data: [{
             type: "pie",
             yValueFormatString: "#,##0.00\"%\"",
             indexLabel: "{label} ({y})",
             dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
         }]
     });
     chart.render();
      
     }
     </script>
     </head>
     <body>
     <div id="chartContainer" style="height: 150px; width: 100%;"></div>
     
     </body>
     </html>                              