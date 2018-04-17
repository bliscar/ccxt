<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 10/4/18
 * Time: 8:20 PM
 */

//***STRATEGY***///
//      TO FIND OUT - Number of bets that can be taken, statistical stop loss.
//
//      1. Find out the range to on which the current system will work
//              ->For the time being take a spread of 200$ from the current price and if prices go beyond that, cut the time limit till then
//      2. For the above range find current mean and SD.
//      3. Create Longs and Shorts at both the extremes(or @SD1, @SD2, etc.) if no open position is there.
//      4. After 5 Minutes Run the same script and check if any position is taken, if not adjust the Opens, if yes then place a short @ the opposite points of double amount and
//              A SL for the current position.
//      5. Keep running the script for every 5 minutes.
//***STRATEGY***//

date_default_timezone_set ('UTC');
include_once __DIR__.'/../../ccxt.php';
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../classes/Utility.php');
use MathPHP\Statistics\Average;


$exchange = new \ccxt\bitmex();


//NeerajTest
//$exchange->apiKey = 'Bd1DKQOJ3MuqZPjv5QcznfYW';
//$exchange->secret = 'qBJQyCwboT3nlPZ2lxnHEAOrBJvIm2f1sodXcXgoWFlAdfh7';
//
//AnuragMain
$exchange->apiKey = 'PeSLxxp7MEZ06ujwf_NPEz4G';
$exchange->secret = 'n1RAC2Euprc5otU2N0Jlsb8ZBfzaQfLnpRfID2Blm7Oxc5y0';

$balance = $exchange->fetch_balance ();
var_dump ($balance);

print_r ($exchange->has); // or var_dump
exit;

$symbol = 'BTC/USD';
//$start_range_timestamp_string = strtotime('2018-04-09 00:00:00').'000';
$start_range = '2018-04-09 11:30:00';
$duration = '5m';
$OHLCV_data = \ccxt\Utility::fetchRangeOHLCVbitmex($exchange,$duration,$symbol,$start_range);


$std_deviation_opens = \ccxt\Utility::stats_standard_deviation($OHLCV_data['opens']);
$mean_opens   = Average::mean($OHLCV_data['opens']);

$std_deviation_highs = \ccxt\Utility::stats_standard_deviation($OHLCV_data['highs']);
$mean_highs   = Average::mean($OHLCV_data['highs']);
$short_point = $mean_highs + $std_deviation_highs;

$std_deviation_lows = \ccxt\Utility::stats_standard_deviation($OHLCV_data['lows']);
$mean_lows   = Average::mean($OHLCV_data['lows']);
$long_point = $mean_lows - $std_deviation_lows;

$std_deviation_closes = \ccxt\Utility::stats_standard_deviation($OHLCV_data['closes']);
$mean_closes   = Average::mean($OHLCV_data['closes']);

$std_deviation_average_highs_lows = \ccxt\Utility::stats_standard_deviation($OHLCV_data['average_highs_lows']);
$mean_average_highs_lows   = Average::mean($OHLCV_data['average_highs_lows']);
$short_point_average = $std_deviation_average_highs_lows + $mean_average_highs_lows;
$long_point_average = $mean_average_highs_lows - $std_deviation_average_highs_lows;

$freq_sp = \ccxt\Utility::frequencyOfRateInDurationBitmex($short_point,$exchange,$duration,$symbol,$start_range);
$freq_lp = \ccxt\Utility::frequencyOfRateInDurationBitmex($long_point,$exchange,$duration,$symbol,$start_range);
$freq_sp_avg = \ccxt\Utility::frequencyOfRateInDurationBitmex($short_point_average,$exchange,$duration,$symbol,$start_range);
$freq_lp_avg = \ccxt\Utility::frequencyOfRateInDurationBitmex($long_point_average,$exchange,$duration,$symbol,$start_range);






