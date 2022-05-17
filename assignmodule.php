<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");


// check logged in
if (isset($_SESSION['id'])) {

   echo template("templates/partials/header.php");
   echo template("templates/partials/nav.php");

   // If a module has been selected
   if (isset($_POST['selmodule'])) {
      $sql = "insert into studentmodules values ('" .  $_SESSION['id'] . "','" . $_POST['selmodule'] . "');";
      $result = mysqli_query($conn, $sql);
      $data['content'] .= "<p>The module " . $_POST['selmodule'] . " has been assigned to you</p>";
   }
   else  // If a module has not been selected
   {

     // Build sql statment that selects all the modules
     $sql = "select * from module";
     $result = mysqli_query($conn, $sql);

     $data['content'] .= <<<EOD
            <div class="container"
            style="max-width: 600px;margin-top:50px;margin-bottom:50px;">
            <div class="card back-light border-outline" style="border-radius: 8px;box-shadow: 0px 0px 20px rgba(0,0,0,0.1);">
               <div class="card-body" style="padding-top: 0px;">
                  <h4 class="card-title" style="text-align: center;margin-top: 40px;margin-bottom: 30px;"><b>Assign Module</b></h4>
                  <form name='frmassignmodule' action='' method='post' >
           EOD;
     $data['content'] .= "<label>Select a module to assign</label>";
     $data['content'] .= "<select name='selmodule' class='form-select back-dark border-outline' style='color:white;' >";
     // Display the module name sin a drop down selection box
     while($row = mysqli_fetch_array($result)) {
        $data['content'] .= "<option value='$row[modulecode]'>$row[name]</option>";
     }
     $data['content'] .= "</select><br/>";
     $data['content'] .= '<div class="d-flex content-align-center">';
     $data['content'] .= "<input class='btn btn-success' type='submit' name='confirm' value='Submit' style='margin-bottom: 10px;margin-top:30px;margin-left:auto;margin-right:auto;' />";
     $data['content'] .= <<<EOD
            </div>
         </form>
      </div>
      </div>
      </div>
      EOD;
   }

   // render the template
   echo template("templates/default.php", $data);

} else {
   header("Location: index.php");
}

echo template("templates/partials/footer.php");

?>
