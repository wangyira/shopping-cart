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

	if(isset($_POST["delete"])){

		$usersql = "SELECT userid,admin from users where email = '" . $_SESSION["email"] . "'";
		$user_results = $mysqli->query($usersql);
		if( !$user_results ) {
			echo $mysqli->error;
			exit();
		}
		while($user_row = $user_results->fetch_assoc()){
			$userid = $user_row['userid'];
			$admin = $user_row['admin'];
		}

		$delete = "DELETE from lists where userid=" . $userid. " AND mealid=" . $_POST["delete"];
		$delete_results = $mysqli->query($delete);
		if( !$delete_results ) {
			echo $mysqli->error;
			exit();
		}
	}

	if(isset($_SESSION["email"])){
		$usersql = "SELECT userid from users where email = '" . $_SESSION["email"] . "'";
		$user_results = $mysqli->query($usersql);
		if( !$user_results ) {
			echo $mysqli->error;
			exit();
		}
		while($user_row = $user_results->fetch_assoc()){
			$userid = $user_row['userid'];
		}
	
		$userlistsql = "SELECT listid, mealid from lists where userid = '" . $userid . "'";
		$listuser = $mysqli->query($userlistsql);
		if( !$listuser ) {
			echo $mysqli->error;
			exit();
		}
		$listarr = array();
		while($list_row = $listuser->fetch_assoc()){
			array_push($listarr, $list_row['mealid']);
			//$listid = $list_row['listid'];
		}
		//echo "listid ".$listid;

		$listuser->data_seek(0);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Shopping List</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bebas+Neue&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="style.css">
	<style>
		body{
			font-size:25px;
		}
		.row{
			max-width:70%;
			margin-left:auto;
			margin-right:auto;
		}
		h1{
			margin-top:100px;
		}
		#meal{
			position:absolute;
			top:0;
			right:0;
			display:inline-block;
			font-size:28px;
			font-family: 'Bebas Neue', cursive;
			color:#426A5A;
		}
		#delete{
			margin-bottom:70px !important;
		}
		@media (max-width:991px){
			.row{
				max-width:80%;
				margin-left:auto;
				margin-right:auto;
			}
		}
		@media (max-width:767px){
			.row{
				max-width:90%;
				margin-left:auto;
				margin-right:auto;
			}
		}
	</style>
</head>
<body>
	<div id="webname" class= "ml-3 mt-2 text-center"><a href="index.php">go get this bread</a></div>

	<div id="meal" class="mr-3 mt-2"><a href="meals.php">Search for Meals</a></div>

	<?php if (isset($_SESSION["email"])) :?>
	<div id="signout" class="mr-3"><a href ="logout.php">Log out</a></div>
	<?php endif ?>

	<h1 class="text-center mb-3">shopping list</h1>

	<?php if (!isset($_SESSION["email"])) :?>
	<div class="text-center">Register or login to add to your shopping list.</div>
	<?php endif ?>

	<?php if (isset($_SESSION["email"])) : ?>
		<?php while ($list_row = $listuser->fetch_assoc()) :?>
			<?php $mealid = $list_row['mealid']; ?>
			

			<?php
				$mealname = "SELECT * from meals where mealid = '" . $mealid . "'";
				$meal_results = $mysqli->query($mealname);
				if( !$meal_results ) {
					echo $mysqli->error;
					exit();
				}
			?>	

			<?php while($meal_row = $meal_results->fetch_assoc()) :?>
				<div class="row mb-3">
					<div class="foodname col-7 text-center">
						
						<?php echo $meal_row['foodname']; ?>
						
							
					</div>

					<div class="foodquantity col-3 text-center">
						
						<?php echo $meal_row['quantity']; ?>
						
					</div>
				</div>

			<?php endwhile; ?>

			<div class="text-center">
				<form name="listform" action="list.php" method="POST">
					<button type="submit" class="btn btn-dark col-2" id="delete" name="delete" value="<?php echo $mealid; ?>">
						Delete
					</button>
				</form>
			</div>
		<?php endwhile; ?>
	<?php endif;?>
	<div class="footer">
		ITP 303 Made By Amanda Wang
	</div>

	
</body>
</html>