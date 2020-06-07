<?php
include "connect.php";
$ptr0 = "SELECT * FROM trade1 where type='0' ORDER BY price DESC LIMIT 1";
$ptr1 = $connection->query($ptr0);
if ($ptr1->num_rows > 0) {
    // output data of each row
    while($rowptr = $ptr1->fetch_assoc()) {
       echo " check avg price "; 
$sid=$rowptr['id']; $spri=$rowptr['clientOrderId']; 
              $clid= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1  WHERE clientOrderId='$spri' AND id ='$sid'"));
               $amount=$clid['quantity'];
               $av=$clid['clientOrderId'];
               //echo  "avg ", $av;
$cht = curl_init("https://api.hitbtc.com/api/2/history/order?symbol=DOGEBTC&clientOrderId=$av"); 
 curl_setopt($cht, CURLOPT_USERPWD, 'API_KEY:SECRET_KEY'); // API AND KEY
 curl_setopt($cht, CURLOPT_RETURNTRANSFER,1);
 curl_setopt($cht, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($cht, CURLOPT_HTTPHEADER, array('accept: application/json'));
 $returnt = curl_exec($cht); 
  curl_close($cht); 
 print_r($return);
 //
 $array = json_decode($returnt, true); //Convert JSON String into PHP Array
          foreach($array as $row) //Extract the Array Values by using Foreach Loop
          {
              $avp=$row['avgPrice'];
             echo "avg price :", $avp; 
         
          }
    $sl1=$avp*0.33/100; $sl=number_format($sl1,11); $sl3=$sl+$avp; $sl2=number_format($sl3,11); echo "<br> sell ", $sl; echo "<br> sell price ", $sl2;
} } else {echo "no found avg row"; }
               ?>
               //////////
<?php
//sell balance update start
$chbal1 = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($chbal1, CURLOPT_USERPWD, 'API_KEY:SECRET_KEY'); // API AND KEY
 curl_setopt($chbal1, CURLOPT_RETURNTRANSFER,1);
 curl_setopt($chbal1, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($chbal1, CURLOPT_HTTPHEADER, array('accept: application/json'));
 $return1 = curl_exec($chbal1); 
  curl_close($chbal1); 
 //print_r($return1);
$data1 = json_decode($return1, true);
$need1 = array(
    1=>'DOGE'
);
foreach ($data1 as $key1 => $value) {//Extract the Array Values by using Foreach Loop
          if (in_array($data1[$key1]['currency'], $need1)) { 
          $dbal=$data1[$key1]['available'];
         $querybal1 = mysqli_query($connection,"UPDATE balance set currency='".$data1[$key1]['currency']."' , available='".$data1[$key1]['available']."' , reserved = '".$data1[$key1]['reserved']."' , date='$date' where currency='DOGE'");
          }}

	//sell balance update end
	// sell order start
echo "doge bal"; echo $dbal;	

?>
<?php
//sell start
$sq0=$amount-1;
if($dbal > $sq0){
$symbol1   = "DOGEBTC";
$side1= "sell";
$type1= "limit";
$price1= "$sl2";
$quantity1="$amount";
$timeInForce1= "GTC";

$ch1 = curl_init();
//do a post
curl_setopt($ch1,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch1, CURLOPT_USERPWD, 'API_KEY:SECRET_KEY'); // API AND KEY
curl_setopt($ch1, CURLOPT_POST,1);
curl_setopt($ch1,CURLOPT_POSTFIELDS,"symbol=$symbol1&side=$side1&price=$price1&quantity=$quantity1&type=$type1&timeInForce=$timeInForce1");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('accept: application/json'));
$result1=curl_exec($ch1);
curl_close($ch1);
//$resultsell=json_decode($resultsell);
//echo"<pre>";
//print_r($resultsell); 

//order end
   $kali=json_decode($result1, true);

$ids=$kali['id'];
$sides=$kali['side'];
$prices=$kali['price'];
$quantitys=$kali['quantity'];
echo "kali"; echo "$ids"; 
$date = Date("Y-m-d H:i:s");
//insert order details
if ($quantitys > $sq0){
$querysell = mysqli_query($connection,"INSERT INTO trade(side,price,quantity,quantity1,quantity2,date) VALUES ('$sides','$prices','$quantitys','$quantitys','0','$date')");

$uptrade1=mysqli_query($connection, "update trade1 set symbol='$avp' ,sellprice='$prices', type = '5' where clientOrderId ='$av' AND id='$sid'");
}
//sell end 
    
}
?>
<?php
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
$date = DATE("Y-M-D H:i:s");
$sideup = mysqli_query($connection, "update trade1 set side = '$date' order by id desc limit 1") ;
?>
</body></html>
