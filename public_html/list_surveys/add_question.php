<?php
include_once(__DIR__."/partials/header.partial.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>


<form method="POST">

 <div class="list-group-item">
        <div class="form-group">
            <label for="answer">Question:</label>
                <input class="form-control" type="text" id="question" name="question" required/>
        </div>
 </div>
   <div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 1:</label>
                <input class="form-control" type="text" id="answer" name="question" required/>
        </div>
	<div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 2:</label>
                <input class="form-control" type="text" id="answer" name="question" required/>
        </div>
	<div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 3:</label>
                <input class="form-control" type="text" id="answer" name="question" />
        </div>
	<div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 4:</label>
                <input class="form-control" type="text" id="answer" name="question" />
        </div>
					
	<input type="submit" name="created" value="Save"/>

</form>


<?php
//require("kush.inc.php");
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
include_once(__DIR__."/partials/header.partial.php");
error_reporting(E_ALL);
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
        $query = file_get_contents(__DIR__ . "/sql/queries/017_CREATE_TABLE_QUESTIONS.sql");
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

