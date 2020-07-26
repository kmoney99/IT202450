<?php
include_once(__DIR__."/partials/header.partial.php");
?>
    <html><head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<!-- Include jQuery 3.5.1-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head><body data-gr-c-s-loaded="true" style="
    background: #28334Aff;
"><nav class="navbar navbar-expand-lg navbar-dark bg-warning">
    <ul class="navbar-nav mr-auto" style="margin: 0 auto;">
        
<li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	" href="/Project/includes/../login.php">Login</a>
            </li>
            <li class="nav-item" style="margin-left: 10px;">
                <a style="
    color: black;
    font-weight: bold;
" class="nav-link" href="/Project/includes/../register.php">Register</a>
            </li>
            </ul>
</nav>
<div id="messages">
        </div>    <div>
	<form method="POST">
        
		<div id="messages">
        </div>    <div style="
    margin: 0 auto;
    width: fit-content;
    /* margin-top: auto !important; */
    margin-top: 2em;
    color: white;
">
        <h4 style="
    /* margin: 0 auto; */
    text-align: center;
">Please Register</h4>
        
            <div style="
    margin-top: 2em;
">
                <label for="email" style="
    display: block;
    text-align: center;
">Email</label>
                <input type="email" id="email" name="email" required="" style="
    /* margin-left: 1.6rem; */
    display: block;
">
            </div>
            <div>
                <label for="password" style="
    display: block;
    text-align: center;
    margin-top: 1em;
">Password</label>
                <input type="password" id="password" name="password" required="" min="3" style="
    margin: 0 auto;
    width: fit-content;
    /* text-align: center; */
">

<label for="password" style="
    display: block;
    text-align: center;
    margin-top: 1em;
">Confirm Password</label>
                <input type="password" id="cpassword" name="cpassword" required="" min="3" style="
    margin: 0 auto;
    width: fit-content;
    /* text-align: center; */
">

            <div style="
    margin: 0 auto;
    width: fit-content;
    margin-top: 1em;
">
    <input type="submit" class="btn btn-warning" name="submit" value="Register" style="border-radius: 5px;">

</div>
        
    </div>



 
            
             
</div></form></div></body></html>     
             
<?php
if (Common::get($_POST, "submit", false)){
    $email = Common::get($_POST, "email", false);
    $password = Common::get($_POST, "password", false);
    $confirm_password = Common::get($_POST, "cpassword", false);
    if($password != $confirm_password){
        Common::flash("Passwords must match", "warning");
        die(header("Location: register.php"));
    }
    if(!empty($email) && !empty($password)){
        $result = DBH::register($email, $password);
        echo var_export($result, true);
        if(Common::get($result, "status", 400) == 200){
            //Note to self: Intentionally didn't add tank creation here
            //keeping it in login where it is (creates a new tank only if user has no tanks)
            //it fulfills the purpose there
            Common::flash("Successfully registered, please login", "success");
            $data = Common::get($result, "data", []);
            $id = Common::get($data,"user_id", -1);
            if($id > -1) {
                $result = DBH::changePoints($id, 10, -1, "earned", "Welcome bonus");
                if(Common::get($result, "status", 400) == 200){
                    Common::flash("Here's 10 free points for the shop to start you off!", "success");
                }
            }
            die(header("Location: " . Common::url_for("login")));
        }
    }
    else{
        Common::flash("Email and password must not be empty", "warning");
        die(header("Location: register.php"));
    }
}