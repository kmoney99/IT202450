<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once(__DIR__."/partials/header.partial.php");

 $sql = "SELECT * FROM SURVEY;";
 $result = mysqli_query($connection_string, $sql);
 $result_check = mysqli_num_rows($result);
 
 if ($result_check > 0) {
	 while ($row = mysqli_fetch_assoc($result)) {
		 echo $row['title'];
	 
 }
 
 }

?>