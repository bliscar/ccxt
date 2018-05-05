
<html>

<script src="https://code.highcharts.com/highcharts.js"></script>

<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<link href="http://cdn.datatables.net/1.10.0/css/jquery.dataTables.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables_themeroller.css">
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>






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
require_once(__DIR__ . '/../../sam-tech/constants/bin_apis.php');
use MathPHP\Statistics\Average;

$exchange = new \ccxt\binanceSam();

$user_name = $_POST['selectApiKeys'];

//$user_name = 'Neeraj';
//$user_name = 'Bhawani';
//$user_name = 'Anurag';
//$user_name = 'Dushyant';



{

    $user = $user_name;
    $exchange->apiKey = $binanceApisapisapis[$user_name][Key];
    $exchange->secret = $binanceApisapisapis[$user_name][Secret];
}
//
//$start_range = '2018-04-01 00:00:00';
//$start_range_for_binance = strtotime($start_range).'000';

//$open_orders_9 = $exchange->fetch_open_orders('LEND/BTC');
//$open_orders_9 = $exchange->fetch_open_orders(null,$start_range_for_binance);

$balance = $exchange->fetch_balance ();
//$balance = $exchange->fetch_markets()
//$closed_orders = $exchange->fetch_closed_orders();
$tickers = $exchange->fetch_tickers_on_quote('btc');
$coins_with_positive_balance = array();
foreach ($balance['total'] as $key => $value){
    if($value > 0){
        $coins_with_positive_balance[$key]['quantity'] = $value;
    }
}

$total_btc_spent = 0;

foreach($coins_with_positive_balance as $key => $value){
//    if(strtolower($key) == 'nebl')
    if(strtolower($key) == 'btc'){
        $coins_with_positive_balance[$key]['current_price'] = 1;
        $coins_with_positive_balance[$key]['avg_buy_price'] = 1;
        $coins_with_positive_balance[$key]['last_24hr_percentage_change'] = 1;
        $coins_with_positive_balance[$key]['btc_value_of_coin'] = $value[quantity];
        $total_bitcoins = $value[quantity];
    }
    else if(array_key_exists($key,$tickers)){
        $coins_with_positive_balance[$key]['current_price'] = $tickers[$key][last];
        $coins_with_positive_balance[$key]['btc_value_of_coin'] = $tickers[$key][last]*$value[quantity];
        $coins_with_positive_balance[$key]['symbol'] = $tickers[$key][symbol];
        $coins_with_positive_balance[$key]['last_24hr_percentage_change'] = $tickers[$key][percentage];
        $symbol_closed_orders = $exchange->fetch_closed_orders($tickers[$key][symbol]);
        $total_coin_amount = 0;
        $total_coin_cost = 0;
        foreach($symbol_closed_orders as $k => $v){
            if($v['side'] == 'buy'){
                $total_coin_amount += $v['amount'];
                $total_coin_cost += $v['cost'];
            }else if($v['side'] == 'sell'){
                $total_coin_amount -= $v['amount'];
                $total_coin_cost -= $v['cost'];
            }
        }
        $total_btc_spent += $total_coin_cost;
        $final_avg_buy_price = $total_coin_cost/$total_coin_amount;
        $total_bitcoins += $tickers[$key][last]*$value[quantity];
        $coins_with_positive_balance[$key]['avg_buy_price'] = $final_avg_buy_price;
        $coins_with_positive_balance[$key]['total_btc_expenditure'] = $total_coin_cost;
    }
}
echo "
                    <h3>Binance of $user</h3>
                ";
echo "
          <table class = 'table table-hover' id = 'portfolio'>
          <thead>
                 
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Avg Buying Price</th>
                    <th>Current Price</th>
                    <th>Last 24 Hr Price Change</th>
                    <th>Profit %</th>
                    <th>BTC Value</th>
                    <th>BTC Cost</th>
                </tr>
           </thead>
           <tbody>
          ";

foreach ($coins_with_positive_balance as $key => $value){
    $percentage_profit = round(($value[current_price] - $value[avg_buy_price])/$value[avg_buy_price],4)*100;
    if(!$value[current_price])
        $percentage_profit = -1000;
    echo        " 
                    <tr>
                        <td>$key</td>
                        <td>$value[quantity]</td>
                        <td>".number_format($value[avg_buy_price],10)."</td>
                        <td>".number_format($value[current_price],10)."</td>
                        <td>".number_format($value[last_24hr_percentage_change],2)."</td>
                        <td>".number_format($percentage_profit,2)."</td>
                        <td>".number_format($value[btc_value_of_coin],10)."</td>
                        <td>".number_format($value[total_btc_expenditure],10)."</td>
                  </tr>
                 ";
}
echo        " 
                  </tbody>";

echo "</table>";
echo "<table  class = 'table table-hover' id = 'portfolioTotal'>
          <thead>
                 
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Avg Buying Price</th>
                    <th>Current Price</th>
                    <th>Profit %</th>
                    <th>BTC Value</th>
                    <th>BTC Cost</th>
                </tr>
           </thead>
           <tbody>
<tr>
                        <td>Total</td>
                        <td>Total</td>
                        <td>Total</td>
                        <td>Total</td>
                        <td>Total</td>
                        <td>Total</td>
                        <td>$total_bitcoins</td>
                        <td>$total_btc_spent</td>
                  </tr></tbody></table>
";

////exit;
//$open_orders_1 = $exchange->fetch_open_orders('GAS/BTC');
//$open_orders_2 = $exchange->fetch_open_orders('KNC/BTC');
//$open_orders_3 = $exchange->fetch_open_orders('NAV/BTC');
//$open_orders_4 = $exchange->fetch_open_orders('OST/BTC');
//$open_orders_5 = $exchange->fetch_open_orders('BCPT/BTC');
//$open_orders_6 = $exchange->fetch_open_orders('WINGS/BTC');
//$open_orders_7 = $exchange->fetch_open_orders('HSR/BTC');
//$open_orders_7 = $exchange->fetch_open_orders('QSP/BTC');
//$open_orders_8 = $exchange->fetch_open_orders('ICN/BTC');
//$open_orders_9 = $exchange->fetch_open_orders('LEND/BTC');
//$open_orders_9 = $exchange->fetch_open_orders();
////echo "asd";
////exit;
//$create_order = $exchange->create_limit_buy_order('GAS/BTC',10,0.003011);

////$create_order = $exchange->create_limit_buy_order('NAV/BTC',194,0.0001550);
////$create_order = $exchange->create_limit_buy_order('NAV/BTC',203,0.0001482);
////$create_order = $exchange->create_limit_buy_order('OST/BTC',203,0.0000275);
////$create_order = $exchange->create_limit_buy_order('BCPT/BTC',468,0.00006410);
////$create_order = $exchange->create_limit_buy_order('BCPT/BTC',320,0.0000621);
////$create_order = $exchange->create_limit_buy_order('WINGS/BTC',665,0.0000621);
////$create_order = $exchange->create_limit_buy_order('HSR/BTC',45,0.001125);
//$create_order = $exchange->create_limit_buy_order('QSP/BTC',4625,0.00002175);
//$create_order = $exchange->create_market_buy_order('QSP/BTC',4625,0.00002175);
//$create_order = $exchange->create_limit_sell_order('QSP/BTC',4625,0.00002180);
//$create_order = $exchange->create_limit_sell_order('ICN/BTC',583,0.00017130);
//$create_order = $exchange->create_limit_buy_order('LEND/BTC',5000,0.00000550);
//
//$open_orders_2 = $exchange->fetch_open_orders('GAS/BTC');

//$open_orders_3_N = $exchange->fetch_open_orders('NAV/BTC');
//$open_orders_4_O = $exchange->fetch_open_orders('OST/BTC');
//$open_orders_5_C = $exchange->fetch_open_orders('BCPT/BTC');
//$open_orders_6_C = $exchange->fetch_open_orders('WINGS/BTC');
//$open_orders_7_C = $exchange->fetch_open_orders('HSR/BTC');
//$open_orders_7c = $exchange->fetch_open_orders('QSP/BTC');
//$open_orders_9c = $exchange->fetch_open_orders('LEND/BTC');








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
$(document).ready(function() {
    $('#portfolio').DataTable({
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1
    });

});

</script>