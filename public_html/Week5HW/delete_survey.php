<?php
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$survey_id = -1;
$result = array();
function get ($arr, $key){
	if(isset($arr[$key])){
		return $arr[$key];
	}
	return "";
}
if(isset($_GET["survey_id"])){
    $thingId = $_GET["survey_id"];
    $stmt = $db->prepare("SELECT * FROM Survey where id = :id");
    $stmt->execute([":id"=>$survey_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
else{
    echo "No thing Id provided in url, don't forget this or sample won't work.";
}






?>

<link rel="stylesheet" type="text/css" href="style.css">

<h1> Delete your survey question here! </h1>
<form method="POST">
<label for="Question Cat">Pick a question category:
  <select id="Category" name="title">
  <option value="sports">Sports</option>
  <option value="countries">Countries</option>
  <option value="internet">World Wide Web</option>
</label>
 <?php if($survey_id > 0):?>
	    <input type="submit" name="updated" value="Update Survey"/>
        <input type="submit" name="delete" value="Delete Survey"/>
 <?php elseif ($survey_id < 0):?>
        <input type="submit" name="created" value="Create Survey"/>
    <?php endif;?>
  
  </select>

  
 <label for="num">Edit your Question:
	<input type="text" id="d" name="description" />
	</label>
	<input type="submit" name="created" value="Edit Question"/>
	
</form>

<?php
if(isset($_POST["updated"]) || isset($_POST["created"]) || isset($_POST["delete"])){
    $delete = isset($_POST["delete"]);
	$title = $_POST["title"];
	$description  = $_POST["description"];
	if(!empty($title) && !empty($description)){
			try{
            if($survey_id > 0) {
                if($delete){
                    $stmt = $db->prepare("DELETE from Survey where id=:id");
                    $result = $stmt->execute(array(
                        ":id" => $survey_id
                    ));
                }
		 else {
                    $stmt = $db->prepare("INSERT INTO Survey (title, description) VALUES (:title, :description)");
                    $result = $stmt->execute(array(
                      ":title" => $title,
					  ":description" => $description,
					  ":id" => $survey_id
                    ));
                }
            }
		
		 else{
                $stmt = $db->prepare("INSERT INTO Survey (title, description) VALUES (:title, :description)");
                $result = $stmt->execute(array(
					 ":title" => $title,
					 ":description" => $description,
                   
                ));
            }
		
		$e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    echo "Successfully interacted with thing: " . $name;
                }
                else{
                    echo "Error interacting record";
                }
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Name and quantity must not be empty.";
    }
}
?>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		