<?php
include "connect.php";
define('TIMEZONE', 'Asia/kolkata');
date_default_timezone_set(TIMEZONE);
$url = "https://api.hitbtc.com/api/2/public/ticker/DOGEBTC";
$dataDOGEBTC = json_decode(file_get_contents($url), true);
$symbol=$dataDOGEBTC['symbol'];
$bid=$dataDOGEBTC['bid'];
$ask=$dataDOGEBTC['ask'];

echo "bid : "; echo $bid; echo "<br>"; 
echo "ask :"; echo  $ask; echo "<br>";
echo "buy :";  echo $bid; echo "<br>";
//echo "sell price :"; echo $sellprice; echo "<br>";


$sell=$ask*0.31/100;
$sell1=number_format($sell,11);
$sellp=$ask+$sell1;
$sellprice=number_format($sellp,11);
$amount="200";

echo "5+ detailts:<br>";
echo "ask"; echo $ask; echo"<br>"; 
echo "sell 1 0.32% "; echo $sell1; echo "<br>";
echo "price P+p "; echo $sellprice; echo "<br>";

?>
<?php
$date = Date("Y-m-d H:i:s");
$chbal = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($chbal, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY
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

$sqlbal= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 where type = '0' ORDER BY price DESC LIMIT 1"));
$sqlbnor= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 where type = '2' ORDER BY id DESC LIMIT 1"));
$sqltup= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 ORDER BY id DESC LIMIT 1"));

$idu=$sqltup['id'];
$lastbal=$sqltup['clientOrderId'];
$noorder=$sqltup['wait'];
$noorder1=$noorder+1;
$sellprice1=$sqlbal['sellprice'];

$bnorsellprice=$sqlbnor['sellprice'];
$bnorprice=$sqlbnor['price'];
$tupprice=$sqlbal['price'];

echo "BTC last Bal ", $lastbal ;
echo "<br> NO ORDER wait VALUE " ; echo  $noorder ;echo"<br>";
if ($available > $lastbal)
{ 
    if ($ask < 0.000000299)
    {
        echo "buy normal"  ; echo "<br>";
//insert data
$query = mysqli_query($connection,"INSERT INTO trade1(price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$ask','$sellprice','$amount','$amount','0','$date','$available','0','2')");
//order code
$symbol = "DOGEBTC";
$side = "buy";
$type = "limit";
$price= "$ask";
$quantity="$amount";
$timeInForce= "GTC"; 
$date = Date("Y-m-d H:i:s");

$ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY 
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
//order buy end

//start sell order

$symbol1   = "DOGEBTC";
$side1= "sell";
$type1= "limit";
$price1=$sellprice;
$quantity1="$amount";
$timeInForce1= "GTC"; // GET EMAIL INTO VAR

$ch1 = curl_init();
//do a post
curl_setopt($ch1,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch1, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY 
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
    
} else { 
    //checking no order wait value
$querynoorder = mysqli_query($connection,"update trade1 set wait ='$noorder1' where id='$idu'");}

//no order count more then 15

if ($noorder > 120) {
            if ($ask < 0.000000299)
    {
        echo "buy no order" ; echo "<br>";
//insert data
$queryno = mysqli_query($connection,"INSERT INTO trade1(price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$bid','$sellprice','$amount','$amount','0','$date','$available','0','0')");
//order code
$symbol = "DOGEBTC";
$side = "buy";
$type = "limit";
$price= "$bid";
$quantity="$amount";
$timeInForce= "GTC"; 
$date = Date("Y-m-d H:i:s");

$ch = curl_init();
//do a post
curl_setopt($ch,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY 
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


//waiting sell order start

//sell order

$symbol1   = "DOGEBTC";
$side1= "sell";
$type1= "limit";
$price1= "$sellprice";
$quantity1="$amount";
$timeInForce1= "GTC"; // GET EMAIL INTO VAR

$ch1 = curl_init();
//do a post
//curl_setopt($ch1,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch1, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY 
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
 else {
    echo "wailt value more then 0 ";
    //sell balance update start
$date = Date("Y-m-d H:i:s");
$chbal1 = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($chbal1, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY
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
         $querybal1 = mysqli_query($connection,"UPDATE balance set currency='".$data1[$key1]['currency']."' , available='".$data1[$key1]['available']."' , reserved = '".$data1[$key1]['reserved']."' , date='$date' where currency='DOGE'");
          }}
	//sell balance update end
	// sell order start
	
	$sqlbal1sell = "SELECT * FROM balance where currency='DOGE' AND available >= '$amount' ";
$result123sell = $connection->query($sqlbal1sell);
if ($result123sell->num_rows > 0) {
    // output data of each row
    while($rowsell = $result123sell->fetch_assoc()) {
      //buynor code start
      $sellnormal = "SELECT * FROM trade1 where type='2'";
$resultsellnormal = $connection->query($sellnormal);
if ($resultsellnormal->num_rows > 0) 
    { echo "buy normal high"; echo $bnorprice;
    //buy nor sell
    //order on
$symbolsell = "DOGEBTC";
$sidesell = "sell";
$typesell = "limit";
$pricesell="$bnorsellprice";
$quantitysell="$amount";
$timeInForcesell= "GTC"; 

$ch1s = curl_init();
//do a post
curl_setopt($ch1s,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch1s, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY 
curl_setopt($ch1s, CURLOPT_POST,1);
curl_setopt($ch1s,CURLOPT_POSTFIELDS,"symbol=$symbolsell&side=$sidesell&price=$pricesell&quantity=$quantitysell&type=$typesell&timeInForce=$timeInForcesell");
curl_setopt($ch1s, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch1s, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch1s, CURLOPT_HTTPHEADER, array('accept: application/json'));
//curl_setopt($chs,CURLOPT_HTTPHEADER,$header);
$resultsell=curl_exec($ch1s);
curl_close($ch1s);
$resultsell=json_decode($resultsell);
echo"<pre>";
print_r($resultsell); 

//order end

//update trade table

$sqlupd = "SELECT * FROM balance where currency='DOGE' AND available >= '$amount' ";
$resultupd = $connection->query($sqlupd);
if ($resultupd->num_rows > 0) {
    // output data of each row
    while($rowupd = $resultupd->fetch_assoc()) {
    //$sql3 ="UPDATE trade1 SET quantity1='0', quantity2='$amount',type='1' WHERE id='$idu' ";
       
        $sql3 ="UPDATE trade1 SET quantity1='0', quantity2='$amount',type='3' WHERE type='2' order by id desc ";
if ($connection->query($sql3) === TRUE) {
    echo "buy nor record updated successfully";
} else {
    echo "Error: " . $sql3 . "<br>" . $connection->error;
} }
// buy nor sell end
    
}else { echo "tup high"; echo $tupprice;
    echo "id: " . $rowsell["id"]. " - Name: " . $rowsell["currency"]. " -available " . $rowsell["available"]." -reserved" .$rowsell["reserved"]. "<br>";
//order on
$symbolsell = "DOGEBTC";
$sidesell = "sell";
$typesell = "limit";
$pricesell="$sellprice1";
$quantitysell="$amount";
$timeInForcesell= "GTC"; 

$ch1s = curl_init();
//do a post
curl_setopt($ch1s,CURLOPT_URL,"https://api.hitbtc.com/api/2/order");
curl_setopt($ch1s, CURLOPT_USERPWD, 'api_key:secret_key'); // API AND KEY 
curl_setopt($ch1s, CURLOPT_POST,1);
curl_setopt($ch1s,CURLOPT_POSTFIELDS,"symbol=$symbolsell&side=$sidesell&price=$pricesell&quantity=$quantitysell&type=$typesell&timeInForce=$timeInForcesell");
curl_setopt($ch1s, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch1s, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch1s, CURLOPT_HTTPHEADER, array('accept: application/json'));
//curl_setopt($chs,CURLOPT_HTTPHEADER,$header);
$resultsell=curl_exec($ch1s);
curl_close($ch1s);
$resultsell=json_decode($resultsell);
echo"<pre>";
print_r($resultsell); 

//order end

//update trade table

$sqlupd = "SELECT * FROM balance where currency='DOGE' AND available >= '$amount' ";
$resultupd = $connection->query($sqlupd);
if ($resultupd->num_rows > 0) {
    // output data of each row
    while($rowupd = $resultupd->fetch_assoc()) {
    //$sql3 ="UPDATE trade1 SET quantity1='0', quantity2='$amount',type='1' WHERE id='$idu' ";
       
        $sql3 ="UPDATE trade1 SET quantity1='0', quantity2='$amount',type='1' WHERE type='0' order by price desc ";
if ($connection->query($sql3) === TRUE) {
    echo "tup sell record updated successfully";
} else {
    echo "Error: " . $sql3 . "<br>" . $connection->error;
}
}}
// buynor code end
        
       
//end
    }
} else {
    echo "0 ETHBTC sell order place doge balance low";
}
//end


    }
} else {
    echo "0 ETH balance low";
}
// trade table update end
}
?>
