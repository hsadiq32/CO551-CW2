<?php
  header("Content-type: image/jpeg");
  
  include("_includes/config.inc");
  include("_includes/dbconnect.inc");
  include("_includes/functions.inc");

  
  $sql = "SELECT image FROM student WHERE studentid='" . $_GET["id"] ."';";
	
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  
  $jpg = $row["image"];

  echo $jpg;
?>