<?php

include_once(__DIR__."/partials/header.partial.php");
require("common.inc.php");
$query = file_get_contents(__DIR__ . "/queries/SELECT_ALL_TABLE_SURVEY.sql");
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
?>

<?php if(isset($results)):?>
    <h3>Add Question</h3>
    <ul>
        
        <?php foreach($results as $row):?>
            <li>
                <?php echo get($row, "id")?>
				
            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
   <?php echo "Failed to open Survey"
<?php endif;?>

<form>
  <label for="question">Question:</label><br>
  <input type="text" id="question" name="question" value=""><br>
  <label for="answer">Answer:</label><br>
  <input type="text" id="answer" name="answer" value=""><br><br>
  <input type="submit" name="created" value="Add Question">
</form>

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
                    echo "Question was sucessfully added: " . $id;
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
