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
	body{
		position: absolute;
		top: 120px;
		background-color: #F0FFFF;
		background: url('/pickupsports2/images/opuR60.jpg') no-repeat center center fixed;
		background-size: cover;
		color: white;
		font-weight: bold;
	}
	#displayTable{
		font-size: 20px;
		padding: 20px;
		
	}
	#displayTable td{
		height: 25px;
	}
	textarea{
		width: 350px;
		height: 100px;
	}
	select{
		font-size: 15px;
	}
	#createButton{
		height: 40px;
		width: 130px;
		color: black;
		font-weight: bold;
		background-color: #2ECC71;
		padding: 10px;
	}
	#createButton:hover{
		transform: scale(1.1);
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

<br>

<table id="displayTable">
<form action="" method="POST" id="eventCreationForm">
<tr>
	<td>Event Created by : </td>
	<td><?php echo $_SESSION['user'];?></td>
</tr>
<tr>
	<td>Sport: </td>
	<td>
		<select name="sports">
			<?php
				$servername = "localhost";
				$username = "root";
				$password = "";
				try{
					$conn = new PDO("mysql:host=$servername;dbname=pickupsports2", $username, $password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
					$sports = $conn->prepare("SELECT * FROM sports");
					$sports->execute();
					while($row = $sports->fetch(PDO::FETCH_ASSOC)){
						$sportName = $row['sportname'];
						$id = $row['id'];
						echo "<option value=".$id.">".$sportName."</option>";
					}
				}		
				catch(PDOException $e){
					echo $e->getMessage();
				}
			?>
		</select>
	</td>
</tr>
<tr>
	<td>Number of Participants Required : </td>
	<td><input type = "text" name = "partCount" required/></td>
</tr>
<tr>
	<td>Description : </td>
	<td><textarea name="desc" form = "eventCreationForm"></textarea></td>
</tr>
<tr>
	<td>Address : </td>
	<td>
		<table id="addressTable">
			<tr>
				<td>Street Address: </td>
				<td><input type="text" name="streetAddr" required/></td>
			</tr>
			<tr>
				<td>City: </td>
				<td><input type="text" name="city" required/></td>
			</tr>
			<tr>
				<td>State: </td>
				<td><input type="text" name="state" required/></td>
			</tr>
			<tr>
				<td>Zip Code: </td>
				<td><input type="text" name="zip"/></td>
			</tr>
			<tr>
				<td>Country: </td>
				<td><input type="text" name="country" required/></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td>Date of Event : </td>
	<td><input type="date" name="date" required/></td>
</tr>
<tr>
	<td>Time of Event : </td>
	<td><input type="time" name="time" required/></td>
</tr>
<tr>
	<td></td>
	<td><input id="createButton" type="submit" name="create" value="Create Event"></td>
</tr>
</form>
</table>

<?php

if(isset($_POST['logout'])){
	header("Location: /pickupsports2/logout.php");
}	

if(isset($_POST['profile'])){
	header("Location: /pickupsports2/userHome2.php");
}

if(isset($_POST['create'])){
	
	$user = $_SESSION['userID'];
	$sport = $_POST['sports'];
	$count = $_POST['partCount'];
	$desc = $_POST['desc'];
	$street = $_POST['streetAddr'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];
	$country = $_POST['country'];
	$date = $_POST['date'];
	$time = $_POST['time'];

	$now = new DateTime();
	$eventDate = new DateTime($date);
	if($eventDate < $now){
		echo "<script>alert('Cannot create event in past')</script>";
	}
	else{
	
		$sqlEventInsert	= $conn->prepare("INSERT INTO events(user, sport, count, description, street, city, state, zipcode, country, date, time) 
						VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$sqlEventInsert->execute(array($user, $sport, $count, $desc, $street, $city, $state, $zip, $country, $date, $time));
		echo "<script>alert('Event Created !')</script>";

		$sqlUpdate = $conn->prepare("UPDATE users SET hostedEvents=hostedEvents+? WHERE id=?");
		$sqlUpdate->execute(array(1, $user));
	}
}

$conn = null;
?>
</body>
</html>