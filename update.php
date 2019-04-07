<?php

if(isset($_POST["Submit"]))
{
   if( isset($_POST["new20"]) || isset($_POST["new50"]))
   {    
		(int)$new20 = $_POST["new20"];
		(int)$new50 = $_POST["new50"];
        
		$hostname_DB = "127.0.0.1";
		$database_DB = "atm";
		$username_DB = "root";
		$password_DB = "";

		try 
		{
		   $CONNPDO = new PDO("mysql:host=".$hostname_DB.";dbname=".$database_DB.";charset=UTF8", $username_DB, $password_DB, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3));
		} 
		catch (PDOException $e) 
		{
		   $CONNPDO = null;
		}
		if ($CONNPDO != null) 
		{  
	       if( $new20 > 0 || $new50 > 0 )
		   {
				
				if(isset($new20) && $new20 > 0 )
				{
					$updata_PRST = $CONNPDO->prepare("UPDATE cash SET  cash20 = :new20 WHERE id = 1 ;");
					$updata_PRST->bindValue(":new20",$new20);
					$updata_PRST->execute() or die($CONNPDO->errorInfo());
				}
				if(isset($new50) && $new50 > 0 )
				{
					$updata_PRST = $CONNPDO->prepare("UPDATE cash SET  cash50 = :new50 WHERE id = 1 ;");
					$updata_PRST->bindValue(":new50",$new50);
					$updata_PRST->execute() or die($CONNPDO->errorInfo());
				}
			 
				$response = "Status has been successfully updated!";
			 
			}
			else
			{
			  $response = "please insert legit sums";
			}			  
		 }
         else
		 {
			 $response = "no pdo connection";
		 }		 
	}
    else
	{
		$response = "Please insert credentials"; 
	}	
 
}
else
{
	$response = "";
}
 ?>
 
 <!DOCTYPE html>
 <html>
 <head>
  <title>ATM cash Update</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
 <center>
	<div class="container">
	<h2 style="color:red;">Cash Update!</h2>
	<hr>
	<h4 style="color:gray;" >Renew the database!</h4>
	<hr>
	<form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" >
	<h5 style="color:gray;" >New $20!</h5>
	<br>
	<input class="form-control" id="usr" type="number" name="new20" style="text-align:center;" placeholder="Insert new quantity of $20 notes here!">
	<br><br>
	<h5 style="color:gray;" >New $50!</h5>
	<br>
	<input class="form-control" id="usr" type="number" name="new50" style="text-align:center;" placeholder="Insert new quantity of $50 notes here!">
	<br><br>
	<input class="btn btn-primary" type="submit" name="Submit" value="Start!" >
	</form> 
	<br>
	<hr>
	<br>
	<?php echo $response; ?>
</center>
 </body>
 </html>