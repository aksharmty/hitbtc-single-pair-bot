<?php
include "connect.php";
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
$url = "https://api.hitbtc.com/api/2/public/ticker/ETHBTC";
$dataDOGEBTC = json_decode(file_get_contents($url), true);
$symbol=$dataDOGEBTC['symbol'];
$bid=$dataDOGEBTC['bid'];
$ask=$dataDOGEBTC['ask'];

echo "bid : "; echo $bid; echo "<br>"; 
echo "ask :"; echo  $ask; echo "<br>";
echo "buy :";  echo $bid; echo "<br>";
//echo "sell price :"; echo $sellprice; echo "<br>";
//new 5+
$askp5=$ask+0.000000010; 
$askp51=number_format($askp5,11);
$sell=$askp51*0.32/100;
$sell1=number_format($sell,11);
$sellp=$askp51+$sell1;
$sellprice=number_format($sellp,11);

echo "5+ detailts:<br>";
echo "ask"; echo $ask; echo"<br>"; 
echo "ask+5(1) ";  echo $askp51; echo "<br>";
echo "sell 1 0.32% "; echo $sell1; echo "<br>";
echo "price P+p "; echo $sellprice; echo "<br>";

?>
<?php

$date = Date("Y-m-d H:i:s");
$chbal = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($chbal, CURLOPT_USERPWD, 'YOUR_API_KEY:YOUR_SECRET_KEY'); // API AND KEY
 curl_setopt($chbal, CURLOPT_RETURNTRANSFER,1);
 curl_setopt($chbal, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($chbal, CURLOPT_HTTPHEADER, array('accept: application/json'));
 $return = curl_exec($chbal); 
  curl_close($chbal); 
 //print_r($return);
$data = json_decode($return, true);
$need = array(
    //1 =>'DOGE',
    1=>'BTC'
);
foreach ($data as $key => $value) {//Extract the Array Values by using Foreach Loop
          if (in_array($data[$key]['currency'], $need)) {
         $querybal = mysqli_query($connection,"UPDATE balance set currency='".$data[$key]['currency']."' , available='".$data[$key]['available']."' , reserved = '".$data[$key]['reserved']."' , date='$date' where currency='BTC'");
          }}
	?>
<?php
$sqlbal= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM balance where currency='BTC' "));
$available=$sqlbal['available'];
echo "BTC Bal ", $available ;

$sqlbal= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 order by id desc"));
$lastbal=$sqlbal['clientOrderId'];
$noorder=$sqlbal['wait'];
$noorder1=$noorder+1;
echo "BTC last Bal ", $lastbal ;
if ($available > $lastbal)
{ 
    if ($askp51 < 0.0241)
    {
        echo buy ; echo "<br>";
//insert data
$query = mysqli_query($connection,"INSERT INTO trade1(clientOrderId) VALUES ('$available')");
//order code
$symbol = "ETHBTC";
$side = "buy";
$type = "limit";
$price= "$askp51";
//$price=$bid;
//$quantity=$quantity2;
$quantity="0.0013";
$timeInForce= "GTC"; 
$date = Date("Y-m-d H:i:s");

$ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch, CURLOPT_USERPWD, 'YOUR_API_KEY:YOUR_SECRET_KEY'); // API AND KEY 
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"symbol=$symbol&side=$side&price=$price&quantity=$quantity&type=$type&timeInForce=$timeInForce");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json'));
//curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
$result=curl_exec($ch);
curl_close($ch);
$result=json_decode($result);
echo"<pre>";
print_r($result);
//order end

?>
//insert order details

//sell order
<?php
$symbol1   = "ETHBTC";
$side1= "sell";
$type1= "limit";
$price1=$sellprice;
$quantity1="0.0013";
$timeInForce1= "GTC"; // GET EMAIL INTO VAR

$ch1 = curl_init();
//do a post
curl_setopt($ch1,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch1, CURLOPT_USERPWD, 'YOUR_API_KEY:YOUR_SECRET_KEY'); // API AND KEY 
curl_setopt($ch1, CURLOPT_POST,1);
curl_setopt($ch1,CURLOPT_POSTFIELDS,"symbol=$symbol1&side=$side1&price=$price1&quantity=$quantity1&type=$type1&timeInForce=$timeInForce1");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('accept: application/json'));
$result1=curl_exec($ch1);
curl_close($ch1);
$result1=json_decode($result1);
echo"<pre>";
print_r($result1);
}
else { echo "sorry price is high"; }
    
} else { echo "wait" ;
$querynoorder = mysqli_query($connection,"update trade1 set wait ='$noorder1' order by id desc limit 1");}
?>
//no order more then 15
<?php
$sql = "SELECT wait FROM trad1 where wait > 15";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
            if ($askp51 < 0.0241)
    {
        echo buy ; echo "<br>";
//insert data
$query = mysqli_query($connection,"INSERT INTO trade1(clientOrderId) VALUES ('$available')");
//order code
$symbol = "ETHBTC";
$side = "buy";
$type = "limit";
$price= "$askp51";
//$price=$bid;
//$quantity=$quantity2;
$quantity="0.0013";
$timeInForce= "GTC"; 
$date = Date("Y-m-d H:i:s");

$ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch, CURLOPT_USERPWD, 'YOUR_API_KEY:YOUR_SECRET_KEY'); // API AND KEY 
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"symbol=$symbol&side=$side&price=$price&quantity=$quantity&type=$type&timeInForce=$timeInForce");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json'));
//curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
$result=curl_exec($ch);
curl_close($ch);
$result=json_decode($result);
echo"<pre>";
print_r($result);
//order end

?>
//insert order details

//sell order
<?php
$symbol1   = "ETHBTC";
$side1= "sell";
$type1= "limit";
$price1=$sellprice;
$quantity1="0.0013";
$timeInForce1= "GTC"; // GET EMAIL INTO VAR

$ch1 = curl_init();
//do a post
curl_setopt($ch1,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch1, CURLOPT_USERPWD, 'YOUR_API_KEY:YOUR_SECRET_KEY'); // API AND KEY 
curl_setopt($ch1, CURLOPT_POST,1);
curl_setopt($ch1,CURLOPT_POSTFIELDS,"symbol=$symbol1&side=$side1&price=$price1&quantity=$quantity1&type=$type1&timeInForce=$timeInForce1");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('accept: application/json'));
$result1=curl_exec($ch1);
curl_close($ch1);
$result1=json_decode($result1);
echo"<pre>";
print_r($result1);
}
else { echo "sorry price is high"; }
    }
} else {
    echo "0 results";
}
