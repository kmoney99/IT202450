
<?php
include("header.php");
?>
<link rel="stylesheet" type="text/css" href="style.css">

<h1 style="background-color:Blue;">Login</h1>

<form method="POST">

 <div style='text-align:center'>

	<label for="email">Email:<font color=red>*</font>
	<input type="email" id="email" name="email" autocomplete="off" />
	</label>

	<label for="p">Password:<font color=red>*</font>
	<input type="password" id="p" name="password" autocomplete="off"/>
	</label>
	<input type="submit" name="login" value="Login"/>
</form>

<?php

if(isset($_POST["login"])){
$fields = array('Email','Password');

foreach($fields AS $fieldname) { //Loop trough each field
  if(!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
    echo "<br>";
	echo 'Field '.$fieldname.' is empty!<br />';
     
  }
}
	
}

$email = $_POST['eamil'];
$password = $_POST['password'];

$sql = "SELECT password
        FROM members
        WHERE email = '$email'		
      ";
	  
	  $result = mysqli_query( $conn, $sql);
	  
	  while ($row = mysqli_fetch_assoc($result ) ) {
		
		$pw = $row['password'];
	  }
		
	if ($pw == $password) {
          
		session_start();
		$_SESSION['u_e'] = $email;
		header("Location: home.php");
 
       		
	  }



?>