<html>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link href="http://cdn.datatables.net/1.10.0/css/jquery.dataTables.css" rel="stylesheet" media="screen">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

</html>

<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 25/4/18
 * Time: 2:42 AM
 */
date_default_timezone_set ('UTC');
include_once __DIR__.'/../../ccxt.php';
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../classes/Utility.php');
require_once(__DIR__ . '/../../samriddhee_db/classes/botStatusDAO.php');
use MathPHP\Statistics\Average;

$exchange = new \ccxt\bitmexSam(); //TestNet
//$exchange = new \ccxt\bitmex();      //MainNet


//NeerajTest
$long = $_GET['long'];
$long = 7500;
$short = $_GET['short'];
$short = 8500;
$amount = 1000;
$exchange->apiKey = 'Bd1DKQOJ3MuqZPjv5QcznfYW';
$exchange->secret = 'qBJQyCwboT3nlPZ2lxnHEAOrBJvIm2f1sodXcXgoWFlAdfh7';
//
//////AnuragMain
//$exchange->apiKey = 'PeSLxxp7MEZ06ujwf_NPEz4G';
//$exchange->secret = 'n1RAC2Euprc5otU2N0Jlsb8ZBfzaQfLnpRfID2Blm7Oxc5y0';
//
//$balance = $exchange->fetch_balance ();
//var_dump ($balance);

//print_r ($exchange->has); // or var_dump
//exit;

//$start_range = strtotime('2018-04-17 00:00:00').'000';
$symbol_perpetual = 'BTC/USD';
$symbol_position = array();
$symbol_position[] = 'XBTUSD';

$open_orders = $exchange->fetch_open_orders($symbol_perpetual);
$open_positions = $exchange->fetch_positions();
//$exchange->fe
foreach ($open_positions as $key => $position){
    if(!in_array($position['symbol'],$symbol_position)){
        unset($open_positions[$key]);
    }
}
$a = $open_positions;

echo "    <h2>
               Bitmex Range Bot Dashboard
          </h2>
          <table class = 'table table-hover'>
                 <tr >
                    <th colspan='20'>Positions</th>
                </tr>   
                <tr>
                    <th>Symbol</th>
                    <th>Size</th>
                    <th>Mark Value</th>
                    <th>Mark Price</th>
                    <th>Entry Price</th>
                    <th>Liquidation Price</th>
                    <th>Margin</th>
                    <th>Leverage</th>
                    <th>Unrealised PnL</th>
                </tr>
          ";

foreach ($open_positions as $row){
    echo "      <tr>
                    <td>$row[symbol]</td>
         ";
    if($row[currentQty] < 0){
        echo "          
                <td><font color='red'>$row[currentQty](SHORT)</font></td>
             ";
    }else{
        echo "          
                <td><font color='green'>$row[currentQty](LONG)</font></td>
             ";
    }

    echo            "
                    <td>".round($row[markValue]/100000000,4)."</td>
                    <td>".$row[markPrice]."</td>
                    <td>".$row[avgEntryPrice]."</td>
                    <td>".$row[liquidationPrice]."</td>";
    if($row[crossMargin] == true){
        echo        "
                    <td>".round($row[maintMargin]/100000000,4)."(CROSS)</td>";
    }else{
        echo        "
                    <td>".round($row[maintMargin]/100000000,4)."</td>";
    }
    echo           "
                    <td>$row[leverage]</td>
                    <td>".round($row[unrealisedPnl]/100000000,4)."</td>
                </tr>
    ";
}

echo "</table>";

echo "    
          <table class = 'table table-hover'>
                 <tr >
                    <th colspan='20'>Active Open Orders</th>
                </tr>   
                <tr>
                    <th>Symbol</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Time</th>
                    <th>Filled</th>
                    <th>Remaining</th>
                </tr>
          ";

foreach ($open_orders as $row){
    echo "      <tr>
                    <td>$row[symbol]</td>
         ";
    if($row[side] == 'sell'){
        echo "          
                <td><font color='red'>-$row[amount](SHORT)</font></td>
             ";
    }else if($row[side] == 'buy'){
        echo "          
                <td><font color='green'>$row[amount](LONG)</font></td>
             ";
    }

    echo            "
                    <td>".$row[price]."</td>
                    <td>".$row[datetime]."</td>
                    <td>".$row[filled]."</td>
                    <td>".$row[remaining]."</td>";

    echo           "
                </tr>
    ";
}

echo "</table>";

echo "<br>";


echo "<h4>Current Status of the bot </h4>";
$bitmex_bot_status = botStatusDAO::getBitmexBotStatus();
if($bitmex_bot_status[0][on_status] == 1){
    echo "<label class='success' type='label' id='labelStatusChange'  ><h3 ><font color='green'>ON</font></h3></label>";
}else if($bitmex_bot_status[0][on_status] == -1){
    echo "<label class='success' type='label' id='labelStatusChange'  ><h3><font color='red'>OFF</font></h3></label>";
}


echo "<h4>Change the Status of the bot</h4>";
echo "<button class='btn btn-danger' type='button' id='statusChange'  >Change Status</button>";
echo "<br>";
echo "<br>";

$bitmex_range_bot_details = botStatusDAO::getBitmexRangeBotDetails();
echo "    
          <table class = 'table table-hover'>
                 <tr >
                    <th colspan='20'>Range Bot Details</th>
                </tr>   
                <tr>
                    <th>Long Price</th>
                    <th>Short Price</th>
                    <th>Amount</th>
                    <th>Update</th>
                </tr>
          ";

echo            " <tr>
                    <td>Long Price<input type='text' class='form-control' id='textLong' value='".$bitmex_range_bot_details[0][long_price]."'></td>
                    <td>Short Price<input type='text' class='form-control' id='textShort' value='".$bitmex_range_bot_details[0][short_price]."'></td>
                    <td>Playing Amount<input type='text' class='form-control' id='textAmount' value='".$bitmex_range_bot_details[0][amount]."'></td>
                    <td><button class='btn btn-success' type='button' id='butUpdateDetails'>Update Details</button></td>
                   </tr>   
                 ";

echo "</table>";

//exit;


?>

<script type="text/javascript">
    $(document).ready(function(){

        $('#statusChange').click(function()
        {
            
            $.ajax({
                type:"GET",
                url: "/../../samriddhee_ajax/bitmex_range_bot_ui_ajax.php",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                data: {'command':'change_status'} ,
                success: function(data) {
                    if(data == 500){
                        alert("Some Error Occurred");
                    }else{
                        if(data.current_status == 1){
                            $('#labelStatusChange').empty();
                            $('#labelStatusChange').append("<h3 ><font color='green'>ON</font></h3>");
                        }else if(data.current_status == -1){
                            $('#labelStatusChange').empty();
                            $('#labelStatusChange').append("<h3 ><font color='red'>OFF</font></h3>");
                        }
                    }
                }

            });

        });

        $('#butUpdateDetails').click(function()
        {
            var long_price = 0;
            var short_price = 1000000;
            var amount = 1;
            long_price = $('#textLong').val();
            short_price = $('#textShort').val();
            amount = $('#textAmount').val();
            var r=confirm("Long Price:" + long_price +
                " Short Price: " + short_price +
                " Amount:" + amount +
                "Click the OK button If you want to send the above information!");
            if (r==true)
            {
                $.ajax({
                    type:"GET",
                    url: "/../../samriddhee_ajax/bitmex_range_bot_ui_ajax.php",
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    data: {'command':'change_range_details', 'long_price':long_price, 'short_price':short_price, 'amount':amount} ,
                    success: function(data) {
                        if(data == 500){
                            alert("Some Error Occurred");
                        }else{
                            alert("Details Updated!");
                            $('#textLong').val(data.long_price);
//                            $("#textLong").css({"background-color": "green"});
                            $('#textShort').val(data.short_price);
//                            $("#textShort").css({"background-color": "green"});
                            $('#textAmount').val(data.amount);
//                            $("#textAmount").css({"background-color": "green"});
                        }
                    }

                });
            }
            else
            {
                alert("You pressed Cancel! Command Aborted!!");
            }
            

        });

    });
</script>
