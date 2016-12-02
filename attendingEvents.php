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

	#event{
		display: block;
		margin: 10px auto;
		width: 100%;
		border: 2px solid black;
		float: left;
		border-radius: 20px;
		padding: 5px 10px 10px 10px;
		background-color: #F0FFF0;
		background-color: #ABEBC6;
		color: black;

	}

	#eventname{
		color: white;
		font-size: 25px;
		font-weight: bold;
		text-align: center;
		background-color: #1E8449;
		padding: 4px;
	}

	#lineOne{
		width: 100%;
		padding: 4px;
	}

	#username{
		width: 50%;
		float: left;
		font-size: 20px;
		font-weight: bold;
	}

	#count{
		width: 50%;
		float: right;
		font-weight: bold;
	}
	
	#lineTwo{
		width: 100%;
		
		float: left;
		font-size: 20px;
		padding: 4px;
	}

	#lineThree{
		width: 100%;
		padding: 4px;
		font-size: 20px;
	}

	#date{
		width: 50%;
		float: left;
		
	}

	#time{
		width: 50%;
		float: right;	
	}
	
	#lineFour{
		width: 100%;
		float: left;
		padding: 4px;
		font-size: 20px;
	}

	#location{
		color: black;		
	}

	#lineFive{
		width: 100%;
		padding: 4px;
		
	}
	#attend button{
		width: 20%;
		float: right;
		background-color: #2ECC71;
		font-weight: bold;
	}
	#attend button:hover{
		transform: scale(1.1);
	}
	body{
		position: absolute;
		top: 120px;
		width: 100%;
		background-color: #F0FFFF;
		background: url('/pickupsports2/images/opuR60.jpg') no-repeat center center fixed;
		background-size: cover;

	}

	#wall{
		width: 80%;
		margin: 10px auto;
	}

	h3{
		margin: 0 auto;
		width: 80%;
		color: white;
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
<?php

$servername = "localhost";
$username = "root";
$password = "";

	if(isset($_POST['logout'])){
		header("Location: /pickupsports2/logout.php");
	}	
	
	if(isset($_POST['profile'])){
		header("Location: /pickupsports2/userHome2.php");
	}


try{

	$conn = new PDO("mysql:host=$servername;dbname=pickupsports2", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if(isset($_POST['cancelEventButton']))
	{
		$sqlRemove = $conn->prepare("DELETE FROM userEventsLink WHERE userID=? and eventID=?");
		$sqlRemove->execute(array($_SESSION['userID'], $_POST['eventID']));

		$sqlUpdate = $conn->prepare("UPDATE users SET attendedEvents=attendedEvents-? WHERE id=?");
		$sqlUpdate->execute(array(1, $_SESSION['userID']));

		$sqlUpdate2 = $conn->prepare("UPDATE events SET count=count+? WHERE id=?");
		$sqlUpdate2->execute(array(1, $_POST['eventID']));

		header("Location: /pickupsports2/attendingEvents.php");		

	}	

	echo "<h3>Events I am attending are:</h3>";

	$sqlOuter = $conn->prepare("SELECT * FROM userEventsLink WHERE userID=?");
	$sqlOuter->execute(array($_SESSION['userID']));
	echo "<div id='wall'>";
	while($rowOuter = $sqlOuter->fetch(PDO::FETCH_ASSOC)){

		$sqlInner = $conn->prepare("SELECT * FROM events WHERE id=? and date>=CURDATE()");
		$sqlInner->execute(array($rowOuter['eventID']));
		while($row = $sqlInner->fetch(PDO::FETCH_ASSOC)){

			$eventID = $row['id'];
			$userID = $row['user'];
			$sportID = $row['sport'];
			$partCount = $row['count'];
			$desc = $row['description'];
			$street = $row['street'];
			$city = $row['city'];
			$state = $row['state'];
			$date = $row['date'];
			$time = $row['time'];
			
			$sqlQuery2 = $conn->prepare("SELECT username FROM users WHERE id=?");
			$sqlQuery2->execute(array($userID));
			$row1 = $sqlQuery2->fetch(PDO::FETCH_ASSOC);
			$usernameDB = $row1['username'];

			$sqlQuery3 = $conn->prepare("SELECT sportname FROM sports WHERE id=?");
			$sqlQuery3->execute(array($sportID));
			$row2 = $sqlQuery3->fetch(PDO::FETCH_ASSOC);
			$sportnameDB = $row2['sportname'];
			
			echo "<div id='event'>";
				echo "<div id='eventname'>".$sportnameDB."</div>";
				echo "<div id='lineOne'>";
					echo "<div id='username'> Organizer Name: ".$usernameDB."</div>";
					echo "<div id='count'> Participants Required: ".$partCount."</div>";
				echo "</div>";
				echo "<div id='lineTwo'>";
					echo "<div id='description'>".$desc."</div>";
				echo "</div>";
				echo "<div id='lineThree'>";
					echo "<div id='date'> Event Date: ".$date."</div>";
					echo "<div id='time'> Event Time: ".$time."</div>";
				echo "</div>";
				echo "<div id='lineFour'>";
					$address = $street.", ".$city.", ".$state." ";
					echo "<div id=location> Location: ".$address."</div>";
				echo "</div>";
				echo "<div id='lineFive'>";
					echo "<div id='attend'><form action='' method='post'><input type='hidden' name='eventID' value=".$eventID."><button type='submit' name='cancelEventButton'>Cannot Attend</button></form></div>";
				echo "</div>";
			echo "</div>";			
		}
		
	}	
	echo "</div>";


}

catch(PDOException $e)
{
	echo $e->getMessage();
}
$conn = null;
?>

</body>
</html>