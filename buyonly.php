<?php
include "connect.php";
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
$url = "https://api.hitbtc.com/api/2/public/ticker/DOGEBTC";
$dataDOGEBTC = json_decode(file_get_contents($url), true);
$symbol=$dataDOGEBTC['symbol'];
//$bid=$dataDOGEBTC['bid'];
$ask=$dataDOGEBTC['ask'];
$date = Date("Y-m-d H:i:s");

$sell=$ask*0.32/100;
$sell1=number_format($sell,11);
$sellp=$ask+$sell1;
$sellprice=number_format($sellp,11);
$amount="200";

echo "ask"; echo $ask; echo"<br>"; 
echo "sell 1 0.32% "; echo $sell1; echo "<br>";
echo "price P+p "; echo $sellprice; echo "<br>";

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
$noorder=$sqltup['wait'];
$noorder1=$noorder+1;

$waitprice0=$sqltup['price'];
$waitprice1 = $waitprice0 - 0.0000000002;
$waitprice=number_format($waitprice1,11) ;
echo "wait price ",$waitprice;

echo "BTC last Bal ", $lastbal ;
echo "<br> NO ORDER wait VALUE " ; echo  $noorder ;echo"<br>";
    //checking no order wait value
$querynoorder = mysqli_query($connection,"update trade1 set wait ='$noorder1' where id='$idu'");

?>
<?php
if ($available > 0.00003) {
if ($available > $lastbal)
{ 
    if ($ask < 0.00000032)
    {
        $sql = "select type from trade1 where type = '0' order by id desc limit 1";
        if ($connection->query($sql) === TRUE) { echo " sell me first" ;}
        else {
        echo "buy normal"  ; echo "<br>";
//insert data
//order code
$symbol = "DOGEBTC";
$side = "buy";
$type = "limit";
$price= "$ask";
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

} }
else { echo "sorry normal buy price is high"; }
    
} else { 
//no order count more then 15

if ($noorder > 28) { 
            if ($ask < $waitprice)
    { echo "  Wait ok ";
        if ($ask < 0.0000003){echo "buy waiting order" ; echo "<br>";
//order code
$symbol = "DOGEBTC";
$side = "buy";
$type = "limit";
$price= "$ask";
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

$ram=json_decode($result, true);

$ids=$ram['id'];
$sidebid=$ram['side'];
$pricebid=$ram['price'];
$quantity123=$ram['quantity'];
$bid123=$ram['clientOrderId'];
echo "ram buy"; echo "$pricebid"; 
//insert order details
$queryno = mysqli_query($connection,"INSERT INTO trade1(price,sellprice,quantity,date,clientOrderId,wait,type,lastbal) VALUES ('$pricebid','$sellprice','$quantity123','$date','$bid123','0','0','$available')");

//buy end 

}

}
else { echo "sorry wait price is high"; }
    }
 else {
    echo "wait value more then 0 ";}
    
}}
else {echo "BTC less then 0.00003";}
?>
