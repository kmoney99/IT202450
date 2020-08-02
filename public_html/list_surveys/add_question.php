<?php
include_once(__DIR__."/partials/header.partial.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*$query = file_get_contents(__DIR__ . "/sql/queries/SELECT_ALL_TABLE_SURVEY.sql");
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

*/
?>


<form method="POST">

 <div class="list-group-item">
        <div class="form-group">
            <label for="answer">Question:</label>
                <input class="form-control" type="text" id="0" name="question" required/>
        </div>
		</div>
   <div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 1:</label>
                <input class="form-control" type="text" id="answer 1" name="answer" required/>
        </div>
	<div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 2:</label>
                <input class="form-control" type="text" id="answer 2" name="answer" required/>
        </div>
	<div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 3:</label>
                <input class="form-control" type="text" id="answer 3" name="answer" />
        </div>
	<div class="list-group-item">
        <div class="form-group">
            <label for="answer">Answer Choice 4:</label>
                <input class="form-control" type="text" id="answer 4" name="answer" />
        </div>
		
		
	<input type="submit" name="created" value="Save"/>

</form>
<?php
include_once(__DIR__."/partials/header.partial.php");


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
			$sql="Insert into Question(id,question) values (:id,:question)";
			$stmt=$common->getDB()->prepare($sql);
			$stmt->execute([":id"=>$id,":question"=>$question]);
            $result=$stmt->execute(array(":id"=>$id,":question"=>$question));
			$e=$stmt->errorInfo();
			
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Question was sucessfully added";
                } else {
                    echo "Error inserting question";
                }
            }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}


?>
		
<?php

if(isset($_POST["created"])) {
    $id = "";
    $answer = "";
	
	
    if(isset($_POST["id"]) && !empty($_POST["id"])){
        $id = $_POST["id"];
    }
	if(isset($_POST["answer"]) && !empty($_POST["answer"])){
        $answer = $_POST["answer"];
    }
	
	try {
			$sql="Insert into Answers(id,answer) values (:id,:answer)";
			$stmt=$common->getDB()->prepare($sql);
			$stmt->execute([":id"=>$id, ":answer"=>$answer]);
            $result=$stmt->execute(array(":id"=>$id, ":answer"=>$answer));
			$e=$stmt->errorInfo();
			
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "& Answer was stored!";
                } else {
                    echo "Error inserting question";
                }
            }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}

?>
