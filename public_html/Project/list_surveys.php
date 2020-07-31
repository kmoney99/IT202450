<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once(__DIR__."/partials/header.partial.php");

			
			
			/*
			$common->getDB()->prepare("SELECT title from SURVEY");
			
			$stmt = $common->getDB()->prepare($result);
			
			$result = $stmt->execute(array(
                ":title" => $title,
			));
		*/
	
        $query = file_get_contents(__DIR__ . "/sql/queries/INSERT_TABLE_SURVEY.sql");
        if(isset($query) && !empty($query)) {
			
			$sql = "SELECT title from SURVEY (title) values (:title)";
			
			$stmt = $common->getDB()->prepare ($sql);
			
			$stmt -> execute ([":title" =>$title]);
            
			$stmt = $common->getDB()->prepare($query);
			
            
			$result = $stmt->execute(array(
			    ":title" => $title,
            
			));
			$e = $stmt->errorInfo();
				if($e[0] != "00000"){
					echo var_export($e, true);
				}
				else{
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					if ($result){
						echo "Survey" . $title; 
					}
				}
			
		}
?>
