<html>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link href="http://cdn.datatables.net/1.10.0/css/jquery.dataTables.css" rel="stylesheet" media="screen">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

</html>
<body class="body">
<?php
/**
 * Created by PhpStorm.
 * User: neerajudai
 * Date: 27/4/18
 * Time: 3:04 PM
 */


date_default_timezone_set ('UTC');
include_once __DIR__.'/../../ccxt.php';
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../classes/Utility.php');
require_once(__DIR__ . '/../../samriddhee_db/classes/botStatusDAO.php');
require_once(__DIR__ . '/../../../sam-tech/constants/bmx_apis.php');
use MathPHP\Statistics\Average;

echo "    <h2>
               Bitmex Bots
          </h2>";

echo '<div class="form-group">';
echo '<label for="selectNet">Select Net</label>';
echo '<select style= "width:17%" class="form-control"  id="selectNet">';
echo '<option value= -1>Select Net</option>';
echo '<option value= 1>Testnet</option>';
echo '<option value= 2>Mainnet</option>';

//for($counter = 0; $counter < count($active_marketplaces['id']); $counter++){
//    echo '<option value='.$active_marketplaces['id'][$counter].'>'.$active_marketplaces['names'][$counter].'</option>';
//}
echo ' </select></div>';
echo '	</div>';

echo '<div class="form-group" id = "divAPiKeys">';
echo '	</div>';

echo '<div class="form-group" id = "divBitmexSymbol">';
echo '	</div>';



?>
</body>
<script type="text/javascript">

    $(document).ready(function() {
        $(".body").on("change", "select[id='selectNet']", function () {
            net_value  = $(this).children('option:selected').attr('value');
            if(net_value != -1){
                $.ajax({
                    type:"GET",
                    url: "/../../samriddhee_ajax/bitmex_bots_ajax.php",
                    contentType: "application/json; charset=utf-8",
                    dataType: "html",
                    data: {'command':'change_net', 'net_value':net_value} ,
                    success: function(data) {
                        if(data == 500){
                            alert("Some Error Occurred");
                        }else{
                            $('#divAPiKeys').empty();
                            $('#divAPiKeys').append(data);

                        }
                    }

                });
            }else{
                alert("No Net Selected");
                $('#divAPiKeys').empty();
                $('#divBitmexSymbol').empty();
            }
        });

        $(".body").on("change", "select[id='selectApiKey']", function () {
            user_name  = $(this).children('option:selected').attr('value');
            if(user_name != -1){
                $.ajax({
                    type:"GET",
                    url: "/../../samriddhee_ajax/bitmex_bots_ajax.php",
                    contentType: "application/json; charset=utf-8",
                    dataType: "html",
                    data: {'command':'change_api_key', 'user_name':user_name, 'net_value':net_value} ,
                    success: function(data) {
                        if(data == 500){
                            alert("Some Error Occurred");
                        }else{
                            $('#divBitmexSymbol').empty();
                            $('#divBitmexSymbol').append(data);

                        }
                    }

                });
            }else{
                alert("No Username Selected");
                $('#divBitmexSymbol').empty();
            }
        });

        $(".body").on("change", "select[id='selectBitmexSymbol']", function () {
            symbol  = $(this).children('option:selected').attr('value');
            if(symbol != -1){
                $.ajax({
                    type:"GET",
                    url: "/../../samriddhee_ajax/bitmex_bots_ajax.php",
                    contentType: "application/json; charset=utf-8",
                    dataType: "html",
                    data: {'command':'change_symbol', 'user_name':user_name, 'net_value':net_value, 'symbol':symbol} ,
                    success: function(data) {
                        if(data == 500){
                            alert("Some Error Occurred");
                        }else{
                            alert("All Set! The bot will run accroding to the settings done in select boxes!");

                        }
                    }

                });
            }else{
                alert("No symbol Selected");
            }
        });
    })
</script>
