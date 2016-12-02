<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<style>
	*{
		margin: 0;
	}
	#container{
		height: 50px;
	}
	#banner{
		
		height: 50px;
		float: right;
	}
	#banner button{
		font-size: 17px;
		color: white;
		font-weight: bold;
		background-color: Transparent;
		border: none;
		height: 50px;
	}
	#banner button:hover{
		color: green;
		transform: scale(1.1);
	}
	#appBanner{
		width: 100%;
		height: 70px;
		text-align: center;
	}

	#appBanner h1{
		font-size: 50px;
		top: 0;
		color: white;
	}
	#pageTop{
		width: 100%;
		position: fixed;
		top: 0;
		overflow: hidden;
		margin-top: 0px;
		padding: 0;
	}

	
	#subscriptionTable{
		width: 100%;	
		text-align: center;
		border: none;
		font-size: 20px;
		padding: 20px;
		
	}
	#subscriptionTable th{
		font-size: 20px;
		background-color: #239B56;
	}
	#subscriptionTable td{
		height: 30px;
		padding: 10px;
	}
	#subscriptionTable tr:nth-child(even){
		background-color: #ABEBC6;
	}
	#subscriptionTable tr:nth-child(odd){
		background-color: #58D68D;
	}
	#unsubscribeButton{
		width: 100%;
		color: black;
		background-color: Transparent;
		border: none;
		font-size: 15px;
		font-weight: bold;
	}
	#unsubscribeButton:hover{
		color: red;
		transform: scale(1.1);
	}

	body{
		position: absolute;
		top: 120px;
		background-color: #F0FFFF;
		width: 100%;
		background: url('/pickupsports2/images/opuR60.jpg') no-repeat center center fixed;
		background-size: cover;

	}
	#wall{
		width: 80%;
		margin: 10px auto;
	}
	h3{
		margin: 0 auto;
		width: 70%;
		color: white;
	}

	#optionsTable{
		margin: 0 auto;
		width: 50%;
	}
	#optionsTable td{
		
		text-align: center;
	}
	#optionsTable select{
		height: 40px;
		width: 140px;
	}
	#submitButton{
		height: 40px;
		width: 120px;
		border: none;
		background-color: Transparent;
		font-size: 20px;
		font-weight: bold;
		color: white;
	}
	#submitButton:hover{
		transform: scale(1.1);
		color: red;
	}

</style>
</head>
<body>
<div id="pageTop">
<div id="appBanner">
	<h1>Pick Up-Sports</h1>
</div>

<div id="container">
<div id="banner">
	<form action="" method="POST" id="bannerForm">
		<button type="submit" name="profile" form="bannerForm">Profile/Wall</button>
		<button type="submit" name="logout" form="bannerForm">logout</button>
	</form>
</div>	
</div>
</div>
<br><br>

<h3>Add Subscription : </h3>
<br>
<table id="optionsTable">
<form action="" method="post" id="sportSubscibeForm">
<tr>
	<td>
		<select name="sportList" form = "sportSubscibeForm">
		<?php
			$servername = "localhost";
			$username = "root";
			$password = "";

			try{
				$conn = new PDO("mysql:host=$servername;dbname=pickupsports2", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
				$sportRows = $conn->prepare("SELECT * FROM sports");
				$sportRows->execute();
	
				while($row=$sportRows->fetch(PDO::FETCH_ASSOC)){
					$id = $row['id'];
					$name = $row['sportname'];
					echo "<option value=".$id.">".$name."</option>";
				}	
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}

		?>
		</select>
	</td>
	<td>
		<input name="subscribeButton" type = "submit" id="submitButton" value="subscribe">
	</td>
</tr>

</form>
</table>
<br><br>
<h3>Your Subscriptions: </h3>
<?php
	$sqlQuery = $conn->prepare("SELECT * FROM subscriptions WHERE userID=?");
	$sqlQuery->execute(array($_SESSION['userID']));
	echo "<div id='wall'>";
	echo "<table id='subscriptionTable'>";
	echo "<tr>";
		echo "<th>Sport </th>";
		echo "<th>Manage</th>";
	echo "</tr>";
	
	while($row = $sqlQuery->fetch(PDO::FETCH_ASSOC)){
		$sportID = $row['sportID'];
		$sqlInner = $conn->prepare("SELECT * FROM sports WHERE id=?");
		$sqlInner->execute(array($sportID));
		$row = $sqlInner->fetch(PDO::FETCH_ASSOC);
		$sportName = $row['sportname'];

		echo "<tr>";
			echo "<td>".$sportName."</td>";
			echo "<td><form action='' method='post' id='unsubscribeForm'><input type='hidden' name='sportID' value=".$sportID."><input id='unsubscribeButton' type='submit' name='unsubscribeButton' value='Un-Subscribe'></form></td>";
		echo "</tr>";
						
	}
	echo "</table>";
	echo "</div>";
?>
<?php
##handling button click


if(isset($_POST['subscribeButton'])){
	try{
		
		$sqlInsert = $conn->prepare("INSERT INTO subscriptions(userID, sportID) VALUES (?, ?)");
		$sqlInsert->execute(array($_SESSION['userID'], $_POST['sportList']));
		#echo "Subscription Added<br>";
		header("Location: /pickupsports2/subscribeEvents.php");		
	}	
	catch(PDOException $e){
		echo "<script>alert('You have already subscibed for the sport selected.')</script>";
	}
}

if(isset($_POST['unsubscribeButton'])){
	#echo $_POST['sportID']."<br>";
	$sqlDelete = $conn->prepare("DELETE FROM subscriptions WHERE userID=? AND sportID=?");
	$sqlDelete->execute(array($_SESSION['userID'], $_POST['sportID']));
	header("Location: /pickupsports2/subscribeEvents.php");	
}

if(isset($_POST['logout'])){
	header("Location: /pickupsports2/logout.php");
}	
	
if(isset($_POST['profile'])){
	header("Location: /pickupsports2/userHome2.php");
}

$conn = null;
?>
</body>
</html>