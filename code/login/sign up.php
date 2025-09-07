<?php
require_once "db.php";
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'] ;
    $contact  = $_POST['contact'] ;
    $email    = $_POST['email'] ;
    $password = $_POST['password'] ;
    $hasOrg   = isset($_POST['checkbox']) ? 1 : 0;
    if ($hasOrg) {
        // Save data into SESSION instead of DB
        $_SESSION['name'] =  $name;
        $_SESSION['contact'] =  $contact;
        $_SESSION['email'] =  $email;
        $_SESSION['password'] =  $password;
        
        // Redirect to company registration page
        header("Location: register company.php");
        exit();
    } else {
        // Insert into client table directly
        $sql = "INSERT INTO client (name, password, email, phone) 
                VALUES ('$name', '$password', '$email', '$contact')";
        if ($conn->query($sql) === TRUE) {
            header("Location: log in.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../style/button.css">
    <link rel="stylesheet" href="../style/form.css">
    <title>Sign up</title>
</head>
<body style="margin-top:3%; margin-bottom:3%;">
    <form align="center" method="post">
        <h2>SIGN UP</h2>
    <div style="display: flex; width: 100%; gap: 10%;">
        <div align="left" style="width: 45%;">
            
        <label>Name</label><br>
        <input type="text" placeholder="Enter your Name" name="name" id="name"><br>
        <label>Contact No.</label><br>
        <input type="tel" placeholder="Enter your Contact no."name="contact" id="contact"><br>
        </div>

        <div align="left" style="width: 45%;">
        <label>Email</label><br>
        <input type="email" placeholder="Enter your Email"name="email" id="email"><br>
        <label>Password</label><br>
        <input type="password" placeholder="Enter your Password" name="password" id="password"><br>

        
        </div>
    </div>
    <div style="text-align: left;">
        <input type="checkbox" style="width: auto;" id="checkbox" name="checkbox">Do you have an organization?
    </div>
        <button type="submit">Sign Up</button>
        <p>Already have an account?<a href="log in.html">Log In</a></p>
</form>
</body>
</html>
