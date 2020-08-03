<?php
include_once(__DIR__."/partials/header.partial.php");
$search = "";
if(isset($_POST["search"])){
    $search = $_POST["search"];
}
?>

<h3> Search for survey question </h3>
<form method="POST">
<label for="Sort the Data">Sort the Surveys:
  <select id="Sort" name="title">
 <option value="1">Ascending Sort</option>

 <option value="0">Descending Sort</option>

    <input type="text" name="search" placeholder="Search.." value="<?php echo $search;?>"/>
    <input type="submit" value="Search"/>

</form>


<?php
if(isset($search)) {

    require("common.inc.php");
    $query = file_get_contents(__DIR__ . "/queries/SEARCH_TABLE_SURVEY.sql");
    if (isset($query) && !empty($query)) {
        try {
            $stmt = getDB()->prepare($query);

            $stmt->execute([":survey"=>$search]);

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>

<?php if(isset($results) && count($results) > 0):?>
    <p>Results</p>
    <ul>

        <?php foreach($results as $row):?>
            <li>
                <?php echo get($row, "title")?>

            </li>
        <?php endforeach;?>
    </ul>
<?php else:?>
    <p>Results</p>
<?php endif;?>
