<nav class="navbar navbar-dark navbar-expand-md bg-dark py-3 back-light" style="border-bottom: 1px solid rgba(255,255,255,0.27) ;">
   <div class="container"><a class="navbar-brand d-flex align-items-center" href="index.php" style="margin-left: 4px;">
   <img src='img/home.svg' alt='Home'></a><button data-bs-toggle="collapse" class="navbar-toggler"
         data-bs-target="#navcol-5">
         <span class="visually-hidden">Toggle navigation</span><span
            class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navcol-5">
         <ul class="navbar-nav ms-auto">
            <li class="nav-item" style="margin: 5px;">
            <a class="nav-link" href="students.php">Students</a>
         </li>
            <li class="nav-item" style="margin: 5px;">
            <a class="nav-link" href="assignmodule.php">Assign Module</a>
         </li>
            <li class="nav-item dropdown dropdown-mobile back-dark" style="border-radius: 15px;">
               <a class="nav-link d-flex justify-content-between" aria-expanded="false" data-bs-toggle="dropdown"
                  href="#" style="color: rgba(0,0,0,0.55);height: 50px;padding: 0px;">
                  <button class="btn btn-secondary nav-link" type="button"
                     style="border-radius: 8px;padding-left: 10px;padding-right: 10px;background: rgb(37,37,37);width: 100%;padding-top: 8px;">
                     <?php echo $_SESSION['id'] ?>
                     <img style="height: 35px;width: 35px;margin-left: 6px;margin-top: -2px;border-radius: 33px;"
                     src="getimg.php?id=<?php echo $_SESSION['id'] ?>"
                     onError="this.onerror=null;this.src='img/image_placeholder.png'">
                  </button>
               </a>
               <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark back-dark"
                  style="border-radius: 6px;border-color: rgba(152,152,152,0.6);margin-top: 5px;">
                  <p style="text-align: center;font-weight: bold;color: grey;margin-top: 10px;"><?php echo $_SESSION['firstname']." ".$_SESSION['lastname']  ?></p>
                  <a class="dropdown-item" href="details.php">My Details</a>
                  <a class="dropdown-item" href="modules.php">My Modules</a>
                  <a class="dropdown-item" href="logout.php">Logout</a>
               </div>
            </li>
            <li class="nav-item"></li>
         </ul>
      </div>
   </div>
</nav>