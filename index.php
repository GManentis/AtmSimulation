<?php

if(isset($_POST["Submit"]))
{ 
     (int)$money = $_POST["money"];
	 if( $money > 0 )
	 {
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
				  $getdata_PRST = $CONNPDO->prepare("SELECT * FROM cash ");
				  $getdata_PRST->execute() or die($CONNPDO->errorInfo());
				  
				  while($getdata_RSLT = $getdata_PRST->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT))
				  {
					$db50 = $getdata_RSLT["cash50"]; 
					$db20 = $getdata_RSLT["cash20"]; 
				  }
					
					
					$assumption1 = ($money%50)%20 ; // important variable to check if the required sum can be given in $50 and $20 notes or in $50 notes only
					$assumption2 = $money%20;//in case the previous variable is not equal to 0 , then we check if the sum can be given in $20 only
					
					$cash50 = floor($money/50);
					$rest = $money%50; // the modulus that will remain and then must be divided by 20 for the $20 notes
					$cash20 = floor($rest/20);
					
					$cashOnly20 = floor($money/20);
					
					$dbstatus50 = $db50 - $cash50; // the result will be the new value of $50 in db
					$dbstatus20 = $db20 - $cash20;//the result will be the new value of $20 in db
					
					$dbstatusAll20 = $db20 - $cashOnly20;//the new value in case the money is in $20 notes
				
					
					if( $assumption1 == 0 || $assumption2 == 0 )//checking if the transcation can be achieved
					{
						if ( $assumption1 == 0 && $db50 >= $cash50 && $db20 >= $cash20 ) // checking if we get the money in $50 and $20 and if there are enough money for the transaction
						{
							$updata_PRST = $CONNPDO->prepare("UPDATE cash SET cash50 = :new50 , cash20 = :new20 WHERE id = 1 ;"); //updating db with new status
				            $updata_PRST->bindValue(":new50", $dbstatus50);
							$updata_PRST->bindValue(":new20", $dbstatus20);
							$updata_PRST->execute() or die($CONNPDO->errorInfo());
							if(floor($cash20) == 0) // check if $20 notes are necessary 
							{
								$resp = "Transaction is complete! Here is your money <span style=\"color:orange;\"> ".$cash50." notes of $50</span>";
							}
							else
							{
								$resp = "Transaction is complete! Here is your money <span style=\"color:orange;\"> ".$cash50." notes of $50</span> and <span style=\"color:blue;\">".$cash20." notes of $20</span>";
							}
							
						}
						elseif ($assumption2 == 0 && $db20 >= $cashOnly20 ) //check we can get the required sum in $20 notes and if there are enough to cover the transaction
						{
							$updata_PRST = $CONNPDO->prepare("UPDATE cash SET  cash20 = :new20 WHERE id = 1 ;");//update for available $20 notes
							$updata_PRST->bindValue(":new20",$dbstatusAll20);
							$updata_PRST->execute() or die($CONNPDO->errorInfo());
							
							$resp = "Transaction is complete! Here is your money <span style=\"color:blue;\"> ".$cashOnly20." notes of $20 </span> ";
						}
						else
						{
							$resp = "Transaction was not successful,we apologise for the inconvenience";
						}
					}
					else
					{
						$resp = "We apologise but the ATM can fullfill transaction with notes of $50 and $20!!";
					}
					
				}
				else
				{
					$resp = "Transaction is impossible due to database error";
				}
	
	 }
	 else
	 {
		 $resp = "Please insert legit number";
	 }
	 
}
else
{
	$resp = "";
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>ATM Simulation</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<center>
<div class="container">
<h2 style="color:red;">Thank you for using our ATM services! Please insert the amount of money you want to withdraw!</h2>
<hr>
<h4 style="color:gray;" >Insert the amount of money you want to withdraw</h4>
<form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" >
<input class="form-control" id="usr" type="number" name="money" style="text-align:center;" placeholder="Insert number here...(Important note: The ATM completes transaction using notes of $20 and $50!! )">
<br><br>
<input class="btn btn-primary" type="submit" name="Submit" value="Transaction Start!" >
</form> 
<br>
<hr>
<br>
<?php echo $resp; ?>
</center>
</body>
</html>