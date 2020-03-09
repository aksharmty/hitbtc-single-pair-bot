 <?php
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
?>
<?php
//delete row
include "connect.php"; 
//$sqldel = "DELETE FROM trade WHERE quantity1  <= 0";

//if ($connection->query($sqldel) === TRUE) {
//    echo "Record deleted successfully";
// } else {
//   echo "Error deleting record: " . $connection->error;
// }
?>
<?php
   $ch = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($ch, CURLOPT_USERPWD, 'YOUR_API_KEY:YOUR|_SECRET_KEY'); // API AND KEY
 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json'));
 $return = curl_exec($ch); 
  curl_close($ch); 
 //print_r($return);
// $data = json_decode(file_get_contents($ch), true);

	?>
	
        <div class="container box">
          <h3 align="center">Import JSON File Data into Mysql Database in PHP</h3><br />
          <?php
         $sql123 = "TRUNCATE TABLE balance  ";
        if ($connection->query($sql123) === TRUE) {
    echo "Ready for New Record updated successfully";
} else {
    echo "Error updating record: " . $connection->error;
} 
         $query = '';
          $table_data = '';
          $date = Date("Y-m-d H:i:s");
          //$filename = "balance.json";
          //$data = file_get_contents($filename); //Read the JSON file in PHP
         // $array = json_decode($return, true); //Convert JSON String into PHP Array
          //start
          $data = json_decode($return, true);
$need = array(
    1 =>'DOGE',
    2=>'BTC'
);
foreach ($data as $key => $value) {
    if (in_array($data[$key]['currency'], $need)) {
        echo $data[$key]['currency'] . " = " . $data[$key]['available'] . " = " . $data[$key]['reserved'];
        echo "<br>";
    }
}
////end
 foreach ($data as $key => $value) {//Extract the Array Values by using Foreach Loop
              if (in_array($data[$key]['currency'], $need)) {
          //$query .= "UPDATE top (100) balance SET currency = ('".$row["currency"]."'),  available = ('".$row["available"]."'), reserved = ('".$row["reserved"]."'); ";
         $query .= "INSERT INTO balance(currency, available,reserved,date) VALUES ('".$data[$key]['currency']."', '".$data[$key]['available']."','".$data[$key]['reserved']."','$date'); ";  // Make Multiple Insert Query 
           $table_data .= '
            <tr>
       <td>'.$data[$key]["currency"].'</td>
       <td>'.$data[$key]["available"].'</td>
       <td>'.$data[$key]["reserved"].'</td>
      </tr>
           '; //Data for display on Web page
          } }
          if(mysqli_multi_query($connection, $query)) //Run Mutliple Insert Query
    {
     echo '<h3>Imported JSON Data</h3><br />';
     echo '
      <table class="table table-bordered">
        <tr>
         <th width="45%">Coin Name</th>
         <th width="10%">Available</th>
         <th width="45%">Reserved</th>
        </tr>
     ';
     echo $table_data;  
     echo '</table>';
          }

?>

     <br />
 
