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
		background-color: #82CAFA;
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
		color: black;
		transform: scale(1.1);
	}
	#appBanner{
		width: 100%;
		height: 70px;
		background-color: #2B60DE;
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
		width: 100%;
		background: url('/pickupsports2/images/opuR60.jpg') no-repeat center center fixed;
		background-size: cover;
		
	}
	#displayTable{
		font-size: 20px;
		padding: 20px;
		font-weight: bold;	
		color: white;
	}
	#displayTable td{
		height: 25px;
	}
	select{
		font-size: 15px;
	}
	#searchButton{
		height: 30px;
		width: 110px;
		color: black;
		font-weight: bold;
		background-color: #2ECC71;
		padding: 5px;
		
	}
	#searchButton:hover{
		transform: scale(1.1);
	}
	#event{
		display: block;
		margin: 10px auto;
		width: 100%;
		border: 2px solid black;
		float: left;
		border-radius: 20px;
		padding: 5px 10px 10px 10px;
		background-color: #ABEBC6;
		
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
	#wall{
		width: 80%;
		margin: 10px auto;
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

<table id="displayTable">
<form action="" method="post">

<tr>
	<td>Username : </td>
	<td><input type="text" name="usernameSearch"></td>
</tr>
<tr>
	<td>Sport : </td>
	<td>
		<select name="sportSearch">
			<option value="0">None</option>
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
	<td></td>
	<td><input type="submit" name="search" id="searchButton" value="Search Events"></td>
</tr>
</form>
</table>

<?php
try{
	if(isset($_POST['logout'])){
		header("Location: /pickupsports2/logout.php");
	}	
	
	if(isset($_POST['profile'])){
		header("Location: /pickupsports2/userHome2.php");
	}

	
	if(isset($_POST['search'])){
		$username1 = $_POST['usernameSearch'];
		$sqlQuery = $conn->prepare("SELECT * FROM users WHERE username=?");
		$sqlQuery->execute(array($_POST['usernameSearch']));
		$row = $sqlQuery->fetch(PDO::FETCH_ASSOC);
		$userid = $row['id'];
		$sportid = $_POST['sportSearch'];

		if($sportid==0 && !empty($username1)){
			#echo "empty sport and selected user<br>";		
			#echo $userid."<br>";

			$sqlQuery = $conn->prepare("SELECT * FROM events WHERE user=? and date>=CURDATE()");
			$sqlQuery->execute(array($userid));

		}
		elseif(empty($username1) && $sportid!=0){
			#echo "empty user and selected sport<br>";	
			$sqlQuery = $conn->prepare("SELECT * FROM events WHERE sport=? and date>=CURDATE()");
			$sqlQuery->execute(array($sportid));

		}
		elseif(!empty($username1) && $sportid!=0){
			#echo "selected user and sport<br>";
			$sqlQuery = $conn->prepare("SELECT * FROM events WHERE user=? and sport=? and date>=CURDATE()");
			$sqlQuery->execute(array($userid, $sportid));
			
		}
		else{
			#echo "empty user and sport<br>";
			$sqlQuery = $conn->prepare("SELECT * FROM events WHERE date>=CURDATE()");
			$sqlQuery->execute();
		}
		
		echo "<div id='wall'>";
		while($row = $sqlQuery->fetch(PDO::FETCH_ASSOC)){
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
					echo "<div id='attend'><form action='' method='post'><input type='hidden' name='eventID' value=".$eventID."><button type='submit' name='attendEventButton'>Attend</button></form></div>";
				echo "</div>";
			echo "</div>";
		}
		echo "</div>";

	}
	if(isset($_POST['attendEventButton']))
	{
		
		echo $_POST['eventID']."<br>";
		echo $_SESSION['userID']."<br>";
		
		$sqlQuery = $conn->prepare("SELECT count FROM events WHERE id=?");
		$sqlQuery->execute(array($_POST['eventID']));
		$row = $sqlQuery->fetch(PDO::FETCH_ASSOC);
		$partCount = $row['count'];
		
		if($partCount<=0){
			echo "<script>alert('The event has reached its subscription Limit. Sorry!!')</script>";
		}
		else{
			try{
		
				$sqlInsert = $conn->prepare("INSERT INTO userEventsLink(userID, eventID) VALUES(?, ?)");
				$sqlInsert->execute(array($_SESSION['userID'], $_POST['eventID']));
		
				$sqlUpdate = $conn->prepare("UPDATE users SET attendedEvents=attendedEvents+? WHERE id=?");
				$sqlUpdate->execute(array(1, $_SESSION['userID']));

				$sqlUpdate2 = $conn->prepare("UPDATE events SET count=count-? WHERE id=?");
				$sqlUpdate2->execute(array(1, $_POST['eventID']));
				
				echo "<script>alert('You are now attending this event.')</script>";

			}
			catch(PDOException $e){
				echo "<script>alert('You are attending this event.')</script>";
			}
		}
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