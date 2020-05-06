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


$sell=$ask*0.32/100;
$sell1=number_format($sell,11);
$sellp=$ask+$sell1;
$sellprice=number_format($sellp,11);
$amount="50";

echo "5+ detailts:<br>";
echo "ask"; echo $ask; echo"<br>"; 
echo "sell 1 0.32% "; echo $sell1; echo "<br>";
echo "price P+p "; echo $sellprice; echo "<br>";

?>
<?php
$date = Date("Y-m-d H:i:s");
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
	?>
<?php
$sqlbal= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM balance where currency='BTC' "));
$available=$sqlbal['available'];
echo "BTC Bal ", $available ;
$sqlbnor= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 where type='0' ORDER BY price DESC LIMIT 1"));
$sqltup= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade1 ORDER BY id DESC LIMIT 1"));

$idu=$sqltup['id'];
$lastbal=$sqltup['clientOrderId'];
$noorder=$sqltup['wait'];
$noorder1=$noorder+1;
$bnorid=$sqlbnor['id'];

$bnorsellprice=$sqlbnor['sellprice'];
//$bnorprice=$sqlbnor['price'];
//$tupprice=$sqlbal['price'];

echo "BTC last Bal ", $lastbal ;
echo "<br> NO ORDER wait VALUE " ; echo  $noorder ;echo"<br>";
?>
<?php
if ($available > 0.000015) {
if ($available > $lastbal)
{ 
    if ($ask < 0.00000032)
    {
        echo "buy normal"  ; echo "<br>";
//insert data
//$query = mysqli_query($connection,"INSERT INTO trade1(price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$ask','$sellprice','$amount','$amount','0','$date','$available','0','0')");
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
//print_r($result);
//order buy end
$sita=json_decode($result, true);

$ids=$sita['id'];
$sideask=$sita['side'];
$priceask=$sita['price'];
$quantityask=$sita['quantity'];
echo "sita"; echo "$ids"; 
//insert order details
$query = mysqli_query($connection,"INSERT INTO trade1(side,price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$sideask','$priceask','$sellprice','$quantityask','$quantityask','0','$date','$available','0','0')");

//buy end 
//sell start
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
curl_setopt($ch1, CURLOPT_USERPWD, 'cHxtLRjcqNVLu7_rZiORQMtbkhD-ZilR:trkn4Y8t3KxpJUVvJjoZRTnkfSnHp-5K'); // API AND KEY 
curl_setopt($ch1, CURLOPT_POST,1);
curl_setopt($ch1,CURLOPT_POSTFIELDS,"symbol=$symbol1&side=$side1&price=$price1&quantity=$quantity1&type=$type1&timeInForce=$timeInForce1");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('accept: application/json'));
$result1=curl_exec($ch1);
curl_close($ch1);

$sita1=json_decode($result1, true);

$ids1=$sita1['id'];
$sideask1=$sita1['side'];
$priceask1=$sita1['price'];
$quantityask1=$sita1['quantity'];
echo "sita1"; echo "$ids1"; 
//insert order details
$query1 = mysqli_query($connection,"INSERT INTO trade(side,price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$sideask1','$priceask1','$sellprice','$quantityask1','$quantityask1','0','$date','$available','0','0')");

//sell end

}
else { echo "sorry normal buy price is high"; }
    
} else { 
    //checking no order wait value
$querynoorder = mysqli_query($connection,"update trade1 set wait ='$noorder1' where id='$idu'");

//no order count more then 15

if ($noorder > 13) {
            if ($bid < 0.00000030199)
    {
        echo "buy waiting order" ; echo "<br>";
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
$quantitybid=$ram['quantity'];
echo "ram buy"; echo "$pricebid"; 
//insert order details
$queryno = mysqli_query($connection,"INSERT INTO trade1(side,price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$sidebid','$pricebid','$sellprice','$quantitybid','$quantitybid','0','$date','$available','0','0')");

//buy end 
//sell start
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
curl_setopt($ch1, CURLOPT_USERPWD, 'cHxtLRjcqNVLu7_rZiORQMtbkhD-ZilR:trkn4Y8t3KxpJUVvJjoZRTnkfSnHp-5K'); // API AND KEY 
curl_setopt($ch1, CURLOPT_POST,1);
curl_setopt($ch1,CURLOPT_POSTFIELDS,"symbol=$symbol1&side=$side1&price=$price1&quantity=$quantity1&type=$type1&timeInForce=$timeInForce1");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
  //return the result of curl_exec,instead
  //of outputting it directly
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('accept: application/json'));
$result1=curl_exec($ch1);
curl_close($ch1);

$ram1=json_decode($result1, true);

$ids2=$ram1['id'];
$sideask2=$ram1['side'];
$priceask2=$ram1['price'];
$quantityask2=$ram1['quantity'];
echo "ram1"; echo "$priceask2"; 
//insert order details
$query1 = mysqli_query($connection,"INSERT INTO trade(side,price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$sideask2','$priceask2','$sellprice','$quantityask2','$quantityask2','0','$date','$available','0','0')");

//sell end



}
else { echo "sorry wait price is high"; }
    }
 else {
    echo "wailt value more then 0 ";}
    
}}
else {echo "BTC less then 0.000015";}
?>
<?php
    
   //sell balance update start
$chbal1 = curl_init('https://api.hitbtc.com/api/2/trading/balance'); 
 curl_setopt($chbal1, CURLOPT_USERPWD, 'cHxtLRjcqNVLu7_rZiORQMtbkhD-ZilR:trkn4Y8t3KxpJUVvJjoZRTnkfSnHp-5K'); // API AND KEY
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

echo "doge bal"; echo $dbal; echo " Om";	
//	$sqlbal1sell = "SELECT * FROM balance where currency='DOGE' AND available  <= '50' ";
//$result123sell = $connection->query($sqlbal1sell);
//if ($result123sell->num_rows > 0) {
if($dbal > 49) {

      //buynor code start
     $sellnormal = "SELECT * FROM trade1 where type='0' order by price desc limit 1";
$resultsellnormal = $connection->query($sellnormal);
if ($resultsellnormal->num_rows > 0) 

    { echo "buy normal high"; 
    //buy nor sell
    
//update trade table
$sql3 ="UPDATE trade1 SET quantity1='0', quantity2='$amount',side='$date' , type='4' WHERE id='$bnorid' AND sellprice='$bnorsellprice' ";
if ($connection->query($sql3) === TRUE) {
    echo "buy nor record updated successfully";
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
curl_setopt($ch1s, CURLOPT_USERPWD, 'cHxtLRjcqNVLu7_rZiORQMtbkhD-ZilR:trkn4Y8t3KxpJUVvJjoZRTnkfSnHp-5K'); // API AND KEY 
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
//$resultsell=json_decode($resultsell);
//echo"<pre>";
//print_r($resultsell); 

//order end
   $kali=json_decode($resultsell, true);

$ids=$kali['id'];
$sides=$kali['side'];
$prices=$kali['price'];
$quantitys=$kali['quantity'];
echo "kali"; echo "$ids"; 
//insert order details
$querysell = mysqli_query($connection,"INSERT INTO trade(side,price,sellprice,quantity,quantity1,quantity2,date,clientOrderId,wait,type) VALUES ('$sides','$prices','$sellprice','$quantitys','$quantitys','0','$date','$available','0','0')");

 
// buy nor sell end
}
else{ echo "0 sell order place doge balance low"; }
//end
}}
// trade table update end
?>
<?php
$sqltr= mysqli_fetch_array(mysqli_query($connection, "SELECT * FROM trade where type ='0' ORDER BY id DESC LIMIT 1"));
$ptr=$sqltr['price'];
echo "$ptr";
$ptr0 = "SELECT * FROM trade1 where sellprice = '$ptr' AND type = '0' ";
$ptr1 = $connection->query($ptr0);
if ($ptr1->num_rows > 0) {
    // output data of each row
    while($rowptr = $ptr1->fetch_assoc()) {
       echo " Abc "; 
$uptrade=mysqli_query($connection, "update trade set type = '4' where price = '$ptr' AND type ='0'");
$uptrade1=mysqli_query($connection, "update trade1 set type = '5' where sellprice ='$ptr' AND type ='0'");
}
}
else { echo "NA";
}
?>