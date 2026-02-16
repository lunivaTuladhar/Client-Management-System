<?php
session_start();
if(!isset($_SESSION['email'])){
    header("location:../login/log in.php");
    exit();
}

include("../db.php");

$email = $_SESSION['email'];

// Fetch client info
$client_query = $conn->prepare("SELECT Client_ID, Name, Email, Phone, Address, Profile, Password FROM client WHERE Email=? LIMIT 1");
$client_query->bind_param("s", $email);
$client_query->execute();
$client_result = $client_query->get_result();

if($client_result->num_rows > 0){
    $client = $client_result->fetch_assoc();
    $id = $client['Client_ID'];
    $name = $client['Name'];
    $phone = $client['Phone'];
    $address = $client['Address'];
    $db_password = $client['Password'];
    $profile_pic = $client['Profile'] ? $client['Profile'] : "../images/default_company.png";
} else {
    $name = "User";
    $phone = "";
    $address = "";
    $profile_pic = "../images/default_company.png";
}

// Update profile info
if(isset($_POST['save_profile'])){
    $new_name = $_POST['name'];
    $new_phone = $_POST['phone'];
    $new_address = $_POST['address'];

    $update_info = $conn->prepare("UPDATE client SET Name=?, Phone=?, Address=? WHERE Client_ID=?");
    $update_info->bind_param("sssi", $new_name, $new_phone, $new_address, $id);
    $update_info->execute();

    $name = $new_name;
    $phone = $new_phone;
    $address = $new_address;
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
            $update_pic = $conn->prepare("UPDATE client SET Profile=? WHERE Client_ID=?");
            $update_pic->bind_param("si", $target_file, $id);
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
        $update_pass = $conn->prepare("UPDATE client SET Password=? WHERE Client_ID=?");
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
    <title>Client Profile</title>
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
        .upload-btn {
            position:absolute; bottom:0; right:0;width:30px;
            background:#2563eb; color:white;
            padding:6px; border-radius:100%; cursor:pointer;
        }
        input {
            width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; margin-bottom:12px;
        }
        .edit-btn,.save-btn,.cancel-btn,.password-btn {
            width:100%; 
            padding:10px; 
            border:none; 
            border-radius:6px; 
            cursor:pointer; 
            margin-top:10px;
        }
        .edit-btn{ 
            width:150px;
            color:#fff; 
            margin-bottom:12px;
        }
        .save-btn{ 
            color:#fff; 
            display:block; /* Set to block so it shows by default */
            background-color: #2563eb; /* Ensure it has color since it was hidden before */
        }
        .cancel-btn{ 
            background-color:rgba(239,24,24,0.2);
            color:#EF1818;
            border :1px solid #EF1818;  
            display:none; 
            margin-bottom:12px;
        }
        .cancel-btn:hover{
            background-color:rgba(239,24,24,0.4);
        }
        .password-btn{ 
            color:#fff; 
            border-radius: 12px;
            width:50%;
            margin:0;
            background-color: #64748b;
        }
        .msg{ text-align:center; color:green; }
        .modal {
            display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.6); justify-content:center; align-items:center;
        }
        .modal-content {
            background:white; padding:20px; border-radius:12px; width:300px;
        }
        .close { float:right; cursor:pointer; }
        #bottom{
            right:0;
            display:flex;
            gap:12px;
            margin-top: 20px;
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
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>

</head>
<body>

<div class="container">
 <a href="Dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
      
    <?php if(isset($msg)){ echo "<p class='msg'>$msg</p>"; } ?>

    <div class="profile-pic-container">
        <img src="<?php echo $profile_pic; ?>" class="profile-pic">
        <button class="upload-btn" onclick="openPicModal()">✎</button>
    </div>

    <form method="post" onsubmit="return validateForm()">
        <label>Name</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">

        <label>Email</label>
        <input type="text" value="<?php echo htmlspecialchars($email); ?>" disabled>

        <label>Phone</label>
        <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>">

        <label>Address</label>
        <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($address); ?>">

        <button type="submit" class="save-btn" name="save_profile" id="saveBtn">Save Changes</button>
    </form>

    <div id="bottom">
        <button class="password-btn" onclick="openPassModal()">Change Password</button>
        <a href="../login/logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?')"><button id="logout">Logout</button></a>
    </div>
</div>

<div id="picModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePicModal()">&times;</span>
        <h3>Upload Profile Picture</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="profile_pic" required>
            <button type="submit" name="update_profile_pic" class="save-btn" style="display:block; margin-top:10px;">Upload</button>
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
            <button type="submit" name="change_password" class="save-btn" style="display:block; margin-top:10px;">Update</button>
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
    let email =document.getElementById("email").value;
    let phone = document.getElementById("contact").value;
    let password = document.getElementById("password").value;

    let namePattern = /^[A-Za-z ]+$/;
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let phonePattern = /^[0-9]{10}$/;

    if (!namePattern.test(name)) {
        alert("Name must contain only letters and spaces");
        return false;
    }

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }

    if (!phonePattern.test(phone)) {
        alert("Phone number must be exactly 10 digits");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long");
        return false;
    }

    return true;
}
</script>

</body>
</html>