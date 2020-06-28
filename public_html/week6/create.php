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
	<label for="num">Enter your Question Number:
	<input type="number" id="d" name="number" />
	</label>
	<input type="submit" name="created" value="Create Question"/>
	
</form>

<?php 
if(isset($_POST["created"])) {
    $name = "";
    $quantity = -1;
    if(isset($_POST["name"]) && !empty($_POST["name"])){
        $name = $_POST["name"];
    }
    if(isset($_POST["quantity"]) && !empty($_POST["quantity"])){
        if(is_numeric($_POST["quantity"])){
            $quantity = (int)$_POST["quantity"];
        }
    }
    //If name or quantity is invalid, don't do the DB part
    if(empty($name) || $quantity < 0 ){
        echo "Name must not be empty and quantity must be greater than or equal to 0";
        die();//terminates the rest of the script
    }
    try {
        require("common.inc.php");
        $query = file_get_contents(__DIR__ . "/queries/INSERT_TABLE_THINGS.sql");
        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
            $result = $stmt->execute(array(
                ":name" => $name,
                ":quantity" => $quantity
            ));
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Successfully inserted new thing: " . $name;
                } else {
                    echo "Error inserting record";
                }
            }
        }
        else{
            echo "Failed to find INSERT_TABLE_THINGS.sql file";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
?>