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
 * Date: 16/4/18
 * Time: 7:17 PM
 */




date_default_timezone_set ('UTC');
include_once __DIR__.'/../ccxt.php';
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/classes/Utility.php');
use MathPHP\Statistics\Average;


$exchange = new \ccxt\bitmex();



$symbol_perpetual = 'BTC/USD';
$symbol_june18 = 'XBTM18';
$symbol_sep18 = 'XBTU18';

$start_range = '2018-04-01 00:00:00';
$duration = '1h';
$OHLCV_data_perpetual = \ccxt\Utility::fetchRangeOHLCVbitmex($exchange,$duration,$symbol_perpetual,$start_range);
$OHLCV_data_june18 = \ccxt\Utility::fetchRangeOHLCVbitmex($exchange,$duration,$symbol_june18,$start_range);
$OHLCV_data_sep18 = \ccxt\Utility::fetchRangeOHLCVbitmex($exchange,$duration,$symbol_sep18,$start_range);

$spread_perpetual_june18 = array();
$spread_perpetual_sep18 = array();
$spread_june18_sep18 = array();
$timestamp_data = array();
foreach($OHLCV_data_perpetual['closes'] as $timestamp => $data_value){
    $spread_perpetual_june18[] = $OHLCV_data_perpetual['closes'][$timestamp] - $OHLCV_data_june18['closes'][$timestamp];
    $spread_perpetual_sep18[] = $OHLCV_data_perpetual['closes'][$timestamp] - $OHLCV_data_sep18['closes'][$timestamp];
    $spread_june18_sep18[] = $OHLCV_data_june18['closes'][$timestamp] - $OHLCV_data_sep18['closes'][$timestamp];
    $timestamp_data[] =  strval(date("Y-m-d H:i:s",$timestamp/1000));
}


?>
<div id="container" style="width:100%; height:400px;"></div>
<div id="container2" style="width:100%; height:400px;"></div>
<div id="container3" style="width:100%; height:400px;"></div>

<script>


    var date_array = [<?php $timestamp_data =  join($timestamp_data, "','");
                            $timestamp_data = "'".$timestamp_data."'";
                            echo $timestamp_data;   ?>];
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
            categories: [<?php echo $timestamp_data  ?>],
            min: 0
        },
        series: [

            {
                name: 'Perpetual-June18',
                data: [<?php echo join($spread_perpetual_june18, ',') ?>]
            }
//            {
//                name: 'eos8',
//                data: [<?php //echo join($eos_btc_8_values, ',') ?>//]
//            }, {
//                name: 'eos8',
//                data: [<?php //echo join($eos_btc_8_values, ',') ?>//]
//            }
        ]
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
            categories: [<?php echo $timestamp_data  ?>],
            min: 0
        },
        series: [

            {
                name: 'Perpetual-Sep18',
                data: [<?php echo join($spread_perpetual_sep18, ',') ?>]
            }
//            {
//                name: 'eos8',
//                data: [<?php //echo join($eos_btc_8_values, ',') ?>//]
//            }, {
//                name: 'eos8',
//                data: [<?php //echo join($eos_btc_8_values, ',') ?>//]
//            }
        ]
    });

    var chart3 = new Highcharts.Chart({
        chart: {
            renderTo: 'container3',
            zoomType: 'x'
        },

        xAxis: {
            type: 'datetime',
            title: {
                text: 'days'
            },
            categories: [<?php echo $timestamp_data  ?>],
            min: 0
        },
        series: [

            {
                name: 'June18-Sep18',
                data: [<?php echo join($spread_june18_sep18, ',') ?>]
            }
//            {
//                name: 'eos8',
//                data: [<?php //echo join($eos_btc_8_values, ',') ?>//]
//            }, {
//                name: 'eos8',
//                data: [<?php //echo join($eos_btc_8_values, ',') ?>//]
//            }
        ]
    });





</script>



