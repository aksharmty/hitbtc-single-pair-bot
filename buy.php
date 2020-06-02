<?php
include "connect.php";
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
 $url = "https://api.hitbtc.com/api/2/public/ticker/DOGEBTC";
$dataDOGEBTC = json_decode(file_get_contents($url), true);
$ask=$dataDOGEBTC['ask'];

echo "ask : ", $ask;
$date = Date("Y-m-d H:i:s");
$amount="100";
$amount1 = $amount-1;
echo "amt", $amount; echo "amt -1 :", $amount1; 
?>

<?php
//btc balance start
$chbal = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($chbal, CURLOPT_USERPWD, 'cHxtLRjcqNVLu7_rZiORQMtbkhD-ZilR:trkn4Y8t3KxpJUVvJjoZRTnkfSnHp-5K'); // API AND KEY
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
//btc balance end
?>
<?php //last bal & wait time start
$sqlbal= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM balance where currency='BTC' "));
$available=$sqlbal['available'];
echo "BTC Bal ", $available ;
$sqlbnor= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 where type='0' ORDER BY price DESC LIMIT 1"));
$sqltup= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 ORDER BY id DESC LIMIT 1"));

$idu=$sqltup['id'];
$lastbal=$sqltup['lastbal'];
$noorder=$sqltup['wait'];
$noorder1=$noorder+1;
$bnorid=$sqlbnor['id'];

$waitprice0=$sqltup['price'] ;
$waitprice1 = $waitprice0*1/100 ;
$waitprice2=number_format($waitprice1,11);
$waitprice3=$waitprice0 - $waitprice2 ;
$waitprice=number_format($waitprice3,11) ;
//echo "wait test ", $waitprice3 ;

echo "BTC last Bal ", $lastbal ;
echo "<br> NO ORDER wait VALUE " ; echo  $noorder ;echo"<br>";
    //checking no order wait value
$querynoorder = mysqli_query($connection,"update trade1 set wait ='$noorder1' where id='$idu'");
//last bal & wait time end
?>	
<?php
if ($available > 0.00003) {
if ($available > $lastbal)
{ 
    if ($ask < 0.00000032)
    {
        echo "buy normal"  ; echo "<br>";
$symbol   = "DOGEBTC";
$side = "buy";
$type = "limit";
$price="0.00000030";
//$price= "$awb1";
$quantity="$amount";
$timeInForce= "GTC"; // GET EMAIL INTO VAR

$ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch, CURLOPT_USERPWD, 'cHxtLRjcqNVLu7_rZiORQMtbkhD-ZilR:trkn4Y8t3KxpJUVvJjoZRTnkfSnHp-5K'); // API AND KEY
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"symbol=$symbol&side=$side&price=$price&quantity=$quantity&type=$type&timeInForce=$timeInForce");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json'));
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
if($quantity123 > $amount1){
$query = mysqli_query($connection,"INSERT INTO trade1(price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type,lastbal) VALUES ('$ask','-','$quantity123','$quantity123','0','$date','$bid123','0','0','$available')");
}
//buy end 
}
else { echo "sorry normal buy price is high"; }
    
} else { 
//if($noorder > 30) {
if ($ask > 0.00000025 && $ask < $waitprice)
    { echo "  Wait  ", $waitprice;
        if ($bid < 0.0000003){echo "buy waiting order" ; echo "<br>";
//order code
$symbol = "DOGEBTC";
$side = "buy";
$type = "limit";
//$price= "$awb1";
$price="0.00000030";
$quantity="$amount";
$timeInForce= "GTC"; 
$date = Date("Y-m-d H:i:s");

$ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch, CURLOPT_USERPWD, 'cHxtLRjcqNVLu7_rZiORQMtbkhD-ZilR:trkn4Y8t3KxpJUVvJjoZRTnkfSnHp-5K'); // API AND KEY 
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
//$result=json_decode($result);
//echo"<pre>";
//print_r($result) ;

$ram=json_decode($result, true);

$ids=$ram['id'];
$sidebid=$ram['side'];
$pricebid=$ram['price'];
$quantity123=$ram['quantity'];
$bid123=$ram['clientOrderId'];
echo "ram buy"; echo "$pricebid"; 
//insert order details
if($quantity123 > $amount1){
$queryno = mysqli_query($connection,"INSERT INTO trade1(price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type,lastbal) VALUES ('$ask','-','$quantity123','$quantity123','0','$date','$bid123','0','0','$available')");
}
//buy end 


            
        }}}}
//}
?>

<?php
//print all
echo "ask :"; echo  $ask; echo "<br>";
echo "wait price", $waitprice;
?>
