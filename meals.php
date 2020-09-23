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
	// print_r($_COOKIE)."<hr>";
	// echo $_COOKIE["gfg"]."<hr>"; 
	// echo $_COOKIE["foodname"]."<hr>"; 
	// echo $_COOKIE["quantity"]."<hr>"; 
	if( isset($_POST["meal"]) && !empty($_POST["meal"])) {
		//echo $_POST["meal"];

		$sql = "SELECT distinct mealid FROM meals where mealname like '%". $_POST["meal"] . "%'";
		//echo "<hr>".$sql;
			
		$results = $mysqli->query($sql);
		if( !$results ) {
			echo $mysqli->error;
			exit();
		}

		$num = $results->num_rows;
		if($num == 0){
			$error = "No meals found";
		}
	}
	if(isset($_SESSION["email"])){
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
	}

	if(isset($_POST['add'])){
		// $usersql = "SELECT userid from users where email = '" . $_SESSION["email"] . "'";
		// $user_results = $mysqli->query($usersql);
		// if( !$user_results ) {
		// 	echo $mysqli->error;
		// 	exit();
		// }
		// while($user_row = $user_results->fetch_assoc()){
		// 	$userid = $user_row['userid'];
		// }

		$listsql = "SELECT listid from lists where userid = '" . $userid . "'";
		$list_results = $mysqli->query($listsql);
		if( !$list_results ) {
			echo $mysqli->error;
			exit();
		}

		if($list_results->num_rows > 0){
			while($list_row = $list_results->fetch_assoc()){
				$listid = $list_row['listid'];
			}
		}
		else{
			$maxlistid = "SELECT MAX(listid) as listid FROM lists";
			$max_list_results = $mysqli->query($maxlistid);
			if( !$max_list_results ) {
				echo $mysqli->error;
				exit();
			}
			while($max_row = $max_list_results->fetch_assoc()){
				$listid = $max_row['listid'] + 1;
			}
		}
		
		$mealsql = "SELECT DISTINCT mealid FROM meals where mealname = '" . $_POST['add'] . "'";
		$meal_results = $mysqli->query($mealsql);
		if( !$meal_results ) {
			echo $mysqli->error;
			exit();
		}
		while($meal_row = $meal_results->fetch_assoc()){
			$mealid = $meal_row['mealid'];
		}
		
		$addmealsql = "INSERT INTO lists (listid, userid, mealid) values (" . $listid ."," . $userid . "," . $mealid . ")";

		$addMeal = $mysqli->query($addmealsql);
		if( !$addMeal ) {
			echo $mysqli->error;
			$error = "Cannot add ingredients to the shopping list.";
			exit();
		}
		$success = "Successfully added ingredient to the shopping list.";
	}

	if(isset($_POST['edit'])){
		// $usersql = "SELECT userid,admin from users where email = '" . $_SESSION["email"] . "'";
		// $user_results = $mysqli->query($usersql);
		// if( !$user_results ) {
		// 	echo $mysqli->error;
		// 	exit();
		// }
		// while($user_row = $user_results->fetch_assoc()){
		// 	$userid = $user_row['userid'];
		// 	$admin = $user_row['admin'];
		// }

		if($admin != 1){
			$error = "Cannot edit a recipe unless you are an admin user.";
		}
		else{//admin user
			$mealname = $_POST['edit'];
			// echo $_POST['edit']."<hr>";
			// echo "this ".$_POST['foodname']." ". $_POST['quantity'];
			$mealsql = "SELECT DISTINCT mealid FROM meals where mealname = '" . $_POST['edit'] . "'";
			$meal_results = $mysqli->query($mealsql);
			if( !$meal_results ) {
				echo $mysqli->error;
				exit();
			}
			while($meal_row = $meal_results->fetch_assoc()){
				$mealid = $meal_row['mealid'];
			}

			$editsql = "update meals set quantity = ? where foodname = ? and mealid=?"; 
			$statement = $mysqli->prepare($editsql);
			$statement->bind_param("sss",$_COOKIE['quantity'], $_COOKIE["foodname"], $mealid);
			// $addingredient = "INSERT INTO meals (mealid, mealname, foodname, quantity) VALUES (?, ?, ?, ?)";
			// $statement = $mysqli->prepare($addingredient);	
			// $statement->bind_param("sssi", $mealid, $mealname, $_COOKIE["foodname"], $_COOKIE['quantity']);

			$executed = $statement->execute();
			// Returns false if error w/ executing the statement
			if(!$executed) {
				echo $mysqli->error;
				$error = "Cannot add ingredient to recipe.";
				exit();
			}

			$statement->close();
			$success = "Successfully updated recipe.";
		}
	}
	//$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Meals</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bebas+Neue&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="style.css">

	<style>
		#list{
			position:absolute;
			top:0;
			right:0;
			display:inline-block;
			font-size:28px;
			font-family: 'Bebas Neue', cursive;
			color:#426A5A;
		}
		h1{
			margin-top:10%;
		}
		input{
			height:50px !important;
			font-size:25px !important;
		}
		button{
			font-size:25px !important;
		}
		.btn{
			color:#84A987;
			border-color:#84A987;
		}
		.btn:hover{
			color:#426A5A;
			border-color:#426A5A;
		}
		table{
			margin-left:auto !important;
			margin-right:auto !important;
			font-size:20px !important;
			font-weight:bold;
		}
		h2{
			color:#84A987;
			font-size:30px;
			font-family: 'Cinzel Decorative', cursive;
			font-weight: bold;
			margin-top:30px;
		}
		.recipe{
			margin-bottom:100px !important;
		}
		#error{
			padding-bottom:20px !important;
		}
		@media (max-width:991px){
			h1{
				font-size:45px;
				margin-top:150px;
				margin-bottom:30px;
			}
			.edit{
				height:110px !important;
			}
			.add{
				height:110px !important;
			}
			button{
				margin-top:10px;
				
			}
		}
		@media (max-width:767px){
			h1{
				font-size:40px;
				margin-top:170px;
				margin-bottom:30px;
			}
		}
	</style>
</head>
<body>
	<div id="webname" class= "ml-3 mt-2 text-center"><a href="index.php">go get this bread</a></div>

	<div id="list" class="mr-3 mt-2"><a href="list.php">Shopping List</a></div>

	<?php if (isset($_SESSION["email"])) :?>
	<div id="signout" class="mr-3"><a href ="logout.php">Log out</a></div>
	<?php endif ?>

	<h1 class="text-center">Find your favorite recipe</h1>
	<form class="form-inline md-form mr-auto mb-4 justify-content-center" action="meals.php" method="POST" onsubmit="return  Form()">
  		<input class="form-control col-lg-7 col-m-8 col-11 mr-sm-2" type="text" name="meal" id="meal" placeholder="teriyaki salmon, avocado toast..." aria-label="Search">
  		<button class="btn btn-rounded btn-sm col-lg-1 col-m-8 col-11" type="submit">Search</button>
  		
	</form> 

	<div id="error" class="text-danger text-center">
		<?php if(isset($error)){
			echo $error;
		}?>
	</div>

	<div id="error" class="text-success text-center">
		<?php if(isset($success)){
			echo $success;
		}?>
	</div>

	<?php if(isset($results)) :?>
	<?php while($row = $results->fetch_assoc()) : ?>
		<?php
			$mealid = $row["mealid"];
			$find = "SELECT * FROM meals where mealid=" . $mealid;
			$res = $mysqli->query($find);
			if( !$res ) {
				echo $mysqli->error;
				exit();
			}
		?>

		<div class="recipe">
			<h2 class="text-center">
				<?php while($b = $res->fetch_assoc()) : ?>
				<?php 
					$meal = $b["mealname"]
					
				?>
				<?php echo $meal;
					break;
				?>
				<?php endwhile;?>
			</h2>
			
			<?php $res->data_seek(0); ?>
		<table class="table table-responsive table-hover col-lg-5 col-m-7 col-10">
			<thead>
				<tr>
					<th>FOOD NAME</th>
					<th>QUANTITY</th>
				</tr>
			</thead>
			<tbody>
			
			<?php while($a = $res->fetch_assoc()) :?>
				<!-- <?php echo $a["foodname"]; ?> -->
					<tr>
						<td class="foodname col-1">
							<?php echo $a["foodname"]; ?>
						</td>
						<td class="quantity col-2 text-right">
							<?php echo $a["quantity"]; ?>
						</td>
					</tr>
			<?php endwhile;?>
			</tbody>
			</table>
			<div class="text-center">
				<form name="addtolist" action="meals.php" method="post">
					<button class="btn btn-rounded btn-sm col-3 edit" name="edit" type="submit"
						value="<?php echo $meal;?>">
				 		Edit recipe
				 	</button>
				 	<input type="hidden" name="foodname" id="foodname" value="">
				 	<input type="hidden" name="quantity" id="quantity" value="">
					<button class="btn btn-rounded btn-sm col-3 add" name="add" type="submit"
						value="<?php echo $meal;?>">
				 		Add to Shopping List
				 	</button>
				</form>
			</div>
		<?php endwhile;?>
	<?php endif;?>	
		</div>

	<div class="d-none" id="admin"><?php echo $admin;?></div>
	<div class="" id="userloggedin"><?php echo isset($_SESSION["logged_in"])==false ?></div>
	<div class="footer">
		ITP 303 Made By Amanda Wang
	</div>
	<script
  src="http://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
	<script>
		function createCookie(name, value, days) { 
		    var expires; 
		      
		    if (days) { 
		        var date = new Date(); 
		        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); 
		        expires = "; expires=" + date.toGMTString(); 
		    } 
		    else { 
		        expires = ""; 
		    } 
		      
		    document.cookie = escape(name) + "=" +  
		        escape(value) + expires + "; path=/"; 
		} 

		let edit = document.querySelectorAll(".edit");
		for(let j=0;j<edit.length;j++){
			edit[j].onclick = function(){
				if(document.querySelector("#userloggedin").innerHTML == 1){
					document.querySelector("#error").innerHTML = "Cannot edit a recipe unless you are logged in.";
					return false;
				}
				if(document.querySelector('#admin').innerHTML == 0){
					document.querySelector("#error").innerHTML = "Cannot edit a recipe unless you are an admin user.";
					return false;
				}
				else{
					console.log('edit');
					var response = prompt("What ingredient's quantity would you like change?");  
					var quant = prompt("How much "+response+" would you like to change the amount to?");
					
					document.querySelector("#foodname").value = response;
					document.querySelector("#quantity").value = quant;
					console.log(document.querySelector("#foodname").value);
					console.log(document.querySelector("#quantity").value);

					

					//createCookie("gfg", "GeeksforGeeks", "10"); 
					createCookie("foodname", response, "10"); 
					createCookie("quantity", quant, "10"); 
				}
			}
		}

		let add = document.querySelectorAll(".add");
		for(let i=0;i<add.length;i++){
			add[i].onclick = function(){
				if(document.querySelector("#userloggedin").innerHTML == 1){
					document.querySelector("#error").innerHTML = "Cannot add a recipe to shopping list unless you are logged in.";
					return false;
				}
			}
		}

		
		// document.querySelectorAll(".add").onclick = function(){
		// 	if(document.querySelector("#userloggedin").innerHTML == 1){
		// 		document.querySelector("#error").innerHTML = "Cannot add a recipe to shopping list unless you are logged in.";
		// 		return false;
		// 	}
		// }
		function validateForm() {
			if(document.querySelector("#meal").value.length == 0){
				document.querySelector("#error").innerHTML = "Please search for a meal";
				return false;
			}
		}
	</script>
</body>
</html>