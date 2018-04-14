<html>
<script type="text/javascript">
    document.write("\<script src='http://code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
</script>
<script src="https://code.highcharts.com/highcharts.js"></script>

</html>
<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 12/4/18
 * Time: 12:44 PM
 */

//include("fusioncharts.php");
//
//// Syntax for the constructor -
//// new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
//$columnChart = new FusionCharts("column2d", "ex1" , 600, 400, "chart-1", "json", '{ "chart":{
//			  "caption":"Harrys SuperMart",
//			  "subCaption":"Top 5 stores in last month by revenue",
//			  "numberPrefix":"$",
//			  "theme":"ocean"
//		   },
//		   "data":[
//			  {
//				 "label":"Bakersfield Central",
//				 "value":"880000"
//			  },
//			  {
//				 "label":"Garden Groove harbour",
//				 "value":"730000"
//			  },
//			  {
//				 "label":"Los Angeles Topanga",
//				 "value":"590000"
//			  },
//			  {
//				 "label":"Compton-Rancho Dom",
//				 "value":"520000"
//			  },
//			  {
//				 "label":"Daly City Serramonte",
//				 "value":"330000"
//			  }
//		   ]
//		}');
//
//// Render the chart
//$columnChart->render();

//   $data[0]['date'] = 1;
//   $data[0]['value'] = 10;
//   $data[1]['date'] = 2;
//   $data[1]['value'] = 20;
//   $data[2]['date'] = 3;
//   $data[2]['value'] = -10;

$date = [1,2,3];
$value = [10,20,-10];
$data = "[$date, $value]";
?>
<div id="container" style="width:100%; height:400px;"></div>
<div id="container2" style="width:100%; height:800px;"></div>
<script>
    $(function () {
        var myChart = Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Fruit Consumption'
            },
            xAxis: {
                categories: ['Apples', 'Bananas', 'Oranges']
            },
            yAxis: {
                title: {
                    text: 'Fruit eaten'
                }
            },
            series: [{
                name: 'Jane',
                data: [1, 0, 4]
            }, {
                name: 'John',
                data: [5, 7, 3]
            }]
        });
    });


    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container2'
        },
        xAxis: {
            date: [<?php echo join($date, ',') ?>]
        },
        series: [{
            name: 'X',
            data: [<?php echo join($value, ',') ?>]
        }]
    });
</script>
