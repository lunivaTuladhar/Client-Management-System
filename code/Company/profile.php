<?php
    session_start();
    if(!isset($_SESSION['email'])){
        header("location:../login/log in.php");
        exit();
    }

    include("../db.php");

    $email = $_SESSION['email'];
    $company_id= $_SESSION['company_id'];
    // Fetch employee info
    $employee_query = $conn->prepare("SELECT Emp_ID, Name, DOB, Email, Phone, Address, Profile, Password FROM employee WHERE Email=? LIMIT 1");
    $employee_query->bind_param("s", $email);
    $employee_query->execute();
    $employee_result = $employee_query->get_result();
    
    if($employee_result->num_rows > 0){
        $employee = $employee_result->fetch_assoc();
    
        $id = $employee['Emp_ID'];
        $DOB = $employee['DOB'];
        $name = $employee['Name'];
        $phone = $employee['Phone'];
        $address = $employee['Address']; 
        $db_password = $employee['Password'];
        $profile_pic = !empty($employee['Profile']) ? $employee['Profile'] : "";
    } else {
        $name = "User";
        $phone = "";
        $address = "";
        $profile_pic = "";
    }

    // Update profile info
    if(isset($_POST['save_profile'])){
        $new_name = $_POST['name'];
        $new_phone = $_POST['phone'];
        $new_address = $_POST['address'];
        $new_date = $_POST['DOB'];
        $update_info = $conn->prepare("UPDATE employee SET Name=?, Phone=?, Address=?,DOB=? WHERE Emp_ID=?");
        $update_info->bind_param("ssssi", $new_name, $new_phone, $new_address,$new_date, $id);
        $update_info->execute();
        
        $name = $new_name;
        $phone = $new_phone;
        $address = $new_address;
        $DOB = $new_date;
        $msg = "Profile updated successfully!";
    }

    // Handle profile picture upload
    if(isset($_POST['update_profile_pic'])){
        
        if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
            $file_tmp = $_FILES['profile_pic']['tmp_name'];
            $file_name = time() . "_" . basename($_FILES['profile_pic']['name']);
            $target_dir = "../images/profiles/";
            if(!is_dir($target_dir)){
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . $file_name;

            if(move_uploaded_file($file_tmp, $target_file)){
                $update_pic = $conn->prepare("UPDATE employee SET Profile=? WHERE Emp_ID=?");
                $update_pic->bind_param("si", $target_file, $id);
                $update_pic->execute();
$update_pic = $conn->prepare("UPDATE company SET Logo=? WHERE Company_ID=?");
                $update_pic->bind_param("si", $target_file, $company_id);
                $update_pic->execute();
                $profile_pic = $target_file;
                $msg = "Profile picture updated!";
            }
        }
    }

    // Change password
    if(isset($_POST['change_password'])){
        $old = $_POST['old_password'];
        $new = $_POST['new_password'];

        if($old == $db_password){
            $update_pass = $conn->prepare("UPDATE employee SET Password=? WHERE Emp_ID=?");
            $update_pass->bind_param("si", $new, $id);
            $update_pass->execute();
            $msg = "Password updated successfully!";
        } else {
            $msg = "Old password is incorrect!";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title> Profile</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">

    <style>
        body { background:#F5F3F3; margin:0; padding:30px; }
        .container {
            max-width:500px;
            margin:auto;
            background:white;
            padding:20px;
            border-radius:12px;
        }
        .profile-pic-container {
            width:120px; height:120px; margin:auto; position:relative;
        }
        .profile-pic { width:120px; height:120px; border-radius:50%; object-fit:cover; }
        
        input {
            width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; margin-bottom:12px;
        }
        
       
        .msg{ text-align:center; color:green; }
        
        .close { float:right; cursor:pointer; }
        #bottom{
            left:0;
            gap:12px;
            
        }
        .logout-button{
            width:50%;
            
        }
        #logout:hover{
            background-color:rgba(239,24,24,0.4);

        }
        #logout{
            background:rgba(239,24,24,0.2);
            color:#EF1818;
            border :1px solid #EF1818; 
        }
        #form{
            padding-right:12px;
        }
    </style>

</head>
<body>

<div class="container">
 <a href="Dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
      
    <?php if(isset($msg)){ echo "<p class='msg'>$msg</p>"; } ?>

    <div class="profile-pic-container">
        <?php if(  empty($profile_pic)):?>
        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' class="profile-pic"><g fill='currentColor' fill-rule='evenodd' clip-rule='evenodd'><path d='M16 9a4 4 0 1 1-8 0a4 4 0 0 1 8 0m-2 0a2 2 0 1 1-4 0a2 2 0 0 1 4 0'/><path d='M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11s11-4.925 11-11S18.075 1 12 1M3 12c0 2.09.713 4.014 1.908 5.542A8.99 8.99 0 0 1 12.065 14a8.98 8.98 0 0 1 7.092 3.458A9 9 0 1 0 3 12m9 9a8.96 8.96 0 0 1-5.672-2.012A6.99 6.99 0 0 1 12.065 16a6.99 6.99 0 0 1 5.689 2.92A8.96 8.96 0 0 1 12 21'/></g></svg>
            <?php else:?>
        <img src="<?php echo $profile_pic; ?>" class="profile-pic">
        <?php endif;?>
    </div>
    <div id="form">
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" id="name" value="<?php echo $name; ?>" disabled>

        <label>Email</label>
        <input type="text" value="<?php echo $email; ?>" disabled>

        <label>Phone</label>
        <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" disabled>
        
        <label>DOB</label>
        <input type="date" name="DOB" id="DOB" value="<?php echo $DOB; ?>" disabled>

        <label>Address</label>
        <input type="text" name="address" id="address" value="<?php echo $address; ?>" disabled>

        
    </form>
<div id="bottom">
 <a href="../login/logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?')"><button id="logout">Logout</button></a>
</div>
    </div>

</body>
</html>

