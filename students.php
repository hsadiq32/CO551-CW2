<?php

   include("_includes/config.inc");
   include("_includes/dbconnect.inc");
   include("_includes/functions.inc");


   // check logged in
   if (isset($_SESSION['id'])) {

      echo template("templates/partials/header.php");
      echo template("templates/partials/nav.php");

      // Build SQL statment that selects a student's modules
      
      $sql = "select * from student";

      $result = mysqli_query($conn,$sql);

      $data['content'] .= "<form action='deletestudents.php' method='POST'>";

      // prepare page content
      $data['content'] .= "<table border='1'>";
      $data['content'] .= "<tr>";
      $data['content'] .= "<th>Student ID</th>";
      $data['content'] .= "<th>Image</th>";
      $data['content'] .= "<th>First Name</th>";
      $data['content'] .= "<th>Last Name</th>";
      $data['content'] .= "<th>D.O.B.</th>";
      $data['content'] .= "<th>House</th>";
      $data['content'] .= "<th>Town</th>";
      $data['content'] .= "<th>County</th>";
      $data['content'] .= "<th>Country</th>";
      $data['content'] .= "<th>Postcode</th>";
      $data['content'] .= "<th>Checkbox</th>";
      $data['content'] .= "</tr>";
      // Display the modules within the html table
      while($row = mysqli_fetch_array($result)) {
         $style="";
         if(isset($_GET["highlight"])){
            if($_GET["highlight"] == $row['studentid']){
               $style="style='background: aqua;'";
            }
         }
         $data['content'] .= "<tr $style>";
         $data['content'] .= "<td><a href=\"details.php?id=$row[studentid]\">$row[studentid]</a></td>";
         $data['content'] .= "<td><img src='getjpg.php?id=$row[studentid]' style='max-width:50px' onError=\"this.onerror=null;this.src='img/image_placeholder.png'\"></td>";
         $data['content'] .= "<td> $row[firstname] </td>";
         $data['content'] .= "<td> $row[lastname] </td>";
         $data['content'] .= "<td> $row[dob] </td>";
         $data['content'] .= "<td> $row[house] </td>";
         $data['content'] .= "<td> $row[town] </td>";
         $data['content'] .= "<td> $row[county] </td>";
         $data['content'] .= "<td> $row[country] </td>";
         $data['content'] .= "<td> $row[postcode] </td>";
         $data['content'] .= "<td><input type='checkbox' name='students[]' value='$row[studentid]' /></td>";
         $data['content'] .= "</tr>";
      }
      $data['content'] .= "</table>";


      $data['content'] .= "<input type='submit' name='deletebtn' value='Delete'>";
      $data['content'] .= "</form>";

      $data['content'] .= "<input type='submit' value='Add Student' onclick=\"location.href='addstudent.php'\">";
      // render the template
      echo template("templates/default.php", $data);

   } else {
      header("Location: index.php");
   }

   echo template("templates/partials/footer.php");

?>
