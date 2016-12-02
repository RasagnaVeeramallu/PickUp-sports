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

	#leaderboard{
		width: 80%;
		text-align: center;
		margin: 0 auto;
		border: none;
	}
	#leaderboard th{
		font-size: 20px;
		background-color: #239B56;
		padding: 5px;
	}

	#leaderboard td{
		font-size: 20px;
		height: 30px;
		padding: 10px;
	}
	#leaderboard tr:nth-child(even){
		background-color: #ABEBC6;
	}
	#leaderboard tr:nth-child(odd){
		background-color: #58D68D;
	}
	body{
		position: absolute;
		top: 120px;
		width: 100%;
		background-color: #F0FFFF;
		background: url('/pickupsports2/images/opuR60.jpg') no-repeat center center fixed;
		background-size: cover;

	}
	h2{
		width: 80%;
		margin: 0 auto;
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
<h2>Leader Board :</h2>
<br>
<?php

$servername = "localhost";
$username = "root";
$password = "";

try{
	$conn = new PDO("mysql:host=$servername;dbname=pickupsports2", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	echo "<table id='leaderboard'>";
	echo "<tr>";
		echo "<th>RANK</th>";
		echo "<th>USERNAME</th>";
		echo "<th>SCORE</th>";
		echo "<th>EVENTS HOSTED</th>";
		echo "<th>EVENTS ATTENDED</th>";	
	echo "</tr>";
	
	$sqlDisplay = $conn->prepare("SELECT id, username, hostedEvents, attendedEvents, (hostedEvents*10+attendedEvents*5) AS score FROM users ORDER BY score DESC");
	$sqlDisplay->execute();
	$rank = 1;
	while($row = $sqlDisplay->fetch(PDO::FETCH_ASSOC))
	{
		$id = $row['id'];
		$username = $row['username'];
		$hosted = $row['hostedEvents'];
		$attended = $row['attendedEvents'];
		#$score = $hosted*10 + $attended*5;
		$score = $row['score'];
		echo "<tr>";
			echo "<td>$rank</td>";
			echo "<td>$username</td>";
			echo "<td>$score</td>";
			echo "<td>$hosted</td>";
			echo "<td>$attended</td>";
		echo "</tr>";
		$rank+=1;	 
		
	}
	echo "</table>";
	if(isset($_POST['logout'])){
		header("Location: /pickupsports2/logout.php");
	}	
	
	if(isset($_POST['profile'])){
		header("Location: /pickupsports2/userHome2.php");
	}

}

catch(PDOException $e)
{
	echo $e->getMessage();
}
$conn = null;
?>
</body>
</html>