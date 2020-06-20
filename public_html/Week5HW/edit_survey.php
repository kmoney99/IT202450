<?php
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$thingId = -1;
$result = array();
function get($arr, $key){
    if(isset($arr[$key])){
        return $arr[$key];
    }
    return "";
}
if(isset($_GET["thingId"])){
    $thingId = $_GET["thingId"];
    $stmt = $db->prepare("SELECT * FROM Survey where id = :id");
    $stmt->execute([":id"=>$thingId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$result){
        $thingId = -1;
    }
}
// else{
   // echo "No thing Id provided in url, don't forget this or sample won't work.";
//}
?>

<form method="POST">
	<label for="thing">Edit the Question's Category:
	<input type="text" id="thing" name="name" value="<?php echo get($result, "title");?>" />
	</label>
	<label for="q">Edit the Question:
	<input type="text" id="q" name="quantity" value="<?php echo get($result, "description");?>" />
	</label>
    <?php if($Id > 0):?>
	    <input type="submit" name="updated" value="Update Question"/>
        <input type="submit" name="delete" value="Delete Question"/>
    <?php elseif ($Id < 0):?>
        <input type="submit" name="created" value="Create Question"/>
    <?php endif;?>
</form>

<?php
if(isset($_POST["updated"]) || isset($_POST["created"]) || isset($_POST["delete"])){
    $delete = isset($_POST["delete"]);
    ":title" => $title,
    ":description" => $description
    if(!empty($title) && !empty($description)){
        try{
            if($thingId > 0) {
                if($delete){
                    $stmt = $db->prepare("DELETE from Survey where id=:id");
                    $result = $stmt->execute(array(
                        ":id" => $Id
                    ));
                }
                else {
                    $stmt = $db->prepare("UPDATE Survey set title = :title, description=:description where id=:id");
                    $result = $stmt->execute(array(
                        ":title" => $title,
						":description" => $description
                        ":id" => $Id
                    ));
                }
            }
            else{
                $stmt = $db->prepare("INSERT INTO Survey (title, description) VALUES (:title, :description)");
                $result = $stmt->execute(array(
                    ":title" => $title,
					":description" => $description
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