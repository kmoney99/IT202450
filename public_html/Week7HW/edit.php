<?php
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$QuestionId = -1;
$result = array();
function get($arr, $key){
    if(isset($arr[$key])){
        return $arr[$key];
    }
    return "";
}
if(isset($_GET["QuestionId"])){
    $QuestionId = $_GET["QuestionId"];
    $stmt = $db->prepare("SELECT * FROM Questions where id = :id");
    $stmt->execute([":id"=>$QuestionId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
else{
    echo "No QuestionId provided in url.";
}
?>

<form method="POST">
    <label for="thing">Question
        <input type="text" id="Question" name="Question" value="<?php echo get($result, "Question");?>" />
    </label>
        <input type="submit" name="updated" value="Update Question"/>
</form>

<?php
if(isset($_POST["updated"])){
    $Question = $_POST["Question"];
    if(!empty($Question)){
        try{
            $stmt = $db->prepare("UPDATE Questions set Question = :Question where id=:id");
            $result = $stmt->execute(array(
                ":Question" => $Question,
                ":id" => $QuestionId
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    echo "Successfully updated Question: " . $Question;
                }
                else{
                    echo "Error updating record";
                }
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Question must not be empty.";
    }
}
?>