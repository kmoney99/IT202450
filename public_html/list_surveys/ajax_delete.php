<?php
$result = array("status"=>200, "message"=>"Nothing happened");
if (isset($_GET["surveyID"]) && !empty($_GET["surveyID"])){
    if(is_numeric($_GET["surveyID"])){
        $surveyID = (int)$_GET["surveyID"];
        $query = file_get_contents(__DIR__ . "/queries/DELETE_ONE_TABLE_SURVEY.sql");
        if(isset($query) && !empty($query)) {
            require("common.inc.php");
            $stmt = getDB()->prepare($query);
            $stmt->execute([":id"=>$surveyID]);
            $e = $stmt->errorInfo();
                if($e[0] == "00000"){
                    //we're just going to redirect back to the list
					
                    //it'll reflect the delete on reload
                    //also wrap it in a die() to prevent the script from any continued execution
                    $result["message"] = "Successfully deleted Survey";
                }
                else{
                    $result["message"] = "Error deleting Survey";
                    $result["error"] = var_export($e,true);
                    $result["status"] = 400;
            }
        }
    }
}
else{
    $result["message"] = "Invalid survey to delete";
    $result["status"] = 400;
}
echo json_encode($result);
?>