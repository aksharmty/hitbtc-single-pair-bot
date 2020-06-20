<!DOCTYPE html>
<html>
<head>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 2020 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
</head>
<body>
<?php
include "connect.php";
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
$date = DATE("Y-M-d H:i:s");

$resistance = mysqli_fetch_array(mysqli_query($connection,"select * from support where type = 'max' AND pass = '0' order by count desc limit 1"));
$sell = $resistance['price'];

$ptr0 = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM trade1 where type='0' ORDER BY price DESC LIMIT 1"));
$sid=$ptr0['id']; $amount=$ptr0['quantity']; $sellprice=$ptr0['sellprice'];
echo $sid; echo $amount; echo $sellprice; echo $sell;    
               ?>
<?php 
echo "sell price : ",$sellprice;
If($sellprice > $sell){ echo "sell price 1 :",$sell; $sl3 - $sell; }else { echo $sellprice; $sl3 = $sellprice;}

?>
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
$price1= "$sl3";
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

//order end
   $kali=json_decode($result1, true);

$prices=$kali['price'];
$quantitys=$kali['quantity'];
 
//insert order details
if ($quantitys > $sq0){
$querysell = mysqli_query($connection,"INSERT INTO trade(price,quantity,date) VALUES ('$prices','$quantitys','$date')");

$uptrade1=mysqli_query($connection, "update trade1 set symbol='$avp' ,sellprice='$prices', type = '5' where clientOrderId ='$av' AND id='$sid'");

$upsu=mysqli_query($connection, "update support set pass ='1' where id='$resistance[id]'");
}
//sell end 
    
}
?>
<?php
$sideup = mysqli_query($connection, "update trade1 set side = '$date' order by id desc limit 1") ;
?>
</body></html>
