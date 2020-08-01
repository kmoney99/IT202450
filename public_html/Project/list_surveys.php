<?php

include_once(__DIR__."/partials/header.partial.php");
require("com.inc.php");
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
?>

<?php if(isset($results)):?>
    <p>Surveys By Title</p>
    <ul>
        
        <?php foreach($results as $row):?>
            <li>
                <?php echo get($row, "title")?>
				
				<a href="add_question.php?surveyID=<?php echo get($row, "id");?>">Add Question</a>
				
                <a href="delete.php?surveyID=<?php echo get($row, "id");?>">Delete Survey</a>
				
				<a href="view_survey.php?surveyID=<?php echo get($row, "id");?>">View Survey</a>
				
            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
    <p>No Surveys at this time.</p>
<?php endif;?>