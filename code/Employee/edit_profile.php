<?php
session_start();
if(!isset($_SESSION['email'])){
    header("location:../login/log in.php");
    exit();
}

include("../db.php");

$email = $_SESSION['email'];

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
    $profile_pic = $employee['Profile'] ?: "";
}

// Update profile info
if(isset($_POST['save_profile'])){
    $new_name = $_POST['name'];
    $new_phone = $_POST['phone'];
    $new_address = $_POST['address'];
    $new_date = $_POST['DOB'];

    $update_info = $conn->prepare("UPDATE employee SET Name=?, Phone=?, Address=?, DOB=? WHERE Emp_ID=?");
    $update_info->bind_param("ssssi", $new_name, $new_phone, $new_address, $new_date, $id);
    
    if($update_info->execute()){
        $name = $new_name;
        $phone = $new_phone;
        $address = $new_address;
        $DOB = $new_date;
        $msg = "Profile updated successfully!";
    }
}

// Handle profile picture upload
if(isset($_POST['update_profile_pic'])){
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $file_name = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target_dir = "../images/profiles/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $target_file = $target_dir . $file_name;
        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)){
            $update_pic = $conn->prepare("UPDATE employee SET Profile=? WHERE Emp_ID=?");
            $update_pic->bind_param("si", $target_file, $id);
            $update_pic->execute();
            $profile_pic = $target_file;
            $msg = "Profile picture updated!";
        }
    }
}

// Change password - FIXED LOGIC
if(isset($_POST['change_password'])){
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    if($old === $db_password){
        // Fixed column name from employee_ID to Emp_ID
        $update_pass = $conn->prepare("UPDATE employee SET Password=? WHERE Emp_ID=?");
        $update_pass->bind_param("si", $new, $id);
        if($update_pass->execute()){
            $msg = "Password updated successfully!";
        }
    } else {
        $msg = "Old password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Profile</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
        body { background:#F5F3F3; margin:0; padding:30px; font-family: sans-serif; }
        .container { max-width:500px; margin:auto; background:white; padding:20px; border-radius:12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .profile-pic-container { width:120px; height:120px; margin:auto; position:relative; margin-bottom: 20px; }
        .profile-pic { width:120px; height:120px; border-radius:50%; object-fit:cover; border: 2px solid #2563eb; }
        .upload-btn { position:absolute; bottom:0; right:0; width:30px; height:30px; background:#2563eb; color:white; border:none; border-radius:100%; cursor:pointer; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-bottom:15px; box-sizing: border-box; }
.save-btn,.password-btn {
            width:100%; 
            padding:10px; 
            border:none; 
            border-radius:12px; 
            cursor:pointer; 
            margin-top:10px;
        }        .save-btn{ 
            width:50%;
            color:#fff; 
            margin:0;
        }
        
        .password-btn{ 
            color:#fff; 
            border-radius: 12px;
            width:50%;
            margin:0;
        }
        .msg { text-align:center; color: #16a34a; font-weight: bold; margin-bottom: 15px; }
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index: 1000; }
        .modal-content { background:white; padding:25px; border-radius:12px; width:320px; }
        .close { float:right; cursor:pointer; font-size: 20px; }
        #bottom { display:flex; justify-content: space-between; gap:10px; margin-top:10px; }
    </style>
</head>
<body>

<div class="container">
 <a href="Dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
    
    <?php if(isset($msg)){ echo "<p class='msg'>$msg</p>"; } ?>

    <div class="profile-pic-container">
        <?php if(empty($profile_pic)): ?>
            <div class="profile-pic" style="background:#ddd; display:flex; align-items:center; justify-content:center;">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="#999"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
        <?php else: ?>
            <img src="<?php echo $profile_pic; ?>" class="profile-pic">
        <?php endif; ?>
        <button class="upload-btn" onclick="openPicModal()">✎</button>
    </div>

    <form method="post" onsubmit="return validateForm()">
        <label>Name</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>

        <label>Email </label>
        <input type="text" id="email" value="<?php echo htmlspecialchars($email); ?>" disabled>

        <label>Phone</label>
        <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>">
        
        <label>DOB</label>
        <input type="date" name="DOB" id="DOB" value="<?php echo $DOB; ?>">

        <label>Address</label>
        <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($address); ?>">

        <div id="bottom">
            <button type="submit" class="save-btn" name="save_profile">Save Changes</button>
            <button type="button" class="password-btn" onclick="openPassModal()">Change Password</button> 
        </div>
    </form>
</div>

<div id="picModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePicModal()">&times;</span>
        <h3>Update Picture</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="profile_pic" required>
            <button type="submit" name="update_profile_pic" class="save-btn" style="width:100%">Upload</button>
        </form>
    </div>
</div>

<div id="passModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePassModal()">&times;</span>
        <h3>Change Password</h3>
        <form method="post">
            <input type="password" name="old_password" placeholder="Old Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" name="change_password" class="save-btn" style="width:100%">Update Password</button>
        </form>
    </div>
</div>

<script>
function openPicModal(){ document.getElementById("picModal").style.display = "flex"; }
function closePicModal(){ document.getElementById("picModal").style.display = "none"; }
function openPassModal(){ document.getElementById("passModal").style.display = "flex"; }
function closePassModal(){ document.getElementById("passModal").style.display = "none"; }

function validateForm() {
    let name = document.getElementById("name").value;
    let phone = document.getElementById("phone").value;

    let namePattern = /^[A-Za-z ]+$/;
    let phonePattern = /^[0-9]{10}$/;

    if (!namePattern.test(name)) {
        alert("Name must contain only letters and spaces");
        return false;
    }

    if (phone !== "" && !phonePattern.test(phone)) {
        alert("Phone number must be exactly 10 digits");
        return false;
    }
    return true;
}
</script>
</body>
</html>