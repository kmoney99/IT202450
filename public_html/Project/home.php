<?php
include_once(__DIR__."/partials/header.partial.php");

if(Common::is_logged_in()){
    //this will auto redirect if user isn't logged in
}
?>
<div>
    <p style="
    margin: 3em auto;
    width: fit-content;
    font-weight: bold;
    font-size: 1.5em;
">Welcome, <?php echo Common::get_username();?></p>
</div>