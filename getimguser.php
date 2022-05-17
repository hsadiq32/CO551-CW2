<?php
  header("Content-type: image");
  include("_includes/config.inc");
  include("_includes/dbconnect.inc");
  include("_includes/functions.inc");
  // Use student id to locate image
  echo $_SESSION['image'];
?>