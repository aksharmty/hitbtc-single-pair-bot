<?php
// fetch volume and place buy and sell order 
?>
<?php
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
include "connect.php"; 

//vol check
$sql123 = mysqli_query($connection,"TRUNCATE TABLE vol");

$jsonFile="https://api.hitbtc.com/api/2/public/orderbook/DOGEBTC?limit=13";
$jsondata = file_get_contents($jsonFile);
$data = json_decode($jsondata, true);

$array_data = $data['ask'];
$array_data1 = $data['bid'];
//ask
foreach ($array_data as $row) {
    $volask = mysqli_query($connection,"INSERT INTO vol (side, price,size) VALUES ('ask','" . $row["price"] . "', '" . $row["size"] . "')");}
//bid
foreach ($array_data1 as $row1) {
    $svolbid = mysqli_query($connection,"INSERT INTO vol (side,price,size) VALUES ('bid','" . $row1["price"] . "', '" . $row1["size"] . "')");}
// check vol value
$sqlask = mysqli_fetch_array(mysqli_query($connection,"SELECT *  FROM vol where side = 'ask' order by -size limit 1"));
$ask0 = $sqlask["price"] - '0.00000000001'; echo " - ASK D: " . $sqlask["price"]. "<br>size : " . $sqlask["size"]. "<br>";
$ask1 = number_format($ask0,11); $ask = $ask1;
 
 $sqlbid = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM vol where side = 'bid' order by -size limit 1"));
$bid0 = $sqlbid["price"] + '0.00000000030'; echo " - BID D: " . $sqlbid["price"]. "<br>size : " . $sqlbid["size"]. "<br>";
$bid1 = number_format($bid0,11); $bid = $bid1;  
echo "bid +30  ", $bid;
echo "<br>ASK -1 ",$ask;
// end vol
?>
<?php
$buy = $bid; echo "<br>buy price ",$buy;
$sell04 = $buy+$buy*0.34/100; $sellprice = number_format($sell04,11); echo "<br>sellprice ",$sellprice;
 ?>
 <?php // balance
 $date = Date("Y-m-d H:i:s");
$keyapi = 'API_KEY:SECRET_KEY'; //wrtite your api key
$balurl = 'https://api.hitbtc.com/api/2/trading/balance';
$orderurl = 'https://api.hitbtc.com/api/2/order';
//btc bal
$chbal = curl_init($balurl); 
 curl_setopt($chbal, CURLOPT_USERPWD, $keyapi); // API AND KEY
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
foreach ($data as $key => $value) {
          if (in_array($data[$key]['currency'], $need)) {
              $available=$data[$key]['available'];
         $querybal = mysqli_query($connection,"UPDATE balance set currency='".$data[$key]['currency']."' , available='".$data[$key]['available']."' , reserved = '".$data[$key]['reserved']."' , date='$date' where currency='BTC'");
          }}
echo "<br>BTC Bal ", $available ; echo "<br>";          

//DOGE balance start
$chbal1 = curl_init($balurl); 
 curl_setopt($chbal1, CURLOPT_USERPWD, $keyapi); // API AND KEY
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
foreach ($data1 as $key1 => $value) {
          if (in_array($data1[$key1]['currency'], $need1)) { 
          $dbal=$data1[$key1]['available'];
         $querybal1 = mysqli_query($connection,"UPDATE balance set currency='".$data1[$key1]['currency']."' , available='".$data1[$key1]['available']."' , reserved = '".$data1[$key1]['reserved']."' , date='$date' where currency='DOGE'");
          }}

	//DOGE balance end
echo "doge bal "; echo $dbal;  
// balnce code end
?>
<?php //new amount
$nam0 = $available/$buy; $nam1 = $nam0/5; $nam2 = floor($nam1/10) * 10; $nam3 = $nam2/2; $nam4 = floor($nam3/10) * 10;  //$bamount = $nam4+$quan;
if($nam2 < 10){ $bamount = 10;} else {$bamount = $nam2;}
echo "<br> new buy amount ", $bamount;

$btcl = $bamount*$buy; $btclow = number_format($btcl,11);
//echo "btc low",$btclow;

$qup = mysqli_fetch_array(mysqli_query($connection,"select * from trade0 order by id desc limit 1"));
$waitup1 = $qup['price'] + "0.000000005";
$waitup = number_format($waitup1,11);
$waitdown1 = $qup['price'] - "0.000000004";
$waitdown = number_format($waitdown1,11);
$lastbal=$qup['lastbal'];

echo "<br>BTC last Bal ", $lastbal ;echo "<br>";
echo "wait up price ",$waitup;echo "<br>";
echo "wait down price ",$waitdown;echo "<br>";

$qup1 = mysqli_fetch_array(mysqli_query($connection,"select * from trade0 where type = '0' order by price desc limit 1"));
$idu=$qup1['id'];
$damount0=1;
$damount1=$qup1['quantity'];
if($damount1 > $damount0){ $damount = $damount1; echo "<br>damount1 : ",$damount1;} else { $damount = $damount0; echo "<br>damount0 : ",$damount0;}

$sellprice2= $qup1['sellprice'];
if($ask > $sellprice2){ $sellprice1 = $ask; echo "<br>ASK SELL",$ask;} else { $sellprice1 = $sellprice2; echo "<br>SELL",$sellprice2;}
//echo "sellprice2 ",$sellprice2; echo "sellprice 1 ", $sellprice1;

?>
<?php //order start
$symbol = "DOGEBTC";
$type = "limit";
$price= "$buy";
$price1= "$sellprice1";
$quantityb="$bamount";
$quantityd="$damount";
echo "<br>damount ",$damount;
echo "<br>bamount ",$bamount;
if($price > 0.00000024){
if ($buy < 0.00000030){echo "<br>sellprice less then 30";
 if ($available > $lastbal or $waitdown > $buy) {
    echo "<br>buy normal";
    if($available > $btclow){
    $ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,$orderurl);
curl_setopt($ch, CURLOPT_USERPWD, $keyapi); // API AND KEY 
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"symbol=$symbol&side=buy&price=$price&quantity=$quantityb&type=$type&timeInForce=GTC");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json'));
//curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
$result=curl_exec($ch);
curl_close($ch);
//$result=json_decode($result);
//echo"<pre>";
//print_r($result);
//order buy end
$sita=json_decode($result, true);

$ids=$sita['id'];
$sideask=$sita['side'];
$priceask=$sita['price'];
$quantity123=$sita['quantity'];
$bid123=$sita['clientOrderId'];
echo "sita"; echo "$ids"; 
//insert order details
if($quantity123 == $bamount) { 
$query = mysqli_query($connection,"INSERT INTO trade0(price,sellprice,quantity,date,clientOrderId,dlastbal,type,lastbal) VALUES ('$priceask','$sellprice','$quantity123','$date','$bid123','$dbal','0','$available')");

//buy end 

}
}else{echo "<br>btc low";}
////////
if ($available > $btclow & $waitup < $buy) {
    echo "<br>buy high";
    $ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,$orderurl);
curl_setopt($ch, CURLOPT_USERPWD, $keyapi); // API AND KEY 
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"symbol=$symbol&side=buy&price=$price&quantity=$quantityb&type=$type&timeInForce=GTC");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json'));
//curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
$result=curl_exec($ch);
curl_close($ch);
//$result=json_decode($result);
//echo"<pre>";
//print_r($result);
//order buy end
$sita=json_decode($result, true);

$ids=$sita['id'];
$sideask=$sita['side'];
$priceask=$sita['price'];
$quantity123=$sita['quantity'];
$bid123=$sita['clientOrderId'];
echo "sita"; echo "$ids"; 
//insert order details
if($quantity123 == $bamount) { 
$query = mysqli_query($connection,"INSERT INTO trade0(price,sellprice,quantity,date,clientOrderId,dlastbal,type,lastbal) VALUES ('$priceask','$sellprice','$quantity123','$date','$bid123','$dbal','0','$available')");

//buy end 

}
else{echo "<br>btc low on high buy";}
    
}
}else { echo "<br> no buy";}

    
    
} else {echo "<br>sellprice more then 30";}
} else {echo "buy price wrong";}
if ($dbal >= $damount){
//sell start
echo "sell";
if($price1 > 0.00000024) {

$ch1 = curl_init();
//do a post
curl_setopt($ch1,CURLOPT_URL,$orderurl);
curl_setopt($ch1, CURLOPT_USERPWD, $keyapi); // API AND KEY
curl_setopt($ch1, CURLOPT_POST,1);
curl_setopt($ch1,CURLOPT_POSTFIELDS,"symbol=$symbol&side=sell&price=$price1&quantity=$quantityd&type=$type&timeInForce=GTC");
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
$bid124=$kali['clientOrderId'];
 
//insert order details
if ($quantitys == $damount){ echo "sell by kali";
$querysell = mysqli_query($connection,"INSERT INTO trade(sellprice,quantity,date,clientOrderId) VALUES ('$prices','$quantitys','$date','$bid124')");

$querysellup = mysqli_query($connection,"update trade0 set type ='1' , sell ='$prices' where id ='$idu'");

}
}else {echo "sell price wrong";}
} else {echo "<br>DOGE bal 0";}
?>
<?php 
$dd =mysqli_query($connection, " delete from trade1 where price = ''") ; ?>
