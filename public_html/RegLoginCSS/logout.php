<?php
include("header.php");
//session_start();//called from header.php
session_unset();
session_destroy();


//get session cookie and delete/clear it for this session
if (ini_get("session.use_cookies")) { 
    $params = session_get_cookie_params(); 
	//clones then destroys since it makes it's lifetime 
	//negative (in the past)
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], 
        $params["secure"], $params["httponly"] 
    ); 
} 
?>
<h2 style="background-color:Violet;"> You have been logged out!</h2>

