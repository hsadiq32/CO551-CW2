<?php
   include("_includes/config.inc");
   include("_includes/dbconnect.inc");
   include("_includes/functions.inc");
   // check logged in
   if (isset($_SESSION['id'])) {

    if($_POST['students']){

        foreach ($_POST['students'] as $id) {
            $sql = "delete from student where studentid=".$id;
            mysqli_query($conn,$sql);
          }
    }
      header("Location: students.php"); // optimised fallback for redirection
   } else {
      header("Location: index.php");
   }
?>