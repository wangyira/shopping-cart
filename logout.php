<?php
	// To destory a session, first need to start a session.
	session_start();
	// Destroys any session variables. Use this to "log out"
	session_destroy();

	header("Location: index.php");
?>