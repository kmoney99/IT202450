<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once(__DIR__."/partials/header.partial.php");
require("common.inc.php"(__DIR__."/includes/common.inc.php"));

$query = file_get_contents(__DIR__ . "/sql/queries/SELECT_ALL_SURVEY.sql");

if(isset($query) && !empty($query));
	
	try {
		$stmt = $common->getDB()->prepare($query);
		$stmt -> execute();
		$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	catch (Exception $e) {
		echo $e->getMessage();
	}
	
	
	
	if(isset($results)) {
		foreach($results as $row) {
			echo get($row, "title");
			
		}
		
	}
	else {
		echo "No Surveys at this time.";
	}
?>

<link rel="stylesheet" type="text/css" href="style.css">