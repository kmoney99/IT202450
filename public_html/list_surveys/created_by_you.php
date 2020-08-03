<?php

include_once(__DIR__."/partials/header.partial.php");
require("common.inc.php");
$query = file_get_contents(__DIR__ . "/queries/SELECT_ALL_TABLE_SURVEY.sql");
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
?>

<?php if(isset($results)):?>
    <p>Surveys created by you</p>
        <?php foreach($results as $row):?>
		
                <?php echo get($row, "title")?>

