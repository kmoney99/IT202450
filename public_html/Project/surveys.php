<?php
include_once(__DIR__."/partials/header.partial.php");

if(Common::is_logged_in()){
    //this will auto redirect if user isn't logged in
}
//TODO: Note, internally calling them questionnaires (and for admin), user facing they're called surveys.
$response = DBH::get_available_surveys();
$available = [];
if(Common::get($response, "status", 400) == 200){
    $available = Common::get($response, "data", []);
}
?>

	<html>
    <ul class="navbar-nav mr-auto" style="margin: 0 auto;">
                    <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../home.php">Home</a>
            </li>
			<li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../search.php">Search</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../create_questionnaire.php">Create Custom Survey</a>
                </li>
				
            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../surveys.php">Surveys</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../TakenSurveys.php">Surveys you've Taken</a>
            </li>
                            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../logout.php">Logout</a>
            </li>
            </ul>
</nav>
<div id="messages">
        </div><div class="container-fluid">
    <h4 style="
    margin: auto 0;
    text-align: center;
    padding-top: 4rem;
">Surveys</h4>
    <div class="list-group">
                    <div class="list-group-item" style="
    text-align: center;
">
                <h6>Phones</h6>
                <p>Phones mostly used</p>
                                    <div>Daily Attempts: 1</div>
                                <a href="survey.php?s=1" class="btn btn-secondary">Participate</a>
            </div>
                    <div class="list-group-item" style="
    text-align: center;
">
                <h6>Sports</h6>
                <p>GK on FIFA</p>
                                    <div>Daily Attempts: 1</div>
                                <a href="survey.php?s=2" class="btn btn-secondary">Participate</a>
            </div>
                    </div>
</div></body></html>

















        <?php endforeach; ?>
        <?php if(count($available) == 0):?>
            <div class="list-group-item">
                No surveys available, please check back later.
            </div>
        <?php endif; ?>
    </div>
</div>