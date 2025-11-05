<?php
session_start();
if(!isset($_SESSION['email'])){
    header("location:../login/log in.php");
    exit();
}

include("../db.php");

$email = $_SESSION['email'];

// Fetch user info
$user_query = $conn->prepare("SELECT Name, Email, Phone, Profile FROM employee WHERE Email=? LIMIT 1");
$user_query->bind_param("s", $email);
$user_query->execute();
$user_result = $user_query->get_result();

if($user_result->num_rows > 0){
    $user = $user_result->fetch_assoc();
    $name = $user['Name'];
    $phone = $user['Phone'];
    $profile_pic = $user['Profile'] ? $user['Profile'] : "../images/default_profile.png";
} else {
    $name = "User";
    $phone = "";
    $profile_pic = "../images/default_profile.png";
}

// Handle profile picture upload
if(isset($_POST['update_profile'])){
    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_name = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target_dir = "../images/profiles/";
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . $file_name;

        if(move_uploaded_file($file_tmp, $target_file)){
            // Update DB
            $update = $conn->prepare("UPDATE employee SET Profile=? WHERE Email=?");
            $update->bind_param("ss", $target_file, $email);
            $update->execute();

            $profile_pic = $target_file;
            $msg = "Profile updated successfully!";
        } else {
            $msg = "Failed to upload image.";
        }
    } else {
        $msg = "No image selected or upload error.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <link rel="stylesheet" href="../style/Heading.css">
    <style>
        form{
            margin:0px 12px 0px 12px;
            width:85%;
        }
        input{
            height: 24px;
        }
        body { 
        background-color:#F5F3F3;
        margin-top:-30;
        padding:0; 
        }
        .container { 
            max-width:500px; 
            margin:5px auto; 
            background:white; 
            padding:20px; 
            border-radius:12px; 
            position:relative; 
        }
        h2 { 
            text-align:center; 
            margin-bottom:20px; 
        }
        .profile-pic-container { 
            position:relative; 
            width:120px; 
            height:120px; 
            margin:0 auto 20px; 
        }
        .profile-pic { 
            width:120px; 
            height:120px;
            border-radius:50%; 
            object-fit:cover; 
            display:block; 
        }
        .upload-btn { 
            position:absolute; 
            bottom:0; 
            right:0; 
            background:#2563eb; 
            color:white; border:none; 
            padding:6px 6px; 
            border-radius:50%; 
            cursor:pointer; 
            font-size:0.8rem; 
            width:30px;
            height:30px;
        }
        .upload-btn svg{
            width:18px;
            height:18px;
        }
        .form-group { 
            margin-bottom:15px; 
        }
        .form-group label { 
            display:block; 
            margin-bottom:5px;
        }
        .form-group input { 
            width:100%;
            padding:8px;
            border:1px solid #ccc; 
            border-radius:6px; 
        }
        button.submit-btn { 
            padding:10px 20px; 
            background:#2563eb; 
            color:white; 
            border:none; 
            border-radius:6px; 
            cursor:pointer; 
        }
        button.submit-btn:hover { 
            background:#1e4fc1; 
        }
        .msg { 
            text-align:center; 
            margin-bottom:15px; 
            color:green; 
        }
        .back { 
            display:block; 
            margin-top:15px; 
            text-align:center; 
        }
        .logout-button { 
            display:block; 
            margin:0px auto; 
            text-align:center; 
            padding:8px 12px; 
            background-color: rgba(239,24,24,0.2);
            color: rgba(239,24,24,0.9);
            border: 1px solid rgba(239,24,24,0.9); 
            text-decoration:none; 
            border-radius: 6px; 
            font-size: 0.9rem; 
            width:fit-content; 
        }
        .logout-button:hover { 
            background-color: rgba(239,24,24,0.4); 
        }

        /* Modal */
        .modal { 
            display:none; 
            position:fixed; 
            z-index:100; 
            left:0; top:0; 
            width:100%; 
            height:100%; 
            background:rgba(0,0,0,0.5); 
            justify-content:center; 
            align-items:center; 
        }
        .modal-content { 
            background:white; 
            padding:20px; 
            border-radius:12px; 
            width:300px; 
            text-align:center; 
            position:relative; 
        }
        .close { 
            position:absolute; 
            top:10px; right:15px; 
            font-size:1.2rem; 
            cursor:pointer; 
        }
        #top{
        display:flex;
        align-items:center; 
    }
    #profile{
        width:100%;
        text-align:center;
    }
    </style>
</head>
<body>

<div class="container">
    <div id="top">
        <a href="Dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
        <div id="profile">
            <h3>My Profile</h3>
        </div>
    </div>

    <?php if(isset($msg)){ echo "<p class='msg'>{$msg}</p>"; } ?>

    <div class="profile-pic-container">
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic">
        <button class="upload-btn" onclick="openModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></g></svg>
        </button>
    </div>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" value="<?php echo htmlspecialchars($name); ?>" readonly>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="text" value="<?php echo htmlspecialchars($email); ?>" readonly>
        </div>

        <div class="form-group">
            <label>Phone:</label>
            <input type="text" value="<?php echo htmlspecialchars($phone); ?>" readonly>
        </div>
    </form>

    
    <a href="../login/logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
</div>

<!-- Modal for uploading new image -->
<div id="uploadModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Upload New Profile Picture</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="profile_pic" accept="image/*" required>
            <br><br>
            <button type="submit" name="update_profile" class="submit-btn">Upload</button>
        </form>
    </div>
</div>

<script>
function openModal(){
    document.getElementById('uploadModal').style.display = 'flex';
}
function closeModal(){
    document.getElementById('uploadModal').style.display = 'none';
}
// Close modal on outside click
window.onclick = function(event){
    let modal = document.getElementById('uploadModal');
    if(event.target == modal){
        modal.style.display = 'none';
    }
}
</script>

</body>
</html>
