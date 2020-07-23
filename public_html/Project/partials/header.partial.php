<?php
require_once (__DIR__."/../includes/common.inc.php");
$logged_in = Common::is_logged_in(false);
?>
<!-- Bootstrap 4 CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<!-- Include jQuery 3.5.1-->
<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <ul class="navbar-nav mr-auto">
	 <li class="nav-item">
            <a class="nav-link" href="<?php echo Common::url_for("surveys");?>">Surveys</a>
        </li>
        <?php endif; ?>
        <?php if(!$logged_in):?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Common::url_for("login");?>">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Common::url_for("register");?>">Register</a>
            </li>
        <?php else:?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo Common::url_for("logout");?>">Logout</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<div id="messages">
    <?php $flash_messages = Common::getFlashMessages();?>
    <?php if(isset($flash_messages) && count($flash_messages) > 0):?>
        <?php foreach($flash_messages as $msg):?>
            <div class="alert alert-<?php echo Common::get($msg, "type");?>"><?php
                echo Common::get($msg, "message");
                //We have the opening and closing tags right after/before the div tags to remove any whitespace characters
                ?></div>
        <?php endforeach;?>
    <?php endif;?>
</div>