<?php
require(__DIR__ . "/includes/common.inc.php");
$query = true;
if(isset($query) && !empty($query)){
    require(__DIR__ ."/includes/config.php");

    require("config.php");
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    try {
        $stmt = getDB()->prepare("Select * From Questionnaires");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
?>
    <!--This part will introduce us to PHP templating,
    note the structure and the ":" -->
    <!-- note how we must close each check we're doing as well-->
<?php if(isset($results)):?>
    <p>This shows when we have results</p>
    <ul>
        <!-- Here we'll loop over all our results and reuse a specific template for each iteration,
        we're also using our helper function to safely return a value based on our key/column name.-->
        <?php foreach($results as $row):?>
            <li>
                <?php echo get($row, "name");?>
                <?php echo get($row, "description");?>
            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
    <p>This shows when we don't have results</p>
<?php endif;?>
