<?php
$search = "";
if(isset($_POST["search"])){
    $search = $_POST["search"];
}
?>
<link rel="stylesheet" type="text/css" href="sty.css">
<h3> Search for survey question </h3>
<form method="POST">
<div class="container">
  <input placeholder='Search...' class='js-search' type="text"  value="<?php echo $search;?>"/>
  <i class="fa fa-search"></i>
  <input type="submit" value="Search"/>
</div>
</form>

</form>
<?php
if(isset($search)) {

    require("common.inc.php");
    $query = file_get_contents(__DIR__ . "/queries/SEARCH_TABLE_SURVEY.sql");
    if (isset($query) && !empty($query)) {
        try {
            $stmt = getDB()->prepare($query);
            //Note: With a LIKE query, we must pass the % during the mapping
            $stmt->execute([":survey"=>$search]);
            //Note the fetchAll(), we need to use it over fetch() if we expect >1 record
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>
<!--This part will introduce us to PHP templating,
note the structure and the ":" -->
<!-- note how we must close each check we're doing as well-->
<?php if(isset($results) && count($results) > 0):?>
    <p>Results</p>
    <ul>
        <!-- Here we'll loop over all our results and reuse a specific template for each iteration,
        we're also using our helper function to safely return a value based on our key/column name.-->
        <?php foreach($results as $row):?>
            <li>
                <?php echo get($row, "title")?>
                <?php echo get($row, "description");?>
                <a href="delete.php?surveyID=<?php echo get($row, "id");?>">Delete</a>
            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
    <p>This shows when we don't have results</p>
<?php endif;?>