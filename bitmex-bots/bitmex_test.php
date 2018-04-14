<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 7/4/18
 * Time: 1:38 PM
 */
date_default_timezone_set ('UTC');
include_once __DIR__.'/../ccxt.php';
require_once(__DIR__ . '/../vendor/autoload.php');
use MathPHP\Statistics\Average;
//include_once __DIR__.'/../php/test/test.php';
//$bitfinex = new \ccxt\bitfinex (); // default id
//$bitfinex1 = new \ccxt\bitfinex (array ('id' => 'bitfinex1'));
//$bitfinex2 = new \ccxt\bitfinex (array ('id' => 'bitfinex2'));
//$id = 'kraken';
//$exchange = '\\ccxt\\' . $id;
//$kraken = new $exchange ();
//$id = 'huobi';
//$bitmex = new \ccxt\bitmex ();
////$huobi = new $exchange ();
//$markets = $bitmex->fetch_markets ();
//print_r($markets);
//exit;
//var_dump ($bitmex->id, $markets);
//load_exchange('bitmex');
//$bitfinex = new \ccxt\bitfinex (array ('verbose' => true)); // log HTTP requests
//$bitfinex->load_markets (); // request markets
//var_dump ($bitfinex->id, $bitfinex->markets); // output a full list of all loaded markets
//var_dump (array_keys ($bitfinex->markets));   // output a short list of market symbols
//var_dump ($bitfinex->markets['XRP/USD']);     // output single market details
//$bitfinex->load_markets (); // return a locally cached version, no reload
//$reloadedMarkets = $bitfinex->load_markets (true); // force HTTP reload = true
//var_dump ($bitfinex->markets['XRP/BTC']);

//$bitmex = new \ccxt\bitmex (array ('verbose' => true)); // log HTTP requests
//$bitmex->load_markets (); // request markets
////var_dump ($bitmex->id, $bitmex->markets['XBTC/USD']); // output a full list of all loaded markets
//var_dump (array_keys ($bitmex->markets));   // output a short list of market symbols
//var_dump ($exchange->load_markets ());
//
//$dashcny1 = $exchange->markets['DASH/CNY'];     // get market structure by symbol
//$dashcny2 = $exchange->market ('DASH/CNY');     // same result in a slightly different way
//
//$dashcnyId = $exchange->market_id ('DASH/CNY'); // get market id by symbol
//
//$symbols = $exchange->symbols;                  // get an array of symbols
//$symbols2 = array_keys ($exchange->markets);    // same as previous line
//
//var_dump ($exchange->id, $symbols);             // print all symbols
//
//$currencies = $exchange->currencies;            // a list of currencies



$process_inr = curl_init('http://localhost:4444/instrument?symbol=XBTUSD');
//$process_inr = curl_init("https://www.zebapi.com/api/v1/market/ticker-new/btc/inr");
//("https://www.zebapi.com/api/v1/market/ticker-new/".strtolower($element['symbol'])."/inr");

curl_setopt($process_inr, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($process_inr, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($process_inr, CURLOPT_AUTOREFERER, false);
curl_setopt($process_inr, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($process_inr, CURLOPT_HTTPHEADER, array('Accept: application/json'));




$result1 = curl_exec($process_inr);
$output = curl_getinfo($process_inr);

$result_data_array_inr = json_decode($result1,true);

$exchange = new \ccxt\bitmexSam();

//
//$a = $okcoinusd->load_markets ();
//
//$a = $okcoinusd->markets['BTC/USD'];                 // symbol → market (get market by symbol)
//$b = $okcoinusd->markets_by_id['XBTUSD'];           // id → market (get market by id)$
//
//$c = $okcoinusd->markets['BTC/USD']['id'];           // symbol → id (get id by symbol)
//$d = $okcoinusd->markets_by_id['XBTUSD']['symbol']; // id → symbol (get symbol by id)
//var_dump (new \ccxt\okcoinusd ()); // PHP

//$b = $okcoinusd->fetchMarkets();      // PHP
////$b = $okcoinusd->public_get_ticker (array ('pair' => 'XBTUSD'));      // PHP
//$c = $okcoinusd->fetchTicker('BTC/USD');
//$c = $okcoinusd->fetchTicker('BTC/USD');
//$c = $okcoinusd->fetchTicker('BTC/USD');
//$c = $okcoinusd->fetchTicker('BTC/USD');
//$c = $okcoinusd->fetchBalance('BTC/USD');
//print_r($c);
//print_r($b);
//print_r($b);
//print_r($b);
//$exchange = '\\ccxt\\kraken';
//$exchange = new $exchange ();
// up to ten orders on each side, for example
//$limit = 20;
//$Ob =  $okcoinusd->fetchOrderBook ('BTC/USD', $limit);
//$Ob2 =  $okcoinusd->fetchL2OrderBook ('BTC/USD', $limit);
//print_r($Ob);
//print_r($Ob2);

//***STRATEGY***///
//      1. Find out the range to on which the current system will work
//              ->For the time being take a spread of 200$ from the current price and if prices go beyond that, cut the time limit till then
//      2. For the above range find current mean and SD.
//      3. Create Longs and Shorts at both the extremes(or @SD1, @SD2, etc.)
//      4. After 5 Minutes Run the same script and check if any position is closed, if not adjust the Opens, if yes then place a short @ the opposite points of double amount and
//              A SL for the current position.
//      5. Keep running the script for every 5 minutes.
//***STRATEGY***//

//
//
//$orderbook = $exchange->fetch_order_book ('BTC/USD');
//$bid = count ($orderbook['bids']) ? $orderbook['bids'][0][0] : null;
//$ask = count ($orderbook['asks']) ? $orderbook['asks'][0][0] : null;
//$spread = ($bid && $ask) ? $ask - $bid : null;
//$result = array ('bid' => $bid, 'ask' => $ask, 'spread' => $spread);
//var_dump ($exchange->id, 'market price', $result);

$symbol = 'BTC/USD';
$current_timestamp = time();
$current_timestamp_modified = $current_timestamp.'000';
$last_timestamp = 0;
$last_timestamp_plus_5_minutes = 0;
//$start_range_timestamp_string = strtotime('2018-04-09 00:00:00').'000';
$start_range_timestamp_string = strtotime('2018-04-09 11:30:00').'000';
$full_range_candlestick_data = array();

$opens = array();
$highs = array();
$lows = array();
$closes = array();
$volumes = array();
$average_highs_lows = array();
$count_6756 = 0;
$count_6696 = 0;
if ($exchange->has['fetchOHLCV']){
        do{
                usleep ($exchange->rateLimit * 1000); // usleep wants microseconds
                $candlestick_data = $exchange->fetch_ohlcv ($symbol, '5m',$start_range_timestamp_string);
                print_r ($candlestick_data); // one month
                $last_timestamp = end($candlestick_data)[0];
                $last_timestamp_plus_5_minutes = $last_timestamp + 300000;
                foreach($candlestick_data as $element){
                        $full_range_candlestick_data[$element[0]] = $element;
                        $opens[$element[0]] = $element[1];
                        $highs[$element[0]] =$element[2];
                        $lows[$element[0]] = $element[3];
                        $closes[$element[0]] = $element[4];
                        $volumes[$element[0]] = $element[5];
                        $average_highs_lows[$element[0]] = round(($element[2]+$element[3])/2);
                        if($element[2] >= 6756 && $element[3] <= 6756)
                                $count_6756++;
                        if($element[2] >= 6696 && $element[3] <= 6696)
                                $count_6696++;
                }
                $start_range_timestamp_string = $last_timestamp_plus_5_minutes;
//                exit;
        }while($last_timestamp_plus_5_minutes < intval($current_timestamp_modified));
}
$final_data = $full_range_candlestick_data;
print_r($full_range_candlestick_data);
echo "done";
//
//if (!function_exists('stats_standard_deviation')) {
////        /**
////         * This user-land implementation follows the implementation quite strictly;
////         * it does not attempt to improve the code or algorithm in any way. It will
////         * raise a warning if you have fewer than 2 values in your array, just like
////         * the extension does (although as an E_USER_WARNING, not E_WARNING).
////         *
////         * @param array $a
////         * @param bool $sample [optional] Defaults to false
////         * @return float|bool The standard deviation or false on error.
////         */
////        function stats_standard_deviation(array $a, $sample = false) {
////                $n = count($a);
////                if ($n === 0) {
////                        trigger_error("The array has zero elements", E_USER_WARNING);
////                        return false;
////                }
////                if ($sample && $n === 1) {
////                        trigger_error("The array has only 1 element", E_USER_WARNING);
////                        return false;
////                }
////                $mean = array_sum($a) / $n;
////                $carry = 0.0;
////                foreach ($a as $val) {
////                        $d = ((double) $val) - $mean;
////                        $carry += $d * $d;
////                };
////                if ($sample) {
////                        --$n;
////                }
////                return sqrt($carry / $n);
////        }
//}

$std_deviation_opens = stats_standard_deviation($opens);
$mean_opens   = Average::mean($opens);

$std_deviation_highs = stats_standard_deviation($highs);
$mean_highs   = Average::mean($highs);
//6756
$std_deviation_lows = stats_standard_deviation($lows);
$mean_lows   = Average::mean($lows);
//6696
$std_deviation_closes = stats_standard_deviation($closes);
$mean_closes   = Average::mean($closes);

$std_deviation_average_highs_lows = stats_standard_deviation($average_highs_lows);
$mean_average_highs_lows   = Average::mean($average_highs_lows);




//
//
//public static function fetchRangeOHLCV($startRange, $endRange){
//
//}