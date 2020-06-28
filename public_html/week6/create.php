<script src="js/script.js"></script>
<!-- note although <script> tag "can" be self terminating some browsers require the
full closing tag-->
<form method="POST" onsubmit="return validate(this);">
    <label for="survey">Survey Name
        <input type="text" id="survey" name="title" required />
    </label>
    <label for="q">Description
        <input type="text" id="description" name="description"/>
    </label>
    <input type="submit" name="created" value="Create Survey"/>
</form>
<?php

if(isset($_POST["created"])) {
    $title = "";
    $description = "";
    if(isset($_POST["title"]) && !empty($_POST["title"])){
        $title = $_POST["title"];
    }
    if(isset($_POST["description"]) && !empty($_POST["description"])){
        if(is_numeric($_POST["description"])){
            $description = (int)$_POST["description"];
        }
    }
if(empty($title) || (empty($description))) {
        
		echo "Fields cannot be empty";
		
        die();
	 }
    try {
        require("common.inc.php");
        $query = file_get_contents(__DIR__ . "/queries/INSERT_TABLE_SURVEY.sql");
        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
            $result = $stmt->execute(array(
                ":title" => $title,
                ":description" => $description
            ));
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Successfully inserted new Survey: " . $title;
                } else {
                    echo "Error inserting record";
                }
            }
        }
        else{
            echo "Failed to find INSERT_TABLE_SURVEY.sql file";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
?>	
