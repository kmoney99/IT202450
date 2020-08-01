<?php
include_once(__DIR__."/partials/header.partial.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');


/*
<form method="POST">

  <label for="question">Question:</label><br>
  <input type="text" id="question" name="question" value=""><br>
  <label for="answer">Answer:</label><br>
  <input type="text" id="answer" name="answer" value=""><br><br>
 
 <input type="submit" name="created" value="Save"/>

</form>
*/



require("common.inc.php");
$query = file_get_contents(__DIR__ . "/sql/queries/SELECT_ALL_TABLE_SURVEY.sql");
if(isset($query) && !empty($query)){
    try {
        $stmt = getDB()->prepare($query);
       
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
	if(isset($results)) {
		
    echo "Question";
	
		foreach($results as $row) {
            
            echo get($row, "id");
				
        }
		
	}
	else {
		echo "Failed to open Survey";
		
	}
?>
<?php
if(isset($_POST["created"])) {
    $id = "";
    $question = "";

    if(isset($_POST["id"]) && !empty($_POST["id"])){
        $id = $_POST["id"];
    }
	if(isset($_POST["question"]) && !empty($_POST["question"])){
        $question = $_POST["question"];
    }
    try {
        require("common.inc.php");
        $query = file_get_contents(__DIR__ . "/sql/queries/create_question.sql");
        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
            $result = $stmt->execute(array(
                ":id" => $id,
                ":question" => $question,
				
            ));
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Question was sucessfully added:", " " .$id;
                } else {
                    echo "Error inserting question";
                }
            }
        }
        else{
            echo "Failed to find create_question.sql file";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
?>
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