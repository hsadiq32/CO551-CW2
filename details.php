<?php
include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");
$message = "";
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
          if(strlen($studentid) != 8){$id_syntax_flag = true;}
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
       if(!$null_flag && !$id_syntax_flag && !$image_flag && !$dob_flag){
         // build an sql statment to update the student details
         $sql = "update student set firstname ='" . $_POST['firstname'] . "',";
         $sql .= "lastname ='" . $_POST['lastname']  . "',";
         $sql .= "dob ='" . $_POST['dob']  . "',";
         $sql .= "house ='" . $_POST['house']  . "',";
         $sql .= "town ='" . $_POST['town']  . "',";
         $sql .= "county ='" . $_POST['county']  . "',";
         $sql .= "country ='" . $_POST['country']  . "',";
         $sql .= "postcode ='" . $_POST['postcode']  . "'";
         if($imagedata != 0){
            $sql .= ", image = " .  $imagedata . " ";
         }
         else{
            $sql .= " ";
         }
         $sql .= "where studentid = '" . $_POST['studentid'] ."';";
         $result = mysqli_query($conn,$sql);
         if($_SESSION['id'] == $studentid){
            $_SESSION['firstname'] = $_POST['firstname'];
            $_SESSION['lastname'] = $_POST['lastname'];
            $_SESSION['image'] = $imagedata;
         }
         $message = "Details have been updated";
       }
       else{
         $error = "Error: ";
         $error .= errorParser("Fields are not Fully Populated", $null_flag);
         $error .= errorParser("Student ID not 8 Digits", $id_syntax_flag);
         $error .= errorParser("Invalid Image", $image_flag);
         $error .= errorParser("Age Must be 16+", $dob_flag);
         $error = substr($error, 0, -2)."."; // fix punctuation
         // output error in HTML form
         $message=$error;
      }

   }
      // Build a SQL statment to return the student record with the id that
      // matches that of the session variable.
      $sqlQuery = $_SESSION['id'];
      $htmlHeading = "My";
      $switchVerify = false;
      if(isset($_GET["id"])){
         $sqlQuery = $_GET["id"]; // can switch between personal and external details
         $switchVerify = true;
      };

      $sql = "select * from student where studentid='". $sqlQuery . "';";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result);

      if($switchVerify && $sqlQuery != $_SESSION['id']){
         $htmlHeading = $row['firstname']."'s"; // custom HTML content for viewing other students data
      }

      $linkbuilder = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

      // using <<<EOD notation to allow building of a multi-line string
      // see http://stackoverflow.com/questions/6924193/what-is-the-use-of-eod-in-php for info
      // also http://stackoverflow.com/questions/8280360/formatting-an-array-value-inside-a-heredoc
      $data['content'] = <<<EOD
   <div class="container"
   style="max-width: 600px;margin-top:50px;margin-bottom:50px;">
   <div class="card back-light border-outline" style="border-radius: 8px;box-shadow: 0px 0px 20px rgba(0,0,0,0.1);">
      <div class="card-body" style="padding-top: 0px;">
         <h4 class="card-title" style="text-align: center;margin-top: 40px;margin-bottom: 30px;"><b>$htmlHeading Details</b></h4>
         <form name="frmdetails" enctype="multipart/form-data" action="$linkbuilder" method="post">
         <div class="back-dark border-outline" style="display: flex;flex-direction: column;width: 50%;min-width: 150px;padding: 15px;border-radius: 6px;margin: auto;">
            <img id="userImg" src='getimg.php?id={$row['studentid']}' style='width:120px;height:120px;object-fit: cover;border-radius:6px;margin: auto; margin-bottom: 6px;' 
            onError="this.onerror=null;this.src='img/image_placeholder.png'">
            <input name="image" id="fileinput" type="file" accept="image/png, image/jpeg" style="display:none;"/>
            <label for="fileinput" class="btn btn-secondary">Upload Image</label>
         </div>
            <div class="row">
               <div class="col-sm">
               <label>First Name</label>
               <input name="firstname" type="text" value="{$row['firstname']}" class="form-control input-text back-dark" placeholder="First Name">
               </div>
               <div class="col-sm">
               <label>Last Name</label>
               <input name="lastname" type="text" value="{$row['lastname']}" class="form-control input-text back-dark" placeholder="Last Name">
               </div>
            </div>
            <div class="row">
               <div class="col-sm">
               <label>D.O.B.</label>
               <input name="dob" type="date" value="{$row['dob']}" class="form-control input-text back-dark" placeholder="Date of Birth">
               </div>
               <div class="col-sm">
               <label>Address</label>
               <input name="house" value="{$row['house']}" class="form-control input-text back-dark" type="text" placeholder="Address">
               </div>
            </div>
            <div class="row">
               <div class="col-sm">
               <label>Postcode</label>
               <input name="postcode" value="{$row['postcode']}" class="form-control input-text back-dark" type="text" placeholder="Postcode">
               </div>
               <div class="col-sm">
               <label>Town</label>
               <input name="town" value="{$row['town']}" class="form-control input-text back-dark" type="text" placeholder="Town">
               </div>
            </div>
            <div class="row">
               <div class="col-sm">
               <label>County</label>
               <input name="county" value="{$row['county']}" class="form-control input-text back-dark" type="text" placeholder="County">
               </div>
               <div class="col-sm">
               <label>Country</label>
               <input name="country" value="{$row['country']}" class="form-control input-text back-dark" type="text" placeholder="Country">
               </div>
            </div>
            <label>Student ID</label>
            <input name="studentid" class="form-control input-text back-light" type="text" value="{$row['studentid']}" readonly>
            <div class="d-flex content-align-center">
               <input type="submit" value="Submit" name="submit" class="btn btn-success" style="margin-bottom: 10px;margin-top:30px;margin-left:auto;margin-right:auto;"/>
            </div>
         </form>
         <p style="text-align:center">$message</p>
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
