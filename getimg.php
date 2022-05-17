<?php
  header("Content-type: image");
  include("_includes/config.inc");
  include("_includes/dbconnect.inc");
  include("_includes/functions.inc");
  // Use student id to locate image
  $sql = "SELECT image FROM student WHERE studentid='" . $_GET["id"] ."';";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  $img = $row["image"];
  echo $img; // output image
?>