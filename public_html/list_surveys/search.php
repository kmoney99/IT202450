<head>
    <title>Simple Survey</title>
    <link rel="stylesheet" type="text/css" href="../ajaxqueries/style2.css">
    <p>
        Search Page
    </p>
</head>
<?php
$search = "";
if(isset($_POST["search"], $_POST["SortBy"])){
    $search = $_POST["search"];
    $Sort = $_POST["SortBy"];
}
?>
<form method="POST">
    <input type="text" name="search" placeholder="Search for Question"
           value="<?php echo $search;?>"/>
    <label for="SortBy">SortBy</label>
    <select id="SortBy" name="SortBy">
        <option value="Ascending">Ascending Order</option>
        <option value="Descending">Descending Order</option>
        <input type="submit"
    </select>
</form>
<?php
if(isset($Sort) && isset($search)) {
    require("common.inc.php");
    $query = file_get_contents(__DIR__ . "/queries/SearchTable.sql");
    if (isset($query) && !empty($query)) {
        if($Sort["SortBy"]=="Ascending"){
            $query = file_get_contents(__DIR__ . "/queries/ASC.sql");
            try {
                $stmt = getDB()->prepare($query);
                //Note: With a LIKE query, we must pass the % during the mapping
                $stmt->execute([":question"=>$search ]);
                //Note the fetchAll(), we need to use it over fetch() if we expect >1 record
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        elseif($Sort["SortBy"]=="Descending"){
            $query = file_get_contents(__DIR__ . "/queries/DESC.sql");
            try {
                $stmt = getDB()->prepare($query);
                //Note: With a LIKE query, we must pass the % during the mapping
                $stmt->execute([":question"=>$search]);
                //Note the fetchAll(), we need to use it over fetch() if we expect >1 record
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

    }
}
?>
<!--This part will introduce us to PHP templating,
note the structure and the ":" -->
<!-- note how we must close each check we're doing as well-->
<?php if(isset($results) && count($results) > 0):?>
    <p>This shows when we have results</p>
    <ul>
        <!-- Here we'll loop over all our results and reuse a specific template for each iteration,
        we're also using our helper function to safely return a value based on our key/column name.-->
        <?php foreach($results as $row):?>
            <li>
                <?php echo get($row, "question")?>
                <a href="../ajaxqueries/delete.php?QuestionId=<?php echo get($row, "id");?>Delete</a>
            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
    <p>This shows when we don't have results</p>
<?php endif;?>