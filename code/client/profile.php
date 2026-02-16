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
        
        input {
            width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; margin-bottom:12px;
        }
        
        
        .msg{ text-align:center; color:green; }
        
        .close { float:right; cursor:pointer; }
        #bottom{
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
    </style>

</head>
<body>

<div class="container">
 <a href="Dashboard.php"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path fill="currentColor" fill-rule="evenodd" d="M10 19.438L8.955 20.5l-7.666-7.79a1.02 1.02 0 0 1 0-1.42L8.955 3.5L10 4.563L2.682 12z"/></svg></a>
      
    <?php if(isset($msg)){ echo "<p class='msg'>$msg</p>"; } ?>

    <div class="profile-pic-container">
        <img src="<?php echo $profile_pic; ?>" class="profile-pic">
    </div>

    <form method="post">
        <label>Name</label>
        <input type="text" name="name" id="name" value="<?php echo $name; ?>" disabled>

        <label>Email</label>
        <input type="text" value="<?php echo $email; ?>" disabled>

        <label>Phone</label>
        <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" disabled>

        <label>Address</label>
        <input type="text" name="address" id="address" value="<?php echo $address; ?>" disabled>


    </form>

    <div id="bottom">
 <a href="../login/logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?')"><button id="logout">Logout</button></a>
</div>
</div>








</body>
</html>
