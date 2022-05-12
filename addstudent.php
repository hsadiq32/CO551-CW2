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