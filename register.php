<?php
	session_start();

	$host = "303.itpwebdev.com";
	$user = "wangyira_db_user";
	$password = "Amandapanda14!";
	$db = "wangyira_project_db";
	//create instance of MySQLi class
	$mysqli = new mysqli($host, $user, $password, $db);

	//Check for DB connection errors
	if( $mysqli->connect_errno) {
		//echo out the error message
		echo $mysqli->connect_error;
		//exit the program, no need to continue on if no db connection is made
		exit();
	}

	// Since we are getting text back, set utf-8 as our characterset.
	$mysqli->set_charset('utf-8');
	if(isset($_POST["check"])){
		if ($_POST["check"] == "on"){
			$check = '1';
		}
	}
	else{
		$check = '0';
	}

	// If user is already logged in, kick them out of this page. Never let them log in again. Else, proceed with login.
	if( isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true ) {
		header("Location: meals.php");// Redirect user to the meals page
	}
	else {
		// Check that the user has submitted a form (as opposed to user who just got to the page)
		//echo "here ".$_POST["email"]." ".$_POST["password"]."<hr>";

		if( isset($_POST["email"]) &&  isset($_POST["password"]) && isset($_POST["confirm"])) {
			// Since we are getting text back, set utf-8 as our characterset.
			$mysqli->set_charset('utf-8');
			
			$sql = "SELECT * FROM users where email = '". $_POST["email"] . "'";
			
			$results = $mysqli->query($sql);
			if( !$results ) {
				echo $mysqli->error;
				exit();
			}

			$num = $results->num_rows;

			// User has attempted to log in - now check for two more cases
			// 1. Validation - make sure user has entered a username & password
			if( empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["confirm"])) {
				$error = "Please enter a email, a password, and confirm the password";
			} 
			else if($_POST["password"] != $_POST["confirm"]){
				echo "doesn't match";
				$error = "Passwords do not match";
			}
			else if( $num > 1) {
				$error = "Email already exist, register with another email";
			}
			else {
				echo $_POST["password"];
				$pass = $_POST["password"];
				$hash = hash("md5", $pass);
				$insert = "INSERT INTO users (email, password, admin) VALUES (?,?,?);";

				$statement = $mysqli->prepare($insert);	

				$statement->bind_param("sss", $_POST["email"], $hash, $check);

				$executed = $statement->execute();
				// Returns false if error w/ executing the statement
				if(!$executed) {
					echo $mysqli->error;
				}

				$statement->close();

				// User has typed in correct email and password, store info in session.
				$_SESSION["logged_in"] = true;
				$_SESSION["email"] = $_POST["email"];
				
				header("Location: meals.php");
			}
		}
	}
	$mysqli->close();
?> 
<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bebas+Neue&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="style.css">
	<style>
		html,body{
			height: 100%;
		}
		body{
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.form-signin {
		  width: 100%;
		  max-width: 560px;
		  padding: 80px 120px;
		  margin: 0 auto;
		  border: 3px solid #426A5A;
		  border-radius: 30px;
		  background-color: #426A5A;
		}
		.form-signin .form-control{
			position: relative;
			box-sizing: border-box;
			height: auto;
			padding: 10px;
			height: 50px;
			font-size:25px;
		}
		a{
			color:#426A5A;;
		}
		@media (max-width:991px){
			h1{
				font-size:45px !important;
			}
			.form-signin{
				max-width: 450px;
		  		padding: 100px 90px;
			}
			.form-signin .form-control {
				height: 40px;
				font-size: 25px;
			}
		}

		@media (max-width:767px){
			h1{
				font-size:40px !important;
			}
			.form-signin{
				max-width: 350px;
		  		padding: 100px 50px;
			}
			.form-signin .form-control {
				height: 40px;
				font-size: 20px;
			}
		}
	</style>
</head>
<body class="text-center">

	<div id="webname" class= "ml-3 mt-2 text-center"><a href="index.php">go get this bread</a></div>

	<form class="form-signin" method="POST" action="register.php">
      <h1 class="mb-4 font-weight-normal">register</h1>
     
      <label for="inputEmail" class="sr-only">Email</label>
      	<input type="email" id="inputEmail" class="form-control mb" placeholder="email" name="email" required autofocus>

      <label for="inputPassword" class="sr-only">Password</label>
      	<input type="password" id="inputPassword" class="form-control" placeholder="password" name="password" required>

      <label for="inputPassword" class="sr-only">Confirm Password</label>
      	<input type="password" id="inputConfirm" class="form-control mb-4" placeholder="confirm password" name="confirm" required>

      <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check">
      <label class="form-check-label mb-3" for="exampleCheck1">sign up to be an admin user</label>
      	
      <button class="btn btn-dark btn-block btn-lg" type="submit">Register</button>
      <!-- <button class="btn btn-dark btn-block btn-lg" type="submit">Register</button> -->
      <div class="text-danger">
			
		<?php 
			if(isset($error) && !empty($error)) {
				echo $error;
			}
		?>
			
	   </div> 
    </form>
</body>
</html>