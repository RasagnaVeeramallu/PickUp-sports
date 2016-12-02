<!DOCTYPE html>
<html>
<head>
<style>
	h1{
		text-align: center;
	}
	.wrapper{
		width: 25%;
		margin: 0 auto;
		padding: 20px;
		border: 3px solid black;
	}
	#loginBox{
		display: none;		
		margin: auto;
		width: 50%;
	}
	.registerBox{
		display: none;
		margin: auto;
		width: 50%;
	}
	.divButtons{
		width: 100%;
		height: 50px;
	}
	.loginDivButton{
		width: 50%;
		height: 100%;
		float: left;
	}
	.registerDivButton{
		width: 50%;
		height: 100%;
		float: right;
	}

</style>

</head>
<body>
<h1>Welcome - Pick Up Sports</h1>
<div class="wrapper">	
	<div class="divButtons">
		<button type="button" name="login" class="loginDivButton" onclick="loginView()"> Login </button>
		<button type="button" name="register" class="registerDivButton" onclick="registerView()"> Register </button>
	</div>
	<br><br>
	<div id="formBox">
	
	<div id="loginBox">
		<form action="" method="POST" id="loginForm">
			<input type="text" name="username" placeholder="username" id="username"><br><br>
			<input type="password" name="password" placeholder="password" id="password"><br><br>
			<input type="submit" name="loginButton" value="Login">
		</form>	
	</div>
	
	<div class="registerBox" id="registerBox">
		<form action="" method="POST" id="registerForm">
			<input type="text" name="firstname" placeholder="firstname" id="firstname"><br><br>
			<input type="text" name="lastname" placeholder="lastname" id="lastname"><br><br>
			<input type="text" name="username" placeholder="username" id="username"><br><br>
			<input type="text" name="email" placeholder="email" id="email"><br><br>
			<input type="password" name="password" placeholder="password" id="password"><br><br>
			<input type="submit" name="registerButton" value="Register">
		</form>	
	</div>
	</div>
</div>

<script type="text/javascript">
function loginView(){
	document.getElementById('registerBox').style.display='none';
	document.getElementById('loginBox').style.display='block';
} 

function registerView(){
	document.getElementById('loginBox').style.display='none';
	document.getElementById('registerBox').style.display='block';
}
</script>
<?php

$servername = "localhost";
$username = "root";
$password = "";

try{
	$conn = new PDO("mysql:host=$servername;dbname=pickupsports2", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	if(isset($_POST['loginButton'])){
		##navigate to user Profile Page
		$usernameUser = $_POST['username'];
		$passwordUser = $_POST['password'];
		
		$sqlQuery = $conn->prepare("SELECT * FROM users WHERE username=?");
		$sqlQuery->execute(array($usernameUser));
		$row = $sqlQuery->fetch(PDO::FETCH_ASSOC);
		$passwordDB = $row['password'];
		$id = $row['id'];
		if($passwordUser==$passwordDB){
			session_start();
			
			$_SESSION['user']=$usernameUser;			
			$_SESSION['userID']=$id;
		
			header("Location: /PickUpSports2/userHome2.php");
		}
		else{
			echo "Invalid Credentials!!<br>";
		}
	}

	if(isset($_POST['registerButton'])){
		##Insert Into DateBase
		$username = $_POST['username'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		
		$sqlInsert = $conn->prepare("INSERT INTO users(username, firstname, lastname, email, password, hostedEvents, attendedEvents, registerDate) 
						VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
		$sqlInsert->execute(array($username, $firstname, $lastname, $email, $password, 0, 0));
		echo "User ".$username." Registered!!<br>";		
	}
}
catch(PDOException $e){
	echo $e->getMessage();
}
?>


</body>
</html>