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
 * Time: 10:19 AM
 */

date_default_timezone_set ('UTC');
include_once __DIR__.'/../ccxt.php';
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/classes/Utility.php');
use MathPHP\Statistics\Average;

$exchange = new \ccxt\binanceSam();


$markets = $exchange->fetch_markets_on_quote('btc');

//$start_range_timestamp_string = strtotime('2018-04-09 11:30:00').'000';
//foreach ($markets as $market){
//    usleep ($exchange->rateLimit * 1000); // usleep wants microseconds
//    $candlestick_data = $exchange->fetch_ohlcv ($market['symbol'], '1h');
////    $candlestick_data = $exchange->fetch_ohlcv ($market['symbol'], '1h',$start_range_timestamp_string);
//    echo "h";
//    exit;
//}

$duration = $_GET['duration'];
//print_r($_GET);
//exit;

//$duration = '2h';

$start_date = strtotime('2017-06-30 00:00:00').'000';

//$ethbtc = \ccxt\binance\Utility::fetchRangeOHLCVbinance($exchange,'1h','ETH/BTC',$start_date);
$eosbtc = \ccxt\binance\Utility::fetchRangeOHLCVbinance($exchange,$duration,'EOS/BTC',$start_date);


$eosbtc20 = \ccxt\binance\Utility::exponentialMovingAverageKeys($eosbtc['closes_index'], 20);
$eosbtc8 = \ccxt\binance\Utility::exponentialMovingAverageKeys($eosbtc['closes_index'], 8);

$eos_btc_20_values = array();
$eos_btc_8_values = array();
$difference = array();
for($i = 0; $i < count($eosbtc8); $i++){
    $difference[$i]['value'] = $eosbtc8[$i]['value'] - $eosbtc20[$i]['value'] ;
    $difference[$i]['timestamp'] = $eosbtc8[$i]['timestamp'];

    $eos_btc_20_values[] = $eosbtc20[$i]['value'];
    $eos_btc_8_values[] = $eosbtc8[$i]['value'];
}
//echo "<pre>";
//print_r($difference);
//
//echo "done";
$date_data = array();
$value_data = array();
$i_counter = 0;
foreach($difference as $data){
    $date_data[] = strval(date("Y-m-d H:i:s",$data['timestamp']/1000));
    $value_data[] = $data['value'];
//    $i_counter++;
//    if($i_counter == 10)
//        break;
}

$date = [1,2,3];
$value = [10,20,-10];
$data = "[$date, $value]";
?>
<div id="container" style="width:100%; height:400px;"></div>
<div id="container2" style="width:100%; height:800px;"></div>
<!--<div id="patientFrequency" style="width:100%; height:800px;"></div>-->

<!--<div id="container2" style="width:100%; height:800px;"></div>-->
<script>
//    $(function () {
//        var myChart = Highcharts.chart('container', {
//            chart: {
//                type: 'line'
//            },
//            title: {
//                text: 'Fruit Consumption'
//            },
//            xAxis: {
//                categories: ['Apples', 'Bananas', 'Oranges']
//            },
//            yAxis: {
//                title: {
//                    text: 'Fruit eaten'
//                }
//            },
//            series: [{
//                name: 'Jane',
//                data: [1, 0, 4]
//            }, {
//                name: 'John',
//                data: [5, 7, 3]
//            }]
//        });
//    });

    var date_array = [<?php $data_req =  join($date_data, "','"); $data_req = "'".$data_req."'"; echo $data_req;   ?>];
    console.log(date_array);
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            zoomType: 'x'
        },

        xAxis: {
            type: 'datetime',
            title: {
                text: 'days'
            },
            categories: [<?php echo $data_req  ?>],
            min: 0
        },
        series: [
//            {
//            name: 'data',
//            data: [<?php //echo join($value_data, ',') ?>//]
//        },
            {
            name: 'eos20',
            data: [<?php echo join($eos_btc_20_values, ',') ?>]
        }, {
            name: 'eos8',
            data: [<?php echo join($eos_btc_8_values, ',') ?>]
        }]
    });

    var chart2 = new Highcharts.Chart({
        chart: {
            renderTo: 'container2',
            zoomType: 'x'
        },

        xAxis: {
            type: 'datetime',
            title: {
                text: 'days'
            },
            categories: [<?php echo $data_req  ?>],
            min: 0
        },
        series: [{
            name: 'data',
            data: [<?php echo join($value_data, ',') ?>]
        }]
    });

//$(function() {
//    var chartdata = [
//        ['2017-06-30 00:00:00'],
//        ['2017-06-30 01:00:00'],
//        ['2017-06-30 02:00:00']
//    ];
//    new Highcharts.Chart({
//        chart: {
//            type: 'line',
//            renderTo: document.getElementById('patientFrequency')
//        },
//        title: {
//            text: 'Patient frequency'
//        },
//        xAxis: {
//            type: 'datetime',
//            title: {
//                text: 'days'
//            }
//        },
//        series: [{
//            data: chartdata
//        }]
//    });
//});
</script>