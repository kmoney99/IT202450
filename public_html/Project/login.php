<?php
include_once(__DIR__."/partials/header.partial.php");
?>
    <html><head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<!-- Include jQuery 3.5.1-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head><body data-gr-c-s-loaded="true" style="
    background: #28334Aff;
"><div id="messages">
        </div>    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<!-- Include jQuery 3.5.1-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

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
">Login</h4>
        <form method="POST">
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
            </div>
            <div style="
    margin: 0 auto;
    width: fit-content;
    margin-top: 1em;
">
    <input type="submit" name="submit" value="Login" style="
    border-radius: 5px;
">
</div>
        </form>
    </div>

</body></html>
<?php
if (Common::get($_POST, "submit", false)){
    $email = Common::get($_POST, "email", false);
    $password = Common::get($_POST, "password", false);
    if(!empty($email) && !empty($password)){
        $result = DBH::login($email, $password);
        echo var_export($result, true);
        if(Common::get($result, "status", 400) == 200){
            $_SESSION["user"] = Common::get($result, "data", NULL);
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