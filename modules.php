<?php

   include("_includes/config.inc");
   include("_includes/dbconnect.inc");
   include("_includes/functions.inc");


   // check logged in
   if (isset($_SESSION['id'])) {

      echo template("templates/partials/header.php");
      echo template("templates/partials/nav.php");

      // Build SQL statment that selects a student's modules
      $sql = "select * from studentmodules sm, module m where m.modulecode = sm.modulecode and sm.studentid = '" . $_SESSION['id'] ."';";

      $result = mysqli_query($conn,$sql);

      // prepare page content
      $data['content'] .= <<<EOD
      <div class="container"
      style="max-width: 600px;margin-top:50px;margin-bottom:50px;">
      <div class="card back-light border-outline" style="border-radius: 8px;box-shadow: 0px 0px 20px rgba(0,0,0,0.1);">
         <div class="card-body" style="padding-top: 0px;">
            <h4 class="card-title" style="text-align: center;margin-top: 40px;margin-bottom: 30px;"><b>My Modules</b></h4>
     EOD;
      $data['content'] .= "<table class ='table table-dark table-bordered'>";
      $data['content'] .= "<tr><th>Code</th><th>Type</th><th>Level</th></tr>";
      // Display the modules within the html table
      while($row = mysqli_fetch_array($result)) {
         $data['content'] .= "<tr><td> $row[modulecode] </td><td> $row[name] </td>";
         $data['content'] .= "<td> $row[level] </td></tr>";
      }
      $data['content'] .= "</table>";
      $data['content'] .= <<<EOD

</div>
</div>
</div>
EOD;

      // render the template
      echo template("templates/default.php", $data);

   } else {
      header("Location: index.php");
   }

   echo template("templates/partials/footer.php");

?>
