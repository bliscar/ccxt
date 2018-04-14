<?php
namespace ccxt\binance;


/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 12/4/18
 * Time: 10:18 AM
 */



date_default_timezone_set ('UTC');
use Exception as Exception; // a common import
use MathPHP\Statistics\Average;
include_once __DIR__.'/../../ccxt.php';
require_once(__DIR__ . '/../../vendor/autoload.php');

class Utility{
    //***Params Value Example***//
    //  $start_range = '2018-04-09 11:30:00'
    //  $end_range = NULL -> $end_range = 'current_timestamp'
    //  $end_range = '2018-04-09 11:30:00'
    //  $exchange = Mandatory Exchange Object
    //  $duration = '1m', '5m', '1h', '1d'
    //  $symbol = 'BTC/USD'
    public static function fetchRangeOHLCVbinance($exchange, $duration, $symbol, $start_range, $end_range = NULL){
        if(!$end_range)
            $end_range = time();
        else
            $end_range = strtotime($end_range);

        if($duration == '1m')
            $seconds_with_suffix = 60000;
        elseif($duration == '5m')
            $seconds_with_suffix = 60000*5;
        elseif($duration == '1h')
            $seconds_with_suffix = 60000*60;       
        elseif($duration == '2h')
            $seconds_with_suffix = 60000*120;
        elseif($duration == '1d')
            $seconds_with_suffix = 60000*60*24;

        $start_range_for_bitmex = strtotime($start_range).'000';
        $end_range_for_bitmex = $end_range.'000';
        $last_timestamp_plus_next_chunk = 0;

        $full_range_candlestick_data = array();

        $opens = array();
        $highs = array();
        $lows = array();
        $closes = array();
        $volumes = array();
        $average_highs_lows = array();
        $full_range_candlestick_data = array();
        $counter = 0;
        if ($exchange->has['fetchOHLCV']){
            do{
                usleep ($exchange->rateLimit * 1000); // usleep wants microseconds
                $candlestick_data = $exchange->fetch_ohlcv ($symbol, $duration,$start_range_for_bitmex);
                $last_timestamp_plus_next_chunk = end($candlestick_data)[0] + $seconds_with_suffix;
                foreach($candlestick_data as $element){
                    $full_range_candlestick_data[$element[0]] = $element;
                    $opens[$element[0]] = $element[1];
                    $highs[$element[0]] =$element[2];
                    $lows[$element[0]] = $element[3];
                    $closes[$element[0]] = $element[4];
                    $volumes[$element[0]] = $element[5];
                    $closes_index[$counter]['timestamp'] = $element[0]; 
                    $closes_index[$counter]['value'] = $element[4]; 
                    $average_highs_lows[$element[0]] = round(($element[2]+$element[3])/2);
                    $counter++;
                }
                $start_range_for_bitmex = $last_timestamp_plus_next_chunk;
            }while($last_timestamp_plus_next_chunk < intval($end_range_for_bitmex));
        }

        $return_data = array();
        $return_data['full_range_candlestick_data'] = $full_range_candlestick_data;
        $return_data['opens'] = $opens;
        $return_data['highs'] = $highs;
        $return_data['lows'] = $lows;
        $return_data['closes'] = $closes;
        $return_data['volumes'] = $volumes;
        $return_data['average_highs_lows'] = $average_highs_lows;
        $return_data['closes_index'] = $closes_index;

        return $return_data;

    }

    public static function exponentialMovingAverageKeys(array $numbers, int $n): array
    {
        
        $m   = count($numbers);
        $α   = 2 / ($n + 1);
        $EMA = [];

        // Start off by seeding with the first data point
        $EMA[0]['value'] = $numbers[0]['value'];
        $EMA[0]['timestamp'] = $numbers[0]['timestamp'];

        // Each day after: EMAtoday = α⋅xtoday + (1-α)EMAyesterday
        for ($i = 1; $i < $m; $i++) {
            $EMA[$i]['value'] = ($α * $numbers[$i]['value']) + ((1 - $α) * $EMA[$i - 1]['value']);
            $EMA[$i]['timestamp'] = $numbers[$i]['timestamp'];
        }

        return $EMA;
    }
}
