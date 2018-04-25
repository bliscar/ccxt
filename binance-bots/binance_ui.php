
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
 * Date: 23/4/18
 * Time: 3:04 PM
 */

date_default_timezone_set ('UTC');
include_once __DIR__.'/../ccxt.php';
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/classes/Utility.php');
use MathPHP\Statistics\Average;

$exchange = new \ccxt\binanceSam();

# Dushyant

$exchange->apiKey = '0zJtyUB3I1RCYDKrpqZGr7hyMNHBM29DCnir51pUph9r4sPrJjxuY9yBxnEOT8Dq';
$exchange->secret = 'jcs7PHcuSd5RGGqp8B13zyumjltZxHNtj5cNqqv5RTzGYaUkHxHgx5TP6i5oWRS5';

# Anurag

//$exchange->apiKey = 'NQd4eVFwTTXAiactc4IA2X9PplxRib7TluB4GL2FTs6O2srBJTYmC3ysLSNiO4rO';
//$exchange->secret = '7Mg8eikYTr8AtUc1DFgVi1aJtjZO26bXXqCuQL71nJ849cpgxzMFfzPb8fppFoFA';

$balance = $exchange->fetch_balance ();
//exit;
$open_orders_1 = $exchange->fetch_open_orders('GAS/BTC');
$open_orders_2 = $exchange->fetch_open_orders('KNC/BTC');
$open_orders_3 = $exchange->fetch_open_orders('NAV/BTC');
$open_orders_4 = $exchange->fetch_open_orders('OST/BTC');
$open_orders_5 = $exchange->fetch_open_orders('BCPT/BTC');
$open_orders_6 = $exchange->fetch_open_orders('WINGS/BTC');
$open_orders_7 = $exchange->fetch_open_orders('HSR/BTC');
//echo "asd";
//exit;
//$create_order = $exchange->create_limit_buy_order('GAS/BTC',10,0.003011);
//$create_order = $exchange->create_limit_buy_order('KNC/BTC',505,0.00019786);
//$create_order = $exchange->create_limit_buy_order('NAV/BTC',194,0.0001550);
//$create_order = $exchange->create_limit_buy_order('NAV/BTC',203,0.0001482);
//$create_order = $exchange->create_limit_buy_order('OST/BTC',203,0.0000275);
//$create_order = $exchange->create_limit_buy_order('BCPT/BTC',468,0.00006410);
//$create_order = $exchange->create_limit_buy_order('BCPT/BTC',320,0.0000621);
//$create_order = $exchange->create_limit_buy_order('WINGS/BTC',665,0.0000621);
//$create_order = $exchange->create_limit_buy_order('HSR/BTC',45,0.001125);

$open_orders_2 = $exchange->fetch_open_orders('GAS/BTC');
$open_orders_2_K = $exchange->fetch_open_orders('KNC/BTC');
$open_orders_3_N = $exchange->fetch_open_orders('NAV/BTC');
$open_orders_4_O = $exchange->fetch_open_orders('OST/BTC');
$open_orders_5_C = $exchange->fetch_open_orders('BCPT/BTC');
$open_orders_6_C = $exchange->fetch_open_orders('WINGS/BTC');
$open_orders_7_C = $exchange->fetch_open_orders('HSR/BTC');
$balance = $exchange->fetch_balance ();
echo "sdf";
exit;
$balance = $exchange->create_limit_sell_order('GAS/BTC',10,3150);


var_dump ($balance);

exit;
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
$duration = '1d';
//print_r($_GET);
//exit;

//$duration = '2h';

$start_date = strtotime('2018-04-20 00:00:00').'000';
$candle_data = array();

//$ethbtc = \ccxt\binance\Utility::fetchRangeOHLCVbinance($exchange,'1h','ETH/BTC',$start_date);
foreach ($markets as $market){
//    $candle_data[$market['symbol']] = \ccxt\binance\Utility::fetchRangeOHLCVbinance($exchange,$duration,$market['symbol'],$start_date);
    $candle_data[$market['symbol']] = $exchange->fetch_ticker($market['symbol']);
}

echo " asd ";



?>
<!--<div id="container" style="width:100%; height:400px;"></div>-->
<!--<div id="container2" style="width:100%; height:800px;"></div>-->
<script>


//    var date_array = [<?php //$data_req =  join($date_data, "','"); $data_req = "'".$data_req."'"; echo $data_req;   ?>//];
//    console.log(date_array);
//    var chart = new Highcharts.Chart({
//        chart: {
//            renderTo: 'container',
//            zoomType: 'x'
//        },
//
//        xAxis: {
//            type: 'datetime',
//            title: {
//                text: 'days'
//            },
//            categories: [<?php //echo $data_req  ?>//],
//            min: 0
//        },
//        series: [
//
//            {
//                name: 'eos20',
//                data: [<?php //echo join($eos_btc_20_values, ',') ?>//]
//            }, {
//                name: 'eos8',
//                data: [<?php //echo join($eos_btc_8_values, ',') ?>//]
//            }]
//    });
//
//    var chart2 = new Highcharts.Chart({
//        chart: {
//            renderTo: 'container2',
//            zoomType: 'x'
//        },
//
//        xAxis: {
//            type: 'datetime',
//            title: {
//                text: 'days'
//            },
//            categories: [<?php //echo $data_req  ?>//],
//            min: 0
//        },
//        series: [{
//            name: 'data',
//            data: [<?php //echo join($value_data, ',') ?>//]
//        }]
//    });


</script>