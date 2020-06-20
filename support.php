support and resistance count on DOGEBTC 1 hour candle<br>
<?php
include "connect.php"; ?>
<?php
          $date = DATE("Y-m-d");
  $sql123 = "TRUNCATE TABLE dogebtccandle";

if ($connection->query($sql123) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $connection->error;
}         $json = "https://api.hitbtc.com/api/2/public/candles/DOGEBTC?peried=H4&sort=DESC&limit=20"; //getting the file content
          $query = '';
          $data = file_get_contents($json); //Read the JSON file in PHP
          $array = json_decode($data, true); //Convert JSON String into PHP Array
          foreach($array as $row) //Extract the Array Values by using Foreach Loop
          {
           $query .= mysqli_query($connection,"INSERT INTO dogebtccandle(min,max) VALUES ('".$row["min"]."', '".$row["max"]."'); "); 
          }
                       ?>
<br><?php
$resistance = mysqli_fetch_array(mysqli_query($connection,"select * from support where type = 'max' order by count desc limit 1"));
echo $resistance['price'];
?>
<?php
$sql1234 = mysqli_query($connection,"TRUNCATE TABLE support");
echo "<br>Resistance count <br>
<table border=1><tr><td>id</td><td>Resistance</td><td>count</td></tr>";
$sql = "SELECT id ,max, COUNT(max) tot FROM dogebtccandle group by max having count(max) > 1 order by tot desc";
$result = $connection->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
echo "<tr><td>" . $row["id"]. "</td><td> " . $row["max"]. "</td><td>" . $row["tot"]. "</td><tr>";
$inmax = mysqli_query($connection,"insert into support (type,price,count,pass) values ('max','".$row["max"]."', '".$row["tot"]."','0')");
}}
else {echo "count no";}

?>

<?php
echo "<table border=1><tr><td>id</td><td>support</td><td>count</td></tr>";
echo "support count<br>";
$sql1 = "SELECT id ,min, COUNT(min) total FROM dogebtccandle group by min having count(min) > 1 order by total desc";
$result1 = $connection->query($sql1);
if ($result->num_rows > 0) {
    while($row1 = $result1->fetch_assoc()){
echo "<tr><td>" . $row1["id"]. "</td><td> " . $row1["min"]. "</td><td>" . $row1["total"]. "</td><tr>";
$inmax = mysqli_query($connection,"insert into support (type,price,count,pass) values ('min','".$row1["min"]."', '".$row1["total"]."','0')");
}}
else {echo "NO count ";}

?> 
<?php
$support = mysqli_fetch_array(mysqli_query($connection,"select * from support where type = 'min' order by count desc"));
echo $support['price'];
?>
