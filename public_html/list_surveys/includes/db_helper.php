<?php

class DBH{
    private static function getDB(){
        global $common;
        if(isset($common)){
            return $common->getDB();
        }
        throw new Exception("Failed to find reference to common");
    }

    /** Wraps all responses in this wrapper as a contract for whoever calls this helper
     * @param $data
     * @param int $status
     * @param string $message
     * @return array
     */
    private static function response($data, $status = 200, $message = ""){
        return array("status"=>$status, "message"=>$message, "data"=>$data);
    }

    /*** Basic repetitive STMT check, throws exception
     * @param $stmt
     * @throws Exception
     */
    private static function verify_sql($stmt){
        if(!isset($stmt)){
            throw new Exception("stmt object is undefined");
        }
        $e = $stmt->errorInfo();
        if($e[0] != '00000'){
            $error = var_export($e, true);
            error_log($error);
            throw new Exception("SQL Error: $error");
        }
    }
    public static function login($email, $pass){
        try {
            $query = file_get_contents(__DIR__ . "/../sql/queries/login.sql");
            $stmt = DBH::getDB()->prepare($query);
            $stmt->execute([":email" => $email]);
            DBH::verify_sql($stmt);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (password_verify($pass, $user["password"])) {
                    unset($user["password"]);//TODO remove password before we return results
                    //TODO get roles
                    $query = file_get_contents(__DIR__ . "/../sql/queries/get_roles.sql");
                    $stmt = DBH::getDB()->prepare($query);
                    $stmt->execute([":user_id"=>$user["id"]]);
                    DBH::verify_sql($stmt);
                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    error_log(var_export($roles, true));
                    $user["roles"] = $roles;
                    return DBH::response($user);
                } else {
                    return DBH::response(NULL, 403, "Invalid email or password");
                }
            } else {
                return DBH::response(NULL, 403, "Invalid email or password");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
    public static function register($email, $pass){
        try {
            $query = file_get_contents(__DIR__ . "/../sql/queries/register.sql");
            $stmt = DBH::getDB()->prepare($query);
            $pass = password_hash($pass, PASSWORD_BCRYPT);
            $result = $stmt->execute([":email" => $email, ":password" => $pass]);
            DBH::verify_sql($stmt);
            if($result){
                return DBH::response(NULL,200, "Registration successful");
            }
            else{
                return DBH::response(NULL, 400, "Registration unsuccessful");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
    public static function save_questionnaire($questionnaire){
        try {
            
            $query = file_get_contents(__DIR__ . "/../sql/queries/create_questionnaire.sql");
            $stmt = DBH::getDB()->prepare($query);
            $stmt->execute([
                ":name"=>Common::get($questionnaire, "name", null),
                ":desc"=>Common::get($questionnaire, "description", null),
                ":apd"=>Common::get($questionnaire, "attempts_per_day", 1),
                ":ma"=>Common::get($questionnaire, "max_attempts", 1),
                ":um"=>Common::get($questionnaire, "use_max", false)?1:0,//convert to tinyint
                ":uid"=>Common::get_user_id()
            ]);
            DBH::verify_sql($stmt);
            //get id
            $questionnaire_id = DBH::getDB()->lastInsertId();
            //batch insert questions
            $query = file_get_contents(__DIR__ . "/../sql/queries/create_question.sql");
            $params = [];
            $questions = Common::get($questionnaire, "questions", []);
            $qt = count($questions);
            $params[":user_id"] = Common::get_user_id();
            $params[":questionnaire_id"] = $questionnaire_id;
            //this is the only placeholder we need to loop over
            for($i = 0; $i < $qt; $i++){
                $params[":question$i"] = Common::get($questions[$i], "question", '');
                if(($i+1) < $qt) {
                    $ni = $i + 1;
                    $query .= ", (:question$ni, :user_id, :questionnaire_id)";
                }
            }
            error_log(var_export($query));
            $stmt = DBH::getDB()->prepare($query);
            $stmt->execute($params);
            DBH::verify_sql($stmt);
            //fetch ids
            $query = file_get_contents(__DIR__ . "/../sql/queries/get_question_ids_for_questionnaire.sql");
            $stmt = DBH::getDB()->prepare($query);
            $stmt->execute([":qid"=>$questionnaire_id]);
            DBH::verify_sql($stmt);
            $question_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //batch insert answers
            $qIndex = 0;
            $params = [];
            //$params[":user_id"] = Common::get_user_id();
            $query = file_get_contents(__DIR__ . "/../sql/queries/create_answer.partial.sql");
            foreach($questions as $question){
                $answers = Common::get($question, "answers", []);
                //$params[":question_id$qIndex"] = Common::get($results[$qIndex], "id", -1);
                $aIndex = 0;
                foreach($answers as $answer){
                    //TODO attempted named params. This would work, but I felt it was a bit messier to setup
                    // $params[":answer-$qIndex-$aIndex"] = Common::get($answer, "answer",'');
                    //$params[":oe-$qIndex-$aIndex"] = Common::get($answer, "open_ended", false)?1:0;
                    if($qIndex > 0 || $aIndex > 0){
                        $query .= ",";
                    }
                    //TODO switched to using positional placeholders instead
                    $query .= "(?, ?, ?, ?)";
                    array_push($params,
                        Common::get($answer, "answer",""),
                        Common::get($answer, "open_ended", false)?1:0,
                        Common::get_user_id(),
                        Common::get($question_ids[$qIndex], "id", -1)
                    );

                    //$query .= "(:answer-$qIndex-$aIndex, :oe-$qIndex-$aIndex, :user_id, :question_id$qIndex)";
                    $aIndex++;
                }

                $qIndex++;

            }
            error_log(var_export($query, true));
            error_log(var_export($params, true));
            $stmt = DBH::getDB()->prepare($query);
            $result = $stmt->execute($params);
            DBH::verify_sql($stmt);
            if($result){
                return DBH::response(NULL,200, "success");
            }
            else{
                return DBH::response(NULL, 400, "error");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
    public static function get_available_surveys(){
        try {
            //need to use a workaround for PDO
            $query = file_get_contents(__DIR__ . "/../sql/queries/get_available_questionnaires.sql");
            $stmt = DBH::getDB()->prepare($query);
            $result = $stmt->execute([":uid"=>Common::get_user_id()]);//not using associative array here
            DBH::verify_sql($stmt);
            if ($result) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return DBH::response($result,200, "success");
            }
            else{
                return DBH::response($result,400, "error");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
    public static function get_not_available_surveys(){
        try {
            //need to use a workaround for PDO
            $query = file_get_contents(__DIR__ . "/../sql/queries/get_not_available_questionnaires.sql");
            $stmt = DBH::getDB()->prepare($query);
            $result = $stmt->execute([":uid"=>Common::get_user_id()]);//not using associative array here
            DBH::verify_sql($stmt);
            if ($result) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return DBH::response($result,200, "success");
            }
            else{
                return DBH::response($result,400, "error");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
    public static function get_questionnaire_by_id($questionnaire_id){
        try {
            //need to use a workaround for PDO
            $query = file_get_contents(__DIR__ . "/../sql/queries/get_full_questionnaire.sql");
            $stmt = DBH::getDB()->prepare($query);
            $result = $stmt->execute([":questionnaire_id"=>$questionnaire_id]);//not using associative array here
            DBH::verify_sql($stmt);
            if ($result) {
                //TODO check https://phpdelusions.net/pdo PDO::FETCH_GROUP for details
                $result = $stmt->fetchAll(PDO::FETCH_GROUP);
                error_log(var_export($result, true));
                //TODO need to do some mapping
                $questions = [];
                foreach($result as $row){
                    $q = Common::get($row, "question_id", -1);
                    if($q > -1){
                        $found = false;
                        foreach($questions as $check){
                            if(Common::get($check, "id", -1) == $q){
                                $found = true;
                                break;
                            }
                        }
                        if(!$found){
                            $questions
                        }
                    }
                }
                return DBH::response($result,200, "success");
            }
            else{
                return DBH::response($result,400, "error");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
    public static function check_survey_status($questionnaire_id){
        try {
            $query = file_get_contents(__DIR__ . "/../sql/queries/check_survey.sql");

            $user_id = Common::get_user_id();
            $stmt = DBH::getDB()->prepare($query);
            $result = $stmt->execute([":qid"=>$questionnaire_id, ":uid"=>$user_id]);
            DBH::verify_sql($stmt);
            if($result){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return DBH::response($result,200, "success");
            }
            else{
                return DBH::response(NULL, 400, "error");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
    public static function save_response($questionnaire_id, $response){
        try {
            $query = file_get_contents(__DIR__ . "/../sql/queries/insert_response.sql");
            $first = true;
            $params = [];
            $user_id = Common::get_user_id();
            foreach($response as $r){
                if(!$first){
                    $query .= ",";
                }
                $first = false;
                $query .= "(?,?,?,?,?)";

                array_push($params,
                    $questionnaire_id,
                    Common::get($r, "question_id", -1),
                    Common::get($r, "answer_id", -1),
                    Common::get($r, "user_input", null),
                    $user_id
                );
            }
            $stmt = DBH::getDB()->prepare($query);
            $result = $stmt->execute($params);
            DBH::verify_sql($stmt);
            if($result){
                return DBH::response(null,200, "success");
            }
            else{
                return DBH::response(NULL, 400, "error");
            }
        }
        catch(Exception $e){
            error_log($e->getMessage());
            return DBH::response(NULL, 400, "DB Error: " . $e->getMessage());
        }
    }
}