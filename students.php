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
      $data['content'] .= "<div class='container panel panel-default' style='margin-top:20px'>";
      $data['content'] .= "<div class='back-light d-flex justify-content-between'
      style='text-align: center;margin-top: 20px;border-top-left-radius: 6px;border-top-right-radius: 6px;padding: 10px;'>";
      $data['content'] .= "<button class='btn btn-success' onclick=\"location.href='addstudent.php'\"><img src='img/add.svg' alt='Add'></button>";
      $data['content'] .= "<p style='margin-top: 7px;margin-bottom: -10px;'>Student Database</p>";
      $data['content'] .= "<button class='btn btn-danger' onclick=\"promptToggler()\" ><img src='img/delete.svg' alt='Delete'></button>";
      $data['content'] .= "</div>";
      $data['content'] .= "<form id='studentForm' action='deletestudents.php' method='POST' class='table-responsive'>";
      // prepare page content
      $data['content'] .= "<table class ='table table-dark table-bordered'>";
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
         if(isset($_GET["highlight"]) && $_GET["highlight"] == $row['studentid']){
            $style="style='--bs-table-bg: #174e12;'";
         }
         $data['content'] .= "<tr $style>";
         $data['content'] .= "<td>
         <a class='btn btn-secondary' style='width:110px' href=\"details.php?id=$row[studentid]\">
         $row[studentid]</a></td>";
         $data['content'] .= "<td style='text-align:center;'>
         <img src='getimg.php?id=$row[studentid]' style='width:38px;height:38px;object-fit: cover;border-radius:6px' 
         onError=\"this.onerror=null;this.src='img/image_placeholder.png'\"></td>";
         $data['content'] .= "<td> $row[firstname] </td>";
         $data['content'] .= "<td> $row[lastname] </td>";
         $data['content'] .= "<td> $row[dob] </td>";
         $data['content'] .= "<td> $row[house] </td>";
         $data['content'] .= "<td> $row[town] </td>";
         $data['content'] .= "<td> $row[county] </td>";
         $data['content'] .= "<td> $row[country] </td>";
         $data['content'] .= "<td> $row[postcode] </td>";
         $data['content'] .= "<td>
         <input type='checkbox' name='students[]' value='$row[studentid]' /></td>";
         $data['content'] .= "</tr>";
      }
      $data['content'] .= "</table>";
      $data['content'] .= "</form>";
      $data['content'] .= "</div>";
      $data['content'] .= '<script type="text/javascript" src="js/prompt.js"></script>';
      // render the template
      echo template("templates/default.php", $data);
   } else {
      header("Location: index.php");
   }
   echo template("templates/partials/footer.php");
?>