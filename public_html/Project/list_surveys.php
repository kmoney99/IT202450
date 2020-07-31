<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once(__DIR__."/partials/header.partial.php");


include_once(__DIR__."/includes/common.inc.php");
			
			
			
			$common->getDB()->prepare("SELECT title from SURVEY");
				
			$result = $stmt->execute(array(
                ":title" => $title,
			));
			
			$stmt = $common->getDB()->prepare($result);
				
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
