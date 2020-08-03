<?php

include_once(__DIR__."/partials/header.partial.php");

$query = file_get_contents(__DIR__ . "/sql/queries/SELECT_ALL_TABLE_SURVEY.sql");
if(isset($query) && !empty($query)){
    try {
        $stmt = getDB()->prepare($query);
       
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
	if(isset($results)) {
		
    echo "Question";
	
		foreach($results as $row) {
            
            echo get($row, "id");
				
        }
		
	}
	else {
		echo "Failed to open Survey";
		
	}

?>