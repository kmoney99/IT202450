<?php include("header.php");?>

<link rel="stylesheet" type="text/css" href="style.css">

<h1 style="background-color:Tomato;">Register</h1>

<form method="POST">

 <div style='text-align:center'>

	<label for="email">Email:<font color=red>*</font>
	<input type="email" id="email" name="email" autocomplete="off" />
	</label>

	<label for="p">Password:<font color=red>*</font>
	<input type="password" id="p" name="password" autocomplete="off"/>
	</label>

	<label for="cp">Confirm Password:<font color=red>*</font>
	<input type="password" id="cp" name="cpassword"/>
	</label>

	<input type="submit" name="register" value="Register"/>

</form>



<?php

$emailErr = "";
if(isset($_POST["register"])){
	if(isset($_POST["password"]) && isset($_POST["cpassword"]) && isset($_POST["email"])){
		$password = $_POST["password"];
		$cpassword = $_POST["cpassword"];
		$email = $_POST["email"];
	
		if($password == $cpassword){
			$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
			try{
				$db = new PDO($connection_string, $dbuser, $dbpass);
				$hash = password_hash($password, PASSWORD_BCRYPT);
				$stmt = $db->prepare("INSERT INTO Users (email, password) VALUES(:email, :password)");
				$stmt->execute(array(
					":email" => $email,
					":password" => $hash//Don't save the raw password $password
				));
				
				
				
				$e = $stmt->errorInfo();
				if($e[0] != "00000"){
				}
				else{
					echo "<div>Successfully registered!</div>";
					
				}
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format"; 
               }
			   
				if (empty($_POST['email'])) {
		
					echo "Email cannot be empty";
		
				}
				if (empty($_POST['password'])) {
		
					echo "Password cannot be empty";
		
				}
				if (empty($_POST['cpassword'])) {
		
					echo "Confirm password cannot be empty";
		
				}
				
			}
			catch (Exception $e){
				echo $e->getMessage();
			}
		}
		else{
			echo "<div> Passwords don't match </div>";
		}
	}
}
?>