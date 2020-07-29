
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once(__DIR__."/partials/header.partial.php");

?>

<link rel="stylesheet" type="text/css" href="style.css">


<form method="POST">

	<label for="num">Title:
		<input type="text" id="d" name="title" />
	</label>
	
	
	<label for="num">Description:
		<input type="text" id="d" name="description" />
	</label>
	

	<label for="Status">Status:
  
		<select id="Category" name="title">
  
		<option value="Draft">Draft</option>
		<option value="Private">Private</option>
		<option value="Public">Public</option>
	
	</label>
	
	
	<input type="submit" name="created" value="Save"/>
  
		</select>


	
	
</form>

<?php

if(isset($_POST["created"])) {
    $title = "";
    $description = "";
	$visibility = "";

    if(isset($_POST["title"]) && !empty($_POST["title"])){
        $title = $_POST["title"];
    }
	if(isset($_POST["description"]) && !empty($_POST["description"])){
        $description = $_POST["description"];
    }
	if(isset($_POST["visibility"]) && !empty($_POST["visibility"])){
        $description = $_POST["visibility"];
    }

    try {
        require("includes/common.inc.php");
        $query = file_get_contents(__DIR__ . "Project/sql/queries/INSERT_TABLE_SURVEY.sql");
        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
            $result = $stmt->execute(array(
                ":title" => $title,
                ":description" => $description,
				":visibility" => $visibility,
				
            ));
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Survey was sucessfully saved: " . $title;
                } else {
                    echo "Error inserting survey";
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

