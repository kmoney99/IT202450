<?php
include_once(__DIR__."/partials/header.partial.php");
?>

<?php
$search = "";
if(isset($_POST["search"])){
    $search = $_POST["search"];
}
?>
<html><head><link 
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../home.php">Home</a>
            </li>
			<li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../search.php">Search</a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../create_questionnaire.php">Create Custom Survey</a>
                </li>
            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../surveys.php">Surveys</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../TakenSurveys.php">Surveys you've Taken</a>
            </li>
                            <li class="nav-item">
                <a class="nav-link" style="
    color: black;
    font-weight: bold;
	margin: 10px;
	" href="/Project/includes/../logout.php">Logout</a>
            </li>
            </ul>
</nav>
<div id="messages">
        </div>
<link rel="stylesheet" type="text/css" href="sty.css">
<h3 style="
    text-align: center;
    margin: 0 auto;
    margin-top: 3rem;
"> Search for survey question </h3>
<form method="POST">
<label for="Sort the Data" style="
    margin-top: 5rem;
    margin-left: 43rem;
    font-weight: bolder;
">Sort the Surveys:
  <select id="Sort" name="title">
 <option value="query" "select="" *="" from="" `survey`="" order="" by="" `title`="" asc"="">Ascending Sort</option>
 
 <option value="query" "select="" *="" from="" `survey`="" order="" by="" `title`="" desc"="">Descending Sort</option>

    </select><input type="text" name="search" placeholder="Search.." value=""> 
    <input type="submit" value="Search">




</label></form></body></html>


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
    <p>No Results</p>
<?php endif;?>