<?php

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
                die(header("Location: list.php"));
            }
            else{
                echo var_export($e, true);
            }
        }
    }
}
else{
    echo "Invalid thing to delete";
}