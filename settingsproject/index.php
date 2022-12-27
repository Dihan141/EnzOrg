<?php
   $conn = new mysqli('localhost','root','','spl');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Settings</title>
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="./styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<?php
   session_start();
   $user_id = $_SESSION['user_id'];
   if(isset($_POST['update_profile']))
   {
      $username = $_POST['update_name'];
      $email = $_POST['update_email'];

      mysqli_query($conn, "UPDATE `users` SET username = '$username', email = '$email' WHERE id = '$user_id'") or die('query failed');
      
      $old_pass = $_POST['old_pass'];
      $update_pass = md5($_POST['update_pass']);
      $temp_pass = $_POST['new_pass'];
      $new_pass = md5($_POST['new_pass']);
      $confirm_pass = md5($_POST['confirm_pass']);
   
      if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)){
         if($update_pass != $old_pass){
            $message[] = 'old password not matched!';
         }elseif($new_pass != $confirm_pass){
            $message[] = 'confirm password not matched!';
         }else if(strlen($temp_pass) < 8 || ctype_upper($temp_pass) || ctype_lower($temp_pass)){
            $message[] = "Password must be atleast 8 character long and contain uppercase and lowercase";
         }else{
            mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('query failed');
            $message[] = 'password updated successfully!';
         }
      }

      $image = $_FILES['update_image']['name'];
      $imageSize = $_FILES['update_image']['size'];
      $imageTempName = $_FILES['update_image']['tmp_name'];
      $imageFolder = 'upload/'. $image;
   
      if(!empty($image))
      {
         if($imageSize > 2000000)
            $message[] = "Image is too large";
         else
         {
            $imageUpdateQuery = mysqli_query($conn, "UPDATE users SET image = '$image' WHERE id = '$user_id'") or die("query failed");
         
            if($imageUpdateQuery)
            {
               move_uploaded_file($imageTempName, $imageFolder);
            }
            $message[] = "Image updated successfully";   
         }
      }
   }
?>

<body>
<?php
    $message = '';

    if(isset($_SESSION['username']))
        $message = $_SESSION['username'];
?>
<section class="container">
   
    <!-- side nav bar begin here -->
    <div class="sidebar">
    <div class="logo-details">
      <!-- <i class='bx bxl-c-plus-plus icon'></i> -->
        <div class="logo_name">EnzOrg</div>
        <i class='bx bx-menu' id="btn" ></i>
    </div>
    <ul class="nav-list">
      <!-- <li>
          <i class='bx bx-search' ></i>
         <input type="text" placeholder="Search...">
         <span class="tooltip">Search</span>
      </li> -->
      <li>
        <a href="#">
          <i class='bx bx-grid-alt'></i>
          <span class="links_name">Dashboard</span>
        </a>
         <span class="tooltip">Dashboard</span>
      </li>
      <li>
       <a href="../profile/index.php">
         <i class='bx bx-user' ></i>
         <span class="links_name">My Profile</span>
       </a>
       <span class="tooltip">My Profile</span>
     </li>
     <li>
       <a href="#">
         <i class='bx bx-task' ></i>
         <span class="links_name">My Tasks</span>
       </a>
       <span class="tooltip">My Tasks</span>
     </li>
     <!-- <li>
       <a href="#">
         <i class='bx bx-pie-chart-alt-2' ></i>
         <span class="links_name">Analytics</span>
       </a>
       <span class="tooltip">Analytics</span>
     </li>
     <li>
       <a href="#">
         <i class='bx bx-folder' ></i>
         <span class="links_name">File Manager</span>
       </a>
       <span class="tooltip">Files</span>
     </li>
     <li>
       <a href="#">
         <i class='bx bx-cart-alt' ></i>
         <span class="links_name">Order</span>
       </a>
       <span class="tooltip">Order</span>
     </li> -->
     <li>
       <a href="#">
         <i class='bx bx-support'></i>
         <span class="links_name">Help & Support</span>
       </a>
       <span class="tooltip">Help & Support</span>
     </li>
     <li>
       <a href="#">
         <i class='bx bxs-contact'></i>
         <span class="links_name">Contact</span>
       </a>
       <span class="tooltip">Contact</span>
     </li>
     
    </ul>
  </div>
<!-- add section here-->
  <script>
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");
  let searchBtn = document.querySelector(".bx-search");

  closeBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("open");
    menuBtnChange();
  });

  searchBtn.addEventListener("click", ()=>{ // Sidebar open when you click on the search iocn
    sidebar.classList.toggle("open");
    menuBtnChange(); 
  });


  function menuBtnChange() {
   if(sidebar.classList.contains("open")){
     closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
   }else {
     closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the iocns class
   }
  }
  </script>




    <!--  nav bar begins here -->
    <nav>
        <!-- <div class="title__name">Dash Board</div> -->
        <div class="nav-links">
            <ul>
                <!-- elements of nav bar  -->
                <li><a href="../Homepage/index.php">HOME</a></li>
                <li><a href="../login/index.php">LOG OUT</a></li>
                <!-- Write profile name here -->
                <?php echo '<li><a href="../profile/index.php">'.$message.'</a></li>';?>
                <li><a href="../about/index.php">ABOUT</a></li>
                <li><a href="#">CONTACT</a></li>
            </ul>
        </div>
    </nav>

<div class="update-profile">
<!-- php code user information -->

   <?php

      $select = mysqli_query($conn, "SELECT * FROM USERS WHERE id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select) > 0)
      {
         $fetch = mysqli_fetch_assoc($select);
      }
   ?>

   <form action="" method="POST" enctype="multipart/form-data">
      

      
      <div class="flex">
         <div class="inputBox">
            <span>Update Project Name :</span>
            <!-- put values here from database -->
            <input type="text" name="update_name" value="<?php echo $fetch['username'];?>" class="box">
            <span>Update Project Description :</span>
            <input type="email" name="update_email" value="<?php echo $fetch['email'];?>" class="box">
            <span>Update Priority :</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
            <span>Update Status :</span>
            <input type="password" name="update_pass" class="box">
            <span>Update Due Date :</span>
            <input type="password" name="new_pass"  class="box">
            <span>Confirm Password :</span>
            <input type="password" name="confirm_pass"  class="box">
         </div>
      </div>
      <div class="buttons">
         <input type="submit" value="Update Project" name="update_profile" class="btn">
      </div>
      <div class="buttons">
      <a href="../projectpageadmin/index.php"><input type="button" value="Go Back" name="go_back" class="btn"></a>
      </div>
   </form>

</div>
</section>

</body>
</html>