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
 * Date: 30/4/18
 * Time: 11:57 AM
 */


date_default_timezone_set ('UTC');
include_once __DIR__.'/../../ccxt.php';
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../classes/Utility.php');
require_once(__DIR__ . '/../../samriddhee_db/classes/botStatusDAO.php');
require_once(__DIR__ . '/../../samriddhee_db/classes/binanceApiKeysDAO.php');

use MathPHP\Statistics\Average;


$bin_api_keys = binanceApiKeysDAO::getBinanceApiKeys();

echo "    <h2>
               Binance Bots
          </h2>";

echo '<form action="../binance_ui.php" id="formApiKeys" method="post">';

echo '<label for="selectApiKeys">Select API Keys</label>';
echo '<select style= "width:17%" class="form-control"  id="selectApiKeys" name="selectApiKeys" form="formApiKeys">';
echo '<option value= -1>Select API Keys</option>';

foreach ($bin_api_keys['api_keys'] as $key => $value){
    echo "<option value= $value[user_name]>$value[user_name]</option>";

}
echo "</select>";

echo '<hr>';

echo '<input type="submit" class="btn-danger">';

echo '</form>';








//for($counter = 0; $counter < count($active_marketplaces['id']); $counter++){
//    echo '<option value='.$active_marketplaces['id'][$counter].'>'.$active_marketplaces['names'][$counter].'</option>';
//}


?>
</body>
<script type="text/javascript">
    
</script>
