<?php
include_once(__DIR__."/partials/header.partial.php");
?>
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
            //get user tank(s) and store in session, not necessary but saves extra DB calls later
            $result = DBH::get_tanks(Common::get_user_id());
            if(Common::get($result, "status", 400) == 200){
                $tanks = Common::get($result, "data", []);
                if(count($tanks) == 0) {
                    //this section is needed to give any previously existing users a tank that didn't have a tank before
                    //this feature was created/added
                    $result = DBH::create_tank(Common::get_user_id());
                    if (Common::get($result, "status", 400) == 200) {
                        $result = DBH::get_tanks(Common::get_user_id());
                        if (Common::get($result, "status", 400) == 200) {
                            $tanks = Common::get($result, "data", []);
                        }
                    }
                }
                //finally let's save our tanks in session
                $_SESSION["user"]["tanks"] = $tanks;
            }
            //end get tanks

            die(header("Location: " . Common::url_for("home")));
        }
        else{
            Common::flash(Common::get($result, "message", "Error logging in"));
            die(header("Location: " . Common::url_for("login")));
        }
    }
    else{
        Common::flash("Email and password must not be empty", "warning");
        die(header("Location: " . Common::url_for("login")));
    }
}
?>