
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
	
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
			
				$db = new PDO($connection_string, $dbuser, $dbpass);
				$stmt = $db->prepare("SELECT * FROM Users where email = :email LIMIT 1");
				$stmt->execute(array(
					":email" => $email
				));
				
				$fields = array('Email','Password');

				foreach($fields AS $fieldname) { //Loop trough each field
				  if(!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
					echo "<br>";
					echo 'Field '.$fieldname.' is empty!<br />';
					 
				  }
				}
				//$e = $stmt->errorInfo();
				//if($e[0] != "00000"){
					//echo var_export($e, true);
				}
				else{
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					if ($result){
						$rpassword = $result["password"];
						if(password_verify($password, $rpassword)){
							
							$_SESSION["user"] = array(
								"email"=>$result["email"],
								"password"=>$result["rpassword"]
							);
							echo var_export($_SESSION, true);
							header("Location: home.php");
						}
						else{
							echo "<div>Invalid password!</div>";
						}
					}
					else{
						echo "<div>Invalid user</div>";
					}
					//echo "<div>Successfully registered!</div>";
				}

}
?>
