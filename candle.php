30 minute doge btc candle<br>
<?php
include "connect.php";
        	$json = file_get_contents("https://api.hitbtc.com/api/2/public/candles/DOGEBTC?period=M30&limit=20", true); //getting the file content
		$decode = json_decode($json, true); //getting the file content as array
		
?>
	<html>  
      <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">  
           <title>DOGE-BTC 30 MINUTES LAST 20 CANDLE Data</title> 
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
     <style>
   
   .box
   {
    width:750px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:100px;
   }
  </style>
      </head>  
      <body>  
        <div class="container box">
          <h3 align="center">DOGE-BTC 30 MINUTES LAST 20 CANDLE Data</h3><br />
          <?php
         $sql123 = "TRUNCATE TABLE dogebtccandle  ";
         if ($connection->query($sql123) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $connection->error;
} 
          $query = '';
          $table_data = '';
          $date = Date("Y-m-d H:i:s");
          $array = json_decode($json, true); //Convert JSON String into PHP Array
          foreach($array as $row) //Extract the Array Values by using Foreach Loop
          {
           $query .= "INSERT INTO dogebtccandle(open, close, min,max,timestamp,date) VALUES ('".$row["open"]."', '".$row["close"]."','".$row["min"]."', '".$row["max"]."','".$row["timestamp"]."','$date'); ";  // Make Multiple Insert Query 
           $table_data .= '
            <tr>
       <td>'.$row["open"].'</td>
       <td>'.$row["close"].'</td>
       <td>'.$row["min"].'</td>
       <td>'.$row["max"].'</td>
       
      </tr>
           '; //Data for display on Web page
       
          }
          if(mysqli_multi_query($connection, $query)) //Run Mutliple Insert Query
    {
    //echo '<h3>DOGE-BTC 30 MINUTES LAST 20 CANDLE Data</h3><br />';
     echo '
      <table class="table table-bordered">
        <tr>
         <th width="25%">open</th>
         <th width="25%">close</th>
         <th width="25%">low</th>
         <th width="25%">high</th>
        </tr>
     ';
     echo $table_data;  
     echo '</table>';
          }

          ?>
          
     <br />
         </div>  
         
      </body>  
 </html>  
 
      </body>  
 </html>  
 
