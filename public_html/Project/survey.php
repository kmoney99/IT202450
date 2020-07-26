<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once(__DIR__."/partials/header.partial.php");

if(Common::is_logged_in()){
    //this will auto redirect if user isn't logged in
}
if(isset($_GET["s"])){
    $questionnaire_id = $_GET["s"];
}
else{
    Common::flash("Not a valid survey", "warning");
    die(header("Location: surveys.php"));
}
if(!Common::get($_POST, "submit", false)){
    $response = DBH::check_survey_status($questionnaire_id);
    if(Common::get($response, "status", 400) == 200){
        $data = Common::get($response, "data", []);
        error_log(var_export($data, true));
        $use_max = Common::get($data, "use_max", 0) == 1;
        $today = (int)Common::get($data, "responses_today", 0);
        $total = (int)Common::get($data, "responses_total", 0);
        $max = (int)Common::get($data, "max_attempts", 0);
        $apd = (int)Common::get($data, "attempts_per_day", 0);
        if($use_max){
            //TODO check query as this part doesn't seem accurate with the group by
            //but the group by is needed for the "today" check
            if($total >= $max){
                Common::flash("Max responses have already been recorded for this survey", "warning");
                die(header("Location: surveys.php"));
            }
        }
        else{
            if($today >= $apd){
                Common::flash("Max responses have already been recorded today for this survey", "warning");
                die(header("Location: surveys.php"));
            }
        }
    }
}
//TODO: Note, internally calling them questionnaires (and for admin), user facing they're called surveys.
$response = DBH::get_questionnaire_by_id($questionnaire_id);
$available = [];
if(Common::get($response, "status", 400) == 200){
    $available = Common::get($response, "data", []);
}
?>
<?php
if(Common::get($_POST, "submit", false)){
    //echo "<br><pre>" . var_export($_POST, true) . "</pre>";
    $response = [];
    foreach($_POST as $key=>$value){
        //echo "<br>$key => $value<br>";
        if(strpos($key, "question") !== false) {
            $is_other = false;
            $question_id = (int)explode("-", $key)[1];
            if (strpos($key, "other") !== false) {
                if (trim(strlen($value)) > 0) {
                    $is_other = true;
                    $answer_id = (int)explode("-", $key)[3];
                    array_push($response, ["question_id" => $question_id, "answer_id" => $answer_id, "user_input" => $value]);
                }
            }
            else{
                array_push($response, ["question_id" => $question_id, "answer_id" => $value]);
            }
        }
    }
    //echo "<br><pre>" . var_export($response, true) . "</pre>";
    if(count($response) > 0){
        $response = DBH::save_response($questionnaire_id, $response);
        if(Common::get($response, "status", 400) == 200){
            Common::flash("Successfully recorded response", "success");
            $result = DBH::changePoints(Common::get_user_id(), 1, -1, "survey", "Thanks for taking this survey");
            if(Common::get($result, "status", 400) == 200) {
                Common::flash("You earned 1 point, thanks for participating!", "success");
            }
        }
        else{
            Common::flash("Error recording response", "danger");
        }
    }
    else{
        Common::flash("Error recording response", "danger");
    }
    die(header("Location: surveys.php"));
}
?>
<div class="container-fluid">
    <!-- see https://www.w3schools.com/php/func_array_reset.asp for use of current() function -->
    <h3><?php echo Common::get(current($available)[0], "questionnaire_name");?></h3>
    <form method="POST">
        <div class="list-group">
            <?php foreach($available as $s): ?>
                <div class="list-group-item">
                    <h4><?php echo Common::get($s[0], "question"); ?></h4>
                    <div class="list-group">
                        <?php foreach($s as $question):?>
                            <div class="list-group-item btn-group-toggle bg-light" data-toggle="buttons">
                                <?php
                                $id = "answer-".Common::get($question, "question_id") . "-" . Common::get($question, "answer_id");
                                ?>
                                <?php if(Common::get($question, "open_ended", false)):?>
                                    <label for="<?php echo $id;?>"><?php echo Common::get($question, "answer");?></label>
                                    <input id="<?php echo $id;?>" class="form-control" type="text"
                                           name="question-<?php echo Common::get($question,"question_id", -1);?>-other-<?php echo Common::get($question, "answer_id", -1);?>" />
                                <?php else:?>

                                    <label class="btn btn-secondary btn-lg btn-block" for="<?php echo $id;?>">
                                        <?php echo Common::get($question, "answer");?>
                                        <input id="<?php echo $id;?>" autocomplete="off" type="radio"
                                               name="question-<?php echo Common::get($question,"question_id", -1);?>"
                                               value="<?php echo Common::get($question, "answer_id", -1);?>"
                                               onclick="selectChoice(this);"/>
                                    </label>
                                <?php endif; ?>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Submit Response"/>
    </form>
</div>
<script>
    function selectChoice(ele){
        $("[name=" + $(ele).attr("name") + "]").each(function(index, item){
            $(item).closest("label").removeClass("active");
        });
        $(ele).closest("label").addClass("active");
    }
</script>