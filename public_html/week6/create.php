<link rel="stylesheet" type="text/css" href="style.css">

<h1> Create your survey question here! </h1>
<form method="POST">
<label for="Question Cat">Pick a question category:
  <select id="Category" name="title">
  <option value="sports">Sports</option>
  <option value="countries">Countries</option>
  <option value="internet">World Wide Web</option>
</label>
  
  </select>


  
 <label for="num">Enter your Question:
	<input type="text" id="d" name="description" />
	</label>
	<input type="submit" name="created" value="Create Question"/>
	
</form>

<?php

if(isset($_POST["created"])) {
    $title = "";
    $description = "";
    if(isset($_POST["title"]) && !empty($_POST["title"])){
        $title = $_POST["title"];
    }
	if(isset($_POST["description"]) && !empty($_POST["description"])){
        $description = $_POST["description"];
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
