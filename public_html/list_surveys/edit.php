<?php
$surveyID = -1;
if(isset($_GET["surveyID"]) && !empty($_GET["surveyID"])){
    $surveyID = $_GET["surveyID"];
}
$result = array();
require("common.inc.php");
?>
<?php
if(isset($_POST["updated"])){
    $title = "";
    $description "";
    if(isset($_POST["title"]) && !empty($_POST["title"])){
        $title = $_POST["title"];
    }
    if(isset($_POST["description"]) && !empty($_POST["description"])){
        if(is_numeric($_POST["description"])){
            $description = (int)$_POST["description"];
        }
    }
    if(!empty($title) && (!empty($description))){
        try{
            $query = NULL;
            echo "[description" . $description . "]";
            $query = file_get_contents(__DIR__ . "/queries/UPDATE_TABLE_THINGS.sql");
            if(isset($query) && !empty($query)) {
                $stmt = getDB()->prepare($query);
                $result = $stmt->execute(array(
                    ":title" => $title,
                    ":description" => $description,
                    ":id" => $surveyID
                ));
                $e = $stmt->errorInfo();
                if ($e[0] != "00000") {
                    echo var_export($e, true);
                } else {
                    if ($result) {
                        echo "Successfully updated Survey: " . $title;
                    } else {
                        echo "Error updating record";
                    }
                }
            }
            else{
                echo "Failed to find UPDATE_TABLE_SURVEY.sql file";
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Title and Description must not be empty.";
    }
}
?>

<?php
//moved the content down here so it pulls the update from the table without having to refresh the page or redirect
//now my success message appears above the form so I'd have to further restructure my code to get the desired output/layout
if($surveyID > -1){
    $query = file_get_contents(__DIR__ . "/queries/SELECT_ONE_TABLE_SURVEY.sql");
    if(isset($query) && !empty($query)) {
        //Note: SQL File contains a "LIMIT 1" although it's not necessary since ID should be unique (i.e., one record)
        try {
            $stmt = getDB()->prepare($query);
            $stmt->execute([":id" => $surveyID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Failed to find SELECT_ONE_TABLE_SURVEY.sql file";
    }
}
else{
    echo "No surveyID provided in url, don't forget this or sample won't work.";
}
?>
<link rel="stylesheet" type="text/css" href="style.css">

<h1> Edit your survey question here! </h1>
<form method="POST">
<label for="Question Cat">Pick a question category:
  <select id="Category" name="title">
  <option value="sports">Sports</option>
  <option value="countries">Countries</option>
  <option value="internet">World Wide Web</option>
</label>
  
  </select>

 <label for="q">Quantity
        <input type="number" id="q" name="quantity" required min="0" />
    </label>
  
 <label for="num">Enter your Question:
	<input type="text" id="d" name="description" />
	</label>
	<input type="submit" name="created" value="Edit Question"/>
	
</form>

