<?php
include "connect.php";
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
$date = Date("Y-m-d H:i:s");

$support = mysqli_fetch_array(mysqli_query($connection,"select * from support where type = 'min' AND pass = '0' order by count desc"));
$buy = $support['price'];

$sell=$buy*0.33/100;
$sell1=number_format($sell,11);
$sellp=$buy+$sell1;
$sellprice=number_format($sellp,11);
$amount="10";

echo "buy"; echo $buy; echo"<br>"; 
echo "sellprice "; echo $sellprice; echo "<br>";

?>
<?php
$chbal = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($chbal, CURLOPT_USERPWD, 'API_KEY:SECRET_KEY'); // API AND KEY
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
              $available=$data[$key]['available'];
         $querybal = mysqli_query($connection,"UPDATE balance set currency='".$data[$key]['currency']."' , available='".$data[$key]['available']."' , reserved = '".$data[$key]['reserved']."' , date='$date' where currency='BTC'");
          }}
	?>
<?php
echo "BTC Bal ", $available ;
$sqltup= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 ORDER BY id DESC LIMIT 1"));

$idu=$sqltup['id'];
$lastbal=$sqltup['lastbal'];

echo "BTC last Bal ", $lastbal ;
echo "<br>";
?>
<?php
if ($available > 0.000054) {
if ($available > $lastbal)
{ 
    if ($buy < 0.00000031)
    {
        echo "buy normal"  ; echo "<br>";
//insert data
//order code
$symbol = "DOGEBTC";
$side = "buy";
$type = "limit";
$price= "$buy";
$quantity="$amount";
$timeInForce= "GTC"; 

$ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch, CURLOPT_USERPWD, 'API_KEY:SECRET_KEY'); // API AND KEY 
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
$query = mysqli_query($connection,"INSERT INTO trade1(price,sellprice,quantity,date,clientOrderId,wait,type,lastbal) VALUES ('$priceask','$sellprice','$quantity123','$date','$bid123','0','0','$available')");

//buy end 
//update support
$upsupport = mysqli_query($connection,"update support set pass = '1' where id = '$support[id]'");

} 
else { echo "sorry buy price is high"; }
    
} else { echo "wait first order not sell"; }
    }
 else { echo "BTC less then 0.000054";}
?>
