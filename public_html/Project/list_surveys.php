<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once(__DIR__."/partials/header.partial.php");

require("config.php");
			
			$common->getDB()->prepare("SELECT * FROM SURVEY where title = :title LIMIT 1");
				
			$stmt->execute(array(
				":title" => $title
			));
			
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
		
						
?>