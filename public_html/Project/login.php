<?php
include_once(__DIR__."/partials/header.partial.php");
?>
    <div>
        <h4>Login</h4>
        <form method="POST">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required/>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required min="3"/>
            </div>
            <input type="submit" name="submit" value="Login"/>
        </form>
    </div>
<?php
if (Common::get($_POST, "submit", false)){
    $email = Common::get($_POST, "email", false);
    $password = Common::get($_POST, "password", false);
    if(!empty($email) && !empty($password)){
        $result = DBH::login($email, $password);
        echo var_export($result, true);
        if(Common::get($result, "status", 400) == 200){
            $_SESSION["user"] = Common::get($result, "data", NULL);

            //fetch system user id and put it in session to reduce DB calls to fetch it when we need
            //to generate points from activity on the app
            $result = DBH::get_system_user_id();
            $result = Common::get($result, "data", false);
            if($result) {
                $_SESSION["system_id"] = Common::get($result, "id", -1);
                error_log("Got system_id " . $_SESSION["system_id"]);
            }
            //end system user fetch
            try{
				$db = new PDO($connection_string, $dbuser, $dbpass);
				$stmt = $db->prepare("SELECT * FROM Users where email = :email LIMIT 1");
				$stmt->execute(array(
					":email" => $email
				));
				$e = $stmt->errorInfo();
				if($e[0] != "00000"){
					echo var_export($e, true);
				}
				else{
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
				}
					if ($result){
						$rpassword = $result["password"];
						if(password_verify($password, $rpassword)){
							
							$_SESSION["user"] = array(
								"id"=>$result["id"],
								"email"=>$result["email"],
								"first_name"=>$result["first_name"],
								"last_name"=>$result["last_name"]
							);
							echo var_export($_SESSION, true);
							header("Location: home.php");
    }
    else{
        Common::flash("Email and password must not be empty", "warning");
        die(header("Location: " . Common::url_for("login")));
    }
}
?>