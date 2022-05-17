<?php
include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");
$message="";
// function for checking student id
function id_checker ($con, $value) {
   $query = mysqli_query($con, "SELECT * FROM student WHERE studentid=".$value);
   if(mysqli_num_rows($query) > 0){return true;}
   else{return false;}
}
function errorParser ($message, $condition) {
   if($condition){return $message.", ";}
   else{return "";}
}
// check logged in
if (isset($_SESSION['id'])) {
   echo template("templates/partials/header.php");
   echo template("templates/partials/nav.php");
   // if the form has been submitted
   if (isset($_POST['submit'])) {
      // verification flags
      $null_flag = false;
      $conflict_flag = false;
      $id_syntax_flag = false;
      $image_flag = false;
      $dob_flag = false;

      $studentid = $_POST['studentid'];
      $image = $_FILES['image']['tmp_name']; 
      $imagedata = 0;
      $dob = new DateTime($_POST['dob']);
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
         if (empty($input)) {$null_flag = true; break;} // no need to keep looking if null found
       }
       if($studentid){
         if (id_checker ($conn, $studentid)) {$conflict_flag = true;}// Checks if ID is already used
         else if(strlen($studentid) != 8){$id_syntax_flag = true;}
       }
       else if(!$null_flag){
          while(true){
             $studentid = mt_rand(10000000,99999999); // if ID is empty, generate unique 8 digit ID
             if (!id_checker ($conn, $studentid)) {break;}
          }
       }
       if($image){
         switch (mime_content_type($image)) {
            case "image/jpeg":
            case "image/png": // allows only jpeg and pngs
               $imagedata = "'".addslashes(fread(fopen($image, "r"), filesize($image)))."'";
               break;
            default:
               $image_flag = true;
        }
       }
       if ($dob && (new DateTime())->diff($dob)->y < 16){$dob_flag = true;}
       if(!$null_flag && !$conflict_flag && !$id_syntax_flag && !$image_flag && !$dob_flag){
         //hash password
         $hashed_password = "'".password_hash($_POST['password'], PASSWORD_DEFAULT)."'";
         // build an sql statment to insert the student details
         $sql = "INSERT INTO student (studentid, firstname, lastname, dob, 
         password, house, town, county, country, postcode, image)
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
         // output success and link redirect to student page
         $data['content'] = "<p style='color:green'>
         Success: $_POST[firstname] $_POST[lastname]'s record has been added 
         <a href=\"students.php?highlight=$studentid\">View Here</a></p>";
       }
       else{
          $error = "Error: ";
          $error .= errorParser("Fields are not Fully Populated", $null_flag);
          $error .= errorParser("Student ID Conflict", $conflict_flag);
          $error .= errorParser("Student ID not 8 Digits", $id_syntax_flag);
          $error .= errorParser("Invalid Image", $image_flag);
          $error .= errorParser("Age Must be 16+", $dob_flag);
          $error = substr($error, 0, -2)."."; // fix punctuation
          // output error in HTML form
          $message=$error;
       }
   }
   $data['content'] .= <<<EOD
   <div class="container"
   style="max-width: 600px;margin-top:50px;margin-bottom:50px;">
   <div class="card back-light border-outline" style="border-radius: 8px;box-shadow: 0px 0px 20px rgba(0,0,0,0.1);">
      <div class="card-body" style="padding-top: 0px;">
         <h4 class="card-title" style="text-align: center;margin-top: 40px;margin-bottom: 30px;"><b>Add New Student</b></h4>
         <form enctype="multipart/form-data" action="$_SERVER[PHP_SELF]" method="post">
            <div class="row">
               <div class="col-sm">
               <div class="back-dark border-outline" style="display: flex;flex-direction: column;border-radius: 6px;padding: 15px;">
                  <img id="userImg" src='' style='width:120px;height:120px;object-fit: cover;border-radius:6px;margin: auto;
                  margin-bottom: 6px;' 
                  onError="this.onerror=null;this.src='img/image_placeholder.png'">
                  <input name="image" id="fileinput" type="file" accept="image/png, image/jpeg" style="display:none;"/>
                  <label for="fileinput" class="btn btn-secondary">Upload Image</label>
               </div>
               </div>
               <div class="col-sm my-auto">
                  <label>Student ID</label>
                  <input name="studentid"class="form-control input-text back-dark" type ="text" maxlength="8" pattern="\d{8}" title="ID Must be 8 Digits" placeholder="Student ID"/>
                  <label>Password</label>
                  <input name="password"class="form-control input-text back-dark" type="password" minlength="6" placeholder="Password" required/>
               </div>
            </div>
            <div class="row">
               <div class="col-sm">
               <label>First Name</label>
               <input name="firstname" type="text" class="form-control input-text back-dark" minlength="2" placeholder="First Name" required/>
               </div>
               <div class="col-sm">
               <label>Last Name</label>
               <input name="lastname" type="text" class="form-control input-text back-dark" minlength="2" placeholder="Last Name" required/>
               </div>
            </div>
            <div class="row">
               <div class="col-sm">
               <label>D.O.B.</label>
               <input name="dob" type="date" class="form-control input-text back-dark" placeholder="Date of Birth" required/>
               </div>
               <div class="col-sm">
               <label>Address</label>
               <input name="house"class="form-control input-text back-dark" type="text" placeholder="Address" required/>
               </div>
            </div>
            <div class="row">
               <div class="col-sm">
               <label>Postcode</label>
               <input name="postcode" class="form-control input-text back-dark" type="text" placeholder="Postcode" required/>
               </div>
               <div class="col-sm">
               <label>Town</label>
               <input name="town" class="form-control input-text back-dark" type="text" placeholder="Town" required/>
               </div>
            </div>
            <div class="row">
               <div class="col-sm">
               <label>County</label>
               <input name="county" class="form-control input-text back-dark" type="text" placeholder="County" required/>
               </div>
               <div class="col-sm">
               <label>Country</label>
               <input name="country" class="form-control input-text back-dark" type="text" placeholder="Country" required/>
               </div>
            </div>
            <div class="d-flex content-align-center">
               <button type="submit" name="submit" class="btn btn-success" style="margin-bottom: 10px;margin-top:30px;margin-left:auto;margin-right:auto;">Submit</button>
            </div>
         </form>
         <p style="text-align:center;color:red;">$message</p>
      </div>
   </div>
</div>
<script src="js/image.js"></script>
EOD;
   // render the template
   echo template("templates/default.php", $data);
} else {
   header("Location: index.php");
}
echo template("templates/partials/footer.php");
?>