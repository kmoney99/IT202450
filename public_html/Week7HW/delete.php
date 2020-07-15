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
    if(!$result){
        $QuestionId = -1;
    }
}
else{
    echo "No QuestionId provided in url, don't forget this or sample won't work.";
}
?>

    <form method="POST">
        <label for="Question">Question Name
            <input type="text" id="Question" name="Question" value="<?php echo get($result, "Question");?>" />
        </label>
        <?php if($QuestionId > 0):?>
            <input type="submit" name="delete" value="Delete Question"/>
        <?php endif;?>
    </form>

<?php
if(isset($_POST["updated"]) || isset($_POST["created"]) || isset($_POST["delete"])){
    $delete = isset($_POST["delete"]);
    $Question = $_POST["Question"];
    if(!empty($Question)){
        try{
            if($QuestionId > 0) {
                if($delete){
                    $stmt = $db->prepare("DELETE from Questions where id=:id");
                    $result = $stmt->execute(array(
                        ":id" => $QuestionId
                    ));
                }

            }

            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    echo "Successfully interacted with Question: " . $Question;
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
        echo "Question must not be empty.";
    }
}
?>