<link rel="stylesheet" type="text/css" href="style.css">

<h1> Create your survey question here! </h1>
<form method="POST">
<label for="Question Cat">Pick a question category:
  <select id="Category" name="title">
  <option value="Sports">Sports</option>
  <option value="Countries">Countries</option>
  <option value="Internet">World Wide Web</option>
</label>
  
  </select>

 <label for="num">Enter your Question:
	<input type="text" id="d" name="description" />
	</label>
	<input type="submit" name="created" value="Create Question"/>
	
</form>

<?php
if(isset($_POST["created"])) {
    $Question = "";
    $Answer = "";
    if(isset($_POST["Question"]) && !empty($_POST["Question"])){
        $Question = $_POST["Question"];
    }
            if(isset($_POST["Answer"]) && !empty($_POST["Answer"])){
                if(is_numeric($_POST["Answer"])){
                    $Answer = (int)$_POST["Answer"];
                }
            }
            //If field is invalid, don't do the DB part
                if(empty($Question) || $Answer < 0 ){
                    echo "Question must not be empty and Answer must be greater than or equal to 0";
                    die();//terminates the rest of the script
                }
                try {
                    require("common.inc.php");
                    $query = file_get_contents(__DIR__ . "/queries/InsertInto.sql");
                    if(isset($query) && !empty($query)) {
                        $stmt = getDB()->prepare($query);
                        $result = $stmt->execute(array(
                            ":Question" => $Question,
                ":Answer" => $Answer
            ));
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Successfully inserted new Question: " . $Question;
                } else {
                    echo "Error inserting record";
                }
            }
        }
                    else{
                        echo "Failed to find InsertInto.sql file";
                    }
                }
                catch (Exception $e){
                    echo $e->getMessage();
                }
            }
            ?>