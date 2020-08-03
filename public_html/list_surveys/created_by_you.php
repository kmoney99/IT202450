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
    <p>Surveys you created</p>
    <ul>
        <div>
        <?php foreach($results as $row):?>
            <li>
                <?php echo get($row, "title")?>
			
		</div>
            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
    <p>You have not created one yet.</p>
<?php endif;?>