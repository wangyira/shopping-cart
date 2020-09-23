<!DOCTYPE html>
<html>
<head>
	<title>Home Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bebas+Neue&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="style.css">
	<style>
		h1{
			margin-top:10%;
		}
		img{
			width:30% !important;
			margin-left:2.3%;
			margin-bottom:50px;
		}
		#search{
			margin-bottom:20px !important;
		}
		#login{
			background-color:#59C3C3;
			text-align:center;
			height:40px;
			float:right;
			font-size:28px;
			font-family: 'Bebas Neue', cursive;
	
		}
		#login:hover{
			background-color:#52489C;
			color:white;
		}
		#register{
			background-color:#59C3C3;
			text-align:center;
			height:40px;
			float:right;
			font-size:28px;
			font-family: 'Bebas Neue', cursive;
		}
		#register:hover{
			background-color:#52489C;
			color:white;
		}
		a{
			color:black;
		}
		a:hover{
			color:white;
		}
		ul{
			width:50% !important;
			margin-left: auto !important;
			margin-right: auto !important;
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
		@media (max-width:991px){
			h1{
				font-size:45px;
			}
			img{
				width:60% !important;
				display:block !important;
				margin-left:auto;
				margin-right:auto;

			}
			ul{
				width:80% !important;
			}
		}

		@media (max-width:767px){
			h1{
				font-size:43px;
				padding-left:50px !important;
				padding-right:50px !important;
			}
			img{
				width:80% !important;
				display:block !important;
				margin-left:auto;
				margin-right:auto;
			}
			ul{
				width:90% !important;
			}
		}
	</style>
</head>
<body>
	
	<div id="webname" class= "ml-3 mt-2 text-center">go get this bread</div>

	<div id="register" class="col-lg-1 col-md-2 col-3">
		<a href="register.php">Register</a>
	</div>

	<div id="login" class="col-lg-1 col-md-2 col-3">
		<a href="login.php">Login</a>
	</div>

	<div class="clearing"></div>

	<div id="search" >
		<!-- <h1 class="text-center">go get this bread</h1> -->

		<h1 class="text-center">Find your favorite recipe</h1>
		<form class="form-inline md-form mr-auto mb-4 justify-content-center" action="meals.php" method="POST" onsubmit="return validateForm()">
	  		<input class="form-control col-lg-7 col-m-8 col-11 mr-sm-2" type="text" name="meal" id="meal" placeholder="teriyaki salmon, avocado toast..." aria-label="Search">
	  		<button class="btn btn-rounded btn-sm col-lg-1 col-m-8 col-11" type="submit">Search</button>
		</form> 

		<div id="error" class="text-center text-danger"></div>

		<ul>
		<li><h4 class="text-center">Customize your shopping list based on your favorite meals</h4></li>

		<li><h4 class="text-center">Never forget to buy something at the grocery store again</h4></li>

		<li><h4 class="text-center">Create an account or login to start adding to your list now</h4></li>
		</ul>

		<!-- <a href="list.php">Link to the list.php (this link will be on a different page for the final project) </a> -->
	</div>

	<img src="food4.jpg">
	<img src="food1.png">
	<img src="food2.jpg">

	<div class="footer">
		ITP 303 Made By Amanda Wang
	</div>
 
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script>
	function validateForm() {
		if(document.querySelector("#meal").value.length == 0){
			document.querySelector("#error").innerHTML = "Please search for a meal";
			return false;
		}
	}
</script>
</body>
</html>