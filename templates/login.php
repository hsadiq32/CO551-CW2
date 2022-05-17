<div class="container"
   style="max-width: 600px;margin: 0;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">
   <div class="card back-light border-outline" style="border-radius: 8px;box-shadow: 0px 0px 20px rgba(0,0,0,0.1);">
      <div class="card-body" style="text-align: center;padding-top: 0px;">
         <h4 class="card-title" style="text-align: center;margin-top: 45px;margin-bottom: 30px;"><b>Login</b></h4>
         <form name="frmLogin" action="authenticate.php" method="post">
            <input name="txtid" class="form-control back-dark input-text" style="margin-bottom: 20px;" type="text" placeholder="Student ID">
            <input name="txtpwd" class="form-control back-dark input-text" type="password" placeholder="Password">
            <p>
               <?php echo $message; ?>
            </p>
            <button name="btnlogin" type="submit" class="btn btn-success" style="margin-bottom: 20px;margin-top: 10px;">Submit</button>
         </form>
      </div>
   </div>
</div>