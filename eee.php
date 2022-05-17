

students.php
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

addstudent.php

<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

$data['content'] = "";

// function for checking student id
function id_checker ($con, $value) {
   $query = mysqli_query($con, "SELECT * FROM student WHERE studentid=".$value);
   if(mysqli_num_rows($query) > 0){
      return true;
  }
  else{
     return false;
  }
}

// check logged in
if (isset($_SESSION['id'])) {

   echo template("templates/partials/header.php");
   echo template("templates/partials/nav.php");

   // if the form has been submitted
   if (isset($_POST['submit'])) {
      //add verification
      $null_flag = false;
      $input_collection = array(
         $_POST['firstname'], 
         $_POST['lastname'], 
         $_POST['dob'], 
         $_POST['password'], 
         $_POST['house'], 
         $_POST['town'], 
         $_POST['county'], 
         $_POST['country'], 
         $_POST['postcode']
      );
      foreach ($input_collection as $input) {
         if (empty($input)) {
            $null_flag = true;
            break; // no need to keep looking if null found
          }
       }
       
       if(!$null_flag){
         $conflict_flag = false;
         $studentid = $_POST['studentid'];
          if($studentid){
            if (id_checker ($conn, $studentid)) { // Checks if ID is already used
               $conflict_flag = true;
            }
          }
          else
          {
             while(true){
                $studentid = mt_rand(10000000,99999999); // if ID is empty, generate unique 8 digit ID
                 if (!id_checker ($conn, $studentid)) {
                    break;
                 }
             }
          }
          if(!$conflict_flag){
            $image = $_FILES['image']['tmp_name']; 
            $imagedata = "'".addslashes(fread(fopen($image, "r"), filesize($image)))."'";
            //hash password
            $hashed_password = "'".password_hash($_POST['password'], PASSWORD_DEFAULT)."'";
            // build an sql statment to insert the student details
            $sql = "INSERT INTO student (
                        studentid, 
                        firstname, 
                        lastname, 
                        dob, 
                        password, 
                        house, 
                        town, 
                        county, 
                        country, 
                        postcode,
                        image
                        )
                  VALUES (
                      $studentid,
                     '$_POST[firstname]', 
                     '$_POST[lastname]', 
                     '$_POST[dob]', 
                      $hashed_password, 
                     '$_POST[house]', 
                     '$_POST[town]', 
                     '$_POST[county]', 
                     '$_POST[country]', 
                     '$_POST[postcode]',
                      $imagedata
                     )"; 
   
            $result = mysqli_query($conn,$sql);
   
            $data['content'] = "<p style='color:green'>Success: $_POST[firstname] $_POST[lastname]'s record has been added <a href=\"students.php?highlight=$studentid\">View Here</a></p>";
            // redirect to student page
          }
          else{
             // conflict error
             $data['content'] = "<p style='color:red'>Error: Student ID Conflict</p>";
          }
       }
       else{
          // null error
          $data['content'] = "<p style='color:red'>Error: Fields are not fully populated</p>";
       }
   }
   $data['content'] .= <<<EOD

   <h2>Add New Student</h2>
   <form enctype="multipart/form-data" action="$_SERVER[PHP_SELF]" method="post">
   Student ID :
   <input name="studentid" type="text" minlength="8" maxlength="8"/><br/>
   Student Image :
   <input  type="file" name="image" accept="image/jpeg" /><br/>
   First Name :
   <input name="firstname" type="text" minlength="2" required/><br/>
   Surname :
   <input name="lastname" type="text" minlength="2" required/><br/>
   D.O.B. :
   <input name="dob" type="date" required/><br/>
   Password :
   <input name="password" type="password" minlength="6" required/><br/>
   Number and Street :
   <input name="house" type="text" required/><br/>
   Town :
   <input name="town" type="text"  required/><br/>
   County :
   <input name="county" type="text" required/><br/>
   Country :
   <input name="country" type="text" required/><br/>
   Postcode :
   <input name="postcode" type="text" required/><br/>
   <input type="submit" value="Save" name="submit"/>
   </form>

EOD;
   // render the template
   echo template("templates/default.php", $data);

} else {
   header("Location: index.php");
}
echo template("templates/partials/footer.php");
?>